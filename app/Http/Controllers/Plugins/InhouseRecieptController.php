<?php

namespace App\Http\Controllers\Plugins;
use App\Core\RequestExecutor;
use App\Http\Controllers\Controller;
use App\Models\Holidays;
use App\Models\TimeSlot;
use App\Models\WebSettings;
use App\Models\Plugin;
use App\Requests\WebSettings\WebSettingsRequests;
use Auth;
use Request;

class InhouseRecieptController extends Controller {
	public function __construct(RequestExecutor $requestExecutor) {
		parent::__construct();
		$this->RequestExecutor = $requestExecutor;
	}

	public function index(Request $request) {
		// dd(Request::route()->uri());
		$plugin = Plugin::where('slug' , 'inhouse-reciept')->first();
		 
		$store = Auth::user()->store;

		return view('plugins.inhouse-reciept',  ['table_count' => $store->table_count,'plugin_name' => $plugin->name,]);
	}

	public function add(\Illuminate\Http\Request $request) {
		
		$store = Auth::user()->store;
		
		if(isset($request->table_count) > 0 ){
			$store->table_count =  $request->table_count;
		}

		if($store->save()) {
			return redirect('plugins')->with('done', \Lang::get('toaster.inhouse_reciept'));
		} else {
			return redirect()->back()->with('done', \Lang::get('toaster.inhouse_reciept_error'));
		}
	}

}