<?php

namespace App\Requests\BackOffice\Dashboard;

use App\Core\BaseRequest as BaseRequest;
use App\Models\WebOrder;
use App\Models\WebStore;
use App\Core\DataTableResponse;
use Illuminate\Support\Facades\Auth;
use App\Core\Response;
use App\Models\CreateTransaction;
use App\Models\OrderItem;

class GetTopProductsRequest extends BaseRequest{

}

class GetTopProductsRequestHandler 
{
    public function Serve($request)
    {
        $columns = [
            'name',
            'stock',
            'quantity',
        ];
        
        $params = $request->all();
        
        if(isset($params['daterange']) || isset($request->daterange)){

            if(isset($request->daterange)){
                $dates = explode(' - ',$request->daterange);
            }else{
                $dates = explode(' - ',$params['daterange']);
            }

        }


        $store = Auth::user()->store;
        $store_id = $store->id;

    // dd($data);
        $limit = $params['length'];
        $start = $params['start'];

        $order = $columns[$params['order'][0]['column']];
        $dir = $params['order'][0]['dir'];


        $ordersObj = OrderItem::with('variant','order')->limit(5)
        ->whereHas('variant', function ($query) use ($store_id,$order ,$dir) {
            return $query->where('store_id',$store_id)->orderBy( $order ,$dir );
        });
        $ordersObj = $ordersObj->whereHas('order', function ($query1) use ($dates) {
            $query1->where('status','Complete')->whereBetween('order_date', $dates);
        });
        

        // dd($ordersObj->toSql());

        $totalData = $ordersObj->count();

        if($limit > -1){
            $ordersObj->offset($start)->limit($limit);
        }

        $orders = $ordersObj->orderBy('id', 'desc')->get();

        $orders->transform(function($order) use($store){

// dd($order->variant->first());

            $data = [
                'id' => $order->variant->first()->product_id,
                'name' => $order->variant->first()->name,
                'price' => number_format($order->variant->first()->retail_price,$store->round_off).' '.$store->default_currency,
                'stock' => $order->variant->first()->product_stock->sum('quantity'),
                'quantity' => $order->quantity,
                'amount' => number_format($order->order->total,$store->round_off).' '.$store->default_currency,
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