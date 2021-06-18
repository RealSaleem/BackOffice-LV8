<?php

namespace App\Requests\Category;
use App\Core\BaseRequest as BaseRequest;
use App\Core\Response;
use App\Models\Category;
use Auth;

class GetSubCategoryRequest extends BaseRequest{
    public $category_id;
}

class GetSubCategoryRequestHandler {

    public function __construct(){
    }

    public function Serve($request){

    	$user = \App::make('user');

    	$where = [
    		'parent_id' => $request->category_id,
    		'store_id' => $user->store_id,
    		'is_deleted' => false
    	];

        $categories = Category::where($where)->get();
        
        return new Response(true, $categories);
    }
} 