<?php

namespace App\Http\Controllers\UserManagement;

use App\Http\Controllers\Controller;
use App\ViewModels\PermissionFormViewModel;

class PermissionController extends Controller
{
    public function index()
    {
        return view('usermanagement.permission.index');
        
    }

    public function create()
    { 
        $data = PermissionFormViewModel::load(0);
        return view('usermanagement.permission.form',$data);
    }

    public function edit($id)
    {
        $data = PermissionFormViewModel::load($id);
        return view('usermanagement.permission.form',$data);
   }

}
