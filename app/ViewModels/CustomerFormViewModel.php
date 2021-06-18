<?php

namespace App\ViewModels;

use App\Core\RequestExecutor;
use App\ViewModels\BaseViewModel;
use App\Requests\Customer\GetCustomerByIdRequest;
use App\Requests\Customer\GetEmptyCustomerRequest;
use App\Models\CustomerGroup;
use Auth;

class CustomerFormViewModel extends BaseViewModel{

    public $customergroups = [];
    public $customer;

    public static function load($id = 0)
    {
        $model = new CustomerFormViewModel();

        $where = [
            'store_id' =>Auth::user()->store_id ,
        ];

        $model->customergroups = CustomerGroup::where($where)->get();

        $requestExecutor = new RequestExecutor();

        if($id > 0){
            $model->title = __('backoffice.edit_customer');
            $model->button_title = __('backoffice.update');
            $model->route = route('api.update.customer');

            $request = new GetCustomerByIdRequest();
            $request->id = $id;
            $response = $requestExecutor->execute($request);
            $model->customer = $response->Payload;
        }else{
            $model->title = __('backoffice.add_customer');
            $model->button_title = __('backoffice.save');
            $model->route = route('api.add.customer');

            $request = new GetEmptyCustomerRequest();
            $response = $requestExecutor->execute($request);
            $model->customer = $response->Payload;
        }

        $model->edit_mode = ($id > 0);

        return [ 'model' => $model ];
    }
}
