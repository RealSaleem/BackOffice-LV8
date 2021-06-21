<?php

namespace App\ViewModels;

use App\Core\RequestExecutor;
use App\ViewModels\BaseViewModel;
use App\Requests\UserManagement\Permission\GetPermissionByIdRequest;
use App\Requests\UserManagement\Permission\GetEmptyPermissionRequest;
use App\Models\Abilities;
use App\Models\Permission;
use App\Models\Entity;
use Auth;
use DB;

class PermissionFormViewModel extends BaseViewModel{

    public $permission = [];


    public static function load($id = 0)
    {
        $model = new PermissionFormViewModel();

        $requestExecutor = new RequestExecutor();

        if($id > 0){
            $model->title = __('backoffice.edit_permission');
            $model->button_title = __('backoffice.update');
            $model->route = route('api.update.permission');
            $request = new GetPermissionByIdRequest();
            $request->id = $id;
            $response = $requestExecutor->execute($request);
            $model->permission = $response->Payload;
        }else{
            $model->title = __('backoffice.add_role_perm');
            $model->button_title = __('backoffice.save');
            $model->route = route('api.add.permission');

            $request = new GetEmptyPermissionRequest();
            $response = $requestExecutor->execute($request);
            $model->permission = $response->Payload;
        }

        $entity = Entity::get();
        $model->edit_mode = ($id > 0);
        // $modelPermission= Permission::get();
        return [ 'model' => $model,'entity'=>$entity];
    }
}
