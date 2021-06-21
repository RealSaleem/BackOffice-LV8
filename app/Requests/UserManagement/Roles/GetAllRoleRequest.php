<?php

namespace App\Requests\UserManagement\Roles;

use App\Core\BaseRequest as BaseRequest;
use App\Core\DataTableResponse;
//use App\Models\Role;
use Spatie\Permission\Models\Role;

class GetAllRoleRequest extends BaseRequest{
    public $store_id;
}

class GetAllRoleRequestHandler {

    public function __construct(){
    }

    public function Serve($request){

        $columns = [
            'name',
            'name',
        ];

        $params = $request->all();


        $limit = $params['length'];
        $start = $params['start'];

        $order = $columns[$params['order'][0]['column']];
        $dir = $params['order'][0]['dir'];

        $roleObj = Role::where('store_id',$request->store_id);
        // $roleObj = Role::where('store_id',$request->store_id)->where('name','!=','admin');

        if(isset($params['search']['value'])
            && !empty($params['search']['value'])
            && strlen($params['search']['value'])
            > 0 ){
            $roleObj = $roleObj->where('display_name','LIKE',"%{$params['search']['value']}%");
        }

        $totalData = $roleObj->count(); //

        $roles = $roleObj->get();
        $roles->transform(function($roleObj){
            $data = [

                'id' => $roleObj->id,
                'name' => $roleObj->name,
                'display_name'=> $roleObj->display_name,

            ];
            return (object)$data;
        });
        return new DataTableResponse($roles,$totalData);
    }
}
