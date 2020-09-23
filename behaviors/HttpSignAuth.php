<?php

namespace app\behaviors;

use app\models\user\Userprofile;
use Yii;
use yii\base\Behavior;
use yii\db\Exception;
use yii\web\Controller;
use yii\web\UnprocessableEntityHttpException;

/**
 * http 签名验证
 *
 * Class HttpSignAuth
 * @package api\behaviors
 * @author jianyan74 <751393839@qq.com>
 */
class HttpSignAuth extends Behavior
{
    /**
     * @var bool
     */
    public $switch = false;

    /**
     * @return array
     */
    public function events()
    {
        return [Controller::EVENT_BEFORE_ACTION => 'beforeAction'];
    }

    /**
     * @param $event
     * @return bool
     * @throws UnprocessableEntityHttpException
     */
    public function beforeAction($event)
    {
        $data = Yii::$app->request->get();
        $user = Userprofile::find()->where([
            'token' => $data['token']
        ])->one();
        if (!$user) {
            throw new Exception('token异常');
        }
        return true;
    }
}