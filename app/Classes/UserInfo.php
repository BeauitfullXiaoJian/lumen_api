<?php

namespace App\Classes;

use App\Models\AccessPermission;
use App\Models\AccessRole;

class UserInfo
{

    private $user;
    private $roles;
    private $permissions;
    private $permissionids;

    public function __construct($user)
    {
        $this->user = $user;
    }

    function getPermissionids()
    {
        $this->allPermissions();
        return $this->permissionids;
    }

    function allPermissions()
    {

        if (!isset($this->permissions)) {
            // 获取用户的所有角色
            $roles = $this->allRoles();

            // 获取角色的所有权限
            $permissions = [];
            foreach ($roles as $role) {
                $permission = $role->permissions ? explode(',', $role->permissions) : [];
                $permissions = array_merge($permissions, $permission);
            }

            // 剔除重复权限
            $permissions = array_unique($permissions);


            $this->permissionids = $permissions;

            $this->permissions = count($permissions)>0?AccessPermission::whereIn('id', $permissions)->get():[];
        }

        return $this->permissions;
    }

    function hasPermission($key)
    {

        $id = AccessPermission::where('key', $key)->value('id');

        $this->allPermissions();

        return (isset($id) && $id > 0)?in_array($id, $this->permissionids):false;
    }

    function isRole($role_id)
    {
        $this->allRoles();
        foreach ($this->roles as $role) {
            if ($role->id==$role_id) {
                return true;
            }
        }
        return false;
    }

    function role()
    {
        $roles = $this->allRoles();
        return empty($roles)?null:$roles[0];
    }

    function allRoles()
    {

        if (!isset($this->roles)) {
            $this->roles = !empty($this->user->role)?AccessRole::whereIn('id', explode(',', $this->user->role))->get():[];
        }

        return $this->roles;
    }
}
