<?php

namespace common\models;

use Yii;

/**
 * This is the model class for table "{{%plate}}".
 *
 * @property integer $id
 * @property string $app_id
 * @property string $app_secret
 * @property string $plate_host
 * @property string $plate_name
 * @property string $plate_desc
 * @property integer $default_group
 * @property integer $created_at
 * @property integer $updated_at
 * @property string $creator
 * @property integer $status
 */
class Plate extends \yii\db\ActiveRecord
{

    const STATUS_ACTIVE = 1;
    const STATUS_INACTIVE = 0;
    /**
     * @inheritdoc
     */
    public static function tableName()
    {
        return '{{%plate}}';
    }

    /**
     * @inheritdoc
     */
    public function rules()
    {
        return [
            [['app_id', 'app_secret', 'plate_host', 'plate_name', 'default_group', 'created_at', 'updated_at'], 'required'],
            [['plate_desc'], 'string'],
            [['default_group', 'created_at', 'updated_at', 'status'], 'integer'],
            [['app_id'], 'string', 'max' => 30],
            [['app_secret'], 'string', 'max' => 60],
            [['plate_host'], 'string', 'max' => 200],
            [['plate_name'], 'string', 'max' => 50],
            [['creator'], 'string', 'max' => 20],
            [['app_id'], 'unique'],
        ];
    }

    public static function getArrayStatus($intStatus = null)
    {
        $array = [
            self::STATUS_ACTIVE => Yii::t('app', 'STATUS_ACTIVE'),
            self::STATUS_INACTIVE => Yii::t('app', 'STATUS_INACTIVE'),
        ];

        if ($intStatus !== null && isset($array[$intStatus])) {
            $array = $array[$intStatus];
        }

        return $array;
    }

    /**
     * 获取状态值对应的颜色信息
     * @param  int $intStatus 状态值
     * @return array|string
     */
    public static function getStatusColor($intStatus = null)
    {
        $array = [
            self::STATUS_ACTIVE => 'label-success',
            self::STATUS_INACTIVE => 'label-danger',
        ];

        if ($intStatus !== null && isset($array[$intStatus])) {
            $array = $array[$intStatus];
        }

        return $array;
    }

    /**
     * @inheritdoc
     */
    public function attributeLabels()
    {
        return [
            'id' => 'ID',
            'app_id' => '平台唯一标识',
            'app_secret' => '密钥',
            'plate_host' => '平台地址',
            'plate_name' => '平台名称',
            'plate_desc' => '平台描述',
            'default_group' => '平台用户默认用户组',
            'created_at' => '创建时间',
            'updated_at' => '更新时间',
            'creator' => '创建者',
            'status' => '状态： 0：停用 1：启用',
        ];
    }

}
