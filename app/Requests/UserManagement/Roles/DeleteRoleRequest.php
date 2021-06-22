<?php

namespace App\Requests\UserManagement\Roles;

use App\Core\BaseRequest as BaseRequest;
use App\Core\Response;
use App\Models\Permission_role;
//use App\Models\Role;
use App\Models\RolePermission;
use Spatie\Permission\Models\Role;
use App\Models\User;
use App\Models\UserRole;
use Illuminate\Support\Facades\DB;
use Auth;

class DeleteRoleRequest extends BaseRequest
{

    public $role_id;

}

class DeleteRoleRequestHandler
{

    public function __construct()
    {
    }

    public function Serve($request)
    {

        $role_id = $request->role_id;
        $role = Role::find($role_id);
        $users = User::role($role->name)->get();
        try {
            if ($users->count() > 0) {
                $success = false;
                $errors = [\Lang::get('toaster.role_user_exist')];
                return new Response($success, $request, null, $errors);
            }else{
                $role->syncPermissions();
                $role->delete();
                $success = true;

            }

            return new Response($success, $request, null, null, \Lang::get('toaster.role_deleted'));

        } catch (Exception $ex) {
            $success = false;
        }

        return new Response($success, $request, null, $errors);
    }
}
