<?php

namespace App\Http\Controllers\Api;

use Illuminate\Http\Request;
use App\Http\Controllers\Controller;
use App\Core\RequestExecutor;
use App\Core\Response;

use App\Requests\Store\CreateStoreRequest;
use App\Requests\Store\EditStoreRequest;
use App\Helpers\ImageUploader;
use Auth;
use App\Models\Language;

class StoreController extends Controller
{
    public function __construct(RequestExecutor $requestExecutor){
        parent::__construct();
        $this->RequestExecutor = $requestExecutor;
    }

    public function update(EditStoreRequest $request)
    {
		$response = $this->RequestExecutor->execute($request);
        return response()->json($response);
    }

    public function step1(Request $request)
    {
        $request->image = null;
        if($request->hasFile('image') || $request->hasFile('images')) {
            $response = ImageUploader::uploadGeneral($request);
            $request->image = $response;
        }

        $param = $request->all();
        $store_request  = new CreateStoreRequest();
        $store_request->name                        = $param['name'];
        $store_request->contact_name                = Auth::user()->name;
        $store_request->email                       = Auth::user()->email;
        $store_request->industry_id                 = $param['industry_id'];
        $store_request->default_currency            = $param['default_currency'];
        $store_request->address                     = $param['address'];
        $store_request->longitude                   = $param['longitude'];
        $store_request->latitude                    = $param['latitude'];
        $store_request->store_logo                  = $request->image;
        $store_request->sku_generation              = 1;
        $store_request->current_sequence_number     = 1000;
        $store_request->round_off                   = 3;
        $store_request->language_ids                = Language::get()->pluck('id')->toArray();

        $response = $this->RequestExecutor->execute($store_request);
        return response()->json($response);
    }
}
