<?php

namespace App\Http\Controllers\Api\Customers;

use App\Models\WebUser;
use Illuminate\Http\Request;
use App\Http\Controllers\Api\ApiController;
use App\Core\RequestExecutor;
use App\Requests\Customer\GetAllCustomerRequest;
use App\Requests\Customer\AddCustomerRequest;
use App\Requests\Customer\EditCustomerRequest;
use App\Http\Requests\Customer\GetAllECommerceCustomerRequest;

use Auth;

class
CustomerController extends ApiController
{

    public function __construct(RequestExecutor $requestExecutor)
    {
        parent::__construct();
        $this->RequestExecutor = $requestExecutor;
        $this->middleware(['permission:list-customer'])->only('getCustomers');
        $this->middleware(['permission:add-customer'])->only('addCustomer');
        $this->middleware(['permission:edit-customer'])->only('updateCustomer');
    }

    public function getCustomers(GetAllCustomerRequest $request)
    {
        $request->store_id = Auth::user()->store_id;
        $response = $this->RequestExecutor->execute($request);
        return response()->json($response->Payload);
    }

    public function toggleCustomer(ToggleCustomerRequest $request)
    {
        $request->store_id = Auth::user()->store_id;
        $response = $this->RequestExecutor->execute($request);
        return response()->json($response->Payload);
    }

    public function addCustomer(AddCustomerRequest $request){
        $request->store_id = Auth::user()->store_id;
        $response = $this->RequestExecutor->execute($request);

         return response()->json($response);
    }


    public function updateCustomer(EditCustomerRequest $request){
        $request->store_id = Auth::user()->store_id;
        $response = $this->RequestExecutor->execute($request);
        return response()->json($response);
    }
}
