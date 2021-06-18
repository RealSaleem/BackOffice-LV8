<?php

namespace App\Requests\Reports;
use App\Core\BaseRequest as BaseRequest;
use App\Core\Response;
use App\Models\PaymentMethod;
use App\Models\Transaction;
use App\Models\Order;
use App\Models\WebOrder;

class GetPaymentReportRequest extends BaseRequest{
    public $store_id;

    public $daterange;
    public $date_filter;
    public $outlet_id;
    public $report_type;
	public $payment_method;
    public $payment_status;

}

class GetPaymentReportRequestHandler {

    public function __construct(){
    }

    public function Serve($request){
        $payment_method = PaymentMethod::where('store_id',$request->store_id)->select('id','name','slug')->get()->toArray();
        $web_order_ids = $this->getWebOrderIds($request);

        $transactions = Transaction::with('web_order')->whereIn('weborder_id', $web_order_ids);

        // filter by payment method
        if(isset($request->payment_method)){
            $transactions = $transactions->where('payment_method_id', $request->payment_method);
        }

        // filter by payment status
        if(isset($request->payment_status)){
            $transactions = $transactions->where('payment_status', $request->payment_status);
        }

        $transac = $transactions->get()->toArray();

        $data = [
            'payment_method'    => $payment_method,
            'transactions'       => $transac
        ];

    	return new Response(true, $data);
    }    

    private function getWebOrderIds($request){

        $date_range = $request->date_filter;
        $date_filter = $request->date_filter;

        $outlet_ids = [];

        $outlets = $request->user->outlets->toArray();
        $outlet_ids = array_column($outlets, 'id');

        $orderObj = WebOrder::with('outlet');
      
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

        $web_order_ids = array_column($orderObj->select('id')->get()->toArray(),'id');

        return $web_order_ids;
    } 
} 