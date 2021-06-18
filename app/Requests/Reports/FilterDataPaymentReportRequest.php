<?php

namespace App\Requests\Reports;
use App\Core\BaseRequest as BaseRequest;
use App\Core\Response;
use App\Models\Order;
use App\Models\Outlet;
use App\Models\Register;
use App\Models\PaymentType;
use App\Models\UserOutlets;

use Auth;


class FilterDataPaymentReportRequest extends BaseRequest{
	public $store_id;
    public $end_date;
    public $start_date;
}

class FilterDataPaymentReportRequestHandler {

    public function __construct(){
    }

    public function Serve($request){
        $login_user = is_null(Auth::user())? \App::make('user') : Auth::user();
        // $outlet_id=Outlet::select('id')->where('store_id', $login_user->store_id)->get()->toArray();
        // $ids = array_map(function ($item) {return $item['id'];}, $outlet_id);

        // $register_id = Register::select('id')->whereIn('outlet_id', $ids)->get()->toArray();
        // $reg_ids     = array_map(function ($item) {return $item['id'];}, $register_id);

        // $in = '(' . implode(',', $reg_ids) . ')';
        $outlet_ids = [];
        $usr_outlets = UserOutlets::where('user_id',$login_user->id)->select('outlet_id')->get()->toArray();

        for ($i=0; $i < sizeof($usr_outlets); $i++) { 
            $outlet_ids[$i] = $usr_outlets[$i]['outlet_id'];
        }
            
        //$payment_method = PaymentType::where('store_id',Auth::user()->store_id)->get(); 
        $payment_method = [
                    (object)['name' => 'Cash'],
                    (object)['name' => 'Credit Card'],
                    (object)['name' => 'Voucher'],
                    ];  

        $data = [];
        foreach ($payment_method as $row) {

            $data1['method'] = $row->name;

            // $data1['data'] = Order::where('payment_method',$row->name)->whereIn('register_id',$reg_ids);
            $data1['data'] = Order::where('payment_method',$row->name)->whereIn('outlet_id',$outlet_ids);

            if(isset($request->start_date) && isset($request->end_date)){
                $data1['data']->where('order_date','>=',$request->start_date);
                $data1['data']->where('order_date','<=',$request->end_date);
            }

            $data1['data'] = $data1['data']->get();

            array_push($data, $data1);
    }


    return new Response(true, $data);
} 
}