<?php

namespace App\Requests\Catalogue\AddOn;
use App\Core\BaseRequest as BaseRequest;
use App\Core\Response;
use App\Models\AddOn;
use App\Models\AddOnItems;
use Auth;

class DeleteAddOnRequest extends BaseRequest{
    public $id;
}

class DeleteAddOnRequestHandler {

    public function __construct(){
    }

    public function Serve($request){

        $add_on = AddOn::where('store_id' , $request->store_id)->find($request->id);

        if($add_on != null){
          $AddOnItems = AddOnItems::where('add_on_id' , $request->id)->delete();

          $add_on->item_Product()->detach();

          if($add_on->delete()){
             return new Response( true,null,null,null,   \Lang::get('toaster.addon_deleted'));
          }else{
             return new Response( false,null,null,'Unable to delete AddOn');
          }
        }else{
          return new Response( false,null,null,'Unable to delete AddOn');
        }
    }
}
