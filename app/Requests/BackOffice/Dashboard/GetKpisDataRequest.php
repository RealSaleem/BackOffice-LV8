<?php

namespace App\Requests\BackOffice\Dashboard;
use App\Core\BaseRequest as BaseRequest;
use App\Models\WebOrder;
use App\Models\WebStore;
use Auth;
use DB;
use App\Core\Response;
use App\Models\CreateTransaction;
use Session;

class GetKpisDataRequest extends BaseRequest{

}

class GetKpisDataRequestHandler 
{
    public function Serve($request)
    { 

        $store = Auth::user()->store;
        $user = Auth::user();

        $data = $request->all();
        $date_filter = $data['date_filter'];
        $filter_type = $data['parameter_type'];
        $result;

        $codition = "";

        if(Session::has("order_outlet_id")){
            $outlet_id = Session::get("order_outlet_id");
            $codition = "AND outlet_id = ". $outlet_id;

        }else{
            if($user->owner == 1){
                $outlet_id = $store->outlets[0]->id;
            }else{
                $outlet_id = $user->usr_outlets[0]->id;
            }
            $codition = "AND orders.outlet_id = ". $outlet_id;
        }
// dd($filter_type,$date_filter);

        if($date_filter == 'day' && $filter_type == 'buttons') {

            $date = '(curdate() - INTERVAL DAYOFWEEK(curdate())-2 DAY)';
            $result = $this->getKpiDataByButtonFilter($date,$codition,$store,$outlet_id);

        } elseif ($date_filter == 'week' && $filter_type == 'buttons') {

            $date = '(curdate() - INTERVAL DAYOFWEEK(CURDATE())+5 DAY)';
            $result = $this->getKpiDataByButtonFilter($date,$codition,$store,$outlet_id);

        } elseif ($date_filter == 'month' && $filter_type == 'buttons') {

            $date = '(curdate() - INTERVAL DAYOFWEEK(CURDATE())+25 DAY)';
            $result = $this->getKpiDataByButtonFilter($date,$codition,$store,$outlet_id);

        } elseif ($filter_type == 'daterange') {
            
            $dates = explode(' - ', $date_filter);
            $start_date = $dates[0];
            $end_date = $dates[1];
            $result = $this->getKpiDataByDateRangeFilter($start_date, $end_date,$codition,$store,$outlet_id);
        }

        if(!is_null($result)){
            return $result;
        }else{
            return new Response(false);
        }
    }

    public function getKpiDataByButtonFilter($date,$codition,$store,$outlet_id) {

        $store_id = $store->id;

        $total_sales = DB::table('orders')
        ->select(DB::raw('IFNULL(SUM(total),0) AS total_sales'))
        ->whereRaw("
            STATUS = 'Complete'
            AND order_date BETWEEN ". $date ." AND curdate()
            AND orders.outlet_id = ". $outlet_id ."
            ".$codition."
            ")
        // ->join('web_payment_methods', 'orders.web_payment_id', '=', 'web_payment_methods.id')
        ->first();

        $number_of_orders_without_failed = DB::table('orders')
        ->select(DB::raw('IFNULL(COUNT(*),0) AS number_of_orders_without_failed'))
        ->whereRaw("
            STATUS <> 'Failed'
            AND order_date BETWEEN ". $date ." AND curdate()
            AND orders.outlet_id = ". $outlet_id ."
            ".$codition."
            ")
        // ->join('web_payment_methods', 'orders.web_payment_id', '=', 'web_payment_methods.id')
        ->first();

        $number_of_orders_returned = DB::table('orders')
        ->select(DB::raw('IFNULL(COUNT(*),0) AS number_of_orders_returned'))
        ->whereRaw("
            STATUS = 'Return Approved'
            AND order_date BETWEEN ". $date ." AND curdate()
            AND orders.outlet_id = ". $outlet_id ."
            ".$codition."
            ")
        // ->join('web_payment_methods', 'orders.web_payment_id', '=', 'web_payment_methods.id')
        ->first();

        $number_of_orders_complete = DB::table('orders')
        ->select(DB::raw('IFNULL(COUNT(*),0) AS number_of_orders_complete'))
        ->whereRaw("
            STATUS = 'Complete'
            AND order_date BETWEEN ". $date ." AND curdate()
            AND orders.outlet_id = ". $outlet_id ."
            ".$codition."
            ")
        // ->join('web_payment_methods', 'orders.web_payment_id', '=', 'web_payment_methods.id')
        ->first();

        $number_of_orders_confirmed = DB::table('orders')
        ->select(DB::raw('IFNULL(COUNT(*),0) AS number_of_orders_confirmed'))
        ->whereRaw("
            STATUS = 'Confirmed'
            AND order_date BETWEEN ". $date ." AND curdate()
            AND orders.outlet_id = ". $outlet_id ."
            ".$codition."
            ")
        // ->join('web_payment_methods', 'orders.web_payment_id', '=', 'web_payment_methods.id')
        ->first();

        $response = array(
            number_format($total_sales->total_sales,$store->round_off).' '.$store->default_currency,
            $number_of_orders_without_failed->number_of_orders_without_failed,
            $number_of_orders_returned->number_of_orders_returned,
            $number_of_orders_complete->number_of_orders_complete,
            $number_of_orders_confirmed->number_of_orders_confirmed,
            0,
            0,
        );

        return $response;

    }

    public function getKpiDataByDateRangeFilter($start_date, $end_date,$codition,$store,$outlet_id) {

        $store_id = $store->id;

        $total_sales = DB::table('orders')
        ->select(DB::raw('IFNULL(SUM(total),0) AS total_sales'))
        ->whereRaw("
            STATUS = 'Complete'
            AND order_date BETWEEN '". $start_date ."' AND '". $end_date ."'
            AND orders.outlet_id = ". $outlet_id ."
            ".$codition."
            ")
        // ->join('web_payment_methods', 'orders.web_payment_id', '=', 'web_payment_methods.id')
        ->first();

        $number_of_orders_without_failed = DB::table('orders')
        ->select(DB::raw('IFNULL(COUNT(*),0) AS number_of_orders_without_failed'))
        ->whereRaw("
            STATUS <> 'Failed'
            AND order_date BETWEEN '". $start_date ."' AND '". $end_date ."'
            AND orders.outlet_id = ". $outlet_id ."
            ".$codition."
            ")
        // ->join('web_payment_methods', 'orders.web_payment_id', '=', 'web_payment_methods.id')
        ->first();

        $number_of_orders_returned = DB::table('orders')
        ->select(DB::raw('IFNULL(COUNT(*),0) AS number_of_orders_returned'))
        ->whereRaw("
            STATUS = 'Return Approved'
            AND order_date BETWEEN '". $start_date ."' AND '". $end_date ."'
            AND orders.outlet_id = ". $outlet_id ."
            ".$codition."
            ")
        // ->join('web_payment_methods', 'orders.web_payment_id', '=', 'web_payment_methods.id')
        ->first();

        $number_of_orders_complete = DB::table('orders')
        ->select(DB::raw('IFNULL(COUNT(*),0) AS number_of_orders_complete'))
        ->whereRaw("
            STATUS = 'Complete'
            AND order_date BETWEEN '". $start_date ."' AND '". $end_date ."'
            AND orders.outlet_id = ". $outlet_id ."
            ".$codition."
            ")
        // ->join('web_payment_methods', 'orders.web_payment_id', '=', 'web_payment_methods.id')
        ->first();

        $number_of_orders_confirmed = DB::table('orders')
        ->select(DB::raw('IFNULL(COUNT(*),0) AS number_of_orders_confirmed'))
        ->whereRaw("
            STATUS = 'Confirmed'
            AND order_date BETWEEN '". $start_date ."' AND '". $end_date ."'
            AND orders.outlet_id = ". $outlet_id ."
            ".$codition."
            ")
        // ->join('web_payment_methods', 'orders.web_payment_id', '=', 'web_payment_methods.id')
        ->first();

        $response = array(
            number_format($total_sales->total_sales,$store->round_off).' '.$store->default_currency,
            $number_of_orders_without_failed->number_of_orders_without_failed,
            $number_of_orders_returned->number_of_orders_returned,
            $number_of_orders_complete->number_of_orders_complete,
            $number_of_orders_confirmed->number_of_orders_confirmed,
            0,
            0,
        );        

        return $response;

    }
} 

	