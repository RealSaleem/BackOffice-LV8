<?php

namespace App\Requests\Catalogue\Supplier;
use App\Core\BaseRequest as BaseRequest;
use App\Core\Response;
use App\Models\Supplier as Supplier;

class DeleteSupplierRequest extends BaseRequest{

    public $store_id;
    public $supplier_id;

}

class DeleteSupplierRequestHandler {

    public function __construct(){
    }

    public function Serve($request){
        $supplier = Supplier::withCount('products')->find($request->supplier_id);

        $success = true;
        $errors = [];

        try{
            if($supplier->products_count == 0){
                $supplier->is_deleted = true;
                $supplier->save();
                $supplier->delete();
                $success = true;
                $Message = \Lang::get('toaster.supplier_deleted');

            }else{
                $success = false;
                $Message = \Lang::get('toaster.supplier_product_exist');
            }
            return new Response($success, $request,null,null, $Message);

        }catch(Exception $ex){
            return new Response(false, null, null, $ex->getMessage(), null);

        }


    }
}
