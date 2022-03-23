<?php

namespace app\components;

use Yii;
use app\models\UserHelper;
use app\components\UserEntity;

class UserIdentity implements \yii\web\IdentityInterface {

    public $id = 0;
    public $userEntity = null;

    public function __construct($id, UserEntity $userEntity) {
        $this->id = (int) $id;
        $this->userEntity = $userEntity;
    }

    public static function className() {
        return get_called_class();
    }

    public static function findIdentity($id) {
        $user = UserHelper::getUser($id);
        $UserEntity = new \app\components\UserEntity($user);
        return new UserIdentity($id, $UserEntity);
    }

    public static function findIdentityByAccessToken($token, $type = null) {
        throw new NotSupportedException();
    }

    public function getId() {
        return $this->id;
    }

    public function getAuthKey() {
        return (string) md5('zzz-G&%4' . $this->id . 'dfj*94(');
    }

    public function validateAuthKey($authKey) {
        return (string) md5('zzz-G&%4' . $this->id . 'dfj*94(') === (string) $authKey;
    }

    public function afterLoginCustom($event) {
        return true;
    }

}
