<?php

namespace App\Requests\Reports;
use App\Core\BaseRequest as BaseRequest;
use App\Core\Response;
use App\Models\Order;
use App\Models\Outlet;
use App\Models\Register;
use App\Models\Category;
use App\Models\Brand;
use App\Models\Supplier;
use App\Models\Product;
use App\Models\ProductCategories;
use App\Models\ProductVariant;
use App\Helpers\VariantStock;

class GetProductReportRequest extends BaseRequest{
    public $store_id;

    public $daterange;
    public $date_filter;
    public $outlet_id;
    public $report_type;
    public $product_name;
    public $category;
    public $brand;
	public $supplier;

}

class GetProductReportRequestHandler {

    public function __construct(){
    }

    public function Serve($request){

        $category = Category::where('store_id',$request->store_id)->where('is_deleted',0)->select('id','name')->get()->toArray();
        $brand = Brand::where('store_id',$request->store_id)->where('is_deleted', 0)->select('id','name')->get()->toArray();
        $supplier = Supplier::where('store_id',$request->store_id)->where('is_deleted', 0)->select('id','name')->get()->toArray();
        
        $date_range = $request->date_filter;
        $date_filter = $request->date_filter;

        // if(isset($request->product_name) || isset($request->category) || isset($request->brand) || isset($request->supplier)){
            $query = Product::where('store_id' , $request->store_id)->where('is_item',0)->whereNull('deleted_at');

            if(!is_null($request->category) && $request->category > 0){
                $relashions = ProductCategories::where(['category_id' => $request->category])->get();
                $ids = array_column($relashions->toArray(),'product_id');
                $query->whereIn('id',$ids);         
            }

            
            if(!is_null($request->brand) && $request->brand > 0){
                $query->where('brand_id',$request->brand);
            }

            if(!is_null($request->supplier) && $request->supplier > 0){
                $query->where('supplier_id',$request->supplier);
            }

            if(!is_null($request->product_name)){
                $relashions = ProductVariant::where('store_id' , $request->store_id)->where('sku', 'like', $request->product_name.'%')->orWhere('name', 'like', '%'.$request->product_name.'%')->get();
                $ids = array_column($relashions->toArray(),'product_id');
                $query->whereIn('id',$ids);
                // dd($ids);
                
            }

            if(isset($date_filter) && $date_filter == 'day') {
            
                $date_range = date('Y-m-d', strtotime('-1 days')).' - '.date('Y-m-d');

            } elseif ($date_filter == 'week') {

                $date_range = date('Y-m-d', strtotime('-7 days')).' - '.date('Y-m-d');

            } elseif ($date_filter == 'month') {
                
                $date_range = date('Y-m-d', strtotime('-30 days')).' - '.date('Y-m-d');
            }  else {
                $date_range = $request->daterange;
            }
            if(isset($date_range)){
                
                $dates = explode(' - ',$date_range);
                $start_date = $dates[0];
                $end_date = $dates[1];

                $query->where('created_at','>=',$start_date);
                $query->where('created_at','<=',$end_date);
            }
            $product_ids = array_column($query->select('id')->get()->toArray(),'id');
            $variants_with_product_id = ProductVariant::select('id',"supplier_price","name","sku","attribute_value_1","attribute_value_2","attribute_value_3","retail_price","stock")->withCount('product_add_on')->whereIn('product_id',$product_ids)->get()->toArray();
            // $products = $query->select('id')->get()->toArray();
            // foreach ($variants_with_product_id as $var) {
            //     if($var['stock'] == null){
                    
            //     }
            // }
        
        // }
            // dd($variants_with_product_id);
        $data = [
            'category' => $category,
            'brand' => $brand,
            'supplier' => $supplier,
            'products' => $variants_with_product_id,
        ];

    	return new Response(true, $data);
    } 
} 