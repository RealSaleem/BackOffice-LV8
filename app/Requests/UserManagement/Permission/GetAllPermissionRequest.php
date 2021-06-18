<?php

namespace App\Requests\UserManagement\Permission;

use App\Core\BaseRequest as BaseRequest;
use App\Core\DataTableResponse;
use App\Models\Permission;
use App\Models\Abilities;

class GetAllPermissionRequest extends BaseRequest{
    // public $store_id;
}

class GetAllPermissionRequestHandler {

    public function __construct(){
    }

    public function Serve($request){
        $columns = [
            'name',
            'display_name',
            'group_display_name',


        ];

        $params = $request->all();
        $limit = $params['length'];
        $start = $params['start'];

        $order = $columns[$params['order'][0]['column']];
        $dir = $params['order'][0]['dir'];

        // $where = [
        //     'group' => 'product',
        // ];
        $permissionObj = new Permission();
        // if(isset($params['search']['value']) && !empty($params['search']['value']) && strlen($params['search']['value']) > 0 )

        // {
        //     $permissionObj = $permissionObj->where('name','LIKE',"%{$params['search']['value']}%");
        // }

        $totalData = $permissionObj->count(); //
        $permissions = $permissionObj->offset($start)->limit($limit)->orderBy($order,$dir)->get();
        $permissions->transform(function($permission){
            $data = [
                'id' => $permission->id,
                'name' => $permission->name,
                'display_name' => $permission->display_name,
                'group_display_name' => $permission->group_display_name,


            ];
            return (object)$data;
        });
        return new DataTableResponse($permissions,$totalData);
    }
}
