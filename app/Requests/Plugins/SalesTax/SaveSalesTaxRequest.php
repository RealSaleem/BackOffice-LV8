<?php

namespace App\Requests\Plugins\SalesTax;

use App\Core\BaseRequest as BaseRequest;
use App\Core\Response;
use App\Models\SalesTax;
use Auth;

class SaveSalesTaxRequest extends BaseRequest
{
    public $store_id;
    public $name;
    public $tax_per;
    public $country;
    public $state;
    public $inc_shipping;
    public $inc_discount;
    public $status;
}


class SaveSalesTaxRequestHandler
{
    public function GetRules($request)
    {
        return [
            'name'                  => 'required',
            'tax_per'               => 'required',
            'country'               => 'required',
            'state'                 => 'required',
            
            
        ];
    }
    public function Serve($request)
    {
        try{
            $sales_tax = SalesTax::where('store_id', Auth::user()->store_id)->first();
            if($sales_tax == null){
                $sales_tax                          = new SalesTax;
                $sales_tax->store_id                = Auth::user()->store_id;
                $sales_tax->name                    = $request->name;
                $sales_tax->tax_per                 = $request->tax_per;
                $sales_tax->country                 = $request->country;
                $sales_tax->state                   = $request->state;
                $sales_tax->inc_shipping            = $request->inc_shipping == '1' ? 1 : 0;
                $sales_tax->inc_discount            = $request->inc_discount == '1' ? 1 : 0;
                $sales_tax->status                  = $request->status == '1' ? 1 : 0;
                $sales_tax->created                 = Auth::user()->id;
                $sales_tax->save();
            }else{
                $sales_tax->store_id                = Auth::user()->store_id;
                $sales_tax->name                    = $request->name;
                $sales_tax->tax_per                 = $request->tax_per;
                $sales_tax->country                 = $request->country;
                $sales_tax->state                   = $request->state;
                $sales_tax->inc_shipping            = $request->inc_shipping == '1' ? 1 : 0;
                $sales_tax->inc_discount            = $request->inc_discount == '1' ? 1 : 0;
                $sales_tax->status                  = $request->status == '1' ? 1 : 0;
                $sales_tax->updated                 = Auth::user()->id;
                $sales_tax->save();
            }
            return new Response(true,$sales_tax);

        }catch(Exception $ex){
            return new Response(false,null,$ex->getMessage());
        }
    }
}
