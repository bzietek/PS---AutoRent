<?php
/** @var yii\web\View $this */

use yii\helpers\Html;

$this->title = 'Auto Rent';
?>

<div class="container mt-5 fade-in">
    <div class="text-center mb-5">
        <h1 class="display-4 text-primary">🚗 Auto Rent</h1>
        <p class="lead text-secondary">Wypożyczalnia samochodów – szybko, wygodnie, online</p>

        <?php if (!Yii::$app->user->isGuest): ?>
            <p class="text-muted mt-3">Witaj, <?= Html::encode(Yii::$app->user->identity->name) ?>! Miłego dnia 🚘</p>
        <?php endif; ?>
    </div>

    <div class="row">
        <div class="col-md-4 mb-4">
            <div class="card shadow-sm">
                <img src="/images/sedan.png" class="card-img-top" alt="Sedan">
                <div class="card-body">
                    <h5 class="card-title">Sedan</h5>
                    <p class="card-text">Idealny na miasto i dłuższe trasy.</p>
                    <a href="#" class="btn btn-outline-primary w-100">Zarezerwuj</a>
                </div>
            </div>
        </div>
        <div class="col-md-4 mb-4">
            <div class="card shadow-sm">
                <img src="/images/suv.png" class="card-img-top" alt="SUV">
                <div class="card-body">
                    <h5 class="card-title">SUV</h5>
                    <p class="card-text">Więcej miejsca i komfortu na każdą podróż.</p>
                    <a href="#" class="btn btn-outline-primary w-100">Zarezerwuj</a>
                </div>
            </div>
        </div>
        <div class="col-md-4 mb-4">
            <div class="card shadow-sm">
                <img src="/images/kompakt.png" class="card-img-top" alt="Kompakt">
                <div class="card-body">
                    <h5 class="card-title">Kompakt</h5>
                    <p class="card-text">Ekonomiczny wybór na codzienne dojazdy.</p>
                    <a href="#" class="btn btn-outline-primary w-100">Zarezerwuj</a>
                </div>
            </div>
        </div>
    </div>
</div>
