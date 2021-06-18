<?php

namespace App\Requests\Reports;
use App\Core\BaseRequest as BaseRequest;
use App\Core\Response;
use App\Models\Register;
use App\Models\RegisterHistory;
use App\Models\Store;
use App\Models\User;
use Auth;


class GetRegisterReportRequest extends BaseRequest{
	public $outlet_id;
	public $daterange;
	public $date_filter;
	public $register_name;
}

class GetRegisterReportRequestHandler {

    public function __construct(){
    }

    public function Serve($request){

        $login_user = is_null(Auth::user())? \App::make('user') : Auth::user();

        $outlet_ids = [];

        $outlets = $login_user->outlets->toArray();
        $outlet_ids = array_column($outlets, 'id');

        if(isset($request->outlet_id) && !empty($request->outlet_id)){
            $registerObj = Register::select('id')->where('outlet_id', $request->outlet_id);
        } else{
            $registerObj = Register::select('id')->whereIn('outlet_id', $outlet_ids);
        }

        // filter by register name
        if(isset($request->register_name) && $request->register_name != null){
            $registerObj = $registerObj->where('name', 'like', '%'.$request->register_name.'%');
        }

        $registers = $registerObj->get()->toArray();

        $registerIds = array_column($registers, 'id');

        $registerHistoryObj = RegisterHistory::with(['orders','cash_flow','register'])->whereIn('register_id', $registerIds)->where('is_open',0);

        // filter products by date
        if( (isset($request->date_filter) && $request->date_filter != null) || (isset($request->daterange) && $request->daterange != null)){

        	$date_range = $this->getDateRange($request);

            $dates = explode(' - ',$date_range);
            $start_date = $dates[0];
            $end_date = $dates[1];

            $registerHistoryObj->where('created_at','>=',$start_date);
            $registerHistoryObj->where('created_at','<=',$end_date);
        }

        $registerHistory = $registerHistoryObj->orderBy('closed_on','DESC')->get();                

        return new Response(true, $registerHistory);
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