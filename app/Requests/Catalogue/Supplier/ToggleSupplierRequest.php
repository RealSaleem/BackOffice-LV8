<?php

namespace App\Requests\Catalogue\Supplier;

use App\Core\BaseRequest as BaseRequest;
use App\Core\Response;
use App\Models\Supplier;
use DB;

class ToggleSupplierRequest extends BaseRequest{

    public $id;
    public $type;
    public $store_id;
}

class ToggleSupplierRequestValidator{
    public function GetRules(){
        return [
            'name' => [
                'required|integer',
                'required|string',
            ],
        ];
    }
}

class ToggleSupplierRequestHandler {

    public function Serve($request){

        try {
            DB::beginTransaction();

            $supplier = Supplier::where(['id' => $request->id, 'store_id' => $request->store_id])->first();

            if($request->type == 'active'){

                $supplier->active = !$supplier->active;
            }

            $supplier->save();

            DB::commit();

            return new Response(true, $supplier,null,null,\Lang::get('toaster.supplier_toggle'));

        } catch (Exception $ex) {

            DB::rollBack();
            return new Response(false,null,$ex->getMessage());
        }
    }
}
