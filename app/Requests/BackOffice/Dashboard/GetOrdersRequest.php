<?php

namespace App\Requests\BackOffice\Dashboard;

use App\Core\BaseRequest as BaseRequest;
use App\Models\WebOrder;
use App\Models\WebStore;
use App\Core\DataTableResponse;
use Illuminate\Support\Facades\Auth;
use App\Core\Response;
use App\Models\CreateTransaction;

class GetOrdersRequest extends BaseRequest{

}

class GetOrdersRequestHandler
{
    public function Serve($request)
    {
        $columns = [
            'order_number',
            'date',
            'amount',
            'customer',
            'shipping_address',
            'payment_status',
            'payment_method',
            'status',
            'outlet_id',
            'action'
        ];

        $params = $request->all();

        $limit = $params['length'];
        $start = $params['start'];

        $order = $columns[$params['order'][0]['column']];
        $dir = $params['order'][0]['dir'];

        $where = [];

        $where = $this->addCondition( $where, $params, 'order_number', 'order_number');
        $where = $this->addCondition( $where, $params, 'customer', 'web_user_id');

        $where = $this->addCondition( $where, $params, 'payment_method', 'web_payment_id');
        $where = $this->addCondition( $where, $params, 'shipping_method', 'web_shipping_id');
        $where = $this->addCondition( $where, $params, 'order_status', 'status');
        $where = $this->addCondition( $where, $params, 'outlet_id', 'outlet_id');

        $ordersObj = WebOrder::whereHas('payment_method')->where($where);
        // $ordersObj->whereNotIn('status', ['Failed']);
        $ordersObj->where('status', '<>', 'Failed');
        $ordersObj->where(['store_id' => Auth::user()->store_id]);

        if(isset($params['daterange']) || isset($request->daterange)){

            if(isset($request->daterange)){
                $dates = explode(' - ',$request->daterange);
            }else{
                $dates = explode(' - ',$params['daterange']);
            }

            $ordersObj->whereBetween('order_date', $dates);
        }

        if(isset($params['orderamountrange'])){
            $amounts = explode('-',$params['orderamountrange']);
            $ordersObj->whereBetween('total', $amounts);
        }

        if(isset($params['address'])){
            $ordersObj->whereHas('address',function($query) use ($params){
                $search = $params['address'];
                return $query->where('country','LIKE',"%{$search}%")
                            ->orWhere('city','LIKE',"%{$search}%")
                            ->orWhere('county','LIKE',"%{$search}%")
                            ->orWhere('block','LIKE',"%{$search}%")
                            ->orWhere('street','LIKE',"%{$search}%")
                            ->orWhere('house_detail','LIKE',"%{$search}%")
                            ->orWhere('office_detail','LIKE',"%{$search}%")
                            ->orWhere('floor','LIKE',"%{$search}%");
            });
        }

        // dd($ordersObj->toSql());

        $totalData = $ordersObj->count();

        if( $order == 'date' ) {
            $order = 'order_date';
         }
         if($order == 'amount') {
             $order = 'total';
         }
         if($order == 'customer') {
             $order = 'user_firstName';
         }
         if($order == 'shipping_address') {
             $order = 'address_info';
         }
         if($order == 'status') {
             $order = 'status';
         }
         if($order == 'outlet_id') {
             $order = 'outlet_id';
         }

        if($limit > -1){
            $ordersObj->offset($start)->limit($limit);
        }

        $orders = $ordersObj->orderBy( $order ,$dir )->orderBy('id', 'desc')->get();

        $orders->transform(function($order){
            $payment_method = isset($order->payment_method->name) ? $order->payment_method->name : '';
            $payment_status = isset($order->transaction) && $order->transaction->count() > 0 && $order->transaction->first()->is_completed ? \Lang::get('site.paid') : \Lang::get('site.unpaid');

            if($order->payment_method->slug == 'cashondelivery'){
                $payment_status = $order->status == 'Complete' ? \Lang::get('site.paid') : \Lang::get('site.unpaid');
            }

            if($order->is_pickup == 1){
                $address = '<b>'.\Lang::get('order.pickup_address').':</b><br>'. $order->order_address.'<br>'.'<b>'.\Lang::get('order.Contact_Info').':</b><br>'. \Lang::get('site.mobile').' :'.$order->user_mobile.'<br>'.\Lang::get('order.customer_name').': '.$order->user_firstName.' '.$order->user_lastName;
            }else{
               $address =  '<b>'.\Lang::get('order.Shipping_address').':</b><br>'. $order->address->get().'<br>'.'<b>'.\Lang::get('order.Contact_Info').':</b><br>'. \Lang::get('site.mobile').' :'.$order->user_mobile.'<br>'.\Lang::get('order.customer_name').': '.$order->user_firstName.' '.$order->user_lastName;
            }

            $data = [
                'id' => $order->order_number,
                'order_date' => $order->order_date,
                'total' => number_format($order->total,\Auth::user()->store->round_off),
                'user_firstName' => $order->user_firstName .' '.$order->user_lastName ,
                'address_info' => $address,
                'payment_status' => $payment_status,
                'payment_method' => $payment_method,
                'status' => $order->status,
                'outlet_id' => $order->outlet_id,
                'action' => $order->status,
                'row_id' => $order->id
            ];


            return (object)$data;
        });

        return new DataTableResponse($orders,$totalData);
    }

    private function addCondition( $where, $params, $request_column, $db_column ){
        if( isset( $params[$request_column] ) && strlen( $params[$request_column] ) > 0){
            $where[$db_column] = $params[$request_column];
        }

        return $where;
    }

}
