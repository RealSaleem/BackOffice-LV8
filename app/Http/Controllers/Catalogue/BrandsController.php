<?php

namespace App\Http\Controllers\Catalogue;

use App\Http\Controllers\Controller;
use App\Models\Permission;
use App\Models\Permission_role;
use App\Models\User;
use App\ViewModels\BrandFormViewModel;
use Illuminate\Support\Facades\Auth;

class BrandsController extends Controller
{

    public function __construct()
    {
        parent::__construct();
    }

    public function index()
    {
        return view('catalogue.brands.index');
    }

    public function create()
    {

        $data = BrandFormViewModel::load(0);
        // dd($data);
        return view('catalogue.brands.form',$data);
    }

    public function edit($id)
    {

        $data = BrandFormViewModel::load($id);

        return view('catalogue.brands.form',$data);
    }
}
