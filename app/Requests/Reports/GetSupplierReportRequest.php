<?php

namespace App\Requests\Reports;
use App\Core\BaseRequest as BaseRequest;
use App\Core\Response;
use App\Models\Supplier;
use Auth;


class GetSupplierReportRequest extends BaseRequest{
	public $outlet_id;
	public $daterange;
	public $date_filter;
    public $supplier_name;
    public $supplier_mobile;
    public $supplier_email;
    public $number_of_product;
}

class GetSupplierReportRequestHandler {

    public function Serve($request){

        $login_user = is_null(Auth::user())? \App::make('user') : Auth::user();

        $supplierObj = Supplier::where(['store_id' => $request->store_id, 'is_deleted' => 0]);

        // filter by supplier name
        if(isset($request->supplier_name) && $request->supplier_name != null){
            $supplierObj = $supplierObj->where('name', 'like', '%'.$request->supplier_name.'%');
        }

        // filter by supplier mobile number
        if(isset($request->supplier_mobile) && $request->supplier_mobile != null){
            $supplierObj = $supplierObj->where('mobile', 'like', '%'.$request->supplier_mobile.'%');
        }

        // filter by supplier email address
        if(isset($request->supplier_email) && $request->supplier_email != null){
            $supplierObj = $supplierObj->where('email', 'like', '%'.$request->supplier_email.'%');
        }

        //filter by products
        if(isset($request->number_of_product) && $request->number_of_product > 0){
            $supplierObj = $supplierObj->has('products', $request->number_of_product);
        }

        // filter products by date
        if( (isset($request->date_filter) && $request->date_filter != null) || (isset($request->daterange) && $request->daterange != null)){

        	$date_range = $this->getDateRange($request);

            $dates = explode(' - ',$date_range);
            $start_date = $dates[0];
            $end_date = $dates[1];

            $supplierObj->whereDate('created_at','>=',$start_date);
            $supplierObj->whereDate('created_at','<=',$end_date);
        } 

        $suppliers = $supplierObj->withCount('products')->get()->toArray();               

        return new Response(true, $suppliers);
    }   


    private function getDateRange($request){

    	$date_range = $request->date_filter;
        $date_filter = $request->date_filter;

    	if(isset($date_filter) && $date_filter == 'day') {
        
            $date_range = date('Y-m-d', strtotime('-1 days')).' - '.date('Y-m-d');

        } elseif ($date_filter == 'week') {

            $date_range = date('Y-m-d', strtotime('-7 days')).' - '.date('Y-m-d');

        } elseif ($date_filter == 'month') {
            
            $date_range = date('Y-m-d', strtotime('-30 days')).' - '.date('Y-m-d');
        }  else {
            $date_range = $request->daterange;
        }

        return $date_range;
    } 
} 