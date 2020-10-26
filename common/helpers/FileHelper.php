<?php

namespace app\common\helpers;

use yii\helpers\BaseFileHelper;
use Yii;

/**
 * Class FileHelper
 * @package common\helpers
 * @author jianyan74 <751393839@qq.com>
 */
class FileHelper extends BaseFileHelper
{
    public $_speed = 0;
    /**
     * 检测目录并循环创建目录
     *
     * @param $catalogue
     */
    public static function mkdirs($catalogue)
    {
        if (!file_exists($catalogue)) {
            self::mkdirs(dirname($catalogue));
            mkdir($catalogue, 0777);
            exec('chown -R www.www '.$catalogue);
        }

        return true;
    }

    /**
     * 写入日志
     *
     * @param $path
     * @param $content
     * @return bool|int
     */
    public static function writeLog($path, $content)
    {
        self::mkdirs(dirname($path));
        return file_put_contents($path, date('Y-m-d H:i:s'). ">>>" . $content."\r\n", FILE_APPEND);
    }

    /**
     * 获取文件夹大小
     *
     * @param string $dir 根文件夹路径
     * @return int
     */
    public static function getDirSize($dir)
    {
        $handle = opendir($dir);
        $sizeResult = 0;
        while (false !== ($FolderOrFile = readdir($handle))) {
            if ($FolderOrFile != "." && $FolderOrFile != "..") {
                if (is_dir("$dir/$FolderOrFile")) {
                    $sizeResult += self::getDirSize("$dir/$FolderOrFile");
                }
                else {
                    $sizeResult += filesize("$dir/$FolderOrFile");
                }
            }
        }

        closedir($handle);
        return $sizeResult;
    }

    /**
     * 基于数组创建目录
     *
     * @param $files
     */
    public static function createDirOrFiles($files)
    {
        foreach ($files as $key => $value) {
            if (substr($value, -1) == '/') {
                mkdir($value);
            }
            else {
                file_put_contents($value, '');
            }
        }
    }

    /**
     * 文件大小字节转换对应的单位
     * @param $size
     * @return string
     */
    public static function convert($size)
    {
        $unit = array('b','kb','MB','GB','tb','pb');
        return round($size / pow(1024, ($i = floor(log($size, 1024)))), 2) . '' . $unit[$i];
    }

    public static function downloadVideo($url)
    {
//        $is_url = Url::isUrl($url);
//        if (!$is_url) {
//            exit('不是正确的链接地址'); //exit掉，下载下来打开会显示无法播放，格式不支持，文件已损坏等
//        }
        //获取文件信息
//        $fileExt = pathinfo($url);
        //获取文件的扩展名
//        $allowDownExt = array ('mp4', 'mov');
        //检测文件类型是否允许下载
//        if (!in_array($fileExt['extension'], $allowDownExt)) {
//            exit('不支持该格式');
//        }
        // 设置浏览器下载的文件名，这里还以原文件名一样
        $filename = basename($url);
// 获取远程文件大小
// 注意filesize()无法获取远程文件大小
        $headers = get_headers($url, 1);
        $fileSize = $headers['Content-Length'];
        if (ini_get('zlib.output_compression')) {
            ini_set('zlib.output_compression', 'Off');
        }
        header_remove('Content-Encoding');
// 设置header头
// 因为不知道文件是什么类型的，告诉浏览器输出的是字节流
        header('Content-Type: application/octet-stream');
// 告诉浏览器返回的文件大小类型是字节
        header('Accept-Ranges:bytes');
// 告诉浏览器返回的文件大小
        header('Content-Length: ' . $fileSize);
// 告诉浏览器文件作为附件处理并且设定最终下载完成的文件名称
        header('Content-Disposition: attachment; filename="' . $filename . '"');
//针对大文件，规定每次读取文件的字节数为4096字节，直接输出数据

        $read_buffer = 4096; //4096
        $handle = fopen($url, 'rb');
//总的缓冲的字节数
        $sum_buffer = 0;
//只要没到文件尾，就一直读取
        while (!feof($handle) && $sum_buffer < $fileSize) {
            echo fread($handle, $read_buffer);
            $sum_buffer += $read_buffer;
        }
        fclose($handle);
        exit;
    }

    /**
     * @param String $file 要下载的文件路径
     * @param String $name 文件名称,为空则与下载的文件名称一样
     * @param boolean $reload 是否开启断点续传
     * @return string
     */
    public static function downloadFile($file, $name = '', $reload = false)
    {
        $log_path = Yii::getAlias('@runtime') . '/api/' . date('Ym') . '/' . date('d') . '/download.txt';
        FileHelper::writeLog($log_path, $file);
        $fp = fopen($file, 'rb');
        if ($fp) {
            if ($name == '') {
                $name = basename($file);
            }
            $header_array = get_headers($file, true);
            // 下载本地文件，获取文件大小
            if (!$header_array) {
                $file_size = filesize($file);
            } else {
                $file_size = $header_array['Content-Length'];
            }
            FileHelper::writeLog($log_path, json_encode($_SERVER, JSON_UNESCAPED_UNICODE));
            if (isset($_SERVER['HTTP_RANGE']) && !empty($_SERVER['HTTP_RANGE'])) {
                $ranges = self::getRange($file_size);
            } else {
                //第一次连接
                $size2 = $file_size - 1;
                header("Content-Range: bytes 0-$size2/$file_size"); //Content-Range: bytes 0-4988927/4988928
                header("Content-Length: " . $file_size); //输出总长
            }

            $ua = $_SERVER["HTTP_USER_AGENT"];//判断是什么类型浏览器
            header('cache-control:public');
            header('content-type:application/octet-stream');

            $encoded_filename = urlencode($name);
            $encoded_filename = str_replace("+", "%20", $encoded_filename);

            //解决下载文件名乱码
            if (preg_match("/MSIE/", $ua) || preg_match("/Trident/", $ua)) {
                header('Content-Disposition: attachment; filename="' . $encoded_filename . '"');
            } else if (preg_match("/Firefox/", $ua)) {
                header('Content-Disposition: attachment; filename*="utf8\'\'' . $name . '"');
            } else if (preg_match("/Chrome/", $ua)) {
                header('Content-Disposition: attachment; filename="' . $encoded_filename . '"');
            } else {
                header('Content-Disposition: attachment; filename="' . $name . '"');
            }
            //header('Content-Disposition: attachment; filename="' . $name . '"');

            if ($reload && $ranges != null) { // 使用续传
                header('HTTP/1.1 206 Partial Content');
                header('Accept-Ranges:bytes');

                // 剩余长度
                header(sprintf('content-length:%u', $ranges['end'] - $ranges['start']));

                // range信息
                header(sprintf('content-range:bytes %s-%s/%s', $ranges['start'], $ranges['end'], $file_size));
                FileHelper::writeLog($log_path, sprintf('content-length:%u', $ranges['end'] - $ranges['start']));
                // fp指针跳到断点位置
                fseek($fp, sprintf('%u', $ranges['start']));
            } else {
                header('HTTP/1.1 200 OK');
                header('content-length:' . $file_size);
            }

            while (!feof($fp)) {
                echo fread($fp, 4096);
                ob_flush();
            }
            ($fp != null) && fclose($fp);
        } else {
            return '';
        }
    }

    /** 设置下载速度
     * @param int $speed
     */
    public function setSpeed($speed)
    {
        if (is_numeric($speed) && $speed > 16 && $speed < 4096) {
            $this->_speed = $speed;
        }
    }

    /** 获取header range信息
     * @param int $file_size 文件大小
     * @return Array
     */
    private static function getRange($file_size)
    {
        if (isset($_SERVER['HTTP_RANGE']) && !empty($_SERVER['HTTP_RANGE'])) {
            $range = $_SERVER['HTTP_RANGE'];
            $range = preg_replace('/[\s|,].*/', '', $range);
            $range = explode('-', substr($range, 6));
            if (count($range) < 2) {
                $range[1] = $file_size;
            }
            $range = array_combine(array('start','end'), $range);
            if (empty($range['start'])) {
                $range['start'] = 0;
            }
            if (empty($range['end'])) {
                $range['end'] = $file_size;
            }
            return $range;
        }
        return null;
    }
}