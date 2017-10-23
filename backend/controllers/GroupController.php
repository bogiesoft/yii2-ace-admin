<?php

namespace backend\controllers;

use common\models\Group;
/**
 * Class GroupController 子系统组别 执行操作控制器
 * @package backend\controllers
 */
class GroupController extends Controller
{
    /**
     * @var string 定义使用的model
     */
    public $modelClass = 'common\models\Group';
     
    /**
     * 查询处理
     * @param  array $params
     * @return array 返回数组
     */
    public function where($params)
    {
        return [
            'id' => '=',
			'group_name' => '=', 
			'created_at' => '=', 
			'updated_at' => '=', 
			'creator' => '=', 
			'status' => '=', 

        ];
    }

    /**
     * 首页显示
     * @return string
     */
    public function actionIndex()
    {
        return $this->render('index', [
            'status' => Group::getArrayStatus(),
            'statusColor' => Group::getStatusColor(),
        ]);
    }
}
