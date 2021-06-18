<?php

namespace App\Requests\Catalogue\Supplier;
use App\Core\BaseRequest as BaseRequest;
use App\Core\Response;
use App\Models\Supplier;
use Auth;

class GetSupplierByIdRequest extends BaseRequest{

    public $id;

}
class GetSupplierByIdRequestHandler {

    public function Serve($request){
        $supplier = Supplier::find($request->id);

        $supplier_template = [
            'id' => $supplier->id,
            "name" => $supplier->name,
            "phone" => $supplier->phone,
            "mobile" => $supplier->mobile,
            'email' => $supplier->email,
            "address" => $supplier->address,
            "street" => $supplier->street,
            "latitude" => $supplier->latitude,
            "longitude" => $supplier->longitude,
            "active" => $supplier->active,

        ];



    	return new Response(true, $supplier_template);
    }
}
