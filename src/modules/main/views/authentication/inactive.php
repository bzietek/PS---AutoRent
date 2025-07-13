<?php

use app\models\database\user\LoginForm;
use yii\helpers\Html;


/** @var yii\web\View $this */
/** @var LoginForm $model */

?>

<h1>Your account was not activated yet!</h1>
<?php if (YII_ENV === 'dev'): ?>
    Activate the account <a href="/main/authentication/activate">here</a>
<?php endif; ?>


