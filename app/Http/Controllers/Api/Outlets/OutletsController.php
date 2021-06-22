<?php

namespace App\Http\Controllers\Api\Outlets;

use Illuminate\Http\Request;
use App\Core\RequestExecutor;
use Illuminate\Support\Facades\Auth;
use App\Http\Controllers\Api\ApiController;
use App\Requests\Outlets\AddOutletsRequest;
use App\Requests\Outlets\EditOutletsRequest;
use App\Requests\Outlets\GetAllOutletsRequest;



class OutletsController extends ApiController
{

    public function __construct(RequestExecutor $requestExecutor)
    {
         parent::__construct();
        $this->RequestExecutor = $requestExecutor;
        $this->middleware(['permission:list-outlet'])->only('getOutlets','show');
        $this->middleware(['permission:add-outlet'])->only('addOutlets');
        $this->middleware(['permission:edit-outlet'])->only('updateOutlets');
    }

    public function getOutlets(GetAllOutletsRequest $request)
    {
        $request->store_id = Auth::user()->store_id;
        $response = $this->RequestExecutor->execute($request);
        return response()->json($response->Payload);
    }
    public function toggleOutlets(ToggleOutletsRequest $request)
    {
        $request->store_id = Auth::user()->store_id;
        $response = $this->RequestExecutor->execute($request);
        return response()->json($response->Payload);
    }

    public function addOutlets(AddOutletsRequest $request)
    {
        $request->user = Auth::user();
        $response = $this->RequestExecutor->execute($request);
        return response()->json($response);
    }

    public function updateOutlets(EditOutletsRequest $request){
        $request->store_id = Auth::user()->store_id;
        $request->user = Auth::user();
        $response = $this->RequestExecutor->execute($request);
        return response()->json($response);
    }
}
