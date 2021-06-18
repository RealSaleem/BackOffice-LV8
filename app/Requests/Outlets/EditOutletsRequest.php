<?php

namespace App\Requests\Outlets;

use App\Core\BaseRequest as BaseRequest;
use App\Core\Response;
use App\Helpers\Helper;
use App\Models\BrandImage;
use App\Models\Outlet;
use App\Models\OutletImages;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Facades\DB;
use Illuminate\Validation\Rule;
use Auth;
use App\Models\Register;

class EditOutletsRequest extends BaseRequest
{
    public $id;
    public $name;
    public $email;
    public $phone;
    public $store_id;
    public $latitude;
    public $longitude;
    public $address;
    public $facebook;
    public $images;
    public $twitter;
    public $instagram;
    public $snapchat;
    public $updated;
    public $store;
    public $image;
    public $registers;
    public $is_active;
    public $allow_online_order;
    public $enable_zone;
    public $allow_pickup;
    public $enable_business_hours;
    public $min_order_amount;
    public $outlet_register_name;

}

class EditOutletsRequestValidator
{
    public function GetRules($request)
    {
        return [
            'name' => [
                'required',
                'string',
                'max:45',
                Rule::unique('outlets')->where(function ($query) {
                    return $query->where([ 'store_id' => Auth::user()->store_id , 'deleted_at' => null]);
                })->ignore($request->id, 'id'),
            ],
            'phone' => 'string',
            'email' => 'required',
        ];
    }
}

class EditOutletsRequestHandler
{

    public function Serve($request)
    {
        $user = $request->user;

        $editOutlets = Outlet::find($request->id);
        $editOutlets->name = $request->name;
        $editOutlets->email = $request->email;
        $editOutlets->phone = $request->phone;
        $editOutlets->pickup = $request->allow_pickup;
        $editOutlets->enable_business_hours = $request->enable_business_hours;
        $editOutlets->allow_online_order = isset($request->allow_online_order) ? $request->allow_online_order : 0;
        $editOutlets->street_1 = $request->address;
        $editOutlets->min_order_value = $request->min_order_amount;
        $editOutlets->latitude = $request->latitude;
        $editOutlets->longitude = $request->longitude;
        $editOutlets->facebook = $request->facebook;
        $editOutlets->twitter = $request->twitter;
        $editOutlets->instagram = $request->instagram;
        $editOutlets->snap_chat = $request->snapchat;
        $editOutlets->updated = $user->id;
        $editOutlets->store_id = $request->store_id;
        $editOutlets->is_active = $request->is_active == 1;
        $editOutlets->enable_zone = $request->enable_zone == 1;
        if (isset($request->images[0]['path'])) {
            $editOutlets->image = $request->images[0]['path'];
        }
        $editOutlets->slug = Helper::getSlug($request->name,$editOutlets->id);

        if ($editOutlets->save()) {
            if ($request->outlet_register_name !== null) {
                foreach ($request->outlet_register_name as $reg) {
                    $register_find = Register::where(['name'=> $reg , 'outlet_id' => $request->id])->first();
                    if($register_find == null) {
                        $register = new Register();
                        $register->outlet_id = $request->id;
                        $register->name = $reg;
                        $register->save();
                    }
                }
            }
            return new Response(true, $editOutlets,null,null,\Lang::get('toaster.outlet_updated'));

        }
        return new Response(false, null,null,\Lang::get('toaster.outlet_unableToUpdated'));


    }

}
