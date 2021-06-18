<?php

namespace App\Requests\Catalogue\Supplier;

use App\Core\BaseRequest as BaseRequest;
use App\Core\DataTableResponse;
use App\Models\Supplier;

class GetAllSupplierRequest extends BaseRequest{
    public $store_id;
}

class GetAllSupplierRequestHandler {

    public function __construct(){
    }

    public function Serve($request){

        $columns = [
            'name',
            'email',
            'mobile',
            'total_products',
            'active',
            'actions',
        ];

        $params = $request->all();

        $limit = $params['length'];
        $start = $params['start'];

        $order = $columns[$params['order'][0]['column']];
        $dir = $params['order'][0]['dir'];

        $where = [
            'store_id' => $request->store_id,
        ];

        $supplierObj = Supplier::where($where)->whereNull('is_deleted');


        if(isset($params['search']['value']) && !empty($params['search']['value']) && strlen($params['search']['value']) > 0 )
        {
            $supplierObj = $supplierObj->where('name','LIKE',"%{$params['search']['value']}%");
        }

        $totalData = $supplierObj->count(); //
        $suppliers = $supplierObj->withCount('product_suppliers')->offset($start)->limit($limit)->orderBy($order,$dir)->get();


        $suppliers->transform(function($supplier){

            $data = [
                'id' => $supplier->id,
                'name' => $supplier->name,
                'mobile' => $supplier->mobile,
                'email' => $supplier->email,
                'total_products' =>$supplier->product_suppliers_count,
                'active' => $supplier->active,
                'actions' => '',
            ];

            return (object)$data;
        });

        return new DataTableResponse($suppliers,$totalData);
    }
}
