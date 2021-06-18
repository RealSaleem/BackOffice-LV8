<?php

namespace App\Modules\Product;
use App\Core\BaseRequest as BaseRequest;
use App\Core\Response;
use App\Models\Product as ProductModel;


class UpdateProductDineInDisplayStatusRequest extends BaseRequest{
    public $id;
    public $dinein_display;
}

class UpdateProductDineInDisplayStatusRequestValidator {

	public function GetRules(){
        return [
            'id' => 'required|integer',
			'dinein_display' => 'required|boolean'
        ];
    }


    public function IsValid($request){
        return new Response(true);
    }
}

class UpdateProductDineInDisplayStatusRequestHandler {

    public function __construct(){
    }

    public function Serve($request){
        $product = ProductModel::find($request->id);
        $product->dinein_display = $request->dinein_display;
        $product->save();

        return new Response(true, $product);
    }
} 