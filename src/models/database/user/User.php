<?php

namespace app\models\database\user;

use app\models\validators\PhoneNumberValidator;
use Yii;
use yii\web\IdentityInterface;

class User extends \app\models\database\generated\APPUSER implements IdentityInterface
{
    const SCENARIO_SIGNUP = 'signup';
    const SCENARIO_EDIT_SELF = 'self-edit';
    const SCENARIO_EDIT_ADMIN = 'admin-edit';
    const SCENARIO_PROFILE_SHOW = 'show-profile';

    const LIST_COUNT_PER_PAGE_DEFAULT = 20;

    public string $confirmPassword = '';
    public string $oldPassword = '';

    public function rules()
    {
        return [
            [['role'], 'default', 'value' => Role::ROLE_CUSTOMER],
            [['id'], 'integer'],
            [['name', 'surname', 'role'], 'string', 'max' => 40],
            [['phone_number'], 'string', 'max' => 12],
            [['password'], 'string', 'max' => 150],
            [['id', 'email', 'phone_number'], 'unique'],
            [['active'], 'boolean'],
            ['role', 'in', 'range' => array_keys(Role::getRoles())],
            ['email', 'email', 'message' => Yii::t('app', 'Invalid email')],
            [['phone_number'], PhoneNumberValidator::class],
            [
                'password',
                'compare',
                'compareAttribute' => 'confirmPassword',
                'on' => [self::SCENARIO_SIGNUP, self::SCENARIO_EDIT_SELF],
                'message' => Yii::t('app', 'Passwords must match'),
            ],
            ['password', 'string', 'min' => 8, 'max' => 20, 'on' => self::SCENARIO_SIGNUP],
            ['password', function ($attribute, $params, $validator) {
                if ($this->password !== $this->getPasswordHash() && strlen($this->password) > 20) {
                    $this->addError($attribute, 'Password should contain at most 20 characters');
                }
            },
            'on' => self::SCENARIO_EDIT_SELF],
            ['password', function ($attribute, $params, $validator) {
                if (!preg_match('/[a-z]/', $this->$attribute)) {
                    $this->addError($attribute, 'Password must contain at least one lowercase letter.');
                }
                if (!preg_match('/[A-Z]/', $this->$attribute)) {
                    $this->addError($attribute, 'Password must contain at least one uppercase letter.');
                }
                if (!preg_match('/\d/', $this->$attribute)) {
                    $this->addError($attribute, 'Password must contain at least one digit.');
                }
            },
            'on' => self::SCENARIO_SIGNUP
            ],
            [['confirmPassword'], 'required', 'on' => [self::SCENARIO_SIGNUP]],
//                [$this->attributes(), 'filter', 'filter' => [static::class, 'sanitizeCharacters'] ],
            [['oldPassword'], 'required', 'on' => self::SCENARIO_EDIT_SELF],
            [['oldPassword'], function ($attribute, $params, $validator) {
                if ($this->validatePassword(
                    $this->$attribute,
                    $this->getPasswordHash()
                ) === false) {
                    $this->addError($attribute, \Yii::t('app', 'Incorrect password'));
                }
            }, 'on' => self::SCENARIO_EDIT_SELF],
        ];
    }

    /**
     * @throws \Throwable
     */
    public function scenarios()
    {
        return array_merge(
            parent::scenarios(),
            [
                self::SCENARIO_SIGNUP => [
                    'name',
                    'surname',
                    'password',
                    'confirmPassword',
                    'email',
                    'phone_number',
                ],
                self::SCENARIO_EDIT_ADMIN => [
                    'name',
                    'surname',
                    'email',
                    'phone_number',
                    'role',
                    'active',
                ],
                self::SCENARIO_EDIT_SELF => [
                    'name',
                    'surname',
                    'password',
                    'confirmPassword',
                    'email',
                    'phone_number',
                    ...(Yii::$app->user->getIdentity()?->role === Role::ROLE_ADMINISTRATOR
                    ? [
                        'role',
                        'active',
                    ]
                    : [
                        'oldPassword'
                    ]
                    ),
                ],
                self::SCENARIO_PROFILE_SHOW => ['visible_name'],
            ]
        );
    }

    public function attributeLabels(): array
    {
        return array_merge(
            parent::attributeLabels(),
            [
                'name' => Yii::t('app', 'First Name'),
                'surname' => Yii::t('app', 'Last Name'),
                'email' => Yii::t('app', 'Email'),
                'phone_number' => Yii::t('app', 'Phone Number'),
                'password' => Yii::t('app', 'Password'),
                'confirmPassword' => Yii::t('app', 'Confirm Password'),
//                'created_at' => Yii::t('app', 'Account creation date'),
                'active' => Yii::t('app', 'Is account active?'),
                'rememberMe' => Yii::t('app', 'Remember Me'),
            ]
        );
    }

    public function getAuthKey() : string {
        return sha1(
            'ajBt%81,' . $this->id . 'kjsd2'
        );
    }

    public function validateAuthKey($authKey) : bool {
        return $this->getAuthKey() === $authKey;
    }

    public function getId(): int|string
    {
        return $this->id;
    }

    public static function findIdentity($id) : User|null {
        $user = static::findOne($id);
        return $user ?: null;
    }

    public static function findIdentityByAccessToken($token, $type = null): null
    {
        return null;
    }

    public static function findByEmailOrPhoneNumber($field) : User|null {
        return static::findOne(['email' => $field]) ?? static::findOne(['phone_number' => $field]);
    }

    public function validatePassword($password, $passwordHash = null) : bool {
        return Yii::$app->getSecurity()->validatePassword(
            $password,
            $passwordHash ?? $this->password
        );
    }

    public function signUp() : bool
    {
        $commited = false;
        if ($this->validate()) {
            $commited = $this->save();
        }
        if (!$commited) {
            $this->password = $this->confirmPassword;
            $this->confirmPassword = '';
        }
        return $commited;
    }

    public function beforeValidate() : bool
    {

        if (parent::beforeValidate() === false) {
            return false;
        }
        if (is_string($this->name)) {
            $this->name = ucfirst(strtolower(trim($this->name)));
        }

        if (is_string($this->surname)) {
            $this->surname = ucfirst(strtolower(trim($this->surname)));
        }

        if (is_string($this->email)) {
            $this->email = strtolower(trim($this->email));
        }
        return true;
    }

    public function beforeSave($insert) : bool
    {
        if (parent::beforeSave($insert) === false) {
            return false;
        }
        $sanitizeBeforeHashCheckAttributes = array_diff(
            array_keys($this->attributes), ['password', 'confirmPassword']
        );
        foreach ($sanitizeBeforeHashCheckAttributes as $attribute) {
            $this->$attribute = static::sanitizeCharacters($this->$attribute);
        }
        $passwordChanged = $this->password !== $this->getPasswordHash();
        if ($this->email !== null && $this->email !==  $this->getOldAttribute('email')) {
            $this->active = 0;
        }
        if ($this->validate(null, false) === false) {
            return false;
        }
        if ($passwordChanged) {
            $this->password = Yii::$app->getSecurity()->generatePasswordHash($this->password);
        }
        if ($this->active === null) {
            $this->active = 0;
        } else {
            $this->active = (int) $this->active;
        }
        return true;
    }

    public static function getRole($userId) : string|null {
        return static::findOne(['id' => $userId])?->role;
    }

    public function getPasswordHash() : string|null
    {
        try {
            $passHash = static::find()
                ->select('password')
                ->where(['id' => $this->id])
                ->one()->password;
        } catch (\Exception|\Throwable $e) {
            return null;
        }
        return $passHash ?? null;
    }
}
