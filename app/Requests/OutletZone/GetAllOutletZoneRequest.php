<?php

namespace App\Requests\OutletZone;
use App\Core\BaseRequest as BaseRequest;
use App\Core\Response;
use App\Models\OutletZone;
use App\Models\Outlet;

class GetAllOutletZoneRequest extends BaseRequest{
	public $store_id;
	public $outlet_id;

}

class GetAllOutletZoneRequestHandler {

    public function __construct(){
    }

    public function Serve($request){
    	$outlet = Outlet::withCount('zones')->where(['store_id' => $request->store_id, 'id' => $request->outlet_id])->first();
    	$zones = OutletZone::with('areas','counties','cities')->withCount('areas','counties','cities')->where(['store_id' => $request->store_id, 'outlet_id' => $request->outlet_id])->get();
        return new Response(true, ['outlet'=>$outlet,'zones'=>$zones]);
    }
}