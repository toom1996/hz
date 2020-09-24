<?php

namespace common\helpers;

use Yii;
use Hashids\Hashids;

/**
 * ID加密辅助类
 *
 * Class HashidsHelper
 * @package common\helpers
 * @author jianyan74 <751393839@qq.com>
 */
class HashidsHelper
{
    /**
     * 长度
     *
     * @var int
     */
    public static $lenght = 10;

    /**
     * @var \Hashids\Hashids
     */
    protected static $hashids;

    /**
     * 加密
     *
     * @param mixed ...$numbers
     * @return string
     */
    public static function encode(...$numbers)
    {
        return self::getHashids()->encode(...$numbers);
    }

    /**
     * 解密
     *
     * @param string $hash
     * @return array
     */
    public static function decode(string $hash)
    {
        return self::getHashids()->decode($hash);
    }

    /**
     * @desc 要加密字符串
     * @param mixed|string $str
     *
     * $hashId = HashidsHelper::encodeHex('string');
     *
     * @return string
     */
    public static function encodeHex($str = '')
    {
        return self::getHashids()->encodeHex($str);
    }

    /**
     * @desc 要解密的hash
     * @param string $hash
     *
     * $id = HashidsHelper::decodeHex('wpfLh9iwsqt0uyCEFjHM');
     *
     * @return string
     */
    public static function decodeHex($hash = '')
    {
        return self::getHashids()->decodeHex($hash);
    }

    /**
     * @return Hashids
     */
    private static function getHashids()
    {
        if (!self::$hashids instanceof Hashids) {
            self::$hashids = new Hashids('MJB_HASH_ID_', self::$lenght); // all lowercase
        }

        return self::$hashids;
    }
}