<?php

namespace App\Modules\Product;
use App\Core\BaseRequest as BaseRequest;
use App\Core\Response;
use Illuminate\Validation\Rule;
use App\Models\ProductLanguage;

use Auth;
use DB;
use Request;

class AddProductLanguageRequest extends BaseRequest{

    public $name;
    public $key;
    public $description;
    public $product_id;
}

class AddProductLanguageRequestValidator {
    public function GetRules(){
        return [
            'name' => [
                'required',
                'string',
                'min:3',
                // Rule::unique('products_language')->where(function ($query) {
                //     return $query->where([
                //         'product_id' => Request::get('product_id'), 
                //         'key' => Request::get('key')
                //     ]);
                // }),                
            ],
            'description' => 'required|string',
            'key'      => 'required',
            'product_id' => 'required|numeric',
        ];
    }
}


class AddProductLanguageRequestHandler 
{
    public function Serve($request)
    {

        try{

            DB::beginTransaction();

            $row = ProductLanguage::where(['product_id' => $request->product_id,'key' => $request->key])->first();

            if(!(isset($row) && !is_null($row))){
                $row = new ProductLanguage;
            }

            $row->name = $request->name;
            $row->description = $request->description;
            $row->product_id = $request->product_id;
            $row->key = $request->key;

            $row->save();

            DB::commit();

            return new Response(true, array($request));
            
        }catch(Exception $ex){
            DB::rollback();
            return new Response(false, null,$ex->getMessage());
        }
    }
}