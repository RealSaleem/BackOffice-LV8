<?php

namespace App\Requests\UserManagement\Roles;
use App\Core\BaseRequest as BaseRequest;
use App\Core\Response;
use Spatie\Permission\Models\Role;
use Silber\Bouncer\BouncerFacade as Bouncer;

class AssignPermissionRequest extends BaseRequest{

    public $permissions;
    public $role;
    public $store_id;
}

class AssignPermissionRequestValidator{
    public function GetRules(){
        return [

        ];
    }
}

class AssignPermissionRequestHandler {

    public function __construct(){
    }

    public function Serve($request){


//dd($request);

        foreach ($request- as $key) {
             $key = json_decode($key);
             dd($key);
//            Bouncer::allow($request->role)->to($key);
        }
        return new Response(true, $request);
    }
}
