<?php

//Set the default time zone to europe/berlin
ini_set('date.timezone','Europe/Berlin');

if (function_exists('date_default_timezone_set')) {
  date_default_timezone_set('Europe/Berlin');
}

// NOTE: Make sure this file is not accessible when deployed to production
if (!in_array(@$_SERVER['REMOTE_ADDR'], ['127.0.0.1', '::1'])) {
	die('You are not allowed to access this file.');
}

defined('YII_DEBUG') or define('YII_DEBUG', true);
defined('YII_ENV') or define('YII_ENV', 'test');

require(__DIR__ . '/../vendor/autoload.php');
require(__DIR__ . '/../vendor/yiisoft/yii2/Yii.php');

$config = require(__DIR__ . '/../tests/acceptance/_config.php');

(new yii\web\Application($config))->run();
