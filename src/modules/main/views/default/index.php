<?php
/** @var yii\web\View $this */

use app\models\Role;

?>

index
<?php if (Yii::$app->getUser()->isGuest) : ?>
    <div>guest</div>
<?php else: ?>
    <?= Yii::$app->getUser()->getIdentity()->username ?? 'username' ?>
<?php endif; ?>