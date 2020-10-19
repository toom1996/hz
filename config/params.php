<?php

return [
    'adminEmail' => 'admin@example.com',
    'senderEmail' => 'noreply@example.com',
    'senderName' => 'Example.com mailer',
    // 微信配置 具体可参考EasyWechat
    'wechatConfig' => [
        'app_id' => 'wx4e99bb31f49db9f6', //测试
        'secret' => 'c852912627fa9f6b3340b7d4e683dfab', //测试
        'token' => 'sisciiZiJCC6PuGOtFWwmDnIHMsZyX', //微信公众号token
        'aes_key' => '', // 兼容与安全模式下请一定要填写！！！
        /**
         * 指定 API 调用返回结果的类型：array(default)/collection/object/raw/自定义类名
         * 使用自定义类名时，构造函数将会接收一个 `EasyWeChat\Kernel\Http\Response` 实例
         */
        'response_type' => 'array',
        /**
         * 接口请求相关配置，超时时间等，具体可用参数请参考：
         * http://docs.guzzlephp.org/en/stable/request-options.html
         *
         * - retries: 重试次数，默认 1，指定当 http 请求失败时重试的次数。
         * - retry_delay: 重试延迟间隔（单位：ms），默认 500
         * - log_template: 指定 HTTP 日志模板，请参考：https://github.com/guzzle/guzzle/blob/master/src/MessageFormatter.php
         */
        'http' => [
            'retries' => 1,
            'retry_delay' => 500,
            'timeout' => 5.0,
            // 'base_uri' => 'https://api.weixin.qq.com/',
        ],
    ],

];
