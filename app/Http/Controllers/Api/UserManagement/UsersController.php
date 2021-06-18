<?php

namespace App\Http\Controllers\Api\UserManagement;

use Illuminate\Http\Request;
use App\Core\RequestExecutor;
use Illuminate\Support\Facades\Auth;
use App\Requests\User\EditProfileRequest;
use App\Http\Controllers\Api\ApiController;
use App\Requests\UserManagement\Users\AddUsersRequest;
use App\Requests\UserManagement\Users\BulkUsersRequest;
use App\Requests\UserManagement\Users\EditUsersRequest;
use App\Requests\UserManagement\Users\DeleteUsersRequest;

use App\Requests\UserManagement\Users\GetAllUsersRequest;
use App\Requests\UserManagement\Users\ToggleUsersRequest;



class UsersController extends ApiController
{

    public function __construct(RequestExecutor $requestExecutor)
    {
        // parent::__construct();
        $this->RequestExecutor = $requestExecutor;
    }


    public function getuser(GetAllUsersRequest $request)
    {
        $request->store_id = Auth::user()->store_id;
        $response = $this->RequestExecutor->execute($request);
        return response()->json($response->Payload);
    }


    public function adduser(Request $request)
    {
        $user_request = new AddUsersRequest();
        $user_request->store_id = Auth::user()->store_id;
        $response = $this->RequestExecutor->execute($user_request);
        return response()->json($response);
    }


    public function updateuser(Request $request)
    {
        $user_request = new EditUsersRequest();
        $user_request->store_id = Auth::user()->store_id;
        $response = $this->RequestExecutor->execute($user_request);
        return response()->json($response);
    }

    public function deleteuser(DeleteUsersRequest $request)
    {
        $request->store_id = Auth::user()->store_id;
        $response = $this->RequestExecutor->execute($request);
        return response()->json($response);
    }

    public function toggleuser(ToggleUsersRequest $request)
    {
        $request->store_id = Auth::user()->store_id;
        $response = $this->RequestExecutor->execute($request);
        return response()->json($response);

    }

    public function bulkuser(BulkUsersRequest $request)
    {
        $request->store_id = Auth::user()->store_id;
        $response = $this->RequestExecutor->execute($request);
        return response()->json($response);
    }

//--------When login user update their profile---------//
    public function save(EditProfileRequest $request)
    {
        $response = $this->RequestExecutor->execute($request);
        return response()->json($response);
    }


}





