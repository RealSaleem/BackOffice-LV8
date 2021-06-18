<?php

namespace App\Requests\Customer;

use App\Core\BaseRequest as BaseRequest;
use App\Core\DataTableResponse;
use App\Models\Customer;

class GetAllCustomerRequest extends BaseRequest{
    public $store_id;
}

class GetAllCustomerRequestHandler {

    public function __construct(){
    }

    public function Serve($request){

        $columns = [
            'name',
            'mobile',
            'email',
            'customer_group',
            'created_by',
            'created_on',
            'action',
            'total_order',
            'total_order_amount',
        ];

        $params = $request->all();


        $limit = $params['length'];
        $start = $params['start'];

        $order = $columns[$params['order'][0]['column']];
        $dir = $params['order'][0]['dir'];

        $where = [
            'store_id' => $request->store_id,
        ];

        $customerObj = Customer::where($where)->with('customer_group');

        if(isset($params['search']['value']) && !empty($params['search']['value']) && strlen($params['search']['value']) > 0 )
        {
            $customerObj = $customerObj->where('name','LIKE',"%{$params['search']['value']}%");
        }

        $totalData = $customerObj->count(); //
        $customers = $customerObj->offset($start)->limit($limit)->orderBy($order,$dir)->get();


        $customers->transform(function($customer){
            $data = [
                'id' => $customer->id,
                'name' => $customer->name,
                'phone' => $customer->phone,
                'email' => $customer->email,
                'street' => $customer->street,
                'customergroup' => isset($customer->customer_group) ? $customer->customer_group->name : "",
                'total_order' => $customer->order,
                'total_order_amount' => $customer->total_amount,
                'createdby' => $customer->createdby,
                'createdon' => $customer->createdon,
                'action' => '',
            ];
//dd($data);
            return (object)$data;
        });

        return new DataTableResponse($customers,$totalData);
    }
}
