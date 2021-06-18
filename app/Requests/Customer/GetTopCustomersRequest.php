<?php

namespace App\Requests\Customer;

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
        
        $data = $request->all();
        $date_filter = $data['date_filter'];
        $filter_type = $data['parameter_type'];
        $result;

        if($date_filter == 'day' && $filter_type == 'buttons') {

            $date = '(curdate() - INTERVAL DAYOFWEEK(curdate())-2 DAY)';
            $result = $this->getKpiDataByButtonFilter($date);

        } elseif ($date_filter == 'week' && $filter_type == 'buttons') {

            $date = '(curdate() - INTERVAL DAYOFWEEK(CURDATE())+5 DAY)';
            $result = $this->getKpiDataByButtonFilter($date);

        } elseif ($date_filter == 'month' && $filter_type == 'buttons') {

            $date = '(curdate() - INTERVAL DAYOFWEEK(CURDATE())+28 DAY)';
            $result = $this->getKpiDataByButtonFilter($date);

        } elseif ($filter_type == 'daterange') {
            
            $dates = explode(' - ', $date_filter);
            $start_date = $dates[0];
            $end_date = $dates[1];
            $result = $this->getKpiDataByDateRangeFilter($start_date, $end_date);

        }

        if(sizeof($result) > 0){
            $result->transform(function($customer){
                $customer->Total_Spent = number_format($customer->Total_Spent,\Auth::user()->store->round_off);
                return $customer;
            });
        }

        return $result;
    }

    public function getKpiDataByButtonFilter($date) {

        // $date = '2019-01-01';
        $store_id = Auth::user()->store_id;

        $top_customers = DB::table('web_users')
        ->select(DB::raw('
            web_users.id,
            web_users.name,
            web_users.email,
            web_users.mobile,
            IFNULL(COUNT(web_orders.id),0) AS Total_Orders,
            IFNULL(SUM(web_orders.total),0) AS Total_Spent
            '))
        ->join('web_orders', 'web_orders.web_user_id', '=', 'web_users.id')
        ->whereRaw("
            web_orders.status = 'Complete'
            AND web_orders.order_date BETWEEN ". $date ." AND curdate()
            AND web_users.store_id = ". $store_id ."
            ")
        ->groupBy('web_users.id', 'web_users.name', 'web_users.email', 'web_users.mobile')
        ->orderBy('Total_Spent')
        ->get();

        return $top_customers;

    }

    public function getKpiDataByDateRangeFilter($start_date, $end_date) {

        $store_id = Auth::user()->store_id;

        $top_customers = DB::table('web_users')
        ->select(DB::raw('
            web_users.id,
            web_users.name,
            web_users.email,
            web_users.mobile,
            IFNULL(COUNT(web_orders.id),0) AS Total_Orders,
            IFNULL(SUM(web_orders.total),0) AS Total_Spent
            '))
        ->join('web_orders', 'web_orders.web_user_id', '=', 'web_users.id')
        ->whereRaw("
            web_orders.status = 'Complete'
            AND web_orders.order_date BETWEEN '". $start_date ."' AND '". $end_date ."'
            AND web_users.store_id = ". $store_id ."
            ")
        ->groupBy('web_users.id', 'web_users.name', 'web_users.email', 'web_users.mobile')
        ->orderBy('Total_Spent')
        ->get();

        return $top_customers;

    }
} 