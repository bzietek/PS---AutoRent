<?php

namespace app\models;

class BaseActiveRecord extends \yii\db\ActiveRecord
{
    public function fillWithNonEmptyAttributes(BaseActiveRecord $updateFromModel) : bool
    {
        foreach ($updateFromModel->attributes as $attribute => $value) {
            if (
                isset($value)
                && is_string($value)
                && strlen($value) > 0
            ) {
                $this->{$attribute} = $value;
            }
        }
        return false;
    }

    public static function sanitizeCharacters($value): string
    {
        return htmlspecialchars($value ?? '', ENT_QUOTES | ENT_HTML5, 'UTF-8');
    }

}