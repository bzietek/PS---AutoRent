<?php

namespace app\models\validators;

use yii\validators\Validator;

class PhoneNumberValidator extends Validator
{
    public string $pattern = '/^\+?[0-9]{9,11}$/';

    public function validateAttribute($model, $attribute): void
    {
        $value = $model->$attribute;

        if (!preg_match($this->pattern, $value)) {
            $this->addError(
                $model,
                $attribute,
                'The phone number format is invalid. Use only digits and optionally start with a \'+\' sign'
            );
        }
    }
}