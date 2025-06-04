<?php

namespace app\commands;

use Yii;
use yii\console\Controller;
use yii\db\Exception;
use yii\helpers\Console;

class DbClientController extends Controller
{
    public function actionIndex()
    {
        $this->stdout("DB REPL (type 'exit' to quit)\n", Console::FG_GREEN);

        while (true) {
            $query = readline("SQL> ");
            if (trim(strtolower($query)) === 'exit') {
                $this->stdout("Goodbye!\n", Console::FG_YELLOW);
                break;
            }
            $this->exec($query);
        }
    }

    public function actionExec(string $query) {
        $this->exec($query);
    }

    private function exec(string $query) {
        $db = Yii::$app->db;
        $queries = $this->explodeQuery($query);
        foreach ($queries as $subquery) {
            $subquery = trim($subquery);
            if (empty($subquery)) {
                continue;
            }
            // $subquery .= ';';
            $command = $db->createCommand($subquery);
            try {
                if (preg_match('/^\s*select/i', $subquery)) {
                    $result = $command->queryAll();
                    if (empty($result) === false) {
                        $this->printTable($result);
                    } else {
                        $this->stdout('Result is an empty table.' . PHP_EOL);
                    }
                } else {
                    $result = $command->execute();
                    $this->stdout('Query affected ' . $result . ' rows.' . PHP_EOL);
                }
            } catch (Exception $e) {
                $this->stderr("Error: " . $e->getMessage() . "\n", Console::FG_RED);
            }
        }
    }

    private function explodeQuery(string $query) : array 
    {
        return explode(';', $query);
    }

    private function printTable(array $rows)
    {
        $columns = array_keys($rows[0]);
        $widths = array_map('strlen', $columns);

        foreach ($rows as $row) {
            foreach ($columns as $i => $col) {
                $widths[$i] = max($widths[$i], strlen((string)$row[$col]));
            }
        }

        $line = '+';
        foreach ($widths as $w) {
            $line .= str_repeat('-', $w + 2) . '+';
        }

        $this->stdout($line . "\n");

        $this->stdout('| ');
        foreach ($columns as $i => $col) {
            $this->stdout(str_pad($col, $widths[$i]) . ' |');
        }
        $this->stdout("\n" . $line . "\n");

        foreach ($rows as $row) {
            $this->stdout('| ');
            foreach ($columns as $i => $col) {
                $this->stdout(str_pad((string)$row[$col], $widths[$i]) . ' |');
            }
            $this->stdout("\n");
        }

        $this->stdout($line . "\n");
    }
}
