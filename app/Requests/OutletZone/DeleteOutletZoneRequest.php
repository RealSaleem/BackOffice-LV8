<?php

namespace App\Requests\OutletZone;
use App\Core\BaseRequest as BaseRequest;
use App\Core\Response;
use App\Models\OutletZone as OutletZone;

class DeleteOutletZoneRequest extends BaseRequest{

    public $id;
    public $store_id;
    public $zone_id;
    public $outlet_id;

}

class DeleteOutletZoneRequestHandler {

    public function __construct(){
    }

    public function Serve($request){
        $zone = OutletZone::with('areas','counties','cities')->where(['id' => $request->zone_id,'store_id' => $request->store_id, 'outlet_id' => $request->outlet_id])->first();
        $success = true;
        try{
            if(isset($zone->counties)){
                 $zone->zone_countries()->detach();
            }
            if(isset($zone->cities)){
                $zone->zone_cities()->detach();
            }
            if(isset($zone->areas)){
                $zone->zone_areas()->detach();
            }
    	   $zone->delete();
        }catch(Exception $ex){
            $success = false;
        }

    	return new Response($success, $request);
    }
}
