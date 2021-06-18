<?php

namespace App\Modules\Supplier;
use App\Core\BaseRequest as BaseRequest;
use App\Core\Response;
use App\Models\Supplier;
use Auth;

class GetSupplierListRequest extends BaseRequest{
    public $name;
    public $email;
    public $mobile;
}

class GetSupplierListRequestHandler {

    public function __construct(){
    }

    public function Serve($request){
        
        $supplier = Supplier::where('store_id',Auth::user()->store_id);

        if(isset($request->name))
        {
            $supplier->where('name','like' ,'%'. $request->name .'%');
                     // ->orWhere('first_name', 'like', '%' . $request->name . '%')
                     // ->orWhere('last_name', 'like', '%' . $request->name . '%');
        }

        if(isset($request->email))
        {
            $supplier->where('email','like' ,'%'. $request->email .'%');
        }

        if(isset($request->mobile))
        {
            $supplier->where('phone','like' ,'%'. $request->mobile .'%')
                    ->orWhere('mobile', 'like', '%' . $request->mobile . '%');
        }                

    	return new Response(true, $supplier->orderBy('id','DESC')->get());
    }
} 