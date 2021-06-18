<?php

namespace App\Http\Controllers;

use App\Http\Controllers\Controller;
use App\Models\Store;
use App\Models\Industry;
use App\Models\Currency;
use App\Models\Language;
use Auth;
use http\Env\Request;

class StoreController extends Controller
{

    public function __construct()
    {
        parent::__construct();
    }

    public function index()
    {

        $data =[
            'store'=> \Auth::user()->store,
            'button' => 'Update',
        ];
        return view('outlets.index', $data);
    }

    public function edit($id)
    {
        $image = [];
        $lang = [];

        $store = Store::find($id);

        foreach (Auth::user()->store->languages as $lan) {
            array_push($lang, $lan->id);
        }

        $industries = Industry::get();
        $currencies = Currency::get();

        array_push($image, ['name' => $store->name, 'url' => $store->store_logo, 'size' => 0]);

        $data = [
            'languages'         => Language::get()->toArray(),
            'store_languages'  =>  $lang,
            'industries'        => $industries,
            'currencies'        => $currencies,
            'image'             => $image,
            'is_edit'           => true,
            'store'             => $store
        ];

        return view('store.edit', $data);
    }

    public function step1()
    {
        if (Auth::check() && Auth::user()->store != null) {
            return redirect()->route('backoffice.dashboard');
        }

        $data = [
            'industries' => Industry::get(),
            'currencies' => Currency::get(),
            'phone' => '+965 99732998',
            'email' => 'info@nextaxe.com'
        ];

        return view('setup.screen1', $data);
    }

    public function step2()
    {
        $data = [
            'phone' => '+965 99732998',
            'email' => 'info@nextaxe.com'
        ];

        return view('setup.screen2', $data);
    }

    public function step3()
    {

        $data = [
            'phone' => '+965 99732998',
            'email' => 'info@nextaxe.com'
        ];

        return view('setup.screen3', $data);
    }
}
