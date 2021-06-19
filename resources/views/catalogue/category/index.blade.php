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
    @include('catalogue.category.delete-modal')
    <link rel="stylesheet" href="https://cdn.datatables.net/responsive/2.2.3/css/responsive.bootstrap.css">

    <div class="row"
    >
        <div class="col-sm-12">
            <div class="greybg1 rounded p-4 mb-3">
                <div class="row">
                    <div class="col-sm-12">
                        <div class="common_title">

                            <h1>
                                {{ __('backoffice.categories') }}
                                @if( $permission::chekStatus('product_type_add','admin'))
                                    <a href="{{ route('category.create')}}"
                                       class="m-b-xs w-auto btn-primary btn-sm pull-right">
                                        {{ __('backoffice.add_category') }}
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
                                    @if( $permission::chekStatus('product_type_edit','admin'))

                                        <div class="btn-toolbar" role="toolbar" aria-label="Toolbar with button groups">
                                            <div class="btn-group mr-2" role="group" aria-label="First group">
                                                <div class="custom-control custom-checkbox header-checkbox">
                                                    <input type="checkbox" class="custom-control-input"
                                                           id="category-all">
                                                    <label class="custom-control-label"
                                                           for="category-all">&nbsp;</label>
                                                </div>
                                                <select class="custom-select" id="bulk-action">
                                                    <option value="">{{ __('backoffice.bulk_actions') }}</option>
                                                    @if($has_dinein_catalogue || $has_dinein_catalogue)
                                                        <option value="1">{{ __('backoffice.enable_dinein') }}</option>
                                                        <option value="2">{{ __('backoffice.disable_dinein') }}</option>
                                                    @endif
                                                    @if($has_pos)
                                                        <option value="3">{{ __('backoffice.enable_pos') }}</option>
                                                        <option value="4">{{ __('backoffice.disable_pos') }}</option>
                                                    @endif
                                                    @if($has_website)
                                                        <option value="5">{{ __('backoffice.enable_website') }}</option>
                                                        <option
                                                            value="6">{{ __('backoffice.disable_website') }}</option>
                                                    @endif
                                                    <option value="7">{{ __('backoffice.activate') }}</option>
                                                    <option value="8">{{ __('backoffice.deactivate') }}</option>
                                                    <option value="9">{{ __('backoffice.delete') }}</option>
                                                </select>
                                                <button type="button" class="btn btn-primary btn-xs btn-bulk ml-3"
                                                        id="bulk-apply">{{ __('backoffice.apply') }}</button>
                                            </div>
                                        </div>
                                    @endif
                                    <hr/>

                                    <table id="category-table" class="table table-striped display responsive nowrap">
                                        <thead>
                                        <tr>
                                            <th style="width:5%">
                                                <div class="expend"></div>
                                            </th>
                                            <th style="width:10%">{{ __('backoffice.name') }} </th>
                                            <th style="width:10%">{{ __('backoffice.parent_category') }} </th>
                                            <th style="width:10%">{{ __('backoffice.sort_order') }} </th>

                                            <th style="width:10%">{{ __('backoffice.total_products') }} </th>
                                            @if($has_pos)
                                                <th style="width:10%">{{ __('backoffice.pos') }}</th>
                                            @endif
                                            @if($has_website)
                                                <th style="width:10%">{{ __('backoffice.website') }} </th>
                                            @endif
                                            @if($has_dinein_catalogue || $has_dinein_catalogue)
                                                <th style="width:10%">{{ __('backoffice.catalogue') }} </th>
                                            @endif

                                            <th style="width:10%">{{ __('backoffice.active') }} </th>
                                            <th style="width:15%">{{ __('backoffice.actions') }} </th>
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
        var categoryTable = null;

        $(document).ready(function () {
            categoryTable = $('#category-table').DataTable({
                "responsive": {
                    "details": {
                        "type": "column"
                    }
                },
                "processing": true,
                "serverSide": true,
                "deferLoading": 0,
                "ajax": {
                    "url": "{{ route('api.fetch.categories') }}",
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
                        <input type="checkbox" class="custom-control-input category-row" data-row="${other.row}" data-category-id="${data.id}" id="check-${data.id}">
                        <label class="custom-control-label" for="check-${data.id}">&nbsp;</label>
                     </div>
                     `
                        }
                    },
                    {
                        data: 'name',
                        render: function (column, row, data) {

                            return `
                        <div class=" no-gutters">
                              <div class="col-auto">
                                 <img src="${data.image}" class="img-fluid" alt="" style="width:50px;height:50px;object-fit:cover;">
                              </div>

                              <div class="col">
                                 <div class="card-block px-2">

                                    <span class="card-title" style="margin-left: -10px;">${data.name}</span>
                                 </div>
                              </div>
                        </div>
                  `;
                        }
                    },
                    {data: 'parent_category', sortable: false},
                    {data: 'sort_order'},
                    {data: 'total_products', sortable: false},

                        @if($has_pos)
                    {
                        data: 'pos', sortable: false, render: function (column, row, data) {
                            return `
  @if( $permission::chekStatus('product_type_edit','admin'))
                            <div class="custom-control custom-switch center-align">
                               <input type="checkbox" class="custom-control-input" id="pos-${data.id}" ${data.pos == 1 ? "checked" : ""} >
                     <label onclick="toggleCategory('pos',${data.id})" class="custom-control-label" for="pos-${data.id}">&nbsp;</label>
                  </div>
                  @endif
                            `;
                        }
                    },
                        @endif
                        @if($has_dinein_catalogue || $has_dinein_catalogue)
                    {
                        data: 'website', sortable: false, render: function (column, row, data) {
                            return `
  @if( $permission::chekStatus('product_type_edit','admin'))
                            <div class="custom-control custom-switch center-align">
                               <input type="checkbox" class="custom-control-input" id="website-${data.id}" ${data.website == 1 ? "checked" : ""} >
                     <label onclick="toggleCategory('website',${data.id})" class="custom-control-label" for="website-${data.id}">&nbsp;</label>
                  </div>
                  @endif
                            `;
                        }
                    },
                        @endif

                        @if($has_website)
                    {
                        data: 'dinein', sortable: false, render: function (column, row, data) {
                            return `
  @if( $permission::chekStatus('product_type_edit','admin'))
                            <div class="custom-control custom-switch center-align">
                               <input type="checkbox" class="custom-control-input" id="dine-${data.id}" ${data.dinein == 1 ? "checked" : ""} >
                     <label onclick="toggleCategory('dinein',${data.id})" class="custom-control-label" for="dine-${data.id}">&nbsp;</label>
                  </div>
                  @endif
                            `;
                        }
                    },
                        @endif

                    {
                        data: 'active', sortable: false, render: function (column, row, data, other) {
                            return `
  @if( $permission::chekStatus('product_type_edit','admin'))
                            <div class="custom-control custom-switch center-align">
                               <input type="checkbox" class="custom-control-input" id="active-${data.id}" ${data.active == 1 ? "checked" : ""} >
                     <label onclick="toggleCategory('active',${data.id},${other.row})" class="custom-control-label" for="active-${data.id}">&nbsp;</label>
                  </div>
                  @endif
                            `;
                        }
                    },
                    {
                        data: 'actions', sortable: false, render: function (column, row, data) {
                            return `
                 @if( $permission::chekStatus('product_type_edit','admin'))
                            <a href="{{ url('catalogue/category/${data.id}/edit') }}" class="badge btn-primary">
{{ __('backoffice.edit') }}
                            </a>
@endif
                            @if( $permission::chekStatus('product_type_delete','admin'))
                            <button type="button" onclick="openDeleteModal(${data.id})" class="badge btn-primary deleteCategory" data-categoryid="${data.id}">
                        {{ __('backoffice.delete') }}
                            </button>
@endif
                            `;
                        }
                    },
                ],
            });

            categoryTable.ajax.reload();
            $('#category-table').removeClass('dataTable');
        });

        function openDeleteModal(categoryId) {
            $('#category_id').val(categoryId);
            $('#category_delete_form').attr('action', "{{ route('api.delete.category') }}");
            $('#category_delete_modal').modal('show');
        }

        $(function () {
            $('#category_delete_form').submit(function (e) {
                e.preventDefault();
                $.ajax({
                    url: $(this).attr('action'),
                    type: $(this).attr('method'),
                    data: $(this).serialize(),
                    success: function (response) {
                        if (response.IsValid && response.Message) {
                            toastr.success(response.Message, 'Success');
                            $('#category_delete_modal').modal('hide');
                            categoryTable.ajax.reload();
                        } else if (response.Message) {
                            $('#category_delete_modal').modal('hide');
                            toastr.error(response.Message, 'Error');
                        } else {
                            $('#category_delete_modal').modal('hide');
                            toastr.error(response.Errors[0], 'Error');
                        }
                    }
                })
                return false;
            });
        });

        $(function () {
            $('#category-all').change(function () {
                if ($(this).is(':checked')) {
                    $('.category-row').prop('checked', true).trigger('change');
                } else {
                    $('.category-row').prop('checked', false).trigger('change');
                }
            });
        });

        $(function () {
            $('#bulk-apply').click(function () {
                let count = 0;
                let categories = [];
                let rows = [];

                $('.category-row').each(function () {
                    if ($(this).is(':checked')) {
                        categories.push(parseInt($(this).data('category-id')));
                        rows.push(parseInt($(this).data('row')));
                        count++;
                    }
                });
                if (count == 0) {
                    toastr.error('No row(s) selected', 'Error');
                    return;
                }

                let val = parseInt($('#bulk-action').val());

                if (categories.length > 0 && val > 0) {
                    let enables = [1, 3, 5, 7];
                    let disables = [2, 4, 6, 8];
                    let type = 'delete';
                    let active = true;

                    if ([1, 2].includes(val)) {
                        type = 'dinein';
                    } else if ([3, 4].includes(val)) {
                        type = 'pos';
                    } else if ([5, 6].includes(val)) {
                        type = 'website';
                    } else if ([7, 8].includes(val)) {
                        type = 'active';
                    } else {
                        type = 'delete';
                    }

                    if (enables.includes(val)) {
                        active = true;
                    } else if (disables.includes(val)) {
                        active = false;
                    }

                    toggleAllCategory(type, categories, rows, active);
                }
            });

        });

        function toggleAllCategory(type, ids, rows, active) {
            $.ajax({
                url: "{{ route('api.toggle.category.all') }}",
                data: {categories: ids, type: type, action: active},
                type: 'POST',
                success: function (response) {
                    if (response.IsValid) {
                        categoryTable.ajax.reload();
                        toastr.success(response.Message, 'Success');
                        $('#category-all').prop('checked', false).trigger('change');
                    } else {
                        toastr.error(response.Message, 'Error');
                        $('#category-all').prop('checked', false).trigger('change');
                    }
                }
            });
        }

        function toggleCategory(type, id, row) {
            $.ajax({
                url: "{{ route('api.toggle.category') }}",
                data: {id: id, type: type},
                type: 'POST',
                success: function (response) {
                    if (response.IsValid) {
                        if (row != null) {
                            categoryTable.row(row).data(response.Payload).draw();
                        }
                        toastr.success(response.Message, 'Success');
                    }
                }
            });
        }


    </script>
@endsection
