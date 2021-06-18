<?php

namespace App\Requests\CustomerGroup;
use App\Core\BaseRequest as BaseRequest;
use App\Core\Response;
use App\Models\CustomerGroup as CustomerGroup;
use Illuminate\Validation\Rule;

class EditCustomerGroupRequest extends BaseRequest{

    public $name;
    public $id;
}

class EditCustomerGroupRequestValidator{
    public function GetRules($request){
        return [
            'name' => [
                'required',
                'string',
                'max:100',
                Rule::unique('customer_groups')->where(function ($query) use ($request) {
                    return $query->where('store_id', $request->store_id);
                })->ignore($request->id),
            ],
        ];
    }
}

class EditCustomerGroupRequestHandler {

    public function __construct(){
    }


    public function Serve($request){
//        dd($request);
        $CustomerGroup = CustomerGroup::find($request->id);

    	 $CustomerGroup->name = $request->name;
    	 $CustomerGroup->save();


        return new Response(true, null,null,null,\Lang::get('toaster.customer_updated'));

    }
}
