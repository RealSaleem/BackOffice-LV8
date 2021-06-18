<?php

namespace App\Requests\Reports;
use App\Core\BaseRequest as BaseRequest;
use App\Core\Response;
use App\Models\User;
use Auth;

class GetUserReportRequest extends BaseRequest{
	public $outlet_id;
	public $daterange;
	public $date_filter;
    public $user_name;
}

class GetUserReportRequestHandler {

    public function Serve($request){

        $login_user = is_null(Auth::user())? \App::make('user') : Auth::user();

        $userObj = User::with('usr_outlets')->where(['store_id' => $request->store_id, 'is_deleted' => null]);

        // filter by name
        if(isset($request->user_name) && $request->user_name != null){
            $userObj = $userObj->where('name', 'like', '%'.$request->user_name.'%');
        }

        // filter users by outlet id
        if(isset($request->outlet_id) && $request->outlet_id != null){
            $userObj = $userObj->whereHas('usr_outlets', function($query) use($request){
                return $query->where('outlet_id', $request->outlet_id);
            });
        }
        
        // filter users by date
        if( (isset($request->date_filter) && $request->date_filter != null) || (isset($request->daterange) && $request->daterange != null)){

        	$date_range = $this->getDateRange($request);

            $dates = explode(' - ',$date_range);
            $start_date = $dates[0];
            $end_date = $dates[1];

            $userObj->whereDate('created_at','>=',$start_date);
            $userObj->whereDate('created_at','<=',$end_date);
        } 

        $users = $userObj->get()->toArray();               

        return new Response(true, $users);
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