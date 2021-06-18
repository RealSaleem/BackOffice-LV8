<?php
namespace App\Http\Controllers\Plugins;
use App\Core\RequestExecutor;
use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Models\SalesTax;
use App\Models\City;
use App\Models\Currency;
use App\Requests\WebSettings\WebSettingsRequests;
use App\Requests\Plugins\SalesTax\SaveSalesTaxRequest;
use Auth;
class SalesTaxController extends Controller
{
    public function __construct(RequestExecutor $requestExecutor)
    {
        parent::__construct();
        $this->RequestExecutor = $requestExecutor;
    }

    public function index()
    { 
        $salestax = SalesTax::where('store_id', Auth::user()->store_id)->first();
        if($salestax == null){
            $salestax = new SalesTax;
        }
        return view('Plugins.salestax', ['salestax'=> $salestax]);
    }

    public function edit()
    {
        $salestax = SalesTax::where('store_id', Auth::user()->store_id)->first();
        $countries = Currency::with('cities')->get();
        $cities = City::get();

        if($salestax == null){
            $salestax = new SalesTax;
        }
        return view('plugins.salestax_edit', ['salestax'=> $salestax, 'countries'=> $countries,'cities'=> $cities]);
    }

    public function save(Request $request)
    {
        $request = new SaveSalesTaxRequest;
        $response = $this->RequestExecutor->execute($request);
        return response()->json($response);
    }    
}