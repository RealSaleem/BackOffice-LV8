<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Permission extends Model
{
    protected $table = 'permissions';


    public function permissionRoles()
    {
        return $this->hasMany('App\Models\PermissionRole','permission_id','id');
    }

}
