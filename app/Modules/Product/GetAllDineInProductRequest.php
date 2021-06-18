<?php

namespace App\Modules\Product;
use App\Core\BaseRequest as BaseRequest;
use App\Core\Response;
use App\Models\Product;
use Auth;

class GetAllDineInProductRequest extends BaseRequest
{
    public $store_id;
}

class GetAllDineInProductRequestHandler
{
    public function Serve($request)
	{
        $products = Product::where('store_id',$request->store_id)->get();
        return new Response(true, $products);
    }
} 