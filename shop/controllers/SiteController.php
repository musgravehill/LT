<?php

namespace app\controllers;

use Yii;
use yii\filters\AccessControl;
use app\components\AccessRule;
use yii\web\Controller;
use yii\web\Response;
use yii\filters\VerbFilter;
use yii\web\HttpException;
//
use app\components\HelperY;
use yii\helpers\Url;
use yii\helpers\Html;
//
use app\models\User;
use app\models\UserHelper; 
use yii\captcha\CaptchaAction;

class SiteController extends Controller {

    use \app\components\ControllerTrait;

    /**
     * @inheritdoc
     */
    public function behaviors() {
        //https://thecodeninja.net/2014/12/simpler-role-based-authorization-in-yii-2-0/ 
        return [
            'access' => [
                'class' => AccessControl::className(),
                // We will override the default rule config with the new AccessRule class
                'ruleConfig' => [
                    'class' => AccessRule::className(),
                ],
                'rules' => [
                    [
                        'actions' => ['index', 'error', 'captcha',],
                        'allow' => true,
                        'roles' => ['?', '@'],
                    ],
                    [
                        'actions' => ['register', 'login'],
                        'allow' => true,
                        'roles' => ['?'],
                    ],
                    [
                        'actions' => ['logout',],
                        'allow' => true,
                        'roles' => ['@'],
                    ],
                ],
            ],
                /* 'verbs' => [
                  'class' => VerbFilter::className(),
                  'actions' => [
                  'logout' => ['post'],
                  ],
                  ], */
        ];
    }

    /**
     * @inheritdoc
     */
    public function actions() {
        return [
            'error' => [//for config ErrorHandler 'site/error'
                'class' => 'yii\web\ErrorAction',
            ],
            'captcha' => [
                'class' => 'yii\captcha\CaptchaAction',
                'fixedVerifyCode' => YII_ENV_TEST ? 'testme' : null,
                'minLength' => 3,
                'maxLength' => 4,
                'offset' => 4,
            ],
        ];
    }

    public function actionError() {
        return $this->render('error');
    }

    public function actionIndex() {
        return $this->render('/site/index', [
        ]);
    }

    public function actionRegister() {
        if (!Yii::$app->user->isGuest) {
            return $this->goHome();
        }

        $model = new \app\models\FormRegister();
        if ($model->load(Yii::$app->request->post()) && $model->validate() && $model->register()) {
            return $this->goHome();
        }
        return $this->render('register', [
                    'model' => $model,
        ]);
    }

    public function actionLogout() {
        Yii::$app->user->logout();

        return $this->goHome();
    }

}
