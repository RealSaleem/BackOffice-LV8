<?php

namespace App\Requests\Brands;
use App\Core\BaseRequest as BaseRequest;
use App\Core\Response;
use App\Models\Brands;
use Auth;

class GetSubBrandsRequest extends BaseRequest{
    public $brands_id;
}

class GetSubBrandsRequestHandler {

    public function __construct(){
    }

    public function Serve($request){

    	$user = \App::make('user');

    	$where = [
    		'brand_id' => $request->brands_id,
    		'store_id' => $user->store_id,
    		'is_deleted' => false
    	];

        $brands = Brands::where($where)->get();
        
        return new Response(true, $brands);
    }
} 