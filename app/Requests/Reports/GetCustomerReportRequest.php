<?php

namespace App\Requests\Reports;
use App\Core\BaseRequest as BaseRequest;
use App\Core\Response;
use App\Models\Customer;
use App\Models\CustomerGroup;
use Auth;

class GetCustomerReportRequest extends BaseRequest{
	public $store_id;
	public $daterange;
	public $date_filter;
    public $customer_name;
    public $customer_mobile;
	public $customer_group;
}

class GetCustomerReportRequestHandler {

    public function Serve($request){

        $login_user = is_null(Auth::user())? \App::make('user') : Auth::user();

        $customerObj = Customer::with(['customer_group', 'order' => function($query){
            $query->where('status', 'Complete');
        }])->where('store_id', $request->store_id);

        // filter by name
        if(isset($request->customer_name) && $request->customer_name != null){
            $customerObj = $customerObj->where('name', 'like', '%'.$request->customer_name.'%');
        }

        // filter by mobile
        if(isset($request->customer_mobile) && $request->customer_mobile != null){
            $customerObj = $customerObj->where('mobile', 'like', '%'.$request->customer_mobile.'%');
        }

        // filter by customer group
        if(isset($request->customer_group) && $request->customer_group != null){
            $customerObj = $customerObj->where('customer_group_id', $request->customer_group);
        }

        // filter products by date
        if( (isset($request->date_filter) && $request->date_filter != null) || (isset($request->daterange) && $request->daterange != null)){

        	$date_range = $this->getDateRange($request);

            $dates = explode(' - ',$date_range);
            $start_date = $dates[0];
            $end_date = $dates[1];

            $customerObj->whereDate('created_at','>=',$start_date);
            $customerObj->whereDate('created_at','<=',$end_date);
        }

        $customers = $customerObj->withCount('order')->get();  
        $customer_groups = CustomerGroup::where('store_id', $request->store_id)->select('id','name')->get();
// dd($customers);
        $data = [
            'customers' => $customers,
            'groups'    => $customer_groups
        ];

        return new Response(true, $data);
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