<?php

namespace app\components;

use app\components\HelperY;


trait ControllerTrait {

    /**
     * {@inheritdoc}
     */
    public function beforeAction($action) {
        if (parent::beforeAction($action)) {             

            $layoutId = (int) HelperY::getGet('layout', 0);
            switch ($layoutId) {
                case 0:
                    //default
                    break;
                case 1:
                    $this->layout = 'modal';
                    //$this->view->params['containerClass'] = 'container-fluid';
                    break;
            }

            return true;
        }

        return false;
    }

}
