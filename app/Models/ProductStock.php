<?php

namespace App\Models;
use Illuminate\Database\Eloquent\Model;
use App\Helpers\OrderType as OrderType;


class ProductStock extends Model{
    protected $table = 'product_stock';

	public function product(){
		return $this->belongsTo('App\Models\Product','product_id');
	}

	public function variant(){
		return $this->belongsTo('App\Models\ProductVariant');
	}
	public function outlet(){
		return $this->belongsTo('App\Models\Outlet');
	}

	public function CalculateCpAndSp($where,$QtyForReturn){
		$variant = ProductVariant::where('id',$where['variant_id'])->with('product')->first();

		$query = $this->where($where);
		$QtyInStock = $query->sum('quantity');
		if($variant->allow_out_of_stock == 1){
			$totalstockIn = $query->where('quantity','>=',0)->orderBy('id','desc')->get();
		}else{
			$totalstockIn = $query->where('quantity','>',0)->orderBy('id','desc')->get();
		}
// 		if($totalstockIn == null){
// 			$totalstockIn = new $this;
// 			$totalstockIn->quantity = 0;
// 			$totalstockIn->cost_price = $variant->retail_price;
// 		}

// // dd($totalstockIn);
		$totalstockIn = $totalstockIn->toArray();

		$qtyForRet = $QtyForReturn;
		$data['error'] = false;
		$notes = null;

		if($QtyInStock < $QtyForReturn && $variant->allow_out_of_stock != 1){
			$data['error'] = true;
			return $data;
		}

		
		$costPrice = 0;
		$stockInAvailable = [];
		
		for($i=0; $i < count($totalstockIn); $i++){
			$QtyInStock = $QtyInStock - $totalstockIn[$i]['quantity'];
			if($QtyInStock < 0){
				$totalstockIn[$i]['quantity'] = $totalstockIn[$i]['quantity'] + $QtyInStock;
				array_unshift($stockInAvailable,$totalstockIn[$i]);
				break;
			}else{
				array_unshift($stockInAvailable,$totalstockIn[$i]);
			}
		}		
	
		foreach ($stockInAvailable as $stock) {
			$diff = $stock['quantity'] - $QtyForReturn; 
			if($diff >= 0 || $variant->allow_out_of_stock == 1){
				$costPrice = $costPrice + ($QtyForReturn * $stock['cost_price']);
				$notes = $notes . ' cp = ' . $QtyForReturn . ' @' . $stock['cost_price'];
				//$notes = $notes . ' sp = ' . $QtyForReturn . ' @' . $stock['sale_price'];
				break;						
			}
			else{
				$costPrice = $costPrice + ($stock['quantity'] * $stock['cost_price']);
				$notes = $notes . ' cp = ' . $stock['quantity'] . ' @' . $stock['cost_price'];
				//$notes = $notes . ' sp = ' . $stock['quantity'] . ' @' . $stock['sale_price'];
				$QtyForReturn = $QtyForReturn - $stock['quantity'];
			}					
		}
		$data['costPrice'] = $costPrice/$qtyForRet;
		$data['sellPrice'] = 0;
		$data['notes'] = $notes;
		return $data;
	}

}