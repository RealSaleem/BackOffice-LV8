<?php

namespace App\Modules\Product;
use App\Core\BaseRequest as BaseRequest;
use App\Core\Response;
use App\Models\Product;
use Auth;

class GetAllProductRequest extends BaseRequest
{
    public $id;
}

class GetAllProductRequestHandler
{
    public function Serve($request)
	{
		$user = is_null(Auth::user())? \App::make('user') : Auth::user();
        $products = Product::where('store_id',$user->store_id)->where('active',1)->get();

        return new Response(true, $products);
    }
    
} 