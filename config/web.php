<?php

$params = require(__DIR__ . '/params.php');
$db = require(__DIR__ . '/db.php');

$config = [
	'id' => 'neighbordrop',
	'basePath' => dirname(__DIR__),
	'extensions' => require(__DIR__ . '/../vendor/yiisoft/extensions.php'),
	'modules' => [
      'user' => [
        'class' => 'dektrium\user\Module',
        'admins' => ['philippfrenzel'],
        'components'=>[
          'manager' => [
              'userClass' => 'frenzelgmbh\appcommon\components\User'
          ]
        ]
      ],
      'gridview' =>  [
          'class' => '\kartik\grid\Module'
      ],
      'address'=>[
          'class' => 'frenzelgmbh\cmaddress\Module',
      ],
      'packaii' => [
          'class' => 'schmunk42\packaii\Module',
          'gitHubUsername' => 'philippfrenzel',
          'gitHubPassword' => 'cassandra0903'
      ],
    ],
    'components' => [        
        'cache' => [
            'class' => 'yii\caching\FileCache',
        ],
        'authManager' => [
          'class' => 'frenzelgmbh\appcommon\components\User',
        ],
        'authClientCollection' => [
            'class' => 'yii\authclient\Collection',
            'clients' => [
                'google' => [
                    'class' => 'yii\authclient\clients\GoogleOpenId'
                ]
            ],
        ],
        'errorHandler' => [
            'errorAction' => 'site/error',
        ],
        'mail' => [
            'class' => 'yii\swiftmailer\Mailer',
            'useFileTransport' => true,
        ],
        'log' => [
            'traceLevel' => YII_DEBUG ? 3 : 0,
            'targets' => [
                [
                    'class' => 'yii\log\FileTarget',
                    'levels' => ['error', 'warning'],
                ],
            ],
        ],
        'db' => $db,
    ],
    'params' => $params,
];

if (YII_ENV_DEV) {
    // configuration adjustments for 'dev' environment
    $config['bootstrap'][] = 'debug';
    $config['modules']['debug'] = 'yii\debug\Module';

    $config['bootstrap'][] = 'gii';
    $config['modules']['gii'] = 'yii\gii\Module';
}

return $config;
