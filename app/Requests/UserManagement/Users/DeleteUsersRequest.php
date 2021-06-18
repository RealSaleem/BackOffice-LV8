<?php

namespace App\Requests\UserManagement\Users;
use App\Core\BaseRequest as BaseRequest;
use App\Core\Response;
use App\Models\User as User;

class DeleteUsersRequest extends BaseRequest{

    public $store_id;
    public $user_id;

}

class DeleteUsersRequestHandler {

    public function __construct(){
    }

    public function Serve($request){
        $user = User::find($request->user_id);
        $role = $user->roles->first();
        $success = true;
        $errors = null;
        $message = null;

        try{
            if(strtolower($role->name) != 'admin'){
                $success = true;
                $user->is_deleted = 1;
                $user->save();
                $user->delete();
                $message = \Lang::get('toaster.user_deleted');
            }else{
                $success = false;
                $errors = [\Lang::get('toaster.admin_can_not_be_deleted')];
            }

        }catch(Exception $ex){
            $success = false;
        }



    	return new Response($success, $request,null,$errors,$message);
    }
}
