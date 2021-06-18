<?php

namespace App\Modules\Supplier;
use App\Core\BaseRequest as BaseRequest;
use App\Core\Response;
use App\Models\Supplier;

class GetSupplierRequest extends BaseRequest{
    public $id;
    //TODO: utilize store id
    public $store_id;
}

class GetSupplierRequestHandler {

    public function __construct(){
    }

    public function Serve($request){
        $supplier = Supplier::find($request->id);
        if($supplier == null)
        	return new Response(false);
        
    	return new Response(true, $supplier);
    }
} 