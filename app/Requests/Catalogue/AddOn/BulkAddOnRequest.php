<?php

namespace App\Requests\Catalogue\AddOn;

use App\Core\BaseRequest as BaseRequest;
use App\Core\Response;
use App\Models\AddOn;
use DB;
use Auth;

class BulkAddOnRequest extends BaseRequest{
    public $store_id;
    public $type;
    public $addon;
    public $action;
}

class BulkAddOnRequestHandler {

    public function __construct(){
    }

    public function Serve($request){

        try {
            DB::beginTransaction();

            $AddOns = AddOn::where(['store_id' => $request->store_id])->whereIn('id',$request->addon);

            $is_active = $request->action === 'true' ? 1 : 0;
            $data = "";
            $Message = "";
            $success = "";

             if($request->type == 'active') {
                 $data = [
                     'is_active' => $is_active
                 ];
                 $AddOns->update($data);
                 $success = true;
                 $Message = \Lang::get('toaster.addon_updated');
             }
             if($request->type == 'delete'){
                 foreach ($AddOns->withCount('items')->get() as $key => $AddOn) {


                     if($AddOn->items_count > 0){
                         $success = false;
                         $Message = \Lang::get('toaster.addon_item_exist');
                         continue;
                     }else{
                         $data = [
                             'is_delete' => 1,
                             'is_active' => 0
                         ];
                         $AddOn->update($data);
                         $AddOn->delete();
                         $success = true;
                         $Message = \Lang::get('toaster.addon_deleted');
                     }

                 }


//                 $addon = AddOn::with(['items'])->where(['store_id' => $request->store_id,'id' => $request->addon])->withCount('items')->first();

//                 if( $addon->items_count = 0){
//
//                     $data = [
//                         'is_delete' => 1,
//                         'is_active' => 0
//                     ];
//                     $AddOn->update($data);
//                     $AddOn->delete();
//                     $success = true;
//                     $Message = \Lang::get('toaster.addon_deleted');
//                 }else{
//                     $success = false;
//                     $Message = \Lang::get('toaster.addon_item_exist');
//                 }


             }

            DB::commit();
            return new Response($success, null, null, null, $Message);

        } catch (\Exception $ex) {
            DB::rollBack();
            return new Response(false, null, null, $ex->getMessage(), null);
        }
    }
}
