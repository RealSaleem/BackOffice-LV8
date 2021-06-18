<?php

namespace App\Requests\UserManagement\Permission;
use App\Core\BaseRequest as BaseRequest;
use App\Core\Response;
use App\Models\Permission;

class DeletePermissionRequest extends BaseRequest{

    public $permission_id;

}

class DeletePermissionRequestHandler {

    public function __construct(){
    }

    public function Serve($request){
        $permission = Permission::find($request->permission_id);
        $success = false;
        $errors = [];

        try{
            $success = true;
            // if($permission->customer_count > 0){
                // $success = false;
                // $errors = ['Unable to delete group which contains customers'];
            // }else{
                $permission->delete();
            // }


        }catch(Exception $ex){
            $success = false;
            $errors = [$ex];
        }

    	return new Response($success, $request,null,$errors);
    }
}
