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
                        'actions' => ['u', 'd', 'list_adm'],
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

        $items = \app\models\Feedback::find()->asArray()->orderby(['id' => SORT_DESC])->limit(10)->all();

        return $this->render('cr', [
                    'model' => $model,
                    'items' => $items,
        ]);
    }

    public function actionList_adm() {
        $filters = [
            'page' => (int) HelperY::getGet('page', 1),
        ];
        array_walk_recursive($filters, function (&$item) {
            $item = HelperY::purify($item, '/[^\w\d\s\-]/Uui');
        });
        $filters['page'] = ($filters['page'] > 0) ? $filters['page'] : 1;

        $urlParams = [];
        $urlParams[0] = 'feedback/list_adm'; //for URL create
        $urlParams['page'] = (int) $filters['page'];

        $items = \app\models\FeedbackHelper::get($filters);

        return $this->render('list_adm', [
                    'items' => $items,
                    'urlParams' => $urlParams,
        ]);
    }

    public function actionU() {
        $id = (int) HelperY::getPost('id', 0);

        $item = \app\models\Feedback::find()->where(['id' => $id])->limit(1)->one();
        if (!$item) {
            throw new HttpException(404, 'Not found');
        }

        $model = new \app\models\FormFeedbackU();
        $model->username = HelperY::getPost('username', 0);
        $model->phone = HelperY::getPost('phone', 0);
        $model->email = HelperY::getPost('email', 0);

        if ($model->load(Yii::$app->request->post()) && $model->validate() && $model->u($item)) {
            //Yii::$app->session->addFlash('success', 'Ok!');
            //return $this->redirect(Yii::$app->request->referrer ?: Yii::$app->homeUrl);
            return $this->asJson(1);
        }

        return $this->asJson(0);  //OR \Yii::$app->response->format = \yii\web\Response::FORMAT_JSON;

        /* return $this->render('u', [
          'model' => $model,
          ]); */
    }

}
