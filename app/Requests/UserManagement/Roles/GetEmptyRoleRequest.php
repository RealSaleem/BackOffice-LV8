<?php

namespace App\Requests\UserManagement\Roles;
use App\Core\BaseRequest as BaseRequest;
use App\Core\Response;
use Auth;

class GetEmptyRoleRequest extends BaseRequest
{
}

class GetEmptyRoleRequestHandler {

    public function Serve($request){
    
        $role = [
			'id' => 0,
			'name' =>'',
			'title' =>'',
			'level' =>'',
			'scope' =>'',
        ];

        
    	return new Response(true, $role);
    }
} 