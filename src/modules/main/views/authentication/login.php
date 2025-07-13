<?php

use app\models\database\user\LoginForm;


/** @var yii\web\View $this */
/** @var LoginForm $model */

?>

<?= $this->render('partials/_login', [
    'model' => $model
]); ?>

