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
    @include('catalogue.brands.delete-modal')


    <div class="row">
        <div class="col-sm-12">
            <div class="greybg1 rounded p-4 mb-3">
                <div class="row">
                    <div class="col-sm-12">
                        <div class="common_title">

                            <h1>
                                {{ __('backoffice.brands') }}
                                @if( $permission::chekStatus('brand_add','admin'))
                                    <a href="{{ route('brands.create')}}"
                                       class="m-b-xs w-auto btn-primary btn-sm pull-right">
                                        {{ __('backoffice.add_brand') }}
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
                                    @if( $permission::chekStatus('brand_edit','admin'))

                                        <div class="btn-toolbar" role="toolbar" aria-label="Toolbar with button groups">
                                            <div class="btn-group mr-2" role="group" aria-label="First group">
                                                <div class="custom-control custom-checkbox header-checkbox">
                                                    <input type="checkbox" class="custom-control-input" id="brands-all">
                                                    <label class="custom-control-label" for="brands-all">&nbsp;</label>
                                                </div>
                                                <select class="custom-select" id="bulk-action">
                                                    <option value="">{{ __('backoffice.bulk_actions') }}</option>
                                                    <option value="1">{{ __('backoffice.activate') }}</option>
                                                    <option value="2">{{ __('backoffice.deactivate') }}</option>
                                                    <option value="4">{{ __('backoffice.feature') }}</option>
                                                    <option value="5">{{ __('backoffice.unfeature') }}</option>
                                                    <option value="3">{{ __('backoffice.delete') }}</option>
                                                </select>
                                                <button type="button" class="btn btn-primary btn-xs btn-bulk ml-3"
                                                        id="bulk-apply">{{ __('backoffice.apply') }}</button>
                                            </div>
                                        </div>
                                    @endif
                                    <hr/>
                                    <table class="table table-striped display responsive nowrap" id="brands-table">
                                        <thead>
                                        <tr>
                                            <th style="width:5%">&nbsp;</th>
                                            <th>{{ __('backoffice.name') }} </th>
                                            <th>{{ __('backoffice.number_of_products') }} </th>
                                            <th>{{ __('backoffice.active') }} </th>
                                            <th>{{ __('backoffice.feature') }} </th>
                                            <th>{{ __('backoffice.actions') }} </th>
                                        </tr>
                                        </thead>
                                    </table>
                                    <hr/>
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
        var brandsTable = null;

        $(document).ready(function () {
            brandsTable = $('#brands-table').DataTable({
                "responsive": {
                    "details": {
                        "type": "column"
                    }
                },
                "processing": true,
                "serverSide": true,
                "deferLoading": 0,
                "ajax": {
                    "url": "{{ route('api.fetch.brands') }}",
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
                        <input type="checkbox" class="custom-control-input brands-row" data-row="${other.row}" data-brands-id="${data.id}" id="check-${data.id}">
                        <label class="custom-control-label" for="check-${data.id}">&nbsp;</label>
                     </div>
                     `
                        }
                    },
                    {
                        data: 'name',
                        render: function (column, row, data) {

                            return `
                        <div class="row no-gutters">
                              <div class="col-auto">
                                 <img src="${data.image}" class="img-fluid" alt="" style="width:50px;height:50px;object-fit:cover;">
                              </div>
                              <div class="col">
                                 <div class="card-block px-2">
                                    <span class="card-title">${data.name}</span>
                                 </div>
                              </div>
                        </div>
                  `;
                        }
                    },
                    {data: 'number_of_products'},
                    {
                        data: 'active', sortable: false, render: function (column, row, data) {
                            return `
                                @if( $permission::chekStatus('brand_edit','admin'))

                            <div class="custom-control custom-switch center-align">
                               <input type="checkbox" class="custom-control-input" id="active-${data.id}" ${data.active == 1 ? "checked" : ""} >
                     <label onclick="toggleBrands('active',${data.id})" class="custom-control-label" for="active-${data.id}">&nbsp;</label>
                  </div>
                  @endif
                            `;
                        }
                    },
                    {
                        data: 'feature', sortable: false, render: function (column, row, data) {
                            return `
                                @if( $permission::chekStatus('brand_edit','admin'))

                            <div class="custom-control custom-switch center-align">
                               <input type="checkbox" class="custom-control-input" id="feature-${data.id}" ${data.feature == 1 ? "checked" : ""} >
                     <label onclick="toggleBrands('feature',${data.id})" class="custom-control-label" for="feature-${data.id}">&nbsp;</label>
                  </div>
                  @endif
                            `;
                        }
                    },
                    {
                        data: 'actions', sortable: false, render: function (column, row, data) {
                            return `
                                @if( $permission::chekStatus('brand_edit','admin'))

                            <a href="{{ url('brands/${data.id}/edit') }}" class="badge btn-primary">
                        {{ __('backoffice.edit') }}
                            </a>
@endif
                            @if( $permission::chekStatus('brand_delete','admin'))

                            <button type="button" onclick="openDeleteModal(${data.id})" class="badge btn-primary deleteBrands" data-brandsid="${data.id}">
                        {{ __('backoffice.delete') }}
                            </button>
@endif
                            `;
                        }
                    },


                ],
            });

            brandsTable.ajax.reload();
            $('#brands-table').removeClass('dataTable');
        });


        function openDeleteModal(brandsId) {
            $('#brands_id').val(brandsId);
            $('#brands_delete_form').attr('action', "{{ route('api.delete.brands') }}");
            $('#brands_delete_modal').modal('show');
        }

        $(function () {
            $('#brands_delete_form').submit(function (e) {
                e.preventDefault();
                $.ajax({
                    url: $(this).attr('action'),
                    type: $(this).attr('method'),
                    data: $(this).serialize(),
                    success: function (response) {
                        if (response.IsValid) {
                            toastr.success(response.Message, 'Success');
                            $('#brands_delete_modal').modal('hide');
                            brandsTable.ajax.reload();
                        } else {
                            $('#brands_delete_modal').modal('hide');
                            toastr.error(response.Errors[0], 'Error');
                        }
                    }
                })
                return false;
            });
        });

        $(function () {
            $('#brands-all').change(function () {
                if ($(this).is(':checked')) {
                    $('.brands-row').prop('checked', true).trigger('change');
                } else {
                    $('.brands-row').prop('checked', false).trigger('change');
                }
            });
        });

        $(function () {
            $('#bulk-apply').click(function () {
                let count = 0;
                let brands = [];
                let rows = [];


                $('.brands-row').each(function () {
                    if ($(this).is(':checked')) {
                        brands.push(parseInt($(this).data('brands-id')));
                        rows.push(parseInt($(this).data('row')));
                        count++;
                    }
                });

                if (count == 0) {
                    toastr.error('No row(s) selected', 'Error');
                    return;
                }

                let val = parseInt($('#bulk-action').val());

                if (brands.length > 0 && val > 0) {
                    let enables = [1, 4];
                    let disables = [2, 3, 5];
                    let type = 'delete';
                    let active = true;


                    if ([1, 2].includes(val)) {
                        type = 'active';
                    } else if ([4, 5].includes(val)) {
                        type = 'feature';
                    } else {
                        type = 'delete';
                    }


                    if (enables.includes(val)) {
                        active = true;
                    } else if (disables.includes(val)) {
                        active = false;
                    }

                    toggleAllBrands(type, brands, rows, active);
                }
            });

        });


        function toggleAllBrands(type, ids, rows, active) {
            $.ajax({
                url: "{{ route('api.toggle.brands.all') }}",
                data: {brands: ids, type: type, action: active},
                type: 'POST',
                success: function (response) {
                    if (response.IsValid && response.Message) {
                        brandsTable.ajax.reload();
                        toastr.success(response.Message, 'Success');
                        $('#brands-all').prop('checked', false).trigger('change');
                    }else if (response.Message){
                        toastr.error(response.Message, 'Error');
                    }
                }
            });
        }


        function toggleBrands(type, id, row) {
            $.ajax({
                url: "{{ route('api.toggle.brands') }}",
                data: {id: id, type: type},
                type: 'POST',
                success: function (response) {
                    if (response.IsValid) {
                        if (row != null) {
                            brandsTable.row(row).data(response.Payload).draw();
                        }
                        toastr.success(response.Message, 'Success');
                    }
                }
            });
        }

    </script>
@endsection
