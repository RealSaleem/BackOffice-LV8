<?php

namespace App\Requests\UserManagement\Users;

use App\Core\BaseRequest as BaseRequest;
use App\Core\Response;
use App\Models\User;
use DB;

class BulkUsersRequest extends BaseRequest{
    public $store_id;
    public $type;
    public $users;
    public $action;
}

class BulkUsersRequestHandler {

    public function __construct(){
    }

    public function Serve($request){
        // dd($request);
        try {
            DB::beginTransaction();
            $user = User::where('id',$request->users)->where(['store_id' => $request->store_id])->first();
            $role = $user->roles->first();
            $userObj = User::where(['store_id' => $request->store_id])->whereIn('id',$request->users);

            $is_active = $request->action === 'true' ? 1 : 0;


            if($request->type == 'active'){
                $data = [
                    'active' => $is_active
                ];

                $userObj->update($data);
                $success = true;
            }else{

                if(strtolower($role->name) != 'admin'){
                    $data = [
                        'is_deleted' => true,
                    ];

                    $userObj->update($data);
                    $user = User::where('id',$request->users)->where(['store_id' => $request->store_id])->first();
                    $user->delete();
                    $success = true;

                }else{
                    $success = false;
                    $errors = [\Lang::get('toaster.admin_can_not_be_deleted')];
                }

            }

            DB::commit();

            $message = $request->type == 'delete' ? \Lang::get('toaster.user_deleted') : \Lang::get('toaster.user_updated');

            return new Response($success, null,null,null,$message);

        } catch (Exception $ex) {

            DB::rollBack();
            return new Response(false,null,$ex->getMessage());
        }
    }
}
