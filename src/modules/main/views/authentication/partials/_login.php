<?php

use app\models\database\user\User;
use yii\helpers\Html;
use yii\widgets\ActiveForm;

$this->title = 'Logowanie';

/** @var User $model */
?>

<h1 class="mb-4 text-center">Logowanie</h1>

<?php $form = ActiveForm::begin([
    'id' => 'login-form',
    'fieldConfig' => [
        'template' => "{label}\n{input}\n{error}",
        'labelOptions' => ['class' => 'col-form-label'],
        'inputOptions' => ['class' => 'form-control'],
        'errorOptions' => ['class' => 'text-danger'],
    ],
]); ?>

<?= $form->field($model, 'loginField')->textInput(['placeholder' => 'Email lub numer telefonu'])->label('Email lub numer telefonu') ?>
<?= $form->field($model, 'password')->passwordInput(['placeholder' => 'Hasło'])->label('Hasło') ?>
<?= $form->field($model, 'rememberMe')->checkbox() ?>

<div class="form-group" style="margin-top: 24px;">
    <?= Html::submitButton('Zaloguj się', ['class' => 'btn btn-primary w-100']) ?>
</div>

<?php ActiveForm::end(); ?>
