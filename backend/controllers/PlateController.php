<?php

namespace backend\controllers;

use common\models\Plate;
/**
 * Class PlateController 子系统管理模块 执行操作控制器
 * @package backend\controllers
 */
class PlateController extends Controller
{
    /**
     * @var string 定义使用的model
     */
    public $modelClass = 'common\models\Plate';
     
    /**
     * 查询处理
     * @param  array $params
     * @return array 返回数组
     */
    public function where($params)
    {
        return [
            'id' => '=',
			'app_id' => '=', 
			'app_secret' => '=', 
			'plate_host' => '=', 
			'plate_name' => '=', 
			'default_group' => '=', 
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
            'status' => Plate::getArrayStatus(),
            'statusColor' => Plate::getStatusColor(),
        ]);
    }
}
