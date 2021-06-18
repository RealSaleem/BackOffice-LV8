<?php

namespace App\Requests\Reports;
use App\Core\BaseRequest as BaseRequest;
use App\Core\Response;
use App\Models\AddOn;
use Auth;

class GetAddonReportRequest extends BaseRequest{
	public $store_id;
	public $daterange;
	public $date_filter;
    public $addon_name;
    public $addon_type;
}

class GetAddonReportRequestHandler {

    public function Serve($request){

        $login_user = is_null(Auth::user())? \App::make('user') : Auth::user();

        $addonObj = AddOn::with(['items'])->where('store_id', $request->store_id)->where('language_key' ,\App::getLocale());

        // filter by name
        if(isset($request->addon_name) && $request->addon_name != null){
            $addonObj = $addonObj->where('name', 'like', '%'.$request->addon_name.'%');
        }

        // filter by type
        if(isset($request->addon_type) && $request->addon_type != null){
            $addonObj = $addonObj->where('type', $request->addon_type);
        }

        // filter by date
        if( (isset($request->date_filter) && $request->date_filter != null) || (isset($request->daterange) && $request->daterange != null)){

        	$date_range = $this->getDateRange($request);

            $dates = explode(' - ',$date_range);
            $start_date = $dates[0];
            $end_date = $dates[1];

            $addonObj->whereDate('created_at','>=',$start_date);
            $addonObj->whereDate('created_at','<=',$end_date);
        }

        $addons = $addonObj->withCount('items')->get()->toArray();               

        return new Response(true, $addons);
    }   

    private function getDateRange($request){

    	$date_range = $request->date_filter;
        $date_filter = $request->date_filter;

    	if(isset($date_filter) && $date_filter == 'day') {
        
            $date_range = date('Y-m-d', strtotime('-1 days')).' - '.date('Y-m-d');

        } elseif ($date_filter == 'week') {

            $date_range = date('Y-m-d', strtotime('-7 days')).' - '.date('Y-m-d');

        } elseif ($date_filter == 'month') {
            
            $date_range = date('Y-m-d', strtotime('-30 days')).' - '.date('Y-m-d');
        }  else {
            $date_range = $request->daterange;
        }

        return $date_range;
    } 
} 