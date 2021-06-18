<?php

namespace App\Requests\Reports;
use App\Core\BaseRequest as BaseRequest;
use App\Core\Response;
use App\Models\Product;
use App\Models\ProductVariant;
use App\Models\Store;
use Auth;


class GetInventoryReportRequest extends BaseRequest{
	public $outlet_id;
	public $daterange;
	public $date_filter;
	public $inventory_type;
	public $stock_range;
	public $price_range_filter;

}

class GetInventoryReportRequestHandler {

    public function __construct(){
    }

    public function Serve($request){

        $login_user = is_null(Auth::user())? \App::make('user') : Auth::user();

        $productsObj = Product::with('product_stock')->where('store_id' , $request->store_id)->where('is_item',0)->whereNull('deleted_at');

        // filter products by date
        if( (isset($request->date_filter) && $request->date_filter != null) || (isset($request->daterange) && $request->daterange != null)){

        	$date_range = $this->getDateRange($request);

            $dates = explode(' - ',$date_range);
            $start_date = $dates[0];
            $end_date = $dates[1];

            $productsObj->where('created_at','>=',$start_date);
            $productsObj->where('created_at','<=',$end_date);
        }

        // filter by outlet id
        if(isset($request->outlet_id) && !empty($request->outlet_id) && strlen($request->outlet_id) > 0){
            $productsObj = 	$productsObj->whereHas('product_stock', function($query) use($request) {
				            	return $query->where('outlet_id', $request->outlet_id);
				            });       
        }

   		// get products id's
        $product_ids = array_column($productsObj->select('id')->get()->toArray(), 'id');

        $variantsObj = ProductVariant::whereIn('product_id', $product_ids);

        // get out of stock products
        if(isset($request->inventory_type) && $request->inventory_type == "out_of_stock"){
            $variantsObj = $variantsObj->where('stock', '<=' , 0);	                
        }

        // get low stock products
        if(isset($request->inventory_type) && $request->inventory_type == "low_stock"){
            $min_stock = Store::select('stock_threshold')->where('id',$request->store_id)->first();
            $min_stock = $min_stock != null ? $min_stock : 10;
            $variantsObj = $variantsObj->where('stock', '<=' , (int)$min_stock->stock_threshold);	                
        }

        //filter by stock range
        if(isset($request->stock_range) && !empty($request->stock_range) && strlen($request->stock_range) > 0){

            // remove white space from price range
            $stock_range = str_replace(' ', '', $request->stock_range);

            // remove "-" sign from price range
            $stock = explode('-',$stock_range);

            if(isset($stock[1])){
                $variantsObj = $variantsObj->where('stock', '>=' , $stock[0])->where('stock', '<=' , $stock[1]);
            } else {
                //if user enter only 1 digit input without "-"
                $variantsObj = $variantsObj->where('stock', '<=', $stock[0]);
            }
        }

        // filter by price range
        if(isset($request->price_range_filter) && !empty($request->price_range_filter) && strlen($request->price_range_filter) > 0){
            // remove white space from price range
            $price_range = str_replace(' ', '', $request->price_range_filter);

            // remove "-" sign from price range
            $price = explode('-',$price_range);

            if(isset($price[1])){
                $variantsObj = $variantsObj->where('retail_price', '>=' , $price[0])->where('retail_price', '<=' , $price[1]);
            } else {
                //if user enter only 1 digit input without "-"
                $variantsObj = $variantsObj->where('retail_price', '<=', $price[0]);
            }
        }

        $total_stock_amount = $variantsObj->sum('retail_price');
        $total_stock = $variantsObj->sum('stock');
        $variants = $variantsObj->get()->toArray();

        $data = [
        	'inventories'	=> $variants,
        	'total_stock_amount'	=> $total_stock_amount,
        	'total_stock'	=> $total_stock
        ];

    	return new Response(true, $data);
    }   


    private function getDateRange($request){

    	$date_range = $request->date_filter;
        $date_filter = $request->date_filter;

    	if(isset($date_filter) && $date_filter == 'day') {
        
            $date_range = date('Y-m-d', strtotime('-1 days')).' - '.date('Y-m-d');

        } elseif ($date_filter == 'week') {

            $date_range = date('Y-m-d', strtotime('-7 days')).' - '.date('Y-m-d');

        } elseif ($date_filter == 'month') {
            
            $date_range = date('Y-m-d', strtotime('-30 days')).' - '.date('Y-m-d');
        }  else {
            $date_range = $request->daterange;
        }

        return $date_range;
    } 
} 