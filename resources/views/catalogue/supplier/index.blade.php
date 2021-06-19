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

    </style>
    @include('catalogue.supplier.delete-modal')


    <div class="row">
        <div class="col-sm-12">
            <div class="greybg1 rounded p-4 mb-3">
                <div class="row">
                    <div class="col-sm-12">
                        <div class="common_title">

                            <h1>
                                {{ __('backoffice.suppliers') }}
                                @if( $permission::chekStatus('supplier_add','admin'))
                                    <a href="{{ route('supplier.create')}}"
                                       class="m-b-xs w-auto btn-primary btn-sm pull-right">
                                        {{ __('backoffice.add_supplier') }}
                                    </a>
                                @endif
                            </h1>

                        </div>
                    </div>
                </div>
            </div>
            <div class="bg-light p-4 rounded">
                <div class=" rounded">
                    <div class="row">
                        <div class="col-md-12">
                            <div class="bg-light rounded">
                                <div class=" table-responsive">
                                    @if( $permission::chekStatus('supplier_edit','admin'))

                                    <div class="btn-toolbar" role="toolbar" aria-label="Toolbar with button groups">
                                        <div class="btn-group mr-2" role="group" aria-label="First group">
                                            <div class="custom-control custom-checkbox header-checkbox">
                                                <input type="checkbox" class="custom-control-input" id="supplier-all">
                                                <label class="custom-control-label" for="supplier-all">&nbsp;</label>
                                            </div>
                                            <select class="custom-select" id="bulk-action">
                                                <option value="">{{__('backoffice.bulk_actions')}}</option>
                                                <option value="1">{{__('backoffice.activate')}}</option>
                                                <option value="2">{{__('backoffice.deactivate')}}</option>
                                                <option value="3">{{__('backoffice.delete')}}</option>
                                            </select>
                                            <button type="button" class="btn btn-primary btn-xs btn-bulk ml-3"
                                                    id="bulk-apply">Apply
                                            </button>
                                        </div>
                                    </div>
                                    @endif
                                    <br/>
                                    <table class="table table-striped display responsive nowrap" id="supplier-table">
                                        <thead>
                                        <tr>
                                            <th style="width:5%">&nbsp;</th>
                                            <th>{{ __('backoffice.name') }} </th>
                                            <th>{{ __('backoffice.mobile') }} </th>
                                            <th>{{ __('backoffice.email') }} </th>
                                            <th>{{ __('backoffice.total_products') }} </th>
                                            <th>{{ __('backoffice.active') }} </th>
                                            <th>{{ __('backoffice.actions') }} </th>
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
    </div>
@endsection
@section('scripts')
    <script src="https://cdn.datatables.net/responsive/2.2.7/js/dataTables.responsive.min.js"></script>
    <script type="text/javascript">
        var supplierTable = null;

        $(document).ready(function () {
            supplierTable = $('#supplier-table').DataTable({
                "responsive": {
                    "details": {
                        "type": "column"
                    }
                },
                "processing": true,
                "serverSide": true,
                "deferLoading": 0,
                "ajax": {
                    "url": "{{ route('api.fetch.suppliers') }}",
                    "dataType": "json",
                    "type": "POST",
                    "data": function (d) {

                    }
                },
                "columns": [

                    {
                        sortable: false,
                        'className': 'dt-body-center',
                        'render': function (column, row, data, other) {
                            console.log()
                            return `
                     <div class="expend"></div>
                     <div class="custom-control custom-checkbox">
                        <input type="checkbox" class="custom-control-input supplier-row" data-row="${other.row}" data-supplier-id="${data.id}" id="check-${data.id}">
                        <label class="custom-control-label" for="check-${data.id}">&nbsp;</label>
                     </div>
                     `
                        }
                    },
                    {data: 'name'},
                    {data: 'mobile'},
                    {data: 'email'},
                    {data: 'total_products'},

                    {
                        data: 'active', render: function (column, row, data) {
                            return `
                             @if( $permission::chekStatus('supplier_edit','admin'))

                  <div class="custom-control custom-switch center-align">
                     <input type="checkbox" class="custom-control-input" id="active-${data.id}" ${data.active == 1 ? "checked" : ""} >
                     <label onclick="toggleSupplier('active',${data.id})" class="custom-control-label" for="active-${data.id}">&nbsp;</label>
                  </div>
                  @endif
                  `;
                        }
                    },
                    {
                        data: 'actions', sortable: false, render: function (column, row, data) {
                            return `
                             @if( $permission::chekStatus('supplier_edit','admin'))

                            <a href="{{ url('catalogue/supplier/${data.id}/edit') }}" class="badge btn-primary">
                        {{ __('backoffice.edit') }}
                            </a>
                            @endif
                            @if( $permission::chekStatus('supplier_delete','admin'))
                            <button type="button" onclick="openDeleteModal(${data.id})" class="badge btn-primary deleteSupplier" data-supplierid="${data.id}">
                        {{ __('backoffice.delete') }}
                            </button>
                            @endif
`;
                        }
                    },
                ],
            });

            supplierTable.ajax.reload();
            $('#supplier-table').removeClass('dataTable');
        });


        function openDeleteModal(supplierId) {
            $('#supplier_id').val(supplierId);
            $('#supplier_delete_form').attr('action', "{{ route('api.delete.supplier') }}");
            $('#supplier_delete_modal').modal('show');

        }


        $(function () {
            $('#supplier_delete_form').submit(function (e) {
                e.preventDefault();
                $.ajax({
                    url: $(this).attr('action'),
                    type: $(this).attr('method'),
                    data: $(this).serialize(),
                    success: function (response) {
                        if (response.IsValid) {
                            toastr.success(response.Message, 'Success');
                            $('#supplier_delete_modal').modal('hide');
                            supplierTable.ajax.reload();
                        } else if(response.IsValid == false){
                            $('#supplier_delete_modal').modal('hide');
                            toastr.error(response.Message, 'Error');
                     } else {
                            $('#supplier_delete_modal').modal('hide');
                            toastr.error(response.Errors[0], 'Error');
                        }
                    }
                })
                return false;
            });
        });

        $(function () {
            $('#supplier-all').change(function () {
                if ($(this).is(':checked')) {
                    $('.supplier-row').prop('checked', true).trigger('change');
                } else {
                    $('.supplier-row').prop('checked', false).trigger('change');
                }
            });
        });
        $(function () {
            $('#bulk-apply').click(function () {
                let count = 0;
                let suppliers = [];
                let rows = [];

                $('.supplier-row').each(function () {
                    if ($(this).is(':checked')) {
                        suppliers.push(parseInt($(this).data('supplier-id')));
                        rows.push(parseInt($(this).data('row')));
                        count++;
                    }
                });

                if (count == 0) {
                    toastr.error('No row(s) selected', 'Error');
                    return;
                }

                let val = parseInt($('#bulk-action').val());

                if (suppliers.length > 0 && val > 0) {
                    let enables = [1, 3, 5, 7];
                    let disables = [2, 4, 6, 8];
                    let type = 'delete';
                    let active = true;

                    if ([1, 2].includes(val)) {
                        type = 'active';

                    } else {
                        type = 'delete';
                    }

                    if (enables.includes(val)) {
                        active = true;
                    } else if (disables.includes(val)) {
                        active = false;
                    }

                    toggleAllSupplier(type, suppliers, rows, active);
                }
            });

        });

        function toggleAllSupplier(type, ids, rows, active) {
            $.ajax({
                url: "{{ route('api.toggle.supplier.all') }}",
                data: {suppliers: ids, type: type, action: active},
                type: 'POST',
                success: function (response) {
                    if (response.IsValid) {
                        supplierTable.ajax.reload();
                        toastr.success(response.Message, 'Success');
                        $('#supplier-all').prop('checked', false).trigger('change');
                    }else if(response.IsValid == false){
                        supplierTable.ajax.reload();
                        toastr.error(response.Message, 'Error');
                        $('#supplier-all').prop('checked', false).trigger('change');
                    }
                }
            });
        }


        function toggleSupplier(type, id, row) {
            $.ajax({
                url: "{{ route('api.toggle.supplier') }}",
                data: {id: id, type: type},
                type: 'POST',
                success: function (response) {
                    if (response.IsValid) {
                        if (row != null) {
                            supplierTable.row(row).data(response.Payload).draw();
                        }
                        toastr.success(response.Message, 'Success');
                    }
                }
            });
        }


    </script>
@endsection
