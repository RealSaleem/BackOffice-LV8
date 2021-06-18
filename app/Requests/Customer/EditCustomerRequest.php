<?php

namespace App\Requests\Customer;

use App\Core\BaseRequest as BaseRequest;
use App\Core\Response;
use App\Models\Customer;
use Illuminate\Validation\Rule;
use Auth;

class EditCustomerRequest extends BaseRequest
{
    public $id;
    public $name;
   // public $last_name;
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
    // public $state;
    // public $country;
    public $sex;
    // public $date_of_birth;
    public $email;
    public $phone;
    // public $twitter;
    // public $instagram;
    // public $facebook;
    // public $snap_chat;
    // public $postal_city;
    // public $postal_street;
    // public $postal_street2;
    // public $postal_suburb;
    // public $postal_postcode;
    // public $postal_state;
    // public $postal_country;
    public $store_id;
    public $latitude;
    public $longitude;
    public $address;

    public $updated;

}

class EditCustomerRequestValidator
{
    public function GetRules($request)
    {
        return [
            'name'        => 'required|string',
            //'last_name'         => 'required|string|max:45',
            //'company'           => 'string|max:45',
            //'loyalty' => 'required|string|max:50',
            //'supplier_code'     => 'required|string|max:45',
            //'supplier_id'       => 'required|numeric',
            // 'mobile'            => ['required','numeric',
            //     Rule::unique('customers')->where(function ($query) {
            //         return $query->where('store_id', Auth::user()->store_id);
            //     })],
            // 'fax'               => 'string|max:50',
            // 'website'           => 'string|max:50',
            // 'street'            => 'required|string|max:50',
            // 'date_of_birth'     => 'string|max:50',
            'sex'               => 'required|string',
            //'street2' => 'required|string|max:50',
            //'suburb' => 'required|string|max:50',
            //'city'              => 'required',
            //'postcode'          => 'numeric',
            //'state'             => 'string|max:50',
            //'country'           => 'required|string|max:50',
             'email'             => ['email',
                Rule::unique('customers')->where(function ($query) use ($request) {
                    return $query->where('store_id', $request->store_id);
                })->ignore($request->id, 'id')],
             'mobile'             => [
                 'required',
                Rule::unique('customers')->where(function ($query) use ($request) {
                    return $query->where('store_id', $request->store_id);
                })->ignore($request->id, 'id')],
            // 'twitter'           => 'string|max:50',
            'customer_group_id' => 'numeric',
            //'store_id' => 'required|string|max:50',

            // 'postal_city'       => 'string|max:45',
            // 'postal_street'     => 'string|max:45',
            // 'postal_street2'    => 'string|max:45',
            // 'postal_suburb'     => 'string|max:45',
            // 'postal_postcode'   => 'numeric',
            // 'postal_state'      => 'string|max:45',
            // 'postal_country'    => 'string|max:45',

        ];
    }
}

class EditCustomerRequestHandler
{

    public function Serve($request)
    {

        $login_user = is_null(Auth::user())? \App::make('user') : Auth::user();
        $editCustomer                       = Customer::find($request->id);
        $editCustomer->name                 = $request->name;
        // $editCustomer->mobile               = $request->mobile;
        $editCustomer->email                = $request->email;
        $editCustomer->phone                = $request->mobile;
        $editCustomer->sex                  = $request->sex;
        $editCustomer->street               = $request->address;
        $editCustomer->customer_group_id    = $request->customer_group_id;
        $editCustomer->store_id             = $request->store_id;
        // $editCustomer->store_id = Auth::user()->store_id;
        //$editCustomer->last_name         = $request->last_name;
        // $editCustomer->company           = $request->company;
        //$editCustomer->loyalty           = $request->loyalty;
        //$editCustomer->supplier_id       = $request->supplier_id;
        //$editCustomer->supplier_code     = $request->supplier_code;

        // $editCustomer->fax               = $request->fax;
        // $editCustomer->website           = $request->website;
        // $editCustomer->street            = $request->street;
        // $editCustomer->street2           = $request->street2;
        // $editCustomer->suburb            = $request->suburb;
        // $editCustomer->city              = $request->city;
        // $editCustomer->postcode          = $request->postcode;
        // $editCustomer->state             = $request->state;
        // $editCustomer->country           = $request->country;

        // $editCustomer->twitter           = $request->twitter;
        // $editCustomer->instagram         = $request->instagram;
        // $editCustomer->facebook          = $request->facebook;
        // $editCustomer->snap_chat         = $request->snap_chat;
        // $editCustomer->date_of_birth     = $request->date_of_birth;

//        $editCustomer->latitude               = $request->latitude  ;
//        $editCustomer->longitude               = $request->longitude;


        // $editCustomer->postal_city       = $request->postal_city;
        // $editCustomer->postal_street     = $request->postal_street;
        // $editCustomer->postal_street2    = $request->postal_street2;
        // $editCustomer->postal_suburb     = $request->postal_suburb;
        // $editCustomer->postal_postcode   = $request->postal_postcode;
        // $editCustomer->postal_state      = $request->postal_state;
        // $editCustomer->postal_country    = $request->postal_country;


        $editCustomer->save();

        return new Response(true, $editCustomer,null,null,\Lang::get('toaster.customer_updated'));
    }
}
