<?php

namespace App\Modules\Supplier;

use App\Core\BaseRequest as BaseRequest;
use App\Core\Response;
use App\Mail\NewSupplierRegistered;
use App\Mail\WelcomeSupplier;
use App\Models\Store;
use App\Models\Supplier;
use Auth;
use Mail;

use Illuminate\Support\Facades\Validator;
use Illuminate\Validation\Rule;


class AddSupplierRequest extends BaseRequest
{

    public $name;
    public $company;
    public $description;
    public $default_markup;
    public $first_name;
    public $last_name;
    public $mobile;
    public $fax;
    public $website;
    public $street;
    public $street2;
    public $suburb;
    public $postcode;
    public $city;
    public $state;
    public $country;
    public $postal_street;
    public $postal_street2;
    public $postal_suburb;
    public $postal_postcode;
    public $postal_city;
    public $postal_state;
    public $postal_country;
    public $email;
    public $phone;
    public $twitter;
    public $instagram;
    public $facebook;
    public $snap_chat;
    public $store_id;

    public $store;

}

class AddSupplierRequestValidator
{
    public function GetRules($request)
    {
        return [
            // 'name'    => 'required|string|max:45',
             'name' => [
                'required',
                'string',
                'max:100',
                Rule::unique('suppliers')->where(function ($query) use ($request) {
                    return $query->where('store_id', $request->store_id);
                }),
            ],
            'company' => 'required|string|max:45',
            // 'description'     => 'string|max:45',
            // 'default_markup'  => 'string|max:45',
            // 'first_name'      => 'required|string|max:45',
            // 'last_name'       => 'required|string|max:45',
            // 'mobile'  => 'required|numeric|unique:suppliers',
            // 'mobile'  => 'required',
            // 'fax'             => 'numeric',
            // 'website'         => 'string|max:45',
            'street'  => 'required|string|max:45',
            // 'street2'         => 'string|max:45',
            // 'suburb'          => 'string|max:45',
            // 'postcode'        => 'numeric',
            // 'city'            => 'string|max:45',
            // 'state'           => 'string|max:45',
            'country' => 'required|string|max:45',
            // 'email'   => 'required|string|max:45',
            // 'phone'           => 'string|max:45',
            // 'twitter'         => 'string|max:45',

            // 'postal_street'   => 'string|max:45',
            // 'postal_street2'  => 'string|max:45',
            // 'postal_suburb'   => 'string|max:45',
            // 'postal_postcode' => 'string|max:45',
            // 'postal_city'     => 'string|max:45',
            // 'postal_state'    => 'string|max:45',
            // 'postal_country'  => 'string|max:45',
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
        $login_user = is_null(Auth::user())? \App::make('user') : Auth::user();
        $store = Store::where('id', $login_user->store_id)->first();

        $newSupplier = new Supplier;

        $newSupplier->name           = $request->name;
        $newSupplier->company        = $request->company;
        $newSupplier->description    = $request->description;
        $newSupplier->first_name     = $request->first_name;
        $newSupplier->last_name      = $request->last_name;
        $newSupplier->default_markup = $request->default_markup;
        $newSupplier->mobile         = $request->mobile;
        $newSupplier->fax            = $request->fax;
        $newSupplier->website        = $request->website;
        $newSupplier->street         = $request->street;
        $newSupplier->street2        = $request->street2;
        $newSupplier->suburb         = $request->suburb;
        $newSupplier->postcode       = $request->postcode;
        $newSupplier->state          = $request->state;
        $newSupplier->country        = $request->country;
        $newSupplier->city           = $request->city;
        $newSupplier->email          = $request->email;
        $newSupplier->phone          = $request->phone;
        $newSupplier->twitter        = $request->twitter;
        $newSupplier->instagram      = $request->instagram;
        $newSupplier->facebook       = $request->facebook;
        $newSupplier->snap_chat      = $request->snap_chat;
        $newSupplier->store_id       = $login_user->store_id;

        $newSupplier->postal_street   = $request->postal_street;
        $newSupplier->postal_street2  = $request->postal_street2;
        $newSupplier->postal_suburb   = $request->postal_suburb;
        $newSupplier->postal_postcode = $request->postal_postcode;
        $newSupplier->postal_state    = $request->postal_state;
        $newSupplier->postal_city     = $request->postal_city;
        $newSupplier->postal_country  = $request->postal_country;
        $newSupplier->save();
        // if ($newSupplier->save()) {
            // $email = new WelcomeSupplier($newSupplier);
            // Mail::to($request->email)->send($email);

        //     $emailData = new NewSupplierRegistered($newSupplier); 
        //     Mail::to($request->email)->send($emailData);

        //     $emailData = new NewSupplierRegistered($newSupplier,true);
        //     Mail::to($emailData->admin->email)->send($emailData);
        // }

        return new Response(true, $newSupplier);
    }
}
