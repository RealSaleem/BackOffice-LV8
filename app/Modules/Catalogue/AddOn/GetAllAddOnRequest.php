<?php

namespace App\Modules\Catalogue\AddOn;
use App\Core\BaseRequest as BaseRequest;
use App\Core\Response;
use App\Models\AddOn;
use Auth;

class GetAllAddOnRequest extends BaseRequest{
    public $store_id;
}

class GetAllAddOnRequestHandler {

    public function __construct(){
    }

    public function Serve($request){
        $locale = \App::getLocale();
        
        if(is_null($locale)){
            $locale = 'en';
        }
        $add_ons = AddOn::with(['items'])->where('store_id',Auth::user()->store_id)->withCount('items')->where('language_key' ,$locale)->get();

        return new Response(true, $add_ons);
    }
} 