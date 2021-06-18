<?php

namespace App\Requests\Plugins\SalesRep;
use App\Core\BaseRequest as BaseRequest;
use App\Core\Response;
use App\Models\SalesRep;
use Auth;
use Illuminate\Validation\Rule;
use DB;

class DeleteSalesRepRequest extends BaseRequest{
    
    public $id;
 
}

class DeleteSalesRepRequestHandler {

    public function __construct(){
    }
    
    public function Serve($request){
        
        $salesrep = SalesRep::where(['id'=> $request->id , 'store_id' => \Auth::user()->store_id])->first();

	        return new Response(true, $salesrep);

    }
} 