<?php

//Set the default time zone to europe/berlin
ini_set('date.timezone','Europe/Berlin');

if (function_exists('date_default_timezone_set')) {
  date_default_timezone_set('Europe/Berlin');
}

// comment out the following two lines when deployed to production
defined('YII_DEBUG') or define('YII_DEBUG', true);
defined('YII_ENV') or define('YII_ENV', 'dev');

require(__DIR__ . '/../vendor/autoload.php');
require(__DIR__ . '/../vendor/yiisoft/yii2/Yii.php');

$config = require(__DIR__ . '/../config/web.php');

(new yii\web\Application($config))->run();
