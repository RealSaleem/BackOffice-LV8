@extends('layouts.backoffice')
@section('content')
    @php
        $permission = '\App\Helpers\Helper';
    @endphp
    <!-- content -->

    <style type="text/css">
        div.container {
            max-width: 1200px
        }

    </style>
    @include('users_role.users.delete-modal')

    <div class="row">
        <div class="col-sm-12">
            <div class="greybg1 rounded p-4 mb-3">
                <div class="row">
                    <div class="col-sm-12">
                        <div class="common_title">
                            <h1 class="mb-2">
                                {{ __('backoffice.user') }}
                                @if( $permission::chekStatus('user_add','admin'))
                                    <a href="{{ route('users.create')}}"
                                       class="m-b-xs w-auto btn-primary btn-sm pull-right">
                                        {{ __('backoffice.add_user') }}
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
                                <div class="greybg1 rounded">
                                    <div class="bg-light p-4 rounded">
                                        <div class=" table-responsive">
                                            @if( $permission::chekStatus('user_edit','admin'))
                                                <div class="btn-toolbar" role="toolbar"
                                                     aria-label="Toolbar with button groups">
                                                    <div class="btn-group mr-2" role="group" aria-label="First group">
                                                        <div class="custom-control custom-checkbox header-checkbox">
                                                            <input type="checkbox" class="custom-control-input"
                                                                   id="users-all">
                                                            <label class="custom-control-label"
                                                                   for="users-all">&nbsp;</label>
                                                        </div>
                                                        <select class="custom-select" id="bulk-action">
                                                            <option value="">{{ __('backoffice.bulk_actions') }}</option>
                                                            <option value="1">{{ __('backoffice.activate') }}</option>
                                                            <option value="2">{{ __('backoffice.deactivate') }}</option>
                                                            <option value="3">{{ __('backoffice.delete') }}</option>
                                                        </select>
                                                        <button type="button"
                                                                class="btn btn-primary btn-xs btn-bulk ml-3"
                                                                id="bulk-apply">{{ __('backoffice.apply') }}</button>
                                                    </div>
                                                </div>
                                            @endif
                                            <hr/>
                                            <table class="table table-striped display responsive nowrap"
                                                   id="users-table">
                                                <thead>
                                                <tr>
                                                    <th style="width:5%">&nbsp;</th>
                                                    <th style="width:10%">{{ __('backoffice.image') }} </th>
                                                    <th style="width:10%">{{ __('backoffice.name') }} </th>
                                                    <th style="width:10%">{{ __('backoffice.mobile') }} </th>
                                                    <th style="width:10%">{{ __('backoffice.outlet') }} </th>
                                                    <th style="width:10%">{{ __('backoffice.role') }} </th>
                                                    <th style="width:10%">{{ __('backoffice.active') }} </th>
                                                    <th style="width:10%">{{ __('backoffice.action') }} </th>
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
        </div>
    </div>

    <style>
        .tb_name {
            height: 8px;
        }

    </style>
@endsection

@section('scripts')
    <script type="text/javascript">
        var usersTable = null;

        $(document).ready(function () {
            userTable = $('#users-table').DataTable({
                "responsive": {
                    "details": {
                        "type": "column"
                    }
                },
                "processing": true,
                "serverSide": true,
                "deferLoading": 0,
                "ajax": {
                    "url": "{{ route('api.fetch.users') }}",
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
                            if(data.role_name !='admin'){
                            return `
                                 <div class="expend"></div>
                                 <div class="custom-control custom-checkbox">
                                    <input type="checkbox" class="custom-control-input user-row" data-row="${other.row}" data-user-id="${data.id}" id="check-${data.id}">
                                    <label class="custom-control-label" for="check-${data.id}">&nbsp;</label>
                                 </div>
                                 `;
                                 }else{
                                return ``;
                            }

                        }
                    },
                    {
                        data: 'image', render: function (column, row, data) {
                            if (data.image != null) {
                                return `<div class="row no-gutters">
                              <div class="col-auto">
                               <img src="${data.image}" class="img-fluid" alt="" style="width:50px;height:50px; order-radius: 50%; object-fit:cover;">
                              </div>
                        </div>`;
                            } else {
                                return `<div class="row no-gutters">
                              <div class="col-auto">

                                <img src="{{ CustomUrl::asset('backoffice/assets/img/dummy-user.png') }}"
                                 class="img-responsive p-2 biglogo" style="    width: 44%;">

                              </div>
                        </div>`;
                            }
                        }
                    },

                    {
                        data: 'name', render: function (column, row, data) {
                            return `<p class="tb_name">${data.name}</p><p class="tb_name">${data.email}</p>`;
                        }
                    },

                    {data: 'mobile',sortable: true},
                    {data: 'outlet', sortable: false},
                    {data: 'role', sortable: false},
                    {
                        data: 'active', sortable: false, render: function (column, row, data) {
                            if(data.role_name !='admin'){
                                return `${data.active == 1 ? "Active" : "In Active"}
                                    @if( $permission::chekStatus('user_edit','admin'))
                                                <div class="custom-control custom-switch center-align">
                                                   <input type="checkbox" class="custom-control-input" id="active-${data.id}" ${data.active == 1 ? "checked" : ""} >
                                         <label onclick="toggleuser('active',${data.id})" class="custom-control-label" for="active-${data.id}">&nbsp;</label>
                                      </div>
                                      @endif
                                `;
                            }else{
                                return `${data.active == 1 ? "Active" : "In Active"}`;
                            }
                        }
                    },
                    {
                        data: 'actions', sortable: false, render: function (column, row, data) {
                            if(data.role_name !='admin'){
                                return `
                                  @if( $permission::chekStatus('user_edit','admin'))
                                <a href="{{ url('users/${data.id}/edit') }}" class="badge btn-primary">
                                    {{ __('backoffice.edit') }}
                                </a>
                                @endif
                                @if( $permission::chekStatus('user_delete','admin'))
                                <button type="button" onclick="openDeleteModal(${data.id})" class="badge btn-primary deleteUser" data-userid="${data.id}">
                                    {{ __('backoffice.delete') }}
                                </button>
                                @endif
                                 `;
                            }else{
                                return ``;
                            }
                        }
                    },


                ],
            });

            userTable.ajax.reload();
            $('#users-table').removeClass('dataTable');
        });

        function openDeleteModal(userId) {
            $('#user_id').val(userId);
            $('#user_delete_form').attr('action', "{{ route('api.delete.user') }}");
            $('#user_delete_modal').modal('show');

        }


        $(function () {
            $('#user_delete_form').submit(function (e) {
                e.preventDefault();
                $.ajax({
                    url: $(this).attr('action'),
                    type: $(this).attr('method'),
                    data: $(this).serialize(),
                    success: function (response) {
                        if (response.IsValid) {
                            toastr.success(response.Message, 'Success');
                            $('#user_delete_modal').modal('hide');
                            userTable.ajax.reload();
                        } else {
                            $('#user_delete_modal').modal('hide');
                            toastr.error(response.Errors[0], 'Error');
                        }
                    }
                })
                return false;
            });
        });

        $(function () {
            $('#users-all').change(function () {
                if ($(this).is(':checked')) {
                    $('.user-row').prop('checked', true).trigger('change');
                } else {
                    $('.user-row').prop('checked', false).trigger('change');
                }
            });
        });
        $(function () {
            $('#bulk-apply').click(function () {
                let count = 0;
                let user = [];
                let rows = [];

                $('.user-row').each(function () {
                    if ($(this).is(':checked')) {
                        user.push(parseInt($(this).data('user-id')));
                        rows.push(parseInt($(this).data('row')));
                        count++;
                    }
                });

                if (count == 0) {
                    toastr.error('No row(s) selected', 'Error');
                    return;
                }

                let val = parseInt($('#bulk-action').val());

                if (user.length > 0 && val > 0) {
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

                    toggleAllUsers(type, user, rows, active);
                }
            });

        });

        function toggleAllUsers(type, ids, rows, active) {
            $.ajax({
                url: "{{ route('api.toggle.user.all') }}",
                data: {users: ids, type: type, action: active},
                type: 'POST',
                success: function (response) {
                    if (response.IsValid) {
                        userTable.ajax.reload();
                        toastr.success(response.Message, 'Success');
                        $('#users-all').prop('checked', false).trigger('change');
                    }
                }
            });
        }

        function toggleuser(type, id, row) {
            $.ajax({
                url: "{{ route('api.toggle.user') }}",
                data: {id: id, type: type},
                type: 'POST',
                success: function (response) {
                    if (response.IsValid) {
                        if (row != null) {
                            userTable.row(row).data(response.Payload).draw();
                        }
                        toastr.success(response.Message, 'Success');
                    }
                }
            });
        }


    </script>
@endsection
