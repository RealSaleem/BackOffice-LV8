<?php

namespace App\Requests\Reports;
use App\Core\BaseRequest as BaseRequest;
use App\Core\Response;
use App\Models\CustomerGroup;
use Auth;

class GetCustomerGroupReportRequest extends BaseRequest{
	public $outlet_id;
	public $daterange;
	public $date_filter;
    public $customer_group_name;
    public $number_of_customer;
}

class GetCustomerGroupReportRequestHandler {

    public function Serve($request){

        $login_user = is_null(Auth::user())? \App::make('user') : Auth::user();

        $customerGroupObj = CustomerGroup::where(['store_id' => $request->store_id]);

        // filter by customer name
        if(isset($request->customer_group_name) && $request->customer_group_name != null){
            $customerGroupObj = $customerGroupObj->where('name', 'like', '%'.$request->customer_group_name.'%');
        }
        
        //filter by customer count
        if(isset($request->number_of_customer) && $request->number_of_customer > 0){
            $customerGroupObj = $customerGroupObj->has('customer', $request->number_of_customer);
        }

        // filter customer by created date
        if( (isset($request->date_filter) && $request->date_filter != null) || (isset($request->daterange) && $request->daterange != null)){

        	$date_range = $this->getDateRange($request);

            $dates = explode(' - ',$date_range);
            $start_date = $dates[0];
            $end_date = $dates[1];

            $customerGroupObj->whereDate('created_at','>=',$start_date);
            $customerGroupObj->whereDate('created_at','<=',$end_date);
        } 

        $customer_groups = $customerGroupObj->select('name','created_at')->withCount('customer')->get()->toArray();               

        return new Response(true, $customer_groups);
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