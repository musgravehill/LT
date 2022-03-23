<?php

namespace app\models;

use yii\base\Model;
use Yii;
use yii\helpers\Html;
use app\components\HelperY;
use yii\helpers\Url;

class FeedbackHelper {

    const COUNT_ON_PAGE = 2;

    public static function get($filters) {
        $offset = (int) ($filters['page'] - 1) * self::COUNT_ON_PAGE;
        $limit = " LIMIT $offset, " . self::COUNT_ON_PAGE . ' ';

        $res = Yii::$app->db->createCommand("
                SELECT
                    fb.*
                FROM  {{feedback}} fb   
                ORDER BY fb.id DESC 
                $limit
               ")
                ->queryAll(); //queryOne
        return $res;
    }

}
