<?php

namespace app\models\database;

use app\models\database\generated\CARORDER;
use Yii;

class Order extends generated\CARORDER
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
                'name' => Yii::t('app', 'First Name'),
                'date_start' => Yii::t('app', 'Start'),
                'date_end' => Yii::t('app', 'End'),
                'status' => Yii::t('app', 'Status'),
                'price' => Yii::t('app', 'Total price'),
                'used_fuel' => Yii::t('app', 'Used up fuel (liters)'),
                'distance' => Yii::t('app', 'Distance traveled'),
                'placed_at' => Yii::t('app', 'Order was placed at'),
            ]
        );
    }
}