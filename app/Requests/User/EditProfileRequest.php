<?php

namespace App\Requests\User;

use App\Core\BaseRequest as BaseRequest;
use App\Core\IValidator;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Validator;
use Illuminate\Http\Request;
use App\Core\Response;
use App\Models\User;
use App\Models\Outlet;
use Auth;


class EditProfileRequest extends BaseRequest
{

    public $id;
    public $name;
    public $mobile;
    public $outlet;
    public $images;
    public $password;
    public $confirm_password;

    /*
      public $outlet_id;
      public $username;
      public $email;

      public $store_id;

      public $daily_target;
      public $weekly_target;
      public $monthly_target;
      public $user_image;
      public $role;
      */
}

class EditProfileRequestValidator
{
    public static function GetRules()
    {
        return [
            'name' => 'required|string|max:45',
//            'email' => 'required|email|max:45|unique:users,email,' . Auth::user()->id,
            //'password' => 'min:6',
            'confirm_password' => 'same:password',
            'mobile' => 'required',
//      'outlets' => 'required|array|min:1',
            //'role' => 'required',
        ];
    }
}

class EditProfileRequestHandler
{
    public function Serve($request)
    {

        try {

            $pass = $request->password;
            $con_pas = $request->confirm_password;
            $user = Auth::user();

            $user->name = $request->name;
            $user->mobile = $request->mobile;

            if ($pass != null && $con_pas != null && $pass == $con_pas) {
                $user->password = Hash::make($pass);
                $user->save();
            }

            if (isset($request->images) && sizeof($request->images) > 0) {
                $user->user_image = $request->images[0]['path'];
            }

            $user->save();

            if (!is_null($request->outlet)) {
                $user->outlets()->detach();
                $outlets = Outlet::whereIn('id', $request->outlet)->get();
                $user->outlets()->attach($outlets);
            }
            return new Response(true, $user, null, null, \Lang::get('toaster.user_updated'));

        } catch (Exception $ex) {

            DB::rollBack();
            return new Response(false, null, $ex->getMessage());
        }
    }
}
