<?php

namespace App\Modules\Product;
use App\Core\BaseRequest as BaseRequest;
use App\Core\Response;
use App\Models\Store;
use Auth;

class GetStoreSkuRequest extends BaseRequest
{
    public $id;
}

class GetStoreSkuRequestHandler
{
    public function Serve($request)
	{
		$login_user = is_null(Auth::user())? \App::make('user') : Auth::user();
        $sku = Store::select('id','current_sequence_number','sku_generation')->with('product_variants')->where('id',$login_user->store_id)->first();
       
        return new Response(true, $sku );
    }
} 