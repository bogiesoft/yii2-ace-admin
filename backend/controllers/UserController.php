<?php

namespace backend\controllers;

use common\models\Group;
use common\models\Permission;
use common\models\Plate;
use common\models\PlateMenu;
use Yii;
use backend\models\User;
use common\models\User as Member;
use common\models\UserGroup;
use yii\db\Exception;
use yii\db\Query;
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
                UserGroup::deleteAll(['user_id'=>$model->id]);
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
                $array['state']['selected'] = in_array($group,$userGroups);
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

        $userPlates = $model->getPlates()->asArray()->all();
        $plates = Plate::find()->asArray()->all();

        $plateTree = [];
        foreach ($plates as $plate) {
            $id = $plate['id'];
            $array = [
                'text' => $plate['plate_name'],
                'id' => $plate['id'],
                'data' => $plate['plate_name'],
                'state' => [],
            ];
            $array['state']['selected'] = in_array($plate,$userPlates);
            if (!isset($plateTree[$id])) {
                $plateTree[$id] = ['children' => []];
            }
            $plateTree[$id] = array_merge($plateTree[$id], $array);
        }

        $userMenus = $model->getMenus()->orderBy('order')->asArray()->all();
        $menus = PlateMenu::find()->asArray()->all();

        $menuTree = [];
        foreach ($menus as $menu) {
            $id = $menu['pid'] == 0  ? $menu['id'] : $menu['pid'];
            $array = [
                'text' => $menu['name'],
                'id' => $menu['id'],
                'data' => $menu['name'],
                'state' => [],
            ];
            $array['state']['selected'] = in_array($menu,$userMenus);
            if (!isset($menuTree[$id])) {
                $menuTree[$id] = ['children' => []];
            }
            if ($menu['pid'] == 0) {
                $menuTree[$id] = array_merge($menuTree[$id], $array);
            } else {
                $menuTree[$id]['children'][] = $array;
            }
        }

        $userPermissions = $model->getPermissions()->orderBy('order')->asArray()->all();
        $permissions = Permission::find()->orderBy('order')->asArray()->all();

        $authTree = [];
        foreach ($permissions as $permission) {
            $id = ($permission['pid'] == 0 || $permission['pid'] == '') ? $permission['id'] : $permission['pid'];
            $array = [
                'text' => $permission['name'].'('.$permission['url'].')',
                'id' => $permission['id'],
                'data' => $permission['desc'],
                'state' => [],
            ];
            $array['state']['selected'] = in_array($permission,$userPermissions);
            if (!isset($authTree[$id])) {
                $authTree[$id] = ['children' => []];
            }
            if ($permission['pid'] == 0) {
                $authTree[$id] = array_merge($authTree[$id], $array);
            } else {
                $authTree[$id]['children'][] = $array;
            }
        }

        return $this->render('edit', [
            'model'=>$model,
            'models' => $userGroups,
            'trees'=>$trees,
            'plates' => $plateTree,
            'menus' => $menuTree,
            'permissions' => $authTree,
        ]);
    }

    public function actionView($name) //目前只支持二级分类，要想更多分类需更改方法
    {
        // 查询角色信息
        /* @var $model \backend\models\User */
        $model = Member::findByUsername($name);

        $userGroups = $model->getGroups()->asArray()->all();
        $groups = Group::find()->orderBy('order')->asArray()->all();
        $groupTree = [];
        foreach ($groups as $group) {
            $id = ($group['pid'] == 0 || $group['pid'] == '') ? $group['id'] : $group['pid'];
            $array = [
                'text' => $group['group_name'],
                'id' => $group['id'],
                'data' => $group['group_name'],
                'state' => [],
            ];
            $array['state']['selected'] = in_array($group,$userGroups);
            if (!isset($groupTree[$id])) {
                $groupTree[$id] = ['children' => []];
            }
            if ($group['pid'] == 0) {
                $array['icon'] = 'menu-icon fa fa-list orange';
                $groupTree[$id] = array_merge($groupTree[$id], $array);
            } else {
                $array['icon'] = false;
                $groupTree[$id]['children'][] = $array;
            }
        }

        $userPlates = $model->getPlates()->asArray()->all();
        $plates = Plate::find()->asArray()->all();

        $plateTree = [];
        foreach ($plates as $plate) {
            $id = $plate['id'];
            $array = [
                'text' => $plate['plate_name'],
                'id' => $plate['id'],
                'data' => $plate['plate_name'],
                'state' => [],
            ];
            $array['state']['selected'] = in_array($plate,$userPlates);
            if (!isset($plateTree[$id])) {
                $plateTree[$id] = ['children' => []];
            }
            $plateTree[$id] = array_merge($plateTree[$id], $array);
        }

        $userMenus = $model->getMenus()->orderBy('order')->asArray()->all();
        $menus = PlateMenu::find()->asArray()->all();

        $menuTree = [];
        foreach ($menus as $menu) {
            $id = $menu['pid'] == 0  ? $menu['id'] : $menu['pid'];
            $array = [
                'text' => $menu['name'],
                'id' => $menu['id'],
                'data' => $menu['name'],
                'state' => [],
            ];
            $array['state']['selected'] = in_array($menu,$userMenus);
            if (!isset($menuTree[$id])) {
                $menuTree[$id] = ['children' => []];
            }
            if ($menu['pid'] == 0) {
                $menuTree[$id] = array_merge($menuTree[$id], $array);
            } else {
                $menuTree[$id]['children'][] = $array;
            }
        }

        $userPermissions = $model->getPermissions()->orderBy('order')->asArray()->all();
        $permissions = Permission::find()->orderBy('order')->asArray()->all();

        $authTree = [];
        foreach ($permissions as $permission) {
            $id = ($permission['pid'] == 0 || $permission['pid'] == '') ? $permission['id'] : $permission['pid'];
            $array = [
                'text' => $permission['name'].'('.$permission['url'].')',
                'id' => $permission['id'],
                'data' => $permission['desc'],
                'state' => [],
            ];
            $array['state']['selected'] = in_array($permission,$userPermissions);
            if (!isset($authTree[$id])) {
                $authTree[$id] = ['children' => []];
            }
            if ($permission['pid'] == 0) {
                $authTree[$id] = array_merge($authTree[$id], $array);
            } else {
                $authTree[$id]['children'][] = $array;
            }
        }
        return $this->render('view', [
            'model' => $model,
            'groups' => $groupTree,
            'plates' => $plateTree,
            'menus' => $menuTree,
            'permissions' => $authTree,
        ]);
    }

    /**
     * 无限极分类
     * @param $arr
     * @param $pid
     * @param $step
     * @return array
     */
    private function GetTree($arr,$pid,$step){
        global $tree;
        foreach($arr as $key=>$val) {
            if($val['pid'] == $pid) {
                $flg = str_repeat('└―',$step);
                $val['name'] = $flg.$val['name'];
                $tree[] = $val;
                $this->GetTree($arr , $val['cid'] ,$step+1);
            }
        }
        return $tree;
    }
    /**
     * $arr = array(
    0=>array(
    'cid'=>1,
    'pid'=>0,
    'name'=>'亚洲',
    ),
    1=>array(
    'cid'=>2,
    'pid'=>0,
    'name'=>'北美洲',
    ),
    2=>array(
    'cid'=>3,
    'pid'=>1,
    'name'=>'中国',
    ),
    3=>array(
    'cid'=>4,
    'pid'=>2,
    'name'=>'美国',
    ),
    4=>array(
    'cid'=>5,
    'pid'=>3,
    'name'=>'北京',
    ),
    5=>array(
    'cid'=>6,
    'pid'=>3,
    'name'=>'河北',
    ),
    6=>array(
    'cid'=>7,
    'pid'=>5,
    'name'=>'东城区',
    ),
    7=>array(
    'cid'=>8,
    'pid'=>5,
    'name'=>'海淀区',
    ),
    );
     */
}
