<?php

namespace App\Requests\UserManagement\Permission;

use App\Core\BaseRequest as BaseRequest;
use App\Core\DataTableResponse;
//use App\Models\Permission;
use App\Models\Abilities;

use Spatie\Permission\Models\Permission;


class GetAllPermissionRequest extends BaseRequest{
    // public $store_id;
}

class GetAllPermissionRequestHandler {

    public function __construct(){
    }

    public function Serve($request){
        $columns = [
            'name',
            'slug',
            'Module',
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
                'name' => $permission->display_name,
                'slug' => $permission->name,
                'module' => $permission->module,

            ];
            return (object)$data;
        });
        return new DataTableResponse($permissions,$totalData);
    }
}
