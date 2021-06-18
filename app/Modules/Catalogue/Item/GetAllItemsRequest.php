<?php

namespace App\Modules\Catalogue\Item;
use App\Core\BaseRequest as BaseRequest;
use App\Core\Response;
use App\Models\Product;
use Auth;

class GetAllItemsRequest extends BaseRequest{
}

class GetAllItemsRequestHandler {

    public function __construct(){
    }

    public function Serve($request){
        $items = Product::with(['transalation','product_variants'])->where([
        	'store_id' => Auth::user()->store_id,
        	'is_item' => 1
        ])->get()->toArray();

        $products = [];

        $round_off = \Auth::user()->store->round_off;

		if(is_null($round_off)){
			$round_off = 2;
		}

        foreach($items as $item){
        	$row = [
        		'price' => sprintf('%s',number_format($item['product_variants'][0]['retail_price'],$round_off, '.', '')),
        	];

        	foreach($item['transalation'] as $trans){
        		$key = sprintf('%s-name', $trans['language_key']);
        		$row[$key] = isset($trans['title']) ? $trans['title'] : '';
        	}

        	array_push($products,$row);
        }

        return new Response(true, $products);
    }
} 