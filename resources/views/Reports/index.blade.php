@extends('layouts.backoffice')
@section('content')
    @php
        $per = '\App\Helpers\Helper';
    @endphp
    <link rel="stylesheet" type="text/css"
          href="https://cdn.datatables.net/responsive/2.2.7/css/responsive.dataTables.min.css">
    <style type="text/css">
        div.container {
            max-width: 1200px
        }

        .date2 {
            display: inline-block;
        }

    </style>
    <div class="row">
        <div class="col-sm-12">
            <div class="greybg1 rounded p-4 mb-3">
                <div class="row">
                    <div class="col-sm-12">
                        <div class="common_title">
                            <h1>{{ __('backoffice.reports') }}</h1>
                            <div class="filter-icons d-inline-block d-md-none d-lg-none">
                                <a href="javascript:;" class="text-primary btn-link btn-lg pull-right">
                                    <i class="fa fa-filter"></i>
                                </a>
                            </div>
                        </div>
                    </div>
                </div>
            </div>

            <div class="card bg-light mt-3 pl-3 pr-3  rounded  border-0">
                <div class="row">
                    <div class="card-body">

                        <div class="row date-filter">
                            <div class="col-sm-4 mb-3">
                                <label class="btn-block">{{__('backoffice.dates')}}</label>
                                <input type="text" id="daterange" name="daterange" ui-jq="daterangepicker" ui-options="{
                                    format: 'YYYY-MM-DD',
                                    }" placeholder="Date Range" class="form-control" autocomplete="off"
                                       form="main-filters"
                                       value="{{isset($filters['daterange']) ? $filters['daterange'] : ''}}"
                                >
                            </div>
                            <div class="col-sm-4 mb-3">
                            </div>
                            <div class="col-sm-4 mb-3">
                                <label class="btn-block">{{__('backoffice.view_by')}}</label>
                                <div class="btn-group btn-group-toggle btns-Mobile" data-toggle="buttons">
                                    <label
                                        class="btn btn-secondary site-date-filter-day {{ isset($filters['date_filter']) && $filters['date_filter'] == 'day' ? 'active' : ''}}">
                                        <input type="radio" name="date_filter" class="date_filter" value="day"
                                               data-filter="day" id="date-filter-day" autocomplete="off"
                                               form="main-filters" {{ isset($filters['date_filter']) && $filters['date_filter'] == 'day' ? 'checked' : ''}}> {{__('backoffice.day')}}
                                    </label>
                                    <label
                                        class="btn btn-secondary {{ isset($filters['date_filter']) && $filters['date_filter'] == 'week' ? 'active' : ''}}">
                                        <input type="radio" name="date_filter" class="date_filter" value="week"
                                               data-filter="week" id="date-filter-week" autocomplete="off"
                                               {{ isset($filters['date_filter']) && $filters['date_filter'] == 'week' ? 'checked' : ''}} form="main-filters"> {{__('backoffice.week')}}
                                    </label>
                                    <label
                                        class="btn btn-secondary  {{ isset($filters['date_filter']) && $filters['date_filter'] == 'month' ? 'active' : ''}}">
                                        <input type="radio" name="date_filter" class="date_filter" value="month"
                                               data-filter="month" id="date-filter-month" autocomplete="off"
                                               {{ isset($filters['date_filter']) && $filters['date_filter'] == 'month' ? 'checked' : ''}}  form="main-filters"> {{__('backoffice.month')}}
                                    </label>
                                </div>
                            </div>
                        </div>
                        <hr>
                        <div class="row date-filter2">
                            <form action="{{ route('reports.index') }}" method="GET" id="main-filters">
                            </form>
                        {{--                        @dd($filters)--}}
                        @php
                            $report_type = isset($filters) ? $filters['report_type'] :'summary';
                        @endphp
                        <!-- no need to outlet filter in category reports and supplier reports  -->
                            @if($report_type == null || $report_type == "summary" || $report_type == "payment" || $report_type == "inventory" || $report_type == "register" || $report_type == "user_report")
                                <div class="col-sm-4 mb-3">
                                    <label class="btn-block">Outlet</label>
                                    <select class="customer-select2 form-control" id="outlet_id" name="outlet_id"
                                            form="main-filters">
                                        <option value="">{{ __('backoffice.all') }}</option>
                                        @foreach ($outlets as $outlet)
                                            <option
                                                value="{{ $outlet->id }}" {{ isset($filters['outlet_id']) && $filters['outlet_id'] == $outlet->id ? 'selected' : null}}>{{ $outlet->name }}</option>
                                        @endforeach
                                    </select>
                                </div>
                            @endif

                            <div class="col-sm-4 mb-3">
                                <label>@lang('backoffice.report_type')</label>
                                <select name="report_type" class="form-control filter_type-select2" form="main-filters">

                                    @if($per::chekStatus('reporting_sales_report'))
                                        <option id="summary"
                                                value="summary" {{ $report_type == 'summary' ? 'selected': null }} >@lang('backoffice.sales')</option>@endif
                                    @if($per::chekStatus('reporting_payment_report'))
                                        <option id="payment"
                                                value="payment" {{ $report_type == "payment" ? 'selected': null }}>
                                            Payment
                                        </option>@endif
                                    @if($per::chekStatus('product_report'))
                                        <option id="product"
                                                value="product" {{ $report_type == "product" ? 'selected': null }}>@lang('backoffice.products')</option>@endif
                                    @if($per::chekStatus('reporting_inventory_report'))
                                        <option id="inventory"
                                                value="inventory" {{ $report_type == "inventory" ? 'selected': null }}>
                                            Inventory
                                        </option>@endif
                                    @if($per::chekStatus('category_report'))
                                        <option id="category_report"
                                                value="category_report" {{ $report_type == "category_report" ? 'selected': null }}>
                                            Category
                                        </option>@endif
                                    @if($per::chekStatus('reporting_register_closure'))
                                        <option id="register"
                                                value="register" {{ $report_type == "register" ? 'selected': null }}>@lang('backoffice.register')</option>@endif
                                    @if($per::chekStatus('customer_report'))
                                        <option id="customer_report"
                                                value="customer_report" {{ $report_type == "customer_report" ? 'selected': null }}>@lang('backoffice.customer')</option>@endif
                                    @if($per::chekStatus('customer_group_report'))
                                        <option id="customer_group"
                                                value="customer_group" {{ $report_type == "customer_group" ? 'selected': null }}>
                                            Customer Group
                                        </option>@endif
                                    @if($per::chekStatus('supplier_report'))
                                        <option id="supplier_report"
                                                value="supplier_report" {{ $report_type == "supplier_report" ? 'selected': null }}>@lang('backoffice.supplier')</option>@endif
                                    @if($per::chekStatus('brand_report'))
                                        <option id="brand"
                                                value="brand" {{ $report_type == "brand" ? 'selected': null }}>@lang('backoffice.brand')</option>@endif
                                    @if($per::chekStatus('user_report'))
                                        <option id="user_report"
                                                value="user_report" {{ $report_type == "user_report" ? 'selected': null }}>@lang('backoffice.user')</option>@endif
                                    @if($per::chekStatus('addon_report'))
                                        <option id="addon_report"
                                                value="addon_report" {{ $report_type == "addon_report" ? 'selected': null }}>@lang('backoffice.addon')</option>@endif
                                </select>
                            </div>
                            <div class="col-sm-4 mb-3">
                                <div class="col-md-12 pt-3 align-right">
                                    <button type="submit" onclick="applyParams()"
                                            class="btn m-b-xs w-xs btn btn-primary rightfloat  maring-top1">{{ __('backoffice.apply_filters')  }}</button>
                                    <a href="{{ route('reports.index') }}"
                                       class="btn m-b-xs w-xs btn btn-light  rightfloat maring-top1 margen-left">{{ __('backoffice.clear')  }}</a>
                                </div>
                            </div>
                        </div>
                        <!-- if select product filter -->
                        @if(isset($filters) && $filters['report_type'] == "product")
                            @if($per::chekStatus('product_report') )

                                <div class="row date-filter3">
                                    <div class="col-sm-3 mb-3">
                                        <label>{{ __('backoffice.name_sku')  }}</label>
                                        <input type="text" class="form-control" name="product_name" id="name_filter"
                                               placeholder="Samsung or FK3001" form="main-filters"
                                               value="{{isset($filters['product_name']) ? $filters['product_name'] : ''}}">
                                    </div>
                                    <div class="col-sm-3 mb-3">
                                        <label>{{ __('backoffice.category')  }}</label>
                                        <select id="category_filter" name="category"
                                                class="form-control rounded custom-select category-select2"
                                                form="main-filters">
                                            <option value="">{{ __('backoffice.select')  }}</option>
                                            @foreach($categories as $category)
                                                <option
                                                    value="{{ $category['id'] }}" {{isset($filters['category']) && $filters['category'] == $category['id'] ? 'selected' : ''}}>{{ $category['name'] }}</option>
                                            @endforeach
                                        </select>
                                    </div>
                                    <div class="col-sm-3 mb-3">
                                        <label>{{ __('backoffice.brand')  }}</label>
                                        <select id="brand_filter"
                                                class="form-control rounded custom-select brand-select2" name="brand"
                                                form="main-filters">
                                            <option value="">{{ __('backoffice.select')  }}</option>
                                            @foreach($brands as $brand)
                                                <option
                                                    value="{{ $brand['id'] }}" {{isset($filters['brand']) && $filters['brand'] == $brand['id'] ? 'selected' : ''}}>{{ $brand['name'] }}</option>
                                            @endforeach
                                        </select>
                                    </div>
                                    <div class="col-sm-3 mb-3">
                                        <label>{{ __('backoffice.supplier')  }}</label>
                                        <select id="supplier_filter"
                                                class="form-control rounded custom-select supplier-select2"
                                                name="supplier" form="main-filters">
                                            <option value="">{{ __('backoffice.select')  }}</option>
                                            @foreach($suppliers as $supplier)
                                                <option
                                                    value="{{ $supplier['id'] }}" {{isset($filters['supplier']) && $filters['supplier'] == $supplier['id'] ? 'selected' : ''}}>{{ $supplier['name'] }}</option>
                                            @endforeach
                                        </select>
                                    </div>
                                </div>


                                <div class="row">
                                    <div class="col-sm-12 dasboard_table">
                                        <h2 class="mt-3 mb-3">Products</h2>
                                        <div class="table-responsive">
                                            <table class="table table-hover display responsive nowrap"
                                                   id="product-table">
                                                <thead>
                                                <tr>
                                                    <th scope="col" width="15%">SKU</th>
                                                    <th scope="col" width="50%">Name</th>
                                                    <th scope="col" width="15%">Supplier Price</th>
                                                    <th scope="col" width="15%">Retail Price</th>
                                                    <th scope="col" width="15%">Total Stock</th>
                                                    <th scope="col" width="15%">Total Addons</th>
                                                </tr>
                                                </thead>

                                                <tbody>
                                                @foreach($products as $product)
                                                    @if($product['stock'] == null)
                                                        {{\App\Helpers\VariantStock::updateStockForReport($product['id'])}}
                                                    @endif
                                                    <tr>
                                                        <td>
                                                            <div class="expend float-left mr-1"> {{ $product['sku'] }}
                                                        </td>
                                                        <td>
                                                            @if($product['name'] == $product['attribute_value_1'])
                                                                {{$product['attribute_value_1']}}
                                                            @else
                                                                {{ $product['name'] }}
                                                            @endif
                                                            @if(($product['name'] == $product['attribute_value_1']) == false && strlen($product['attribute_value_1']) > 1)  {{ $product['attribute_value_1'] }} @endif
                                                            @if(strlen($product['attribute_value_2']) > 1)
                                                                / {{ $product['attribute_value_2'] }} @endif
                                                            @if(strlen($product['attribute_value_3']) > 1)
                                                                / {{ $product['attribute_value_3'] }} @endif
                                                        </td>
                                                        <td> {{ Auth::user()->store->default_currency .' '.number_format($product['supplier_price'],Auth::user()->store->round_off)}} </td>
                                                        <td> {{ Auth::user()->store->default_currency .' '.number_format($product['retail_price'],Auth::user()->store->round_off)}} </td>
                                                        <td>{{ $product['stock'] }}</td>
                                                        <td>{{ $product['product_add_on_count'] > 0 ? $product['product_add_on_count'] : __('backoffice.n/a') }}</td>
                                                    </tr>
                                                @endforeach
                                                </tbody>
                                            </table>
                                        </div>
                                    </div>
                                </div>
                            @endif
                        @endif
                    <!-- if select payment group filter -->
                        @if(isset($filters) && $filters['report_type'] == "payment")
                            @if($per::chekStatus('reporting_payment_report') )

                                @include('Reports.payment_report')
                            @endif
                        @endif
                    <!-- if select payment group filter -->
                        @if(isset($filters) && $filters['report_type'] == "inventory")
                            @if($per::chekStatus('reporting_inventory_report') )

                                @include('Reports.inventory-report')
                            @endif
                        @endif
                    <!-- if select customer group filter -->
                        @if(isset($filters) && $filters['report_type'] == "customer_report")
                            @if($per::chekStatus('customer_report') )

                                @include('Reports.customer-report')
                            @endif
                        @endif
                    <!-- if select customer group filter -->
                        @if(isset($filters) && $filters['report_type'] == "customer_group")
                            @if($per::chekStatus('customer_group_report') )

                                <div class="row date-filter5">
                                    <div class="col-sm-3 mb-3">
                                        <label>Group Name</label>
                                        <input name="customer_group_name" class="form-control rounded" type="text"
                                               placeholder="Group Name" form="main-filters"
                                               value="{{ request()->get('customer_group_name') }}">
                                    </div>
                                    <div class="col-sm-3 mb-3">
                                        <label>No of customer</label>
                                        <input name="number_of_customer" class="form-control rounded" type="number"
                                               placeholder="No of Customer" form="main-filters"
                                               value="{{ request()->get('number_of_customer') }}">
                                    </div>
                                </div>

                                <div class="row">
                                    <div class="col-sm-12 dasboard_table">
                                        <h2 class="mt-3 mb-3">Customer Group</h2>
                                        <div class="table-responsive">
                                            <table class="table table-hover display responsive nowrap"
                                                   id="customerGroups-reports-table">
                                                <thead>
                                                <tr>
                                                    <th scope="col">{{ __('backoffice.date') }}</th>
                                                    <th scope="col">{{ __('backoffice.name') }}</th>
                                                    <th scope="col">{{ __('backoffice.total_customers') }}</th>
                                                </tr>
                                                </thead>
                                                <tbody>
                                                @foreach($customer_group_reports as $customer_group)
                                                    <tr>
                                                        <td>
                                                            <div
                                                                class="expend float-left mr-1"> {{ date('d-m-Y', strtotime($customer_group['created_at'])) }}
                                                        </td>
                                                        <td>{{ $customer_group['name'] }}</td>
                                                        <td>{{ $customer_group['customer_count'] }}</td>
                                                    </tr>
                                                @endforeach
                                                </tbody>
                                            </table>
                                        </div>
                                    </div>
                                </div>
                            @endif
                        @endif
                    <!-- if select user filter -->
                        @if(isset($filters) && $filters['report_type'] == "user_report")
                            @if($per::chekStatus('user_report') )

                                <div class="row date-filter6">
                                    <div class="col-sm-3 mb-3">
                                        <label>User Name</label>
                                        <input name="user_name" class="form-control rounded" type="text"
                                               placeholder="User Name" form="main-filters"
                                               value="{{ request()->get('user_name') }}">
                                    </div>
                                </div>

                                <div class="row">
                                    <div class="col-sm-12 dasboard_table">
                                        <h2 class="mt-3 mb-3">Users</h2>
                                        <div class="table-responsive">
                                            <table class="table table-hover display responsive nowrap"
                                                   id="users-report-table">
                                                <thead>
                                                <tr>
                                                    <th scope="col">Name</th>
                                                    <th scope="col">Email</th>
                                                    <th scope="col">Mobile</th>
                                                </tr>
                                                </thead>

                                                <tbody>
                                                @foreach($user_reports as $user_report)
                                                    <tr>
                                                        <td>
                                                            <div
                                                                class="expend float-left mr-1"> {{ $user_report['name'] }}
                                                        </td>
                                                        <td>{{ $user_report['email'] }}</td>
                                                        <td>{{ $user_report['mobile'] }}</td>
                                                    </tr>
                                                @endforeach
                                                </tbody>
                                            </table>
                                        </div>
                                    </div>
                                </div>
                            @endif
                        <!-- if select register filter -->
                            @if(isset($filters) && $filters['report_type'] == "register")
                                <div class="row date-filter7">
                                    <div class="col-sm-3 mb-3">
                                        <label>Register Name</label>
                                        <input name="register_name" class="form-control rounded" type="text"
                                               placeholder="Register Name" form="main-filters"
                                               value="{{ request()->get('register_name') }}">
                                    </div>
                                </div>

                                <div class="row">
                                    <div class="col-sm-12 dasboard_table">
                                        <h2 class="mt-3 mb-3">Registers</h2>
                                        <div class="table-responsive">
                                            <table class="table table-hover display responsive nowrap"
                                                   id="register-reports-table">
                                                <thead>
                                                <tr>
                                                    <th scope="col">Register</th>
                                                    <th scope="col">Time opened</th>
                                                    <th scope="col">Time close</th>
                                                    <th scope="col">Opening Float</th>
                                                    <th scope="col">Sales</th>
                                                    <th scope="col">Expected</th>
                                                    <th scope="col">Difference</th>
                                                </tr>
                                                </thead>
                                                <tbody>
                                                @foreach($register_reports as $register_report)
                                                    <!-- need to sum of total sales against specific register history row -->
                                                    @php
                                                        $total_sales = 0;
                                                    @endphp
                                                    @if(count($register_report['orders']) > 0)
                                                        @foreach($register_report['orders'] as $orders)
                                                            @if($orders->payment_method == "Cash" || $orders->payment_method == "Credit Card")
                                                                @php
                                                                    $total_sales += $orders->total;
                                                                @endphp
                                                            @endif
                                                        @endforeach
                                                    @endif
                                                    <tr>
                                                        <td>
                                                            <div
                                                                class="expend float-left mr-1"> {{ $register_report['register']['name'] }}
                                                        </td>
                                                        <td>{{ $register_report['opened_on'] }}</td>
                                                        <td>{{ $register_report['closed_on'] }}</td>
                                                        <td>{{ $register_report['opening_balance'] > 0 ? PriceHelper::number_format($register_report['opening_balance']) : PriceHelper::number_format(0) }}</td>
                                                        <td>{{ PriceHelper::number_format($total_sales) }}</td>
                                                        <td>{{ PriceHelper::number_format($register_report['closing_expected']) }}</td>
                                                        <td>{{ PriceHelper::number_format($register_report['closing_expected'] - $register_report['closing_actual']) }}</td>
                                                    </tr>
                                                    @php
                                                        $total_sales = 0;
                                                    @endphp
                                                @endforeach
                                                </tbody>
                                            </table>
                                        </div>
                                    </div>
                                </div>
                            @endif
                        @endif
                    <!-- if select brand filter -->
                        @if(isset($filters) && $filters['report_type'] == "brand")
                            @if($per::chekStatus('brand_report') )
                                <div class="row date-filter8">
                                    <div class="col-sm-3 mb-3">
                                        <label>Brand Name</label>
                                        <input name="brand_name" class="form-control rounded" type="text"
                                               placeholder="Brand Name" form="main-filters"
                                               value="{{ request()->get('brand_name') }}">
                                    </div>
                                    <div class="col-sm-3 mb-3">
                                        <label>No of Products</label>
                                        <input name="number_of_product" class="form-control rounded" type="number"
                                               placeholder="No of Products" form="main-filters"
                                               value="{{ request()->get('number_of_product') }}">
                                    </div>
                                </div>

                                <div class="row">
                                    <div class="col-sm-12 dasboard_table">
                                        <h2 class="mt-3 mb-3">Brands</h2>
                                        <div class="table-responsive">
                                            <table class="table table-hover display responsive nowrap"
                                                   id="supplier-reports-table">
                                                <thead>
                                                <tr>
                                                    <th scope="col">{{ __('backoffice.date') }}</th>
                                                    <th scope="col">{{ __('backoffice.name') }}</th>
                                                    <th scope="col">{{ __('backoffice.total_products') }}</th>
                                                    <th scope="col">{{ __('backoffice.active') }}</th>
                                                    <th scope="col">{{ __('backoffice.featured') }}</th>
                                                </tr>
                                                </thead>
                                                <tbody>
                                                @foreach($brand_reports as $brand_report)
                                                    <tr>
                                                        <td>
                                                            <div
                                                                class="expend float-left mr-1"> {{ date('d-m-Y', strtotime($brand_report['created_at'])) }}
                                                        </td>
                                                        <td>{{ $brand_report['name'] }}</td>
                                                        <td>{{ $brand_report['products_count'] }}</td>
                                                        <td>{{ $brand_report['active'] == 1 ? __('report.yes') : __('report.no') }}</td>
                                                        <td>{{ $brand_report['is_featured'] == 1 ? __('report.yes') : __('report.no') }}</td>
                                                    </tr>
                                                @endforeach
                                                </tbody>
                                            </table>
                                        </div>
                                    </div>
                                </div>
                            @endif
                        @endif
                    <!-- if select supplier filter -->
                        @if(isset($filters) && $filters['report_type'] == "supplier_report")
                            @if($per::chekStatus('supplier_report') )
                                <div class="row date-filter8">
                                    <div class="col-sm-3 mb-3">
                                        <label>{{ __('backoffice.supplier_name') }}</label>
                                        <input name="supplier_name" class="form-control rounded" type="text"
                                               placeholder="Supplier Name" form="main-filters"
                                               value="{{ request()->get('supplier_name') }}">
                                    </div>
                                    <div class="col-sm-3 mb-3">
                                        <label>{{ __('backoffice.mobile') }}</label>
                                        <input name="supplier_mobile" class="form-control rounded" type="number"
                                               placeholder="Mobile" form="main-filters"
                                               value="{{ request()->get('supplier_mobile') }}">
                                    </div>
                                    <div class="col-sm-3 mb-3">
                                        <label>{{ __('backoffice.email') }}</label>
                                        <input name="supplier_email" class="form-control rounded" type="email"
                                               placeholder="Email" form="main-filters"
                                               value="{{ request()->get('supplier_email') }}">
                                    </div>
                                    <div class="col-sm-3 mb-3">
                                        <label>No of Products</label>
                                        <input name="number_of_product" class="form-control rounded" type="number"
                                               placeholder="No of Products" form="main-filters"
                                               value="{{ request()->get('number_of_product') }}">
                                    </div>
                                </div>

                                <div class="row">
                                    <div class="col-sm-12 dasboard_table">
                                        <h2 class="mt-3 mb-3">Supplier</h2>
                                        <div class="table-responsive">
                                            <table class="table table-hover display responsive nowrap"
                                                   id="supplier-reports-table">
                                                <thead>
                                                <tr>
                                                    <th scope="col">{{ __('backoffice.date') }}</th>
                                                    <th scope="col">{{ __('backoffice.name') }}</th>
                                                    <th scope="col">{{ __('backoffice.mobile') }}</th>
                                                    <th scope="col">{{ __('backoffice.email') }}</th>
                                                    <th scope="col">{{ __('backoffice.total_products') }}</th>
                                                </tr>
                                                </thead>
                                                <tbody>
                                                @foreach($supplier_reports as $supplier_report)
                                                    <tr>
                                                        <td>
                                                            <div
                                                                class="expend float-left mr-1"> {{ date('d-m-Y', strtotime($supplier_report['created_at'])) }}
                                                        </td>
                                                        <td>{{ $supplier_report['name'] }}</td>
                                                        <td>{{ $supplier_report['mobile'] }}</td>
                                                        <td>{{ $supplier_report['email'] }}</td>
                                                        <td>{{ $supplier_report['products_count'] }}</td>

                                                    </tr>
                                                @endforeach
                                                </tbody>
                                            </table>
                                        </div>
                                    </div>
                                </div>
                            @endif
                        @endif
                    <!-- if select category filter -->
                        @if(isset($filters) && $filters['report_type'] == "category_report")
                            @include('Reports.category-report')
                        @endif
                    <!-- if select addon filter -->
                        @if(isset($filters) && $filters['report_type'] == "addon_report")
                            @if($per::chekStatus('addon_report') )
                                <div class="row date-filter9">
                                    <div class="col-sm-3 mb-3">
                                        <label>Name</label>
                                        <input name="addon_name" class="form-control rounded" type="text"
                                               placeholder="name" form="main-filters"
                                               value="{{ request()->get('addon_name') }}">
                                    </div>
                                    <div class="col-sm-3 mb-3">
                                        <label>{{ __('backoffice.addon_type') }}</label>
                                        <select id="addon_type" name="addon_type"
                                                class="form-control rounded custom-select customer_group-select2"
                                                form="main-filters">
                                            <option value="">Select</option>
                                            <option
                                                value="add_on" {{ request()->get('addon_type') == "add_on" ? 'selected' : null }}>
                                                Addon
                                            </option>
                                            <option
                                                value="option" {{ request()->get('addon_type') == "option" ? 'selected' : null }}>
                                                Option
                                            </option>
                                        </select>
                                    </div>
                                </div>

                                <div class="row">
                                    <div class="col-sm-12 dasboard_table">
                                        <h2 class="mt-3 mb-3">Addons</h2>
                                        <div class="table-responsive">
                                            <table class="table table-hover display responsive nowrap"
                                                   id="customer-reports-table">
                                                <thead>
                                                <tr>
                                                    <th scope="col">{{ __('backoffice.date') }}</th>
                                                    <th scope="col">{{ __('backoffice.name') }}</th>
                                                    <th scope="col">{{ __('backoffice.type') }}</th>
                                                    <th scope="col">{{ __('backoffice.total_items') }}</th>
                                                </tr>
                                                </thead>
                                                <tbody>
                                                @foreach($addon_reports as $addon_report)
                                                    <tr>
                                                        <td>
                                                            <div
                                                                class="expend float-left mr-1"> {{ date('d-m-Y', strtotime($addon_report['created_at'])) }}
                                                        </td>
                                                        <td>{{ $addon_report['name'] }}</td>
                                                        <td>{{ $addon_report['type'] == 'add_on' ? __('backoffice.addon') : __('backoffice.option') }}</td>
                                                        <td>{{ $addon_report['items_count'] }}</td>
                                                    </tr>
                                                @endforeach
                                                </tbody>
                                            </table>
                                        </div>
                                    </div>
                                </div>
                            @endif
                        @endif
                    <!-- display sales summary by default -->
                        @if(isset($filters['report_type']) == false ||  $filters['report_type'] == "summary" && $per::chekStatus('reporting_sales_report') )
                            @if($per::chekStatus('reporting_sales_report') )
                                <div class="row date-filter10">
                                    <div class="col-sm-3 mb-3">
                                        <label>Status</label>
                                        {{-- <select id="sales_status" class="form-control rounded custom-select sales_status-select2" name="sales_status" form="main-filters">
                                            <option value="">Select</option>
                                            <option value="cancelled" {{ isset($filters['sales_status']) && $filters['sales_status'] == 'cancelled' ? 'selected' : null }}>Cancelled</option>
                                      </select> --}}

                                        <select class="form-control select2 inventory-select2" name="sales_status"
                                                form="main-filters">
                                            <option value="">All</option>
                                            <option
                                                value="cancelled" {{ isset($filters['sales_status']) && $filters['sales_status'] == 'cancelled' ? 'selected' : null }}>
                                                Cancelled
                                            </option>
                                        </select>
                                    </div>
                                </div>
                    </div>

                    <div class="col-12">
                        <div class="col-sm-12 dasboard_table">
                            <h2 class="mt-3 mb-3">Sales</h2>
                            <div class="table-responsive">

                                <!-- create multiple tables because when user applied cancelled filter, we are calling the data from "weborders" table otherwise the data is calling from "orders" table -->
                                @if(isset($filters['sales_status']) &&  $filters['sales_status'] == "cancelled")
                                    <table class="table table-striped display responsive nowrap" id="sales-table">
                                        <thead>
                                        <tr>
                                            <th scope="col">{{ __('backoffice.date') }}</th>
                                            <th scope="col">{{ __('backoffice.customer') }}</th>
                                            <th scope="col">{{ __('backoffice.cancel_note') }}</th>
                                            <th scope="col">{{ __('backoffice.outlet') }}</th>
                                            <th scope="col">{{ __('backoffice.order_no') }}</th>
                                            <th scope="col">{{ __('backoffice.sub_total') }}</th>
                                            <th scope="col">{{ __('backoffice.discount') }}</th>
                                            <th scope="col">{{ __('backoffice.total') }}</th>
                                            <th scope="col">{{ __('backoffice.status') }}</th>
                                        </tr>
                                        </thead>

                                        <tbody>
                                        @foreach($sales as $sale)
                                            <tr>
                                                <td>
                                                    <div class="expend float-left mr-1"></div> {{ $sale['order_date'] }}
                                                </td>
                                                <td>{{ $sale['user_firstName'] }}</td>
                                                <td>{{ $sale['cancel_notes'] }}</td>
                                                <td>{{ $sale['outlet']['name'] }}</td>
                                                <td>{{ $sale['order_number'] }}</td>
                                                <td>{{ Auth::user()->store->default_currency .' '.number_format($sale['subtotal'] ,Auth::user()->store->round_off)}} </td>
                                                <td> {{ isset($sale['discount_amount']) && $sale['discount_amount'] > 0 ? Auth::user()->store->default_currency .' '.number_format($sale['discount_amount'] ,Auth::user()->store->round_off) : '-' }} </td>
                                                <td> {{ Auth::user()->store->default_currency .' '.number_format($sale['total'] ,Auth::user()->store->round_off)}} </td>
                                                <td>{{ $sale['status'] }}</td>
                                            </tr>
                                        @endforeach
                                        </tbody>
                                    </table>
                                @else
                                    <table class="table table-striped display responsive nowrap" id="sales-table">
                                        <thead>
                                        <tr>
                                            <th scope="col">{{ __('backoffice.date') }}</th>
                                            <th scope="col">{{ __('backoffice.customer') }}</th>
                                            <!-- if applied cancelled filter -->
                                            @if(isset($filters['sales_status']) &&  $filters['sales_status'] == "cancelled")
                                                <th scope="col">{{ __('backoffice.cancel_note') }}</th>
                                            @else
                                                <th scope="col">{{ __('backoffice.user') }}</th>
                                            @endif
                                            <th scope="col">{{ __('backoffice.outlet') }}</th>
                                            <th scope="col">{{ __('backoffice.reciept_no') }}</th>
                                            <th scope="col">{{ __('backoffice.sub_total') }}</th>
                                            <th scope="col">{{ __('backoffice.discount') }}</th>
                                            <th scope="col">{{ __('backoffice.total') }}</th>
                                            <th scope="col">{{ __('backoffice.status') }}</th>
                                        </tr>
                                        </thead>

                                        <tbody>
                                        @foreach($sales as $sale)
                                            <tr>
                                                <td>
                                                    <div class="expend float-left mr-1"></div> {{ $sale['order_date'] }}
                                                </td>
                                                <td>{{ isset($sale['customer']['name']) ? $sale['customer']['name'] : '' }}</td>
                                                <td>{{ $sale['user']['name'] }}</td>
                                                <td>{{ $sale['outlet']['name'] }}</td>
                                                <td>{{ $sale['receipt'] }}</td>
                                                <td> {{ Auth::user()->store->default_currency .' '.number_format($sale['sub_total'] ,Auth::user()->store->round_off)}} </td>
                                                <td> {{ $sale['discount'] > 0 ? Auth::user()->store->default_currency .' '.number_format($sale['discount'] ,Auth::user()->store->round_off) : '-' }} </td>
                                                <td> {{ Auth::user()->store->default_currency .' '.number_format($sale['total'] ,Auth::user()->store->round_off) }} </td>
                                                <td>{{ $sale['status'] }}</td>
                                            </tr>
                                        @endforeach
                                        </tbody>
                                    </table>
                                @endif
                            </div>
                        </div>
                    </div>
                    @endif
                    @endif


                    <hr>
                </div>
            </div>
        </div>
    </div>
    </div>

@endsection
@section('scripts')
    <script src="https://cdn.datatables.net/responsive/2.2.7/js/dataTables.responsive.min.js"></script>

    <script type="text/javascript" src="https://cdn.datatables.net/buttons/1.6.1/js/dataTables.buttons.min.js"></script>
    <script type="text/javascript" src="https://cdn.datatables.net/buttons/1.6.1/js/buttons.flash.min.js"></script>
    <script type="text/javascript" src="https://cdnjs.cloudflare.com/ajax/libs/jszip/3.1.3/jszip.min.js"></script>
    <script type="text/javascript" src="https://cdnjs.cloudflare.com/ajax/libs/pdfmake/0.1.53/pdfmake.min.js"></script>
    <script type="text/javascript" src="https://cdnjs.cloudflare.com/ajax/libs/pdfmake/0.1.53/vfs_fonts.js"></script>
    <script type="text/javascript" src="https://cdn.datatables.net/buttons/1.6.1/js/buttons.html5.min.js"></script>
    <script type="text/javascript" src="https://cdn.datatables.net/buttons/1.6.1/js/buttons.print.min.js"></script>


    <style type="text/css">
        div.dt-buttons {
            float: right;
            margin-left: 5px;
            margin-top: -5px;
        }
    </style>
    <script type="text/javascript">
        var ordersTable = null;
        var customerTable = null;
        var customerData = [];

        var date_filter = '';


        function formReset() {
            $('#order_number').val('').trigger("change");
            $('#customer').val('').trigger("change");
        }

        $(document).ready(function () {

            $('input[name="daterange"]').daterangepicker({
                autoUpdateInput: false,
                locale: {
                    cancelLabel: 'Clear',
                    format: "YYYY-MM-DD"
                }
            });

            $('input[name="daterange"]').on('apply.daterangepicker', function (ev, picker) {
                $(this).val(picker.startDate.format('YYYY-MM-DD') + ' - ' + picker.endDate.format('YYYY-MM-DD'));
            });

            $('input[name="daterange"]').on('cancel.daterangepicker', function (ev, picker) {
                $(this).val('');
            });

            $('.customer-select2, .order-select2, .category-select2, .brand-select2, .supplier-select2,.filter_type-select2, .payment_method-select2,.customer_group-select2,.country-select2, .inventory-select2').select2();

            ordersTable = $('#orders-table').DataTable({
                responsive: {
                    details: {
                        type: 'column'
                    }
                },
                "processing": true,
                "serverSide": true,
                "deferLoading": 0,
                "ajax": {
                    // "url": "{{ route('api.fetch.salesreport') }}",
                    "dataType": "json",
                    "type": "POST",
                    "data": function (d) {

                        if ($('#daterange').val() != '') {
                            d['parameter_type'] = 'daterange';
                            d['date_filter'] = $('#daterange').val();
                            ;

                            d['daterange'] = 'daterange';
                            d['order_status'] = 'Confirmed';
                        } else {
                            d['parameter_type'] = 'buttons';
                            d['date_filter'] = date_filter;

                            d['order_status'] = 'Confirmed';
                        }
                        if ($('#outlet_id').val() != '') {
                            // let web_order_outlet_id = localStorage.getItem('web_order_outlet_id');
                            // if(web_order_outlet_id != undefined && web_order_outlet_id){
                            // 	d['outlet_id'] = web_order_outlet_id;
                            // }else{
                            d['outlet_id'] = $('#outlet_id').val();
                            // }
                        }
                    }
                },

                "columns": [
                    {data: 'id'},
                    {data: 'order_date'},
                    {data: 'total'},
                    {data: 'user_firstName'},
                    {data: 'address_info', sortable: false},
                    {data: 'status'},
                    {
                        data: 'payment_status', render: function (column, data, row) {
                            return `${row.payment_method} ( ${row.payment_status} )`
                        }
                    },

                ],
                "order": [[0, "desc"]],
            });


            ordersTable.ajax.reload(reloadPage);
        });

        function applyParams() {
            $('form#main-filters').submit();
            // ordersTable.ajax.reload(reloadPage);
        }


        // setInterval(()=>{
        // 	ordersTable.ajax.reload(reloadPage);
        // },60000);


        $(function () {
            $('input[name="daterange"]').on('apply.daterangepicker', function (ev, picker) {
                $(this).val(picker.startDate.format('YYYY-MM-DD') + ' - ' + picker.endDate.format('YYYY-MM-DD'));
                var date_filter = picker.startDate.format('YYYY-MM-DD') + ' - ' + picker.endDate.format('YYYY-MM-DD');
                $('.date_filter').removeAttr('checked').trigger('change');
                $('.date_filter').parent().removeClass('active');
                $('#daterange').val(date_filter);
                // ordersTable.ajax.reload(reloadPage);
            });
        });

        $(function () {
            $('.date_filter').on('change', function () {
                date_filter = $(this).data('filter');
                if (date_filter.length > 0) {
                    $('#daterange').val('');
                    // ordersTable.ajax.reload(reloadPage);
                }
            });
        });

        function reloadPage(data) {
            if (data != null) {
                //customerData = data.extra.customers_widget;
                customerTable.clear().rows.add(data.extra.customers_widget).draw()

                if (Object.keys(data.extra.kpis).length > 0) {
                    $('#dashboard_total_sales').html(data.extra.kpis[0]);
                    $('#dashboard_number_of_orders_without_failed').html(data.extra.kpis[1]);
                    $('#dashboard_number_of_orders_returned').html(data.extra.kpis[2]);
                    $('#dashboard_number_of_orders_complete').html(data.extra.kpis[3]);
                    $('#dashboard_number_of_orders_confirmed').html(data.extra.kpis[4]);

                    if (data.extra.kpis[4] > 0) {
                        $('.confirmed-orders').addClass('bg-warning');
                        clearInterval(blinker);
                        blinker = setInterval(() => {
                            $('.confirmed-orders').toggleClass('bg-warning');
                        }, 1000);

                    } else {
                        $('.confirmed-orders').removeClass('bg-warning');
                    }

                } else {
                    $('#dashboard_total_sales').html(0);
                    $('#dashboard_number_of_orders_without_failed').html(0);
                    $('#dashboard_number_of_orders_returned').html(0);
                    $('#dashboard_number_of_orders_complete').html(0);
                    $('#dashboard_number_of_orders_confirmed').html(0);
                }


            } else {
                $('#dashboard_total_sales').html(0);
                $('#dashboard_number_of_orders_without_failed').html(0);
                $('#dashboard_number_of_orders_returned').html(0);
                $('#dashboard_number_of_orders_complete').html(0);
                $('#dashboard_number_of_orders_confirmed').html(0);
            }
        }


        $(document).ready(function () {

            /* jquery datatable */
            $("#sales-table").append('<tfoot><td></td><td></td><td></td><td></td><td></td><td></td><td></td><td></td><td></td></tfoot>');
            $('#sales-table').DataTable({

                lengthMenu: [[10, 25, 50, 100, -1], [10, 25, 50, 100, "All"]],
                pageLength: 10,
                order: [[0, "desc"]],
                dom: 'lBfrtip',
                responsive: {
                    details: {
                        type: 'column'
                    }
                },
                footerCallback: function (tfoot, data, start, end, display) {
                    var api = this.api();
                    let default_currency = '{!! Auth::user()->store->default_currency !!}';

                    $(api.column(7).footer()).html(
                        "Total: " + default_currency + " " + api.column(7).data().reduce(function (a, b) {
                            if (b.split(' ')[1] != undefined) {
                                return (parseFloat(a) + parseFloat(b.split(' ')[1])).toFixed(3);
                            }
                        }, 0)
                    );
                },
                buttons: [
                    {
                        extend: 'excel',
                        text: 'Excel',
                        className: 'btn-sm btn-primary',
                        filename: 'sales-summary',
                        footer: true,
                    },
                    {
                        extend: 'print',
                        text: 'Print',
                        className: 'btn-sm btn-primary',
                        filename: 'sales-summary',
                        footer: true,
                    }
                ]
            });

            //payments datatable settings
            $("#payments-table").append('<tfoot><td></td><td></td><td></td><td></td></tfoot>');
            $('#payments-table').DataTable({
                responsive: {
                    details: {
                        type: 'column'
                    }
                },
                lengthMenu: [[10, 25, 50, 100, -1], [10, 25, 50, 100, "All"]],
                pageLength: 10,
                order: [[0, "desc"]],
                dom: 'lBfrtip',
                footerCallback: function (tfoot, data, start, end, display) {
                    var api = this.api();
                    let default_currency = '{!! Auth::user()->store->default_currency !!}';

                    $(api.column(1).footer()).html(
                        "Total: " + default_currency + " " + api.column(1).data().reduce(function (a, b) {
                            if (b.split(' ')[1] != undefined) {
                                return (parseFloat(a) + parseFloat(b.split(' ')[1])).toFixed(3);
                            }
                        }, 0)
                    );
                },
                buttons: [
                    {
                        extend: 'excel',
                        text: 'Excel',
                        className: 'btn-sm btn-primary',
                        filename: 'payments-summary',
                        footer: true,
                    },
                    {
                        extend: 'print',
                        text: 'Print',
                        className: 'btn-sm btn-primary',
                        filename: 'payments-summary',
                        footer: true,
                    }
                ]
            });

            //products reports filters
            $('#product-table').DataTable({
                responsive: {
                    details: {
                        type: 'column'
                    }
                },
                lengthMenu: [[10, 25, 50, 100, -1], [10, 25, 50, 100, "All"]],
                pageLength: 10,
                order: [[0, "desc"]],
                dom: 'lBfrtip',
                buttons: [
                    {
                        extend: 'excel',
                        text: 'Excel',
                        className: 'btn-sm btn-primary',
                        filename: 'Products Report',
                        exportOptions: {
                            columns: [0, 1, 2, 3, 4]
                        }
                    },
                    {
                        extend: 'print',
                        text: 'Print',
                        className: 'btn-sm btn-primary',
                        filename: 'Products Report',
                        exportOptions: {
                            columns: [0, 1, 2, 3, 4]
                        }
                    }
                ]
            });

            // inventory reports tables
            $('#inventory-reports-table').DataTable({
                responsive: {
                    details: {
                        type: 'column'
                    }
                },
                lengthMenu: [[10, 25, 50, 100, -1], [10, 25, 50, 100, "All"]],
                pageLength: 10,
                order: [[0, "desc"]],
                dom: 'lBfrtip',
                buttons: [
                    {
                        extend: 'excel',
                        text: 'Excel',
                        className: 'btn-sm btn-primary',
                        filename: 'inventory-reports',
                        footer: true,
                    },
                    {
                        extend: 'print',
                        text: 'Print',
                        className: 'btn-sm btn-primary',
                        filename: 'inventory-reports',
                        footer: true,
                    }
                ]
            });

            // register reports tables
            $('#register-reports-table').DataTable({
                responsive: {
                    details: {
                        type: 'column'
                    }
                },
                lengthMenu: [[10, 25, 50, 100, -1], [10, 25, 50, 100, "All"]],
                pageLength: 10,
                order: [[0, "desc"]],
                dom: 'lBfrtip',
                buttons: [
                    {
                        extend: 'excel',
                        text: 'Excel',
                        className: 'btn-sm btn-primary',
                        filename: 'register-reports',
                        footer: true,
                    },
                    {
                        extend: 'print',
                        text: 'Print',
                        className: 'btn-sm btn-primary',
                        filename: 'register-reports',
                        footer: true,
                    }
                ]
            });

            // category reports table
            $('#category-reports-table').DataTable({
                responsive: {
                    details: {
                        type: 'column'
                    }
                },
                lengthMenu: [[10, 25, 50, 100, -1], [10, 25, 50, 100, "All"]],
                pageLength: 10,
                dom: 'lBfrtip',
                buttons: [
                    {
                        extend: 'excel',
                        text: 'Excel',
                        className: 'btn-sm btn-primary',
                        filename: 'category-reports',
                        footer: true,
                    },
                    {
                        extend: 'print',
                        text: 'Print',
                        className: 'btn-sm btn-primary',
                        filename: 'category-reports',
                        footer: true,
                    }
                ]
            });

            // supplier reports table
            $('#supplier-reports-table').DataTable({
                responsive: {
                    details: {
                        type: 'column'
                    }
                },
                lengthMenu: [[10, 25, 50, 100, -1], [10, 25, 50, 100, "All"]],
                pageLength: 10,
                dom: 'lBfrtip',
                buttons: [
                    {
                        extend: 'excel',
                        text: 'Excel',
                        className: 'btn-sm btn-primary',
                        filename: 'supplier-reports',
                        footer: true,
                    },
                    {
                        extend: 'print',
                        text: 'Print',
                        className: 'btn-sm btn-primary',
                        filename: 'supplier-reports',
                        footer: true,
                    }
                ]
            });

            // customer group  reports table
            $('#customerGroups-reports-table').DataTable({
                responsive: {
                    details: {
                        type: 'column'
                    }
                },
                lengthMenu: [[10, 25, 50, 100, -1], [10, 25, 50, 100, "All"]],
                pageLength: 10,
                dom: 'lBfrtip',
                buttons: [
                    {
                        extend: 'excel',
                        text: 'Excel',
                        className: 'btn-sm btn-primary',
                        filename: 'customer-group-reports',
                        footer: true,
                    },
                    {
                        extend: 'print',
                        text: 'Print',
                        className: 'btn-sm btn-primary',
                        filename: 'customer-group-reports',
                        footer: true,
                    }
                ]
            });

            //user reports
            $('#users-reports-table').DataTable({
                responsive: {
                    details: {
                        type: 'column'
                    }
                },
                lengthMenu: [[10, 25, 50, 100, -1], [10, 25, 50, 100, "All"]],
                pageLength: 10,
                dom: 'lBfrtip',
                buttons: [
                    {
                        extend: 'excel',
                        text: 'Excel',
                        className: 'btn-sm btn-primary',
                        filename: 'user-reports',
                        footer: true,
                    },
                    {
                        extend: 'print',
                        text: 'Print',
                        className: 'btn-sm btn-primary',
                        filename: 'user-reports',
                        footer: true,
                    }
                ]
            });

            $('#customer-reports-table').DataTable({
                responsive: {
                    details: {
                        type: 'column'
                    }
                },
                lengthMenu: [[10, 25, 50, 100, -1], [10, 25, 50, 100, "All"]],
                pageLength: 10,
                dom: 'lBfrtip',
                buttons: [
                    {
                        extend: 'excel',
                        text: 'Excel',
                        className: 'btn-sm btn-primary',
                        filename: 'customer-reports',
                        footer: true,
                    },
                    {
                        extend: 'print',
                        text: 'Print',
                        className: 'btn-sm btn-primary',
                        filename: 'customer-reports',
                        footer: true,
                    }
                ]
            });

        });

    </script>

    <script>
        $(document).ready(function () {
            $(".filter-icons a.btn-lg").click(function () {
                $(".date-filter, .date-filter2, .date-filter3, .date-filter4, .date-filter5, .date-filter6, .date-filter7, .date-filter8, .date-filter9, .date-filter10").toggleClass('date2');

            });
        });
    </script>


@endsection


