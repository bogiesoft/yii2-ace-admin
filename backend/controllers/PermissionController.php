<?php

namespace backend\controllers;

use common\models\Permission;
/**
 * Class PermissionController 子系统权限 执行操作控制器
 * @package backend\controllers
 */
class PermissionController extends Controller
{
    /**
     * @var string 定义使用的model
     */
    public $modelClass = 'common\models\Permission';
     
    /**
     * 查询处理
     * @param  array $params
     * @return array 返回数组
     */
    public function where($params)
    {
        return [
            'id' => '=',
			'name' => '=', 
			'created_at' => '=', 
			'updated_at' => '=', 
            'status'=>'=',
        ];
    }

    /**
     * 首页显示
     * @return string
     */
    public function actionIndex()
    {
        return $this->render('index', [
            'status' => Permission::getArrayStatus(),
            'statusColor' => Permission::getStatusColor(),
        ]);
    }
}
