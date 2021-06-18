<?php

namespace App\ViewModels;

use App\ViewModels\BaseViewModel;
use App\Requests\Catalogue\Product\GetProductByIdRequest;
use App\Core\RequestExecutor;

class VariantFormViewModel extends BaseViewModel{

    public $product;
    public $languages = [];
    public $outlets = [];

    public static function load($id)
    {
        $model = new VariantFormViewModel();

        $model->button_title = __('variant.save');
        $model->route = route('api.update.variant');
        
        $request = new GetProductByIdRequest();
        $request->id = $id;
        $requestExecutor = new RequestExecutor();        
        $response = $requestExecutor->execute($request); 
        $model->product = $response->Payload;
        $model->title = $model->product['name'];

        return [ 'model' => $model ];
    }
}