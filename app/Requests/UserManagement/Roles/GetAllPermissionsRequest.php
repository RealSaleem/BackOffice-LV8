<?php

namespace App\Requests\UserManagement\Roles;

use App\Core\BaseRequest as BaseRequest;
use App\Core\DataTableResponse;
use Spatie\Permission\Models\Permission;
use App\Models\Entity;
use Spatie\Permission\Models\Role;
use App\Core\Response;

class GetAllPermissionsRequest extends BaseRequest{
    public $store_id;
    public $id;
}

class GetAllPermissionsRequestHandler {

    public function __construct(){
    }

    public function Serve($request){

        $role = Role::find($request->id);
        $permission = Permission::get();
        $entity = Entity::get();
//        dd($entity);

        $data = array(
            'role'          => $role,
            'permission'    => $permission,
            'entity'        => $entity
        );

        return new Response(true, $data);
    }
}
