<?php

namespace App\Modules\Product;
use App\Core\BaseRequest as BaseRequest;
use App\Core\Response;
use App\Models\Product;
use Auth;

class DeleteProductRequest extends BaseRequest{
   
    public $id;
   
}

class DeleteProductRequestHandler {

    public function __construct(){
    }

    public function Serve($request){

        $success = true;
        try{
            $product = Product::where([['id', $request->id],['store_id',Auth::user()->store_id]])->first();
            $product->deleted_by = Auth::user()->id;
            $product->deleted_at = date('Y-m-d H:i:s');
            $product->save();

        }catch(Exception $ex){
            $success = false;
        }

    	return new Response($success, $request);
    }
} 