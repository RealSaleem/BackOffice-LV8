<?php

namespace App\Modules\Product;
use App\Core\BaseRequest as BaseRequest;
use App\Core\Response;
use App\Models\ProductStock;



class GetStockRequest extends BaseRequest{
    public $id;
}


class GetStockRequestHandler {

    public function Serve($request){
    	
    	$stock = ProductStock::where('product_id',$request->id)->orderBy('id','desc')->first();
		return new Response(true, $stock);
	}
} 