<?php

namespace App\Requests\Catalogue\Brands;

use App\Core\BaseRequest as BaseRequest;
use App\Core\Response;
use App\Models\Brand;
use DB;

class ToggleBrandsRequest extends BaseRequest
{

    public $id;
    public $type;
    public $store_id;
}

class ToggleBrandsRequestValidator
{
    public function GetRules()
    {
        return [
            'name' => [
                'required|integer',
                'required|string',
            ],
        ];
    }
}

class ToggleBrandsRequestHandler
{

    public function Serve($request)
    {
//dd($request);
        try {
            DB::beginTransaction();

            $brands = Brand::where(['id' => $request->id, 'store_id' => $request->store_id])->first();

            if ($request->type == 'active') {
                if ($brands->active == 0 || $brands->active == null) {
                    $brands->active = 1;
                } else {
                    $brands->active = 0;
                }

            }
            if ($request->type == 'feature') {
                if ($brands->is_featured == 0 || $brands->is_featured == null) {
                    $brands->is_featured = 1;
                } else {
                    $brands->is_featured = 0;
                }
            }

            $brands->save();

            DB::commit();

            $data = [
                'id' => $brands->id,
                'name' => $brands->name,
                'number_of_products' => 0,
                'actions' => '',
            ];

            return new Response(true, $brands, null, null, \Lang::get('toaster.brand_toggle'));

        } catch (Exception $ex) {

            DB::rollBack();
            return new Response(false, null, $ex->getMessage());
        }
    }
}
