<?php

namespace App\Modules\Catalogue\Item;
use App\Core\BaseRequest as BaseRequest;
use App\Core\Response;


class AddItemRequest extends BaseRequest{

    public $id;
	public $category_id;
    public $supplier_id;




}

class AddItemRequestValidator {
    public function GetRules(){
        return [
//            'name' => [
//                'required',
//                'string',
//                'min:3',
//                Rule::unique('products')->where(function ($query) {
//                    return $query->where('store_id', Auth::user()->store_id);
//                })->whereNull('deleted_at'),
//            ],

        ];
    }


}
class AddItemRequestHandler {
    public function __construct()
    {

    }

    public function Serve($request){
dd($request);
        try {

            DB::commit();
            $product->Message = 'Product has been added successfully';
        	return new Response(true,$product);

        } catch (Exception $ex) {
            DB::rollBack();
            return new Response(false,null,$ex->getMessage());
        }
    }

}
