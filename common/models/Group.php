<?php

namespace common\models;

use Yii;

/**
 * This is the model class for table "{{%group}}".
 *
 * @property integer $id
 * @property string $group_name
 * @property string $desc
 * @property integer $created_at
 * @property integer $updated_at
 * @property integer $order
 * @property string $creator
 * @property integer $status
 */
class Group extends \yii\db\ActiveRecord
{
    const STATUS_ACTIVE = 1;
    const STATUS_INACTIVE = 0;

    /**
     * @inheritdoc
     */
    public static function tableName()
    {
        return '{{%group}}';
    }

    /**
     * @inheritdoc
     */
    public function rules()
    {
        return [
            [['group_name'], 'required'],
            [['desc'], 'string'],
            [['created_at', 'updated_at', 'order', 'status'], 'integer'],
            [['group_name'], 'string', 'max' => 255],
            [['creator'], 'string', 'max' => 20],
            [['group_name'], 'unique'],
        ];
    }

    /**
     * @inheritdoc
     */
    public function attributeLabels()
    {
        return [
            'id' => 'ID',
            'group_name' => '用户组名',
            'desc' => '用户组描述',
            'created_at' => '创建时间',
            'updated_at' => '更新时间',
            'order' => '排序',
            'creator' => '创建者',
            'status' => '状态：0：停用 1：启用',
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
            self::STATUS_INACTIVE => 'label-warning',
        ];

        if ($intStatus !== null && isset($array[$intStatus])) {
            $array = $array[$intStatus];
        }

        return $array;
    }
}
