<?php

namespace App\Requests\Reports;

use App\Core\BaseRequest as BaseRequest;
use App\Core\Response;
use App\Models\Order;
use App\Models\WebOrder;
use App\Models\WebUser;
use App\Models\Address;
use App\Models\ShippingMethod;
use App\Models\PaymentMethod;
use App\Helpers\PriceHelper;
use Auth;

class SalesReportRequest extends BaseRequest
{
    public $start_date;
    public $end_date;
    public $daterange;
    public $date_filter;
    public $report_type;
    public $outlet_id;
    public $user;
    public $sales_status;
}

class SalesReportRequestHandler
{

    public function __construct()
    {
    }

    public function Serve($request){


        $date_range = $request->date_filter;
        $date_filter = $request->date_filter;

        $outlet_ids = [];

        $outlets = $request->user->outlets->toArray();
        $outlet_ids = array_column($outlets, 'id');

        if(isset($request->sales_status) && $request->sales_status == "cancelled"){
            $orderObj = WebOrder::with('outlet')->where('status', 'Cancelled');
        } else {
            $orderObj = Order::with('customer', 'user', 'outlet')->where(['status' => 'Complete']);
        }

        if(isset($request->outlet_id) && !empty($request->outlet_id)){
            $orderObj->where('outlet_id', $request->outlet_id);
        } else{
            $orderObj->whereIn('outlet_id', $outlet_ids);
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

            $orderObj->where('order_date','>=',$start_date);
            $orderObj->where('order_date','<=',$end_date);
        }

        $orders = $orderObj->get()->toArray();
//dd($orders);
        return new Response(true, $orders);
    }

}
