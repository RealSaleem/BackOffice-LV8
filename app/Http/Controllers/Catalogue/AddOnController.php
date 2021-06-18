<?php

namespace App\Http\Controllers\Catalogue;

use App\Http\Controllers\Controller;
use App\ViewModels\AddOnFormViewModel;

class AddOnController extends Controller
{

    public function __construct()
    {
        parent::__construct();
    }

    public function index()
    {
        $data['store'] = \Auth::user()->store;
        return view('catalogue.addon.index',$data);
        
    }

    public function create()
    { 
        $data = AddOnFormViewModel::load(0);
        return view('catalogue.addon.form',$data);
    }

    public function edit($id)
    {

        $data = AddOnFormViewModel::load($id);
        // dd($data);
        return view('catalogue.addon.form',$data);
   }
}
