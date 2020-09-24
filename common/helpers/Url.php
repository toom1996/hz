<?php

namespace common\helpers;

use Yii;
use yii\helpers\BaseUrl;
use common\enums\AuthEnum;
use yii\web\BadRequestHttpException;

/**
 * Class Url
 * @package common\helpers
 * @author jianyan74 <751393839@qq.com>
 */
class Url extends BaseUrl
{
    /**
     * 生成模块Url
     *
     * @param array $url
     * @param bool $scheme
     * @return bool| string
     */
    public static function to($url = '', $scheme = false)
    {
        if (is_array($url) && Yii::$app->id != AuthEnum::TYPE_BACKEND) {
            $url = static::isMerchant($url);
        }

        if (Yii::$app->params['inAddon']) {
            return urldecode(parent::to(self::regroupUrl($url), $scheme));
        }

        return parent::to($url, $scheme);
    }

    /**
     * 生成前台链接
     *
     * @param array $url
     * @param bool $scheme
     * @return string
     * @throws \yii\base\InvalidConfigException
     */
    public static function toFront(array $url, $scheme = false)
    {
        $domainName = Yii::getAlias('@frontendUrl');
        return static::create($url, $scheme, $domainName, '', 'urlManagerFront');
    }

    /**
     * 生成微信链接
     *
     * @param array $url
     * @param bool $scheme
     * @return string
     * @throws \yii\base\InvalidConfigException
     */
    public static function toWechat(array $url, $scheme = false)
    {
        $domainName = Yii::getAlias('@wechatUrl');
        return static::create($url, $scheme, $domainName, '/wechat', 'urlManagerWechat');
    }

    /**
     * 生成oauth2链接
     *
     * @param array $url
     * @param bool $scheme
     * @return string
     * @throws \yii\base\InvalidConfigException
     */
    public static function toOAuth2(array $url, $scheme = false)
    {
        $domainName = Yii::getAlias('@oauth2Url');
        return static::create($url, $scheme, $domainName, '/oauth2', 'urlManagerOAuth2');
    }

    /**
     * 生成oauth2链接
     *
     * @param array $url
     * @param bool $scheme
     * @return string
     * @throws \yii\base\InvalidConfigException
     */
    public static function toStorage(array $url, $scheme = false)
    {
        $domainName = Yii::getAlias('@storageUrl');
        return static::create($url, $scheme, $domainName, '/storage', 'urlManagerStorage');
    }

    /**
     * 生成Api链接
     *
     * @param array $url
     * @param bool $scheme
     * @return string
     * @throws \yii\base\InvalidConfigException
     */
    public static function toApi(array $url, $scheme = false)
    {
        $domainName = Yii::getAlias('@apiUrl');
        return static::create($url, $scheme, $domainName, '/api', 'urlManagerApi');
    }

    /**
     * 获取权限所需的url
     *
     * @param $url
     * @return string
     */
    public static function getAuthUrl($url)
    {
        return static::normalizeRoute($url);
    }

    /**
     * @param $url
     * @param $scheme
     * @param $domainName
     * @param $appId
     * @param $key
     * @return string
     * @throws \yii\base\InvalidConfigException
     */
    protected static function create($url, $scheme, $domainName, $appId, $key)
    {
        $url = static::isMerchant($url);
        Yii::$app->params['inAddon'] && $url = self::regroupUrl($url);

        if (!Yii::$app->has($key)) {
            Yii::$app->set($key, [
                'class' => 'yii\web\urlManager',
                'hostInfo' => !empty($domainName) ? $domainName : Yii::$app->request->hostInfo . $appId,
                'scriptUrl' => '', // 代替'baseUrl'
                'enablePrettyUrl' => true,
                'showScriptName' => true,
                'suffix' => '',// 静态
            ]);

            unset($domainName);
        }

        return urldecode(Yii::$app->$key->createAbsoluteUrl($url, $scheme));
    }

    /**
     * @param array $url
     * @return array
     */
    protected static function isMerchant(array $url)
    {
        if (true === Yii::$app->params['merchantOpen']) {
            $url = ArrayHelper::merge([
                'merchant_id' => Yii::$app->services->merchant->getId()
            ], $url);
        }

        return $url;
    }

    /**
     * 重组url
     *
     * @param array $url 重组地址
     * @param array $addonsUrl 路由地址
     * @return array
     */
    protected static function regroupUrl($url)
    {
        if (!is_array($url)) {
            return $url;
        }

        $addonsUrl = [];
        $addonsUrl[0] = '/addons/' . StringHelper::toUnderScore(Yii::$app->params['addonInfo']['name']) . '/' . self::regroupRoute($url);

        // 删除默认跳转url
        unset($url[0]);
        foreach ($url as $key => $vo) {
            $addonsUrl[$key] = $vo;
        }

        return $addonsUrl;
    }

    /**
     * 重组路由
     *
     * @param array $url
     * @return string
     */
    public static function regroupRoute($url)
    {
        $oldRoute = Yii::$app->params['addonInfo']['oldRoute'];
        $route = $url[0];
        // 如果只填写了方法转为控制器方法
        if (count(explode('/', $route)) < 2) {
            $oldRoute = explode('/', $oldRoute);
            $oldRoute[count($oldRoute) - 1] = $url[0];
            $route = implode('/', $oldRoute);

            unset($oldRoute);
        }

        return $route;
    }

    /**
     * @desc 获得访客真实ip
     * @return mixed
     */
    public static function Get_Ip()
    {
        //判断服务器是否允许$_SERVER
        if (isset($_SERVER)) {
            if (isset($_SERVER['HTTP_X_FORWARDED_FOR'])) {
                $realIp = $_SERVER['HTTP_X_FORWARDED_FOR'];
            } elseif (isset($_SERVER['HTTP_CLIENT_IP'])) {
                $realIp = $_SERVER['HTTP_CLIENT_IP'];
            } else {
                $realIp = $_SERVER['REMOTE_ADDR'];
            }
        } else {
            //不允许就使用getenv获取
            if (getenv("HTTP_X_FORWARDED_FOR")) {
                $realIp = getenv("HTTP_X_FORWARDED_FOR");
            } elseif (getenv("HTTP_CLIENT_IP")) {
                $realIp = getenv("HTTP_CLIENT_IP");
            } else {
                $realIp = getenv("REMOTE_ADDR");
            }
        }

        $realIp = explode(',', $realIp);
        return $realIp[0];
    }

    /**
     * 生成随机ip
     * @return string
     */
    public static function rand_ip()
    {
        return mt_rand(0,255).'.'.mt_rand(0,255).'.'.mt_rand(0,255).'.'.mt_rand(0,255);
    }

    /**
     * 过滤域名中的链接地址
     * @param string $link
     * @return bool
     */
    public static function filter_domain($link = '')
    {
        if (strpos($link, 'dmh.bjhzkq.com') !== false || strpos($link, 'meijiebao.org.cn') !== false) {
            return true;
        }
        return false;
    }

    /**
     * 判断是否为链接
     * @param $url
     * @return bool|false|int
     */
    public static function isUrl($url)
    {
        if (!trim($url)) {
            return false;
        }
        if (strlen($url) < 10) {
            return false;
        }
        $pattern = '_^(?:(?:https?|ftp)://)(?:\S+(?::\S*)?@)?(?:(?!10(?:\.\d{1,3}){3})(?!127(?:\.\d{1,3}){3})(?!169\.254(?:\.\d{1,3}){2})(?!192\.168(?:\.\d{1,3}){2})(?!172\.(?:1[6-9]|2\d|3[0-1])(?:\.\d{1,3}){2})(?:[1-9]\d?|1\d\d|2[01]\d|22[0-3])(?:\.(?:1?\d{1,2}|2[0-4]\d|25[0-5])){2}(?:\.(?:[1-9]\d?|1\d\d|2[0-4]\d|25[0-4]))|(?:(?:[a-z\x{00a1}-\x{ffff}0-9]+-?)*[a-z\x{00a1}-\x{ffff}0-9]+)(?:\.(?:[a-z\x{00a1}-\x{ffff}0-9]+-?)*[a-z\x{00a1}-\x{ffff}0-9]+)*(?:\.(?:[a-z\x{00a1}-\x{ffff}]{2,})))(?::\d{2,5})?(?:/[^\s]*)?$_iuS';
        $result = preg_match($pattern, $url);
        $result = (bool) $result;
        return $result;
    }

    /**
     * 过滤链接参数
     * @param $link
     * @return string
     * @throws BadRequestHttpException
     */
    public static function filterUrlParam($link)
    {
        $is_link = self::isUrl($link);
        if (!$is_link) {
            throw new BadRequestHttpException('不是一条正确的链接！');
        }
        $url = parse_url($link);
        $http = $url['scheme'] ? $url['scheme'] . '://' : 'http://';
        $link = $http . $url['host'] . $url['path'];
        return $link;
    }
}