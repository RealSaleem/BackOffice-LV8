<?php

namespace App\Requests\BackOffice\Dashboard;

use App\Core\BaseRequest as BaseRequest;
use App\Core\Response;
use App\Models\WebUser;
use Auth;
use DB;

class GetTopCustomersRequest extends BaseRequest{
	public $store_id;
}
    
class GetTopCustomersRequestHandler {

    public function Serve($request){
        $store = Auth::user()->store;

        $data = $request->all();
        $date_filter = $data['date_filter'];
        $filter_type = $data['parameter_type'];
        $result;

        if($date_filter == 'day' && $filter_type == 'buttons') {

            $date = '(curdate() - INTERVAL DAYOFWEEK(curdate())-2 DAY)';
            $result = $this->getKpiDataByButtonFilter($date, $store->id);

        } elseif ($date_filter == 'week' && $filter_type == 'buttons') {

            $date = '(curdate() - INTERVAL DAYOFWEEK(CURDATE())+5 DAY)';
            $result = $this->getKpiDataByButtonFilter($date, $store->id);

        } elseif ($date_filter == 'month' && $filter_type == 'buttons') {

            $date = '(curdate() - INTERVAL DAYOFWEEK(CURDATE())+28 DAY)';
            $result = $this->getKpiDataByButtonFilter($date, $store->id);

        }elseif ($date_filter == '1year' && $filter_type == 'buttons') {

            $date = '(curdate() - INTERVAL DAYOFWEEK(CURDATE())+363 DAY)';
            $result = $this->getKpiDataByButtonFilter($date, $store->id);

        } elseif ($filter_type == 'daterange') {
            
            $dates = explode(' - ', $date_filter);
            $start_date = $dates[0];
            $end_date = $dates[1];
            $result = $this->getKpiDataByDateRangeFilter($start_date, $end_date, $store->id);

        }

        if(sizeof($result) > 0){
            $result->transform(function($customer) use($store){
                $customer->Total_Spent = number_format($customer->Total_Spent,$store->round_off).' '.$store->default_currency;
                return $customer;
            });
        }

        return $result;
    }

    public function getKpiDataByButtonFilter($date, $store_id) {

        $top_customers = DB::table('customers')
        ->select(DB::raw('
            customers.id,
            customers.name,
            customers.email,
            customers.mobile,
            IFNULL(COUNT(orders.id),0) AS Total_Orders,
            IFNULL(SUM(orders.total),0) AS Total_Spent
            '))
        ->join('orders', 'orders.customer_id', '=', 'customers.id')
        ->whereRaw("
            orders.status = 'Complete'
            AND orders.order_date BETWEEN ". $date ." AND curdate()
            AND customers.store_id = ". $store_id ."
            ")
        ->groupBy('customers.id', 'customers.name', 'customers.email', 'customers.mobile')
        ->orderBy('Total_Spent')
        ->get();

        return $top_customers;

    }

    public function getKpiDataByDateRangeFilter($start_date, $end_date, $store_id) {

        $top_customers = DB::table('customers')
        ->select(DB::raw('
            customers.id,
            customers.name,
            customers.email,
            customers.mobile,
            IFNULL(COUNT(orders.id),0) AS Total_Orders,
            IFNULL(SUM(orders.total),0) AS Total_Spent
            '))
        ->join('orders', 'orders.customer_id', '=', 'customers.id')
        ->whereRaw("
            orders.status = 'Complete'
            AND orders.order_date BETWEEN '". $start_date ."' AND '". $end_date ."'
            AND customers.store_id = ". $store_id ."
            ")
        ->groupBy('customers.id', 'customers.name', 'customers.email', 'customers.mobile')
        ->orderBy('Total_Spent')
        ->get();

        return $top_customers;
    }
} 