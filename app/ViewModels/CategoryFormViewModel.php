<?php

namespace App\ViewModels;

use App\Core\RequestExecutor;
use App\ViewModels\BaseViewModel;
use App\Requests\Catalogue\Category\GetCategoryByIdRequest;
use App\Requests\Catalogue\Category\GetEmptyCategoryRequest;
use App\Models\Category;
use Auth;

class CategoryFormViewModel extends BaseViewModel{

    public $categories = [];
    public $languages = [];
    public $category;

    public static function load($id = 0)
    {
        $model = new CategoryFormViewModel();

        $store = Auth::user()->store;

        $model->languages = $store->languages->toArray();


        $where = [
            'store_id' => $store->id ,
            'is_deleted' => false,
        ];
        $wheresort = [
            'store_id' => $store->id ,
        ];
            //'active' => 1

        $model->categories = Category::with('children')->where($where)->get();
        $getSort = Category::where($wheresort)->orderBy('id','desc')->first();
        $model->sortOrder =  $getSort->sort_order +1;

        $requestExecutor = new RequestExecutor();

        if($id > 0){
            $model->title = __('backoffice.edit_category');
            $model->button_title = __('backoffice.update');
            $model->route = route('api.update.category');

            $request = new GetCategoryByIdRequest();
            $request->id = $id;
            $response = $requestExecutor->execute($request);
            $model->category = $response->Payload;
        }else{
            $model->title = __('backoffice.add_category');
            $model->button_title = __('backoffice.save');
            $model->route = route('api.add.category');

            $request = new GetEmptyCategoryRequest();
            $response = $requestExecutor->execute($request);
            $model->category = $response->Payload;
        }

        $model->edit_mode = ($id > 0);

//        dd($model);

        return [ 'model' => $model ];
    }
}
