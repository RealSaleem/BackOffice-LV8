<?php

namespace App\ViewModels;

use App\Core\RequestExecutor;
use App\ViewModels\BaseViewModel;
use App\Requests\CustomerGroup\GetCustomerGroupByIdRequest;
use App\Requests\CustomerGroup\GetEmptyCustomerGroupRequest;
use App\Models\CustomerGroup;
use Auth;

class CustomerGroupFormViewModel extends BaseViewModel{

    public $customergroup = [];


    public static function load($id = 0)
    {
        $model = new CustomerGroupFormViewModel();

        $requestExecutor = new RequestExecutor();

        if($id > 0){
            $model->title = __('backoffice.edit_customer group');
            $model->button_title = __('backoffice.update');
            $model->route = route('api.update.customergroup');

            $request = new GetCustomerGroupByIdRequest();
            $request->id = $id;
            $response = $requestExecutor->execute($request);
            $model->customergroup = $response->Payload;
        }else{
            $model->title = __('backoffice.add_customer group');
            $model->button_title = __('backoffice.save');
            $model->route = route('api.add.customergroup');

            $request = new GetEmptyCustomerGroupRequest();
            $response = $requestExecutor->execute($request);
            $model->customergroup = $response->Payload;
        }

        $model->edit_mode = ($id > 0);

        return [ 'model' => $model ];
    }
}
