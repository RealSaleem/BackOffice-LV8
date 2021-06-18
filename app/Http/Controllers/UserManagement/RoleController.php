<?php
namespace App\Http\Controllers\UserManagement;

use App\Http\Controllers\Controller;
use App\Core\RequestExecutor;
use App\ViewModels\RoleFormViewModel;
use App\Models\Permission;
use App\Models\Entity;
use App\Models\Role;
use Auth;
use App\Requests\UserManagement\Roles\GetAllPermissionsRequest;

class RoleController extends Controller
{
    public function __construct(RequestExecutor $requestExecutor)
    {
        parent::__construct();
        $this->RequestExecutor = $requestExecutor;
    }

    public function index()
    {
        return view('users_role.roles.index');

    }

    public function create()
    {
        $data = RoleFormViewModel::load(0);
        $entityModule = Entity::get();
        $permissions =  Permission::get();


        return view('users_role.roles.add',$data)->with(compact('entityModule','permissions'));
    }


    public function edit($id)
    {
        $data = RoleFormViewModel::load($id);
        return view('users_role.roles.edit')->with(compact('data'));
    }

    public function assign_permission($id)
    {
        $request = new GetAllPermissionsRequest();

        $request->id = $id;
        $request->store_id = Auth::user()->store_id;
        $response = $this->RequestExecutor->execute($request);
        $data = $response->Payload;

        return view('users_role.roles.assign_permission', [ 'data' => $data]);
    }

}
