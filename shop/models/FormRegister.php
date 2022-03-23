<?php

namespace app\models;

use Yii;
use yii\base\Model;
use app\models\User;
use app\components\HelperY;
use yii\helpers\Url;

class FormRegister extends Model {

    public $pass;
    public $email;

    public function register() {
        $user = new User;
        $user->email = $this->email;
        $user->pass = UserHelper::generatePasswordHash($this->pass);
        $user->role = UserHelper::ROLE_ADMIN;
        if ($user->save()) {
            $UserEntity = new \app\components\UserEntity($user);
            $UserIdentity = new \app\components\UserIdentity($user['id'], $UserEntity);
            Yii::$app->user->login($UserIdentity, HelperY::params('lifetimeLogin'));
            //Yii::$app->user->login($user, HelperY::params('lifetimeLogin'));
            return true;
        }

        return false;
    }

    public function rules() {
        return [
            ['email', 'required'],
            ['email', 'email'],
            //
            ['pass', 'required'],
            ['pass', 'string', 'min' => 6, 'max' => 12],
        ];
    }

    public function attributeLabels() {
        return [
            'pass' => 'Пароль',
            'email' => 'Email',
        ];
    }

    public function isPhone($attribute) {
        if (!preg_match('/^[0-9]{10}$/', $this->$attribute)) {
            $this->addError($attribute, 'Нужен мобильный телефон.');
            return false;
        }
        /* if (!preg_match('/^9[0-9]{9}$/', $this->$attribute)) {
          $this->addError($attribute, 'Первая цифра должна быть "9".');
          return false;
          } */
        return true;
    }

    public function beforeValidate() {
        if (!empty($this->phone)) {
            $this->phone = HelperY::purify(str_replace('+7', '', $this->phone), '/[^\d]/Uui'); //10 digits without +7
        }


        return parent::beforeValidate();
    }

}
