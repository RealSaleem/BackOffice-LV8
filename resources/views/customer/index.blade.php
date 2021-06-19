@extends('layouts.backoffice')
@section('content')
    @php
        $permission = '\App\Helpers\Helper';
    @endphp
    <!-- content -->
    <link rel="stylesheet" type="text/css"
          href="https://cdn.datatables.net/responsive/2.2.7/css/responsive.dataTables.min.css">
    <style type="text/css">
        div.container {
            max-width: 1200px
        }
        @media screen and (max-width: 600px) {
            .table-customer td.sorting_1:before {
                content: '+';
                /* position: absolute; */
                top: 55px;
                left: 15px;
                width: 20px;
                height: 20px;
                font-size: 18px;
                background: #4caf50;
                border-radius: 50%;
                color: #fff;
                line-height: 20px;
                text-align: center;
                display: inline-block;
                margin-bottom: 11px;
                margin-right:5px;
             }
            .table-customer .parent td.sorting_1:before {
                content: '-';
                background:#f44336;
            }

        }


    </style>
    @include('customer.delete-modal')


    <div class="row">
        <div class="col-sm-12">
            <div class="greybg1 rounded p-4 mb-3">
                <div class="row">
                    <div class="col-sm-12">
                        <div class="common_title">
                            <h1>
                                {{ __('backoffice.customers') }}
                                @if( $permission::chekStatus('customer_add','admin'))
                                    <a href="{{ route('customer.create')}}"
                                       class="m-b-xs w-auto btn-primary btn-sm pull-right">
                                        {{ __('backoffice.add_customer') }}

                                    </a>
                                @endif
                            </h1>
                        </div>
                    </div>
                </div>
            </div>


            <div class="col-sm-12">
                <ul class="nav nav-tabs nav-justified" id="myTab" role="tablist">
                    <li class="nav-item greybg1">
                        <a class="nav-link active" id="active-tabs" data-toggle="tab" href="#tab_1" role="tab"
                           aria-controls="home" aria-selected="true">{{ __('backoffice.general_customers') }}</a>
                    </li>
                    <li class="nav-item greybg1">
                        <a class="nav-link" id="inactive-tab2" data-toggle="tab" href="#tab_2" role="tab"
                           aria-controls="tab_2" aria-selected="false">{{ __('backoffice.E_comerce_customers') }}</a>
                    </li>
                </ul>
            </div>
            <div class="tab-content">
                <div class="tab-pane active" id="tab_1">
                    <div class="bg-light p-4 rounded">
                        <div class=" rounded">
                            <div class="row">
                                <div class="col-md-12">
                                    <div class="bg-light rounded">
                                        <div class=" table-responsive table-customer">
                                            <table class="table table-striped display responsive nowrap"
                                                   id="customer-table">
                                                <thead>
                                                <tr>
                                                    <th>{{ __('backoffice.name') }} </th>
                                                    <th>{{ __('backoffice.phone') }} </th>
                                                    <th>{{ __('backoffice.email') }} </th>
                                                    <th>{{ __('backoffice.group') }} </th>
                                                    <th>{{ __('backoffice.action') }} </th>
                                                </tr>
                                                </thead>

                                            </table>
                                        </div>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
                <div class="tab-pane" id="tab_2">
                    <div class="bg-light p-4 rounded">
                        <div class=" rounded">
                            <div class="row">
                                <div class="col-md-12">
                                    <div class="bg-light rounded">
                                        <div class=" table-responsive">
                                            <table id="eCommerce-table" class="display nowrap" style="width:100%">
                                                <thead>
                                                <tr>
                                                    <th>{{ __('backoffice.name') }} </th>
                                                    <th>{{ __('backoffice.mobile') }} </th>
                                                    <th>{{ __('backoffice.email') }} </th>
                                                    @if( $permission::chekStatus('customer_edit','admin'))
                                                        <th>{{ __('backoffice.action') }} </th>
                                                    @endif
                                                </tr>
                                                </thead>
                                                <tbody>
                                                @if($Customer)
                                                    @foreach($Customer as $customers)
                                                        <tr>
                                                            <td><div class="expend"></div> {{$customers->name}}</td>
                                                            <td>{{$customers->mobile}}</td>
                                                            <td>{{$customers->email}}</td>
                                                            @if( $permission::chekStatus('customer_edit','admin'))
                                                                <td>
                                                                    <a href="{{url('customer/edit/') }}"
                                                                       class="badge btn-primary">
                                                                        {{ __('backoffice.edit') }}
                                                                    </a>
                                                                </td>
                                                            @endif
                                                        </tr>
                                                    @endforeach
                                                @endif

                                                </tbody>
                                            </table>
                                        </div>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>


                    {{--                              <div class="ml-4 mt-4">--}}
                    {{--                              <form class="example" action="action_page.php">--}}
                    {{--                                 <input type="text" placeholder="Search.." name="search">--}}
                    {{--                                 <button class="btn-primary btn-sm" >Search</button>--}}
                    {{--                              </form>--}}
                    {{--                           </div>--}}

                </div>
            </div>
        </div>
    </div>
@endsection
@section('scripts')
    <script src="https://cdn.datatables.net/responsive/2.2.7/js/dataTables.responsive.min.js"></script>

    <script type="text/javascript">
        var customerTable = null;
        $(document).ready(function () {
            customerTable = $('#customer-table').DataTable({
                "responsive": {
                    "details": {
                        "type": "column"
                    }
                },
                "processing": false,
                "serverSide": true,
                "deferLoading": 0,

                "ajax": {
                    "url": "{{ route('api.fetch.customers') }}",
                    "dataType": "json",
                    "type": "POST",
                    "data": function (d) {

                    }
                },
                "columns": [
                    //  {
                    //    'className': 'dt-body-center',
                    //    'render': function (column,row,data,other){
                    //       console.log()
                    //       return `
                    //       <div class="expend"></div>
                    //       `
                    //    }
                    // },
                    {data: 'name'},
                    {data: 'phone'},
                    {data: 'email'},
                    {data: 'customergroup'},

                    {
                        data: 'actions', 'render': function (column, row, data, other) {
                            return `
                             @if( $permission::chekStatus('customer_edit','admin'))
                            <a href='{{ url('customers/customer/${data.id}/edit') }}' class="badge btn-primary">
                                {{ __('backoffice.edit') }}
                            </a>
                            @endif`;
                        }
                    },
                ],
            });

            customerTable.ajax.reload()
            $('#eCommerce-table').DataTable({
                "processing": true,
                "deferLoading": 0,
            });
        });
    </script>
@endsection
