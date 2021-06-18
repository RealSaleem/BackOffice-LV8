<?php

namespace App\Http\Controllers\Reports;

use App\Models\Store;
use Illuminate\Http\Request;
use App\Core\RequestExecutor;
use App\Models\CustomerGroup;
use App\Http\Controllers\Controller;
use Illuminate\Support\Facades\Auth;
use App\Requests\Reports\SalesReportRequest;
use App\Requests\Reports\GetUserReportRequest;
use App\Requests\Reports\GetAddonReportRequest;
use App\Requests\Reports\GetBrandReportRequest;
use App\Requests\Reports\GetPaymentReportRequest;
use App\Requests\Reports\GetProductReportRequest;
use App\Requests\Reports\GetCategoryReportRequest;
use App\Requests\Reports\GetCustomerReportRequest;
use App\Requests\Reports\GetRegisterReportRequest;
use App\Requests\Reports\GetSupplierReportRequest;
use App\Requests\Reports\GetInventoryReportRequest;
use App\Requests\Reports\GetCustomerGroupReportRequest;

class ReportsController extends Controller
{

    public function __construct(RequestExecutor $requestExecutor)
    {
        // parent::__construct();
        $this->RequestExecutor = $requestExecutor;
    }

    public function index(Request $request)
    {
        $params = $request->all();
        //        dd($params);

        $store = Store::with('outlets')->where('id', \Auth::user()->store->id)->select('id', 'name')->first();

        // show by default sales summary
        $sales = [];
        if ((isset($params['report_type']) &&  $params['report_type'] == "summary") || !isset($params['report_type'])) {
            $sales = $this->get_sales_report($params);
        }

        //inventory reports
        if (isset($params['report_type']) && $params['report_type'] == "inventory") {
            $this->get_inventory_report($params);
        }

        //Payment reports
        $payment_methods = [];
        if (isset($params['report_type']) && $params['report_type'] == "payment") {
            $payment_methods = $this->get_payment_report($params);
        }

        //products reports
        $products = [];
        if (isset($params['report_type']) && $params['report_type'] == "product") {
            $products = $this->get_product_report($params);
        }

        // inventory reports
        $inventory_reports = [];
        if (isset($params['report_type']) && $params['report_type'] == "inventory") {
            $inventory_reports = $this->get_inventory_report($params);
        }

        // register reports
        $register_reports = [];
        if (isset($params['report_type']) && $params['report_type'] == "register") {
            $register_reports = $this->get_register_report($params);
        }

        //category reports
        $category_reports = [];
        if (isset($params['report_type']) && $params['report_type'] == "category_report") {
            $category_reports = $this->get_category_report($params);
        }

        //supplier reports
        $supplier_reports = [];
        if (isset($params['report_type']) && $params['report_type'] == "supplier_report") {
            $supplier_reports = $this->get_supplier_report($params);
        }

        //brand reports
        $brand_reports = [];
        if (isset($params['report_type']) && $params['report_type'] == "brand") {
            $brand_reports = $this->get_brand_report($params);
        }

        //customer group request
        $customer_group_reports = [];
        if (isset($params['report_type']) && $params['report_type'] == "customer_group") {
            $customer_group_reports = $this->get_customer_group_report($params);
        }

        //user reports
        $user_reports = [];
        if (isset($params['report_type']) && $params['report_type'] == "user_report") {
            $user_reports = $this->get_user_report($params);
        }

        //customer reports
        $customer_reports = [];
        if (isset($params['report_type']) && $params['report_type'] == "customer_report") {
            $customer_reports = $this->get_customer_report($params);
        }

        //customer reports
        $addon_reports = [];
        if (isset($params['report_type']) && $params['report_type'] == "addon_report") {
            $addon_reports = $this->get_addon_report($params);
        }

        $data = [

            'outlets'                   => $store->outlets,
            'categories'                => isset($products['category']) ? $products['category'] : [],
            'brands'                    => isset($products['brand']) ? $products['brand'] : [],
            'suppliers'                 => isset($products['supplier']) ? $products['supplier'] : [],
            'products'                  => isset($products['products']) ? $products['products'] : [],
            'payment_methods'           => isset($payment_methods['payment_method']) ? $payment_methods['payment_method'] : [],
            'payment_reports'           => isset($payment_methods['transactions']) ? $payment_methods['transactions'] : [],
            'customer_groups'           => $customer_reports != null ? $customer_reports['groups'] : [],
            'countries'                 => [],
            'filters'                   => $params != null ? $params : null,
            'sales'                     => $sales,
            'inventory_reports'         => $inventory_reports,
            'register_reports'          => $register_reports,
            'category_reports'          => $category_reports,
            'supplier_reports'          => $supplier_reports,
            'brand_reports'             => $brand_reports,
            'customer_group_reports'    => $customer_group_reports,
            'user_reports'              => $user_reports,
            'customer_reports'          => $customer_reports,
            'addon_reports'             => $addon_reports
        ];

        return view('Reports.index', $data);
    }

    public function get_sales_report($request)
    {

        $request = new SalesReportRequest();
        $request->user = Auth::user();
        $response = $this->RequestExecutor->execute($request);

        return $response->Payload;
    }

    public function get_payment_report($request)
    {
        $request = new GetPaymentReportRequest();
        $request->store_id = Auth::user()->store_id;
        $request->user = Auth::user();
        $response = $this->RequestExecutor->execute($request);
        // dd($response);
        return $response->Payload;
    }
    public function get_inventory_report($request)
    {
        $request = new GetInventoryReportRequest();
        $request->store_id = Auth::user()->store_id;
        $response = $this->RequestExecutor->execute($request);
        // dd($response);
        return $response->Payload;
    }
    public function get_product_report($request)
    {
        $request = new GetProductReportRequest();
        $request->store_id = Auth::user()->store_id;
        $response = $this->RequestExecutor->execute($request);
        // dd($response);
        return $response->Payload;
    }
    public function get_category_report()
    {
        $request = new GetCategoryReportRequest();
        $request->store_id = Auth::user()->store_id;
        $response = $this->RequestExecutor->execute($request);
        // dd($response);
        return $response->Payload;
    }
    public function get_register_report()
    {
        $request = new GetRegisterReportRequest();
        $request->store_id = Auth::user()->store_id;
        $response = $this->RequestExecutor->execute($request);
        // dd($response);
        return $response->Payload;
    }
    public function get_customer_report()
    {
        $request = new GetCustomerReportRequest();
        $request->store_id = Auth::user()->store_id;
        $response = $this->RequestExecutor->execute($request);
        // dd($response);
        return $response->Payload;
    }
    public function get_customer_group_report()
    {
        $request = new GetCustomerGroupReportRequest();
        $request->store_id = Auth::user()->store_id;
        $response = $this->RequestExecutor->execute($request);
        // dd($response);
        return $response->Payload;
    }
    public function get_supplier_report()
    {
        $request = new GetSupplierReportRequest();
        $request->store_id = Auth::user()->store_id;
        $response = $this->RequestExecutor->execute($request);
        // dd($response);
        return $response->Payload;
    }
    public function get_brand_report()
    {
        $request = new GetBrandReportRequest();
        $request->store_id = Auth::user()->store_id;
        $response = $this->RequestExecutor->execute($request);
        // dd($response);
        return $response->Payload;
    }
    public function get_user_report()
    {
        $request = new GetUserReportRequest();
        $request->store_id = Auth::user()->store_id;
        $response = $this->RequestExecutor->execute($request);
        // dd($response);
        return $response->Payload;
    }
    public function get_addon_report()
    {
        $request = new GetAddonReportRequest();
        $request->store_id = Auth::user()->store_id;
        $response = $this->RequestExecutor->execute($request);
        // dd($response);
        return $response->Payload;
    }
}
