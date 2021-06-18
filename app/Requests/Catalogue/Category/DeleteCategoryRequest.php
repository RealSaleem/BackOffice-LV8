<?php

namespace App\Requests\Catalogue\Category;
use App\Core\BaseRequest as BaseRequest;
use App\Core\Response;
use App\Models\Category as Category;

class DeleteCategoryRequest extends BaseRequest{
    public $store_id;
    public $category_id;
}

class DeleteCategoryRequestHandler {

    public function __construct(){
    }

    public function Serve($request){

        $category = Category::withCount('products')->find($request->category_id);

        $success = true;
        $errors = [];

        try{
            if($category->products_count == 0){
                $category->delete();
                $message  =\Lang::get('toaster.category_deleted');
            }else{
               $success = false;
               $message  =\Lang::get('toaster.category_softdeleted');
            }

        }catch(Exception $ex){
            $success = false;
        }

    	return new Response($success, $request,null,$errors,$message);
    }
}
