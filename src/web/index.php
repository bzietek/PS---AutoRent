<?php
require __DIR__ . '/../../vendor/autoload.php';
ini_set('display_errors', 1);
error_reporting(E_ALL);
$envfile = __DIR__ . '/../../.env';
if (file_exists($envfile)) {
	$dotenv = Dotenv\Dotenv::createImmutable(dirname($envfile));
	$dotenv->load();
	if (array_key_exists('YII_DEBUG', $_ENV)) {
		defined('YII_DEBUG') or define(
			'YII_DEBUG',
			($_ENV['YII_DEBUG'] === 'false') === false && (bool) $_ENV['YII_DEBUG']
		);
	}
	if (array_key_exists('YII_ENV', $_ENV)) {
		defined('YII_ENV') or define('YII_ENV', $_ENV['YII_ENV']);
	}
} else {
	die('missing the .env file. Env path: ' . $envfile);
}

defined('YII_DEBUG') or define('YII_DEBUG', true);
defined('YII_ENV') or define('YII_ENV', 'dev');


require __DIR__ . '/../../vendor/yiisoft/yii2/Yii.php';
$config = require __DIR__ . '/../config/web.php';

(new yii\web\Application($config))->run();
