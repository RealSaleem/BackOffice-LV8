<?php

namespace App\Modules\Catalogue\Product;
use App\Core\BaseRequest as BaseRequest;
use App\Core\Response;
use App\Models\Outlet as Outlet;
use App\Models\Product;
use Auth;
class GetEmptyComboRequest extends BaseRequest{
   
    public $id;
    
}
class GetEmptyComboRequestHandler {

    public function Serve($request){

        $combo_stock     = Product::with([
            // 'product_categories',
            // 'product_suppliers',
            // 'product_ralated',
            // 'product_images',
            'product_stock',
            'product_verients',
            'composite_products',
            // 'transalation'
        ])->find(319);

        $combo_stock_template=[];
    foreach ($combo_stock->product_stock as $stock) 
        {
            $outlet = Outlet::find($stock->outlet_id);

            // dd($stock);
            $outlet_stock_template=[
                'Outlet'                    =>$outlet->name,
                'name'                      => $combo_stock->name,
                'quantity'                  =>$stock->quantity,
                'price'                     =>$stock->cost_price
            ];

       //      array_push($outlet_stock_template,[
       //          'Outlet'                    =>$outlet->name,
    			// 'name'                      => $combo_stock->name,
       //          'quantity'                  =>$stock->quantity,
       //          'price'                     =>$stock->cost_price
       //      ]);
    }
    dd($outlet_stock_template);

    	return new Response(true, $outlet_stock_template);
    }
} 