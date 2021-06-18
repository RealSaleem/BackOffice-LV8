<?php

namespace App\Requests\Catalogue\AddOn;
use App\Core\BaseRequest as BaseRequest;
use App\Core\Response;
use App\Models\AddOn;
use App\Models\Product;
use App\Models\ProductTag;
use App\Models\ProductVariant;
use Auth;
use Request;
use View;

class GetAddOnForExportRequest extends BaseRequest{
    public $store_id;
    public $lang;
}

class GetAddOnForExportRequestValidatosr {
    public function IsValid($request){
        return new Response(true);
    }
}

class GetAddOnForExportRequestHandler {

    public function __construct(){
    }

    public function Serve($request)
	{
        $addons = AddOn::where('store_id' , Auth::user()->store_id)->orderBy('id','asc')->get();

        return new Response(true, $addons);
    }
}
