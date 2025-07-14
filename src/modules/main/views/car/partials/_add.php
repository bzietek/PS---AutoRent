<?php

use app\models\database\car\Car;
use yii\helpers\Html;
use yii\widgets\ActiveForm;

$form = ActiveForm::begin([
    'id' => 'car-add-form',
    'enableClientValidation' => true,
]);

/** @var Car $model */
?>

<?= $form->field($model, 'brand')->textInput(['maxlength' => true]) ?>
<?= $form->field($model, 'model')->textInput(['maxlength' => true]) ?>
<?= $form->field($model, 'year')->input('date') ?>
<?= $form->field($model, 'VIN')->textInput(['maxlength' => true]) ?>
<?= $form->field($model, 'status')->dropDownList([
    'available' => 'Available',
    'rented' => 'Rented',
    'maintenance' => 'Maintenance',
]) ?>
<?= $form->field($model, 'price_per_day')->textInput(['type' => 'number', 'step' => '0.01']) ?>
<?= $form->field($model, 'location_id')->textInput() ?>

<div class="form-group">
    <?= Html::submitButton('Dodaj samochód', ['class' => 'btn btn-primary']) ?>
</div>

<?php ActiveForm::end(); ?>
