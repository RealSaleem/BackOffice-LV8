<?php

namespace App\Modules\Brand;
use App\Core\BaseRequest as BaseRequest;
use App\Core\Response;
use App\Models\Brand;
use Auth;

class GetAllBrandRequest extends BaseRequest{
    public $store_id;
}

class GetAllBrandRequestHandler {

    public function __construct(){
    }

    public function Serve($request){
    	$login_user = is_null(Auth::user())? \App::make('user') : Auth::user();
        $Brands = Brand::where('store_id',$login_user->store_id)->withCount('products')->get();
        return new Response(true, $Brands);
    }
} 