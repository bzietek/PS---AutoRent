<?php

namespace app\commands;

use yii\gii\generators\model\Generator;
use \Yii;

class MigrateController extends \yii\console\controllers\MigrateController
{
    public $migrationTable = 'migration';

    public $ignoredTablesInAutogeneratingActiveRecords = ['migration'];

    public function afterAction($action, $result)
    {
        if (in_array($action->id, ['down', 'fresh', 'redo', 'to', 'up'])) {
            $tablesInTheDb = $this->db->createCommand(
                'SELECT table_name FROM user_tables ORDER BY table_name'
            )->queryColumn();
            $tables = array_intersect(
                Yii::$app->params['tables'],
                $tablesInTheDb
            );

            $notFoundTables = array_diff(Yii::$app->params['tables'], $tables);
            if (count($notFoundTables)) {
                echo PHP_EOL . 'FOLLOWING TABLES COULD NOT BE FOUND:' . PHP_EOL;
                echo implode(PHP_EOL, $notFoundTables);
            }
            $counter = 0;
            echo PHP_EOL . PHP_EOL . 'Generating active-models for following tables:' . PHP_EOL;
            foreach ($tables as $tableName) {
                $generator = new Generator([
                    'ns' => 'app\models\database\generated',
                    'tableName' => $tableName,
                    'baseClass' => 'app\models\BaseActiveRecord',
                ]);
                $files = $generator->generate();
                foreach ($files as $file) {
                    /** @var \yii\gii\CodeFile $file */
                    $file->save();
                }
                echo '    > ', $tableName . PHP_EOL;
                $counter++;
            }
            echo $counter . ' active-models were generated.' . PHP_EOL;
        }
        parent::afterAction($action, $result);
    }

    public function actionCreateUser($userName = 'appUser', $password = 'Apas$W0rd') 
    {
        Yii::$app->db->createCommand('CREATE USER ' . $userName . ' IDENTIFIED BY ' . $password)->execute();
    }
}
