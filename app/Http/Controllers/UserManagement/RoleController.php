<?php
namespace App\Http\Controllers\UserManagement;

use App\Http\Controllers\Controller;
use App\Core\RequestExecutor;
use App\ViewModels\RoleFormViewModel;
//use App\Models\Permission;
use App\Models\Entity;
use Spatie\Permission\Models\Permission;
use App\Models\Role;
use Auth;
use App\Requests\UserManagement\Roles\GetAllPermissionsRequest;

class RoleController extends Controller
{
    public function __construct(RequestExecutor $requestExecutor)
    {
        parent::__construct();
        $this->RequestExecutor = $requestExecutor;
        $this->middleware(['permission:list-role'])->only('index');
        $this->middleware(['permission:add-role'])->only('create');

    }

    public function index()
    {
        return view('users_role.roles.index');

    }

    public function create()
    {
        $data = RoleFormViewModel::load(0);
        $permissions =  Permission::all()->groupBy('module');
        $reports =  Permission::where('module','Report')->orWhere('module', 'Export')->get()->groupBy('module');
        return view('users_role.roles.form',$data)->with(compact('permissions','reports'));
    }


    public function edit($id)
    {
        $data = RoleFormViewModel::load($id);
//        return view('users_role.roles.form')->with(compact('data'));
        $entityModule = Entity::get();
        $permissions =  Permission::all()->groupBy('module');
        $reports =  Permission::where('module','Report')->orWhere('module', 'Export')->get()->groupBy('module');
        return view('users_role.roles.form',$data)->with(compact('permissions','reports'));
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
