<?php

namespace app\models;

use Yii;
use yii\base\Model;
use app\models\User;
use app\components\HelperY;
use yii\helpers\Url;

class FormFeedbackU extends Model {

    public $username;
    public $phone;
    public $email;

    public function u($f) {
        $f->email = HelperY::purify($this->email, '/[^\w\d@\-_\.]/Uui');
        $f->phone = HelperY::purify($this->phone, '/[^\w\d\(\)\-\+]/Uui');
        $f->username = HelperY::purify($this->username, '/[^\w\d\s]/Uui');

        if ($f->save()) {
            return true;
        }

        return false;
    }

    public function rules() {
        return [
            ['username', 'required'],
            ['username', 'string', 'min' => 3, 'max' => 64],
            ['username', 'trim'],
            //
            ['phone', 'required'],
            ['phone', 'isPhone'],
            ['phone', 'string', 'min' => 5, 'max' => 32],
            ['phone', 'trim'],
            //
            ['email', 'required'],
            ['email', 'email'],
            ['email', 'string', 'min' => 3, 'max' => 64],
            ['email', 'trim'],
        ];
    }

    public function attributeLabels() {
        return [
            'username' => 'Имя',
            'phone' => 'Телефон',
            'email' => 'Email',
        ];
    }

    public function isPhone($attribute) {
        if (!preg_match('/^[0-9\-\+\(\)\s]+$/', $this->$attribute)) {
            $this->addError($attribute, 'Введите номер телефона.');
            return false;
        }
        return true;
    }

    /* public function beforeValidate() {
      if (!empty($this->phone)) {
      $this->phone = HelperY::purify(str_replace('+7', '', $this->phone), '/[^\d]/Uui'); //10 digits without +7
      }
      return parent::beforeValidate();
      } */
}
