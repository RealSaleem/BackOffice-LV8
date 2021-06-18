<?php

namespace App\Requests\Customer;
use App\Core\BaseRequest as BaseRequest;
use App\Core\Response;
use Auth;

class GetEmptyCustomerRequest extends BaseRequest
{
}

class GetEmptyCustomerRequestHandler {

    public function Serve($request){

        $customer = [
			'id' => 0,
			"customer_group_id" => 0,
			"name" => '',
			"phone" => '',
			"mobile" => '',
			'email' => '',
			"sex" => 'M',
            "address"   => '',
            "latitude" => '',
            "longitude" => '',
        ];

    	return new Response(true, $customer);
    }
} 