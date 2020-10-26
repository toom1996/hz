<?php

$params = require __DIR__ . '/params.php';
$db = require __DIR__ . '/db.php';

$config = [
    'id' => 'basic',
    'basePath' => dirname(__DIR__),
    'bootstrap' => ['log'],
    'aliases' => [
        '@bower' => '@vendor/bower-asset',
        '@npm'   => '@vendor/npm-asset',
    ],
    'components' => [
        'request' => [
            // !!! insert a secret key in the following (if it is empty) - this is required by cookie validation
            'cookieValidationKey' => '7OWqgxF2gGkultRhgPC70sSnZdiUsjdK',
        ],
        'cache' => [
            'class' => 'yii\caching\FileCache',
        ],
        'response' => [
            'class' => 'yii\web\Response',
            'as beforeSend' => 'app\behaviors\BeforeSend',
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
        'wechat' => [
            'class' => 'jianyan\easywechat\Wechat',
            'userOptions' => [],  // 用户身份类参数
            'sessionParam' => 'wechatUser', // 微信用户信息将存储在会话在这个密钥
            'returnUrlParam' => '_wechatReturnUrl', // returnUrl 存储在会话中
            'rebinds' => [ // 自定义服务模块
                // 'cache' => 'common\components\Cache',
            ]
        ],
        /** ------ 服务层 ------ **/
        'services' => [
            'class' => 'services\Application',
        ],
        /** ------ redis配置 ------ **/
        'redis' => [
            'class' => 'yii\redis\Connection',
            'hostname' => '127.0.0.1',
            'port' => 6379,
            'database' => 0,
        ],
        /** ------ 格式化时间 ------ **/
        'formatter' => [
            'dateFormat' => 'yyyy-MM-dd',
            'datetimeFormat' => 'yyyy-MM-dd HH:mm:ss',
            'decimalSeparator' => ',',
            'thousandSeparator' => ' ',
            'currencyCode' => 'CNY',
        ],
        'debris' => [
            'class' => 'common\components\Debris',
        ],
        'db' => $db,
        'urlManager' => [
            'class' => 'yii\web\UrlManager',
            // 美化Url,默认不启用。但实际使用中，特别是产品环境，一般都会启用。
            'enablePrettyUrl' => true,
            // 是否启用严格解析，如启用严格解析，要求当前请求应至少匹配1个路由规则，
            // 否则认为是无效路由。
            // 这个选项仅在 enablePrettyUrl 启用后才有效。启用容易出错
            // 注意:如果不需要严格解析路由请直接删除或注释此行代码
            'enableStrictParsing' => true,
            // 是否在URL中显示入口脚本。是对美化功能的进一步补充。
            'showScriptName' => false,
            // 指定续接在URL后面的一个后缀，如 .html 之类的。仅在 enablePrettyUrl 启用时有效。
            'suffix' => '',
            'rules' => [
                [
                    'class' => 'yii\rest\UrlRule',
                    'controller' => [
                        /**
                         * 默认登录测试控制器(Post)
                         * http://当前域名/api/v1/site/login
                         */
                        // 'sign-secret-key',
                        // 版本1
                        'site',
                        'user',
                        'wechat-api',
                    ],
                    'pluralize' => false,// 是否启用复数形式，注意index的复数indices，开启后不直观
                    'extraPatterns' => [
                        'GET index' => 'index',
                        'GET get_info' => 'get_info',
                        'GET login-qrcode' => 'login-qrcode', //获取二维码
                        'POST wechat-login' => 'wechat-login',

                    ],
                ],
            ]
        ],
    ],
    'params' => $params,
    'modules' => [
        /** ------ 插件模块 ------ **/
        'addons' => [
            'class' => 'common\components\AddonsModule',
        ],
    ],
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
        'allowedIPs' => ['127.0.0.1', '::1'],
    ];
}

return $config;
