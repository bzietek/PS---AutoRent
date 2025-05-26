<?php


/** @var yii\web\View $this */
/** @var User $model */


use app\models\database\user\User;

$this->title = 'Register';
$this->params['breadcrumbs'][] = $this->title;

?>

<?= $this->render('partials/_signup', [
    'model' => $model
]);
