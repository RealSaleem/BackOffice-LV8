@extends('layouts.backoffice')
@section('content')
    @php
        $permission = '\App\Helpers\Helper';
    @endphp
    <!-- content -->
    @include('catalogue.addon.delete-modal')



    <div class="row">
        <div class="col-sm-12">
            <div class="greybg1 rounded p-4 mb-3">
                <div class="row">
                    <div class="col-sm-12">
                        <div class="common_title">
                            <h1>
                                {{ __('backoffice.addons') }}
                                @if( $permission::chekStatus('addons_add','admin'))
                                    <a href="{{ route('addon.create')}}"class="m-b-xs w-auto btn-primary btn-sm pull-right">
                                        {{ __('backoffice.add_addon') }}
                                    </a>
                                @endif
                                {{--              <a href="{{ route('import.addon.index')}}" class="m-b-xs w-auto btn-primary btn-sm pull-right">--}}
                                {{--                {{ __('Import Addons') }}--}}
                                {{--            </a>--}}
                                @if( $permission::chekStatus('addonsitem_list','admin'))
                                    <a href="{{ route('item.index')}}" class="m-b-xs w-auto btn-primary btn-sm pull-right">
                                        {{ __('backoffice.item_title') }}
                                    </a>
                                @endif
                            </h1>
                        </div>
                    </div>
                </div>
            </div>
            <div class="bg-light p-4 rounded">
                <div class=" rounded" >
                    <div class="row">
                        <div class="col-md-12">
                            <div class="bg-light rounded">
                                <div class=" table-responsive">
                                    @if( $permission::chekStatus('addonsitem_edit','admin'))
                                        <div class="btn-toolbar" role="toolbar" aria-label="Toolbar with button groups">
                                            <div class="btn-group mr-2" role="group" aria-label="First group">
                                                <div class="custom-control custom-checkbox header-checkbox">
                                                    <input type="checkbox" class="custom-control-input" id="addon-all">
                                                    <label class="custom-control-label" for="addon-all">&nbsp;</label>
                                                </div>
                                                <select class="custom-select" id="bulk-action">
                                                    <option value="">{{ __('backoffice.bulk_actions') }}</option>
                                                    <option value="1">{{ __('backoffice.activate') }}</option>
                                                    <option value="2">{{ __('backoffice.deactivate') }}</option>
                                                    <option value="3">{{ __('backoffice.delete') }}</option>
                                                </select>
                                                <button type="button" class="btn btn-primary btn-xs btn-bulk ml-3 " id="bulk-apply">{{ __('backoffice.apply') }}</button>
                                            </div>
                                        </div>
                                    @endif

                                    <hr />
                                    <table class="table table-striped" id="add_on-table">
                                        <thead>
                                        <tr>
                                            <th style="width:5%" >&nbsp;</th>
                                            <th>{{ __('backoffice.name') }} </th>
                                            <th>{{ __('backoffice.type') }} </th>
                                            <th>{{ __('backoffice.active') }} </th>
                                            <th>{{ __('backoffice.count') }} </th>
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
    <script type="text/javascript">
        var add_onTable = null;

        $(document).ready(function(){
            add_onTable = $('#add_on-table').DataTable({
                "processing": true,
                "serverSide": true,
                "deferLoading" : 0,
                "ajax":{
                    "url": "{{ route('api.fetch.addons') }}",
                    "dataType": "json",
                    "type": "POST",
                    "data": function(d){
                    }
                },
                "columns": [
                    {
                        sortable : false,
                        'className': 'dt-body-center',
                        'render': function (column,row,data,other){
                            return`
                     <div class="custom-control custom-checkbox">
                        <input type="checkbox" class="custom-control-input add_on-row" data-row="${other.row}" data-add_on-id="${data.id}" id="check-${data.id}">
                        <label class="custom-control-label" for="check-${data.id}">&nbsp;</label>
                     </div>
                     `
                        }
                    },
                    {
                        data: 'name',
                        render: function(column, row, data) {
                            return `
                     <div class="row no-gutters">
                           <div class="col">
                              <div class="card-block px-2">
                                 <span class="card-title">${data.name}</span>
                              </div>
                           </div>
                     </div>
                  `;
                        }
                    },
                    {
                        data: 'type',
                        render: function(column, row, data) {
                            return `
                        <div class="row no-gutters">
                              <div class="col">
                                 <div class="card-block px-2">
                                    <span class="card-title">
                                    ${ data.type == 'add_on' ? "{{ __('backoffice.addon') }}" : "{{ __('backoffice.option') }}" }
                                    </span>
                                 </div>
                              </div>
                        </div>
                  `;
                        }
                    },
                    { data: 'active' , render : function(column,row,data){
                            return `
 @if( $permission::chekStatus('addons_edit','admin'))
                            <div class="custom-control custom-switch center-align">
                               <input type="checkbox" class="custom-control-input" id="active-${data.id}" ${ data.active == 1 ? "checked" : "" } >
                     <label onclick="toggleAddOn('active',${data.id})" class="custom-control-label" for="active-${data.id}">&nbsp;</label>
                  </div>
                  @endif
                            `;
                        } },
                    { data: 'count' },
                    { data: 'actions' , sortable : false , render : function(column,row,data){
                            return `
                   @if( $permission::chekStatus('addons_edit','admin'))
                            <a href="{{ url('catalogue/addon/${data.identifier}/edit') }}" class="badge btn-primary">
                        {{ __('backoffice.edit_addon_title') }}
                            </a>
@endif
                            @if( $permission::chekStatus('addons_delete','admin'))
                            <button type="button" onclick="openDeleteModal(${data.id})" class="badge btn-primary deleteaddon" data-add_onid="${data.id}">
                        {{ __('backoffice.delete') }}
                            </button>
@endif
                            `;
                        }},


                ],
            });

            add_onTable.ajax.reload();
            $('#add_on-table').removeClass('dataTable');
        });


        function openDeleteModal(add_onId){
            $('#addon_id').val(add_onId);
            $('#addon_delete_form').attr('action', "{{ route('api.delete.addon') }}" );
            $('#addon_delete_modal').modal('show');
        }

        $(function(){
            $('#addon_delete_form').submit(function(e){
                e.preventDefault();
                $.ajax({
                    url: $(this).attr('action'),
                    type: $(this).attr('method'),
                    data: $(this).serialize(),
                    success: function (response) {
                        if(response.IsValid){
                            toastr.success(response.Message,'Success');
                            $('#addon_delete_modal').modal('hide');
                            add_onTable.ajax.reload();
                        }else{
                            $('#addon_delete_modal').modal('hide');
                            toastr.error( response.Errors[0],'Error');
                        }
                    }
                })
                return false;
            });
        });

        $(function(){
            $('#addon-all').change(function(){
                if($(this).is(':checked')){
                    $('.add_on-row').prop('checked',true).trigger('change');
                }else{
                    $('.add_on-row').prop('checked',false).trigger('change');
                }
            });
        });

        $(function(){
            $('#bulk-apply').click(function(){
                let count = 0;
                let add_on = [];
                let rows = [];


                $('.add_on-row').each(function(){
                    if($(this).is(':checked')){
                        add_on.push(parseInt($(this).data('add_on-id')));
                        rows.push(parseInt($(this).data('row')));
                        count++;
                    }
                });

                if(count == 0){
                    toastr.error('No row(s) selected','Error');
                    return;
                }

                let val = parseInt($('#bulk-action').val());

                if(add_on.length > 0 && val > 0){
                    let enables = [1];
                    let disables = [2,3];
                    let type = 'delete';
                    let active = true;




                    if([1,2].includes(val)){
                        type = 'active';
                    }else{
                        type = 'delete';
                    }

                    if(enables.includes(val)){
                        active = true;
                    }else if(disables.includes(val)){
                        active = false;
                    }

                    toggleAllAddons(type,add_on,rows,active);
                }
            });

        });


        function toggleAllAddons(type,ids,rows,active){
            $.ajax({
                url : "{{ route('api.toggle.addon.all') }}",
                data : { addon : ids , type : type , action : active },
                type : 'POST',
                success : function(response){
                    if(response.IsValid== true){
                        add_onTable.ajax.reload();
                        toastr.success(response.Message,'Success');
                        $('#addon-all').prop('checked',false).trigger('change');
                    }else{
                        toastr.error(response.Message,'Error');
                        $('#addon-all').prop('checked',false).trigger('change');
                    }
                }
            });
        }




        function toggleAddOn(type,id,row){
            $.ajax({
                url : "{{ route('api.toggle.addon') }}",
                data : { id : id , type : type },
                type : 'POST',
                success : function(response){
                    console.log(response);
                    if(response.IsValid){
                        toastr.success(response.Message,'Success');
                        if(row != null){
                            add_onTable.row(row).data(response.Payload).draw();
                        }

                    }
                }
            });
        }

    </script>
@endsection
