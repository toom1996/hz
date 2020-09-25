<?php

namespace app\behaviors;

use Yii;
use yii\base\Behavior;

/**
 * Class BeforeSend
 * @package api\behaviors
 * @author jianyan74 <751393839@qq.com>
 */
class BeforeSend extends Behavior
{
    /**
     * {@inheritdoc}
     */
    public function events()
    {
        return [
            'beforeSend' => 'beforeSend',
        ];
    }

    /**
     * 格式化返回
     *
     * @param $event
     * @throws \yii\base\InvalidConfigException
     */
    public function beforeSend($event)
    {
        if (!Yii::$app->request->isAjax && Yii::$app->controller->module->id == 'gii') {
            return true;
        }
        $response = $event->sender;
        $response->data = [
            'code' => $response->statusCode,
            'message' => $response->statusText,
            'data' => $response->data,
        ];

        Yii::warning(microtime(true));

        // 检查是否报错
        if ($response->statusCode >= 300 && $exception = Yii::$app->getErrorHandler()->exception) {
            $errData = [
                'type' => get_class($exception),
                'file' => method_exists($exception, 'getFile') ? $exception->getFile() : '',
                'errorMessage' => $exception->getMessage(),
                'line' => $exception->getLine(),
                'stack-trace' => explode("\n", $exception->getTraceAsString()),
            ];
        } else {
            $errData = [];
        }
        // 格式化报错输入格式
        if ($response->statusCode >= 500) {
            $response->data['data'] = YII_DEBUG ? $errData : '内部服务器错误,请联系管理员';
        }

        // 提取系统的报错信息
        if ($response->statusCode >= 300 && isset($response->data['data']['message']) && isset($response->data['data']['status'])) {
            $response->data['message'] = $response->data['data']['message'];
        }

        // 身份信息提示
        if ($response->statusCode == 401)
        {
            $response->data['message'] = '⊙(・◇・)？身份信息过期了耶~';
            Yii::warning('delete - - - - - cookie');
            setcookie('qingzhanSession',null,-1,'/',Yii::$app->request->hostName); //删除cookie
            setcookie('qingzhanMemberInfo',null,-1,'/',Yii::$app->request->hostName); //删除cookie
        }

        Yii::warning(microtime(true));
        $response->format = yii\web\Response::FORMAT_JSON;
        $response->statusCode = 200; // 考虑到了某些前端必须返回成功操作，所以这里可以设置为都返回200的状态码
    }
}