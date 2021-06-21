<?php

namespace App\Requests\UserManagement\Permission;
use App\Core\BaseRequest as BaseRequest;
use App\Core\Response;
use App\Models\Store;
use App\Models\Entity;
//use App\Models\Permission;
use Auth;
use Spatie\Permission\Models\Permission;

// use Mail;
// use App\Mail\NewCustomerGroupNotification;

class AddPermissionRequest extends BaseRequest{

    public $name;
    public $route;
    public $group;
    public $description;
    public $store_id;
    public $crud;
    public $entity;
    public $slug;
    // public $created_at;
}

class AddPermissionRequestValidator {
    public function GetRules(){
        return [
//            'name' => 'required|max:225',
//            'title' => 'required|max:225',
//            'entity' => 'required|max:225',
//            'description' => 'required',
        ];
    }
}

class AddPermissionRequestHandler {
    public function Serve($request){


        $login_user = is_null(Auth::user())? \App::make('user') : Auth::user();

        $store = Store::where('id',$login_user->store_id)->first();

//        $newPer = new Permission;
//        $entity_model = Entity::find($request->entity);
        $Store_id  =  Auth::user()->store['id'];


//        $permission                         = new Permission();
//        $permission->name                   = $request->name;
//        $permission->display_name           = $permission->name;
//        $permission->description            = $request->description;
//        $permission->group                  = $request->group;
//        $permission->group_display_name     = $permission->group;
//        $permission->save();

        try{
            $permission                   = new Permission();
            $permission->name             = strtolower($request->slug);
            $permission->module           = $request->entity;
            $permission->display_name     = $request->name;
            $permission->save();
            return new Response(true, $permission,null,null,\Lang::get('toaster.permission_added'));
        }catch (\Exception $ex){
            return new Response(false, null,null,$ex->getMessage());

        }





    }
}
