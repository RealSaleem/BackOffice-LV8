<?php

namespace App\Requests\CustomerGroup;

use App\Core\BaseRequest as BaseRequest;
use App\Core\DataTableResponse;
use App\Models\CustomerGroup;

class GetAllCustomerGroupRequest extends BaseRequest{
    public $store_id;
}

class GetAllCustomerGroupRequestHandler {

    public function __construct(){
    }

    public function Serve($request){

        $columns = [
            'id',
            'name',
             'total_customers',
            'total_customers',
            'actions',
        ];
//dd($request);
        $params = $request->all();


        $limit = $params['length'];
        $start = $params['start'];

        $order = $columns[$params['order'][0]['column']];
        $dir = $params['order'][0]['dir'];

        $where = [
            'store_id' => $request->store_id,
            //'is_deleted' => 0,
        ];

        $customergroupObj = CustomerGroup::where($where);

        if(isset($params['search']['value']) && !empty($params['search']['value']) && strlen($params['search']['value']) > 0 )
        {
            $customergroupObj = $customergroupObj->where('name','LIKE',"%{$params['search']['value']}%");
        }

        $totalData = $customergroupObj->count();
        $customersgroup = $customergroupObj->withCount('customer')->offset($start)->limit($limit)->orderBy($order,$dir)->get();


        $customersgroup->transform(function($customergroup){

            $data = [

                'id' => $customergroup->id,
                'name' => $customergroup->name,
//                'created_at' =>  date('d-m-Y', strtotime($customergroup->created_at)),
                'total_customers' => $customergroup->customer_count,
                'action' => '',
            ];

            return (object)$data;
        });

        return new DataTableResponse($customersgroup,$totalData);
    }
}
