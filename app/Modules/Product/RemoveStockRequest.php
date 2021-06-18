<?php

namespace App\Modules\Product;
use App\Core\BaseRequest as BaseRequest;
use App\Core\Response;
use App\Models\ProductStock;
use App\Models\ProductVariant;
use App\Models\Store;
use App\Helpers\OrderType as OrderType;

use Auth;

use Mail;
use App\Mail\StockRemoved;

class RemoveStockRequest extends BaseRequest{
    public $invoice_date;
    public $invoice_number;
    public $purchase_order;
    public $notes;
    public $product_id;
    public $variant_id;
    public $quantity;
    public $outlet_id;
    public $store;
    public $store_id;
    public $flag;
}

class RemoveStockRequestValidatosr {
    public function GetRules(){
		return [
		   'invoice_date' => 'required|numeric',
		   // 'invoice_number' => 'required|alpha_num',
		   // 'purchase_order' => 'required|alpha_num',
		   // 'notes' => 'required|string',
		   'product_id' => 'required|numeric',
		   // 'variant_id' => 'numeric',
		   'quantity' => 'required|numeric',
		   'outlet_id' => 'required',
	   ];
    }
}

class RemoveStockRequestHandler {

    public function Serve($request){
    	$store = Store::where('id',Auth::user()->store_id)->first();
        try{
			if($request->quantity > 0){
				$productStock = new ProductStock();
				$productStock->invoice_date = $request->invoice_date;
				$productStock->invoice_number = $request->invoice_number;
				$productStock->purchase_order = $request->purchase_order;
				$productStock->notes = $request->notes;
				$productStock->quantity = 0 - $request->quantity;
				$productStock->product_id = $request->product_id;
				$productStock->variant_id = $request->variant_id;
				$productStock->created = $productStock->updated = Auth::user()->id;
				$productStock->created_at = $productStock->updated_at = date('Y-m-d H:i:s');
				$productStock->is_remove = true;
				$productStock->outlet_id = $request->outlet_id;
				$productStock->type = OrderType::Out;

				$where = [ 'product_id' => $request->product_id, 'variant_id' => $request->variant_id, 'outlet_id' => $request->outlet_id ];
				$data = $productStock->CalculateCpAndSp($where,$request->quantity);
				
				if($data['error']){
					return new Response(false, ['Invalid quantity provided']);
				}
				$productStock->cost_price = $data['costPrice'];
				$productStock->sale_price = $data['costPrice'];
				$productStock->notes = $data['notes'];

				if ($productStock->save()) {
					$flag = true;
				}

				if ($flag == true) {	
					$emailStore = new StockRemoved($productStock);
					Mail::to($store->email)->send($emailStore);

					$emailAdmin = new StockRemoved($productStock);
					Mail::to(env('MAIL_FROM_ADDRESS'))->send($emailAdmin);
				}

				return new Response(true, $request);
			}else{
				return new Response(false, ['Invalid quantity provided']);
			}
		}catch(Exception $ex){
			return new Response(false, $ex);
		}
    }
} 