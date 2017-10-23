<?php

namespace common\models;

use Yii;

/**
 * This is the model class for table "{{%group_permission}}".
 *
 * @property integer $group_id
 * @property integer $permission_id
 */
class GroupPermission extends \yii\db\ActiveRecord
{
    /**
     * @inheritdoc
     */
    public static function tableName()
    {
        return '{{%group_permission}}';
    }

    /**
     * @inheritdoc
     */
    public function rules()
    {
        return [
            [['group_id', 'permission_id'], 'integer'],
            [['group_id', 'permission_id'], 'unique', 'targetAttribute' => ['group_id', 'permission_id'], 'message' => 'The combination of 用户组ID and 权限ID has already been taken.'],
        ];
    }

    /**
     * @inheritdoc
     */
    public function attributeLabels()
    {
        return [
            'group_id' => '用户组ID',
            'permission_id' => '权限ID',
        ];
    }

}
