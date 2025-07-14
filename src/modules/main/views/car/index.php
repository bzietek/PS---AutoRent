<?php
use yii\helpers\Html;

/** @var app\models\database\car\Car[] $cars */

$this->title = 'Lista samochodów';

$statusLabels = [
    'available' => 'Dostępny',
    'rented' => 'Wynajęty',
    'maintenance' => 'Serwis',
];
?>

<h1><?= Html::encode($this->title) ?></h1>

<table class="table table-bordered">
    <thead>
        <tr>
            <th>ID</th>
            <th>Marka</th>
            <th>Model</th>
            <th>Rok</th>
            <th>VIN</th>
            <th>Status</th>
            <th>Cena za dzień [zł]</th>
            <th></th>
        </tr>
    </thead>
    <tbody>
        <?php foreach ($cars as $car): ?>
            <tr>
                <td><?= Html::encode($car->id) ?></td>
                <td><?= Html::encode($car->brand) ?></td>
                <td><?= Html::encode($car->model) ?></td>
                <td>
                <?= $car->year ? Yii::$app->formatter->asDate($car->year, 'php:d.m.Y') : '' ?>
                </td>
                <td><?= Html::encode($car->VIN) ?></td>
                <td>
                    <?= isset($statusLabels[$car->status]) ? $statusLabels[$car->status] : Html::encode($car->status) ?>
                </td>
                <td><?= Html::encode($car->price_per_day) ?></td>
                 <td>
                    <?= Html::a('✕', 
                        ['car/delete', 'id' => $car->id], 
                        [
                            'class' => 'btn btn-danger btn-sm',
                            'title' => 'Usuń',
                            'data' => [
                                'confirm' => 'Czy na pewno chcesz usunąć ten samochód?',
                                'method' => 'post',
                            ],
                        ]
                    ) ?>
                </td>
            </tr>
        <?php endforeach; ?>
    </tbody>
</table>
