<?php

namespace common\models;

use Yii;

/**
 * This is the model class for table "{{%plate_menu}}".
 *
 * @property integer $id
 * @property integer $pid
 * @property integer $plate_id
 * @property string $url
 * @property string $name
 * @property string $icon
 * @property integer $created_at
 * @property integer $updated_at
 * @property integer $order
 * @property string $creator
 * @property integer $status
 */
class PlateMenu extends \yii\db\ActiveRecord
{
    const STATUS_ACTIVE = 1;
    const STATUS_INACTIVE = 0;
    /**
     * @inheritdoc
     */
    public static function tableName()
    {
        return '{{%plate_menu}}';
    }

    /**
     * @inheritdoc
     */
    public function rules()
    {
        return [
            [['pid', 'plate_id', 'created_at', 'updated_at', 'order', 'status'], 'integer'],
            [['plate_id', 'url'], 'required'],
            [['url'], 'string', 'max' => 100],
            [['name'], 'string', 'max' => 60],
            [['icon'], 'string', 'max' => 25],
            [['creator'], 'string', 'max' => 20],
        ];
    }

    /**
     * @inheritdoc
     */
    public function attributeLabels()
    {
        return [
            'id' => 'ID',
            'pid' => '父级ID',
            'plate_id' => '平台ID',
            'url' => '路由地址',
            'name' => '菜单名称',
            'icon' => '图标',
            'created_at' => '创建时间',
            'updated_at' => '更新时间',
            'order' => '排序',
            'creator' => '创建者',
            'status' => '状态： 0：停用 1：启用',
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
