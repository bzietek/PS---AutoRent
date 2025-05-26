<?php

namespace app\models;

use Yii;

class Role
{
    const string ROLE_CUSTOMER = 'customer';
    const string ROLE_ADMINISTRATOR = 'admin';

    public static function getRoles() : array {
        return [
            self::ROLE_CUSTOMER => Yii::t('app', 'Customer'),
            self::ROLE_ADMINISTRATOR => Yii::t('app', 'Admin'),
        ];
    }
}
