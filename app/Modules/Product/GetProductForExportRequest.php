<?php

namespace App\Modules\Product;
use App\Core\BaseRequest as BaseRequest;
use App\Core\Response;
use App\Models\Product;
use App\Models\ProductTag;
use App\Models\ProductVariant;
use Auth;
use Request;
use View;

class GetProductForExportRequest extends BaseRequest{
    public $store_id;
}

class GetProductForExportRequestValidatosr {
    public function IsValid($request){
        return new Response(true);
    }
}

class GetProductForExportRequestHandler {

    public function __construct(){
    }

    public function Serve($request)
	{
		// $product = Product::where('store_id' , Auth::user()->store_id)->with(['tags','product_variants','brand','supplier','category','user']);

		$prod_id = Product::select('id')->where('store_id' , Auth::user()->store_id)->get();
		$prod_id = array_map(function ($item) {return $item['id'];}, $prod_id->toArray());

		$product = ProductVariant::whereIn('product_id' , $prod_id)->with(['product'=>function($query){
			$query->with(['tags','brand','supplier','category','user'])->get();
		}])->get();

        return new Response(true, $product);
    }
}