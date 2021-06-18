<?php

namespace App\Modules\Catalogue\Product;
use App\Core\BaseRequest as BaseRequest;
use App\Core\Response;
use App\Models\Outlet as Outlet;
use App\Models\Product;
use Auth;
class GetEmptyOutletRequest extends BaseRequest{
   
    public $id;
    
}
class GetEmptyOutletRequestHandler {

    public function Serve($request){

        // $product     = Product::with('product_stock')->find($request->id);
        // $outlet_stock      = $product->product_stock->toArray();
            $outletname = Outlet::where('store_id',Auth::user()->store_id)->get();
        $outlet_stock_template=[];
        foreach ($outletname as $stock) 
            {
                array_push($outlet_stock_template,[
                    'id'                  => '',
                    'outlet_id'           =>$stock->id,
        			'name'                => $stock->name,
                    'quantity'            =>'',
                    're_order_point'      =>'',
                    're_order_quantity'   =>'',
                    'supply_price'        =>'',
                    'margin'              =>''
                ]);
        }

    	return new Response(true, $outlet_stock_template);
    }
} 