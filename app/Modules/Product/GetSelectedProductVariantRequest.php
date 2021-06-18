<?php

namespace App\Modules\Product;
use App\Core\BaseRequest as BaseRequest;
use App\Core\Response;
use App\Models\Product;
use App\Models\ProductVariant;
use Auth;

class GetSelectedProductVariantRequest extends BaseRequest{
	public $product_id;
}

class GetSelectedProductVariantRequestHandler {

    public function Serve($request)
	{

		$productVariant = ProductVariant::where('product_id',$request->product_id)->get();
        return new Response(true, $productVariant);
    }
} 