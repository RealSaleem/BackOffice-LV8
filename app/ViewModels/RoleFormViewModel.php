<?php

namespace App\ViewModels;

use App\Core\RequestExecutor;
use App\Models\Entity;
use App\Models\Permission_role;
use App\Requests\UserManagement\Roles\GetAllPermissionsRequest;
use App\ViewModels\BaseViewModel;
use App\Requests\UserManagement\Roles\GetRoleByIdRequest;
use App\Requests\UserManagement\Roles\GetEmptyRoleRequest;
use App\Models\Roles;
use Auth;

class RoleFormViewModel extends BaseViewModel
{

    public $role = [];


    public static function load($id = 0)
    {
        $model = new RoleFormViewModel();

        $requestExecutor = new RequestExecutor();

        if ($id > 0) {
            $model->title = __('backoffice.upd_role');
            $model->button_title = __('backoffice.update');
            $model->route = route('api.update.roles');
            $request = new GetRoleByIdRequest();
            $request->id = $id;
            $response = $requestExecutor->execute($request);
            $model->role = $response->Payload;
        } else {
            $model->title = __('backoffice.add_role');
            $model->button_title = __('backoffice.save');
            $model->route = route('api.add.roles');

            $request = new GetEmptyRoleRequest();
            $response = $requestExecutor->execute($request);
            $model->role = $response->Payload;
        }

        $model->edit_mode = ($id > 0);


        return ['model' => $model];
    }
}
