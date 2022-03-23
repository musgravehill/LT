<?php

namespace app\models;

use Yii;

/**
 * This is the model class for table "feedback".
 *
 * @property int $id
 * @property string $email
 * @property string $phone
 * @property string $username
 * @property string $dt_create
 */
class Feedback extends \yii\db\ActiveRecord
{
    /**
     * {@inheritdoc}
     */
    public static function tableName()
    {
        return 'feedback';
    }

    /**
     * {@inheritdoc}
     */
    public function rules()
    {
        return [
            [['email', 'phone', 'username', 'dt_create'], 'required'],
            [['dt_create'], 'safe'],
            [['email', 'username'], 'string', 'max' => 64],
            [['phone'], 'string', 'max' => 32],
        ];
    }

    /**
     * {@inheritdoc}
     */
    public function attributeLabels()
    {
        return [
            'id' => 'ID',
            'email' => 'Email',
            'phone' => 'Phone',
            'username' => 'Username',
            'dt_create' => 'Dt Create',
        ];
    }
}
