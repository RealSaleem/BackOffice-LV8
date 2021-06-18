<?php

namespace App\Requests\Catalogue\Product;
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
    public $lang;
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
        ini_set('memory_limit', '1024M');

		$prod_id = Product::where('store_id' , $request->user->store_id)
                    ->whereNull('deleted_at')
                    ->pluck('id')
                    ->toArray();
        $product_variants = ProductVariant::whereIn('product_id' , $prod_id);

        if ($request->lang != null) {

            $product_variants = $product_variants->with('product');

        }else{
            $product_variants = $product_variants->with(
                'product.brand',
                'product.product_categories.category.parent.parent',
                'product.product_suppliers.supplier',
                'product.product_images',
                'product.composite_products.product_variant',
                'product_stock.outlet'
            );
        }
        $product_variants = $product_variants->orderBy('id','asc')->get()->toArray();

        return new Response(true, $product_variants);
    }
}