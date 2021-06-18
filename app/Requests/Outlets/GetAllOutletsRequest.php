<?php

namespace App\Requests\Outlets;

use App\Core\BaseRequest as BaseRequest;
use App\Core\DataTableResponse;
use App\Helpers\Helper;
use App\Models\Outlet;


class GetAllOutletsRequest extends BaseRequest{
    public $store_id;
}

class GetAllOutletsRequestHandler {

    public function __construct(){
    }

    public function Serve($request){

        $columns = [
            'name',
            'mobile',
            'email',
            'action',
            'facebook',
            'twitter',
            'instagram',
            'snap_chat'
        ];

        $params = $request->all();


        $limit = $params['length'];
        $start = $params['start'];

        $order = $columns[$params['order'][0]['column']];
        $dir = $params['order'][0]['dir'];

        $where = [
            'store_id' => $request->store_id,
        ];

        $outletsObj = Outlet::where($where);


        if(isset($params['search']['value']) && !empty($params['search']['value']) && strlen($params['search']['value']) > 0 )
        {
            $outletsObj = $outletsObj->where('name','LIKE',"%{$params['search']['value']}%");
        }

        $totalData = $outletsObj->count(); //
        $outlets = $outletsObj->offset($start)->limit($limit)->orderBy($order,$dir)->get();


        $outlets->transform(function($outlets){

            $data = [

                'id' => $outlets->id,
                'name' => $outlets->name,
                'mobile' => $outlets->mobile,
                'email' => $outlets->email,
                'facebook' => $outlets->facebook,
                'twitter' => $outlets->twitter,
                'instagram' => $outlets->instagram,
                'snap_chat' => $outlets->snapchat,
                'action' => '',
            ];
            return (object)$data;
        });

        return new DataTableResponse($outlets,$totalData);
    }
}
