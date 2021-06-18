<?php

namespace App\Requests\Plugins\SalesRep;
use App\Core\BaseRequest as BaseRequest;
use App\Core\Response;
use App\Models\SalesRep;
use Auth;

class GetSalesRepRequest extends BaseRequest{
	public $id;
}

class GetSalesRepRequestHandler {

    public function __construct(){
    }

    public function Serve($request){
        $login_user = is_null(Auth::user())? \App::make('user') : Auth::user();
    	$salesrep = SalesRep::where([['id', $request->id]])->first();
        
        return new Response(true, $salesrep);
    }
}   