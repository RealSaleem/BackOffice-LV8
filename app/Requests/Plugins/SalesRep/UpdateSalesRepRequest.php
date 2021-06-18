<?php

namespace App\Requests\Plugins\SalesRep;

use App\Core\BaseRequest as BaseRequest;
use App\Core\Response;
use DB;
use Auth;
use App\Models\SalesRep;

class UpdateSalesRepRequest extends BaseRequest
{
    public $name;
    public $code;
    public $salary;
    public $commission;
    public $phone;
    public $national_id;
    public $active;
    public $id;
}

class UpdateSalesRepRequestHandler
{
    public function Serve($request)
    {
        // dd($request);
        try {
            
            DB::beginTransaction();

            $salesrep                        = SalesRep::find($request->id);
            $salesrep->store_id              = Auth::user()->store_id;
            $salesrep->name                  = $request->name;
            $salesrep->code                  = $request->code;
            $salesrep->salary                = $request->salary;
            $salesrep->commission            = $request->commission;
            $salesrep->phone                 = $request->phone;
            $salesrep->national_id           = $request->national_id;
            $salesrep->is_active             = $request->active == 'on' ? 1: 0;
            
            $salesrep->save();

            DB::commit();

            return new Response(true,null,null,null,'Record has been updated successfully');
            
        } catch (\PDOException $ex) {
            
            DB::rollBack();
            return new Response(false,null,$ex->getMessage());
        }        
    }
}
