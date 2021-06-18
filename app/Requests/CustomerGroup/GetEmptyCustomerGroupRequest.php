<?php

namespace App\Requests\Customergroup;
use App\Core\BaseRequest as BaseRequest;
use App\Core\Response;
use Auth;

class GetEmptyCustomerGroupRequest extends BaseRequest
{
}

class GetEmptyCustomerGroupRequestHandler {

    public function Serve($request){
    
        $customergroup = [
			'id' => 0,
			'name' =>'',
        ];

        
    	return new Response(true, $customergroup);
    }
} 