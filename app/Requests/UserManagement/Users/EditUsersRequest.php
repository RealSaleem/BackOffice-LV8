<?php

namespace App\Requests\Usermanagement\Users;

use App\Core\BaseRequest as BaseRequest;
use App\Core\Response;
use App\Models\UserRole;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Facades\DB;
use Illuminate\Validation\Rule;
use App\Models\User;
use App\Models\Outlet;
use App\Models\Role;
use Auth;
use App\Models\UserImage;
use Silber\Bouncer\Database\Queries\Roles;

class EditUsersRequest extends BaseRequest
{

    public $id;
    public $name;
    public $mobile;
    public $phone;
    public $email;
    public $password;
    public $confirm_password;
    public $images;
    public $active;
    public $store_id;
    public $updated;
    public $outlets;
    public $role;
}

class EditUsersRequestValidator
{
    public function GetRules($request)
    {
        return [
            'name' => 'required|string|max:45',
            'mobile' => [
                'required',
                Rule::unique('users')->where(function ($query) use ($request) {
                    return $query->where('store_id', $request->store_id);
                })->ignore($request->id)
            ],
            'email' => [
                'required',
                'email',
                'max:50',
                Rule::unique('users')->where(function ($query) use ($request) {
                    return $query->where('store_id', $request->store_id);
                })->ignore($request->id)
            ],
            'outlets'           => 'required|array|min:1',
            'role'              => 'required',
            'confirm_password'  => 'same:password',
        ];
    }
}

class EditUsersRequestHandler
{

    public function __construct()
    {
    }

    public function Serve($request)
    {

        $editUser = User::where([['id',$request->id],['store_id',$request->store_id]])->first();
        if($editUser != null ){
            $editUser->name = $request->name;
            $editUser->mobile = $request->mobile;
            $editUser->email = $request->email;
            $editUser->verified = 1;

            if($request->password != null){
                if($request->password == $request->confirm_password){
                    $editUser->password = bcrypt($request->password);
                }
            }

            if(isset($request->images[0]['path'])){
                $editUser->user_image   = $request->images[0]['path'];
            }

            $editUser->role_id      = $request->role;
            $editUser->save();

            $editUser->roles()->detach();
            $editUser->roles()->attach($request->role);


            // step 2
            if ($request->outlets != null) {
                $editUser->outlets()->detach();
                $outlets = Outlet::whereIn('id', $request->outlets)->where('store_id', $request->store_id)->get();
                $editUser->outlets()->attach($outlets);
            }
            return new Response(true, null,null,null,\Lang::get('toaster.user_updated'));
        }
        return new Response(true, null,null,\Lang::get('toaster.unable_to_find_user'),null);
    }
}
