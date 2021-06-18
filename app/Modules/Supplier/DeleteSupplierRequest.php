<?php

namespace App\Modules\Supplier;
use App\Core\BaseRequest as BaseRequest;
use App\Core\Response;
use App\Models\Supplier as Supplier;

class DeleteSupplierRequest extends BaseRequest{
   
    public $id;
   
}

class DeleteSupplierRequestHandler {

    public function __construct(){
    }

    public function Serve($request){
        $supplier = Supplier::find($request->id);
        $success = true;
        try{
    	 $supplier->delete();
        }catch(Exception $ex){
            $success = false;
        }

    	return new Response($success, $request);
    }
} 