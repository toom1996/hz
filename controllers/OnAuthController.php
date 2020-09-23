<?php

namespace app\controllers;

use Yii;
use yii\web\NotFoundHttpException;

/**
 * 需要授权登录访问基类
 *
 * Class OnAuthController
 * @package api\controllers
 * @property yii\db\ActiveRecord|yii\base\Model $modelClass
 * @author jianyan74 <751393839@qq.com>
 */
class OnAuthController extends ActiveController
{

    public $modelClass = '';

    /**
     * @return array
     */
    public function actions()
    {
        $actions = parent::actions();
//        Yii::warning($actions);
        // 注销系统自带的实现方法
        unset($actions['index'], $actions['update'], $actions['create'], $actions['view'], $actions['delete']);
        // 自定义数据indexDataProvider覆盖IndexAction中的prepareDataProvider()方法
        // $actions['index']['prepareDataProvider'] = [$this, 'indexDataProvider'];
        return $actions;
    }

    /**
     * 验证更新是否本人
     *
     * @param $action
     * @return bool
     * @throws \Exception
     */
//    public function beforeAction($action)
//    {
//        var_dump(Yii::$app->user->identity->member_id);die;
//        if ($action == 'update' && Yii::$app->user->identity->member_id != Yii::$app->request->get('id', null)) {
//            throw new NotFoundHttpException('权限不足.');
//        }
//
//        return parent::beforeAction($action);
//    }

    /**
     * @param $id
     * @return \yii\db\ActiveRecord
     * @throws NotFoundHttpException
     */
//    protected function findModel($id)
//    {
//        /* @var $model \yii\db\ActiveRecord */
//        if (empty($id) || !($model = $this->modelClass::find()->where([
//                'id' => $id,
//                'status' => StatusEnum::ENABLED
//            ])->andFilterWhere(['merchant_id' => $this->getMerchantId()])->one())) {
//            throw new NotFoundHttpException('请求的数据不存在');
//        }
//
//        return $model;
//    }
}
