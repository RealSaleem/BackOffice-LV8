<?php

namespace App\Http\Controllers\Customers;

use App\Http\Controllers\Controller;
use App\Models\WebUser;
use App\ViewModels\CustomerFormViewModel;
use Illuminate\Support\Facades\Auth;

class CustomerController extends Controller
{

    public function __construct()
    {
        // parent::__construct();
    }

    public function index()
    {
        $Customer = WebUser::where('store_id',Auth::user()->store->id)->wherenull('customer_id')->get();
        return view('customer.index')->with(compact('Customer'));
    }

    public function create()
    {
        $data = CustomerFormViewModel::load(0);

        return view('customer.form',$data);
    }

    public function edit($id)
    {
        $data = CustomerFormViewModel::load($id);
        return view('customer.form',$data);
    }
}
