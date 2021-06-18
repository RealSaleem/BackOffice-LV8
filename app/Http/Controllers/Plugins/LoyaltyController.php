<?php

namespace App\Http\Controllers\Plugins;
use App\Core\RequestExecutor;
use Illuminate\Http\Request;
use App\Models\Loyalty;
use App\Requests\Plugins\Loyalty\SaveLoyaltyRequest;
use Auth;
use App\Http\Controllers\Controller;

class LoyaltyController extends Controller
{
    public function __construct(RequestExecutor $requestExecutor)
    {
        parent::__construct();
        $this->RequestExecutor = $requestExecutor;
    }

    public function index()
    { 
        $loyalty = Loyalty::where('store_id', Auth::user()->store_id)->first();
        if($loyalty == null){
            $loyalty = [
                'cap_amount'            => 200,
                'points'                => 20,
                'redeem_rate'           => 10,
                'max_reword_discount'   => 50,
                'expire_after'          => '',
            ];
        }
        return view('plugins.loyalty.view', ['loyalty'=> $loyalty]);
    }

    public function edit()
    {
        $loyalty = Loyalty::where('store_id', Auth::user()->store_id)->first();
        if($loyalty == null){
            $loyalty = new Loyalty;
        }
        return view('plugins.loyalty.Loyalty', ['loyalty'=> $loyalty]);
    }

    public function save(Request $request)
    {
        $request = new SaveLoyaltyRequest;

        $response = $this->RequestExecutor->execute($request);
        return response()->json($response);
    }    
}