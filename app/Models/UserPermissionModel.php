<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class UserPermissionModel
{
    public static function getModulePermissions($module_id,$user)
    {


        $permissions = [];
        foreach($user->permissions as $permission)
        {
            if($permission->module_id === $module_id){
                $permissions[] = $permission->permission_id;
            }
        }

        return $permissions;
    }


    public static function hasReadWritePermission($module_id,$user)
    {
        $permissions = self::getModulePermissions($module_id,$user);

        if(!empty($permissions))
        {
            return true;
        }else{
            return false;
        }
    }

    public function hasWritePermission($module_id,$user)
    {
        $permissions = self::getModulePermissions($module_id,$user);
        if(in_array(PermissionsModel::WRITE,$permissions))
        {
            return true;
        }else{
            return false;
        }
    }
}
