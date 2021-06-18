<?php

namespace App\Modules\Product;
use App\Core\BaseRequest as BaseRequest;
use App\Core\Response;
use App\Models\ProductVariant;

class GetVariantByIdRequest extends BaseRequest{
	public $id;
}

class GetVariantByIdRequestHandler {

    public function Serve($request)
	{

		$productVariant = ProductVariant::where('id',$request->id)->get();
        return new Response(true, $productVariant);
    }
} 