<?php

namespace app\models;

use yii\base\Model;
use Yii;
use yii\helpers\Html;
use app\components\HelperY;
use yii\helpers\Url;

class UserHelper {

    const ROLE_MANAGER = 10;
    const ROLE_GENDIR = 20;
    const ROLE_ADMIN = 30;
    const COUNT_ON_PAGE = 10;

    public static function getEmail($id) {
        $u = User::find()->where(['id' => $id])->limit(1)->one();
        return ($u) ? $u->email : false;
    }

    public static function findByEmail($email) {
        $email = \app\components\HelperY::purify($email, '/[^\w\d\.-_@]/Uui');
        return User::find()->where(['email' => $email])->limit(1)->one();
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

    public static function getUser($id) {
        //return User::findOne($id);
        return User::find()->where(['id' => $id])->limit(1)->one();
    }

    public static function getUserRoleName($roleId) {
        switch ($roleId) {
            case UserHelper::ROLE_ADMIN:
                return 'ADMIN';
            case UserHelper::ROLE_GENDIR:
                return 'gendir';
            case UserHelper::ROLE_MANAGER:
                return 'user';
        }
        return '??????';
    }

}
