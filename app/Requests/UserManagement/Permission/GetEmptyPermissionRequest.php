<?php

namespace App\Requests\UserManagement\Permission;
use App\Core\BaseRequest as BaseRequest;
use App\Core\Response;
use Auth;

class GetEmptyPermissionRequest extends BaseRequest
{
}

class GetEmptyPermissionRequestHandler {

    public function Serve($request){
        $permission = [
			'id' => 0,
			'title'=>null,
			'name'=>null,
			'entity_id'=>null,
			'entity_type'=>null,
			'description'=>null
        ];

        
    	return new Response(true, $permission);
    }
} 