<?php
use yii\helpers\Html;
use yii\widgets\ActiveForm;

/** @var yii\web\View $this */
/** @var app\models\database\car\Car $model */
/** @var yii\widgets\ActiveForm $form */

$this->title = 'Dodaj samochód';
?>

<h1><?= Html::encode($this->title) ?></h1>

<?php $form = ActiveForm::begin(); ?>

<?= $form->field($model, 'brand')->textInput(['maxlength' => true]) ?>
<?= $form->field($model, 'model')->textInput(['maxlength' => true]) ?>
<?= $form->field($model, 'year')->input('date') ?>
<?= $form->field($model, 'VIN')->textInput(['maxlength' => true]) ?>
<?= $form->field($model, 'status')->dropDownList([
    'available' => 'Dostępny',
    'rented' => 'Wynajęty',
    'maintenance' => 'Serwis',
]) ?>
<?= $form->field($model, 'price_per_day')->textInput(['type' => 'number', 'step' => '0.01']) ?>

<div class="form-group" style="margin-top: 24px;">
    <?= Html::submitButton('Zapisz', ['class' => 'btn btn-success']) ?>
</div>

<?php ActiveForm::end(); ?>
