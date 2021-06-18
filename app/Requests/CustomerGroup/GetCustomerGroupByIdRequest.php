<?php

namespace App\Requests\CustomerGroup;
use App\Core\BaseRequest as BaseRequest;
use App\Core\Response;
use App\Models\CustomerGroup as CustomerGroup;
use Auth;

class GetCustomerGroupByIdRequest extends BaseRequest{
   
    public $id;
    
}
class GetCustomerGroupByIdRequestHandler {

    public function Serve($request){
        $customergroup = CustomerGroup::find($request->id);

        $customergroup_template = [
			'id' => $customergroup->id,
			'name'=>$customergroup->name,
        ];

       

    	return new Response(true, $customergroup_template);
    }
} 