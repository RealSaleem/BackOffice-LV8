<?php

namespace App\Http\Controllers\Api\Catalogue;

use Illuminate\Http\Request;
use App\Http\Controllers\Api\ApiController;
use App\Core\RequestExecutor;
use App\Requests\Catalogue\Supplier\GetAllSupplierRequest;
use App\Requests\Catalogue\Supplier\AddSupplierRequest;
use App\Requests\Catalogue\Supplier\ToggleSupplierRequest;
use App\Requests\Catalogue\Supplier\DeleteSupplierRequest;
use App\Requests\Catalogue\Supplier\BulkSupplierRequest;
use App\Requests\Catalogue\Supplier\EditSupplierRequest;
use Auth;


class SupplierController extends ApiController
{

    public function __construct(RequestExecutor $requestExecutor)
    {
        parent::__construct();
        $this->RequestExecutor = $requestExecutor;
        $this->middleware(['permission:list-supplier'])->only('getSuppliers');
        $this->middleware(['permission:add-supplier'])->only('addSupplier');
        $this->middleware(['permission:edit-supplier'])->only('updateSupplier');
        $this->middleware(['permission:delete-supplier'])->only('deleteSupplier');
    }

    public function getSuppliers(GetAllSupplierRequest $request)
    {
        $request->store_id = Auth::user()->store_id;
        $response = $this->RequestExecutor->execute($request);
        return response()->json($response->Payload);
    }

    public function addSupplier(AddSupplierRequest $request)
    {
        $request->store_id = Auth::user()->store_id;
        $response = $this->RequestExecutor->execute($request);
        return response()->json($response);
    }

    public function toggleSupplier(ToggleSupplierRequest $request)
    {
        $request->store_id = Auth::user()->store_id;
        $response = $this->RequestExecutor->execute($request);
        return response()->json($response);
    }

    public function deleteSupplier(DeleteSupplierRequest $request)
    {
        $request->store_id = Auth::user()->store_id;
        $response = $this->RequestExecutor->execute($request);
        return response()->json($response);
    }

    public function bulkSupplier(BulkSupplierRequest $request)
    {
        $request->store_id = Auth::user()->store_id;
        $response = $this->RequestExecutor->execute($request);
        return response()->json($response);
    }

    public function updateSupplier(EditSupplierRequest $request)
    {
        $request->store_id = Auth::user()->store_id;
        $response = $this->RequestExecutor->execute($request);
        return response()->json($response);
    }
}
