<?php

namespace App\Http\Controllers\Catalogue;

use App\Http\Controllers\Controller;
use App\Models\Supplier;
use App\ViewModels\SupplierFormViewModel;
use Illuminate\Support\Facades\Auth;

class SupplierController extends Controller
{

    public function __construct()
    {
        parent::__construct();
    }

    public function index()
    {
        return view('catalogue.supplier.index');
    }

    public function create()
    {
        $data = SupplierFormViewModel::load(0);
        return view('catalogue.supplier.form',$data);
    }

    public function edit($id)
    {
        $data = SupplierFormViewModel::load($id);
//        dd($data);
        return view('catalogue.supplier.form',$data);
    }
}
