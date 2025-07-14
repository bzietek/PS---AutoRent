<?php

namespace app\models\database\car;

use Yii;

class Car extends \app\models\database\generated\CAR
{
    const SCENARIO_ADD = 'add';

    public function rules(): array
    {
        return array_merge(
            parent::rules(),
            [
                [['brand', 'model', 'year', 'VIN', 'status', 'price_per_day'], 'required', 'message' => 'Pole {attribute} jest wymagane.'],
                [['brand', 'model', 'VIN', 'status'], 'string', 'max' => 40, 'tooLong' => '{attribute} może mieć maksymalnie 40 znaków.'],
                [['price_per_day'], 'number', 'message' => 'Cena musi być liczbą.'],
                [['year'], 'date', 'format' => 'php:Y-m-d', 'message' => 'Podaj datę w formacie RRRR-MM-DD.'],
                [['VIN'], 'unique', 'message' => 'Podany VIN już istnieje w bazie.'],
                [['id'], 'unique', 'message' => 'Podany ID już istnieje w bazie.'],
            ]
        );
    }

    public function scenarios(): array
    {
        return array_merge(
            parent::scenarios(),
            [
                self::SCENARIO_ADD => [
                    'brand',
                    'model',
                    'year',
                    'VIN',
                    'status',
                    'price_per_day',
                    'location_id',
                ],
            ]
        );
    }

    public function attributeLabels(): array
    {
        return array_merge(
            parent::attributeLabels(),
            [
                'brand' => Yii::t('app', 'Marka'),
                'model' => Yii::t('app', 'Model'),
                'year' => Yii::t('app', 'Rok produkcji'),
                'VIN' => Yii::t('app', 'VIN'),
                'status' => Yii::t('app', 'Status'),
                'price_per_day' => Yii::t('app', 'Cena za dzień'),
                'location_id' => Yii::t('app', 'Lokalizacja'),
            ]
        );
    }

    public function beforeSave($insert)
    {
        if (parent::beforeSave($insert)) {
            if (!empty($this->year)) {
                $date = \DateTime::createFromFormat('Y-m-d', $this->year);
                if ($date) {
                    $this->year = $date->format('d-M-Y');
                }
            }
            return true;
        }
        return false;
    }
}
