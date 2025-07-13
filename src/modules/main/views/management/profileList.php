<?php

use app\models\database\user\Role;
use app\models\database\user\UserSearch;
use app\modules\main\models\management\AdminActionColumn;
use yii\data\ActiveDataProvider;
use yii\grid\GridView;
use yii\helpers\ArrayHelper;
use yii\helpers\Html;
use yii\web\View;
use yii\widgets\ActiveForm;

/** @var View $this */
/** @var ActiveDataProvider $list */
/** @var int $countPerPage */
/** @var UserSearch $searchModel */

$columns = [
    'id',
    'name',
    'surname',
    'role' => [
        'value' => function ($model) {
            return Role::getRoles()[$model->role];
        },
        'attribute' => 'role',
    ],
    'email',
    'phone_number',
    'active' => [
        'value' => function ($model) {
            return $model->active ? 'active' : 'inactive';
        },
        'attribute' => 'active'
    ],
    ['class' => AdminActionColumn::class]
];
?>

    <div class="filter-form">

        <?php $form = ActiveForm::begin([
            'method' => 'get',
            'action' => ['management/profiles'],
        ]); ?>

        <div class="row">
            <div class="col-md-2">
                <?= $form->field($searchModel, 'name')->textInput([
                    'placeholder' => Yii::t('app', 'First Name'),
                ]) ?>
            </div>

            <div class="col-md-2">
                <?= $form->field($searchModel, 'surname')->textInput([
                    'placeholder' => Yii::t('app', 'Last Name'),
                ]) ?>
            </div>

            <div class="col-md-2">
                <?= $form->field($searchModel, 'email')->textInput([
                    'placeholder' => Yii::t('app', 'Email'),
                ]) ?>
            </div>

            <div class="col-md-2">
                <?= $form->field($searchModel, 'phone_number')->textInput([
                    'placeholder' => Yii::t('app', 'Phone Number'),
                ]) ?>
            </div>

                <div class="col-md-2">
                    <?= $form->field($searchModel, 'id')->textInput([
                        'placeholder' => Yii::t('app', 'Id'),
                    ]) ?>
                </div>

                <div class="col-md-2">
                    <?= $form->field($searchModel, 'role')->dropDownList(
//                        ArrayHelper::map(Role::getRoles(), function ($key) { return $key; }, function ($value) { return $value; }),
                        Role::getRoles(),
                        ['prompt' => 'Select Role']
                    ) ?>
                </div>

                <div class="col-md-2">
                    <?= $form->field($searchModel, 'active')->dropDownList(
                        [
//                            null => '',
                            0 => 'inactive',
                            1 => 'active'
                        ],
                        ['prompt' => 'is active?']
                    ) ?>
                </div>

            <div class="col-md-2">
                <?= $form->field($searchModel, 'created_at_start')->input('date', [
                    'placeholder' => 'Start date',
                ])->label('Created After') ?>
            </div>
            <div class="col-md-2">
                <?= $form->field($searchModel, 'created_at_end')->input('date', [
                    'placeholder' => 'End date',
                ])->label('Created Before') ?>
            </div>
        </div>
        <div class="row d-flex justify-content-around align-items-center flex-row">
            <div class="col-md-2">
                <label for="count"><?= Yii::t('app', 'Items Per Page') ?></label>
                <?= Html::dropDownList('count', $countPerPage, [
                    5 => '5',
                    10 => '10',
                    20 => '20',
                    50 => '50',
                    100 => '100'
                ], [
                    'class' => 'form-control',
                    'prompt' => Yii::t('app', 'Select Count'),
                ]) ?>
            </div>

            <div class="col-md-2">
                <div class="form-group">
                    <?= Html::submitButton('Filter', ['class' => 'btn btn-primary']) ?>
                </div>
            </div>


        </div>

        <?php ActiveForm::end(); ?>

    </div>

    <hr>

<?= GridView::widget([
    'dataProvider' => $list,
    'columns' => [
//        ['class' => 'yii\grid\SerialColumn'],
        ...$columns,
    ],
    'pager' => [
        'options' => ['class' => 'pagination pagination-sm'],
        'maxButtonCount' => 5,
        'nextPageLabel' => '>',
        'prevPageLabel' => '<',
        'firstPageLabel' => '<<',
        'lastPageLabel' => '>>',
        'activePageCssClass' => 'active',
        'disabledPageCssClass' => 'disabled',
    ],
]); ?>
