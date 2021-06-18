<?php

namespace App\Requests\Backoffice\Catalogue\Category;
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
        $category = Category::withCount('products')->find($request->id);
        $success = false;
        $errors = [];
        
        try{
            if($category->products_count == 0){
                $success = true;
                $category->is_deleted = true;
                $category->save();
            }else{
                $errors = ['error' => 'Unable to delete assigned category'];
            }

        }catch(Exception $ex){
            $success = false;
        }

    	return new Response($success, $request,null,$errors);
    }
} 