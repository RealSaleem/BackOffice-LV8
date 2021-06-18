<?php

namespace App\Requests\Store;

use App\Core\BaseRequest as BaseRequest;
use App\Core\Response;
use App\Models\Store as Store;
use App\Models\User;
use App\Models\City;
use App\Models\Industry;
use Auth;
use App\Models\Role;
use App\Models\Permission;

use App\Models\UsersStore;
use App\Models\Category;
use App\Models\Brand;
use App\Models\Supplier;
use App\Models\Language;
use App\Models\PaymentMethod;
use App\Models\ShippingMethod;
use App\Models\Customer;
use App\Models\Outlet;
use App\Models\Register;

use Mail;
use App\Mail\StoreUpdated;
use DB;

use App\Helpers\AppsVisibilityHelper;

class EditStoreRequest extends BaseRequest
{

    public $id;
    public $name;
    public $default_currency;
    public $industry_id;
    public $sku_generation;
    public $current_sequence_number;
    public $stock_threshold;
    public $round_off;
    public $language_ids;

    public $contact_name;
    public $email;
    public $phone;

    public $website;
    public $twitter;
    public $instagram;
    public $facebook;
    public $snap_chat;

    public $reciept_disclaimer;
    public $store_logo;

    public $images = [];

    /*
    public $store_url;
    public $timezone;
    public $private_url;
    public $physical_street_1;
    public $physical_street_2;
    public $physical_suburb;
    public $physical_city;
    public $physical_postcode;
    public $physical_state;
    public $physical_country;
    public $postal_street_1;
    public $postal_street_2;
    public $postal_suburb;
    public $postal_city;
    public $postal_postcode;
    public $postal_state;
    public $postal_country;
    */

}

class EditStoreRequestValidator
{
    public function GetRules()
    {
        return [
            'name'                    => 'required|string|max:45',
            'default_currency'        => 'required|string|max:45',
            'industry_id'             => 'required|integer',

            'contact_name'            => 'required|string|max:45',
            'email'                   => 'required|email|max:45',
            'phone'                   => 'required',

            //'store_logo'              => 'required|string|max:1000',
            'reciept_disclaimer'      => 'required|string|max:1000',
        ];
    }
}

class EditStoreRequestHandler
{
    public function Serve($request)
    {
//        dd($request->language_ids);
        try{
            DB::beginTransaction();

            $user = Auth::user();

            $request->current_sequence_number = ($request->current_sequence_number == null)? 1000:$request->current_sequence_number;
            $request->stock_threshold = ($request->stock_threshold == null)? '100':$request->stock_threshold;
            $request->round_off = ($request->round_off == null)? '2':$request->round_off;

            $store = Store::find($request->id);

            $is_new_store = false;

            if (is_null($store)) {

                $store = new Store();
                $is_new_store = true;
                $store->is_active   = 0;
            }

            $store->name                    = $request->name;
            $store->default_currency        = isset( $request->default_currency ) ? $request->default_currency : $store->default_currency;
            $store->sku_generation          = $request->sku_generation;
            $store->current_sequence_number = $request->current_sequence_number;

            $store->contact_name      = $request->contact_name;
            $store->email             = $request->email;
            $store->phone             = $request->phone;

            /*
                $store->timezone                = $request->timezone;
                $store_url = strtolower($store->name);
                $store_url = str_replace(' ', '-', $store_url);
                $store_url = $store_url . ".nextaxe.com";
                $store->private_url       = $store_url;
                $store->twitter           = $request->twitter;
                $store->instagram         = $request->instagram;
                $store->facebook          = $request->facebook;
                $store->snap_chat         = $request->snap_chat;
                $store->website           = $request->website;
            */

            $store->stock_threshold   = $request->stock_threshold;
            $store->reciept_disclaimer= $request->reciept_disclaimer;
            $store->round_off         = $request->round_off;
            $store->industry_id       = $request->industry_id;

            if(sizeof($request->images) > 0){
                $store->store_logo        = $request->images[0]['path'];
            }

            $industry = Industry::find($store->industry_id);

            $store->product_type = $industry->product_type;

            $store->save();

            if(is_array($request->language_ids) && sizeof($request->language_ids) > 0)
            {
//                dd($request->language_ids);
                $store->languages()->detach();
                sort($request->language_ids,SORT_NATURAL);
                $ids=  isset($request->language_ids) ? array_unique($request->language_ids) : 1;
                $store->languages()->attach($ids);
            }

            if (is_null($user->store_id) || empty($user->store_id))
            {
                $user->store_id = $store->id;
                $user->save();

                $newCustomerGroup = new CustomerGroup;
                $newCustomerGroup->name = 'General Customer Group';
                $newCustomerGroup->store_id = $store->id;
                $newCustomerGroup->save();

            }

            $this->setupRequired($user, $store);

            $this->optionalSetup($user ,$store);

            DB::commit();

            try{
                $emailData = new StoreUpdated($user);
                Mail::to($user->email)->queue($emailData);
                // Mail::to($user->email)->send($emailData);
            }catch(\Exception $ex){
            }
            return new Response(true, $store,null,null,\Lang::get('toaster.store_updated'));


        }catch(Exception $ex){

            DB::rollBack();

            return new Response(false,null,$ex->getMessage());
        }
    }

    private function optionalSetup($user, $store )
    {
        $data = AppsVisibilityHelper::get();

        $store_id = $store->id;

        $brand = Brand::where(['store_id' => $store_id])->first();

        if(is_null($brand)){
            $brand = new Brand();
            $brand->name = 'General';
            $brand->store_id = $store_id;
            $brand->save();
        }

        $category = Category::where(['store_id' => $store_id])->first();

        if(is_null($category)){
            $category = new Category();
            $category->name = 'General';
            $category->store_id = $store_id;
            $category->pos_display = true;
            $category->web_display = true;
            $category->dinein_display = true;
            $category->is_deleted = false;
            $category->sort_order = 1;
            $category->save();
        }

        $supplier = Supplier::where(['store_id' => $store_id])->first();

        if(is_null($supplier)){
            $supplier = new Supplier();
            $supplier->name = 'General';
            $supplier->store_id = $store_id;
            $supplier->save();
        }

        if($data['has_website'] || $data['has_mobile'])
        {
            $payment = PaymentMethod::where(['store_id' => $store_id])->first();

            if(is_null($payment)){
                $PaymentGateways = new PaymentMethod;
                $PaymentGateways->name =  'Cash on Delivery';
                $PaymentGateways->description = 'Cash on Delivery';
                $PaymentGateways->slug = 'cashondelivery';
                $PaymentGateways->store_id = $store_id;
                $PaymentGateways->save();
            }

            $shipping = ShippingMethod::where(['store_id' => $store_id])->first();

            if(is_null($shipping)){
                $ShippingMethods = new ShippingMethod;
                $ShippingMethods->name                    =     'Free Shipping';
                $ShippingMethods->rate                    =     '';
                $ShippingMethods->delivery_time           =     '5 Working days';
                $ShippingMethods->disclaimer              =     '';
                $ShippingMethods->store_id                =     $store_id;
                $ShippingMethods->is_active                =    1;
                $ShippingMethods->save();
            }
        }

        return true;
    }

    private function setupRequired($user, $store)
    {
        $store_id = $store->id;

        $outlet = Outlet::where(['store_id' => $store_id])->first();

        if(is_null($outlet)){
            $outlet = new Outlet;
            $outlet->store_id         = $store_id;
            $outlet->name             = 'Main Outlet';
            $outlet->phone            = $store->phone;
            $outlet->email            = $store->email;
            $outlet->longitude        = 0;
            $outlet->latitude         = 0;
            $outlet->is_active        = 1;

            $outlet->save();

            if(count($user->usr_outlets) > 0){
                $user->outlets()->detach();
            }
            $user->outlets()->attach($outlet->id);

            $register = Register::where(['outlet_id' => $outlet->id])->first();

            if(is_null($register)){
                $newRegister                             = new Register;
                $newRegister->outlet_id                  = $outlet->id;
                $newRegister->name                       = 'General Register';
                $newRegister->save();
            }
        }

        $languages = $store->languages->toArray();

        if(!(is_array($languages) && sizeof($languages) > 0 )){
            $english = Language::where(['name' => 'English'])->first();
            $store->languages()->attach($english->id);

        }else{
            /*
                $names = array_column($languages, 'name');
                if(!in_array('English', $names)){
                    $english = Language::where(['name' => 'English'])->first();
                    $store->languages()->attach($english->id);
                }
            */
        }
    }
}
