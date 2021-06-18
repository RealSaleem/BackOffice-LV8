<?php

namespace App\Requests\Reports;
use App\Core\BaseRequest as BaseRequest;
use App\Core\Response;
use App\Models\Category;
use Auth;


class GetCategoryReportRequest extends BaseRequest{
	public $outlet_id;
	public $daterange;
	public $date_filter;
	public $category_name;
    public $number_of_product;
    public $categories_available_on;
}

class GetCategoryReportRequestHandler {

    public function Serve($request){

        $login_user = is_null(Auth::user())? \App::make('user') : Auth::user();

        $categoryObj = Category::with('parent')->where(['store_id' => $request->store_id, 'is_deleted' => 0]);

        // filter by register name
        if(isset($request->category_name) && $request->category_name != null){
            $categoryObj = $categoryObj->where('name', 'like', '%'.$request->category_name.'%');
        }

        //filter by products
        if(isset($request->number_of_product) && $request->number_of_product > 0){
            $categoryObj = $categoryObj->has('products', $request->number_of_product);
        }

        //filter by web_display, dinein_display, pos_display etc
        // Pass same database column name from input field
        if(isset($request->categories_available_on) && $request->categories_available_on != null){
            $categoryObj = $categoryObj->where($request->categories_available_on, 1);
        }

        // filter products by date
        if( (isset($request->date_filter) && $request->date_filter != null) || (isset($request->daterange) && $request->daterange != null)){

        	$date_range = $this->getDateRange($request);

            $dates = explode(' - ',$date_range);
            $start_date = $dates[0];
            $end_date = $dates[1];

            $categoryObj->whereDate('created_at','>=',$start_date);
            $categoryObj->whereDate('created_at','<=',$end_date);
        } 

        $category = $categoryObj->withCount('products')->get()->toArray();               

        return new Response(true, $category);
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