<?php

namespace app\models;

use Yii;

/**
 * This is the model class for table "{{%user}}".
 *
 * @property int $id
 * @property string $username
 * @property string $f
 * @property string $i
 * @property string $o
 * @property string $email
 * @property string $pass
 * @property int $role
 * @property string $dt_created
 * @property int $status_id
 * @property int $geo_region_id
 * @property string $phone
 * @property int $gendir_id
 * @property int $phone_is_confirm
 * @property int $email_is_confirm
 * @property double $geo_lat
 * @property double $geo_long
 * @property string $geo_region
 * @property string $geo_city
 * @property string $geo_addr
 * @property string $geo_region_md5
 * @property string $geo_city_md5
 * @property int $balance_kopek
 * @property string $vk_url
 * @property string $my_site_url
 * @property string $instagram_url
 * @property string $youtube_url
 * @property string $about_me
 * @property string $d_birthday
 */
class ZUser extends \yii\db\ActiveRecord implements \yii\web\IdentityInterface {

    const ROLE_MANAGER = 10; //ROLE_GENDIR create users with rhis role
    const ROLE_GENDIR = 20; //register with this role
    const ROLE_ADMIN = 30;
    //
    const STATUS_NOTACTIVE = 0; //
    const STATUS_ACTIVE = 10; //

    public function afterLoginCustom($event) {
        //$user_id = (int) $this->id;
        return true;
    }

    public static function getAdmins() {
        $us = self::find()->where(['role' => self::ROLE_ADMIN])->limit(30)->all();
        return $us;
    }

    /**
     * @inheritdoc
     */
    public static function findIdentity($id) {
        //return self::findOne($id);
        return self::find()->where(['id' => $id])->limit(1)->one();
    }

    /**
     * @inheritdoc
     */
    public static function findIdentityByAccessToken($token, $type = null) {
        throw new NotSupportedException();
    }

    public function getAuthKey() {
        return (string) md5('zzz-G&%4' . $this->id . 'dfj*94(');
    }

    public function validateAuthKey($authKey) {
        return (string) md5('zzz-G&%4' . $this->id . 'dfj*94(') === (string) $authKey;
    }

    public function getId() {
        return $this->id;
    }

    public function getRole() {
        return $this->role;
    }

    public static function getFIO($id, $isFull = true) {
        $u = self::find()->where(['id' => $id])->limit(1)->one();
        if (!$u) {
            return '.';
        }
        if ($isFull) {
            return $u->f . ' ' . $u->i . ' ' . $u->o;
        } else {
            return \app\components\HelperY::fioShort($u->f, $u->i, $u->o);
        }
        return '.';
    }

    public static function getPhone($id) {
        $u = self::find()->where(['id' => $id])->limit(1)->one();
        return ($u) ? $u->phone : false;
    }

    public static function findByPhone($phone) {
        $phone = \app\components\HelperY::purify($phone, '/[^\d]/Uui');
        return self::find()->where(['phone' => $phone])->limit(1)->one();
    }

    public static function getEmail($id) {
        $u = self::find()->where(['id' => $id])->limit(1)->one();
        return ($u) ? $u->email : false;
    }

    public static function findByEmail($email) {
        $email = \app\components\HelperY::purify($email, '/[^\w\d\.-_@]/Uui');
        return self::find()->where(['email' => $email])->limit(1)->one();
    }

    public function validatePassword($pass_input) {
        $password = $pass_input;
        $hash = $this->pass;

        if (!is_string($password) || $password === '') {
            return false;
        }

        if (!preg_match('/^\$2[axy]\$(\d\d)\$[\.\/0-9A-Za-z]{22}/', $hash, $matches) || $matches[1] < 4 || $matches[1] > 30) {
            return false;
        }

        return Yii::$app->getSecurity()->validatePassword($password, $hash);
    }

    public static function generatePasswordHash($password) {
        return Yii::$app->getSecurity()->generatePasswordHash($password);
    }

    ///////////////////////////////////////////////////////////////////////////////////////////////////////////////////////

    /**
     * {@inheritdoc}
     */
    public static function tableName() {
        return '{{%user}}';
    }

    /**
     * {@inheritdoc}
     */
    public function rules() {
        return [
            [['pass', 'dt_created', 'status_id', 'balance_kopek'], 'required'],
            [['role', 'status_id', 'geo_region_id', 'gendir_id', 'phone_is_confirm', 'email_is_confirm', 'balance_kopek'], 'integer'],
            [['dt_created', 'd_birthday'], 'safe'],
            [['geo_lat', 'geo_long'], 'number'],
            [['username', 'f', 'i', 'o', 'email', 'geo_region_md5', 'geo_city_md5'], 'string', 'max' => 32],
            [['pass'], 'string', 'max' => 64],
            [['phone'], 'string', 'max' => 10],
            [['geo_region', 'geo_city', 'geo_addr', 'vk_url', 'my_site_url', 'instagram_url', 'youtube_url'], 'string', 'max' => 128],
            [['about_me'], 'string', 'max' => 1024],
            [['phone'], 'unique'],
                /* [['phone'], 'string', 'max' => 10],
                  [['phone'], 'unique'],
                  [['pass', 'dt_created', 'status_id'], 'required'],
                  [['role', 'status_id', 'geo_region_id', 'gendir_id', 'phone_is_confirm', 'email_is_confirm', 'balance_kopek'], 'integer'],
                  [['dt_created'], 'safe'],
                  [['geo_lat', 'geo_long'], 'number'],
                  [['f', 'i', 'o', 'email', 'geo_region_md5', 'geo_city_md5'], 'string', 'max' => 32],
                  [['geo_region', 'geo_city', 'geo_addr'], 'string', 'max' => 128],
                  [['pass'], 'string', 'max' => 64],
                  [['username'], 'string', 'max' => 32],
                  [['vk_url', 'my_site_url', 'instagram_url', 'youtube_url', 'about_me',], 'string', 'max' => 128],
                  [['d_birthday'], 'safe'],
                  [['about_me',], 'string', 'max' => 1024], */
        ];
    }

    /**
     * {@inheritdoc}
     */
    public function attributeLabels() {
        return [
            'id' => 'ID',
        ];
    }

}
