<?php

namespace App\Http\Controllers\Reports;
use App\Http\Controllers\Controller;
use App\Helpers\AppsVisibilityHelper;
use App\Models\outlet;
use Auth;

class CustomerReportController extends Controller
{

    public function __construct()
    {
        parent::__construct();
    }

    public function index()
    {
        $store_id = Auth::user()->store_id;
        $outlets = Outlet::where('store_id' , $store_id)->get();
        $data = [
         'outlets' => $outlets
         ];

        return view('reports.customerreport.index',$data);
    }

}
