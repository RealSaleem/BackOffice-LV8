<?php

namespace App\Modules\Product;
use App\Core\BaseRequest as BaseRequest;
use App\Core\Response;
use App\Models\Product as ProductModel;


class UpdateProductWebDisplayStatusRequest extends BaseRequest{
    public $id;
    public $web_display;
}

class UpdateProductWebDisplayStatusRequestValidator {

	public function GetRules(){
        return [
            'id' => 'required|integer',
			'web_display' => 'required|boolean'
        ];
    }


    public function IsValid($request){
        return new Response(true);
    }
}

class UpdateProductWebDisplayStatusRequestHandler {

    public function __construct(){
    }

    public function Serve($request){
        $product = ProductModel::find($request->id);
        $product->web_display = $request->web_display;
        $product->save();

        return new Response(true, $product);
    }
} 