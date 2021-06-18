<?php

namespace App\Requests\Catalogue\Brands;

use App\Core\BaseRequest as BaseRequest;
use App\Core\Response;
use App\Models\Brand;
use DB;

class BulkBrandsRequest extends BaseRequest{
    public $store_id;
    public $type;
    public $brands;
    public $action;
}

class BulkBrandsRequestHandler {

    public function __construct(){
    }

    public function Serve($request){
        try {
            DB::beginTransaction();

            $brandsObj = Brand::where(['store_id' => $request->store_id])->whereIn('id',$request->brands);

            $is_active = $request->action === 'true' ? 1 : 0;

             if($request->type == 'active'){
                $data = [
                    'active' => $is_active
                ];

                $brandsObj->update($data);
            }else if ($request->type == 'feature'){
                 $data = [
                     'is_featured' => $is_active
                 ];
                 $brandsObj->update($data);
             }else if ($request->type == 'delete'){
                foreach ($brandsObj->withCount('products')->get() as $key => $brand) {


                    if($brand->products_count > 0){
                        continue;
                        $errorMessage = \Lang::get('toaster.brand_product_exist');
                        return new Response(false, null,null,null,$errorMessage);
                    }else{
                        $brand->is_deleted = 1;
                        $brand->deleted_by = $request->user->id;
                        $brand->save();
                        $brand->delete();
                    }

                }
            }
            DB::commit();
            $message = $request->type == 'active' || $request->type == 'feature' ? \Lang::get('toaster.brand_bulk_update') : \Lang::get('toaster.bands_bulk_deleted');
            return new Response(true, null,null,null,$message);
        } catch (\Exception $ex) {
            DB::rollBack();
            return new Response(false,null,$ex->getMessage());
        }
    }
}
