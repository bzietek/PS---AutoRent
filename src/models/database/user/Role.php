<?php

namespace app\models\database\user;

use Yii;

class Role
{
    const string ROLE_CUSTOMER = 'customer';
    const string ROLE_ADMINISTRATOR = 'admin';
    const string ROLE_SERVICE_ENGINEER = 'service_engineer';

    public static function getRoles() : array {
        return [
            self::ROLE_CUSTOMER => Yii::t('app', 'Customer'),
            self::ROLE_ADMINISTRATOR => Yii::t('app', 'Admin'),
            self::ROLE_SERVICE_ENGINEER => Yii::t('app', 'Service Engineer'),
        ];
    }
}
