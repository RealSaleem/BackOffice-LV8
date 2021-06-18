<?php

namespace App\Requests\Catalogue\Supplier;

use App\Core\BaseRequest as BaseRequest;
use App\Core\Response;
use App\Mail\NewSupplierRegistered;
use App\Mail\WelcomeSupplier;
use App\Models\Store;
use App\Models\Supplier;
use Auth;
use Mail;
use Illuminate\Support\Facades\DB;

use Illuminate\Support\Facades\Validator;
use Illuminate\Validation\Rule;


class AddSupplierRequest extends BaseRequest
{

    public $name;
    public $mobile;
    public $phone;
    public $email;
    public $address;
    public $latitude;
    public $longitude;
    public $active;
}

class AddSupplierRequestValidator
{
    public function GetRules($request)
    {
        return [
            'name' => [
                'required',
                'string',
                'min:3',
            ],
            'mobile' => [
                'required',
                Rule::unique('suppliers')->where(function ($query) use ($request) {
                    return $query->where('store_id', $request->store_id)->where('is_deleted', 0)->orWhere('is_deleted', null);
                }),
            ],

            'email' => ['required', 'email',
                Rule::unique('suppliers')->where(function ($query) use ($request) {
                    return $query->where('store_id', $request->store_id)->where('is_deleted', 0)->orWhere('is_deleted', null);
                }),],

        ];
    }
}

class AddSupplierRequestHandler
{

    public function __construct()
    {
    }

    public function Serve($request)
    {

        try {
            DB::beginTransaction();
            $login_user = is_null(Auth::user()) ? \App::make('user') : Auth::user();
            $store = Store::where('id', $login_user->store_id)->first();
//dd($login_user->store_id);
//        dd($request);
            $newSupplier = new Supplier;

            $newSupplier->name = $request->name;
            $newSupplier->mobile = $request->mobile;

            $newSupplier->email = $request->email;
            $newSupplier->phone = isset($request->phone) ? $request->phone : $request->mobile;
            $newSupplier->street = $request->address;
//        $newSupplier->latitude        = $request->latitude;
//        $newSupplier->longitude        = $request->longitude;
            $newSupplier->active = (bool)$request->active;

            $newSupplier->store_id = $login_user->store_id;
//        $newSupplier->is_deleted     = 0;
            $newSupplier->save();

            // if ($newSupplier->save()) {
            // $email = new WelcomeSupplier($newSupplier);
            // Mail::to($request->email)->send($email);

            //     $emailData = new NewSupplierRegistered($newSupplier);
            //     Mail::to($request->email)->send($emailData);

            //     $emailData = new NewSupplierRegistered($newSupplier,true);
            //     Mail::to($emailData->admin->email)->send($emailData);
            // }
            DB::commit();
            return new Response(true, $newSupplier, null, null, \Lang::get('toaster.supplier_added'));
        } catch (\Exception $ex) {
            DB::rollBack();
            return new Response(false, null, null, $ex->getMessage(), null);
        }
    }
}
