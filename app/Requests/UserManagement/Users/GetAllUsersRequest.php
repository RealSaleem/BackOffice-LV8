<?php

namespace App\Requests\UserManagement\Users;

use App\Core\BaseRequest as BaseRequest;
use App\Core\DataTableResponse;
use App\Models\Outlet;
use App\Models\User;
use App\Models\Role;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;

class GetAllUsersRequest extends BaseRequest
{
    public $store_id;
}

class GetAllUsersRequestHandler
{

    public function __construct()
    {
    }

    public function Serve($request)
    {
        $columns = [
            'name',
            'email',
            'mobile',
            'active',
            'actions',
        ];

        $params = $request->all();

        $limit = $params['length'];
        $start = $params['start'];

        $order = $columns[$params['order'][0]['column']];
        $dir = $params['order'][0]['dir'];

        $where = [
            'store_id' => $request->store_id,
            'is_deleted' => null
        ];

        $userObj = User::where($where);
        // ->where('id', '!=', Auth::user()->id);

        if (isset($params['search']['value']) && !empty($params['search']['value']) && strlen($params['search']['value']) > 0) {
            $userObj = $userObj->where('name', 'LIKE', "%{$params['search']['value']}%");
        }


        $totalData = $userObj->count(); //
        $users = $userObj->offset($start)->limit($limit)->orderBy($order, $dir)->get();
        $users->transform(function ($user) {
            $getOutlet = $user->usr_outlets->pluck('name')->toArray();

            $data = [
                'id' => $user->id,
                'image' => $user->user_image,
                'name' => $user->name,
                'mobile' => $user->mobile,
                'outlet' => implode(", ", $getOutlet),
                'email' => $user->email,
                'active' => $user->active,
                'role' => isset($user) ? $user->getRoleNames() : null,
                'role_name' => isset($user) ? $user->getRoleNames() : null,
                'actions' => '',
            ];

            return (object)$data;
        });

        return new DataTableResponse($users, $totalData);
    }
}
