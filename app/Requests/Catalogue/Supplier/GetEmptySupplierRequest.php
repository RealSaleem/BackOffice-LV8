<?php

namespace App\Requests\Catalogue\Supplier;
use App\Core\BaseRequest as BaseRequest;
use App\Core\Response;
use Auth;

class GetEmptySupplierRequest extends BaseRequest
{
}

class GetEmptySupplierRequestHandler {

    public function Serve($request){
    	

        $supplier = [
			'id' => 0,
			"name" => '',
			"phone" => '',
			"mobile" => '',
			'email' => '',
			"address"   => '',
			"latitude" => '',
			"longitude" => '',
			"active" => '',
			
        ];


    	return new Response(true, $supplier);
    }
} 