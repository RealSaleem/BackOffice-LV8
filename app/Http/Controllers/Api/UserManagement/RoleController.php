<?php

namespace App\Http\Controllers\Api\UserManagement;

use App\Http\Requests\Usermanegment\Roles\UpdateRoleRequest;
use App\Models\Abilities;
use App\Models\Permission_role;
use App\Models\Store;
use Illuminate\Support\Facades\Validator;
use Illuminate\Validation\Rule;
use Spatie\Permission\Models\Role;
use App\Models\UserRole;
use Illuminate\Http\Request;
use Spatie\Permission\Models\Permission;
use App\Http\Controllers\Api\ApiController;
use App\Core\RequestExecutor;
use App\Requests\UserManagement\Roles\GetAllRoleRequest;
use App\Requests\UserManagement\Roles\AddRoleRequest;
use App\Requests\UserManagement\Roles\EditRoleRequest;
use App\Requests\UserManagement\Roles\DeleteRoleRequest;
use App\Requests\UserManagement\Roles\GetAllPermissionsRequest;
use App\Requests\UserManagement\Roles\AssignPermissionRequest;
use Silber\Bouncer\BouncerFacade as Bouncer;

use Auth;
use Illuminate\Support\Facades\DB;

class RoleController extends ApiController
{

    public function __construct(RequestExecutor $requestExecutor)
    {
        parent::__construct();
        $this->RequestExecutor = $requestExecutor;
    }

    public function getRoles(GetAllRoleRequest $request)
    {
        $request->store_id = Auth::user()->store_id;
        $response = $this->RequestExecutor->execute($request);
        return response()->json($response->Payload);
    }

    public function store(AddRoleRequest $request)
    {
        $request->store_id = Auth::user()->store_id;
        $response = $this->RequestExecutor->execute($request);
        return response()->json($response);
    }


    public function updateRole(EditRoleRequest $request)
    {
        $request->store_id = Auth::user()->store_id;
        $response = $this->RequestExecutor->execute($request);
        return response()->json($response);
    }

    public function deleteRole(DeleteRoleRequest $request)
    {
        $request->store_id = Auth::user()->store_id;
        $response = $this->RequestExecutor->execute($request);
        return response()->json($response);
    }




}
