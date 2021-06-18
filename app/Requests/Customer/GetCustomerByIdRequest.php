<?php

namespace App\Requests\Customer;
use App\Core\BaseRequest as BaseRequest;
use App\Core\Response;
use App\Models\Customer;
use Auth;

class GetCustomerByIdRequest extends BaseRequest{

    public $id;

}
class GetCustomerByIdRequestHandler {

    public function Serve($request){

        $customer = Customer::find($request->id);

        $customer_template = [
			'id' => $customer->id,
			"customer_group_id" => $customer->customer_group_id,
			"name" => $customer->name,
			"sex" => $customer->sex,
			"phone" => $customer->phone,
            "mobile" => $customer->mobile,
            'email' => $customer->email,
			"street" => $customer->street,
            "latitude"    => $customer->latitude,
            "longitude" => $customer->longitude
        ];

    	return new Response(true, $customer_template);
    }
}
