<?php

namespace App\Requests\Outlets;

use App\Core\BaseRequest as BaseRequest;
use App\Core\Response;
use App\Helpers\Helper;
use App\Models\Outlet;
use App\Models\OutletImages;
use App\Models\Register;
use App\Models\Store;
use Auth;
use App\Mail\NewOutletsRegistered;
use Illuminate\Support\Facades\DB;
use Illuminate\Validation\Rule;
use Mail;
use App\Models\City;
use App\Mail\NewOutletRegistered;
use App\Mail\WelcomeOutlet;

class AddOutletsRequest extends BaseRequest
{

    public $name;
    public $email;
    public $phone;
    public $images;
    public $image;
    public $address;
    public $latitude;
    public $longitude;
    public $store_id;
    public $facebook;
    public $twitter;
    public $instagram;
    public $snapchat;
    public $active;
    public $registers;
    public $is_active;
    public $allow_online_order;
    public $enable_zone;
    public $allow_pickup;
    public $enable_business_hours;
    public $min_order_amount;
    public $outlet_register_name;
    public $state;
    public $country;
    public $timezone;
    public $snap_chat;
    public $min_order_value;
    public $pickup;

    public $status;
    public $street_1;
    public $street_2;
    public $city;
    public $order_num;
    public $order_num_prefix;
}

class AddOutletsRequestValidator
{
    public function GetRules($request)
    {
        return [
            'name' => [
                'required',
                'string',
                'max:45',
                Rule::unique('outlets')->where(function ($query)  use ($request){
                    return $query->where([ 'store_id' => $request->user->store_id , 'deleted_at' => null]);
                }),
            ],
            'phone'=> 'numeric',
            'email'=> 'required',
        ];
    }
}

class AddOutletsRequestHandler
{

    public function Serve($request)
    {

        $user = $request->user;
        $store = $user->store;

        try {

            DB::beginTransaction();

            $newOutlet = new Outlet;

            $newOutlet->store_id                = $user->store_id;
            $newOutlet->name                    = $request->name;
            $newOutlet->order_num_prefix        = $request->order_num_prefix;
            $newOutlet->order_num               = $request->order_num;
            $newOutlet->status                  = $request->status;
            $newOutlet->street_1                = $request->address;
            $newOutlet->street_2                = $request->street_2;
            $newOutlet->city                    = $request->city;
            $newOutlet->state                   = (bool)$request->state;
            $newOutlet->country                 = $request->country;
            $newOutlet->timezone                = $request->timezone;
            $newOutlet->email                   = $request->email;
            $newOutlet->phone                   = $request->phone;
            $newOutlet->twitter                 = $request->twitter;
            $newOutlet->instagram               = $request->instagram;
            $newOutlet->facebook                = $request->facebook;
            $newOutlet->snap_chat               = $request->snap_chat;

            $newOutlet->longitude               = $request->longitude;
            $newOutlet->latitude                = $request->latitude;
            $newOutlet->min_order_value         = $request->min_order_value;

            $newOutlet->pickup                  = (bool)$request->allow_pickup;
            $newOutlet->is_active               = (bool)$request->is_active;
            $newOutlet->enable_zone             = (bool)$request->enable_zone;
            $newOutlet->enable_business_hours   = (bool)$request->enable_business_hours;
            $newOutlet->allow_online_order      = $request->allow_online_order ? $request->allow_online_order : 0 ;
            $newOutlet->created                 = $user->id;

            if (isset($request->images[0]['path'])) {
                $newOutlet->image               = $request->images[0]['path'];
            }

            if ($newOutlet->save()) {

                $user->outlets()->attach($newOutlet->id);
                if ($request->outlet_register_name !== null) {
                    foreach ($request->outlet_register_name as $reg) {
                        $register_find = Register::where(['name'=> $reg , 'outlet_id' => $newOutlet->id])->first();
                        if($register_find == null){
                            $register = new Register();
                            $register->outlet_id    = $newOutlet->id;
                            $register->name         = $reg;
                            $register->save();
                        }
                    }
                }
                $newOutlet->slug = Helper::getSlug($request->name,$newOutlet->id);

                $city_name = City::find($newOutlet->city);
                if($city_name != null){
                    $newOutlet->city = $city_name->city;
                }

                $store_id = $user->store_id;

                DB::commit();

                if ($request->email != null) {
                    $email = new WelcomeOutlet($newOutlet);
                    Mail::to($request->email)->queue($email);
                }

                //assign it to admin
                if(!$user->hasRole('admin')){
                    $admin = User::with(['roles' => function($q) use ($store_id){
                        return $q->where('name', 'admin')->where('store_id',$store_id);
                    }])->first();

                    if(isset($admin)){
                        $admin->outlets()->attach($newOutlet->id);
                    }

                    $emailStore = new NewOutletRegistered($newOutlet,$store,$admin);
                    Mail::to($admin->email)->queue($emailStore);
                }

            }
            return new Response(true, $newOutlet,null, null,\Lang::get('toaster.outlet_added'));

        } catch (Exception $ex) {
            DB::rollBack();
            return new Response(false, null, $ex->errorInfo, null);
        }
    }
}
