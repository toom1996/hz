<?php
namespace common\helpers;

use Yii;
use yii\httpclient\Client;
use yii\httpclient\Exception;

class CurlHelper
{
    public static $log_path;

    public static $clientFlag = array(
        'Mozilla/5.0 (Windows NT 6.1; WOW64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/28.0.1500.95 Safari/537.36',
        'Mozilla/5.0 (Windows NT 6.1; WOW64; rv:24.0) Gecko/20100101 Firefox/24.0',
        'Mozilla/4.0 (compatible; MSIE 8.0; Windows NT 6.1; WOW64; Trident/4.0; GTB7.2; SLCC2; .NET CLR 2.0.50727; .NET CLR 3.5.30729; .NET CLR 3.0.30729; Media Center PC 6.0; InfoPath.3; .NET4.0C; .NET4.0E)',
        'Mozilla/5.0 (Windows NT 6.1; WOW64) AppleWebKit/537.1 (KHTML, like Gecko) Chrome/21.0.1180.89 Safari/537.1',
        'Mozilla/4.0 (compatible; MSIE 8.0; Windows NT 5.1; Trident/4.0; GTB7.2; .NET CLR 2.0.50727; .NET CLR 3.0.4506.2152; .NET CLR 3.5.30729)',
        'Mozilla/5.0 (compatible; MSIE 9.0; Windows NT 6.1;Trident/5.0)',
        'Mozilla/4.0 (compatible; MSIE 7.0; Windows NT 6.1; Trident/5.0; SLCC2; .NET CLR 2.0.50727; .NET CLR 3.5.30729; .NET CLR 3.0.30729; .NET4.0C; SE 2.X MetaSr 1.0)',
        'Mozilla/5.0 (Windows NT 5.1) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/30.0.1599.69 Safari/537.36 OPR/17.0.1241.45',
        'Mozilla/5.0 (Windows NT 5.1) AppleWebKit/537.1 (KHTML, like Gecko) Chrome/21.0.1180.89 Safari/537.1',
        'Mozilla/4.0 (compatible; MSIE 8.0; Windows NT 5.1; Trident/4.0; .NET CLR 2.0.50727; .NET CLR 3.0.4506.2152; .NET CLR 3.5.30729; Alexa Toolbar; .NET4.0C; .NET4.0E; LBBROWSER)',
        'Mozilla/4.0 (compatible; MSIE 8.0; Windows NT 5.1; Trident/4.0; GTB7.2; .NET CLR 2.0.50727; .NET CLR 3.0.4506.2152; .NET CLR 3.5.30729)',
        'Mozilla/5.0 (Windows NT 5.1) AppleWebKit/537.1 (KHTML, like Gecko) Chrome/21.0.1180.89 Safari/537.1',
        'Mozilla/4.0 (compatible; MSIE 7.0; Windows NT 6.1; Trident/5.0; SLCC2; .NET CLR 2.0.50727; .NET CLR 3.5.30729; .NET CLR 3.0.30729; .NET4.0C; SE 2.X MetaSr 1.0)'
    );

    public static $wapClientFlag = array(
        'Mozilla/5.0 (iPhone; CPU iPhone OS 9_2_1 like Mac OS X) AppleWebKit/601.1.46 (KHTML, like Gecko) Mobile/13D15 MicroMessenger/6.3.16', //iPhone
        'Mozilla/5.0 (Linux; U; Android 6.0; zh-cn; HUAWEI CAZ-AL10 Build/HUAWEICAZ-AL10) AppleWebKit/534.30 (KHTML, like Gecko) Version/4.0 UCBrowser/1.0.0.100 U3/0.8.0 Mobile Safari/534.30 AliApp(TB/6.5.1) WindVane/8.0.0 1080X1788 GCanvas/1.4.2.21', //华为
        'Mozilla/5.0 (Linux; U; Android 6.0; zh-cn; MI 5 Build/MRA58K) AppleWebKit/537.36 (KHTML, like Gecko) Version/4.0 Chrome/46.0.2490.85 Mobile Safari/537.36 XiaoMi/MiuiBrowser/8.1.6', //小米5
        'Mozilla/5.0 (Linux; U; Android 6.0; zh-CN; HUAWEI NXT-TL00 Build/HUAWEINXT-TL00) AppleWebKit/537.36 (KHTML, like Gecko) Version/4.0 Chrome/40.0.2214.89 UCBrowser/11.3.8.909 Mobile Safari/537.36', //华为Mate 8
        'Mozilla/5.0 (Linux; U; Android 6.0.1; zh-cn; vivo X9 Build/MMB29M) AppleWebKit/534.30 (KHTML, like Gecko) Version/4.0 UCBrowser/1.0.0.100 U3/0.8.0 Mobile Safari/534.30 AliApp(TB/6.5.1) WindVane/8.0.0 1080X1920 GCanvas/1.4.2.21', //vivo X9
        'Mozilla/5.0 (Linux; U; Android 5.1; zh-CN; OPPO A59m Build/LMY47I) AppleWebKit/537.36 (KHTML, like Gecko) Version/4.0 Chrome/40.0.2214.89 UCBrowser/11.4.1.939 Mobile Safari/537.36', //OPPO A59
        'Mozilla/5.0 (Linux; Android 6.0.1; SM-G9250 Build/MMB29K; wv) AppleWebKit/537.36 (KHTML, like Gecko) Version/4.0 Chrome/48.0.2564.106 Mobile Safari/537.36 baiduboxapp/6.3.1 (Baidu; P1 6.0.1)', //三星
        'Mozilla/5.0 (Linux; U; Android 4.0.3; zh-cn) AppleWebKit/530.17 (KHTML, like Gecko) FlyFlow/2.2 Version/4.0 Mobile Safari/530.17', //魅族MX
        'Mozilla/5.0 (iPad; CPU OS 7_1 like Mac OS X) AppleWebKit/537.51.2 (KHTML, like Gecko) Version/7.0 Mobile/11D167 Safari/9537.53', //iPad 2
        'Mozilla/5.0 (Linux; U; Android 4.4.4; zh-cn; HM NOTE 1LTETD Build/KTU84P) AppleWebKit/534.24 (KHTML, like Gecko) Version/4.0 Mobile Safari/534.24 T5/2.0 baiduboxapp/6.1 (Baidu; P1 4.4.4)', //红米Note
        'Mozilla/5.0 (Linux; U; Android 6.0.1; zh-cn; ZTE B2015 Build/MMB29M) AppleWebKit/537.36 (KHTML, like Gecko)Version/4.0 Chrome/37.0.0.0 MQQBrowser/6.0 Mobile Safari/537.36', //中兴
        'Mozilla/5.0 (Linux; U; Android 6.0.1; zh-CN; ATH-AL00 Build/HONORATH-AL00) AppleWebKit/537.36 (KHTML, like Gecko) Version/4.0 Chrome/40.0.2214.89 UCBrowser/11.2.1.888 Mobile Safari/537.36',//华为荣耀7i
        'Mozilla/5.0 (Linux; Android 6.0.1; C106-6 Build/ZOXCNFN5801710251S) AppleWebKit/537.36 (KHTML, like Gecko) Version/4.0 Chrome/35.0.1916.138 Mobile Safari/537.36 T7/7.4 baiduboxapp/8.1 (Baidu; P1 6.0.1)', //酷派C106-6
        'Mozilla/5.0 (iPhone 6s; CPU iPhone OS 9_2 like Mac OS X) AppleWebKit/601.1.46 (KHTML, like Gecko) Version/9.0 MQQBrowser/7.1.1 Mobile/13C75 Safari/8536.25 MttCustomUA/2', //iPhone 6s
        'Mozilla/5.0 (iPhone; CPU iPhone OS 10_2_1 like Mac OS X) AppleWebKit/602.4.6 (KHTML, like Gecko) Mobile/14D27 baiduboxapp/0_01.5.2.8_enohpi_8022_2421/1.2.01_1C2%257enohPi/1099a/18E16F5C9907DAD6754C78620E827717E0F9CB210FCBHRRMKLS/1', //iPhone手机百度
        'Mozilla/5.0 (Linux; U; Android 4.4.4; zh-cn; MI NOTE LTE Build/KTU84P) AppleWebKit/534.30 (KHTML, like Gecko) Version/4.0 UCBrowser/1.0.0.100 U3/0.8.0 Mobile Safari/534.30 AliApp(TB/6.4.3) WindVane/8.0.0 1080X1920 GCanvas/1.4.2.21', //小米Note UC浏览器
        'Mozilla/5.0 (Linux; U; Android 7.0; zh-cn; FRD-AL10 Build/HUAWEIFRD-AL10) AppleWebKit/534.30 (KHTML, like Gecko) Version/4.0 UCBrowser/1.0.0.100 U3/0.8.0 Mobile Safari/534.30 AliApp(TB/6.5.0) WindVane/8.0.0 1080X1794 GCanvas/1.4.2.21', //华为荣耀8高配版 UC浏览器
        'Mozilla/5.0 (Linux; U; Android 5.1; zh-cn; m3 note Build/LMY47I) AppleWebKit/534.30 (KHTML, like Gecko) Version/4.0 UCBrowser/1.0.0.100 U3/0.8.0 Mobile Safari/534.30 AliApp(TB/6.5.1) WindVane/8.0.0 1080X1920 GCanvas/1.4.2.21', //魅蓝note 3
        'Mozilla/5.0 (Linux; U; Android 5.1.1; zh-cn; NX529J Build/LMY47V) AppleWebKit/534.30 (KHTML, like Gecko) Version/4.0 UCBrowser/1.0.0.100 U3/0.8.0 Mobile Safari/534.30 AliApp(TB/6.4.3) WindVane/8.0.0 1080X1920 GCanvas/1.4.2.21', //努比亚Z11 Mini
        'Mozilla/5.0 (Linux; U; Android 7.0; zh-cn; MHA-AL00 Build/HUAWEIMHA-AL00) AppleWebKit/534.30 (KHTML, like Gecko) Version/4.0 UCBrowser/1.0.0.100 U3/0.8.0 Mobile Safari/534.30 AliApp(TB/6.5.0) WindVane/8.0.0 1080X1812 GCanvas/1.4.2.21', //华为Mate 9
        'Mozilla/5.0 (Linux; U; Android 6.0.1; zh-cn; Le X820 Build/FEXCNFN5902012151S) AppleWebKit/537.36 (KHTML, like Gecko)Version/4.0 Chrome/37.0.0.0 MQQBrowser/7.3 Mobile Safari/537.36', //乐视Le X820
        'Mozilla/5.0 (iPhone 6; CPU iPhone OS 10_2_1 like Mac OS X) AppleWebKit/602.4.6 (KHTML, like Gecko) Version/10.0 MQQBrowser/7.3 Mobile/14D27 Safari/8536.25 MttCustomUA/2 QBWebViewType/1', //iPhone 6
        'Mozilla/5.0 (iPhone; CPU iPhone OS 10_1 like Mac OS X) AppleWebKit/602.2.14 (KHTML, like Gecko) Mobile/14B72 rabbit/1.0 baiduboxapp/0_0.0.1.7_enohpi_4331_057/1.01_2C2%7enohPi/1099a/6C098F1CCE0764F9FA70F99DA9974B9B200A469E0FCHCTFCNPL/1', //iPhone 7
        'Mozilla/5.0 (iPhone 6sp; CPU iPhone OS 10_1_1 like Mac OS X) AppleWebKit/602.2.14 (KHTML, like Gecko) Version/6.0 MQQBrowser/6.9.1 Mobile/14B100 Safari/8536.25 MttCustomUA/2', //iPhone 6s Plus
        'Mozilla/5.0 (Linux; U; Android 6.0.1; zh-cn; ZTE B2015 Build/MMB29M) AppleWebKit/537.36 (KHTML, like Gecko)Version/4.0 Chrome/37.0.0.0 MQQBrowser/6.0 Mobile Safari/537.36', //中兴AXON Mini
        'Mozilla/5.0 (Linux; U; Android 5.1.1; zh-CN; HUAWEI M2-A01L Build/HUAWEIM2-A01L) AppleWebKit/537.36 (KHTML, like Gecko) Version/4.0 Chrome/40.0.2214.89 UCBrowser/11.2.5.884 Mobile Safari/537.36', //华为M2平板电脑
    );

    /**
     * 单个查询
     * @param $url
     * @param string $postfields
     * @param string $cookiefile
     * @param string $header
     * @param int $time_out 超时时间
     * @param string $type 请求端
     * @return mixed
     */
    public static function curlRequest($url, $postfields = '', $cookiefile = '', $header = '', $time_out = 60, $type = '')
    {
        $ch = curl_init();
        $array = array ();
        if ($type && strpos($type, "m_") !== false) {
            shuffle(self::$wapClientFlag);
            $flag = reset(self::$wapClientFlag);
        } else {
            shuffle(self::$clientFlag);
            $flag = reset(self::$clientFlag);
        }

        $array [CURLOPT_USERAGENT] = $flag;
        $array [CURLOPT_URL] = $url;
        $array [CURLOPT_REFERER] = $url; //请求重定向地址
        $array [CURLOPT_HEADER] = 0;
        $array [CURLOPT_RETURNTRANSFER] = true;
        $array [CURLOPT_FOLLOWLOCATION] = true; //重定向
        $array [CURLOPT_AUTOREFERER] = true;
        $array [CURLINFO_HEADER_OUT] = true;

        $array [CURLOPT_TIMEOUT] = $time_out;
        $array [CURLOPT_CONNECTTIMEOUT] = $time_out;
        if (!empty($postfields)) {
            $array [CURLOPT_POST] = true;
            $array [CURLOPT_POSTFIELDS] = $postfields;
        }
//        if (!empty($cookiefile)) {
//            $array [CURLOPT_COOKIEJAR] = $cookiefile;
//            $array [CURLOPT_COOKIEFILE] = $cookiefile;
//        }
        $cookie_jar = $cookiefile ? $cookiefile : '';
//        $cookie_jar = is_file($cookiefile) ? $cookiefile : self::getCookiePath($cookiefile);
        @chown($cookie_jar, 'www');
        @chgrp($cookie_jar, 'www');
        $array [CURLOPT_COOKIEJAR] = $cookie_jar;
        $array [CURLOPT_COOKIEFILE] = $cookie_jar;
        $ip = rand(0, 255) . '.' . rand(0, 255) . '.' . rand(0, 255) . '.' . rand(0, 255); //随机ip
        $array[CURLOPT_HTTPHEADER] = array('X-FORWARDED-FOR:' . $ip . '','CLIENT-IP:' . $ip . ''); //伪造ip
        if (empty($header)) {
            $header = array('Expect:');
        } else {
            $header[] = 'Expect:';
        }
        $array[CURLOPT_HTTPHEADER] = $header;
        curl_setopt_array($ch, $array); // 传入curl参数
        $content['html'] = curl_exec($ch);
        $content['info'] = curl_getinfo($ch);
        $content['errno'] = curl_errno($ch);
        $content['cookie'] = $cookie_jar; // 执行
        curl_close($ch); // 关闭
        return $content;
    }

    /**
     * 模拟百度蜘蛛请求
     * @param $url
     * @param string $postfields
     * @param string $cookiefile
     * @param string $header
     * @param int $time_out
     * @return mixed
     */
    public static function baiduSpiderRequest($url, $postfields = '', $cookiefile = '', $header = '', $time_out = 60)
    {
        $ch = curl_init();
        //随机生成IP
        $ip = rand(0, 255) . '.' . rand(0, 255) . '.' . rand(0, 255) . '.' . rand(0, 255); // 百度 蜘蛛
//        $rand = rand(195,230);
//        $ip = '101.226.169.'.$rand;
        $timeout = 15;
        curl_setopt($ch, CURLOPT_URL, $url);
        curl_setopt($ch, CURLOPT_TIMEOUT, $time_out);
        //伪造百度 蜘蛛IP
        curl_setopt($ch, CURLOPT_HTTPHEADER, array('X-FORWARDED-FOR:' . $ip . '', 'CLIENT-IP:' . $ip . ''));
        $PC_UA = array(
            "Mozilla/5.0 (compatible; Baiduspider/2.0; +http://www.baidu.com/search/spider.html)",
            "Mozilla/5.0 (compatible;Baiduspider-render/2.0; +http://www.baidu.com/search/spider.html)",
        );
        $WAP_UA = array(
            "Mozilla/5.0 (Linux;u;Android 4.2.2;zh-cn;) AppleWebKit/534.46 (KHTML,likeGecko) Version/5.1 Mobile Safari/10600.6.3 (compatible; Baiduspider/2.0;+http://www.baidu.com/search/spider.html)",
            "Mozilla/5.0 (iPhone; CPU iPhone OS 9_1 likeMac OS X) AppleWebKit/601.1.46 (KHTML, like Gecko) Version/9.0 Mobile/13B143Safari/601.1 (compatible; Baiduspider-render/2.0; +http://www.baidu.com/search/spider.html)",
        );
        shuffle($PC_UA);
        $PC_UA = reset($PC_UA);
        $cookie_jar = is_file($cookiefile) ? $cookiefile : self::getCookiePath($cookiefile);
        curl_setopt($ch, CURLOPT_COOKIEJAR, $cookie_jar);
        curl_setopt($ch, CURLOPT_COOKIEFILE, $cookie_jar);
        curl_setopt($ch, CURLOPT_FOLLOWLOCATION, true); //跟踪重定向页面
        //伪造百度 蜘蛛头部
        curl_setopt($ch, CURLOPT_USERAGENT, $PC_UA);//构造UA来路地址
        curl_setopt($ch, CURLOPT_RETURNTRANSFER, 1);
        curl_setopt($ch, CURLOPT_HEADER, 0);
        curl_setopt($ch, CURLOPT_CONNECTTIMEOUT, $timeout);
        curl_setopt($ch, CURLOPT_SSL_VERIFYPEER, false);
        curl_setopt($ch, CURLOPT_IPRESOLVE, CURL_IPRESOLVE_V4);
        $content ['html'] = curl_exec($ch);
        $content ['info'] = curl_getinfo($ch);
        $content ['errno'] = curl_errno($ch);
        $content ['cookie'] = $cookie_jar;
        return $content;
    }

    /**
     * 批量查询
     * @param $url_array
     * @param int $wait_usec
     * @param string $type
     * @return array|bool
     */
    public static function multiGetUrl($url_array, $wait_usec = 0, $type = "PC")
    {
        if (!is_array($url_array)) {
            return false;
        }
        $wait_usec = intval($wait_usec);

        $data    = array();
        $handle  = array();
        $running = 0;

        $mh = curl_multi_init(); // multi curl handler

        $i = 0;
        foreach ($url_array as $k => $url) {
            $ch = curl_init();
            if (is_array($type) && count($type) > 0) {
                if (strpos($type[$k], "m_") === false) {
                    shuffle(self::$clientFlag);
                    $flag = reset(self::$clientFlag);
                } else {
                    shuffle(self::$wapClientFlag);
                    $flag = reset(self::$wapClientFlag);
                }
            } else {
                if ($type == "PC") {
                    shuffle(self::$clientFlag);
                    $flag = reset(self::$clientFlag);
                } else {
                    shuffle(self::$wapClientFlag);
                    $flag = reset(self::$wapClientFlag);
                }
            }

            curl_setopt($ch, CURLOPT_REFERER, self::$referer); //构造来路
            curl_setopt($ch, CURLOPT_URL, $url);
            curl_setopt($ch, CURLOPT_RETURNTRANSFER, 1); // return don't print
            curl_setopt($ch, CURLOPT_TIMEOUT, $wait_usec ? $wait_usec : 10);
            curl_setopt($ch, CURLOPT_USERAGENT, $flag);
            curl_setopt($ch, CURLOPT_FOLLOWLOCATION, 1); // 302 redirect
            curl_setopt($ch, CURLOPT_MAXREDIRS, 7);
            curl_setopt($ch, CURLOPT_SSL_VERIFYPEER, false); // 信任任何证书
            curl_setopt($ch, CURLOPT_SSL_VERIFYHOST, 1); // 检查证书中是否设置域名
            $ip = rand(0, 255) . '.' . rand(0, 255) . '.' . rand(0, 255) . '.' . rand(0, 255); //随机ip
            curl_setopt($ch, CURLOPT_HTTPHEADER, array('X-FORWARDED-FOR:' . $ip . '','CLIENT-IP:' . $ip . ''));
            curl_multi_add_handle($mh, $ch); // 把 curl resource 放進 multi curl handler 裡

            $handle[$i++] = $ch;
        }

        do {
            $mrc = curl_multi_exec($mh, $active);
        } while ($mrc == CURLM_CALL_MULTI_PERFORM);
//            var_dump('循环:'.$active);
        while ($active and $mrc == CURLM_OK) {
//                var_dump('判断:'.curl_multi_select($mh));
            if (curl_multi_select($mh) != -1) {
                do {
                    $mrc = curl_multi_exec($mh, $active);
//                        var_dump('do:'.$mrc);
                } while ($mrc == CURLM_CALL_MULTI_PERFORM);
            }
        }
        /* 讀取資料 */
        foreach ($handle as $i => $ch) {
            $content  = curl_multi_getcontent($ch);
            $data[$i]['html'] = (curl_errno($ch) == 0) ? $content : false;
            $data[$i]['h'] = curl_getinfo($ch);
            $data[$i]['e'] = curl_errno($ch);
        }

        /* 移除 handle*/
        foreach ($handle as $ch) {
            curl_multi_remove_handle($mh, $ch);
        }

        curl_multi_close($mh);
        return $data;
    }

    protected static function getCookiePath($path)
    {
        FileHelper::mkdirs(dirname($path));
        return $path;
    }

    /**
     * @param $url
     * @param string $method
     * @param array $data
     * @param int $count
     * @return array
     * @throws \yii\base\InvalidConfigException
     */
    public static function againRequest($url, $method = 'GET', $data = [], $count = 0)
    {
        try {
            $ip      = Url::rand_ip();
            shuffle(CurlHelper::$wapClientFlag);
            $randomUserAgent = reset(CurlHelper::$wapClientFlag);
            $curl    = new Client(['baseUrl' => $url]);
            $request = $curl->createRequest()->setMethod($method)->addHeaders([
                'User-Agent'      => $randomUserAgent,
                'X-FORWARDED-FOR' => $ip,
                'CLIENT-IP'       => $ip,
            ])->setData($data)->send();
            if ($request->headers['http-code'] != 200) {
                if ($count < 5) {
                    $count++;
                    return self::againRequest($url, $method, $data, $count);
                }
                return [
                    'code' => $request->headers['http-code'],
                    'message' => '第' . $count . '次 请求错误:' . $request->headers['message']
                ];
            }
            $response = json_decode($request->content, true);
            if ($response['code'] != 200) {
                if ($count < 5) {
                    $count++;
                    return self::againRequest($url, $method, $data, $count);
                }
                return [
                    'code' => $response['code'],
                    'message' => '第' . $count . '次 返回错误:' . $response['message']
                ];
            }
            return [
                'code' => 200,
                'message' => '成功'
            ];
        } catch (Exception $e) {
            if ($count < 5) {
                $count++;
                return self::againRequest($url, $method, $data, $count);
            }
            return [
                'code' => 422,
                'message' => $e->getMessage()
            ];
        }
    }

    /**
     * @param $url
     * @param string $method 请求方式
     * @param array $setData 请求状态,重定向会有多个headers['http-code'][0]、重定向地址headers['location']
     * @return array
     * @throws \yii\base\InvalidConfigException
     */
    public static function client($url, $method = 'GET', $setData = [])
    {
        try {
            $ip      = Url::rand_ip();
            shuffle(self::$wapClientFlag);
            $user_agent = reset(self::$wapClientFlag);
            $curl    = new Client(['baseUrl' => $url]);
            $response = $curl->createRequest()->setMethod($method)->addHeaders([
                'User-Agent'      => $user_agent,
                'X-FORWARDED-FOR' => $ip,
                'CLIENT-IP'       => $ip,
            ])->setData($setData)->send();
            return ['code' => 200 ,'headers' => $response->headers,'html' => $response->content];
        } catch (Exception $e) {
            return ['code' => 400 ,'headers' => '','html' => $e->getMessage()];
        }
    }
}
