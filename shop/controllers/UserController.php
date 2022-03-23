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
use app\models\LogSysHelper;
use app\models\UserTokenHelper;
use yii\captcha\CaptchaAction;
use app\components\BizRule;
use app\models\FormUserEdit;
//
use JeroenDesloovere\VCard\VCard;

class UserController extends Controller {

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
                //'only' => ['profile', 'usergeo', 'edit'],
                'rules' => [
                    [
                        'actions' => ['profile', 'edit', 'get_noty'],
                        'allow' => true,
                        'roles' => ['@'],
                    ],
                    [
                        'actions' => ['geo_save', 'map', 'view', 'vcard', 'businesscard_qrcode',],
                        'allow' => true,
                        'roles' => ['?', '@'],
                    ],
                    [
                        'actions' => ['delete'],
                        'allow' => true,
                        'roles' => [UserHelper::ROLE_GENDIR, UserHelper::ROLE_ADMIN,],
                    ],
                    [
                        'actions' => ['adm_list'],
                        'allow' => true,
                        'roles' => [UserHelper::ROLE_ADMIN,],
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

    public function actionAdm_list() {
        $filters = [
            'page' => (int) HelperY::getGet('page', 1),
        ];
        array_walk_recursive($filters, function (&$item) {
            $item = HelperY::purify($item, '/[^\w\d\s\-]/Uui');
        });
        $filters['page'] = ($filters['page'] > 0) ? $filters['page'] : 1;

        $urlParams = [];
        $urlParams[0] = 'user/adm_list'; //for URL create
        $urlParams['page'] = (int) $filters['page'];

        $users = UserHelper::getUsers($filters);
        return $this->render('adm_list', [
                    'users' => $users,
                    'urlParams' => $urlParams,
        ]);
    }

    public function actionView($id) {
        $checksum = HelperY::getGet('checksum', false);
        $hash = UserHelper::hashId($id);

        if (!$checksum) {
            throw new HttpException(404, 'Not found');
        }

        if ((string) $hash === (string) $checksum) {
            
        } else {
            throw new HttpException(404, 'Not found');
        }

        $user = UserHelper::getUser($id);
        if (!$user) {
            throw new HttpException(404, 'Not found');
        }

        $utm_source = HelperY::getGet('utm_source', false);
        if ($utm_source && $utm_source == 'businesscard') {
            $EventObject = new \app\components\Event\EventObject_userBusinesscardView($id, HelperY::userId());
            Yii::$app->eventManager->trigger(\app\components\Event\EventComponent::EVENT_USER_BUSINESSCARD_VIEW, $EventObject);
        }

        $cs = \app\models\CompanyHelper::getGendirCompanies((int) $user['id']);

        return $this->render('view', [
                    'user' => $user,
                    'cs' => $cs,
        ]);
    }

    public function actionDelete($id) {
        if (!BizRule::isAllow_user_delete(HelperY::userId(), $id)) {
            throw new HttpException(404, 'Not found');
        }

        $user = UserHelper::getUser($id);
        $fud = new \app\models\FormUserDelete();

        if ($fud->load(Yii::$app->request->post()) && $fud->validate() && $fud->delete($user)) {
            Yii::$app->session->addFlash('success', 'Пользователь удален.');
            return $this->redirect(Url::to(['employee/list']));
        }

        return $this->render('delete', [
                    'user' => $user,
                    'model' => $fud,
        ]);
    }

    public function actionEdit($id) {
        if (!BizRule::isAllow_user_edit(HelperY::userId(), $id)) {
            throw new HttpException(404, 'Not found');
        }

        $user = UserHelper::getUser($id);
        $FormUserEdit = new FormUserEdit();
        $FormUserEdit->username = $user->username;
        $FormUserEdit->d_birthday = (int) date('d', strtotime($user->d_birthday));
        $FormUserEdit->m_birthday = (int) date('m', strtotime($user->d_birthday));
        $FormUserEdit->y_birthday = (int) date('Y', strtotime($user->d_birthday));
        $FormUserEdit->y_birthday = ($FormUserEdit->y_birthday == 1900) ? 0 : $FormUserEdit->y_birthday;
        $FormUserEdit->email = $user->email;
        $FormUserEdit->my_site_url = $user->my_site_url;
        $FormUserEdit->vk_url = $user->vk_url;
        $FormUserEdit->instagram_url = $user->instagram_url;
        $FormUserEdit->youtube_url = $user->youtube_url;
        $FormUserEdit->about_me = $user->about_me;

        if ($FormUserEdit->load(Yii::$app->request->post())) {
            $FormUserEdit->photo = \yii\web\UploadedFile::getInstance($FormUserEdit, 'photo');
            if ($FormUserEdit->validate() && $FormUserEdit->edit($user)) {
                Yii::$app->session->addFlash('success', 'Сохранено!');
                return $this->redirect(Url::to(['user/edit', 'id' => $id,]));
            }
        }

        return $this->render('edit', [
                    'user' => $user,
                    'model' => $FormUserEdit,
        ]);
    }

    public function actionProfile() {
        $user = UserHelper::getUser(HelperY::userId());
        if (!$user) {
            throw new HttpException(404, 'Not found');
        }

        $FormSysSupport = new \app\models\FormSysSupport();
        if ($FormSysSupport->load(Yii::$app->request->post()) && $FormSysSupport->validate() && $FormSysSupport->save()) {
            Yii::$app->session->addFlash('success', 'Ваше сообщение отправлено админу!');
            return $this->redirect(Url::to(['user/profile']));
        }

        return $this->render('profile', [
                    'user' => $user,
                    'companies' => \app\models\CompanyHelper::getGendirCompanies(HelperY::userId()),
                    'FormSysSupport' => $FormSysSupport,
        ]);
    }

    public function actionMap() {
        $this->layout = 'modal';
        return $this->render('map', [
        ]);
    }

    public function actionGeo_save() {
        $geo_lat = (float) HelperY::getPost('geo_lat', 0);
        $geo_long = (float) HelperY::getPost('geo_long', 0);
        $geo_addr = HelperY::sanitizeText(HelperY::getPost('geo_addr', ''));

        if (!Yii::$app->user->isGuest) {
            $u = UserHelper::getUser(HelperY::userId());
            $u->geo_lat = $geo_lat;
            $u->geo_long = $geo_long;
            $u->geo_region = HelperY::sanitizeText(HelperY::getPost('geo_region', ''));
            $u->geo_city = HelperY::sanitizeText(HelperY::getPost('geo_city', ''));
            $u->geo_addr = $geo_addr;
            $u->geo_region_md5 = md5($u->geo_region);
            $u->geo_city_md5 = md5($u->geo_city);
            $u->save();
        }

        //for user and guest
        $GeoClient = new \app\components\GeoClient(HelperY::userId());
        $GeoClient->setGeo($geo_lat, $geo_long, $geo_addr);

        return $this->asJson(1);  //OR \Yii::$app->response->format = \yii\web\Response::FORMAT_JSON;
    }

    public function actionGet_noty() {
        $countNews = [
            'companies' => [
                'booking' => (int) 0,
                'order' => (int) 0,
            ],
            'client' => [
                'booking' => (int) 0,
                'order' => (int) 0,
            ],
        ];

        $companies = \app\models\CompanyHelper::getGendirCompanies(HelperY::userId());
        if ($companies) {
            foreach ($companies as $company) {
                $tmp = (int) \app\models\BookingHelper::getNewsCountForCompany($company['id']);
                $countNews['companies']['booking'] += (int) $tmp;
                $countNews['company'][$company['id']]['booking'] = (int) $tmp;

                $tmp = (int) \app\models\OrderHelper::getNewsCountForCompany($company['id']);
                $countNews['companies']['order'] += (int) $tmp;
                $countNews['company'][$company['id']]['order'] = (int) $tmp;
            }
        }

        $countNews['client']['booking'] = (int) \app\models\BookingHelper::getNewsCountForClient(HelperY::userId());
        $countNews['client']['order'] = (int) \app\models\OrderHelper::getNewsCountForClient(HelperY::userId());

        return $this->asJson($countNews);
    }

    public function actionBusinesscard_qrcode($id) {
        $checksum = HelperY::getGet('checksum', false);
        $hash = UserHelper::hashId($id);

        if (!$checksum) {
            throw new HttpException(404, 'Not found');
        }

        if ((string) $hash === (string) $checksum) {
            
        } else {
            throw new HttpException(404, 'Not found');
        }

        $user = UserHelper::getUser($id);
        if (!$user) {
            throw new HttpException(404, 'Not found');
        }

        return $this->render('businesscard_qrcode', [
                    'user' => $user,
        ]);
    }

    public function actionVcard($id) {
        $checksum = HelperY::getGet('checksum', false);
        $hash = UserHelper::hashId($id);

        if (!$checksum) {
            throw new HttpException(404, 'Not found');
        }

        if ((string) $hash === (string) $checksum) {
            
        } else {
            throw new HttpException(404, 'Not found');
        }

        $user = UserHelper::getUser($id);
        if (!$user) {
            throw new HttpException(404, 'Not found');
        }


        // define vcard
        $vcard = new VCard();

        // add personal data
        $vcard->addName('', Html::encode($user['username']), '', '', '');
        $vcard->addEmail(Html::encode($user['email']));
        $vcard->addPhoneNumber('+7' . Html::encode($user['phone']), 'PREF;WORK;HOME;VOICE;MSG;CELL');
        $vcard->addNote(Html::encode($user['about_me']));
        $vcard->addURL(Html::encode($user['my_site_url']));
        $vcard->addPhoto(UserHelper::getPhotoUrl($user['id']));

        // return vcard as a string
        //return $vcard->getOutput();
        // return vcard as a download
        return $vcard->download();
    }

}
