<?php

namespace App\Modules\Catalogue\Product;
use App\Core\BaseRequest as BaseRequest;
use App\Core\Response;
use App\Models\Outlet as Outlet;
use App\Models\Product;
use App\Models\ProductVariant;
use App\Models\ProductStock;
// use App\Models\Outlet;
use App\Helpers\VariantStock;
use App\Models\ProductAttribute;
use App\Models\ProductAttributeValue;
use Auth;

class GetOutletByIdRequest extends BaseRequest{
   
    public $id;
    
}
class GetOutletByIdRequestHandler {

    public function Serve($request){

        $product     = Product::with('product_stock')->find($request->id);

        $outlets = Auth::user()->store->outlets->toArray();
        $variants = ProductVariant::where('product_id',$request->id)->get();
        $modified_variants = [];

        if( sizeof($variants) > 0)
        {
            foreach ($variants as $variant) 
            {
                $variant_outlets = [];

                foreach ($outlets as $outlet) 
                {
                     // dd($outlet);
                    $where = [
                        ['outlet_id','=',$outlet['id']],
                        ['product_id','=',$variant->product_id],
                        ['variant_id','=',$variant->id],
                        ['is_default','=',1],
                    ];

                    $product_stock = ProductStock::where($where)->first();


                    if(!is_null($product_stock)){
                        $outlet['quantity'] = VariantStock::count($outlet['id'],$variant->id);
                        $outlet['re_order_point'] = $product_stock->re_order_point;
                        $outlet['re_order_quantity'] = $product_stock->re_order_quantity;
                        $outlet['supply_price'] = $product_stock->cost_price;
                        $outlet['margin'] = $product_stock->margin;
                    }else{
                        $outlet['quantity'] = VariantStock::count($outlet['id'],$variant->id);
                        $outlet['re_order_point'] = 0;
                        $outlet['re_order_quantity'] = 0;
                        $outlet['supply_price'] = 0;
                        $outlet['margin'] = 0;
                    }

                    array_push($variant_outlets, $outlet);
                }

                $variant->outlets = $variant_outlets;
            }
        }
                // dd($outlets);
        
    	return new Response(true, $variants);
    }
} 