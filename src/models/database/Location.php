<?php

namespace app\models\database;

use Yii;

class Location extends generated\LOCATION
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
            []
        );
    }
}