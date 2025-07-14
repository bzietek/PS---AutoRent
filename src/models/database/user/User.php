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
        [['name', 'surname', 'role'], 'string', 'max' => 40,
            'tooLong' => '{attribute} może mieć maksymalnie 40 znaków.'],
        [['phone_number'], 'string', 'max' => 12,
            'tooLong' => 'Numer telefonu może mieć maksymalnie 12 znaków.'],
        [['password'], 'string', 'max' => 150,
            'tooLong' => 'Hasło może mieć maksymalnie 150 znaków.'],
        [['id', 'email', 'phone_number'], 'unique',
            'message' => '{attribute} już istnieje w bazie.'],
        [['active'], 'boolean'],
        ['role', 'in', 'range' => array_keys(Role::getRoles()),
            'message' => 'Nieprawidłowa rola użytkownika.'],
        ['email', 'email', 'message' => 'Nieprawidłowy adres e-mail.'],
        [['phone_number'], PhoneNumberValidator::class],
        [
            'password',
            'compare',
            'compareAttribute' => 'confirmPassword',
            'on' => [self::SCENARIO_SIGNUP, self::SCENARIO_EDIT_SELF],
            'message' => 'Hasła muszą być identyczne.',
        ],
        ['password', 'string', 'min' => 8, 'max' => 20, 'on' => self::SCENARIO_SIGNUP,
            'tooShort' => 'Hasło musi mieć co najmniej 8 znaków.',
            'tooLong' => 'Hasło może mieć maksymalnie 20 znaków.'],
        ['password', function ($attribute, $params, $validator) {
            if ($this->password !== $this->getPasswordHash() && strlen($this->password) > 20) {
                $this->addError($attribute, 'Hasło może mieć maksymalnie 20 znaków.');
            }
        }, 'on' => self::SCENARIO_EDIT_SELF],
        ['password', function ($attribute, $params, $validator) {
            if (!preg_match('/[a-z]/', $this->$attribute)) {
                $this->addError($attribute, 'Hasło musi zawierać co najmniej jedną małą literę.');
            }
            if (!preg_match('/[A-Z]/', $this->$attribute)) {
                $this->addError($attribute, 'Hasło musi zawierać co najmniej jedną wielką literę.');
            }
            if (!preg_match('/\d/', $this->$attribute)) {
                $this->addError($attribute, 'Hasło musi zawierać co najmniej jedną cyfrę.');
            }
        }, 'on' => self::SCENARIO_SIGNUP],
        [['confirmPassword'], 'required', 'on' => [self::SCENARIO_SIGNUP],
            'message' => 'Powtórz hasło.'],
        [['oldPassword'], 'required', 'on' => self::SCENARIO_EDIT_SELF,
            'message' => 'Podaj aktualne hasło.'],
        [['oldPassword'], function ($attribute, $params, $validator) {
            if ($this->validatePassword(
                $this->$attribute,
                $this->getPasswordHash()
            ) === false) {
                $this->addError($attribute, 'Nieprawidłowe hasło.');
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
            'name' => 'Imię',
            'surname' => 'Nazwisko',
            'email' => 'Adres e-mail',
            'phone_number' => 'Numer telefonu',
            'password' => 'Hasło',
            'confirmPassword' => 'Powtórz hasło',
            'oldPassword' => 'Aktualne hasło', 
            'active' => 'Czy konto jest aktywne?',
            'rememberMe' => 'Zapamiętaj mnie',
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
