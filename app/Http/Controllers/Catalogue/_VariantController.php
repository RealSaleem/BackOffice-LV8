<?php

namespace App\Http\Controllers\Catalogue;

use App\Http\Controllers\Controller;
use App\ViewModels\VariantFormViewModel;

class VariantController extends Controller
{
    public function __construct()
    {
        parent::__construct();
    }

    public function edit($id)
    {
        $data = VariantFormViewModel::load($id);
        return view('catalogue.variant.form',$data);
   }
}
