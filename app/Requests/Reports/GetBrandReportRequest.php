<?php

namespace App\Requests\Reports;
use App\Core\BaseRequest as BaseRequest;
use App\Core\Response;
use App\Models\Brand;
use Auth;

class GetBrandReportRequest extends BaseRequest{
	public $outlet_id;
	public $daterange;
	public $date_filter;
    public $brand_name;
    public $number_of_product;
}

class GetBrandReportRequestHandler {

    public function Serve($request){

        $login_user = is_null(Auth::user())? \App::make('user') : Auth::user();

        $brandObj = Brand::where(['store_id' => $request->store_id, 'is_deleted' => 0]);

        // filter by supplier name
        if(isset($request->brand_name) && $request->brand_name != null){
            $brandObj = $brandObj->where('name', 'like', '%'.$request->brand_name.'%');
        }
        
        //filter by products
        if(isset($request->number_of_product) && $request->number_of_product > 0){
            $brandObj = $brandObj->has('products', $request->number_of_product);
        }

        // filter products by date
        if( (isset($request->date_filter) && $request->date_filter != null) || (isset($request->daterange) && $request->daterange != null)){

        	$date_range = $this->getDateRange($request);

            $dates = explode(' - ',$date_range);
            $start_date = $dates[0];
            $end_date = $dates[1];

            $brandObj->whereDate('created_at','>=',$start_date);
            $brandObj->whereDate('created_at','<=',$end_date);
        } 

        $brands = $brandObj->withCount('products')->get()->toArray();               

        return new Response(true, $brands);
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