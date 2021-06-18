<?php

namespace App\Requests\Catalogue\Product;

use App\Core\BaseRequest as BaseRequest;
use App\Core\Response;
use App\Models\Product;
use DB;

class BulkProductRequest extends BaseRequest{
    public $store_id;
    public $type;
    public $product;
    public $action;
}

class BulkProductRequestHandler {

    public function __construct(){
    }

    public function Serve($request){

        try {
            DB::beginTransaction();

            $productObj = Product::where(['store_id' => $request->store_id])->whereIn('id',$request->product);

            $is_active = $request->action === 'true' ? 1 : 0;

            if($request->type == 'dinein'){
                $productObj->update(['dinein_display' => $is_active]);
            }else if($request->type == 'is_featured'){
                $productObj->update(['is_featured' => $is_active]);
            }else if($request->type == 'website'){
                $productObj->update(['web_display' => $is_active]);
            }else if($request->type == 'active'){
                $data = [
                    'active' => $is_active
                ];

                if(!$is_active){
                    $data['dinein_display'] = $data['is_featured'] = $data['web_display'] = $is_active;
                }
                $productObj->update($data);
            }else{
                $productObj->delete();
            }

            DB::commit();

            $message = $request->type == 'delete' ? \Lang::get('toaster.product_bulk_deleted') : \Lang::get('toaster.product_bulk_update');

            return new Response(true, null,null,null,$message);

        } catch (Exception $ex) {

            DB::rollBack();
            return new Response(false,null,$ex->getMessage());
        }
    }
}
