<?php

namespace App\Requests\Catalogue\Brands;
use App\Core\BaseRequest as BaseRequest;
use App\Core\Response;
use App\Models\Brand;
use App\Models\Brand as Brands;

class DeleteBrandsRequest extends BaseRequest{

    public $id;
    public $brands_id;
}

class DeleteBrandsRequestHandler {

    public function __construct(){
    }

    public function Serve($request){
        $check_brand = Brands::withCount('products')->where(['id'=> $request->id,'store_id'=> $request->store_id])->first();
        try {
            if($check_brand !=  null){
                if ($check_brand->products_count > 0) {

                   return new Response(false, null,null,['Unable to delete brand which has products'],null);
                } else {
                    $check_brand->is_deleted = 1;
                    $check_brand->save();
                    $check_brand->delete();
                   return new Response(true, null,null,null,\Lang::get('toaster.brand_deleted'));
                }
            }
        	return new Response(false, null,null,['Unable to find brand'],null);
        } catch (\Exception $ex) {
           return new Response(false, null,null,[$ex->getMessage()],null);
        }
    }
}
