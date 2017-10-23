<?php

namespace backend\controllers;

/**
 * Class PlateMenuController 子系统菜单 执行操作控制器
 * @package backend\controllers
 */
use common\models\PlateMenu;
use yii\rest\ActiveController;

class PlateMenuController extends Controller
{
    /**
     * @var string 定义使用的model
     */
    public $modelClass = 'common\models\PlateMenu';
     
    /**
     * 查询处理
     * @param  array $params
     * @return array 返回数组
     */
    public function where($params)
    {
        return [
            'id' => '=',
			'pid' => '=', 
			'plate_id' => '=', 
			'url' => '=', 
			'name' => '=', 
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
            'status' => PlateMenu::getArrayStatus(),
            'statusColor' => PlateMenu::getStatusColor(),
        ]);
    }
}
