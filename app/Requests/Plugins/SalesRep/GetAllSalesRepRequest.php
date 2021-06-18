<?php

namespace App\Requests\Plugins\SalesRep;
use App\Core\BaseRequest as BaseRequest;
use App\Core\Response;
use App\Models\SalesRep;
use Auth;

class GetAllSalesRepRequest extends BaseRequest{
	public $id;
}

class GetAllSalesRepRequestHandler {

    public function __construct(){
    }

    public function Serve($request){
        $login_user = is_null(Auth::user())? \App::make('user') : Auth::user();
    	$salesrep = SalesRep::where('store_id', $login_user->store_id)->get();
        

        return new Response(true, $salesrep);
    }
}   