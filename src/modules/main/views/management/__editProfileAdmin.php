<?php

use app\models\database\user\Role;
use app\models\database\user\User;
use yii\helpers\Html;
use yii\widgets\ActiveForm;

/** @var User $model */

?>

    <h1><?= Yii::t('app', Yii::$app->user->getId() === $model->id ? 'Edit your profile' : 'Edit profile as Admin'); ?></h1>

<?php
$form = ActiveForm::begin([
    'action' => ["/profile/$model->id"],
    'method' => 'POST',
    'id' => 'edit-profile-form',
    'fieldConfig' => [
        'template' => "{label}\n{input}\n{error}",
        'labelOptions' => ['class' => 'col-lg-1 col-form-label mr-lg-3'],
        'inputOptions' => ['class' => 'col-lg-3 form-control'],
        'errorOptions' => ['class' => 'col-lg-7 text-danger'],
    ],
    'enableClientValidation' => true,
]);
?>

<?= $form->field($model, 'email')->textInput(['autofocus' => true]); ?>
<?= $form->field($model, 'phone_number')->textInput(); ?>
<?= $form->field($model, 'name')->textInput(); ?>
<?= $form->field($model, 'surname')->textInput(); ?>
<?= $form->field($model, 'role')->dropDownList(Role::getRoles()); ?>
<?= $form->field($model, 'active')->checkbox(); ?>

<?= Html::submitButton(Yii::t('app', 'save'), ['class' => 'btn btn-primary']); ?>
<?php ActiveForm::end(); ?>