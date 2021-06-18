<?php

namespace App\Requests\UserManagement\Roles;

use App\Core\BaseRequest as BaseRequest;
use App\Core\Response;
use App\Models\Permission;
use App\Models\Permission_role;
use App\Models\Role;
use App\Models\Store;
use Auth;

class GetRoleByIdRequest extends BaseRequest
{

    public $id;
    public $name;
    public $display_name;
    public $description;

}

class GetRoleByIdRequestHandler
{

    public function Serve($request)
    {

        $id = $request->id;
        $role = Role::with('permissions')->find($id);

        $data = [
            'title' => __('role.upd_role'),
            'button_title' => __('role.update'),
            'route' => route('api.update.roles'),
            'role' => $role,
            'permissions'=> $role->permissions,
        ];

        return new Response(true,$data );
    }
}
