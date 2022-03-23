<?php

namespace app\components;

use Yii;
use app\models\UserHelper;

class HelperY {

    public static function dt() {
        return date('Y-m-d H:i:s');
    }

    public static function userId() {
        if (!isset(Yii::$app->user)) {
            return (int) 0;
        }
        if (Yii::$app->user->isGuest) {
            return (int) 0;
        }
        return (int) Yii::$app->user->identity->userEntity->id;
    }

    public static function getPost($name, $defaultValue) {
        return \Yii::$app->request->post($name, $defaultValue);
    }

    public static function getGet($name, $defaultValue) {
        return \Yii::$app->request->get($name, $defaultValue);
    }

    public static function params($name) {
        return \Yii::$app->params[$name];
    }

    /**
     *
     * @param string $res
     * @param string $regexp '/[^\w\d]/Uui'     purify($res, '/[^\w\d]/Uui') 
     * @return string                     
     */
    public static function purify($res, $regexp) {
        $res = str_replace(array("\r", "\n", "\t"), ' ', $res);
        $res = preg_replace($regexp, '', $res);
        $res = preg_replace('/ {2,}/Uui', ' ', $res);
        return trim($res);
    }

}
