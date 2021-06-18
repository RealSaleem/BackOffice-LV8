<?php

namespace App\Requests\Plugins\SalesRep;
use App\Core\BaseRequest as BaseRequest;
use App\Core\Response;
use App\Models\SalesRep;
use Auth;
use Illuminate\Validation\Rule;
use DB;

class AddSalesRepRequest extends BaseRequest{
    
    public $name;
    public $code;
    public $salary;
    public $commission;
    public $phone;
    public $national_id;
    public $active;

}

class AddSalesRepRequestValidator{
    public function GetRules(){
        return [
            
                'name'          =>  'required',
                'code'          =>  'required',
                'national_id'   =>  'required', 
        ];
    }
} 

class AddSalesRepRequestHandler {

    public function __construct(){
    }

    public function Serve($request){

        	$newSalesRep = new SalesRep;
        	
        	$newSalesRep->name 					= $request->name;
            $newSalesRep->code 					= $request->code;
            $newSalesRep->salary 				= $request->salary;
            $newSalesRep->commission 			= $request->commission;
            $newSalesRep->phone 				= $request->phone;
            $newSalesRep->national_id           = $request->national_id;
            $newSalesRep->is_active 			= $request->active == 'on' ? 1: 0;
            
            $newSalesRep->store_id 				= Auth::user()->store_id;
            
            $newSalesRep->save();

            return new Response(true, $newSalesRep);

    }
} 