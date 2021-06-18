<?php

namespace App\Modules\Catalogue\Brand;
use App\Core\BaseRequest as BaseRequest;
use App\Core\Response;
use App\Models\Brand as Brand;

class DeleteBrandRequest extends BaseRequest{
   
    public $id;
   
}

class DeleteBrandRequestHandler {

    public function __construct(){
    }

    public function Serve($request){
        $Brand = Brand::with('products')->withCount('products')->find($request->id);
        $success = false;
        $errors = [];
        
        try{
            $success = true;
            $Brand->is_deleted = true;
            $Brand->save();

            $general_Brand = Brand::where(['name' => 'General', 'store_id' => \Auth::user()->store_id])->first();
            if(isset($general_Brand)){

                if(sizeof($Brand->products) > 0){
                    foreach($Brand->products as $product){

                        $product->Brand_id = $general_Brand->id;
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