<?php

namespace App\Http\Controllers\Api\Catalogue;

use Illuminate\Http\Request;
use App\Http\Controllers\Api\ApiController;
use App\Core\RequestExecutor;
use App\Requests\Catalogue\AddOn\GetAllAddOnRequest;
use App\Requests\Catalogue\AddOn\AddAddOnRequest;
use App\Requests\Catalogue\AddOn\UpdateAddOnRequest;

use Auth;


class VariantController extends ApiController
{

    public function __construct(RequestExecutor $requestExecutor)
    {
        parent::__construct();
        $this->RequestExecutor = $requestExecutor;
    }

    public function getAddOn(GetAllAddOnRequest $request)
    {
        $request->store_id = Auth::user()->store_id;
        $response = $this->RequestExecutor->execute($request);
        return response()->json($response->Payload);
    }

    public function updateAddOn(UpdateAddOnRequest $request){
        $request->store_id = Auth::user()->store_id;
        $response = $this->RequestExecutor->execute($request);
        $response->Message = \Lang::get('addon.addon_updated_successfully'); 
        return response()->json($response);
    }
}