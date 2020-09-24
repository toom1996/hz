<?php

namespace common\components;

use Yii;
use yii\web\UnprocessableEntityHttpException;

/**
 * Class Debris
 * @package common\components
 * @author jianyan74 <751393839@qq.com>
 */
class Debris
{

    /**
     * 解析微信是否报错
     *
     * @param array $message 微信回调数据
     * @param bool $direct 是否直接报错
     * @return bool
     * @throws UnprocessableEntityHttpException
     * @throws \EasyWeChat\Kernel\Exceptions\HttpException
     * @throws \EasyWeChat\Kernel\Exceptions\InvalidArgumentException
     * @throws \EasyWeChat\Kernel\Exceptions\InvalidConfigException
     * @throws \EasyWeChat\Kernel\Exceptions\RuntimeException
     * @throws \Psr\SimpleCache\InvalidArgumentException
     */
    public function getWechatError($message, $direct = true)
    {
        if (isset($message['errcode']) && $message['errcode'] != 0) {
            // token过期 强制重新从微信服务器获取 token.
            if ($message['errcode'] == 40001) {
                Yii::$app->wechat->app->access_token->getToken(true);
            }

            if ($direct) {
                throw new UnprocessableEntityHttpException($message['errmsg']);
            }

            return $message['errmsg'];
        }

        return false;
    }

}