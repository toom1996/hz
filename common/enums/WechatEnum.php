<?php

namespace common\enums;

/**
 * Class WechatEnum
 * @package common\enums
 * @author jianyan74 <751393839@qq.com>
 */
class WechatEnum
{

    const FOLLOW_ON = 1;
    const FOLLOW_OFF = -1;

    /**
     * 关注状态
     *
     * @var array
     */
    public static $followStatus = [
        self::FOLLOW_ON  => '已关注',
        self::FOLLOW_OFF => '未关注',
    ];
    /**
     * 模块类别
     */
    const RULE_MODULE_TEXT = 'text';
    const RULE_MODULE_NEWS = 'news';
    const RULE_MODULE_MUSIC = 'music';
    const RULE_MODULE_IMAGE = 'image';
    const RULE_MODULE_VOICE = 'voice';
    const RULE_MODULE_VIDEO = 'video';
    const RULE_MODULE_ADDON = 'addon';
    const RULE_MODULE_USER_API = 'user-api';
    const RULE_MODULE_WX_CARD = 'wxcard';
    const RULE_MODULE_DEFAULT = 'default';

    /**
     * @var array
     * 说明
     */
    public static $moduleExplain = [
        self::RULE_MODULE_TEXT => '文字回复',
        self::RULE_MODULE_IMAGE => '图片回复',
        self::RULE_MODULE_NEWS => '图文回复',
        // self::RULE_MODULE_MUSIC => '音乐回复',
        self::RULE_MODULE_VOICE => '语音回复',
        self::RULE_MODULE_VIDEO => '视频回复',
        // self::RULE_MODULE_ADDON => '模块回复',
        self::RULE_MODULE_USER_API => '自定义接口回复',
        // self::RULE_MODULE_WX_CARD => '微信卡卷回复',
        // self::RULE_MODULE_DEFAULT => '默认回复',
    ];
    /**
     * 普通消息
     */
    const TYPE_TEXT = "text";// 文本消息
    const TYPE_IMAGE = "image";// 图片消息
    const TYPE_VOICE = "voice";// 语音消息
    const TYPE_VIDEO = "video";// 视频消息
    const TYPE_LOCATION = "location";// 地理位置消息
    const TYPE_LINK = "link";// 链接消息
    const TYPE_EVENT = "event";// 事件

    /**
     * 事件
     */
    const EVENT_SUBSCRIBE = "subscribe"; // 关注事件
    const EVENT_UN_SUBSCRIBE = "unsubscribe";// 取消关注事件
    const EVENT_LOCATION = "LOCATION";// 上传地址事件
    const EVENT_VIEW = "VIEW";// 访问链接事件
    const EVENT_CILCK = "CLICK";// 点击事件
    const EVENT_SCAN = "SCAN";// 二维码扫描事件

    /**
     * 其他消息
     */
    const TYPE_SHORTVIDEO = "shortvideo";// 小视频消息
    const TYPE_TRACE = "trace";// 上报地理位置
    const TYPE_MERCHANT_ORDER = "merchant_order";// 微小店消息
    const TYPE_SHAKEAROUND_USER_SHAKE = "ShakearoundUserShake";// 摇一摇:开始摇一摇消息
    const TYPE_SHAKEAROUND_LOTTERY_BIND = "ShakearoundLotteryBind";// 摇一摇:摇到了红包消息
    const TYPE_WIFI_CONNECTED = "WifiConnected";// Wifi连接成功消息

    /**
     * 特殊消息类型
     *
     * @var array
     */
    public static $typeExplanation = [
        self::TYPE_IMAGE => "图片消息",
        self::TYPE_VOICE => "语音消息",
        self::TYPE_VIDEO => "视频消息",
        self::TYPE_SHORTVIDEO => "小视频消息",
        self::TYPE_LOCATION => "位置消息",
        self::TYPE_TRACE => "上报地理位置",
        self::TYPE_LINK => "链接消息",
        self::TYPE_MERCHANT_ORDER => "微小店消息",
        self::TYPE_SHAKEAROUND_USER_SHAKE => "摇一摇：开始摇一摇消息",
        self::TYPE_SHAKEAROUND_LOTTERY_BIND => "摇一摇：摇到了红包消息",
        self::TYPE_WIFI_CONNECTED => "wifi连接成功消息",
    ];

    // 发送消息
    const SEND_TYPE_TEXT = 'text';
}