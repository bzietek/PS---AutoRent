<?php

use yii\helpers\ArrayHelper;

return ArrayHelper::merge(
    require __DIR__ . '/common.php',
    [
        'controllerNamespace' => 'app\commands',
        'controllerMap' => [
            'migrate' => [
                'class' => 'app\commands\MigrateController',
                'migrationNamespaces' => [
                    'app\migrations',
                ],
                'migrationPath' => null,
            ],
            ...(
                $_ENV['YII_ENV'] == 'dev'
                ? [
                    'migration' => [
                        'class' => 'bizley\migration\controllers\MigrationController',
                        // 'migrationPath' => 'src/migrations/',
                        // 'migrationPath' => __DIR__ . '/../migrations',
                        'migrationNamespace' => 'app\\migrations',
                        // 'onlyShow' => true,
                    ],
                ]
                : []
            )
        ],
    ]
);
