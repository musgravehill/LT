<?php

namespace app\components;

use Yii;
use app\models\User;

class UserEntity {

    public $id;
    public $email;
    public $role;

    public function __construct(User $user) {
        if (!$user) {
            return;
        }

        $class = new \ReflectionClass($this);
        $names = [];
        foreach ($class->getProperties(\ReflectionProperty::IS_PUBLIC) as $property) {
            if (!$property->isStatic()) {
                $names[] = $property->getName();
            }
        }
        foreach ($names as $n) {
            if (isset($user->$n)) {
                $this->$n = $user[$n];
            }
        }

        /* $vars = get_object_vars($this);
          foreach ($vars as $n => $v) {
          if (isset($user->$n)) {
          $this->$n = $user[$n];
          }
          } */
    }

}
