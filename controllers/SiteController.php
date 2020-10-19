<?php

namespace app\controllers;

use common\helpers\ResultDataHelper;
use Yii;
use yii\filters\AccessControl;
use yii\web\Controller;
use yii\web\Response;
use yii\filters\VerbFilter;
use app\models\LoginForm;
use app\models\ContactForm;

class SiteController extends OnAuthController
{

    public $modelClass = '';
    /**
     * {@inheritdoc}
     */
//    public function behaviors()
//    {
//        return [
//            'access' => [
//                'class' => AccessControl::className(),
//                'only' => ['logout'],
//                'rules' => [
//                    [
//                        'actions' => ['logout'],
//                        'allow' => true,
//                        'roles' => ['@'],
//                    ],
//                ],
//            ],
//            'verbs' => [
//                'class' => VerbFilter::className(),
//                'actions' => [
//                    'logout' => ['post'],
//                ],
//            ],
//        ];
//    }

    /**
     * {@inheritdoc}
     */
    public function actions()
    {
        return [
            'error' => [
                'class' => 'yii\web\ErrorAction',
            ],
            'captcha' => [
                'class' => 'yii\captcha\CaptchaAction',
                'fixedVerifyCode' => YII_ENV_TEST ? 'testme' : null,
            ],
        ];
    }


    /**
     * 不用进行登录验证的方法
     * 例如： ['index', 'update', 'create', 'view', 'delete']
     * 默认全部需要验证
     *
     * @var array
     */
    protected $optional = ['wechat-login', 'login-qrcode'];

    /**
     * 获取二维码
     */
    public function actionLoginQrcode()
    {
        $ticket_data = Yii::$app->wechat->app->qrcode->temporary('foo', 30);
        $data['expire_date'] = $date = date('Y-m-d',time() + $ticket_data['expire_seconds']);
//        if ($error = Yii::$app->debris->getWechatError($ticket_data, false)) {
//            return ResultDataHelper::api(422, '系统繁忙，请刷新重试');
//        }
        $data['qc_code_url'] = Yii::$app->wechat->app->qrcode->url($ticket_data['ticket']);
        $date['timestamp'] = time(); //不知道是咋
        return $data;
    }

    /**
     * 微信扫码登录
     * 一直检测当前是否关注，关注了跳到后台，否则需要关注才
     * @return array|bool
     * @throws \Exception
     */
    public function actionWechatLogin()
    {

    }



    /**
     * Displays homepage.
     *
     * @return string
     */
    public function actionIndex()
    {
        return '日';
    }

    /**
     * Login action.
     *
     * @return Response|string
     */
//    public function actionLogin()
//    {
//        if (!Yii::$app->user->isGuest) {
//            return $this->goHome();
//        }
//
//        $model = new LoginForm();
//        if ($model->load(Yii::$app->request->post()) && $model->login()) {
//            return $this->goBack();
//        }
//
//        $model->password = '';
//        return $this->render('login', [
//            'model' => $model,
//        ]);
//    }

    /**
     * Logout action.
     *
     * @return Response
     */
//    public function actionLogout()
//    {
//        Yii::$app->user->logout();
//
//        return $this->goHome();
//    }

    /**
     * Displays contact page.
     *
     * @return Response|string
     */
//    public function actionContact()
//    {
//        $model = new ContactForm();
//        if ($model->load(Yii::$app->request->post()) && $model->contact(Yii::$app->params['adminEmail'])) {
//            Yii::$app->session->setFlash('contactFormSubmitted');
//
//            return $this->refresh();
//        }
//        return $this->render('contact', [
//            'model' => $model,
//        ]);
//    }

    /**
     * Displays about page.
     *
     * @return string
     */
//    public function actionAbout()
//    {
//        return $this->render('about');
//    }
}
