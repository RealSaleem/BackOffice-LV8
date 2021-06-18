<?php

namespace App\Modules\Product;
use App\Core\BaseRequest as BaseRequest;
use App\Core\Response;
use App\Models\Product as ProductModel;


class UpdateProductStatusRequest extends BaseRequest{
    public $id;
    public $active;
}

class UpdateProductStatusRequestValidator {

	public function GetRules(){
        return [
            'id' => 'required|integer',
			'active' => 'required|boolean'
        ];
    }


    public function IsValid($request){
        return new Response(true);
    }
}

class UpdateProductStatusRequestHandler {

    public function __construct(){
    }

    public function Serve($request){
        $product = ProductModel::find($request->id);
        $product->active = $request->active;
        $product->save();

        return new Response(true, $product);
    }
} 