<?php

namespace App\Http\Controllers;
use App\Http\Controllers;

use App\Core\RequestExecutor;
use App\Models\Customer;
use App\Models\Order;
use App\Models\OrderItem;
use App\Models\ProductVariant;
use App\Models\User;
use App\Models\UsersStore;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Hash;

use App\Requests\BackOffice\Dashboard\GetTopProductsRequest;
use App\Requests\BackOffice\Dashboard\GetKpisDataRequest;
use App\Requests\BackOffice\Dashboard\GetTopCustomersRequest;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;

class DashboardController extends Controller
{
    /**
     * Create a new controller instance.
     *
     * @return void
     */
    public function __construct(RequestExecutor $requestExecutor)
    {
        $this->RequestExecutor = $requestExecutor;
    }

    /**
     * Show the application dashboard.
     *
     * @return \Illuminate\Http\Response
     */
    public function index()
    {
        $Store_id = Auth::user()->store_id;

        $data = OrderItem::with('variant','order')
            ->whereHas('variant', function ($query) use ($Store_id) {return $query->where('store_id',$Store_id);})
            ->whereHas('order', function ($query1) use ($Store_id) {$query1->where('status','Complete');
            })->limit(5)->get();

        $StoreCustomer = Customer::with('order')->where('store_id',$Store_id)->limit(5)->get();

        return view('dashboard/index')->with(compact('data','StoreCustomer'));
    }

    public function getKpiData(GetKpisDataRequest $request){

        $data = $request->all();
        $date_filter = $data['date_filter'];
        $filter_type = $data['parameter_type'];
        // KPIs
        $response = $this->RequestExecutor->execute($request);
        $kpis = $response->Payload;
        // **** //

        // Orders Widget
        $orders_widget;
        $date_range = $date_filter;

        $order_status = 'Confirmed';

        $getTopProductsRequest = new GetTopProductsRequest();
        $getTopProductsRequest->order_status = $order_status;

        if($date_filter == 'day' && $filter_type == 'buttons') {

            $date_range = date('Y-m-d', strtotime('-1 days')).' - '.date('Y-m-d');

        } elseif ($date_filter == 'week' && $filter_type == 'buttons') {

            $date_range = date('Y-m-d', strtotime('-7 days')).' - '.date('Y-m-d');

        } elseif ($date_filter == 'month' && $filter_type == 'buttons') {

            $date_range = date('Y-m-d', strtotime('-30 days')).' - '.date('Y-m-d');

        } elseif ($date_filter == '1year' && $filter_type == 'buttons') {
            
            $date_range = date('Y-m-d', strtotime('-365 days')).' - '.date('Y-m-d');
        }  
        
        $getTopProductsRequest->daterange = $date_range;
        
        $response = $this->RequestExecutor->execute($getTopProductsRequest);
        // dd($response);

        // **** //

        $customers_widget = [];
        if($date_filter !== 'all' && $filter_type !== 'all') {
            // Customers Widget
            $customers = new GetTopCustomersRequest();

            $customers_widget = $this->RequestExecutor->execute($customers);

            $customers_widget = sizeof($customers_widget->Payload) > 0 ? $customers_widget->Payload : [];

            $response->Payload->extra['kpis'] = $kpis;
            $response->Payload->extra['customers_widget'] = $customers_widget;

        } else {

            $response->Payload->extra['kpis'] = $kpis;
            $response->Payload->extra['customers_widget'] = $customers_widget;
        }

        return response()->json($response->Payload);

    }

    public function getOrders(Request $request){


        $getOrdersRequest = new GetTopProductsRequest();
        $response = $this->RequestExecutor->execute($getOrdersRequest);

        // KPIs Data Here
        $store_id = Auth::user()->store_id;
        $condition = "";


        // if($request->outlet_id){
        //     $outlet_id = $request->outlet_id;
        //     $condition = "AND outlet_id = ". $outlet_id;
        // }else{
        //     if(Auth::user()->owner == 1){
        //         $outlet_id = Auth::user()->store->outlets[0]->id;
        //     }else{
        //         $outlet_id = Auth::user()->usr_outlets[0]->id;
        //     }
        //     $condition = "AND outlet_id = ". $outlet_id;
        // }

        $number_of_orders_without_failed = DB::table('orders')
        ->select(DB::raw('IFNULL(COUNT(*),0) AS number_of_orders_without_failed'))
        ->whereRaw("
            STATUS <> 'Voided'
            AND orders.store_id = ". $store_id ."
            ".$condition."
            ")
        // ->join('web_payment_methods', 'orders.web_payment_id', '=', 'web_payment_methods.id')
        ->first();

        $number_of_orders_complete = DB::table('orders')
        ->select(DB::raw('IFNULL(COUNT(*),0) AS number_of_orders_complete'))
        ->whereRaw("
            STATUS = 'Complete'
            AND orders.store_id = ". $store_id ."

            ".$condition."
            ")

        // ->join('web_payment_methods', 'orders.web_payment_id', '=', 'web_payment_methods.id')
        ->first();

        $number_of_orders_confirmed = DB::table('orders')
        ->select(DB::raw('IFNULL(COUNT(*),0) AS number_of_orders_confirmed'))
        ->whereRaw("
            STATUS = 'Complete'
            AND orders.store_id = ". $store_id ."

            ".$condition."
            ")

        // ->join('web_payment_methods', 'orders.web_payment_id', '=', 'web_payment_methods.id')
        ->first();


        $kpis = [
            $number_of_orders_without_failed,
            $number_of_orders_complete,
            $number_of_orders_confirmed,
        ];
        // *** End *** //

        $response->Payload->extra['kpis'] = $kpis;
        return response()->json($response->Payload);
    }

}
