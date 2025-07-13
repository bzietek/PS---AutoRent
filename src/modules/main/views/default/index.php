<<<<<<< HEAD
<?php

/** @var yii\web\View $this */

use yii\helpers\Html;

$this->title = 'Panel użytkownika';

$user = Yii::$app->user->getIdentity();
?>

<div class="container mt-5 fade-in">
    <div class="mb-4 text-center">
        <h2 class="text-primary">
    👋 Witaj, <?= Html::encode($user->name ?? $user->email ?? 'Użytkowniku') ?>!
        </h2>
        <p class="lead">Zarządzaj swoją aplikacją Auto Rent</p>
    </div>

    <div class="row text-center">
        <div class="col-md-4 mb-4">
            <div class="card shadow-sm">
                <div class="card-body">
                    <h5 class="card-title">Użytkownicy</h5>
                    <p class="card-text">Przeglądaj i zarządzaj kontami</p>
                    <a href="/management/profiles" class="btn btn-outline-primary w-100">Przejdź</a>
                </div>
            </div>
        </div>
        <div class="col-md-4 mb-4">
            <div class="card shadow-sm">
                <div class="card-body">
                    <h5 class="card-title">Lokalizacje</h5>
                    <p class="card-text">Zarządzaj punktami wynajmu</p>
                    <a href="/locations" class="btn btn-outline-primary w-100">Przejdź</a>
                </div>
            </div>
        </div>
        <div class="col-md-4 mb-4">
            <div class="card shadow-sm">
                <div class="card-body">
                    <h5 class="card-title">Flota pojazdów</h5>
                    <p class="card-text">Przeglądaj dostępne auta</p>
                    <a href="/cars" class="btn btn-outline-primary w-100">Przejdź</a>
                </div>
            </div>
        </div>
    </div>
</div>
=======
<?php

/** @var yii\web\View $this */

use yii\helpers\Html;

$this->title = 'Panel użytkownika';

$user = Yii::$app->user->getIdentity();
?>

<div class="container mt-5 fade-in">
    <div class="mb-4 text-center">
        <h2 class="text-primary">👋 Witaj, <?= Html::encode($user->name) ?>!</h2>
        <p class="lead">Zarządzaj swoją aplikacją Auto Rent</p>
    </div>

    <div class="row text-center">
        <div class="col-md-4 mb-4">
            <div class="card shadow-sm">
                <div class="card-body">
                    <h5 class="card-title">Użytkownicy</h5>
                    <p class="card-text">Przeglądaj i zarządzaj kontami</p>
                    <a href="/management/profiles" class="btn btn-outline-primary w-100">Przejdź</a>
                </div>
            </div>
        </div>
        <div class="col-md-4 mb-4">
            <div class="card shadow-sm">
                <div class="card-body">
                    <h5 class="card-title">Lokalizacje</h5>
                    <p class="card-text">Zarządzaj punktami wynajmu</p>
                    <a href="/locations" class="btn btn-outline-primary w-100">Przejdź</a>
                </div>
            </div>
        </div>
        <div class="col-md-4 mb-4">
            <div class="card shadow-sm">
                <div class="card-body">
                    <h5 class="card-title">Flota pojazdów</h5>
                    <p class="card-text">Przeglądaj dostępne auta</p>
                    <a href="/cars" class="btn btn-outline-primary w-100">Przejdź</a>
                </div>
            </div>
        </div>
    </div>
</div>
>>>>>>> origin/main
