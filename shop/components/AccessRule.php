<?php

namespace app\components;

class AccessRule extends \yii\filters\AccessRule {

    /**
     * @inheritdoc
     */
    protected function matchRole($user) {
        if (empty($this->roles)) {
            return true; //allow to all, cause no roles_based
        }

        foreach ($this->roles as $role) {
            if ($role === '?') {
                if ($user->getIsGuest()) {
                    return true;
                }
            } elseif ($role === '@') {
                if (!$user->getIsGuest()) {
                    return true;
                }
                // Check if the user is logged in, and the roles match
            } elseif (!$user->getIsGuest() && $role === \Yii::$app->user->identity->userEntity->role) {
                return true;
            }
        }

        return false;
    }

}
