<?php

namespace App\Requests\OutletZone;
use App\Core\BaseRequest as BaseRequest;
use App\Core\Response;
use App\Models\OutletZone;
use App\Models\Outlet;

class GetOutletZoneRequest extends BaseRequest{
	public $store_id;
	public $outlet_id;
	public $zone_id;

}

class GetOutletZoneRequestHandler {

    public function __construct(){
    }

    public function Serve($request){
    	$outlet = Outlet::withCount('zones')->where(['store_id' => $request->store_id, 'id' => $request->outlet_id])->first();
    	$zone = OutletZone::with('areas','counties','cities')->withCount('areas','counties','cities')->where(['id' => $request->zone_id,'store_id' => $request->store_id, 'outlet_id' => $request->outlet_id])->first();
        return new Response(true, ['outlet'=>$outlet,'zone'=>$zone]);
    }
}