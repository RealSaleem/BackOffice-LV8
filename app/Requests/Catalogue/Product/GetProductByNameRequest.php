<?php

namespace App\Requests\Catalogue\Product;
use App\Core\BaseRequest as BaseRequest;
use App\Core\Response;
use App\Models\Product;
use App\Models\ProductVariant;
use Auth;

class GetProductByNameRequest extends BaseRequest{

    public $name;

}

class GetProductByNameRequestHandler {

    public function __construct(){
    }
    public function Serve($request){

        $varients = ProductVariant::where([['store_id', '=' , Auth::user()->store_id],['name', 'like',$request->name.'%']])->get()->toArray();
        $product = Product::where([['store_id', '=' , Auth::user()->store_id],['name', 'like',$request->name.'%']])->with('product_images')->first();

        if($varients == null){
            $variant = ProductVariant::where('product_id', $product['id'])->get()->toArray();
            $var_arr = [];

            foreach ($variant as $var) {
                $var_name = trim($product['name']);
                if (!is_null($var['attribute_value_1'])) {
                   $var_name                        .= ' '.trim($var['attribute_value_1']);
                }
                if (!is_null($var['attribute_value_2'])) {
                   $var_name                        .= ' '.trim($var['attribute_value_2']);
                }
                if (!is_null($var['attribute_value_3'])) {
                    $var_name                       .= ' '.trim($var['attribute_value_3']);
                }
                $var['name'] = trim($var_name);
                $var['unit'] = $product->unit;
                $var['sku'] = $var->sku;

                if(sizeof($product->product_images) > 0){
                    $var['image'] = $product->product_images->first()->url;
                }else{
                    $var['image'] = url('img/image-not-available.jpg');
                }

                array_push($var_arr, $var);
            }
            return new Response(true, $var_arr);
        }else{

            $var = [];

            foreach ($varients as $row) {
                $row['unit'] = $product->unit;

                if(sizeof($product->product_images) > 0){
                    $row['image'] = $product->product_images->first()->url;
                }else{
                    $row['image'] = url('img/image-not-available.jpg');
                }

                $row['sku'] = $product->product_variants->first()->sku;

                array_push($var,$row);
            }
              
            return new Response(true, $var);
        }
    }
} 