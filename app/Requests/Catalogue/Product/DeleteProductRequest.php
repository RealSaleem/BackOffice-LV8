<?php

namespace App\Requests\Catalogue\Product;
use App\Core\BaseRequest as BaseRequest;
use App\Core\Response;
use App\Models\Product as Product;

class DeleteProductRequest extends BaseRequest{
    public $store_id;
    public $product_id;
}

class DeleteProductRequestHandler {

    public function __construct(){
    }

    public function Serve($request){

        $product = Product::find($request->product_id);

        $success = true;
        $errors = [];

        try{
            if($product->products_count == 0){
                $product->delete();
            }else{
                $product->is_deleted = true;
                $product->save();
            }

        }catch(Exception $ex){
            $success = false;
        }

    	return new Response($success, $request,null,$errors,\Lang::get('toaster.product_deleted'));
    }
}
