<?php

namespace App\Requests\CustomerGroup;
use App\Core\BaseRequest as BaseRequest;
use App\Core\Response;
use App\Models\CustomerGroup as CustomerGroup;

class DeleteCustomerGroupRequest extends BaseRequest{

    public $customergroup_id;

}

class DeleteCustomerGroupRequestHandler {

    public function __construct(){
    }

    public function Serve($request){

        $customergroup = CustomerGroup::find($request->customergroup_id);

        $success = false;
        $errors = [];

        try{
            $success = true;
             if($customergroup->customer->count() > 0){
                 $success = false;
                 $message = \Lang::get('toaster.customerGroup_customer_exist');
             }else{
                $customergroup->delete();
                 $success = true;
                 $message = \Lang::get('toaster.customerGroup_deleted');
             }
            return new Response($success, $request,null,null,$message);


        }catch(Exception $ex){
            $success = false;
            return new Response($success, $request,null,$ex);

        }

    }
}
