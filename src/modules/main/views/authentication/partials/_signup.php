<?php

use app\models\database\user\User;
use yii\helpers\Html;
use yii\widgets\ActiveForm;

/** @var User $model */

$form = ActiveForm::begin([
    'action' => ['/signup'],
    'method' => 'POST',
    'id' => 'signup-form',
    'fieldConfig' => [
        'template' => "{label}\n{input}\n{error}",
        'labelOptions' => ['class' => 'col-lg-1 col-form-label mr-lg-3'],
        'inputOptions' => ['class' => 'col-lg-3 form-control'],
        'errorOptions' => ['class' => 'col-lg-7 text-danger'],
    ],
    'enableClientValidation' => false,
]);
?>

<?= $form->field($model, 'name')->textInput(['autofocus' => true]); ?>
<?= $form->field($model, 'surname')->textInput(['autofocus' => true]); ?>
<?= $form->field($model, 'email')->textInput(); ?>
<?= $form->field($model, 'phone_number')->textInput(); ?>
<?= $form->field($model, 'password')->passwordInput(); ?>
<?= $form->field($model, 'confirmPassword')->passwordInput(); ?>

<?= Html::submitButton('Zarejestruj się', ['class' => 'btn btn-primary w-100', 'style' => 'margin-top:24px;']) ?>
<?php ActiveForm::end(); ?>
