<?php

namespace App\Modules\Catalogue\Category;
use App\Core\BaseRequest as BaseRequest;
use App\Core\Response;
use App\Models\Category as Category;

class DeleteCategoryRequest extends BaseRequest{
   
    public $id;
   
}

class DeleteCategoryRequestHandler {

    public function __construct(){
    }

    public function Serve($request){
        $category = Category::with('products')->withCount('products')->find($request->id);
        $success = false;
        $errors = [];
        
        try{
            $success = true;
            $category->is_deleted = true;
            $category->save();

            $general_category = Category::where(['name' => 'General', 'store_id' => \Auth::user()->store_id])->first();

            if(isset($general_category)){

                if(sizeof($category->products) > 0){
                    foreach($category->products as $product){

                        $product->category_id = $general_category->id;
                        $product->save();
                    }
                }
            }

        }catch(Exception $ex){
            $success = false;
        }

    	return new Response($success, $request,null,$errors);
    }
} 