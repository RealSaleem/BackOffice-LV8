<?php

namespace App\Http\Controllers\Apps;
use App\Models\Apps;
use App\Models\Store;
use App\Core\Response;
use App\Models\StoreApp;
use App\Models\StorePlugin;
use Illuminate\Http\Request;
use App\Core\RequestExecutor;
use App\Http\Controllers\Controller;
use Illuminate\Support\Facades\Auth;
use App\Requests\Apps\GetAllAppsRequest;

class AppsController extends Controller
{

    public function __construct(RequestExecutor $requestExecutor)
    {
        // parent::__construct();
        $this->RequestExecutor = $requestExecutor;
    }

   public function index(Request $request)
    {
        $store_id = Auth::user()->store_id;
        $apps= StoreApp::where('store_id', $store_id)->with('app')->get();
        return view('apps.index',['apps'   => $apps ]);
    }
    public function app_Store(){
        $apps = Apps::get();
        return view('apps.app_store',compact('apps'));
    }


    public function appPurchase(Request $request){
        $id = $request->id;
        $app = Apps::find($id);
        if($app != null){
            $store_id = Auth::user()->store_id;
            $check_exist = StoreApp::where('store_id',$store_id)->where('app_id',$id)->get();

            if(count($check_exist) > 0){
                return response()->json(array('error' => 'App Already Purchased.' ));
            }else{
                $app = new StoreApp();
                $app->store_id  = $store_id;
                $app->app_id    = $id;
                $app->active    = 0;
                $message = $app->name.\Lang::get('toaster.app_purchased');

                if($app->save()){
                    return response()->json(['message' => $message ]);
                }
            }
            return response()->json(array('error' => \Lang::get('toaster.app_already_purchased') ));
        }
        return response()->json(array('error' => \Lang::get('toaster.app_notFound') ));
    }

    public function toggle(Request $request)
    {
        $id = $request->id;
        $store_id = Auth::user()->store_id;
        $purshased_app = StoreApp::where('store_id', $store_id)->where('id', $id)->first();

        if($purshased_app != null){
            $purshased_app->active = 1;
            $purshased_app->save();

            $data = [
              'response' => true,
              'message' => \Lang::get('toaster.app_installed'),
            ];
            return response()->json($data);
        }
        $data = [
          'response' => false,
          'message' => \Lang::get('toaster.app_unableTo_installed'),
        ];
        return response()->json($data);
    }
}
