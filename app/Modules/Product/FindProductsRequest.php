<?php

namespace App\Modules\Product;
use App\Core\BaseRequest as BaseRequest;
use App\Core\Response;
use App\Models\Product as ProductModel;
use App\Helpers\VariantStock;
use Auth;

class FindProductsRequest extends BaseRequest{
    public $q;
    public $outlet_id;
    public $show_composite;
    public $supplier_id;
}

class FindProductsRequestValidatosr {
    public function IsValid($request){
        return new Response(true);
    }
} 

class FindProductsRequestHandler {

    public function __construct(){
    }

    public function Serve($request){
        $query = "  SELECT pv.id AS product_variant_id, p.name AS name, p.id as product_id
                        , pv.attribute_value_1, pv.attribute_value_2
                        , pv.attribute_value_3, pv.sku
                        , pv.supplier_price,pv.retail_price
                        , p.store_id
                        , p.id
                    FROM products p 
                    JOIN product_variants pv ON pv.product_id = p.id
                    WHERE (p.name LIKE '$request->q%' OR pv.sku LIKE '$request->q%') ";
        
        $query .= ' and p.store_id = '. Auth::user()->store_id;
        
        if(isset($request->supplier_id) && $request->supplier_id > 0){
            $query .= ' and p.supplier_id = '. $request->supplier_id;
        }

        if($request->show_composite == 1){
            $query .= ' and p.is_composite = 1';
        }else{
            $query .= ' and (p.is_composite = 0 OR p.is_composite IS NULL)';
        }

        $products = \DB::select($query);

        if(isset($request->outlet_id) && !is_null($request->outlet_id)){
            $data = [];

            if(sizeof($products) > 0){
                foreach ($products as $product) {
                    $row = $product;
                    $row->stock = VariantStock::count($request->outlet_id,$product->product_variant_id);
                    array_push($data, $row);
                }
            }

            return new Response(true, $data);
        }else{
            return new Response(true, $products);
        }

    }
} 