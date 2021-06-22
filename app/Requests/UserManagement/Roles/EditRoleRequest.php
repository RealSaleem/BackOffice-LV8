<?php

namespace App\Requests\UserManagement\Roles;
use App\Core\BaseRequest as BaseRequest;
use App\Core\Response;
use App\Models\Permission;
use App\Models\Permission_role;
//use App\Models\Role;
use Spatie\Permission\Models\Role;

use App\Models\UsersStore;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;

class EditRoleRequest extends BaseRequest{

    public $name;
    public $level;
    public $scope;
    public $permissions;
    public $id;
    public $display_name;
    public $description;
}

class EditRoleRequestValidator{
    public function GetRules(){
        return [
            'name'          => 'required|string|max:225',
//            'display_name'  => 'required|string|max:10',
//            'description'   => 'required|string|max:11',
        ];
    }
}

class EditRoleRequestHandler {

    public function __construct(){
    }


    public function Serve($request)
    {
//        dd($request);
        try{

            DB::beginTransaction();
            $role = Role::find($request->id);
            $role->name             = strtolower($request->name);
            $role->display_name     = $request->name;
            $role->save();

            if(strtolower($role->name) != 'admin'){
                if ($request->permissions !== null) {
                    $role->syncPermissions($request->permissions);
                }
            }

            DB::commit();
            return new Response(true, $role, null, null, \Lang::get('toaster.role_updated'));

        } catch (\Exception $ex) {
            DB::rollBack();
            return new Response(false, null, null, $ex->getMessage(), null);
        }


    }

}
