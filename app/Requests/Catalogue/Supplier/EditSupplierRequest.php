<?php

namespace App\Requests\Catalogue\Supplier;

use App\Core\BaseRequest as BaseRequest;
use App\Core\Response;
use Illuminate\Validation\Rule;
use App\Models\Supplier;
use Auth;
use Illuminate\Support\Facades\DB;

class EditSupplierRequest extends BaseRequest
{

    public $id;
    public $name;
    public $mobile;
    public $phone;
    public $email;
    public $address;
    public $latitude;
    public $longitude;
    public $active;
    public $store_id;
    public $updated;
}

class EditSupplierRequestValidator
{
    public function GetRules($request)
    {
        return [
            'name' => [
                'required',
                'string',
                'min:3',
            ],
            'mobile' => [
                'required',
                Rule::unique('suppliers')->where(function ($query) use ($request) {
                    return $query->where('store_id', $request->store_id)->where('is_deleted', 0)->orWhere('is_deleted', null);
                })->ignore($request->id),
            ],
            'email' => [
                'required',
                'string',
                'max:100',
            ],
            'street' => [
                'required',
                'string',
            ],
        ];
    }
}

class EditSupplierRequestHandler
{

    public function __construct()
    {
    }

    public function Serve($request)
    {
//        dd($request);
        try {
            DB::beginTransaction();

            $editSupplier = Supplier::find($request->id);
            $editSupplier->name = $request->name;
            $editSupplier->mobile = $request->mobile;

            $editSupplier->email = $request->email;
            $editSupplier->phone = $request->phone;
            $editSupplier->street = $request->address;
//        $editSupplier->latitude     = $request->latitude;
//        $editSupplier->longitude     = $request->longitude;
            $editSupplier->active = $request->active;


            $editSupplier->save();


            DB::commit();
            return new Response(true, $editSupplier, null, null, \Lang::get('toaster.supplier_updated'));
        } catch (\Exception $ex) {
            DB::rollBack();
            return new Response(false, null, null, $ex->getMessage(), null);
        }
    }
}
