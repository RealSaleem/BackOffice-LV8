<?php

namespace App\Modules\Catalogue\Product;
use App\Core\BaseRequest as BaseRequest;
use App\Core\Response;
use App\Models\Product;
use App\Models\ProductTag;
use Auth;

class updateHas_VariantRaquest extends BaseRequest{
    public $id;
}
 

class updateHas_VariantRaquestHandler {

    public function __construct(){
    }

    public function Serve($request){

        $product = Product::with('product_variants')->get();
        foreach ($product as $pro) {
            if($pro->product_variants->count() >  1){
                $pro->has_variant = 1;
            }
            $pro->save();
        }
        return new Response(true, $product);    
    }
} 