<?php

namespace common\enums;

/**
 * Class CacheKeyEnum
 * @package common\enums
 * @author jianyan74 <751393839@qq.com>
 */
class CacheKeyEnum
{
    const API_MINI_PROGRAM_LOGIN = 'api:mini-program:auth:'; // 小程序授权
    const API_ACCESS_TOKEN = 'api:access-token:'; // 用户信息记录
    const SYS_CONFIG = 'sys:config'; // 公用参数
    const SYS_PROBE = 'sys:probe'; // 系统探针
    const WECHAT_FANS_STAT = 'wechat:fans:stat:'; // 粉丝统计缓存
    const COMMON_ADDONS = 'common:addons:'; // 插件
    const COMMON_ADDONS_CONFIG = 'common:addons-config:'; // 插件配置
    const COMMON_PROVINCES = 'common:provinces:'; // 省市区
    const COMMON_IP_BLACKLIST = 'common:ip-blacklist:'; // ip黑名单
    const COMMON_ACTION_BEHAVIOR = 'common:action-behavior'; // 需要被记录的行为
    //redis key名称
    const REDIS_AVERAGE_KEY = 'REDIS_AVERAGE_KEY'; //素人发布redis key名称
    const MJB_QUERY_INCLUDED = 'mjb_query_included'; //查询收录redis key
    const MJB_SUBMIT_LINK = 'mjb_submit_link'; //提交链接redis key
    const MJB_ORDER_REDIS = 'mjb_order_redis'; //订单同步
    const MJB_XHS_ORDER_REDIS = 'mjb_xhs_order_redis'; //订单同步
    const MJB_XHS_RECRUIT_TASK = 'mjb_xhs_recruit_task'; //报名任务
    const MJB_RECRUIT_BLOG = 'mjb_recruit_blog'; //同步博主
    const MJB_MEDIA = 'mjb_media_sync'; //同步媒体
    const MJB_WE_MEDIA = 'mjb_we_media_sync'; //同步自媒体
    const CACHE_TMP_FILE = 'cache_tmp_file'; //临时文件缓存
    const XHS_ARTICLE_COOKIE = 'xhs_article_cookie'; //查询笔记信息
    const XHS_USER_COOKIE = 'xhs_user_cookie'; //查询博主信息
    const XHS_BLOG_COOKIE = 'xhs_blog_cookie'; //查询报名博主信息
    const XHS_NOTE_COOKIE = 'xhs_note_cookie'; //查询报名博主的笔记信息
    const MJB_XHS_ORDER_BATCH_RECEIVE = 'mjb_xhs_order_batch_receive'; //小红书批量接单

}