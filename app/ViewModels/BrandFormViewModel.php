<?php

namespace App\ViewModels;

use App\Core\RequestExecutor;
use App\ViewModels\BaseViewModel;
use App\Requests\Catalogue\Brands\GetBrandsByIdRequest;
use App\Requests\Catalogue\Brands\GetEmptyBrandsRequest;
use App\Models\Brand;
use Auth;

class BrandFormViewModel extends BaseViewModel{

    public $brands = [];
    public $languages = [];
    public $brand;

    public static function load($id = 0)
    {
        $model = new BrandFormViewModel();

        $store = Auth::user()->store;

        $model->languages = $store->languages->toArray();


        $where = [
            'store_id' => $store->id ,
            'is_deleted' => false,
            //'active' => 1
        ];

        // $model->brand = Brand::with('children')->where($where)->get();

        $requestExecutor = new RequestExecutor();

        if($id > 0){
            $model->title = __('backoffice.update');
            $model->button_title = __('backoffice.update');
            $model->route = route('api.update.brand');

            $request = new GetBrandsByIdRequest();
            $request->id = $id;
            $response = $requestExecutor->execute($request);
            $model->brand = $response->Payload;
        }else{
            $model->title = __('backoffice.add_brand');
            $model->button_title = __('backoffice.save');
            $model->route = route('api.add.brand');
            $request = new GetEmptyBrandsRequest();
            $response = $requestExecutor->execute($request);
            $model->brand = $response->Payload;
        }

        $model->edit_mode = ($id > 0);

        return [ 'model' => $model ];
    }
}
