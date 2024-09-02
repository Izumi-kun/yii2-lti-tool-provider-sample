<?php

use app\controllers\SiteController;
use app\models\User;
use izumi\yii2lti\Module as LtiToolModule;
use yii\caching\FileCache;
use yii\debug\Module as YiiDebugModule;
use yii\filters\ContentNegotiator;
use yii\httpclient\CurlTransport;
use yii\log\FileTarget;

$db = require __DIR__ . '/db.php';

$config = [
    'id' => 'lti',
    'name' => 'Sample LTI Tool',
    'basePath' => dirname(__DIR__),
    'bootstrap' => [
        'log',
        [
            'class' => ContentNegotiator::class,
            'languages' => ['en', 'ru'],
        ],
    ],
    'aliases' => [
        '@bower' => '@vendor/bower-asset',
        '@npm'   => '@vendor/npm-asset',
    ],
    'components' => [
        'request' => [
            // !!! insert a secret key in the following (if it is empty) - this is required by cookie validation
            'cookieValidationKey' => 'QFKtETQywZhSvztwUcswyjb33QCG-WVU',
        ],
        'cache' => [
            'class' => FileCache::class,
        ],
        'user' => [
            'identityClass' => User::class,
            'loginUrl' => ['site/index'],
        ],
        'log' => [
            'traceLevel' => 0,
            'targets' => [
                [
                    'class' => FileTarget::class,
                    'levels' => ['error', 'warning'],
                ],
            ],
        ],
        'db' => $db,
        'urlManager' => [
            'enablePrettyUrl' => true,
            'showScriptName' => false,
        ],
    ],
    'modules' => [
        'lti' => [
            'class' => LtiToolModule::class,
            'tool' => [
                'debugMode' => YII_DEBUG,
                // Do not use this private key in production!
                'rsaKey' => '-----BEGIN RSA PRIVATE KEY-----
MIIEogIBAAKCAQEArMivGSuJBYA+A1BFl3MvzcApq59Xwun5ksY/4FA70SRPppY1
ueK0SGl0ErMyapbWML75Vxp6WGC+4gEtCRfpcZhSClu9nJJv4xRSpzxyuLExyjIF
03mcBLk+rWndkRhqcUU8s2hgQ9F5nhPJzrXOlLL1x4yE77XN0kSrksVnVFBvJ3mt
otFNCmEduj1USmdVp2X76jpxHFd4McQBXpP2akiLaeNpz+CW/utufBDqnPGtViRX
kCJD9t0cpAX0l2jeOpI6078hm0HExzVEP1xLuzG+NhpbTIVO8IpMMDBgJQe4Par2
fX7zBTOU3bvdvxFBDTnaOHC87zSjQQUqBQ3uUwIDAQABAoIBAEZO81FesccXRCS9
CVRzfsROqaY4lNGvu+rJ2TxB6dVU3USAYyRc59d/ccgaOy28azQywet7zsUfuQzm
RZkprciXnuqwIhwQSo8wueFra5NUJ1qLuGsxVRdm+eY+6fYc5VNqRSUMGAAAwWhd
zQTcXk+L8w9cUsvoQvSJFPgIc9+OoAVNHNP7C1ew7q+p8Ps8MuFokP7P3anp8lHe
OOdoFNWKkwzIBd0L5g8CaMN3VoNTsJmw7ARnJEjKwxGB2uEoA5ZVOI80r1r/VhQa
EtJFsDYUY5rQ2dwMQVR9Xcm6HOsoGFo0uwJce+/vNF/PKkyvIGSFxz7VbfmV9lIq
qHnbLoECgYEA3bwM8XXtAyUw7zjEC1TN2ow7dsOiVHE6CLpWOLWyQFKWMvBvH9pN
poG6E1N4P4qq2ZkI2OGTvgVQ2Xm+b+egqPjeAbB8iv6U66xceW6U6r+4hkJNRKOC
fRq6fnlKgemwyulB4aHd1cK903rjcEqUax+4s2l/FIAk7RPgwwylI3ECgYEAx3wd
9czl1ME+tjLnwAVBLvKCAZra9V6+tIIPVhpDAn06E7+AUg0Og1VyQEMNOc9T4hYZ
e1QZU1+4nQ1u/OHTTEXhsni46BiFhKJt01YrOnilPrfRpsJy2h89ys4Ni9sQ2vQP
Lg5IAjktBsfp4OGQGeVOC8UvM8o4EaOq7Hm8xAMCgYAUGbj+ppcOwu9VsEqtUEm8
9xto38E2cHE3W1T3nRkElbgB9CPOumZxoq7wk4+CCxsD0MipiIFxJ2A15sBpupCo
4K6Xbp1LqFptptlXNLwRL8IVgaspfr0UhDjFwE8NydZ5/n03bAoFF7tHkYtOoaQk
teJzeSvI+vjd+QBWDi5mMQKBgFQqXu2tZK8OVapD8hnHXcg0E3wf3RA3yFiao2Pd
srYjJdTdMIPt9FifPZQ1digU/LxgPKIXSpQtx9OjrldN5HvC5EYLv2BVsEfUzGel
bJm/+2Bp6C/mzNSQ595gb8C5TfRDkwAIkIM3onLA+EGkicsTP4mhyZedU2jF2EDr
4CQHAoGAX8rGC7Sbhq2qTDKWQWAcrS60KbXuaAEEGO9faxk8HjCJ27jAUgu69fqr
2zwcpxz9OyyoFxCH4mR/DR7kGxrLZcCRssxGD0saQMxpuJJg2QUP6y85KwvoYoOY
2+sBBndxp1sg8JyU7rDsXZ2IZfzgepkfyKwFdIEI3y/fkyrnRo0=
-----END RSA PRIVATE KEY-----',
            ],
            'httpClient' => [
                'transport' => CurlTransport::class
            ],
            'on launch' => [SiteController::class, 'ltiLaunch'],
            'on error' => [SiteController::class, 'ltiError'],
        ],
    ],
];

if (YII_ENV_DEV) {
    // configuration adjustments for 'dev' environment
    $config['bootstrap'][] = 'debug';
    $config['modules']['debug'] = [
        'class' => YiiDebugModule::class,
        // uncomment the following to add your IP if you are not connecting from localhost.
        //'allowedIPs' => ['127.0.0.1', '::1'],
    ];
}

return $config;
