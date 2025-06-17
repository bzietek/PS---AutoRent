<?php

namespace app\models\database;

use Yii;

class Car extends generated\CAR
{
    public function rules(): array
    {
        return array_merge(
            parent::scenarios(),
            []
        );
    }

    public function scenarios(): array
    {
        return array_merge(
            parent::scenarios(),
            []
        );
    }

    public function attributeLabels(): array
    {
        return array_merge(
            parent::attributeLabels(),
            [
                'brand' => Yii::t('app', 'Brand'),
                'model' => Yii::t('app', 'Model'),
                'year' => Yii::t('app', 'Production Year'),
                'VIN' => Yii::t('app', 'VIN'),
                'status' => Yii::t('app', 'Status'),
                'price_per_day' => Yii::t('app', 'Cost of renting for a day'),
            ]
        );
    }
}