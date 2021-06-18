<?php

namespace App\Requests\UserManagement\Users;
use App\Core\BaseRequest as BaseRequest;
use App\Core\Response;
use App\Models\User;
use Auth;

class GetUsersByIdRequest extends BaseRequest{

    public $id;
    public $store_id;

}
class GetUsersByIdRequestHandler {

    public function Serve($request){

        $user = User::with('usr_outlets')->where([['id',$request->id],['store_id',$request->store_id]])->first();
        if($user != null){
            $image_array = [
                'name'      => $user->name,
                'url'      => $user->user_image,
                'size'      => 0
            ];

            $user_template = [
                'id'                => $user->id,
                "name"              => $user->name,
                "mobile"            => $user->mobile,
                'email'             => $user->email,
                "active"            => $user->active,
                "password"          => $user->password,
                "user_image"        => $image_array,
                'user_outlet_ids'   => $user->usr_outlets->pluck('id')->toArray(),
                "role_id"           => $user->role_id,
            ];

    	       return new Response(true, $user_template);
        }
        return new Response(true, null,null,\Lang::get('users.unable_to_find_user'),null);
    }
}
