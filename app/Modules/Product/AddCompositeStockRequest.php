<?php

namespace App\Modules\Product;
use App\Core\BaseRequest as BaseRequest;
use App\Core\Response;
use App\Models\ProductStock;
use App\Models\ProductVariant;
use Auth;

class AddStockRequest extends BaseRequest{
    public $invoice_date;
    public $invoice_number;
    public $purchase_order;
    public $cost_price;
    public $sale_price;
    public $margin;
    public $notes;
    public $product_id;
    public $variant_id;
    public $quantity;
}

class AddStockRequestValidator {
    public function GetRules(){
         return [
            'invoice_date' => 'required',
            'invoice_number' => 'required|alpha_num',
            // 'purchase_order' => 'required|alpha_num',
            'cost_price' => 'required|numeric',
            'sale_price' => 'required|numeric',
            // 'margin' => 'numeric',
            // 'notes' => 'required|string',
            'product_id' => 'required|numeric',
            'variant_id' => 'numeric',
            'quantity' => 'required|numeric',
        ];
    }
}

class AddStockRequestHandler {

    public function Serve($request){

        try{
			if($request->quantity > 0){
				$productStock = new ProductStock();
				$productStock->invoice_date = $request->invoice_date;
				$productStock->invoice_number = $request->invoice_number;
				$productStock->purchase_order = $request->purchase_order;
				$productStock->cost_price = $request->cost_price;
				$productStock->sale_price = $request->sale_price;
				$productStock->margin = $request->margin;
				$productStock->notes = $request->notes;
				$productStock->quantity = $request->quantity;
				$productStock->product_id = $request->product_id;
				$productStock->variant_id = $request->variant_id;
				$productStock->created = $productStock->updated = Auth::user()->id;
				$productStock->created_at = $productStock->updated_at = date('Y-m-d H:i:s');
				$productStock->is_remove = false;

				if($productStock->variant_id<=0){
					$quantity = 0;
					$productComposite = ProductStock::where('product_id',$request->product_id)->orderBy('quantity', 'desc')->first();
					if($productComposite!=null){
						$quantity = $productComposite->quantity;
					}
					$productStock->quantity = $quantity + $productStock->quantity;
				}
				$productStock->save();

				if($productStock->variant_id>0){
					$productVariant = ProductVariant::where('id',$productStock->variant_id)->first();
					$quantity = $productVariant->quantity;
					if(isset($productVariant->quantity) && $productVariant->quantity > 0){
						$quantity = $productVariant->quantity;
					}
					$productVariant->quantity = $quantity + $productStock->quantity;
					$productVariant->retail_price = $request->sale_price;
					$productVariant->supplier_price = $request->cost_price;

					$productVariant->save();
				}
				return new Response(true, $productStock);
			}else{
				return new Response(false, ['Invalid quantity provided']);
			}
		}catch(Exception $ex){
			return new Response(false, $ex);
		}
    }
} 