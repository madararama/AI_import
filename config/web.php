<?php

$params = require __DIR__ . '/params.php';
$db = require __DIR__ . '/db.php';

$config = [
    'id' => 'basic',
    'basePath' => dirname(__DIR__),
    'bootstrap' => ['log'],
    'defaultRoute' => 'excel/index',
    'aliases' => [
        '@bower' => '@vendor/bower-asset',
        '@npm'   => '@vendor/npm-asset',
    ],
    'components' => [
        'request' => [
            // !!! insert a secret key in the following (if it is empty) - this is required by cookie validation
            'cookieValidationKey' => 'asdasd',
        ],
        'cache' => [
            'class' => 'yii\caching\FileCache',
        ],
        'user' => [
            'identityClass' => 'app\models\User',
            'enableAutoLogin' => true,
        ],
        'errorHandler' => [
            'errorAction' => 'site/error',
        ],
        'mailer' => [
            'class' => 'yii\swiftmailer\Mailer',
            // send all mails to a file by default. You have to set
            // 'useFileTransport' to false and configure a transport
            // for the mailer to send real emails.
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
        'urlManager' => [
            'enablePrettyUrl' => true,
            'showScriptName' => false,
            'rules' => [
            ],
        ],
// 		'view' => [
		// 	'theme' => [
		// 		'pathMap' => [
		// 			'@app/views' => '@vendor/p2made/yii2-sb-admin-theme/views/sb-admin-2',
		// 		],
		// 	],
		// ],
        // 'assetManager' => [
		// 	'bundles' => [
		// 		'yii\web\JqueryAsset' => [
		// 			'sourcePath' => null, 'js' => [],
		// 		],
		// 		'yii\bootstrap\BootstrapAsset' => [
		// 			'sourcePath' => null, 'css' => [],
		// 		],
		// 		'yii\bootstrap\BootstrapPluginAsset' => [
		// 			'sourcePath' => null, 'js' => [],
		// 		],
		// 		'yii\jui\JuiAsset' => [
		// 			'sourcePath' => null, 'css' => [], 'js' => [],
		// 		],
		// 		'\rmrevin\yii\fontawesome\AssetBundle' => [
		// 			'sourcePath' => null, 'css' => [],
		// 		],
		// 	],
		// ],
    ],
    'modules'=>[
        'gridview' => [
            'class' => '\kartik\grid\Module',
        ],
        'datecontrol' =>  [
            'class' => '\kartik\datecontrol\Module'
        ],
    ],
    'params' => $params,
];

if (YII_ENV_DEV) {
    // configuration adjustments for 'dev' environment
    $config['bootstrap'][] = 'debug';
    $config['modules']['debug'] = [
        'class' => 'yii\debug\Module',
        // uncomment the following to add your IP if you are not connecting from localhost.
        //'allowedIPs' => ['127.0.0.1', '::1'],
    ];

    $config['bootstrap'][] = 'gii';
    $config['modules']['gii'] = [
        'class' => 'yii\gii\Module',
        // uncomment the following to add your IP if you are not connecting from localhost.
        //'allowedIPs' => ['127.0.0.1', '::1'],
    ];
}

return $config;
