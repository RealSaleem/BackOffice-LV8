<?php

namespace App\ViewModels;

use App\Core\RequestExecutor;
use App\ViewModels\BaseViewModel;
use App\Requests\UserManagement\Users\GetUsersByIdRequest;
use App\Requests\UserManagement\Users\GetEmptyUsersRequest;
use App\Models\User;
use App\Models\Outlet;
use App\Models\Role;
use Auth;

class UsersFormViewModel extends BaseViewModel{

    public $users;

    public static function load($id = 0)
    {
        $model = new UsersFormViewModel();

        $store = Auth::user()->store;

        $where = [
            'store_id' => $store->id ,
            'is_deleted' => false,
            //'active' => 1
        ];

        $requestExecutor = new RequestExecutor();

        if($id > 0){
            $model->title           = __('backoffice.edit_user');
            $model->button_title    = __('backoffice.update');
            $model->route           = route('api.update.user');

            $request        = new GetUsersByIdRequest();
            $request->id    = $id;
            $request->store_id = $store->id;
            $response       = $requestExecutor->execute($request);
            $model->user    = $response->Payload;
        }else{
            $model->title           = __('backoffice.add_user');
            $model->button_title    = __('backoffice.save');
            $model->route           = route('api.add.user');
            $request                = new GetEmptyUsersRequest();

            $response       = $requestExecutor->execute($request);
            $model->user    = $response->Payload;
        }

        $model->edit_mode   = ($id > 0);
        $store_id           = Auth::user()->store_id;
        $user               = User::where('store_id' , $store_id)->first();
        $outlets            = Outlet::where('store_id' , $store_id)->get();
        $getrole            = Role::where('store_id' , $store_id)->get();
        $role               = isset($getrole) ? $getrole : NULL;

        return [ 'model' => $model, 'user'=> $user, 'outlets' => $outlets , 'role'=> $role];
    }
}
