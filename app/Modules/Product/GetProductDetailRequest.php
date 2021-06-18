<?php

namespace App\Modules\Product;
use App\Core\BaseRequest as BaseRequest;
use App\Core\Response;
use App\Models\Product as ProductModel;
use App\Models\ProductTag;


class GetProductDetailRequest extends BaseRequest{
    public $id;
}

class GetProductDetailRequestValidatosr {
    public function IsValid($request){
        return new Response(true);
    }
}

class GetProductDetailRequestHandler {

    public function __construct(){
    }

    public function Serve($request){
        $product = ProductModel::with(
            'product_variants',
            'tags',
            'related',
            //'product_tags',
            'product_images',
            //'product_tags.tag',
            'composite_products.product_variant.product'
            )->find($request->id);

        return new Response(true, $product);
    }
}
