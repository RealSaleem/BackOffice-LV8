<?php

namespace App\Http\Controllers\Plugins;
use App\Core\RequestExecutor;
use App\Http\Controllers\Controller;
use App\Models\Holidays;
use App\Models\TimeSlot;
use App\Models\WebSettings;
use App\Requests\WebSettings\WebSettingsRequests;
use Auth;	
use Request;

class DeliveryTimeController extends Controller {
	public function __construct(RequestExecutor $requestExecutor) {
		parent::__construct();
		$this->RequestExecutor = $requestExecutor;
	}

	public function index(WebSettingsRequests $request) {
		$request->store_id = Auth::user()->store_id;
		$response = $this->RequestExecutor->execute($request);

		$do_not = json_decode($response->Payload->do_not_deliver);
		$Holidays = Holidays::where('store_id', Auth::user()->store_id)->get();
		$TimeSlot = TimeSlot::where('store_id', Auth::user()->store_id)->get();
		$holis = [];
		foreach ($Holidays as $hol) {
			array_push($holis, $hol->date);
		}
		$times = [];
		foreach ($TimeSlot as $tim) {
			array_push($times, $tim->start_time . ' - ' . $tim->end_time);
		}

		if (is_null($do_not)) {
			$do_not = (object) [
				'Sunday' => '',
				'Monday' => '',
				'Tuesday' => '',
				'Wednesday' => '',
				'Thursday' => '',
				'Friday' => '',
				'Saturday' => '',
			];
		}

		$data = [
			'model' => $response->Payload,
			'do_not_deliver' => $do_not,
			'holidays' => implode(",", $holis),
			'time_slots' => implode(",", $times),
			'time_sl' => $times,
		];

		// dd($data);

		return view('plugins.delivery-time', $data);
	}
	
	public function add(\Illuminate\Http\Request $request) {
		// dd($request);
		$store = WebSettings::where('store_id', Auth::user()->store_id)->first();

		$data = [
			'Sunday' => isset($request->sunday) ? 0 : '',
			'Monday' => isset($request->monday) ? 1 : '',
			'Tuesday' => isset($request->tuesday) ? 2 : '',
			'Wednesday' => isset($request->wednesday) ? 3 : '',
			'Thursday' => isset($request->thursday) ? 4 : '',
			'Friday' => isset($request->friday) ? 5 : '',
			'Saturday' => isset($request->saturday) ? 6 : '',
		];

		$same_day_delivery = $request->same_day_delivery;
		$store->do_not_deliver = json_encode($data);
		
		if($same_day_delivery == 'on'){
			$store->same_day_delivery = 1;
		}

		Holidays::where('store_id', Auth::user()->store_id)->delete();
		if ($request->holidays != null) {
			$holidays = explode(',', $request->holidays);
			foreach ($holidays as $holi) {
				$holiday = new Holidays();
				$holiday->store_id = Auth::user()->store_id;
				$holiday->created = Auth::user()->id;
				$holiday->date = $holi;
				$holiday->save();
			}
		}

		TimeSlot::where('store_id', Auth::user()->store_id)->delete();

		if ($request->time_slots != null) {
			$time_slots = explode(',', $request->time_slots);
			$start = '';
			$end = '';
			foreach ($time_slots as $slot) {
				$time_s = explode('- ', $slot);
				if (isset($time_s[0])) {
					$start = $time_s[0];
				}
				if (isset($time_s[1])) {
					$end = $time_s[1];
				}
				$time_slot = new TimeSlot();
				$time_slot->store_id = Auth::user()->store_id;
				$time_slot->created = Auth::user()->id;
				$time_slot->start_time = $start;
				$time_slot->end_time = $end;
				$time_slot->save();
			}
		}
		if ($store->save()) {
			return redirect('plugins')->with('done', \Lang::get('toaster.delivery_time'));
		} else {
			return redirect()->back()->with('done', \Lang::get('toaster.delivery_time_error'));
		}
	}

}