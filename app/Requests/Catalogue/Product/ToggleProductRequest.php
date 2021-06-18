<?php

namespace App\Requests\Catalogue\Product;

use App\Core\BaseRequest as BaseRequest;
use App\Core\Response;
use App\Models\Product;
use DB;

class ToggleProductRequest extends BaseRequest{

    public $id;
    public $type;
    public $store_id;
}

class ToggleProductRequestValidator{
    public function GetRules(){
        return [
            'name' => [
                'required|integer',
                'required|string',
            ],
        ];
    }
}

class ToggleProductRequestHandler {

    public function Serve($request){
//dd($request);
        try {
            DB::beginTransaction();

            $product = Product::where(['id' => $request->id, 'store_id' => $request->store_id])->first();

            if($request->type == 'dinein'){
                $product->dinein_display = !$product->dinein_display;
            }else if($request->type == 'active'){
                $product->active = !$product->active;
            }else if($request->type == 'pos'){
                $product->is_featured = !$product->is_featured;
            }else if($request->type == 'website'){
                $product->web_display = !$product->web_display;
            }

            $product->save();

            DB::commit();

            $data = [
                'id' => $product->id,
                'name' => $product->name,
                'parent_product' => isset($product->parent) ? $product->parent->name : '-',
                'sort_order' => $product->sort_order,
                'total_products' => $product->products_count,
                'dinein' => $product->dinein_display,
                'pos' => $product->is_featured,
                'website' => $product->web_display,
                'active' => $product->active,
                'actions' => '',
            ];
//            dd($product);

            return new Response(true, $data,null,null,\Lang::get('toaster.product_updated'));

        } catch (Exception $ex) {

            DB::rollBack();
            return new Response(false,null,$ex->getMessage());
        }
    }
}
