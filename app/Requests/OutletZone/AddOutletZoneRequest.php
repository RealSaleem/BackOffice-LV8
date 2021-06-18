<?php

namespace App\Requests\OutletZone;

use App\Core\BaseRequest as BaseRequest;
use App\Core\Response;
use App\Models\OutletZone as OutletZone;
use App\Models\User;
use Auth;
use Illuminate\Validation\Rule;

class AddOutletZoneRequest extends BaseRequest
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

class AddOutletZoneRequestValidator
{
    public function GetRules()
    {
        return [
            'name' => [
                'required',
                'string',
                'max:50',
                Rule::unique('outlet_zone')->where(function ($query){
                    return $query->where('store_id', Auth::user()->store_id);
                })
            ],
            'delivery_time'     => 'required',
            'delivery_charges'     => 'required|numeric',
        ];
    }
}

class AddOutletZoneRequestHandler
{

    public function __construct()
    {
    }

    public function Serve($request)
    {
//dd($request);
        $zone = new OutletZone;

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
        $zone->country_id       = $request->country[0] ?? 0;
        $zone->city_id          = $request->city[0] ?? 0;
        $zone->zone_coverage    = $request->zone_coverage;

        if($request->is_active == 'on'){
            $zone->is_active        = 1;
        }

        $zone->business_hours   = json_encode((object)$request->business_hours);

        $zone->save();

        if(is_array($request->country) && sizeof($request->country) > 0)
        {
            $zone->zone_countries()->attach($request->country);
        }
        if(is_array($request->city) && sizeof($request->city) > 0)
        {
            $zone->zone_cities()->attach($request->city);
        }
        if(is_array($request->areas) && sizeof($request->areas) > 0)
        {
            $zone->zone_areas()->attach($request->areas);
        }
        return new Response(true, $zone);
    }
}
