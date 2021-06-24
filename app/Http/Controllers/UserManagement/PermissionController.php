<?php

namespace App\Http\Controllers\UserManagement;

use App\Http\Controllers\Controller;
use App\ViewModels\PermissionFormViewModel;

class PermissionController extends Controller
{
    public function __construct()
    {
        parent::__construct();
        $this->middleware(['role:admin','permission:list-permission'])->only('index');
        $this->middleware(['role:admin','permission:add-permission'])->only('create');

    }
    public function index()
    {
        return view('users_role.permissions.index');

    }

    public function create()
    {
        $data = PermissionFormViewModel::load(0);

        return view('users_role.permissions.form',$data);
    }

    public function edit($id)
    {
        $data = PermissionFormViewModel::load($id);
        return view('usermanagement.permission.form',$data);
   }

}
