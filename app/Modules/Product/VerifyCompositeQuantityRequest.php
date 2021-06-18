<?php

namespace App\Modules\Product;
use App\Core\BaseRequest as BaseRequest;
use App\Core\Response;
use App\Models\Product as ProductModel;
use App\Models\CompositeProduct;
use App\Helpers\VariantStock;


class VerifyCompositeQuantityRequest extends BaseRequest{
    public $product_id;
    public $outlet_id;
    public $quantity;
}

class VerifyCompositeQuantityRequestValidator {

	public function GetRules(){
        return [
            'product_id' => 'required|integer',
			'outlet_id' => 'required|integer',
            'quantity' => 'required|integer',
        ];
    }
}

class VerifyCompositeQuantityRequestHandler {

    public function Serve($request){
        //$request->product_id = 205;

        $varified = true;

        $variants = CompositeProduct::where('product_id',$request->product_id)->get();

        foreach ($variants as $variant) {
            $available_quantity = VariantStock::count($request->outlet_id,$variant->product_variant_id);
            $expected_quantity = $request->quantity * $variant->quantity;

            if($available_quantity < $expected_quantity){
                $varified = false;
                break;
            }
        }

        if($varified){
            return new Response(true);
        }else{
            return new Response(false,null,null,['error' => 'Compoite quantity is not available']);
        }
    }
} 