<?php

namespace App\Http\Controllers\Reports;
use App\Http\Controllers\Controller;
use App\Helpers\AppsVisibilityHelper;
use App\Models\Category;
use App\Models\Brand;
use App\Models\Supplier;


class StockReportController extends Controller
{

    public function __construct()
    {
        parent::__construct();
    }

    public function index()
    {
       $store_id = \Auth::user()->store_id;
        $data = AppsVisibilityHelper::get();
        $data['categories'] = Category::where(['store_id' => $store_id , 'active' => 1 , 'is_deleted' => 0])->get();
        $data['brands'] = Brand::where(['store_id' => $store_id , 'active' => 1 , 'is_deleted' => 0])->get();
        $data['suppliers'] = Supplier::where(['store_id' => $store_id , 'active' => 1 , 'is_deleted' => 0])->get();
        return view('reports.stockreport.index',$data);
        
    }

}
