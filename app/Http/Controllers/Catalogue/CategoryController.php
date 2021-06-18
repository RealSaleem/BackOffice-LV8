<?php

namespace App\Http\Controllers\Catalogue;

use App\Http\Controllers\Controller;
use App\Helpers\AppsVisibilityHelper;
use App\ViewModels\CategoryFormViewModel;

class CategoryController extends Controller
{

    public function __construct()
    {
        // parent::__construct();
    }

    public function index()
    {
        $data = AppsVisibilityHelper::get();
        return view('catalogue.category.index',$data);
    }

    public function create()
    { 
        $model = CategoryFormViewModel::load(0);
        $data = array_merge($model, AppsVisibilityHelper::get());
        return view('catalogue.category.form',$data);
    }

    public function edit($id)
    {
        $model = CategoryFormViewModel::load($id);
        $data = array_merge($model, AppsVisibilityHelper::get());
        return view('catalogue.category.form',$data);
    }
}
