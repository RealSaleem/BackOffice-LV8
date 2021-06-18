<?php

namespace App\Requests\CustomerGroup;

use App\Core\BaseRequest as BaseRequest;
use App\Core\Response;
use App\Models\CustomerGroup;
use DB;

class BulkCustomerGroupRequest extends BaseRequest{
    public $store_id;
    public $type;
    public $customersgroup;
    public $action;
    public $deleted;
}

class BulkCustomerGroupRequestHandler {

    public function __construct(){
    }

    public function Serve($request){
        try {
            DB::beginTransaction();

            $customer_group = CustomerGroup::
                    where('store_id' , $request->store_id)
                    ->whereIn('id',$request->customersgroup)
                    ->withCount('customer')
                    ->get();


                if ($request->type == 'delete') {
                    foreach($customer_group as $group){
                    if ($group->customer_count > 0) {
                        $status = false;
                        $message = \Lang::get('toaster.customerGroup_customer_exist');
                        continue;
                    } else {
                        $group->delete();
                        $status = true;
                        $message = \Lang::get('toaster.customerGroup_deleted');
                    }
                }
            }
            DB::commit();
            return new Response($status, null,null,null,$message);
        } catch (\Exception $ex) {
            DB::rollBack();
            return new Response(false,null,$ex->getMessage());
        }
    }
}
