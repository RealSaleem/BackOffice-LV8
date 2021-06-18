<?php

namespace App\Http\Controllers\Api\Customers;

use Illuminate\Http\Request;
use App\Http\Controllers\Api\ApiController;
use App\Core\RequestExecutor;
use App\Requests\CustomerGroup\GetAllCustomerGroupRequest;
use App\Requests\CustomerGroup\AddCustomerGroupRequest;
use App\Requests\CustomerGroup\DeleteCustomerGroupRequest;
use App\Requests\CustomerGroup\BulkCustomerGroupRequest;
use App\Requests\CustomerGroup\EditCustomerGroupRequest;

use Auth;

class CustomerGroupController extends ApiController
{

    public function __construct(RequestExecutor $requestExecutor)
    {
        parent::__construct();
        $this->RequestExecutor = $requestExecutor;
    }

    public function getCustomerGroup(GetAllCustomerGroupRequest $request){
        $request->store_id = Auth::user()->store_id;
        $response = $this->RequestExecutor->execute($request);

        return response()->json($response->Payload);
    }
    public function addCustomerGroup( AddCustomerGroupRequest $request){
        $request->store_id = Auth::user()->store_id;
        $response = $this->RequestExecutor->execute($request);
         return response()->json($response);

    }

    public function deleteCustomerGroup(DeleteCustomerGroupRequest $request){
        $request->store_id = Auth::user()->store_id;
        $response = $this->RequestExecutor->execute($request);
        return response()->json($response);
    }


    public function bulkCustomerGroup(BulkCustomerGroupRequest $request){
        $request->store_id = Auth::user()->store_id;
        $response = $this->RequestExecutor->execute($request);
        return response()->json($response);
    }

    public function updateCustomerGroup(EditCustomerGroupRequest $request){
        $request->store_id = Auth::user()->store_id;
        $response = $this->RequestExecutor->execute($request);
        return response()->json($response);
    }

}
