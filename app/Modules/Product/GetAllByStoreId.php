<?php

namespace App\Modules\Product;
use App\Core\BaseRequest as BaseRequest;
use App\Core\Response;
use App\Models\Product as ProductModel;
use Auth;

class GetAllByStoreId extends BaseRequest{
    public $id;
}

class GetAllByStoreIdValidatosr {
    public function IsValid($request){
        return new Response(true);
    }
} 

class GetAllByStoreIdHandler {
	//private $model = new App\Models\Product();
    public function __construct(){
    }

    public function Serve($request){
        $user  = is_null(Auth::user()) ? \App::make('user') : Auth::user();
        $product = ProductModel::where('store_id', '=', $user->store_id)->get();

    	return new Response(true, $product);
    }
} 