<?php

namespace backend\controllers;

use backend\models\Menu;
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

    public function actionEdit($username)
    {
        $model = Member::findByUsername($username);  // 查询对象

        // 添加权限
        $request = Yii::$app->request;       // 请求信息
        $array = $request->post();// 请求参数信息
        $userGroups = $model->getGroups()->all();
        $userGroupRelations = $model->getUserGroups()->all();

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

            $userGroups = $model->getUserGroups();
            $userGroupRelations = $model->getUserGroups()->all();
        }

        /** @var \common\models\User $userObj */
        $userObj = User::find()
            ->where(['username'=>$username])
            ->one();
        $groups = $userObj->getGroups()->all();
        $menus = [];
        foreach ($groups as $model){
            $menus[$model->group_name] = $model->group_name . ' (' . $model->desc . ')';
        }
        $arrHaves = array_keys($menus);

        $trees = [];
        if ($menus) {
            // 获取一级目录
            foreach ($menus as $value) {
                // 初始化的判断数据
                $id = $value['pid'] == 0 ? $value['id'] : $value['pid'];
                $array = [
                    'text' => $value['menu_name'],
                    'id' => $value['id'],
                    'data' => $value['url'],
                    'state' => [],
                ];

                // 默认选中
                $array['state']['selected'] = in_array($value['url'], $arrHaves);
                if (!isset($trees[$id])) {
                    $trees[$id] = ['children' => []];
                }

                // 判断添加数据
                if ($value['pid'] == 0) {
                    $array['icon'] = 'menu-icon fa fa-list orange';
                    $trees[$id] = array_merge($trees[$id], $array);
                } else {
                    $array['icon'] = false;
                    $trees[$id]['children'][] = $array;
                }
            }
        }

        // 导航信息
        $trees = array_values($trees);
        // 加载视图返回
        return $this->render('edit', [
            'model'=>$model,
            'models' => $userGroups,// 模型对象
            'relation'=>$userGroupRelations,
            'trees'=>$trees,
        ]);
    }
}
