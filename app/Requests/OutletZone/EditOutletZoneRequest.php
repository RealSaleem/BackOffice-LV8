<?php

namespace App\Requests\OutletZone;

use App\Core\BaseRequest as BaseRequest;
use App\Core\Response;
use App\Models\OutletZone as OutletZone;
use App\Models\User;
use Auth;
use Illuminate\Validation\Rule;

class EditOutletZoneRequest extends BaseRequest
{

    public $id;
    public $store_id;
    public $outlet_id;
    public $name;
    public $delivery_time;
    public $delivery_charges;
    public $min_order;
    public $country;
    public $city;
    public $areas;
    public $business_hours;
    public $is_active;
    public $zone_coverage;

}

class EditOutletZoneRequestValidator
{
    public function GetRules()
    {
        return [
            'name' => [
                'required',
                'string',
                'max:50',
                Rule::unique('outlet_zone')->where(function ($query){
                    return $query->where(['store_id'=> Auth::user()->store_id, 'outlet_id' => \Request::get('outlet_id')]);
                })->ignore(\Request::get('id'))
            ],
            'delivery_time'     => 'required',
            'delivery_charges'     => 'required|numeric',
        ];
    }
}

class EditOutletZoneRequestHandler
{

    public function __construct()
    {
    }

    public function Serve($request)
    {
//        dd($request);

        $login_user = is_null(Auth::user())? \App::make('user') : Auth::user();
        $zone = OutletZone::with('areas')->find($request->zone_id);

        if( $request->zone_coverage == 'country'){
            $request->city = null;
            $request->areas =null;
        }
        if( $request->zone_coverage == 'city'){
            $request->areas =null;
        }

        $zone->store_id         = $request->store_id;
        $zone->outlet_id        = $request->outlet_id;
        $zone->name             = $request->name;
        $zone->delivery_time    = $request->delivery_time;
        $zone->delivery_charges = $request->delivery_charges;
        $zone->min_order        = $request->min_order;
        $zone->country_id       = $request->country[0];
        $zone->city_id          = $request->city[0];
        // $zone->zone_coverage    = $request->zone_coverage;

        if($request->is_active == 'on'){
            $zone->is_active        = 1;
        }

        $zone->business_hours   = json_encode((object)$request->business_hours);
        $zone->save();

        if($zone->zone_coverage == 'country'){
            $zone->zone_countries()->detach();
            if(is_array($request->country) && sizeof($request->country) > 0)
            {
                $zone->zone_countries()->attach($request->country);
            }
        }else if($zone->zone_coverage == 'cities'){
            $zone->zone_cities()->detach();
            if(is_array($request->city) && sizeof($request->city) > 0)
            {
                $zone->zone_cities()->attach($request->city);
            }
        }else if($zone->zone_coverage == 'area'){
            $zone->zone_areas()->detach();
            if(is_array($request->areas) && sizeof($request->areas) > 0)
            {
                $zone->zone_areas()->attach($request->areas);
            }
        }


        return new Response(true, $zone);
    }
}
