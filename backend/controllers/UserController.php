<?php

namespace backend\controllers;

use common\models\Group;
use Yii;
use backend\models\User;
use common\models\User as Member;
use common\models\UserGroup;
use yii\db\Exception;
use yii\web\HttpException;

/**
 * Class UserController 用户信息
 * @package backend\controllers
 */
class UserController extends Controller
{
    /**
     * @var string 定义使用的model
     */
    public $modelClass = 'backend\models\User';

    /**
     * 查询处理
     * @param  array $params
     * @return array 返回数组
     */
    public function where($params)
    {
        return [
            'username' => 'like',
            'email' => 'like',
        ];
    }

    /**
     * 首页显示
     * @return string
     */
    public function actionIndex()
    {
        return $this->render('index', [
            'status' => User::getArrayStatus(),
            'statusColor' => User::getStatusColor(),
        ]);
    }

    /**
     * 处理导出数据显示的问题
     * @return array
     */
    public function getExportHandleParams()
    {
        $array['created_at'] = $array['updated_at'] = function ($value) {
            return date('Y-m-d H:i:s', $value);
        };

        return $array;
    }

    public function actionEdit($name)
    {
        $model = Member::findByUsername($name);  // 查询对象
        // 添加权限
        $request = Yii::$app->request;       // 请求信息
        $array = $request->post();// 请求参数信息
        $userGroups = $model->getGroups()->orderBy('order')->asArray()->all();

        if(!empty($array)){
            $arr = [];
            foreach($array as $temp)
            {
                if(
                    isset($temp['user_id']) &&
                    isset($temp['group_id']) &&
                    !empty($temp['user_id']) &&
                    !empty($temp['group_id']) &&
                    is_numeric($temp['user_id']) &&
                    is_numeric($temp['group_id'])
                ){
                    $arr['user_id'] = $temp['user_id'];
                    $arr['group_id'] = $temp['group_id'];
                }
            }
            if (!$model) {
                throw new HttpException(404);
            }
            $trans = Yii::$app->db->beginTransaction();
            try {
                foreach ($userGroups as $userGroup) {
                    $userGroup->delete();
                }
                Yii::$app->db->createCommand()
                    ->batchInsert(UserGroup::tableName(), ['user_id', 'group_id'], $arr)
                    ->execute();
                $trans->commit();
            } catch (Exception $e) {
                $trans->rollBack();
            }
            $userGroups = $model->getGroups()->orderBy('order')->asArray()->all();
        }

        $groups = Group::find()->orderBy('order')->asArray()->all();

        $trees = [];
        if ($groups) {
            // 获取一级目录
            foreach ($groups as $group) {
                // 初始化的判断数据
                $id = ($group['pid'] == 0 || $group['pid'] == '') ? $group['id'] : $group['pid'];
                $array = [
                    'text' => $group['group_name'],
                    'id' => $group['id'],
                    'data' => $group['group_name'],
                    'state' => [],
                ];

                // 默认选中
                $array['state']['selected'] = true;
                if (!isset($trees[$id])) {
                    $trees[$id] = ['children' => []];
                }

                // 判断添加数据
                if ($group['pid'] == 0) {
                    $trees[$id] = array_merge($trees[$id], $array);
                } else {
                    $trees[$id]['children'][] = $array;
                }
            }
        }
        $temp = [];
        foreach ($trees as $tree){
            $temp[] = $tree;
        }
        $trees = $temp;


        return $this->render('edit', [
            'model'=>$model,
            'models' => $userGroups,
            'trees'=>$trees,
        ]);
    }

    public function actionView($name)
    {
        // 查询角色信息
        /* @var $model \backend\models\User */
        $model = Member::findByUsername($name);

        $userGroups = $model->getGroups()->orderBy('order')->asArray()->all();

        $userPlates = $model->getPlates()->asArray()->all();

        $userMenus = $model->getMenus()->asArray()->all();

    }
}
