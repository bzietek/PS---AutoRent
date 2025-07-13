<?php

use yii\helpers\ArrayHelper;

$pathingRules = [
    '/' => 'main/default/index',
    '/login' => 'main/authentication/login',
    '/logout' => 'main/authentication/logout',
    '/signup' => 'main/authentication/signup',
    [
        'pattern'  => 'profile/<id:\d+>',
        'route'    => 'main/management/profile',
        'defaults' => ['id' => null],
    ],
    '/inactive' => 'main/authentication/inactive',

    //fallback simple routing
    '/<action:[\w-]+>' => 'main/default/<action>',
    '/<controller:[\w-]+>/<action:[\w-]+>' => 'main/<controller>/<action>',
    '/<module:[\w-]+>/<controller:[\w-]+>/<action:[\w-]+>' => '<module>/<controller>/<action>',
];

return ArrayHelper::merge(
    require DIR . '/common.php',
    [
        'components' => [
            'request' => [
                'cookieValidationKey' => '2lE28pun0hQpCrp12lmHNK9hPP2Xulo2',
            ],
            'urlManager' => [
                'enablePrettyUrl' => true,
                'showScriptName' => false,
                'rules' => $pathingRules,
                'normalizer' => [
                    'class' => 'yii\web\UrlNormalizer',
                ],
            ],
            'user' => [
                'identityClass' => 'app\models\database\user\User',
                'enableAutoLogin' => true,
                'loginUrl' => '/login',
                'accessChecker' => [
                    'class' => 'app\models\AccessChecker'
                ]
            ],
            'errorHandler' => [
                'errorAction' => 'site/error',
            ],
            'cache' => [
                'class' => 'yii\caching\FileCache',
            ],
        ],
    ]
);