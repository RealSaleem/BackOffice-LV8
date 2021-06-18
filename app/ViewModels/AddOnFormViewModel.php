<?php

namespace App\ViewModels;

use App\Core\RequestExecutor;
use App\ViewModels\BaseViewModel;
use App\Requests\Catalogue\AddOn\GetAddOnByIdRequest;
use App\Requests\Catalogue\AddOn\GetEmptyAddOnRequest;
use App\Requests\Catalogue\AddOn\GetAllItemsRequest;
use Auth;
use App\Models\Product;

class AddOnFormViewModel extends BaseViewModel{

    public $languages = [];
    public $addon;

    public static function load($id = 0)
    {
        $model = new AddOnFormViewModel();

        $model->languages = Auth::user()->store->languages->toArray();

        $requestExecutor = new RequestExecutor();

        if($id > 0){
            $model->title = __('backoffice.edit_addon_title');
            $model->button_title = __('backoffice.update');
            $model->route = route('api.update.addon');

            $request = new GetAddOnByIdRequest();
            $request->id = $id;
            $response = $requestExecutor->execute($request);
            $model->addon = $response->Payload;
        }else{
            $model->title = __('backoffice.add_addon');
            $model->button_title = __('backoffice.save');
            $model->route = route('api.add.addon');
            $request = new GetEmptyAddOnRequest();
            $response = $requestExecutor->execute($request);
            $model->addon = $response->Payload;
        }

        $model->edit_mode = ($id > 0);

        $itemsRequest  = new GetAllItemsRequest();
        $itemsResponse  = $requestExecutor->execute($itemsRequest);
        $items = $itemsResponse->Payload;

        // $items = Product::with(['transalation','product_variants'])->where([
        // 	'store_id' => Auth::user()->store_id,
        // 	'is_item' => 1
        // ])->get();




        return [ 'model' => $model, 'items' => $items];
    }
}
