<?php

namespace App\Requests\UserManagement\Users;

use App\Core\BaseRequest as BaseRequest;
use App\Core\Response;
use App\Models\Store;
use App\Models\User;
use App\Models\UserImage;
use Spatie\Permission\Models\Role;
use App\Models\Outlet;
use App\Models\UserRole;
use Auth;
use Mail;
use Illuminate\Support\Facades\Validator;
use Illuminate\Validation\Rule;
use DB;

class AddUsersRequest extends BaseRequest
{

    public $name;
    public $mobile;
    public $email;
    public $address;
    public $password;
    public $role;
    public $outlets;
    public $active;
    public $images;
}

class AddUsersRequestValidator
{
    public function GetRules($request)
    {
        return [
            'name' => 'required|string|max:45',
            'mobile' => [
                'required',
                Rule::unique('users')->where(function ($query) use ($request) {
                    return $query->where('store_id', $request->store_id)->whereNull('deleted_at');
                })
            ],
            'email' => [
                'required',
                'email',
                'max:50',
                Rule::unique('users')->where(function ($query) use ($request) {
                    return $query->where('store_id', $request->store_id)->whereNull('deleted_at');
                })
            ],
            'role'          => 'required',
            'outlets'       => 'required|array|min:1',
            'password'      => 'required|min:6',
            'confirm_password' => 'same:password',
        ];
    }
}

class AddUsersRequestHandler
{

    public function __construct()
    {
    }

    public function Serve($request)
    {
//        dd($request);
        try {
            DB::beginTransaction();
            $user = new User();
            $user->store_id             = $request->store_id;
            $user->name                 = $request->name;
            $user->password             = bcrypt($request->password);
            $user->email                = $request->email;
            $user->mobile               = $request->mobile;
            $user->verified             = 1;
            $user->active               = 0;
            $user->email_token          = base64_encode($request->email);
            $user->role_id              = $request->role;
            if (isset($request->images[0]['path'])) {
                $user->user_image = $request->images[0]['path'];
            }
            $user->save();


            $user->roles()->attach($request->role);


            // step 2
            if (!is_null($request->outlets)) {
                $outlets = Outlet::whereIn('id', $request->outlets)->get();
                $user->outlets()->attach($outlets);
            }

            DB::commit();
            return new Response(true, $user, null, null, \Lang::get('toaster.user_added'));

        } catch (\Exception $ex) {
            DB::rollBack();
            return new Response(false, null, null, $ex->getMessage(), null);
        }
    }
}
