<?php

namespace App\Modules\Product;
use App\Core\BaseRequest as BaseRequest;
use App\Core\Response;
use App\Models\ProductVariant;

class GetProductVariantRequest extends BaseRequest{
	public $id;
}

class GetProductVariantRequestHandler {

    public function Serve($request)
	{
		$productVariant = ProductVariant::select('sku')->orderBy('sku','desc')->first();
        return new Response(true, $productVariant);
    }
} 