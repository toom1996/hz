<?php
/**
 * Created by PhpStorm.
 * User: 毛阿毛
 * Date: 2019/4/25
 * Time: 11:57
 */
namespace common\helpers;

use Yii;
class OfficeHelper
{
    /**
     * libreoffice 启动
     *
     * 例如：/user/local/bin/libreoffice
     * 注意后面的空格
     * @var string
     */
    private static $officePath = 'libreoffice ';

    /**
     * 转码为html
     *
     * @param string $filePath 文件路径
     * @param string $outDir 输出目录
     */
    public static function transCoding($filePath, $outDir)
    {
        exec(self::$officePath . "--invisible --convert-to html $filePath --outdir $outDir", $out);
    }

    /**
     * 根据word文件转成的html文件，转码并获取内容
     * @param $filePath
     * @return array
     */
    public static function getBodyFromHtml($filePath)
    {
        ini_set('pcre.backtrack_limit', 999999999);
        $contents = file_get_contents($filePath);
        if (!$contents){
            return [
                'code' => 422,
                'msg' => '没有内容',
                'data' => []
            ];
        }

        $contents = stripslashes($contents);

        preg_match('/<body.*?>(.*?)<\/body>/is',$contents,$match);
        if ($match[1]){
            $contents = $match[1];
        }
        preg_match_all('/<img src="(.*?)".*?>/', $contents, $matches);

        if (is_array($matches[1]) && count($matches[1]) > 0) {
            $config = Yii::$app->params['uploadConfig']['images'];
            foreach ($matches[1] as $k => $imgInfo) {
                $base64_image_content = $imgInfo;
                //匹配出图片的格式
                if (preg_match('/^(data:\s*image\/(\w+);base64,)/', $base64_image_content, $result)){
                    $imgInfo = base64_decode(str_replace($result[1], '', $base64_image_content));

                    //储存路径
                    $imgName = $config['path'] . date($config['subName'], time()) . '/' .uniqid() . '_' . StringHelper::randomNum().'.'.$result[2];
                    $targetFileName = Yii::getAlias('@attachment') . '/' .$imgName;
                    FileHelper::mkdirs(dirname($targetFileName));
                    file_put_contents($targetFileName, $imgInfo);

                    $articleImg = Yii::$app->request->hostInfo.Yii::getAlias('@attachurl') . '/' .$imgName;
                    $contents = str_ireplace($matches[0][$k], '<img style="max-width:500px;" src="'.$articleImg.'">', $contents);
                }
            }
        }
        $contents = preg_replace('/<p.*?>/', '<p>', $contents);
        $contents = preg_replace('/<span.*?>/', '', $contents);
        $contents = preg_replace('/<font.*?>/', '', $contents);
        $contents = str_ireplace(array('</span>','</font>'), array('',''), $contents);
        $contents = str_ireplace(PHP_EOL, '', $contents);
        $contents = html_entity_decode($contents);

        return [
            'code' => 200,
            'msg' => '成功',
            'data' => [
                'content' => str_ireplace("\t",'',htmlspecialchars_decode($contents)),
            ],
        ];
    }
}