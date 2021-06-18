<?php

namespace App\Requests\Catalogue\AddOn;

use App\Core\BaseRequest as BaseRequest;
use App\Core\Response;
use App\Models\AddOn;
use DB;

class ToggleAddOnRequest extends BaseRequest
{

    public $id;
    public $type;
    public $store_id;
}

class ToggleAddOnRequestValidator
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

class ToggleAddOnRequestHandler
{

    public function Serve($request)
    {

        try {
            DB::beginTransaction();

            $addons = AddOn::where(['id' => $request->id, 'store_id' => $request->store_id])->first();

            if ($request->type == 'active') {
                $addons->is_active = !$addons->is_active;
            }

            $addons->save();


            DB::commit();
            $data = [
                'id' => $addons->id,
                'name' => $addons->name,
                'type' => $addons->name,
                'count' => 0,
                'actions' => '',
            ];

            return new Response(true, $addons, null,null, \Lang::get('toaster.addon_updated'));

        } catch (Exception $ex) {

            DB::rollBack();
            return new Response(false, null, $ex->getMessage());
        }
    }
}
