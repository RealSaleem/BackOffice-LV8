<?php

namespace App\Http\Controllers\Catalogue;

use App\Http\Controllers\Controller;
use App\ViewModels\ProductFormViewModel;
use App\Helpers\AppsVisibilityHelper;
use App\Models\Category;
use App\Models\Brand;
use App\Models\Supplier;

class ProductController extends Controller
{

    public function __construct()
    {
        parent::__construct();
    }

    public function index()
    {
        $store_id = \Auth::user()->store_id;
        $data = AppsVisibilityHelper::get();
        $data['categories'] = Category::where(['store_id' => $store_id , 'is_deleted' => 0])->get();
        $data['brands'] = Brand::where(['store_id' => $store_id , 'is_deleted' => 0])->get();
        $data['suppliers'] = Supplier::where(['store_id' => $store_id , 'is_deleted' => 0])->get();

        return view('catalogue.product.index',$data);
    }

    public function create()
    {
        $model = ProductFormViewModel::load(0);
        $data = array_merge($model, AppsVisibilityHelper::get());
        //dd($data);
        return view('catalogue.product.add',$data);
    }

    public function edit($id)
    {
        $model = ProductFormViewModel::load($id);
        $data = array_merge($model, AppsVisibilityHelper::get());
        return view('catalogue.product.add',$data);
    }
}
