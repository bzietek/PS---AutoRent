<?php

namespace app\models\database\car;

use Yii;

class Brand
{
    const string BRAND_AUDI = 'Audi';
    const string BRAND_BMW = 'BMW';
    const string BRAND_CITROEN = 'Citroen';
    const string BRAND_TOYOTA = 'Toyota';
    const string BRAND_NISSAN = 'Nissan';

    public static function getBrand () : array {
        return [
            self::BRAND_AUDI => Yii::t('app', 'Audi'),
            self::BRAND_BMW => Yii::t('app', 'BMW'),
            self::BRAND_CITROEN => Yii::t('app', 'Citroen'),
            self::BRAND_TOYOTA => Yii::t('app', 'Toyota'),
            self::BRAND_TOYOTA => Yii::t('app', 'Nissan'),
        ];
    }
}