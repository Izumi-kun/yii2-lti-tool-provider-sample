<?php

use yii\caching\FileCache;
use yii\console\controllers\MigrateController;
use yii\log\FileTarget;

$db = require __DIR__ . '/db.php';

$config = [
    'id' => 'lti-console',
    'basePath' => dirname(__DIR__),
    'bootstrap' => ['log'],
    'controllerNamespace' => 'app\commands',
    'controllerMap' => [
        'migrate' => [
            'class' => MigrateController::class,
            'migrationNamespaces' => [
                'izumi\yii2lti\migrations',
            ],
        ],
    ],
    'aliases' => [
        '@bower' => '@vendor/bower-asset',
        '@npm'   => '@vendor/npm-asset',
    ],
    'components' => [
        'cache' => [
            'class' => FileCache::class,
        ],
        'log' => [
            'targets' => [
                [
                    'class' => FileTarget::class,
                    'levels' => ['error', 'warning'],
                ],
            ],
        ],
        'db' => $db,
    ],
];

return $config;
