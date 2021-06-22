<?php

namespace App\Http\Controllers\Api\Catalogue;

use Illuminate\Http\Request;
use App\Http\Controllers\Api\ApiController;
use App\Core\RequestExecutor;
use App\Requests\Catalogue\AddOn\GetAllAddOnRequest;
use App\Requests\Catalogue\AddOn\AddAddOnRequest;
use App\Requests\Catalogue\AddOn\UpdateAddOnRequest;
use App\Requests\Catalogue\AddOn\ToggleAddOnRequest;
use App\Requests\Catalogue\AddOn\DeleteAddOnRequest;
use App\Requests\Catalogue\AddOn\BulkAddonRequest;

use Auth;


class AddOnController extends ApiController
{

    public function __construct(RequestExecutor $requestExecutor)
    {
        parent::__construct();
        $this->RequestExecutor = $requestExecutor;
        $this->middleware(['permission:list-addons'])->only('getAddOn');
        $this->middleware(['permission:add-addons'])->only('addAddOn');
        $this->middleware(['permission:edit-addons'])->only('updateAddOn');
        $this->middleware(['permission:delete-addons'])->only('deleteAddon');
    }

    public function getAddOn(GetAllAddOnRequest $request)
    {
        $request->store_id = Auth::user()->store_id;
        $response = $this->RequestExecutor->execute($request);
        return response()->json($response->Payload);
    }
    public function toggleAddOn(ToggleAddOnRequest $request)
    {
        $request->store_id = Auth::user()->store_id;
        $response = $this->RequestExecutor->execute($request);
        return response()->json($response);
    }

    public function addAddOn(AddAddOnRequest $request){
        $request->store_id  = Auth::user()->store_id;
        $response = $this->RequestExecutor->execute($request);
        return response()->json($response);
    }

    public function updateAddOn(UpdateAddOnRequest $request){
        $request->store_id = Auth::user()->store_id;
        $response = $this->RequestExecutor->execute($request);
        return response()->json($response);
    }


    public function deleteAddon(DeleteAddOnRequest $request){
        $request->store_id = Auth::user()->store_id;
        $response = $this->RequestExecutor->execute($request);
        return response()->json($response);
    }

    public function bulkAddon(BulkAddonRequest $request)
    {
        $request->store_id = Auth::user()->store_id;
        $response = $this->RequestExecutor->execute($request);
        return response()->json($response);
    }
}





