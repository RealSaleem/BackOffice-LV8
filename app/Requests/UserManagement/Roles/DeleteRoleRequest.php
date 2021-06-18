<?php

namespace App\Requests\UserManagement\Roles;
use App\Core\BaseRequest as BaseRequest;
use App\Core\Response;
use App\Models\Permission_role;
use App\Models\Role;
use App\Models\UserRole;
use Illuminate\Support\Facades\DB;

class DeleteRoleRequest extends BaseRequest{

    public $role_id;

}

class DeleteRoleRequestHandler {

    public function __construct(){
    }

    public function Serve($request)
    {
        $role_id = $request->role_id;
        $check_role = UserRole::where('role_id', $role_id)->first();

        $success = false;
        $errors = [];

        if ($check_role != null) {
            $success = false;
            $errors = [\Lang::get('toaster.role_user_exist')];
            return new Response($success, $request, null, $errors);
        } else {
            try {
                $role = Role::find($role_id);
               $result =  $role->delete();
                $role->permissions()->detach();
                $success = true;
                return new Response($success, $request, null, null,\Lang::get('toaster.role_deleted'));

            } catch (Exception $ex) {
                $success = false;
            }
        }
        return new Response($success, $request, null, $errors);
    }
}
