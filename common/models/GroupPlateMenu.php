<?php

namespace common\models;

use Yii;

/**
 * This is the model class for table "{{%group_plate_menu}}".
 *
 * @property integer $group_id
 * @property integer $plate_menu_id
 */
class GroupPlateMenu extends \yii\db\ActiveRecord
{
    /**
     * @inheritdoc
     */
    public static function tableName()
    {
        return '{{%group_plate_menu}}';
    }

    /**
     * @inheritdoc
     */
    public function rules()
    {
        return [
            [['group_id', 'plate_menu_id'], 'integer'],
            [['group_id', 'plate_menu_id'], 'unique', 'targetAttribute' => ['group_id', 'plate_menu_id'], 'message' => 'The combination of 用户组ID and 平台菜单ID has already been taken.'],
        ];
    }

    /**
     * @inheritdoc
     */
    public function attributeLabels()
    {
        return [
            'group_id' => '用户组ID',
            'plate_menu_id' => '平台菜单ID',
        ];
    }
}
