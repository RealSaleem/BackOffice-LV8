@extends('layouts.backoffice')
@section('content')
{{--    @dd(Auth::user()->getAllPermissions())--}}

    <div class="row">
        <div class="col-sm-12">
            <div class="greybg1 rounded p-4 mb-3">
                <div class="row">
                    <div class="col-sm-12">
                        <div class="common_title">
                            <h1>{{__('backoffice.dashboard')}}</h1>
                            <div class="filter-icons d-inline-block d-md-none d-lg-none">
                                <a href="javascript:;" class="text-primary btn-link btn-lg pull-right">
                                    <i class="fa fa-filter"></i>
                                </a>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
            @if (\Session::has('errors'))
                <div class="alert alert-danger danger" style="margin: 15px 54px;">

                        <span>{!! \Session::get('errors') !!}</span>

                </div>
            @endif

            <div class="card bg-light mt-3 pl-3 pr-3  rounded  border-0">
                <div class="row">
                    <div class="card-body">

                        <div class="row date-filter">
                            <div class="col-sm-4 mb-3 filter">

                                <label class="btn-block">{{__('backoffice.view_by')}}</label>
                                <div class="btn-group btn-group-toggle" data-toggle="buttons">
                                    <label class="btn btn-secondary active">
                                        <input type="radio" name="date_filter" class="date_filter" data-filter="day" id="date-filter-day" autocomplete="off"> {{__('backoffice.day')}}
                                    </label>
                                    <label class="btn btn-secondary">
                                        <input type="radio" name="date_filter" class="date_filter" data-filter="week" id="date-filter-week" autocomplete="off"> {{__('backoffice.week')}}
                                    </label>
                                    <label class="btn btn-secondary">
                                        <input type="radio" name="date_filter" class="date_filter" data-filter="month" id="date-filter-month" autocomplete="off" checked>  {{__('backoffice.month')}}
                                    </label>
                                    <!-- <label class="btn btn-secondary">
                                        <input type="radio" name="date_filter" class="date_filter" data-filter="1year" id="date-filter-year" autocomplete="off" checked>  {{__('backoffice.year')}}
                                    </label> -->
                                </div>
                            </div>

                            <div class="col-sm-4 mb-3">
                            </div>
                            <div class="col-sm-4 mb-3 dates">

                                <label class="btn-block">{{__('backoffice.dates')}}</label>
                                <input type="text" id="daterange" name="daterange" ui-jq="daterangepicker" ui-options="{
                                format: 'YYYY-MM-DD',
                                }" placeholder="Date Range" class="form-control" autocomplete="off">
                            </div>
                        </div>
                        <div class="row">
                            <div class="col-6 col-xl-4 col-md-6 mb-4">
                                <div class="card shadow card-boxes py-2">
                                    <div class="card-body">
                                        <div class="row no-gutters align-items-center">
                                            <div class="col-sm-2">
                                                <i class="icon-layers text-primary fa-2x"></i>
                                            </div>
                                            <div class="col-sm-10">
                                                <div class="text-xs font-weight-bold text-uppercase mb-1 text-muted">
                                                    Total Sales
                                                </div>
                                                <div class="h5 mb-0 font-weight-bold text-gray-800" id="dashboard_total_sales">0</div>
                                                <!-- <span class="small text-success"> <i class="fa fa-long-arrow-up"></i> 70% from yesterday</span> -->
                                            </div>
                                        </div>
                                    </div>
                                </div>
                            </div>
                             <!-- Earnings (Monthly) Card Example -->
                            <div class="col-6 col-xl-4 col-md-6 mb-4">
                                <div class="card shadow card-boxes py-2">
                                    <div class="card-body">
                                        <div class="row no-gutters align-items-center">
                                            <div class="col-sm-2">
                                                <i class="icon-pie-chart text-success fa-2x"></i>
                                            </div>
                                            @if( \App\Helpers\Helper::chekStatus('dashboard_dashboard','admin'))

                                                <div class="col-sm-10">
                                                    <div
                                                        class="text-xs font-weight-bold text-uppercase mb-1 text-muted">
                                                        Top Sold Products
                                                    </div>
                                                    <div class="h5 mb-0 font-weight-bold text-gray-800" id="dashboard_top_sold_products">0</div>
                                                    <!-- <span class="small text-warning"> <i class="fa fa-long-arrow-down"></i> 2% from yesterday</span> -->
                                                </div>
                                            @endif
                                        </div>
                                    </div>
                                </div>
                            </div>
                            <!-- Earnings (Monthly) Card Example -->
                            <div class="col-6 col-xl-4 col-md-6 mb-4">
                                <div class="card shadow card-boxes py-2">
                                    <div class="card-body">
                                        <div class="row no-gutters align-items-center">
                                            <div class="col-sm-2">
                                                <i class="icon-close text-danger fa-2x"></i>
                                            </div>
                                            <div class="col-sm-10">
                                                <div class="text-xs font-weight-bold text-uppercase mb-1 text-muted">Out
                                                    Of Stocks (Products)
                                                </div>
                                                <div class="row no-gutters align-items-center">
                                                    <div class="col-auto">
                                                        <div class="h5 mb-0 mr-md-3 font-weight-bold text-gray-800" id="dashboard_out_of_stock_products">
                                                            0
                                                        </div>
                                                        <!-- <span class="small text-warning"> <i class="fa fa-long-arrow-down"></i> 2% from yesterday</span> -->
                                                    </div>
                                                </div>
                                            </div>
                                        </div>
                                    </div>
                                </div>
                            </div>

                            <!-- Earnings (Monthly) Card Example -->
                            <div class="col-6 col-xl-4 col-md-6 mb-4">
                                <div class="card shadow card-boxes py-2">
                                    <div class="card-body">
                                        <div class="row no-gutters align-items-center">
                                            <div class="col-sm-2">
                                                <i class="icon-list text-success fa-2x"></i>
                                            </div>
                                            <div class="col-sm-10">
                                                <div class="text-xs font-weight-bold  text-uppercase mb-1 text-muted">
                                                    Number Of Orders
                                                </div>
                                                <div class="h5 mb-0 font-weight-bold text-gray-800" id="dashboard_number_of_orders_without_failed">0</div>
                                                <!-- <span class="small text-warning"> <i class="fa fa-long-arrow-down"></i> 2% from yesterday</span> -->
                                            </div>
                                        </div>
                                    </div>
                                </div>
                            </div>
                            <!-- Earnings (Monthly) Card Example Current Orders-->
                            <div class="col-6 col-xl-4 col-md-6 mb-4">
                                <div class="card shadow card-boxes py-2">
                                    <div class="card-body">
                                        <div class="row no-gutters align-items-center">
                                            <div class="col-sm-2">
                                                <i class="icon-loop text-info fa-2x"></i>
                                            </div>
                                            <div class="col-sm-10">
                                                <div class="text-xs font-weight-bold text-uppercase mb-1 text-muted">
                                                    Current Orders
                                                </div>
                                                <div class="row no-gutters align-items-center">
                                                    <div class="col-auto">
                                                        <div class="h5 mb-0 mr-md-3 font-weight-bold text-gray-800" id="dashboard_number_of_orders_confirmed">
                                                            0
                                                        </div>
                                                        <!-- <span class="small text-warning"> <i  class="fa fa-long-arrow-down"></i> 2% from yesterday</span> -->
                                                    </div>
                                                </div>
                                            </div>
                                        </div>
                                    </div>
                                </div>
                            </div>
                            <!-- Pending Requests Card Example -->
                            <div class="col-6 col-xl-4 col-md-6 mb-4">
                                <div class="card shadow card-boxes py-2">
                                    <div class="card-body">
                                        <div class="row no-gutters align-items-center">
                                            <div class="col-sm-2">
                                                <i class="icon-grid text-warning fa-2x"></i>
                                            </div>
                                            <div class="col-sm-10">
                                                <div class="text-xs font-weight-bold  text-uppercase mb-1 text-muted">
                                                    Returned Orders
                                                </div>
                                                <div class="h5 mb-0 font-weight-bold text-gray-800" id="dashboard_number_of_orders_returned">0</div>
                                                <!-- <span class="small text-warning"> <i class="fa fa-long-arrow-down"></i> 2% from yesterday</span> -->
                                            </div>
                                        </div>
                                    </div>
                                </div>
                            </div>
                        </div>
                        <div class="row">
                            <div class="col-sm-12">
                                <h2 class="mt-3 mb-3">Products Sold</h2>
                                <div class=" table-responsive">
                                    <div id="msg"/>
                                    <table class="table table-hover" id="products-table">
                                        <thead>
                                        <tr class="row-gridh">
                                            <th scope="col">Name</th>
                                            <th scope="col">Price</th>
                                            <th scope="col">Items Stock</th>
                                            <th scope="col">Items Sold</th>
                                            <th scope="col">Amount</th>
                                            <th width="60">Action</th>
                                        </tr>
                                        </thead>
                                        <tbody>

                                        </tbody>
                                    </table>
                                </div>

                                <h2 class="mt-3 mb-3 d-inline-block w-100">Top Customers </h2>

                                <div class=" table-responsive">
                                    <table class="table table-striped" id="dashboard_top_customers">
                                        <thead>
                                        <tr class="row-gridh2">
                                            <th scope="col">Name</th>
                                            <th scope="col">Email</th>
                                            <th scope="col">Mobile</th>
                                            <th scope="col">Order Count</th>
                                            <th scope="col">Transaction Value</th>
                                        </tr>
                                        </thead>
                                        <tbody>
                                        </tbody>
                                    </table>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>



@endsection
@section('scripts')

    <!-- <script src="{{App\Helpers\CustomUrl::asset('js/js/flextable.js')}} "></script>
<script>
    $(document).ready(function() {
        $('#window').flextable({
            bt:'parent',
              bw: 768,
              cm:'auto',
              hparent:'',
              hcell:'',
              header:'before',
              combine:false
        });
    });
</script> -->

    <script>
        $(document).ready(function () {
            $(".filter-icons a.btn-lg").click(function () {
                $(".date-filter").toggleClass('date');
            });
        });
    </script>

<script type="text/javascript">

        var ordersTable = null;
        var customerTable = null;
        var customerData = [];

        var date_filter = 'day';


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

            $('input[name="daterange"]').on('apply.daterangepicker', function(ev, picker) {
                $(this).val(picker.startDate.format('YYYY-MM-DD') + ' - ' + picker.endDate.format('YYYY-MM-DD'));
            });

            $('input[name="daterange"]').on('cancel.daterangepicker', function(ev, picker) {
                $(this).val('');
            });

            $('.customer-select2, .order-select2').select2();

            ordersTable = $('#products-table').DataTable({
                "processing": true,
                "serverSide": true,
                "deferLoading" : 0,
                "ajax":{
                     "url": "{{ route('backoffice.dashboard.kpis') }}",
                     "dataType": "json",
                     "type": "POST",
                     "data": function(d){

                        if($('#daterange').val() != ''){
                            d['parameter_type'] = 'daterange';
                            d['date_filter'] = $('#daterange').val();;
                            d['daterange'] = 'daterange';
                        }else{
                            d['parameter_type'] = 'buttons';
                            d['date_filter'] = date_filter;
                        }
                    }
                },

                "columns": [

                    { data: 'name', sortable: false },
                    { data: 'price', sortable: false },
                    { data: 'stock' , sortable: false},
                    { data: 'quantity', sortable: false },
                    { data: 'amount' , sortable: false},

                    { data: 'action' , sortable: false, render : function(column,data,row){


                        let view_url = site_url(`product/${row.id}/edit`);
                        let edit_button = `<a href="${view_url}"><i class="fa fa-eye pr-3" title="View"></i></a>`;

                        return edit_button;

                    } }
                ],
                "order": [[ 0, "desc" ]],
                "createdRow": function( row, data, dataIndex ) {
                    if ( data.status == 'Confirmed' ) {
                        $(row).addClass('bg-warning');
                    }
                }
            });

            customerTable = $('#dashboard_top_customers').DataTable({
                responsive: true,
                aaSorting: [[ 3, "desc" ]],
                //data: result.customers_widget,
                data : customerData,
                columns: [
                    { data: 'name' },
                    { data: 'email', render: function (data, type, full) {
                        return '<a href="mailto:'+ data +'">'+ data +'</a>';
                    }},
                    { data: 'mobile' },
                    { data: 'Total_Orders' },
                    { data: 'Total_Spent' },
                ],

            });

            ordersTable.ajax.reload(reloadPage);
        });
        // $('#outlet_id').change(function(){
        //     if($('#outlet_id').val() != ''){
        //         localStorage.setItem('web_order_outlet_id',$('#outlet_id').val());
        //     }else{
        //         localStorage.setItem('web_order_outlet_id','');
        //     }
        //     ordersTable.ajax.reload(reloadPage);
        //     // window.location.reload();
        // });

        function applyParams(){
            ordersTable.ajax.reload(reloadPage);
        }


        setInterval(()=>{
            ordersTable.ajax.reload(reloadPage);
        },60000);

        $(function(){
            $('input[name="daterange"]').on('apply.daterangepicker', function(ev, picker) {
                $(this).val(picker.startDate.format('YYYY-MM-DD') + ' - ' + picker.endDate.format('YYYY-MM-DD'));
                var date_filter = picker.startDate.format('YYYY-MM-DD') + ' - ' + picker.endDate.format('YYYY-MM-DD');
                $('.date_filter').removeAttr('checked').trigger('change');
                $('.date_filter').parent().removeClass('active');
                $('#daterange').val(date_filter);
                ordersTable.ajax.reload(reloadPage);
            });
        });

        $(function(){
            $('.date_filter').on('change', function() {
                date_filter = $(this).data('filter');
                if(date_filter.length > 0){
                    $('#daterange').val('');
                    ordersTable.ajax.reload(reloadPage);
                }
            });
        });

        var blinker;

        function reloadPage(data){
                console.log(data);
            if(data != null){
                //customerData = data.extra.customers_widget;
                customerTable.clear().rows.add(data.extra.customers_widget).draw()

                if(Object.keys(data.extra.kpis).length > 0){
                    $('#dashboard_total_sales').html(data.extra.kpis[0]);
                    $('#dashboard_number_of_orders_without_failed').html(data.extra.kpis[1]);
                    $('#dashboard_number_of_orders_returned').html(data.extra.kpis[2]);
                    $('#dashboard_number_of_orders').html(data.extra.kpis[3]);
                    $('#dashboard_number_of_orders_confirmed').html(data.extra.kpis[4]);

                    $('#dashboard_out_of_stock_products').html(data.extra.kpis[5]);
                    $('#dashboard_top_sold_products').html(data.extra.kpis[6]);



                    if(data.extra.kpis[4] > 0){
                        $('.confirmed-orders').addClass('bg-warning');

                        clearInterval(blinker);

                        blinker = setInterval(() => {
                            $('.confirmed-orders').toggleClass('bg-warning');
                        }, 1000);

                    }else{
                        $('.confirmed-orders').removeClass('bg-warning');
                    }

                }else{
                    $('#dashboard_total_sales').html(0);
                    $('#dashboard_number_of_orders_without_failed').html(0);
                    $('#dashboard_number_of_orders_returned').html(0);
                    $('#dashboard_number_of_orders').html(0);
                    $('#dashboard_number_of_orders_confirmed').html(0);

                    $('#dashboard_out_of_stock_products').html(0);
                    $('#dashboard_top_sold_products').html(0);
                }


            }else{
                $('#dashboard_total_sales').html(0);
                $('#dashboard_number_of_orders_without_failed').html(0);
                $('#dashboard_number_of_orders_returned').html(0);
                $('#dashboard_number_of_orders').html(0);
                $('#dashboard_number_of_orders_confirmed').html(0);
                $('#dashboard_out_of_stock_products').html(0);
                $('#dashboard_top_sold_products').html(0);

            }
        }

</script>

<!-- //done remove commity due to issue in datarange seleton -->
<!-- <script src="https://cdnjs.cloudflare.com/ajax/libs/jquery/2.1.3/jquery.min.js"></script> -->

<script type="text/javascript">
    (function ($) {
        'use strict';
        $.fn.tableanarchy = function (options) {
            var tableanarchy = this,
                settings = $.extend({
                    labelClass: "",      // Class of row labels.
                    containerClass: ""   // Optional class to give the new container.
                }, options),
                aTableSelectors, currentSelector, tableRows, tableCells,
                className, contents, $newCont1, $newCont2, $newCont3;

            //$("#msg").text(tableanarchy.selector);
            //Create a main container to hold the moved table cell elements.
            $newCont1 = $('<div />');
            if (settings.containerClass) {
                $newCont1.addClass(settings.containerClass);
            }
            //Place the main container just above the existing table.
            tableanarchy.before($newCont1);
            //Get all table rows from the table, saving them in a JS object.
            tableRows = $(tableanarchy.selector + ' tr');
            //Loop through each item (table row) in the JS object.
            $.each(tableRows, function (count, item) {
                $newCont2 = $('<div />');
                //Copy any table row's attributes to the current item
                //$(item).each(function() {
                $.each(this.attributes, function (count, attrib) {
                    var name = attrib.name;
                    var value = attrib.value;
                    $newCont2.attr(name, value);
                });
                //});
                //Detach all table cells from the current item, saving them in a JS object.
                tableCells = $(item).children().detach();
                //Loop through each item in the JS object.
                $.each(tableCells, function (count, item) {
                    className = $(item).attr('class');
                    contents = $(item).contents();
                    if (className === settings.labelClass) {
                        //Create a new container with the same class as the existing item.
                        $newCont3 = $('<label />');
                    } else {
                        $newCont3 = $('<div />');
                    }
                    //Add the contents of the item to the new container.
                    $newCont3.append(contents).addClass(className);
                    /*Add class to container to indicate which table column it originally came
                      from. This will provide an additional method for styling the content.*/
                    $newCont3.addClass('col' + (count + 1).toString());
                    //Add the new container to the main container.
                    $newCont2.append($newCont3);
                });
                //Add a break at the end of the container so that the items will wrap correctly.
                //$newCont2.append('<br />'); // I'm not sure why I put this here. :(
                $newCont1.append($newCont2);
            });
            //Remove the now empty original table.
            tableanarchy.remove();
        };
    }(jQuery));
    if ($(window).width() < 700) {
        $("#window").tableanarchy({
            containerClass: "new-structure"
        });
        $("#window2").tableanarchy({
            containerClass: "new-structure"
        });
    }
    setTimeout(function() { $('.danger').alert('close'); }, 5000);

</script>

@endsection
