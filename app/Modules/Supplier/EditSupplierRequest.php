<?php

namespace App\Modules\Supplier;

use App\Core\BaseRequest as BaseRequest;
use App\Core\Response;
use App\Models\Supplier as Supplier;
use Illuminate\Validation\Rule;

class EditSupplierRequest extends BaseRequest
{

    public $id;
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
    public $state;
    public $city;
    public $postal_street;
    public $postal_street2;
    public $postal_suburb;
    public $postal_postcode;
    public $postal_city;
    public $postal_state;
    public $postal_country;
    public $country;
    public $email;
    public $phone;
    public $twitter;
    public $instagram;
    public $facebook;
    public $snap_chat;
}

class EditSupplierRequestValidator
{
    public function GetRules($request)
    {
        return [
           'name' => [
                'required',
                'string',
                'max:100',
                Rule::unique('suppliers')->where(function ($query) use ($request) {
                    return $query->where('store_id', $request->store_id);
                })->ignore($request->id),
            ],
            'company'         => 'required|string|max:45',
            // 'description'     => 'string|max:45',
            // 'default_markup'  => 'string|max:45',
            // 'first_name'      => 'required|string|max:45',
            // 'last_name'       => 'required|string|max:45',
            // 'mobile'          => 'required|numeric|unique:suppliers,mobile,'.$request->id,
            // 'mobile'          => 'required',
            // 'fax'             => 'numeric',
            // 'website'         => 'string|max:45',
            'street'          => 'required|string|max:45',
            // 'postcode'        => 'numeric',
            //'city'            => 'string|max:45',
            // 'state'           => 'string|max:45',
            'country'         => 'required|string|max:45',
            // 'email'           => 'required|string|max:45',
            // 'phone'           => 'numeric',
            // 'twitter'         => 'string|max:45',

            // 'postal_street'   => 'string|max:45',
            // 'postal_street2'  => 'string|max:45',
            // 'postal_suburb'   => 'string|max:45',
            // 'postal_postcode' => 'numeric',
            // 'postal_city'     => 'string|max:50',
            // 'postal_state'    => 'string|max:45',
            // 'postal_country'  => 'string|max:45',
        ];
    }
}

class EditSupplierRequestHandler
{

    public function __construct()
    {
    }

    public function Serve($request)
    {
        $supplier = Supplier::find($request->id);

        $supplier->name           = $request->name;
        $supplier->company        = $request->company;
        $supplier->description    = $request->description;
        $supplier->first_name     = $request->first_name;
        $supplier->last_name      = $request->last_name;
        $supplier->default_markup = $request->default_markup;
        $supplier->mobile         = $request->mobile;
        $supplier->fax            = $request->fax;
        $supplier->website        = $request->website;
        $supplier->street         = $request->street;
        $supplier->postcode       = $request->postcode;
        $supplier->city           = $request->city;
        $supplier->state          = $request->state;
        $supplier->country        = $request->country;
        $supplier->email          = $request->email;
        $supplier->phone          = $request->phone;
        $supplier->twitter        = $request->twitter;
        $supplier->instagram      = $request->instagram;
        $supplier->facebook       = $request->facebook;
        $supplier->snap_chat      = $request->snap_chat;

        $supplier->postal_street   = $request->postal_street;
        $supplier->postal_street2  = $request->postal_street2;
        $supplier->postal_suburb   = $request->postal_suburb;
        $supplier->postal_postcode = $request->postal_postcode;
        $supplier->postal_city     = $request->postal_city;
        $supplier->postal_state    = $request->postal_state;
        $supplier->postal_country  = $request->postal_country;
        $supplier->save();

        return new Response(true, $request);
    }
}
