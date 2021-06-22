@extends('layouts.backoffice')
@section('content')
    @php
        $permission = '\App\Helpers\Helper';
    @endphp
    <!-- content -->
    @include('users_role.roles.delete-modal')
    <div class="row">
        <div class="col-sm-12">
            <div class="greybg1 rounded p-4 mb-3">
                <div class="row">
                    <div class="col-sm-12">
                        <div class="common_title">
                            <h1>
                                {{ __('backoffice.roles') }}


                                <a href="{{ route('roles.create')}}"
                                   class="m-b-xs w-auto btn-primary btn-sm pull-right">
                                    {{ __('backoffice.add_role') }}

                                </a>

                            </h1>
                        </div>
                    </div>
                </div>
            </div>
            {{--       @dd(Auth::user()->id)--}}

            <div class="bg-light p-4 rounded">
                <div class=" rounded">
                    <div class="row">
                        <div class="col-md-12">
                            <div class="bg-light rounded">
                                <div class=" table-responsive">
                                    <table class="table table-striped" id="role-table">
                                        <thead>
                                        <tr>
                                        <!-- <th>{{ __('backoffice.name') }} </th> -->
                                            <th>{{ __('backoffice.name') }} </th>
                                            <th>{{ __('backoffice.title') }} </th>

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
    </div>
@endsection
@section('scripts')
    <script type="text/javascript">
        var roleTable = null;

        $(document).ready(function () {
            roleTable = $('#role-table').DataTable({
                "processing": true,
                "serverSide": true,
                "deferLoading": 0,
                "ajax": {
                    "url": "{{ route('api.fetch.roles') }}",
                    "dataType": "json",
                    "type": "POST",
                    "data": function (d) {

                    },

                },
                "columns": [
                    // { data: 'id' },
                    {data: 'name'},
                    {data: 'display_name'},
                    // { data: 'description' },
                    {
                        data: 'actions', sortable: false, render: function (column, row, data) {
                            if (data.name != 'admin') {
                                return `
                                @can('edit-role')
                                <a href="{{url('usermanagement/roles/edit/${data.id}') }}" class="badge btn-primary">
                          {{ __('backoffice.edit') }}
                                </a>
                                @endcan
                                @can('delete-role')
                                <button onclick="openDeleteModal(${data.id})" class="badge btn-primary">
                          {{ __('backoffice.delete') }}
                                </button>
                                @endcan
                                `;
                            } else {
                                return ``;
                            }
                        }
                    },
                ],
            });

            roleTable.ajax.reload();
            $('#role-table').removeClass('dataTable');
        });

        function openDeleteModal(id) {
            $('#role_id').val(id);
            $('#role_delete_form').attr('action', "{{ route('api.delete.roles') }}");
            $('#role_delete_modal').modal('show');
        }

        $(function () {
            $('#role_delete_form').submit(function (e) {
                e.preventDefault();
                $.ajax({
                    url: $(this).attr('action'),
                    type: $(this).attr('method'),
                    data: $(this).serialize(),
                    success: function (response) {
                        if (response.IsValid) {
                            toastr.success(response.Message, 'Success');
                            $('#role_delete_modal').modal('hide');
                            roleTable.ajax.reload();
                        } else {
                            $('#role_delete_modal').modal('hide');
                            toastr.error(response.Errors[0], 'Error');
                        }
                    }
                })
                return false;
            });
        });
    </script>
@endsection
