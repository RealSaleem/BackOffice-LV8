<?php

namespace App\Modules\Product;
use App\Core\BaseRequest as BaseRequest;
use App\Core\Response;
use App\Models\Product;
use Auth;

class GetAllWebProductRequest extends BaseRequest
{
    
}

class GetAllWebProductRequestHandler
{
    public function Serve($request)
	{
        $products = Product::with(['brand','category','supplier','product_variants'])->where('store_id',Auth::user()->store_id)->get();
        return new Response(true, $products);
    }
} 