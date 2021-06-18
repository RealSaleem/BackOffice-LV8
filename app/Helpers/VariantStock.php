<?php
namespace App\Helpers;
use App\Models\ProductVariant;
use DB;

class VariantStock {

	public static function updateStock($variant_id)
    {

    	$count = VariantStock::stock($variant_id);
    	
    	$productvariant 			= ProductVariant::find($variant_id);
    	$productvariant->stock 	= $count;
    	// dd($productvariant);
    	$productvariant->save();

		return $productvariant;	
	}
	public static function updateStockForReport($variant_id)
    {

    	$count = VariantStock::stock($variant_id);
    	
    	$productvariant 			= ProductVariant::find($variant_id);
    	$productvariant->stock 	= $count;
    	// dd($productvariant);
    	$productvariant->save();

		// return $productvariant;	
	}

    public static function count($outlet_id,$variant_id)
    {
    	$value =  DB::table('product_stock')
    				->where('variant_id',$variant_id)
					->where('outlet_id',$outlet_id)
					->sum('quantity');

		return VariantStock::format($value);	
	}
	
	public static function stock($variant_id)
    {
    	$value =  DB::table('product_stock')
    				->where('variant_id',$variant_id)
					->sum('quantity');
		return VariantStock::format($value);				
	}
	
	public static function format($value)
	{
		//to find either the number is decimale and deilmal value greater then zero
		if(fmod($value,1)){
			return round($value,4);
		}else{
			return number_format($value,0, '.', '');
		}
	}

	// public static function is_decimal( $val )
	// {
	//     return is_numeric( $val ) && floor( $val ) != $val;
	// }
}