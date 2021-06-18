<?php

namespace App\Requests\UserManagement\Users;

use App\Core\BaseRequest as BaseRequest;
use App\Core\Response;
use App\Models\User;
use DB;

class ToggleUsersRequest extends BaseRequest{

    public $id;
    public $type;
    public $store_id;
}

class ToggleUsersRequestValidator{
    public function GetRules(){
        return [
            'name' => [
                'required|integer',
                'required|string',
            ],
        ];
    }
}

class ToggleUsersRequestHandler {

    public function Serve($request){

        try {
            DB::beginTransaction();

            $user = User::where(['id' => $request->id, 'store_id' => $request->store_id])->first();

            if($request->type == 'active'){

                $user->active = !$user->active;
            }

            $user->save();

            DB::commit();

            return new Response(true, $user,null,null,\Lang::get('toaster.user_updated'));

        } catch (Exception $ex) {

            DB::rollBack();
            return new Response(false,null,$ex->getMessage());
        }
    }
}
