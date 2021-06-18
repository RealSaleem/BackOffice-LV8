<?php

namespace App\Requests\Plugins\Loyalty;

use App\Core\BaseRequest as BaseRequest;
use App\Core\Response;
use App\Models\Loyalty;
use Auth;

class SaveLoyaltyRequest extends BaseRequest
{
    public $store_id;
    public $cap_amount;
    public $points;
    public $redeem_rate;
    public $max_reword_discount;
    public $expire_after;
}


class SaveLoyaltyRequestHandler
{
    public function GetRules($request)
    {
        return [
            'cap_amount'            => 'required',
            'points'                => 'required',
            'redeem_rate'           => 'required',
            'max_reword_discount'   => 'required',
            
            
        ];
    }
    public function Serve($request)
    {
        try{

             $loyalty = Loyalty::where('store_id', Auth::user()->store_id)->first();
            if($loyalty == null){
                $loyalty = new Loyalty;
                $loyalty->store_id              = Auth::user()->store_id;
                $loyalty->cap_amount            = $request->cap_amount;
                $loyalty->points                = $request->points;
                $loyalty->redeem_rate           = $request->redeem_rate;
                $loyalty->max_reword_discount   = $request->max_reword_discount;
                $loyalty->expire_after          = $request->expire_after;
                $loyalty->created               = Auth::user()->id;
                $loyalty->save();
            }else{
                $loyalty->store_id              = Auth::user()->store_id;
                $loyalty->cap_amount            = $request->cap_amount;
                $loyalty->points                = $request->points;
                $loyalty->redeem_rate           = $request->redeem_rate;
                $loyalty->max_reword_discount   = $request->max_reword_discount;
                $loyalty->expire_after          = $request->expire_after;
                $loyalty->updated               = Auth::user()->id;
                $loyalty->save();
            }

            return new Response(true,$loyalty);

        }catch(Exception $ex){
            return new Response(false,null,$ex->getMessage());
        }
    }
}
