<?php

namespace App\Requests\Catalogue\Supplier;

use App\Core\BaseRequest as BaseRequest;
use App\Core\Response;
use App\Models\Supplier;
use DB;

class BulkSupplierRequest extends BaseRequest{
    public $store_id;
    public $type;
    public $suppliers;
    public $action;
}

class BulkSupplierRequestHandler {

    public function __construct(){
    }

    public function Serve($request){

        try {
            DB::beginTransaction();
            $message = "";
            $supplierObj = Supplier::where(['store_id' => $request->store_id])->whereIn('id',$request->suppliers);

            $is_active = $request->action === 'true' ? 1 : 0;


            if($request->type == 'active'){
                $data = [
                    'active' => $is_active
                ];
                $success = true;
                $message = \Lang::get('toaster.supplier_bulk_update');
                $supplierObj->update($data);
            }else if ($request->type == 'delete'){
                foreach ($supplierObj->withCount('products')->get() as $key => $value) {
                    if ($value->products_count > 0) {
                        $message = \Lang::get('toaster.supplier_product_exist');
                        $success = false;
                        continue;

                    }else{
                        $value->delete();
                        $success = true;
                        $message = \Lang::get('toaster.supplier_deleted');
                    }
                }
            }

            DB::commit();

//            $message = $request->type == 'delete' ? \Lang::get('supplier.bulk_message') : \Lang::get('supplier.bulk_message');


            return new Response( $success, null,null,null, $message);

        } catch (Exception $ex) {

            DB::rollBack();
            return new Response(false,null,$ex->getMessage());
        }
    }
}
