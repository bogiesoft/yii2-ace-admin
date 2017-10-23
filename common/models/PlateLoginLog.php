<?php

namespace common\models;

use Yii;

/**
 * This is the model class for table "{{%plate_login_log}}".
 *
 * @property integer $id
 * @property integer $uid
 * @property string $login_ip
 * @property integer $created_at
 * @property integer $plate_id
 */
class PlateLoginLog extends \yii\db\ActiveRecord
{
    /**
     * @inheritdoc
     */
    public static function tableName()
    {
        return '{{%plate_login_log}}';
    }

    /**
     * @inheritdoc
     */
    public function rules()
    {
        return [
            [['uid', 'created_at', 'plate_id'], 'integer'],
            [['login_ip'], 'string', 'max' => 20],
        ];
    }

    /**
     * @inheritdoc
     */
    public function attributeLabels()
    {
        return [
            'id' => 'ID',
            'uid' => '登录用户ID',
            'login_ip' => '登录ip',
            'created_at' => '登录时间',
            'plate_id' => '来源平台',
        ];
    }

}
