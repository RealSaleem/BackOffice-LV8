<?php

namespace App\Requests\UserManagement\Permission;
use App\Core\BaseRequest as BaseRequest;
use App\Core\Response;
use App\Models\Permission;
use Auth;

class GetPermissionByIdRequest extends BaseRequest{
    public $id;
}
class GetPermissionByIdRequestHandler {

    public function Serve($request){
        $permission = Permission::find($request->id);

        $permission_template = [
            'id' => $permission->id,
            'name' => $permission->name,
            'description' => $permission->description,
            'group' => $permission->group,
//			'entity_type'=>$permission->entity_type,
//            'description'=>$permission->description,
        ];
    	return new Response(true, $permission_template);
    }
}
