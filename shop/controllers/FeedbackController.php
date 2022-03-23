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
//
use JeroenDesloovere\VCard\VCard;

class FeedbackController extends Controller {

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
                        'actions' => ['cr'],
                        'allow' => true,
                        'roles' => ['?', '@'],
                    ],
                    [
                        'actions' => ['u', 'd', 'list'],
                        'allow' => true,
                        'roles' => [UserHelper::ROLE_ADMIN],
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

    public function actionCr() {
        $model = new \app\models\FormFeedbackCr();
        if ($model->load(Yii::$app->request->post()) && $model->validate() && $model->cr()) {
            Yii::$app->session->addFlash('success', 'Спасибо!');
            return $this->redirect(Url::to(['feedback/cr',]));
        }

        $items = \app\models\Feedback::find()->orderby(['id' => SORT_DESC])->limit(10)->all();

        return $this->render('cr', [
                    'model' => $model,
                    'items' => $items,
        ]);
    }

}
