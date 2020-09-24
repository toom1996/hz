<?php

namespace common\helpers;

use Yii;
use yii\helpers\BaseStringHelper;
use Ramsey\Uuid\Uuid;

/**
 * Class StringHelper
 * @package common\helpers
 * @author jianyan74 <751393839@qq.com>
 */
class StringHelper extends BaseStringHelper
{
    /**
     * 生成Uuid
     *
     * @param string $type 类型 默认时间 time/md5/random/sha1/uniqid 其中uniqid不需要特别开启php函数
     * @param string $name 加密名
     * @return string
     * @throws \Exception
     */
    public static function uuid($type = 'time', $name = 'php.net')
    {
        switch ($type) {
            // 生成版本1（基于时间的）UUID对象
            case  'time' :
                $uuid = Uuid::uuid1();

                break;
            // 生成第三个版本（基于名称的和散列的MD5）UUID对象
            case  'md5' :
                $uuid = Uuid::uuid3(Uuid::NAMESPACE_DNS, $name);

                break;
            // 生成版本4（随机）UUID对象
            case  'random' :
                $uuid = Uuid::uuid4();

                break;
            // 产生一个版本5（基于名称和散列的SHA1）UUID对象
            case  'sha1' :
                $uuid = Uuid::uuid5(Uuid::NAMESPACE_DNS, $name);

                break;
            // php自带的唯一id
            case  'uniqid' :
                return md5(uniqid(md5(microtime(true) . self::randomNum(8)), true));

                break;
        }

        return $uuid->toString();
    }

    /**
     * 日期转时间戳
     *
     * @param $value
     * @return false|int
     */
    public static function dateToInt($value)
    {
        if (empty($value)) {
            return $value;
        }

        if (!is_numeric($value)) {
            return strtotime($value);
        }

        return $value;
    }

    /**
     * 获取缩略图地址
     *
     * @param string $url
     * @param int $width
     * @param int $height
     */
    public static function getThumbUrl($url, $width, $height)
    {
        $url = str_replace('attachment/images', 'attachment/thumb', $url);
        return self::createThumbUrl($url, $width, $height);
    }

    /**
     * 创建缩略图地址
     *
     * @param string $url
     * @param int $width
     * @param int $height
     */
    public static function createThumbUrl($url, $width, $height)
    {
        $url = explode('/', $url);
        $nameArr = explode('.', end($url));
        $url[count($url) - 1] = $nameArr[0] . "@{$width}x{$height}." . $nameArr[1];

        return implode('/', $url);
    }

    /**
     * 获取压缩图片地址
     *
     * @param $url
     * @param $quality
     * @return string
     */
    public static function getAliasUrl($url, $alias = 'compress')
    {
        $url = explode('/', $url);
        $nameArr = explode('.', end($url));
        $url[count($url) - 1] = $nameArr[0] . "@{$alias}." . $nameArr[1];

        return implode('/', $url);
    }

    /**
     * 根据Url获取本地绝对路径
     *
     * @param $url
     * @param string $type
     * @return string
     */
    public static function getLocalFilePath($url, $type = 'images')
    {
        $prefix = Yii::getAlias("@root/") . 'web';
        if (Yii::$app->params['uploadConfig'][$type]['fullPath'] == true) {
            $url = str_replace(Yii::$app->request->hostInfo, '', $url);
        }

        return $prefix . $url;
    }

    /**
     * 分析枚举类型配置值
     *
     * 格式 a:名称1,b:名称2
     *
     * @param $string
     * @return array
     */
    public static function parseAttr($string)
    {
        $array = preg_split('/[,;\r\n]+/', trim($string, ",;\r\n"));
        if (strpos($string, ':')) {
            $value = [];
            foreach ($array as $val) {
                list($k, $v) = explode(':', $val);
                $value[$k] = $v;
            }
        } else {
            $value = $array;
        }

        return $value;
    }

    /**
     * 返回字符串在另一个字符串中第一次出现的位置
     *
     * @param $string
     * @param $find
     * @return bool
     * true | false
     */
    public static function strExists($string, $find)
    {
        return !(strpos($string, $find) === false);
    }

    /**
     * XML 字符串载入对象中
     *
     * @param string $string 必需。规定要使用的 XML 字符串
     * @param string $class_name 可选。规定新对象的 class
     * @param int $options 可选。规定附加的 Libxml 参数
     * @param string $ns
     * @param bool $is_prefix
     * @return bool|\SimpleXMLElement
     */
    public static function simplexmlLoadString(
        $string,
        $class_name = 'SimpleXMLElement',
        $options = 0,
        $ns = '',
        $is_prefix = false
    ) {
        libxml_disable_entity_loader(true);
        if (preg_match('/(\<\!DOCTYPE|\<\!ENTITY)/i', $string)) {
            return false;
        }

        return simplexml_load_string($string, $class_name, $options, $ns, $is_prefix);
    }

    /**
     * 字符串提取汉字
     *
     * @param $string
     * @return mixed
     */
    public static function strToChineseCharacters($string)
    {
        preg_match_all("/[\x{4e00}-\x{9fa5}]+/u", $string, $chinese);

        return $chinese;
    }

    /**
     * 字符首字母转大小写
     *
     * @param $str
     * @return mixed
     */
    public static function strUcwords($str)
    {
        return str_replace(' ', '', ucwords(str_replace('-', ' ', $str)));
    }

    /**
     * 驼峰命名法转下划线风格
     *
     * @param $str
     * @return string
     */
    public static function toUnderScore($str)
    {
        $array = [];
        for ($i = 0; $i < strlen($str); $i++) {
            if ($str[$i] == strtolower($str[$i])) {
                $array[] = $str[$i];
            } else {
                if ($i > 0) {
                    $array[] = '-';
                }

                $array[] = strtolower($str[$i]);
            }
        }

        return implode('', $array);
    }

    /**
     * 获取字符串后面的字符串
     *
     * @param string $fileName 文件名
     * @param string $type 字符类型
     * @param int $length 长度
     * @return bool|string
     */
    public static function clipping($fileName, $type = '.', $length = 0)
    {
        return substr(strtolower(strrchr($fileName, $type)), $length);
    }

    /**
     * 获取随机字符串
     *
     * @param $length
     * @param bool $numeric
     * @return string
     */
    public static function random($length, $numeric = false)
    {
        $seed = base_convert(md5(microtime() . $_SERVER['DOCUMENT_ROOT']), 16, $numeric ? 10 : 35);
        $seed = $numeric ? (str_replace('0', '', $seed) . '012340567890') : ($seed . 'zZ' . strtoupper($seed));

        $hash = '';
        if (!$numeric) {
            $hash = chr(rand(1, 26) + rand(0, 1) * 32 + 64);
            $length--;
        }

        $max = strlen($seed) - 1;
        for ($i = 0; $i < $length; $i++) {
            $hash .= $seed{mt_rand(0, $max)};
        }

        return $hash;
    }

    /**
     * 获取数字随机字符串
     *
     * @param bool $prefix 判断是否需求前缀
     * @param int $length 长度
     * @return string
     */
    public static function randomNum($prefix = false, $length = 8)
    {
        $str = $prefix ?? '';
        return $str . substr(implode(null, array_map('ord', str_split(md5(substr(uniqid(), 7, 13), 1)))), 0, $length);
    }

    /**
     * 字符串匹配替换
     *
     * @param $search
     * @param $replace
     * @param $subject
     * @param null $count
     * @return mixed
     */
    public static function replace($search, $replace, $subject, &$count = null)
    {
        return str_replace($search, $replace, $subject, $count);
    }

    /**
     * 验证是否Windows
     *
     * @return bool
     */
    public static function isWindowsOS()
    {
        return strncmp(PHP_OS, 'WIN', 3) === 0;
    }

    /**
     * 将一个字符串部分字符用*替代隐藏
     *
     * @param string $string 待转换的字符串
     * @param int $bengin 起始位置，从0开始计数，当$type=4时，表示左侧保留长度
     * @param int $len 需要转换成*的字符个数，当$type=4时，表示右侧保留长度
     * @param int $type 转换类型：0，从左向右隐藏；1，从右向左隐藏；2，从指定字符位置分割前由右向左隐藏；3，从指定字符位置分割后由左向右隐藏；4，保留首末指定字符串
     * @param string $glue 分割符
     * @return bool|string
     */
    public static function hideStr($string, $bengin = 0, $len = 4, $type = 0, $glue = "@")
    {
        if (empty($string)) {
            return false;
        }

        $array = [];
        if ($type == 0 || $type == 1 || $type == 4) {
            $strlen = $length = mb_strlen($string);

            while ($strlen) {
                $array[] = mb_substr($string, 0, 1, "utf8");
                $string = mb_substr($string, 1, $strlen, "utf8");
                $strlen = mb_strlen($string);
            }
        }

        switch ($type) {
            case 0 :
                for ($i = $bengin; $i < ($bengin + $len); $i++) {
                    isset($array[$i]) && $array[$i] = "*";
                }

                $string = implode("", $array);
                break;
            case 1 :
                $array = array_reverse($array);
                for ($i = $bengin; $i < ($bengin + $len); $i++) {
                    isset($array[$i]) && $array[$i] = "*";
                }

                $string = implode("", array_reverse($array));
                break;
            case 2 :
                $array = explode($glue, $string);
                $array[0] = self::hideStr($array[0], $bengin, $len, 1);
                $string = implode($glue, $array);
                break;
            case 3 :
                $array = explode($glue, $string);
                $array[1] = self::hideStr($array[1], $bengin, $len, 0);
                $string = implode($glue, $array);
                break;
            case 4 :
                $left = $bengin;
                $right = $len;
                $tem = array();
                for ($i = 0; $i < ($length - $right); $i++) {
                    if (isset($array[$i])) {
                        $tem[] = $i >= $left ? "*" : $array[$i];
                    }
                }

                $array = array_chunk(array_reverse($array), $right);
                $array = array_reverse($array[0]);
                for ($i = 0; $i < $right; $i++) {
                    $tem[] = $array[$i];
                }
                $string = implode("", $tem);
                break;
        }

        return $string;
    }

    /**
     * 根据内容获取前30个字符串
     * @param $string
     * @return bool|mixed|string
     */
    public static function get_article_title($string)
    {
        if(!$string) return false;
        $string = str_ireplace('<p> </p>','',$string);
        $preg = '/<p.*?>(.*?)<\/p>/i';
        preg_match_all($preg,$string,$match);
        if(!$match[1]){
            $preg = '/<div.*?>(.*?)<\/div>/i';
            preg_match_all($preg,$string,$match);
        }
        if(is_array($match[1]) && count($match[1]) > 0){
            foreach ($match[1] as $v){
                $p = strip_tags(trim($v));
                $p = str_ireplace("<<P>", "<P>", $p);
                $p = preg_replace('/<script .*?\/script>/', '', $p);
                $p = preg_replace('/[\s\n]*/', '', strip_tags($p));
                $p = str_replace('&nbsp;', '', $p);
                $p = str_replace(' ', '', $p);
                $string = str_replace('　', '', $p);
                $stringNum = mb_strlen($string);
                $noChinese = preg_replace('/[^\x{4e00}-\x{9fa5}]/u', '', $p);//过滤非中文
                if(!trim($p) || $stringNum < 3 || !trim($noChinese)){ //过滤空的段落和符号
                    continue;
                }
                if($stringNum > 35){
                    $string = mb_substr($string, 0, 30);
                }
                break;
            }
        }else{
            $p = str_ireplace("<<P>", "<P>", $string);
            $p = preg_replace('/<script .*?\/script>/', '', $p);
            $p = preg_replace('/[\s\n]*/', '', strip_tags($p));
            $p = str_replace('&nbsp;', '', $p);
            $string = str_replace(' ', '', $p);
            $string = mb_substr($string, 0, 30);
        }

        $string = preg_replace_callback(    //执行一个正则表达式搜索并且使用一个回调进行替换
            '/./u',
            function (array $match) {
                return strlen($match[0]) >= 4 ? '' : $match[0];
            },
            $string);
        return $string;
    }

    /**
     * 获取首字母的拼音
     * @param $str
     * @return mixed|string
     */
    public static function getFirstLetter($str)
    {
        if(empty($str)) return '';

        $newStr = self::getFirstLetterByChineseCharList($str);
        if(!empty($newStr)) {
            return $newStr;
        }

        $fchar = ord($str{0});
        if($fchar >= ord("A") and $fchar <= ord("z") )return strtoupper($str{0});
        $s1 = iconv("UTF-8","GBK", $str);
        $s2 = iconv("GBK","UTF-8", $s1);
        if($s2 == $str){$s = $s1;}
        else{$s = $str;}
        $asc = ord($s{0}) * 256 + ord($s{1}) - 65536;
        if($asc >= -20319 and $asc <= -20284) return "A";
        if($asc >= -20283 and $asc <= -19776) return "B";
        if($asc >= -19775 and $asc <= -19219) return "C";
        if($asc >= -19218 and $asc <= -18711) return "D";
        if($asc >= -18710 and $asc <= -18527) return "E";
        if($asc >= -18526 and $asc <= -18240) return "F";
        if($asc >= -18239 and $asc <= -17923) return "G";
        if($asc >= -17922 and $asc <= -17418) return "I";
        if($asc >= -17417 and $asc <= -16475) return "J";
        if($asc >= -16474 and $asc <= -16213) return "K";
        if($asc >= -16212 and $asc <= -15641) return "L";
        if($asc >= -15640 and $asc <= -15166) return "M";
        if($asc >= -15165 and $asc <= -14923) return "N";
        if($asc >= -14922 and $asc <= -14915) return "O";
        if($asc >= -14914 and $asc <= -14631) return "P";
        if($asc >= -14630 and $asc <= -14150) return "Q";
        if($asc >= -14149 and $asc <= -14091) return "R";
        if($asc >= -14090 and $asc <= -13319) return "S";
        if($asc >= -13318 and $asc <= -12839) return "T";
        if($asc >= -12838 and $asc <= -12557) return "W";
        if($asc >= -12556 and $asc <= -11848) return "X";
        if($asc >= -11847 and $asc <= -11056) return "Y";
        if($asc >= -11055 and $asc <= -10247) return "Z";
        return '';
    }

    /**
     * 获取中文字符
     * @param $str
     * @return string
     */
    private static function getFirstLetterByChineseCharList($str)
    {
        if(empty($str)) return '';
        $chineseCharList = Yii::$app->params['chinese.char.list'];
        $firstLetter = self::cutStr($str, 0 ,1);
        foreach($chineseCharList as $v){
            if(strpos($v, $firstLetter) !== false) {
                return $v{0};
            }
        }
        return '其他';
    }

    /**
     * 截取字符串
     * @param $str
     * @param $start
     * @param $len
     * @param string $charset
     * @return string|string[]|null
     */
    public static function cutStr($str, $start, $len, $charset='utf-8')
    {
        if($charset=='utf-8')
        {
            return preg_replace('#^(?:[\x00-\x7F]|[\xC0-\xFF][\x80-\xBF]+){0,'.$start.'}'.'((?:[\x00-\x7F]|[\xC0-\xFF][\x80-\xBF]+){0,'.$len.'}).*#s','$1',$str);
        }
        $tmpstr = "";
        $strlen = $start + $len;
        for($i = 0; $i < $strlen; $i++) {
            if(ord(substr($str, $i, 1)) > 0xa0)
            {
                $tmpstr .= substr($str, $i, 2);
                $i++;
            }
            else
                $tmpstr .= substr($str, $i, 1);
        }
        return $tmpstr;
    }

    /**
     * 过滤掉邮箱
     * @param string $string
     * @return mixed|string
     */
    public static function filterEmail($string = '')
    {
        if (!$string) {
            return '';
        }
        $string = str_ireplace('邮箱', '', $string);
        $str1 = '/([\w-\.]+)@((\[[0-9]{1,3}\.[0-9]{1,3}\.[0-9]{1,3}\.)|(([\w-]+\.)+))([a-zA-Z]{2,4}|[0-9]{1,3})(\]?)/i';
        preg_match($str1, $string, $m1);
        if ($m1 && $m1[0]) { //找到完整邮箱就过滤
            $string = str_replace($m1[0], '', $string);
            return $string;
        }
        if (!$m1) {
            $pattern = '/@((\[[0-9]{1,3}\.[0-9]{1,3}\.[0-9]{1,3}\.)|(([\w-]+\.)+))([a-zA-Z]{2,4}|[0-9]{1,3})(\]?)/';
            preg_match($pattern, $string, $match);
            if ($match && $match[0]) { //找到后缀带有邮箱的就全过滤掉
                $string = '';
            }
            return $string;
        }
        return $string;
    }

    /**
     * 延迟执行
     * @param $sec 随机秒数
     */
    public static function sleep($sec)
    {
        while ($sec) {
            sleep(1);
            echo $sec . ".";
            if ($sec == 1) {
                echo "0";
            }
            $sec--;
        }
    }

    /**
     * 截取指定两个字符之间字符串
     * @param $string
     * @param $start
     * @param $end
     * @return string
     */
    public static function substr_between($string, $start, $end)
    {
        $start_str = mb_strpos($string,$start) + mb_strlen($start);
        $end_str = mb_strpos($string,$end) - $start_str;
        return mb_substr($string,$start_str,$end_str);
    }

    /**
     * 对字符串或者数组含非UTF-8，进行转换未UTF-8
     * @param $string
     * @return array|bool|int|string|string[]|null
     */
    public static function convert_encoding($string)
    {
        if(!is_array($string) && !is_int($string)) {
            return mb_convert_encoding($string, 'UTF-8', "UTF-8,ASCII,UTF-16");
        }

        foreach($string as $key => $value) {
            $string[$key] = mb_convert_encoding($value, 'UTF-8', "UTF-8,ASCII,UTF-16");
        }
        return $string;
    }

    /**
     * 过滤掉emoji表情
     * @param $content
     * @return mixed|string|string[]|null
     */
    public static function filter_Emoji($content)
    {
        $str = trim($content);
        $str = preg_replace_callback(    //执行一个正则表达式搜索并且使用一个回调进行替换
            '/./u',
            function (array $match) {
                return strlen($match[0]) >= 4 ? '' : $match[0];
            },
            $str);
        return $str;
    }

    /**
     * 过滤表情符号
     * @param $Str
     * @return string
     */
    public static function remove_emoji($Str)
    {
        $slashfound = false;
        $countoff = 0;
        $StrArr = str_split($Str); $NewStr = '';
        foreach ($StrArr as $Char) {
            $CharNo = ord($Char);
            if ($slashfound) {
                if ($CharNo == 117) {
                    $countoff = 4;
                } else {
                    $NewStr .= '\\';
                    $NewStr .= $Char;
                }
                $slashfound = false;
            } else if ($countoff > 0) {
                $countoff = $countoff - 1;
            } else if ($CharNo == 92) {
                $slashfound = true;
            } else {
                $NewStr .= $Char;
            }
        }
        return $NewStr;
    }
}