<?php

/**
 * @author xiaojian
 * @file MenuController.php
 * @info 系统菜单控制器
 * @date 2017年8月23日
 */

namespace App\Http\ManagerApi\Controllers;

use Laravel\Lumen\Routing\Controller;
use App\Api\Contracts\ApiContract;
use App\Core\AuthContract;
use App\Api\Traits\Func\ArraySortTrait;
use App\Models\AccessMenu;
use App\Models\AccessMenuModel;

class MenuController extends Controller
{
    use ArraySortTrait;

    private $api;

    private $menu;

    /**
     * Create a new controller instance.
     *
     * @return void
     */
    public function __construct(ApiContract $api)
    {
        $this->api = $api;
        $this->menu = new AccessMenu();
    }

    /**
     * @name   获取系统所有菜单（用于菜单设置模块）
     * @author xiaojian
     * @return array[result:请求结果，message:操作信息,datas:数据结果]
     */
    function getAllMenu()
    {
        //get groups data
        $groups = $this->menu->groupData();

        $models = AccessMenuModel::orderBy('level')->get();

        //desc sort  groups data by level
        foreach ($groups as $key => $value) {
            $groups[$key]['groups'] = $this->array_sort_params($value['groups'], 'level', SORT_ASC);
        }

        //按parentid分组获取数据
        return $this->api->datas(['groups'=>$groups,'models'=>$models]);
    }

    function addMenu()
    {
        $params = $this->api->getParams(['title', 'icon', 'url', 'parentid:integer', 'permissionid:integer']);

        if ($params['result']) {
            $max = $this->menu->where('parentid', $params['datas']['parentid'])->max('level');
            $params['datas']['level'] = empty($max) ? 1 : ++$max;
            return $this->api->insert_message($this->menu->insertGetId($params['datas']), '菜单添加成功~','菜单添加失败~');
        }
        else {
            return $params;
        }
    }

    function deleteMenu()
    {

        $param = $this->api->getParam('menuid:integer');

        if ($param['result']) {
            //delete menuid
            $menuid = $param['datas']['menuid'];

            //remove child menu
            $this->menu->where('parentid', $menuid)->delete();

            //remove self
            $result = $this->menu->destroy($menuid);

            return $this->api->delete_message($result, '菜单删除成功~','菜单删除失败~');
        }
        else {
            return $param;
        }
    }

    function updateMenu()
    {
        $params = $this->api->getParams(['id:integer'], ['title', 'icon', 'url', 'permissionid:integer']);

        if ($params['result']) {
            //update menuid
            $menuid = $params['datas']['id'];
            unset($params['datas']['id']);

            if (empty($params['datas'])) {
                return $this->api->error('not have any params');
            }

            //try update menu
            $this->menu->where('id', $menuid)->update($params['datas']);

            return $this->api->success('菜单修改成功~');
        }
        else {
            return $params;
        }
    }

    function sortMenu()
    {
        $param = $this->api->getParams(['ids']);
        if ($param['result']) {
            $ids = explode(',', $param['datas']['ids']);
            $result = $this->menu->sort($ids, 'level');
            return $result ? $this->api->success("排序成功") : $this->api->error("排序失败");
        }
        else {
            return $param;
        }
    }

    /**
     * @name   获取管理员的菜单（根据用户的权限列出菜单）
     * @author xiaojian
     * @return array[result:请求结果，message:操作信息,datas:数据结果]
     */
    function getAdminMenu(AuthContract $auth)
    {
        $permissions = $auth->info->getPermissionids();

        $permissions[] = 0;

        $groups = $this->menu->groupData([
            ['op' => 'whereIn', 'params' => ['permissionid', $permissions]]
        ]);

        //desc sort  groups data by level
        foreach ($groups as $key => $value) {
            $groups[$key]['groups'] = $this->array_sort_params($value['groups'], 'level', SORT_ASC);
        }

        //按parentid分组获取数据
        return $this->api->datas($groups);
    }

    public function addModel(){
        $params = $this->api->checkParams(['title']);
        $model = AccessMenuModel::find($params);
        if(isset($mdoel)){
            return $this->api->error('请不要添加重复的菜单模块');
        }
        $max = AccessMenuModel::max('level');
        $params['level'] = empty($max) ? 1 : ++$max;
        AccessMenuModel::insert($params);
        return $this->api->success('添加成功');
    }

    public function updateModel(){
        $params = $this->api->checkParams(['id:integer','title']);
        $model = AccessMenuModel::find($params['id']);
        if(!isset($model)){
            return $this->api->error('菜单模块不存在');
        }
        $model->title = $params['title'];
        $model->save();
        return $this->api->success('修改成功');
    }

    public function deleteModel(){

        $params = $this->api->checkParams(['id:integer']);

        //remove menu
        $this->menu->where('mid', $params['id'])->delete();

        //remove self
        $result = AccessMenuModel::destroy($params['id']);

        return $this->api->delete_message($result, '菜单模块删除成功~','菜单模块删除失败~');     
    }

    public function sortModel(){
        $params = $this->api->checkParams(['ids:string']);
        $ids = explode(',', $params['ids']);
        $result = with(new AccessMenuModel())->sort($ids, 'level');
        return $result ? $this->api->success("排序成功") : $this->api->error("排序失败");
    }
}
