<?php

namespace App\Requests\UserManagement\Permission;
use App\Core\BaseRequest as BaseRequest;
use App\Core\Response;
use App\Models\Permission;
use App\Models\Entity;

class EditPermissionRequest extends BaseRequest{

    public $name;
    public $route;
    public $group;
    public $description;
    public $store_id;
    public $id;
}

class EditPermissionRequestValidator{
    public function GetRules(){
        return [
//            'name' => 'required|max:225',
//            'title' => 'required|max:225',
//            'entity' => 'required|max:225',
//            'description' => 'required',
        ];
    }
}

class EditPermissionRequestHandler {

    public function __construct(){
    }

    public function Serve($request){


        $permission = Permission::find($request->id);
        $permission->name                   = $request->name;
        $permission->display_name           = $permission->name;
//        $permission->route                  = $request->route;
        $permission->description            = $request->description;
        $permission->group                  = $request->group;
        $permission->group_display_name     = $permission->group;
        $permission->save();


    	return new Response(true, $request);
    }
}
