<?php
/** @var yii\web\View $this */
/** @var string $content */

use app\assets\AppAsset;
use app\models\database\user\Role;
use app\widgets\Alert;
use yii\bootstrap5\Html;
use yii\bootstrap5\Nav;
use yii\bootstrap5\NavBar;

AppAsset::register($this);
$this->registerCssFile('@web/css/site.css');
$this->registerCssFile('@web/css/site.css');

$this->registerCsrfMetaTags();
$this->registerMetaTag(['charset' => Yii::$app->charset], 'charset');
$this->registerMetaTag(['name' => 'viewport', 'content' => 'width=device-width, initial-scale=1, shrink-to-fit=no']);
$this->registerMetaTag(['name' => 'description', 'content' => $this->params['meta_description'] ?? '']);
$this->registerMetaTag(['name' => 'keywords', 'content' => $this->params['meta_keywords'] ?? '']);
$this->registerLinkTag(['rel' => 'icon', 'type' => 'image/x-icon', 'href' => Yii::getAlias('@web/favicon.ico')]);

$this->title = Html::encode(Yii::$app->name);

$this->title = Html::encode(Yii::$app->name);
$isAdmin = Yii::$app->user->getIdentity()?->role === Role::ROLE_ADMINISTRATOR;
$isServiceEngineer = Yii::$app->user->getIdentity()?->role === Role::ROLE_SERVICE_ENGINEER; 

?>
<?php $this->beginPage() ?>
<!DOCTYPE html>
<html lang="<?= Yii::$app->language ?>" class="h-100">
<head>
    <title><?= $this->title ?></title>
    <title><?= $this->title ?></title>
    <?php $this->head() ?>
</head>
<body class="d-flex flex-column h-100 bg-light">
<body class="d-flex flex-column h-100 bg-light">
<?php $this->beginBody() ?>

<header id="header" class="shadow-sm">
<header id="header" class="shadow-sm">
    <?php
        NavBar::begin([
            'brandLabel' => Html::tag('span', Yii::$app->name, ['class' => 'fw-bold']),
            'brandLabel' => Html::tag('span', Yii::$app->name, ['class' => 'fw-bold']),
            'brandUrl' => Yii::$app->homeUrl,
            'options' => ['class' => 'navbar navbar-expand-md navbar-dark bg-dark fixed-top']
            'options' => ['class' => 'navbar navbar-expand-md navbar-dark bg-dark fixed-top']
        ]);
    ?>
    <div class="d-flex justify-content-between w-100">
        <div id="left-nav">
            <?= Nav::widget([
                'options' => ['class' => 'navbar-nav me-auto'],
                'items' => array_merge(
                    [
                        ['label' => 'Start', 'url' => ['/default/index']],
                        ['label' => 'O nas', 'url' => ['/default/about']],
                        ['label' => 'Kontakt', 'url' => ['/default/contact']],
                    ],
                    $isAdmin ? [['label' => 'Użytkownicy', 'url' => ['/management/profiles']]] : [],
                    $isServiceEngineer ? [['label' => 'Dodawanie samochodów', 'url' => ['/car/add']]] : [],
                    $isServiceEngineer ? [['label' => 'Lista samochodów', 'url' => ['/car/index']]] : []
                )
            ]) ?>
        </div>
        <div id="right-nav">
            <?= Nav::widget([
                'options' => ['class' => 'navbar-nav ms-auto'],
                'items' => Yii::$app->user->isGuest
                    ? [
                        ['label' => 'Logowanie', 'url' => ['/login']],
                        ['label' => 'Rejestracja', 'url' => ['/signup']],
                    ]
                    : [
                        [
                            'label' => Yii::$app->user->getIdentity()->name ?? '',
                            'url' => ['/profile/' . Yii::$app->user->getIdentity()->getId()]
                        ],
                        ['label' => 'Wyloguj', 'url' => ['/logout']],
                    ]
            ]) ?>
        </div>
    <?php NavBar::end(); ?>
</header>

<main id="main" class="flex-shrink-0" role="main">
    <div class="container fade-in">
    <div class="container fade-in">
        <?= Alert::widget() ?>
        <?= $content ?>
    </div>
</main>

<footer id="footer" class="bg-dark text-white mt-auto py-4">
<footer id="footer" class="bg-dark text-white mt-auto py-4">
    <div class="container">
        <div class="row small">
            <div class="col-md-6 text-center text-md-start">
                &copy; Auto Rent <?= date('Y') ?>
            </div>
            <div class="col-md-6 text-center text-md-end">
                <a href="https://www.yiiframework.com/" class="text-white-50 text-decoration-none">
                    Powered by Yii
                </a>
            </div>
        <div class="row small">
            <div class="col-md-6 text-center text-md-start">
                &copy; Auto Rent <?= date('Y') ?>
            </div>
            <div class="col-md-6 text-center text-md-end">
                <a href="https://www.yiiframework.com/" class="text-white-50 text-decoration-none">
                    Powered by Yii
                </a>
            </div>
        </div>
    </div>
</footer>

<?php $this->endBody() ?>
</body>
</html>
<?php $this->endPage() ?>
