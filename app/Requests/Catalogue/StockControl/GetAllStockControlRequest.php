<?php

namespace App\Requests\Catalogue\StockControl;

use App\Core\BaseRequest as BaseRequest;
use App\Core\DataTableResponse;
use App\Models\PurchaseOrder;

class GetAllStockControlRequest extends BaseRequest{
    public $store_id;
}

class GetAllStockControlRequestHandler {

    public function __construct(){
    }

    public function Serve($request){
    	
        $columns = [
            'name',
            'ordertype',
            'due_date',
            'deliver_due',
            'order_number',
            'outlet',
            'supplier',
            'status',
            'action',          
        ];

        $params = $request->all();

        $limit = $params['length'];
        $start = $params['start'];

        $order = $columns[$params['order'][0]['column']];
        $dir = $params['order'][0]['dir'];

        $where = [ 
            'store_id' => $request->store_id, 
        ];

        $stockcontrolObj = PurchaseOrder::where($where);

        if(isset($params['search']['value']) && !empty($params['search']['value']) && strlen($params['search']['value']) > 0 )
        {            
            $stockcontrolObj = $stockcontrolObj->where('name','LIKE',"%{$params['search']['value']}%");
        }

        $totalData = $stockcontrolObj->count(); // 
        $stockcontrol
         = $stockcontrolObj->offset($start)->limit($limit)->orderBy($order,$dir)->get();
        //->withCount('products')
       

        $stockcontrol->transform(function($stockcontrol){
            
            $data = [
                'id' => $stockcontrol->id,
                'name' => $stockcontrol->name,
                'order_type' => $stockcontrol->ordertype,
                'due_date' => $stockcontrol->due_date,
                'delivery_due' => $stockcontrol->delivery_due,
                'order_number' => $stockcontrol->order_number, 
                'outlet' => $stockcontrol->outlet, 
                'supplier' => $stockcontrol->supplier, 
                'status' => $stockcontrol->status, 
                'action' => '',
            ];
           
            return (object)$data;
        });

        return new DataTableResponse($stockcontrol,$totalData);
    }
} 