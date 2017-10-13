<?php
$params = array_merge(
    require(__DIR__ . '/../../common/config/params.php'),
    require(__DIR__ . '/../../common/config/params-local.php'),
    require(__DIR__ . '/params.php'),
    require(__DIR__ . '/params-local.php'),
    require(__DIR__ . '/../../common/config/wechat.php')

);

return [
    'id' => 'app-api',
    'basePath' => dirname(__DIR__),
    'controllerNamespace' => 'api\controllers',
    'bootstrap' => ['log'],
    'modules' => [
        'v1' => [
            'class' => 'api\modules\v1\Module',
        ],
    ],

    'components' => [
        'request' => [
            //'csrfParam' => '_csrf-api',
            'parsers' => [
                'application/json' => 'yii\web\JsonParser',
            ]
        ],
        'response' => [
            'class' => 'yii\web\Response',
            'on beforeSend' => function ($event) {
                $response = $event->sender;
                $response->data = [
                    'code' => $response->getStatusCode(),
                    'data' => $response->data,
                    'message' => $response->statusText
                ];
                $response->format = yii\web\Response::FORMAT_JSON;
            },
        ],
        'user' => [
            'identityClass' => 'common\models\User',
            'enableAutoLogin' => true,
            'enableSession' => false,
           // 'identityCookie' => ['name' => '_identity-backend', 'httpOnly' => true],
        ],
        'session' => [
            // this is the name of the session cookie used for login on the backend
            'name' => 'advanced-backend',
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
        'errorHandler' => [
            'errorAction' => 'site/error',
        ],

        'urlManager' => [
            'enablePrettyUrl' => true,
            'enableStrictParsing' => true,
            'showScriptName' => false,
            'rules' => [
                [
                    'class' => 'yii\rest\UrlRule',
                    'controller' =>['v1/eval'],
                    'pluralize' => false,
                    'extraPatterns' => [
                        'GET create' => 'create',
                        'GET detail' => 'detail',
                        'GET getpass' => 'getpass',
                    ]

                ],
                [
                    'class' => 'yii\rest\UrlRule',
                    'controller' =>['v1/user'],
                   'pluralize' => false,
                    'extraPatterns' => [
                        'POST login' => 'login',
                        'GET signup-test' => 'signup-test',
                        'GET user-profile' => 'user-profile',
                    ]

                ],
                [
                    'class' => 'yii\rest\UrlRule',
                    'controller' =>['v1/wechat',],
                     'pluralize' => false,
                    'extraPatterns' => [
                        'GET wechat' => 'wechat',
                        'POST wechat' => 'wechat',
                        'GET wx-oauth' => 'wx-oauth',
                        'POST wx-oauth' => 'wx-oauth',
                        'GET wx-oauth-detail' => 'wx-oauth-detail',
                        'GET wx-oauth-callback' => 'wx-oauth-callback',
                        'POST wx-oauth-callback' => 'wx-oauth-callback',
                    ]

                ],
            ],
        ],

        'wechat' => [
            'class' => 'maxwen\easywechat\Wechat',
        ],

    ],
    'params' => $params,
];
