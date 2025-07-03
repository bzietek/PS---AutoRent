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
    require __DIR__ . '/common.php',
    [
        'components' => [
            'request' => [
                // !!! insert a secret key in the following (if it is empty) - this is required by cookie validation
                'cookieValidationKey' => '2lE28pun0hQpCrp12lmHNK9hPP2Xulo2',
            ],
    //        'cache' => [
    //            'class' => 'yii\caching\FileCache',
    //        ],
            'urlManager' => [
                'enablePrettyUrl' => true,
                'showScriptName' => false,
                'rules' => $pathingRules,
                'normalizer' => [
                    'class' => 'yii\web\UrlNormalizer',
    //                'collapseSlashes' => true, // Collapse consecutive slashes into one
    //                'normalizeTrailingSlash' => true, // Remove/add trailing slash based on rules
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
        ],
    ]
    );
