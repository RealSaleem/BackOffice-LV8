<?php

namespace App\Http\Controllers\DeliveryZones;

use App\Core\RequestExecutor;
use Illuminate\Http\Request;
use Auth;
use App\Requests\OutletZone\GetAllOutletZoneRequest;
use App\Requests\OutletZone\AddOutletZoneRequest;
use App\Requests\OutletZone\EditOutletZoneRequest;
use App\Requests\OutletZone\GetOutletZoneRequest;
use App\Requests\OutletZone\DeleteOutletZoneRequest;

use App\Models\Currency;
use App\Models\City;
use App\Models\CityAreas;
use App\Models\OutletZone;
use App\Models\Holidays;
use App\Models\TimeSlot;
use App\Http\Controllers\Controller;



class DeliveryZonesController extends Controller
{
    /**
     * Create a new controller instance.
     *
     * @return void
     */
    public function __construct(RequestExecutor $requestExecutor)
    {
        parent::__construct();
        $this->RequestExecutor = $requestExecutor;
    }

    /**
     * Show the application dashboard.
     *
     * @return \Illuminate\Http\Response
     */
    public function index(Request $request, $id)
    {
      $request = new GetAllOutletZoneRequest;
      $request->outlet_id = $id;
      $request->store_id = Auth::user()->store_id;
      $response = $this->RequestExecutor->execute($request);

      $data = [
          'outlet' =>  isset($response->Payload['outlet']) ? $response->Payload['outlet'] : '',
          'zones' =>  isset($response->Payload['zones']) ? $response->Payload['zones'] : '',
        ];
      return view('deliveryzone.delivery_zone' , $data);
    }
    public function getArea($city, $country)
    {
        $curl = curl_init();
        curl_setopt($curl, CURLOPT_URL,'https://maps.googleapis.com/maps/api/place/textsearch/json?query=places+'.$city.'+'.$country.'&key=AIzaSyDRCd_mF3VtrWp8rdRtnOjZkdE9P_-kxRc');
        curl_setopt($curl, CURLOPT_POST, 1);
        curl_setopt($curl, CURLOPT_RETURNTRANSFER, true);
        curl_setopt($curl, CURLOPT_SSL_VERIFYPEER, false);
        curl_setopt($curl, CURLOPT_SSL_VERIFYHOST, false);
        curl_setopt($curl, CURLOPT_POSTFIELDS, false);
        $result = curl_exec($curl);
        $info = curl_getinfo($curl);
        curl_close($curl);
        $json = json_decode($result, true);
        $address = [];
        if( $json != null && is_array($json) &&  is_array($json['results']) && $json['results'] != null && sizeof($json['results']) > 0){
            foreach ($json['results'] as $record) {
              if(sizeof($record) > 1){
                array_push($address, $record['formatted_address']);
              }
          }
        }
        return $address;
    }
    public function saveArea($city, $country, $address)
    {
      foreach (array_unique($address) as $add) {
          $c_area = new CityAreas;
          $c_area->city_id = $city->id;
          $c_area->name = $add;
          $c_area->save();
      }
    }
    public function add_areas_of_all_cities(Request $request)
    {
      $countries = Currency::with('cities')->get();
      $cities = City::get();
      $country = [];
      $city = [];
      $address = [];

      foreach ($countries as $cntry) {
        $country = strtolower($cntry->country);
        foreach ($cntry->cities as $cty) {
          $city = strtolower($cty->city);
          $areas = CityAreas::where('city_id' ,$cty->id)->first();
          if($areas == null){
            $address = $this->getArea(preg_replace('/\s+/', '', $city), $country);
            $this->saveArea($cty, $country, $address);
          }
        }
      }
    }

    public function import_data(Request $request)
    {
        $file = $request->file('imported-file');
        if ($file->getClientOriginalExtension() == 'xlsx') {
            $path = $request->file('imported-file')->getRealPath();
            $data = \Excel::load($path)->get();
             $country = null;
              $city = null;
            foreach ($data[0] as $sheet) {
              // dd($sheet['country'],$sheet['cities'],$sheet['area']);
              if($sheet['country'] != null){
                $country = Currency::where('country' , $sheet['country'] )->first();
                if(isset($country) && $country != null){

                }else{
                  $country = new Currency;
                  $country->country = $sheet['country'];
                  $country->save();
                }
              }
              if( $sheet['cities'] != null){
                $city = City::where('city' , $sheet['cities'] )->first();
                if(isset($city) && $city != null){

                }else{
                  $city = new City;
                  $city->city = $sheet['cities'];
                  $city->country_id = $country->id;
                  $city->save();
                }
              }
              if( $sheet['area'] != null && $sheet['area']  != 'N/A'){
                $area = CityAreas::where('name' , $sheet['area'] )->first();
                if(isset($area) && $area != null){

                }else{

                  if(sizeof(explode('$', $sheet['area'])) > 1){
                    $areass = explode('$', $sheet['area']);
                    foreach ($areass as $ara) {
                      if($ara != null){
                          $area = new CityAreas;
                          $area->name = ucfirst(trim($ara));
                          $area->city_id = $city->id;
                          $area->save();
                      }
                    }
                  }else{
                    $area = new CityAreas;
                    $area->name = $sheet['area'];
                    $area->city_id = $city->id;
                    $area->save();
                  }
                }
              }
              // dd($country,$city,$area);
            }

        }
        return redirect()->back();
    }

    public function add(Request $request, $id)
    {
      $request = new GetAllOutletZoneRequest;
      $request->outlet_id = $id;
      $request->store_id = Auth::user()->store_id;
      $response = $this->RequestExecutor->execute($request);

      $countries = Currency::withCount('countries')->with('cities')->orderBy('id', 'ASC')->get();
      $cities = City::withCount('cities')->orderBy('id', 'ASC')->get();
      // $areas = CityAreas::withCount('areas')->orderBy('id', 'ASC')->get();
      $zone = isset($response->Payload['zone']) ? $response->Payload['zone'] : new OutletZone;

      $zones = OutletZone::with('areas')->withCount('areas')->where(['store_id' => Auth::user()->store_id, 'outlet_id' => $id])->get();
      $already_assign_areas = [];
      foreach ($zones as $zon) {
        foreach ($zon->areas as $area) {
          array_push($already_assign_areas, $area->area);
        }
      }
      $areas = CityAreas::whereNotIn('id', $already_assign_areas)->orderBy('id', 'ASC')->get();


      $countries_id  = [];
      if(isset($zone->counties) ){
         foreach ($zone->counties as $country) {
          array_push($countries_id, $country->country_id);
        }
       }
      $cities_id  = [];
       if(isset($zone->cities) ){
         foreach ($zone->cities as $city) {
          array_push($cities_id, $city->city_id);
        }
       }
      $areas_ids  = [];
      if(isset($zone->areas) ){
        foreach ($zone->areas as $area) {
          array_push($areas_ids, $area->area);
        }
      }

      $data = [
          'outlet' =>  isset($response->Payload['outlet']) ? $response->Payload['outlet'] : '',
          'zone' =>  $zone,
          'zone_name' => 'Add Delivery Zone',
          'countries_id'=> $countries_id,
          'cities_id'=> $cities_id,
          'areas_ids'=> $areas_ids,
          'countries'=> $countries,
          'cities'=> $cities,
          'areas'=> $areas,
          'button'=> 'Submit',
          'is_edit'=> false,
          'route' => url('delivery_zones').'/'.$id.'/store',
          // 'already_assign_areas'=> $already_assign_areas,
      ];

      return view('deliveryzone.form_delivery_zone' , $data);
    }

    public function store(Request $request, $outlet_id)
    {
      // dd($request);
      $areas = isset($request->area) ? $request->area : [];
      $business_hours =$request->day;

      $request = new AddOutletZoneRequest;
      $request->areas               = $areas;
      $request->business_hours      = $business_hours;
      $request->outlet_id           = $outlet_id;
      $request->store_id            = Auth::user()->store_id;

      $response = $this->RequestExecutor->execute($request);

      if($response->IsValid){
        return redirect()->to('delivery_zones/'.$outlet_id)->with('done', 'Zone has been added');

      }else{
        return redirect()->back()->with('error', $response->Errors[0]);
      }
    }

    public function edit(Request $request, $outlet_id, $id )
    {
      $request = new GetOutletZoneRequest;
      $request->outlet_id = $outlet_id;
      $request->zone_id = $id;
      $request->store_id = Auth::user()->store_id;
      $response = $this->RequestExecutor->execute($request);

      $countries = Currency::withCount('countries')->with('cities')->orderBy('id', 'ASC')->get();
      $cities = City::withCount('cities')->orderBy('id', 'ASC')->get();
      $areas = CityAreas::withCount('areas')->orderBy('id', 'ASC')->get();
      $zone = isset($response->Payload['zone']) ? $response->Payload['zone'] : '';

      $already_assign_areas = [];

      $countries_id  = [];
       if(isset($zone->counties) ){
         foreach ($zone->counties as $country) {
          array_push($countries_id, $country->country_id);
        }
       }
      $cities_id  = [];
       if(isset($zone->cities) ){
         foreach ($zone->cities as $city) {
          array_push($cities_id, $city->city_id);
        }
       }
      $areas_ids  = [];
       if(isset($zone->areas) ){
         foreach ($zone->areas as $area) {
          array_push($areas_ids, $area->area);
        }
       }
      $data = [
          'outlet' => isset($response->Payload['outlet']) ? $response->Payload['outlet'] : '' ,
          'zone' =>  $zone,
          'zone_name' =>  isset($zone->name) ? $zone->name :'',
          'countries_id'=> $countries_id,
          'cities_id'=> $cities_id,
          'areas_ids'=> $areas_ids,
          'countries'=> $countries,
          'cities'=> $cities,
          'areas'=> $areas,
          'button'=> 'Update',
          'is_edit'=> true,
          'day' => json_decode($zone->business_hours ?? '' ,true),
          'route' => url('delivery_zones').'/'.$outlet_id.'/update/'.$id,
        ];
//         dd( $data);
      return view('deliveryzone.form_delivery_zone' , $data);
    }

    public function update(Request $request, $outlet_id, $id )
    {
//       dd($request);
      $areas = isset($request->area) ? $request->area : [];
      $business_hours =$request->day;

      $request = new EditOutletZoneRequest;
      $request->areas               = $areas;
      $request->business_hours      = $business_hours;
      $request->outlet_id           = $outlet_id;
      $request->zone_id             = $id;
      $request->store_id            = Auth::user()->store_id;

      $response = $this->RequestExecutor->execute($request);
      $response->outlet_id = $request->outlet_id;


      if($response->IsValid){
        return redirect()->to('delivery_zones/'.$outlet_id)->with('done', 'Zone has been updated');

      }else{
        return redirect()->back()->with('error', $response->Errors[0]);
      }
    }

//    public function delete(Request $request, $outlet_id, $id )
    public function delete(Request $request)
    {
//        dd((int)$request->zone_id);
      $request = new DeleteOutletZoneRequest;
      $request->outlet_id           =  $request->outlet_id;
      $request->zone_id             =  (int)$request->zone_id;
      $request->store_id            = Auth::user()->store_id;

      $response = $this->RequestExecutor->execute($request);

      if($response->IsValid){
        return redirect()->to('delivery_zones/'.$request->outlet_id)->with('done', 'Zone has been deleted');

      }else{
        return redirect()->back();
      }
    }
}
