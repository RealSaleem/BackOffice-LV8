<?php

namespace App\Requests\Customer;

use App\Core\BaseRequest as BaseRequest;
use App\Core\Response;
use App\Models\Customer;
use App\Models\Store;
use Auth;
use App\Mail\NewCustomerRegistered;
use Mail;
use Illuminate\Validation\Rule;
use Illuminate\Support\Facades\DB;

class AddCustomerRequest extends BaseRequest
{

    public $name;
    //public $last_name;
    // public $company;
    //public $loyalty;
    //public $supplier_code;
    //public $supplier_id;
    public $customer_group_id;
    public $mobile;
    // public $fax;
    // public $website;
    // public $street;
    // public $street2;
    // public $suburb;
    // public $city;
    // public $postcode;
    public $sex;
    // public $date_of_birth;
    // public $state;
    // public $country;
    public $email;
    public $phone;
    public $address;
    public $latitude;
    public $longitude;
    // public $twitter;
    // public $instagram;
    // public $facebook;
    // public $snap_chat;
    public $store_id;

    // public $postal_city;
    // public $postal_street;
    // public $postal_street2;
    // public $postal_suburb;
    // public $postal_postcode;
    // public $postal_state;
    // public $postal_country;

    // public $store;
    // public $created;
    // public $id;
}

class AddCustomerRequestValidator
{
    public function GetRules($request)
    {
        return [
            'name'     => 'required|string|max:45',
            'email'             => [
                'email',
                'required',
                Rule::unique('customers')->where(function ($query) use ($request) {
                    return $query->where('store_id', $request->store_id);
                })],
            'mobile'             => [
                'required',
                Rule::unique('customers')->where(function ($query) use ($request) {
                    return $query->where('store_id', $request->store_id);
                })],
            // 'mobile'   => 'required|numeric|unique:customers',
            'sex'      => 'required',
            //'last_name'  => 'required|string|max:45',
            //'company'           => 'string|max:45',
            //'loyalty' => 'string|max:50',
            //'street2' => 'string|max:45',
            //'suburb' => 'string|max:45',
            //'supplier_code'     => 'string|max:45',
            //'supplier_id'       => 'numeric',
            'customer_group_id' => 'numeric',

            // 'fax'               => 'string|max:50',
            // 'website'           => 'string|max:50',
            // 'address1'     => 'required|string|max:50',
            // 'street2'            => 'string|max:45',
            // 'suburb'            => 'string|max:45',
            //'city'       => 'required',
            //'postcode'          => 'numeric',
            //'state'             => 'string|max:50',
            //'country'    => 'required|string|max:50',

            // 'twitter'           => 'string|max:50',

            // 'postal_city'       => 'string|max:45',
            // 'postal_street'     => 'string|max:45',
            // 'postal_street2'    => 'string|max:45',
            // 'postal_suburb'     => 'string|max:45',
            // 'postal_postcode'   => 'numeric',
            // 'postal_state'      => 'string|max:45',
            // 'postal_country'    => 'string|max:45',
            //'store_id' => 'string|max:50',
            //'date_of_birth' => 'string|max:50',

            // 'country'    => 'required'

        ];
    }
}

class AddCustomerRequestHandler
{

    public function Serve($request)
    {
//        dd($request);
        try {
            DB::beginTransaction();
        $user  = is_null(Auth::user()) ? \App::make('user') : Auth::user();
        $store = Store::where('id', $user->store_id)->first();

        $newCustomer                        = new Customer;
        $newCustomer->name                  = $request->name;
        // $newCustomer->mobile                = $request->mobile;
        $newCustomer->email                 = $request->email;
        $newCustomer->street                = $request->address;
        $newCustomer->phone                 = $request->mobile;
        $newCustomer->sex                   = $request->sex;
        $newCustomer->customer_group_id     = $request->customer_group_id;
        $newCustomer->last_name             =  isset($request->last_name) ? $request->last_name : $request->name;
        $newCustomer->created               = $user->id;
        $newCustomer->store_id              = $store->id;
        //$newCustomer->last_name  = $request->last_name;
        // $newCustomer->company    = $request->company;
        //$newCustomer->loyalty           = $request->loyalty;
        //$newCustomer->supplier_id       = $request->supplier_id;
        //$newCustomer->supplier_code     = $request->supplier_code;

        // $newCustomer->fax               = $request->fax;
        // $newCustomer->website           = $request->website;
        // $newCustomer->street            = $request->street;
        // $newCustomer->street2           = $request->street2;
        // $newCustomer->suburb            = $request->suburb;
        // $newCustomer->city              = $request->city;
        // $newCustomer->postal_city       = $request->postal_city;
        // $newCustomer->postcode          = $request->postcode;
        // $newCustomer->state             = $request->state;
        // $newCustomer->country           = $request->country;

        // $newCustomer->twitter           = $request->twitter;
        // $newCustomer->instagram         = $request->instagram;
        // $newCustomer->facebook          = $request->facebook;
        // $newCustomer->snap_chat         = $request->snap_chat;
        // $newCustomer->date_of_birth     = $request->date_of_birth;

//        $newCustomer->latitude = $request->latitude;
//        $newCustomer->longitude = $request->longitude;
//        $newCustomer->address = $request->address;


        // $newCustomer->postal_city     = $request->postal_city;
        // $newCustomer->postal_street   = $request->postal_street;
        // $newCustomer->postal_street2  = $request->postal_street2;
        // $newCustomer->postal_suburb   = $request->postal_suburb;
        // $newCustomer->postal_postcode = $request->postal_postcode;
        // $newCustomer->postal_state    = $request->postal_state;
        // $newCustomer->postal_country  = $request->postal_country;


        //$newCustomer->store_id = Auth::user()->store_id;

        $newCustomer->save();

        if ($newCustomer->save()) {
            // if ($request->email != null) {
            //     $email = new WelcomeCustomer($newCustomer);
            //     Mail::to($request->email)->send($email);
            // }

            // $emailStore = new NewCustomerRegistered($newCustomer,$store);
            // Mail::to($newCustomer->email)->send($emailStore);
        }
            DB::commit();
        return new Response(true, $newCustomer, null, null, \Lang::get('toaster.customer_added'));
        } catch (\Exception $ex) {
            DB::rollBack();
            return new Response(false, null, null, $ex->getMessage(), null);
        }
    }
}
