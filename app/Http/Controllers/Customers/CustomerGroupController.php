<?php

namespace App\Http\Controllers\Customers;

use App\Http\Controllers\Controller;
use App\ViewModels\CustomerGroupFormViewModel;

class CustomerGroupController extends Controller
{

    public function __construct()
    {
        parent::__construct();
    }

    public function index()
    {
        return view('customergroup.index');
    }

    public function create()
    { 
        $data = CustomerGroupFormViewModel::load(0);
        
        return view('customergroup.form',$data);
    }

    public function edit($id)
    {
        $data = CustomerGroupFormViewModel::load($id);
        return view('customergroup.form',$data);
    }
}
