<?php

namespace App\Http\Controllers\Outlets;

use App\Core\Response;
use App\Http\Controllers\Controller;
use App\Models\Register;
use App\ViewModels\OutletsFormViewModel;
use App\Core\RequestExecutor;
use Illuminate\Http\Request;
use App\Models\Outlet;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Session;
use Laminas\Diactoros\Response\JsonResponse;

class OutletsController extends Controller
{

    /**
     * @var RequestExecutor
     */
    private $RequestExecutor;

    public function __construct(RequestExecutor $requestExecutor)
    {
        // parent::__construct();
        $this->RequestExecutor = $requestExecutor;
    }

    public function index()
    {
        $data['store'] = \Auth::user()->store;
        return view('outlets.index',$data);

    }

    public function create()
    {
        $data = OutletsFormViewModel::load(0);
        return view('outlets.form',$data);
    }

    public function edit($id)
    {
        $data = OutletsFormViewModel::load($id);
        return view('outlets.form',$data);
   }

    public function show($id)
    {
        $data = OutletsFormViewModel::load($id);
        return view('outlets.view',$data);
    }
    public function business_hours(Request $request, $id)
    {
       $outlet = Outlet::find($request->id);
       $outlet->business_hours = json_decode($outlet->business_hours ?? '' ,true);
       $data =[
            'outlet'=>$outlet,
            'route' => url('business_hours').'/'.$outlet->id.'/store',
        ];
      return view('outlets.out_business_hours' , $data);

    }
    public function business_hours_store(Request $request, $outlet_id)
    {
        $outlet = Outlet::find($request->id);
        $outlet->business_hours = json_encode((object)$request->day);
        $outlet->save();
        return redirect()->route('outlets.index')->with('done', 'Business Hours has been updated');
    }
    
    public function updateRegister(Request $request)
    {
        $id = $request->id;
        $oldRegName = $request->outlet_register_old_name;
        $register = Register::find($id);
        $register->name = $oldRegName;
        $result = $register->save();
        if($result){
            Session::flash('success','Register has been updated');
            return redirect()->back();
        }else{
            return false;
        }
    }
}
