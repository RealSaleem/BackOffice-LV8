<?php

namespace App\Modules\Catalogue\Product;
use App\Core\BaseRequest as BaseRequest;
use App\Core\Response;
use App\Models\ProductCategories as ProductCategories;
use Auth;
class GetCategoriesRequest extends BaseRequest{
   
    public $id;
    
}
class GetCategoriesRequestHandler {

    public function Serve($request){
        $categories = ProductCategories::where('product_id',$request->id)->get()->toArray();
    

    	return new Response(true, $categories);
    }
} 