<?php

namespace app\controllers;

use Yii;
use yii\filters\AccessControl;
use app\components\AccessRule;
use yii\web\Controller;
use yii\web\Response;
use yii\filters\VerbFilter;
use \yii\web\HttpException;
//
use app\components\HelperY;
use yii\helpers\Url;
use yii\helpers\Html;
//
use app\models\User;
use app\models\UserHelper;
use app\models\Category;
use app\models\LogSysHelper;
//
use yii\imagine\Image;
use yii\web\UploadedFile;

class AdmController extends \yii\web\Controller {

    public function behaviors() {
        //https://thecodeninja.net/2014/12/simpler-role-based-authorization-in-yii-2-0/
        return [
            'access' => [
                'class' => AccessControl::className(),
                // We will override the default rule config with the new AccessRule class
                'ruleConfig' => [
                    'class' => AccessRule::className(),
                ],
                //'only' => ['my', 'create', 'edit'],
                'rules' => [
                    [
                        'actions' => ['list', 'sms', 'conf', 'company_owner', 'user_list', 'filltables', 'purchase_adm', 'dssl', 'diskont'],
                        'allow' => true,
                        'roles' => [UserHelper::ROLE_ADMIN],
                    ],
                    [
                        'actions' => ['purchase'],
                        'allow' => true,
                        'roles' => ['?', '@'],
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

    public static function diskontProductsImport() {
        $zipUrl = 'http://dis-kont.ru/stock/stock.zip';
        $zipPath = \Yii::getAlias('@webroot/log/diskont.zip');
        $xlsxPath = \Yii::getAlias('@webroot/log/diskont.xlsx');

        $arrContextOptions = array(
            "ssl" => array(
                "verify_peer" => false,
                "verify_peer_name" => false,
            ),
        );

        file_put_contents($zipPath, fopen($zipUrl, 'r'), 0, stream_context_create($arrContextOptions));
        $zip = new \ZipArchive();
        $res = $zip->open($zipPath);
        if ($res === TRUE) {
            //$zip->extractTo($xlsxPath);
            $filename = $zip->getNameIndex(0);
            //$fileinfo = pathinfo($filename);
            copy("zip://" . $zipPath . "#" . $filename, $xlsxPath);
            $zip->close();
        } else {
            echo "ERR unzip";
        }

        $reader = \PhpOffice\PhpSpreadsheet\IOFactory::createReader('Xlsx');
        $reader->setReadDataOnly(TRUE);
        $reader->setLoadSheetsOnly(['Основной склад']);
        $spreadsheet = $reader->load($xlsxPath);
        //$spreadsheet->getSheetNames(); //Array ( [0] => Как работать с файлом [1] => Основной склад [2] => HiWatch [3] => Dahua [4] => Hikvision [5] => Бастион [6] => Ezviz [7] => EZ-IP [8] => Imou )

        $worksheet = $spreadsheet->getSheet(0); //setLoadSheetsOnly(['Основной склад']);
        $data = $worksheet->toArray();
        foreach ($data as $item) {
            $name = HelperY::purify($item[3] . ' ' . $item[0], '/[^\w\d\-_\.\s;]/Uui');
            $sku = mb_substr(HelperY::purify($item[1] . ';' . $item[2], '/[^\w\d\-_\.\s;]/Uui'), 0, 16, "utf-8");
            self::diskontProductImport($sku, $name, (int) $item[4], (int) $item[5]);
        }

        /*
          [0] => Номенклатура
          [1] => Артикул
          [2] => Код
          [3] => Производитель
          [4] => Розница
          [5] => Остаток */
    }

    public static function diskontProductImport($sku, $name, $price, $count) {
        $company_id = 1020;
        if ($price <= 0) {
            return false;
        }

        $type_id = \app\models\ProductHelper::TYPE_BZN;

        $p = \app\models\Product::find()->where(['company_id' => $company_id, 'type_id' => $type_id, 'sku' => $sku,])->limit(1)->one();
        if (!$p) {
            $p = new \app\models\Product();
        }

        $p->type_id = $type_id;
        $p->sku = $sku;
        $p->name = HelperY::sanitizeText($name);
        $p->dsc = HelperY::sanitizeText($name);
        $p->price = (float) $price;
        $p->user_id = 0;
        $p->company_id = $company_id;
        $p->dt_upd = HelperY::dt();
        $p->count_available = (int) $count;
        $p->is_deleted = 0;

        if (!$p->dsc) {
            $p->dsc = '.';
        }

        if ($p->save()) {
            $EventObject = new \app\components\Event\EventObject_productCrud(0, $p);
            Yii::$app->eventManager->trigger(\app\components\Event\EventComponent::EVENT_PRODUCT_CRUD, $EventObject);
            unset($EventObject);
        } else {
            //print_r($p->errors);
            return false;
        }

        unset($p);
        unset($company_id);
        unset($type_id);
        unset($sku);

        return true;
        //$data->attributes()->available;
    }

    public static function dsslProductsImport() {
        /*
          [0] =>
          [1] => Бренд
          [2] => Код
          [3] => Номенклатура
          [4] => Валюта
          [5] => ЦЕНА
          [6] => СТОП-цена
          [7] => Описание
          [8] => МСК 1024
          [9] => СПБ 1025
          [10] => КРД 1026
          [11] => НН 1027
          [12] => НСК 1028
          [13] => ВЛК 1029
          [14] => ВЛГ 1030
          [15] => ВРЖ 1031
          [16] => ЕКБ 1032
          [17] => КЗН 1033
          [18] => Пермь 1034
          [19] => РНД 1035
          [20] => САМ 1036
          [21] => УФА 1037
          [22] => ЯР 1038
          [23] => Пятигорск 1039
          [24] => Сургут 1040
          [25] => Красноярск 1041
         */

        $cities = [
            '8' => 1024,
            '9' => 1025,
            '10' => 1026,
            '11' => 1027,
            '12' => 1028,
            '13' => 1029,
            '14' => 1030,
            '15' => 1031,
            '16' => 1032,
            '17' => 1033,
            '18' => 1034,
            '19' => 1035,
            '20' => 1036,
            '21' => 1037,
            '22' => 1038,
            '23' => 1039,
            '24' => 1040,
            '25' => 1041
        ];

        $i = 0;
        $filePath = \Yii::getAlias('@webroot/log/dssl.csv');
        $handle = @fopen($filePath, "r");
        if ($handle) {
            while (($buffer = fgets($handle, 4096)) !== false) {
                $i++;
                $item = explode(';', $buffer);
                $data = [
                    'id' => $item[2],
                    'name' => trim($item[3], ' \n\r\t\v\0' . "'" . '"'),
                    'description' => trim($item[7], ' \n\r\t\v\0' . "'" . '"'),
                    'price' => (float) $item[5],
                    'picture' => '',
                ];

                foreach ($cities as $countId => $companyId) {
                    $data['count'] = (int) $item[$countId];
                    if ($data['count'] > 0) {
                        self::dsslProductImport($companyId, $data);
                    }
                }

                //if ($i > 100) {
                //  break;
                //}
            }
            fclose($handle);
        }
        echo 'Строк в файле: ' . $i;
    }

    public static function dsslProductImport($company_id, $data) {
        if ($data['price'] <= 0) {
            return false;
        }
        $company_id = (int) $company_id;
        $type_id = \app\models\ProductHelper::TYPE_BZN;
        $sku = HelperY::sanitizeWDS($data['id']);

        $p = \app\models\Product::find()->where(['company_id' => $company_id, 'type_id' => $type_id, 'sku' => $sku,])->limit(1)->one();
        if (!$p) {
            $p = new \app\models\Product();
        }

        $p->type_id = $type_id;
        $p->sku = $sku;
        $p->name = HelperY::sanitizeText($data['name']);
        $p->dsc = HelperY::sanitizeText($data['description']);
        $p->price = $data['price'];
        $p->user_id = 0;
        $p->company_id = $company_id;
        $p->dt_upd = HelperY::dt();
        $p->count_available = (int) $data['count'];
        $p->photo_url_1 = HelperY::sanitizeUrl($data['picture']);
        $p->is_deleted = 0;

        if (!$p->dsc) {
            $p->dsc = '.';
        }

        if ($p->save()) {
            $EventObject = new \app\components\Event\EventObject_productCrud(0, $p);
            Yii::$app->eventManager->trigger(\app\components\Event\EventComponent::EVENT_PRODUCT_CRUD, $EventObject);
            unset($EventObject);
        } else {
            return false;
        }

        unset($p);
        unset($data);
        unset($company_id);
        unset($type_id);
        unset($sku);

        return true;
        //$data->attributes()->available;
    }

    public function actionDssl() {
        self::dsslProductsImport();
    }

    public function actionDiskont() {
        self::diskontProductsImport();
    }

    public function actionPurchase_adm() {
        $filters = [
            'page' => (int) HelperY::getGet('page', 1),
        ];
        array_walk_recursive($filters, function (&$item) {
            $item = HelperY::purify($item, '/[^\w\d\s\-]/Uui');
        });
        $filters['page'] = ($filters['page'] > 0) ? $filters['page'] : 1;

        $urlParams = [];
        $urlParams[0] = 'adm/Purchase_adm'; //for URL create
        $urlParams['page'] = (int) $filters['page'];

        $items = \app\models\AdmLogPurchaseRequestHelper::getLog($filters);
        return $this->render('purchase_adm', [
                    'items' => $items,
                    'urlParams' => $urlParams,
        ]);
    }

    public function actionPurchase() {
        $txt = HelperY::getPost('txt', false);
        if ($txt) {
            $log = new \app\models\AdmLogPurchaseRequest();
            $log->txt = HelperY::sanitizeText($txt, 1023);
            $log->dt_created = HelperY::dt();
            $log->user_id = (int) HelperY::userId();
            if ($log->save()) {
                Yii::$app->session->addFlash('success', 'Отправлено!');
            } else {
                Yii::$app->session->addFlash('danger', 'Не удалось отправить. Попробуйте еще раз.');
            }
        }
        return $this->render('purchase', array());
    }

    //   /adm/filltables/0
    public function actionFilltables($id) {
        $page = abs($id);
        \app\components\Sphinx\SphinxCron::fillTables($page);
        return $this->render('filltables', array(
                    'page' => $page,
        ));
    }

    public function actionList() {

        return $this->render('list', array(
        ));
    }

    public function actionSms() {
        $phone = \app\models\NotyChannel::preparePhone(HelperY::getPost('phone', 0));
        $message = HelperY::sanitizeText(HelperY::getPost('message', '+'), 64);
        if ($phone && $message) {
            $res = \app\models\NotyChannel::sms($phone, $message);
            Yii::$app->session->addFlash('warning', print_r($res, true));
        }
        return $this->render('sms', [
        ]);
    }

    public function actionConf() {
        $k = (int) HelperY::getPost('k', 0);
        $v = HelperY::purify(HelperY::getPost('v', ''), '/[^\w\d,\.\[\]\(\)]/Uui');
        if ($k) {
            \app\models\AdmConfHelper::setV($k, $v);
        }
        return $this->render('conf', [
        ]);
    }

}
