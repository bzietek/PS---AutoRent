<?php

namespace app\models\database\user;

use app\models\Role;
use Yii;
use yii\db\ActiveQuery;
use yii\web\IdentityInterface;

class User extends \app\models\database\generated\APPUSER implements IdentityInterface
{
    const SCENARIO_SIGNUP = 'signup';
    const SCENARIO_EDIT_SELF = 'self-edit';
    const SCENARIO_EDIT_ADMIN = 'admin-edit';
    const SCENARIO_PROFILE_SHOW = 'show-profile';

    public string $confirmPassword = '';
    public string $oldPassword = '';

    public function rules()
    {
        return [

            [['modified_at', 'created_at'], 'default', 'value' => null],
            [['role'], 'default', 'value' => ''],
            [['id'], 'integer'],
            [['modified_at', 'created_at'], 'safe'],
            [['username', 'email', 'role'], 'string', 'max' => 40],
            [['password'], 'string', 'max' => 150],
            [['visible_name'], 'string', 'max' => 30],
            [['id'], 'unique'],

            ['role', 'in', 'range' => array_keys(Role::getRoles())],
            ['email', 'email', 'message' => Yii::t('app', 'Invalid email')],
            [
                'password',
                'compare',
                'compareAttribute' => 'confirmPassword',
                'on' => [self::SCENARIO_SIGNUP, self::SCENARIO_EDIT_SELF],
                'message' => Yii::t('app', 'Passwords must match'),
            ],
            ['password', 'string', 'min' => 8, 'max' => 20],
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

    public function scenarios()
    {
        return array_merge(
            parent::scenarios(),
            [
                self::SCENARIO_SIGNUP => [
                    'id',
                    'username',
                    'password',
                    'confirmPassword',
                    'email',
                    'visible_name',
                    'role'
                ],
                self::SCENARIO_EDIT_ADMIN => ['username', 'email', 'visible_name', 'role'],
                self::SCENARIO_EDIT_SELF => [
                    'username',
                    'password',
                    'confirmPassword',
                    'email',
                    'visible_name',
                    ...(Yii::$app->user->getIdentity()?->role === Role::ROLE_ADMINISTRATOR
                    ? ['role']
                    : ['oldPassword']
                    ),
                ],
                self::SCENARIO_PROFILE_SHOW => ['visible_name'],
            ]
        );
    }

    public function attributeLabels()
    {
        return array_merge(
            parent::rules(),
            [
                'username' => Yii::t('app', 'Username'),
                'email' => Yii::t('app', 'Email'),
                'password' => Yii::t('app', 'Password'),
                'visible_name' => Yii::t('app', 'Nickname'),
                'confirmPassword' => Yii::t('app', 'Confirm Password'),
                'created_at' => Yii::t('app', 'Account creation date'),
                'rememberMe' => Yii::t('app', 'Remember Me'),
            ]
        );
    }

    public static function sanitizeCharacters($value)
    {
        return htmlspecialchars($value, ENT_QUOTES | ENT_HTML5, 'UTF-8');
    }

    public function getAuthKey() : string {
        return sha1(
            'ajBt%81,' . $this->id . $this->created_at . $this->username
        );
    }

    public function validateAuthKey($authKey) : bool {
        return $this->getAuthKey() === $authKey;
    }

    public function getId() {
        return $this->id;
    }

    public static function findIdentity($id) : User|null {
        $user = static::findOne($id);
        return $user ?: null;
    }

    public static function findIdentityByAccessToken($token, $type = null)
    {
        return null;
    }

    public static function findByUsernameOrEmail($field) : User|null {
        return static::findOne(['username' => $field]) ?? static::findOne(['email' => $field]);
    }

    public static function getIdsByVisibleNameLike($visibleName) : ActiveQuery|array
    {
        return array_map(function($arg) {
            return $arg?->id;
        }, static::find()->where(['like', 'visible_name', $visibleName])->all());
    }

    public function validatePassword($password, $passwordHash = null) : bool {
        return Yii::$app->getSecurity()->validatePassword(
            $password,
            $passwordHash ?? $this->password
        );
    }

    public function signUp() : bool {
        $commited = false;
        if ($this->validate()) {
            $commited = $this->save();
            if (!$commited) {
                $this->password = $this->confirmPassword;
            }
        }
        if (!$commited) {
            $this->password = $this->confirmPassword;
            $this->confirmPassword = '';
        }
        return $commited;
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
        if ($this->validate(null, false) === false) {
            return false;
        }
        if ($passwordChanged) {
            $this->password = Yii::$app->getSecurity()->generatePasswordHash($this->password);
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

    public function fillWithNonEmptyAttributes(User $updateFromModel) : bool
    {
        foreach ($updateFromModel->attributes as $attribute => $value) {
            if (
                isset($value)
                && is_string($value)
                && strlen($value) > 0
            ) {
                $this->{$attribute} = $value;
            }
        }
        return false;
    }
}
