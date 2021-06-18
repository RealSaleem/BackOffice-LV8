<?php

namespace App\Requests\CustomerGroup;

use App\Core\BaseRequest as BaseRequest;
use App\Core\Response;
use App\Models\CustomerGroup;
use App\Models\Store;
use Auth;
use Illuminate\Validation\Rule;
use Illuminate\Support\Facades\DB;


// use Mail;
// use App\Mail\NewCustomerGroupNotification;

class AddCustomerGroupRequest extends BaseRequest
{

    public $name;
    public $created_at;

    public $store_id;
    public $store;

}

class AddCustomerGroupRequestValidator
{
    public function GetRules($request)
    {
        return [
            'name' => [
                'required',
                'string',
                'max:100',
                Rule::unique('customer_groups')->where(function ($query) use ($request) {
                    return $query->where('store_id', $request->store_id);
                }),
            ],
        ];
    }
}

class AddCustomerGroupRequestHandler
{

    public function Serve($request)
    {
        try {
            DB::beginTransaction();
            $login_user = is_null(Auth::user()) ? \App::make('user') : Auth::user();

            $store = Store::where('id', $login_user->store_id)->first();

            $newCustomerGroup = new CustomerGroup;

            $newCustomerGroup->name = $request->name;
            $newCustomerGroup->store_id = $login_user->store_id;
            $newCustomerGroup->save();
            // if ($newCustomerGroup->save()) {
            //     $email = new NewCustomerGroupNotification($newCustomerGroup);
            //     Mail::to($store->email)->send($email);

            //     $emailAdmin = new NewCustomerGroupNotification($newCustomerGroup);
            //     Mail::to(env('MAIL_FROM_ADDRESS'))->send($emailAdmin);

            // }
            DB::commit();
            return new Response(true, $newCustomerGroup, null, null, \Lang::get('toaster.customerGroup_added'));
        } catch (\Exception $ex) {
            DB::rollBack();
            return new Response(false, null, null, $ex->getMessage(), null);
        }
    }
}
