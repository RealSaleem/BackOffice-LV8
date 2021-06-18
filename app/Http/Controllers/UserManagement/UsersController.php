<?php

namespace App\Http\Controllers\UserManagement;

use App\Http\Controllers\Controller;
use App\Helpers\AppsVisibilityHelper;
use App\ViewModels\UserProfileFormViewModel;
use App\ViewModels\UsersFormViewModel;
use App\Models\outlet;


class UsersController extends Controller
{

    public function __construct()
    {
        parent::__construct();
    }

    public function index()
    {
        $data = UsersFormViewModel::load(0);
        return view('users_role.users.index', $data);

    }

    public function create()
    {
        $data = UsersFormViewModel::load(0);
        return view('users_role.users.form', $data);
    }

    public function edit($id)
    {
        $data = UsersFormViewModel::load($id);
        if ($data['model']->user != null) {
            return view('users_role.users.form', $data);
        }
        return redirect()->back();
    }

//--------When User View Their Profile---------//
    public function profile_index()
    {
        $data = UserProfileFormViewModel::load(0);
        return view('profile.view', ['user' => $data]);
    }

//--------When User Edit Their Profile---------//
    public function profile_edit()
    {
        $data = UserProfileFormViewModel::load(0);
        return view('profile.edit', ['user' => $data]);
    }

}
