<?php

namespace App\Requests\UserManagement\Permission;
use App\Core\BaseRequest as BaseRequest;
use App\Core\Response;
use App\Models\Store;
use App\Models\Entity;
use App\Models\Permission;
use Auth;

// use Mail;
// use App\Mail\NewCustomerGroupNotification;

class AddPermissionRequest extends BaseRequest{

    public $name;
    public $route;
    public $group;
    public $description;
    public $store_id;
    public $crud;
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

//        dd($request);
        $login_user = is_null(Auth::user())? \App::make('user') : Auth::user();

        $store = Store::where('id',$login_user->store_id)->first();

//        $newPer = new Permission;
//        $entity_model = Entity::find($request->entity);
        $Store_id  =  Auth::user()->store['id'];


        $permission                         = new Permission();
        $permission->name                   = $request->name;
        $permission->display_name           = $permission->name;
//        $permission->route                  = $request->route;
        $permission->description            = $request->description;
        $permission->group                  = $request->group;
        $permission->group_display_name     = $permission->group;
        $permission->save();
//        dd($permission);


//        if($request->crud == 'on'){
//            $crud= ['add'.$request->name, 'view'.$request->name,'update'.$request->name,'delete'.$request->name];
//            $title= ['add','view','update','delete'];
//            $permission = Permission::create([
//                'name' => $crud,
//                'title' => $title,
//                'store_id' =>$Store_id,
//                'entity' =>$entity_model->name,
//            ]);
//
//        }else{
//            $permission = Permission::create([
//                'name' => $request->name,
//                'title' => $request->title,
//                'store_id' =>$Store_id,
//                'entity' =>$entity_model->name,
//            ]);


//        }





//        $newPer->name = $request->name;
//        $newPer->title = $request->title;
//        $newPer->entity_id = $request->entity;
//        $newPer->entity_type = $entity_model->name;
//        $newPer->description = $request->description;
//        $newPer->store_id = $login_user->store_id;
//
//        $newPer->save();
        // if ($newPer->save()) {
        //     $email = new NewCustomerGroupNotification($newPer);
        //     Mail::to($store->email)->send($email);

        //     $emailAdmin = new NewCustomerGroupNotification($newPer);
        //     Mail::to(env('MAIL_FROM_ADDRESS'))->send($emailAdmin);

        // }
        return new Response(true, $permission);
    }
}
