<?php

namespace App\Modules\Product;
use App\Core\BaseRequest as BaseRequest;
use App\Core\Response;
use App\Models\Product;
use App\Models\ProductVariant;
use Auth;

class GetVariantOptionRequest extends BaseRequest{
	public $product_id;
	public $attribute;
}

class GetVariantOptionRequestHandler {

    public function Serve($request)
	{

		$productVariant = ProductVariant::select($request->attribute)->where('product_id',$request->product_id)->where('is_active',true)->groupBy($request->attribute)->get();
        return new Response(true, $productVariant);
    }
} 