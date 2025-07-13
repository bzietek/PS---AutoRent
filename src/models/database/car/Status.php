<?php

namespace app\models\database\car;

use Yii;

class Status
{
    const string STATUS_AVAILABLE = 'available';
    const string STATUS_MAINTENANCE = 'maintenance';
    const string STATUS_RENTED = 'rented';
    const string STATUS_RESERVED = 'reserved';


    public static function getStatus() : array {
        return [
            self::STATUS_AVAILABLE => Yii::t('app', 'available'),
            self::STATUS_RENTED => Yii::t('app', 'rented'),
            self::STATUS_RESERVED => Yii::t('app', 'reserved'),
            self::STATUS_MAINTENANCE => Yii::t('app', 'maintenance'),
        ];
    }
}