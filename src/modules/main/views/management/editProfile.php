<?php

use app\models\database\user\User;
use yii\web\View;

/** @var View $this */
/** @var User $model */

?>

<?=
$this->render($model->scenario === User::SCENARIO_EDIT_ADMIN ? '__editProfileAdmin' : '__editProfileSelf', [
    'model' => $model,
]);