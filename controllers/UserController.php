<?php


namespace app\controllers;


use app\models\user\Userprofile;

class UserController extends OnAuthController
{

    /**
     *
     *
     * @return array
     */
    public function actionGet_info()
    {
        return [
            'head_portrait' => \Yii::$app->user->identity->getHeadPortrait(),
            'id' => \Yii::$app->user->getId(),
            'username' => \Yii::$app->user->identity->getUsername()
        ];
    }
}