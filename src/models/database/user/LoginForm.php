<?php

namespace app\models\database\user;

use Yii;
use yii\base\Model;

class LoginForm extends Model
{
    public $loginField;
    public $password;
    public $rememberMe;

    private $_user = null;

    public function rules()
    {
        return [
            [['loginField', 'password'], 'required'],
            ['rememberMe', 'boolean'],
            ['password', 'validatePassword'],
        ];
    }

    public function attributeLabels()
    {
        return array_merge(Parent::attributeLabels(),
            [
                'loginField' => \Yii::t('app', 'Email or Phone Number'),
                'password' => \Yii::t('app', 'Password'),
                'rememberMe' => \Yii::t('app', 'Remember me'),
            ]
        ) ;
    }

    public function validatePassword($attribute, $params)
    {
        if (!$this->hasErrors()) {
            $user = $this->getUser();
            if (!$user || !$user->validatePassword($this->password)) {
                $this->addError($attribute, 'Incorrect email/phone number or password.');
            }
        }
    }

    public function login()
    {
        if ($this->validate()) {
            return Yii::$app->user->login($this->getUser(), $this->rememberMe ? 3600*24*30 : 0);
        }
        return false;
    }

    public function getUser() : User|null
    {
        if ($this->_user === null) {
            $this->_user = User::findByEmailOrPhoneNumber($this->loginField);
        }
        return $this->_user;
    }
}
