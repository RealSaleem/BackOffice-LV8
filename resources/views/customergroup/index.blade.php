@extends('layouts.backoffice')
@section('content')
    @php
        $permission = '\App\Helpers\Helper';
    @endphp
    <!-- content -->

    @include('customergroup.delete-modal')


    <div class="row">
        <div class="col-sm-12">
            <div class="greybg1 rounded p-4 mb-3">
                <div class="row">
                    <div class="col-sm-12">
                        <div class="common_title">
                            <h1>
                                {{ __('backoffice.customer groups') }}
                                @if( $permission::chekStatus('customer_group_add','admin'))

                                    <a href="{{ route('customergroup.create')}}"
                                       class="m-b-xs w-auto btn-primary btn-sm pull-right">
                                        {{ __('backoffice.add_customer_group') }}
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
                                    @if( $permission::chekStatus('customer_group_edit','admin'))

                                        <div class="btn-toolbar" role="toolbar" aria-label="Toolbar with button groups">
                                            <div class="btn-group mr-2" role="group" aria-label="First group">
                                                <div class="custom-control custom-checkbox header-checkbox">
                                                    <input type="checkbox" class="custom-control-input"
                                                           id="customergroup-all">
                                                    <label class="custom-control-label"
                                                           for="customergroup-all">&nbsp;</label>
                                                </div>
                                                <select class="custom-select" id="bulk-action">
                                                    <option value="">{{__('backoffice.bulk_action')}}</option>
                                                    <option value="1">{{__('backoffice.delete')}}</option>
                                                </select>
                                                <button type="button" class="btn btn-primary btn-xs btn-bulk ml-3"
                                                        id="bulk-apply">Apply
                                                </button>
                                            </div>
                                        </div>
                                    @endif
                                    <br/>
                                    <table class="table table-striped" id="customergroup-table">
                                        <thead>
                                        <tr>
                                            <th style="width:5%">&nbsp;</th>
                                            <th>{{ __('backoffice.name') }} </th>
                                            {{--                                 <th>{{ __('customergroup.created_at') }} </th>--}}
                                            <th>{{ __('backoffice.total_customers') }} </th>
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
    <script type="text/javascript">
        var customergroupTable = null;

        $(document).ready(function () {
            customergroupTable = $('#customergroup-table').DataTable({
                "processing": true,
                "serverSide": true,
                "deferLoading": 0,
                "ajax": {
                    "url": "{{ route('api.fetch.customersgroup') }}",
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
                     <div class="custom-control custom-checkbox">
                        <input type="checkbox" class="custom-control-input customergroup-row" data-row="${other.row}" data-customergroup-id="${data.id}" id="check-${data.id}">
                        <label class="custom-control-label" for="check-${data.id}">&nbsp;</label>
                     </div>
                     `
                        }
                    },

                    {data: 'name'},
                    // { data: 'created_at' , sortable : true  },
                    {data: 'total_customers', sortable: false,},
                    {
                        data: 'actions', sortable: false, render: function (column, row, data) {
                            return `
                        @if( $permission::chekStatus('customer_group_edit','admin'))

                            <a href="{{ url('customers/customergroup/${data.id}/edit') }}" class="badge btn-primary">
                        {{ __('backoffice.edit') }}
                            </a>
                            @endif
                            @if( $permission::chekStatus('customer_group_delete','admin'))

                            <button onclick="openDeleteModal(${data.id})" class="badge btn-primary deleteCustomerGroup" data-customergroupid="${data.id}">
                        {{ __('backoffice.delete') }}
                            </button>
                            @endif
                            `;
                        }
                    },


                ],
            });

            customergroupTable.ajax.reload();
            $('#customergroup-table').removeClass('dataTable');

        });

        function openDeleteModal(customergroupId) {
            $('#customergroup_id').val(customergroupId);
            $('#customergroup_delete_form').attr('action', "{{ route('api.delete.customergroup') }}");
            $('#customergroup_delete_modal').modal('show');

        }

        $(function () {
            $('#customergroup_delete_form').submit(function (e) {
                e.preventDefault();
                $.ajax({
                    url: $(this).attr('action'),
                    type: $(this).attr('method'),
                    data: $(this).serialize(),
                    success: function (response) {
                        console.log(response);
                        if (response.IsValid) {
                            toastr.success(response.Message, 'Success');
                            $('#customergroup_delete_modal').modal('hide');
                            customergroupTable.ajax.reload();
                        } else {
                            $('#customergroup_delete_modal').modal('hide');
                            toastr.error(response.Message, 'Error');
                        }
                    }
                })
                return false;
            });
        });
        $(function () {
            $('#customergroup-all').change(function () {
                if ($(this).is(':checked')) {
                    $('.customergroup-row').prop('checked', true).trigger('change');
                } else {
                    $('.customergroup-row').prop('checked', false).trigger('change');
                }
            });
        });
        $(function () {
            $('#bulk-apply').click(function () {
                let count = 0;
                let customersgroup = [];
                let rows = [];

                $('.customergroup-row').each(function () {
                    if ($(this).is(':checked')) {
                        customersgroup.push(parseInt($(this).data('customergroup-id')));
                        rows.push(parseInt($(this).data('row')));
                        count++;
                    }
                });

                if (count == 0) {
                    toastr.error('No row(s) selected', 'Error');
                    return;
                }

                let val = parseInt($('#bulk-action').val());

                if (customersgroup.length > 0 && val > 0) {
                    let enables = [1];
                    let disables = [2];
                    let type = 'delete';


                    // if([1,2].includes(val)){


                    //    type = 'delete';
                    // }

                    // if(enables.includes(val)){
                    //    active = true;
                    // }else if(disables.includes(val)){
                    //    active = false;
                    // }

                    deleteAllCustomerGroup(type, customersgroup, rows);
                }
            });


            function deleteAllCustomerGroup(type, ids, rows) {

                $.ajax({
                    url: "{{ route('api.delete.customergroup.all') }}",
                    data: {customersgroup: ids, type: type},
                    type: 'POST',
                    success: function (response) {
                        if (response.IsValid) {
                            customergroupTable.ajax.reload();
                            toastr.success(response.Message, 'Success');
                            $('#customergroup-all').prop('checked', false).trigger('change');
                        } else {
                            toastr.error(response.Message, 'error');
                            customergroupTable.ajax.reload();
                        }
                    }
                });
            }
        });


    </script>
@endsection
