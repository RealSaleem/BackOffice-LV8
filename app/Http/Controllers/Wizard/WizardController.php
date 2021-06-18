<?php

namespace App\Http\Controllers\Wizard;

use Illuminate\Http\Request;
use App\Http\Controllers\Controller;
// use App\Requests\Customers\Customer\GetAllCustomerRequest;
use App\Core\RequestExecutor;

use Auth;

class WizardController extends Controller
{

    public function __construct(RequestExecutor $requestExecutor)
    {
        $this->RequestExecutor = $requestExecutor;
    }

    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */

    public function add1()
    {

        // $currencies = Currency::all();

        return view('wizard.screen1');
    }

    /**
     * Show the form for creating a new resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function add2()

    {
        
        return view('wizard.screen2');
    }
     public function add3()
     { 
        
              
        return view('wizard.screen3');
     }


    /**
     * Store a newly created resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
}