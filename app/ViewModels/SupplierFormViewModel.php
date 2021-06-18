<?php

namespace App\ViewModels;

use App\Core\RequestExecutor;
use App\ViewModels\BaseViewModel;
use App\Requests\Catalogue\Supplier\GetSupplierByIdRequest;
use App\Requests\Catalogue\Supplier\GetEmptySupplierRequest;
use App\Models\Supplier;
use Auth;

class SupplierFormViewModel extends BaseViewModel{

    // public $suppliers = [];
    public $supplier;

    public static function load($id = 0)
    {
        $model = new SupplierFormViewModel();


        $where = [
            'store_id' =>Auth::user()->store->id ,

        ];


        $requestExecutor = new RequestExecutor();

        if($id > 0){
            $model->title = __('backoffice.edit_supplier');
            $model->button_title = __('backoffice.update');
            $model->route = route('api.update.supplier');

            $request = new GetSupplierByIdRequest();
            $request->id = $id;
            $response = $requestExecutor->execute($request);
            $model->supplier = $response->Payload;
        }else{
            $model->title = __('backoffice.add_supplier');
            $model->button_title = __('backoffice.save');
            $model->route = route('api.add.supplier');

            $request = new GetEmptysupplierRequest();
            $response = $requestExecutor->execute($request);
            $model->supplier = $response->Payload;
        }

        $model->edit_mode = ($id > 0);

        return [ 'model' => $model ];
    }
}
