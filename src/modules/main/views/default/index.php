<?php
/** @var yii\web\View $this */

?>

index
<?php if (Yii::$app->getUser()->isGuest) : ?>
    <div>guest</div>
<?php else: ?>
    <?= Yii::$app->getUser()->getIdentity()->name ?? 'name' ?>
<?php endif; ?>