<?php

namespace App\Requests\UserManagement\Roles;

use App\Core\BaseRequest as BaseRequest;

use Illuminate\Foundation\Auth\User as Authenticatable;
use Spatie\Permission\Traits\HasRoles;
use App\Core\Response;
//use App\Models\Role;
//use App\Models\Permission;
use Spatie\Permission\Models\Role;
use Spatie\Permission\Models\Permission;

use Illuminate\Support\Facades\Validator;
use Illuminate\Validation\Rule;
use Illuminate\Support\Facades\DB;
use Auth;

class AddRoleRequest extends BaseRequest
{

    public $name;
    public $permissions;
    public $store_id;
    public $display_name;
}

class AddRoleRequestValidator
{
    public function GetRules($request)
    {
        return [
            'name' => [
                'required',
                'string',
                'max:50',
                Rule::unique('roles')->where(function ($query) use ($request) {
                    return $query->where('store_id', $request->store_id);
                })
            ],
        ];
    }
}

class AddRoleRequestHandler
{


    public function Serve($request)
    {
//        dd($request);

        try {
            DB::beginTransaction();

            $role                   = new Role();
            $role->name             = strtolower($request->name);
            $role->display_name     = $request->name;
            $role->store_id         = Auth::user()->store_id;
            $role->save();
            if ($request->permissions !== null) {
                    $role->syncPermissions($request->permissions);
            }

            DB::commit();
            return new Response(true, $role, null, null, \Lang::get('toaster.role_added'));

        } catch (\Exception $e) {
            DB::rollBack();
            return new Response(false, null, null, $e->getMessage(), null);
        }
    }
}
