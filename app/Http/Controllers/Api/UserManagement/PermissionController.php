<?php

namespace App\Http\Controllers\Api\UserManagement;

use Illuminate\Http\Request;
use App\Http\Controllers\Api\ApiController;
use App\Core\RequestExecutor;
use App\Requests\UserManagement\Permission\GetAllPermissionRequest;
use App\Requests\UserManagement\Permission\AddPermissionRequest;
use App\Requests\UserManagement\Permission\EditPermissionRequest;
use App\Requests\UserManagement\Permission\DeletePermissionRequest;

use Auth;

class PermissionController extends ApiController
{
    public function __construct(RequestExecutor $requestExecutor)
    {
        parent::__construct();
        $this->RequestExecutor = $requestExecutor;
    }

    public function getPermission(GetAllPermissionRequest $request)
    {
        $request->store_id = Auth::user()->store_id;
        $response = $this->RequestExecutor->execute($request);
        return response()->json($response->Payload);
    }
    public function addPermission(AddPermissionRequest $request)
    {
        $response = $this->RequestExecutor->execute($request);
         return response()->json($response);
    }
    public function updatePermission(EditPermissionRequest $request)
    {
            $request->store_id = Auth::user()->store_id;
            $response = $this->RequestExecutor->execute($request);
            $response->Message = \Lang::get('permission.permission_updated_successfully');
            return response()->json($response);
        }
    public function deletePermission(DeletePermissionRequest $request){
        $request->store_id = Auth::user()->store_id;
        $response = $this->RequestExecutor->execute($request);
        $response->Message = \Lang::get('role.delete_success_message');
        return response()->json($response);
    }
}
