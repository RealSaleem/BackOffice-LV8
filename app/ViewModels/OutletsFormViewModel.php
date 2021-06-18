<?php

namespace App\ViewModels;

use App\Core\RequestExecutor;
use App\ViewModels\BaseViewModel;
use App\Requests\Outlets\GetOutletsByIdRequest;
use App\Requests\Outlets\GetEmptyOutletsRequest;
use App\Models\Outlet;
use Auth;

class OutletsFormViewModel extends BaseViewModel{
    public $outlet;

    public static function load($id = 0)
    {
        $model = new OutletsFormViewModel();

        $where = [
            'store_id' =>Auth::user()->store_id , 
        ];
        $requestExecutor = new RequestExecutor();

        if($id > 0){
            $model->title = __('outlets.update_outlets');
            $model->button_title = __('outlets.update');
            $model->route = route('api.update.outlets');
            
            $request = new GetOutletsByIdRequest();
            $request->id = $id;
            $response = $requestExecutor->execute($request); 
            $model->outlet = $response->Payload;
        }else{
            $model->title = __('outlets.add_outlets');
            $model->button_title = __('outlets.save');
            $model->route = route('api.add.outlets');

            $request = new GetEmptyOutletsRequest();
            $response = $requestExecutor->execute($request); 
            $model->outlet = $response->Payload;
        }

        $model->edit_mode = ($id > 0);
        
        return [ 'model' => $model ];
    }
}