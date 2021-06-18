<?php

namespace App\Modules\Product;
use App\Core\BaseRequest as BaseRequest;
use App\Core\Response;
use App\Models\Product as ProductModel;

class GetStoreProductsRequest extends BaseRequest{
    public $id;
}

class GetStoreProductsRequestValidatosr {
    public function IsValid($request){
        return new Response(true);
    }
} 

class GetStoreProductsRequestHandler {
	//private $model = new App\Models\Product();
    public function __construct(){
    }

    public function Serve($request){
        $model = new ProductModel();
        $v = $model->find(3)->product_variants;
    	return new Response(true, $v);
    }
} 