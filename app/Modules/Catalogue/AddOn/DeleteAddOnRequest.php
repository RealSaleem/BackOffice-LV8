<?php

namespace App\Modules\Catalogue\AddOn;
use App\Core\BaseRequest as BaseRequest;
use App\Core\Response;
use App\Models\AddOn;
use App\Models\AddOnTranslation;
use App\Models\AddOnItems;
use App\Models\AddOnItemsTranslation;
use Auth;

class DeleteAddOnRequest extends BaseRequest{

    public $id;

}

class DeleteAddOnRequestHandler {

    public function __construct(){
    }

    public function Serve($request){

        $add_on = AddOn::where('store_id' , Auth::user()->store_id)->find($request->id);


        if(isset($add_on)){
          // $AddOnTranslation = AddOnTranslation::where('add_on_id' , $request->id)->delete();
          $AddOnItems = AddOnItems::where('add_on_id' , $request->id)->delete();

            $add_on->item_Product()->detach();
          // $AddOnItemsTranslation = AddOnItemsTranslation::where('add_on_id' , $request->id)->delete();
          // // foreach ($add_on as $add) {
          //   if(isset($add->transalation)){
          //     foreach ($add->transalation as $trans) {
          //       $trans->delete();
          //     }
          //   }

          //   if(isset($add->items)){
          //     foreach ($add->items as $item) {
          //       if(isset($item->item_transalation)){
          //         foreach ($item->item_transalation as $tran) {
          //           $tran->delete();
          //         }
          //       }
          //       $item->delete();
          //     }
          //   }

          // }
          if($add_on->delete()){
             return new Response( true,null,null,null,'AddOn deleted Successfully');
          }else{
    	       return new Response( false,null,null,'Unable to delete AddOn');
          }
        }else{
          return new Response( false,null,null,'Unable to delete AddOn');
        }
    }
}
