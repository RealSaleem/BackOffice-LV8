<?php

namespace App\Requests\UserManagement\Users;
use App\Core\BaseRequest as BaseRequest;
use App\Core\Response;
use Auth;

class GetEmptyUsersRequest extends BaseRequest
{
}

class GetEmptyUsersRequestHandler {

    public function Serve($request){
    	
        $image_array = [
            'name'      => null, 
            'url'      => null, 
            'size'      => 0
        ];
        $user = [
			"user_image" => $image_array,
			'id' => 0,
            "name" => '',
            "mobile" => '',
            'email' => '',
            "active" => '',
            "password"=> '',
            'user_outlet_ids' => [],
        ];

    	return new Response(true, $user);
    }
} 