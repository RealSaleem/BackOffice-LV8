@extends('layouts.backoffice')
@section('content')
    @php
        $permission = '\App\Helpers\Helper';
    @endphp
@include('catalogue.item.delete_model')

    <!-- content -->



    <div class="row">
        <div class="col-sm-12">
            <div class="greybg1 rounded p-4 mb-3">
                <div class="row">
                    <div class="col-sm-4">
                        <div class="common_title">
                            {{ __('backoffice.item_title') }}

                        </div>
                    </div>
                    <div class="col-sm-4">

                    </div>
                    <div class="col-sm-4">
                        <div class="common_title">
                            @if( $permission::chekStatus('addonsitem_add','admin'))
                            <a href="{{ route('item.create')}}"class="m-b-xs w-auto btn-primary btn-sm pull-right">
                                {{ __('backoffice.add') }}
                            </a>
                            @endif
                                @if( $permission::chekStatus('addons_add','admin'))
                            <a href="{{ route('addon.index')}}"class="m-b-xs w-auto btn-primary btn-sm pull-right">
                                {{ __('backoffice.back') }}
                            </a>
                                    @endif

                        </div>
                    </div>
                </div>
            </div>
            <div class="bg-light p-4 rounded">
                <div class=" rounded" >
                    <div class="row">
                        <div class="col-md-12">
                            <div class="bg-light rounded">
                                @if( $permission::chekStatus('addonsitem_edit','admin'))
                                <div class=" table-responsive">
                                    <div class="btn-toolbar" role="toolbar" aria-label="Toolbar with button groups">
                                        <div class="btn-group mr-2" role="group" aria-label="First group">
                                            <div class="custom-control custom-checkbox header-checkbox">
                                                <input type="checkbox" class="custom-control-input" id="Item-all">
                                                <label class="custom-control-label" for="Item-all">&nbsp;</label>
                                            </div>
                                            <select class="custom-select" id="bulk-action">
                                                <option value="">{{ __('backoffice.bulk_actions') }}</option>
{{--                                                <option value="1">{{ __('addon.activate') }}</option>--}}
{{--                                                <option value="2">{{ __('addon.deactivate') }}</option>--}}
                                                <option value="3">{{ __('backoffice.delete') }}</option>
                                            </select>
                                            <button type="button" class="btn btn-primary btn-xs btn-bulk ml-3 " id="bulk-apply">{{ __('backoffice.apply') }}</button>
                                        </div>
                                    </div>
                                    @endif
                                    <hr />
                                    <table class="table table-striped" id="item-table">
                                        <thead>
                                        <tr>
                                            <th style="width:5%" >&nbsp;</th>
                                            <th>{{ __('backoffice.name') }} </th>
                                            <th>{{ __('backoffice.price') }} </th>

                                            <th> @if( $permission::chekStatus('addonsitem_edit','admin') || $permission::chekStatus('addonsitem_delete','admin')){{ __('backoffice.action') }} @endif </th>

                                        </tr>
                                        </thead>
                                        <tbody>
                                        @php
                                            $currency = Auth::user()->store->default_currency;
                                        @endphp
                                        @foreach($items as $item)
                                            <tr>
                                                <td>
                                                    <div class="custom-control custom-checkbox">
                                                        <input type="checkbox" class="custom-control-input add_on-row" data-row="${other.row}"  data-add_on-id="{{$item['id']}}" id="check-{{$item['id']}}">
                                                        <label class="custom-control-label" for="check-{{$item['id']}}">&nbsp;</label>
                                                    </div>
                                                </td>
                                                <td>{{$item['en-name']}} </td>
                                                <td>{{(float)$item['price']}} {{$currency}}&nbsp;  </td>
                                                <td>
                                                    @if( $permission::chekStatus('addonsitem_edit','admin'))
                                                    <a href="{{ url('item/'.$item['id'].'/edit') }}" class="badge btn-primary">
                                                        {{ __('backoffice.edit') }}
                                                    </a>
                                                    @endif
                                                        @if( $permission::chekStatus('addonsitem_delete','admin'))
                                                    <button type="button" onclick="openDeleteModal({{$item['id']}})" class="badge btn-primary deleteaddon" data-add_onid="{{$item['id']}}">
                                                        {{ __('backoffice.delete') }}
                                                    </button>
                                                            @endif

                                                </td>
                                            </tr>
                                        @endforeach
                                        </tbody>


                                    </table>
                                    {{--                        <table class="table table-striped" id="add_on-table">--}}
                                    {{--                           <thead>--}}
                                    {{--                              <tr>--}}
                                    {{--                                 <th style="width:5%" >&nbsp;</th>--}}
                                    {{--                                 <th>{{ __('addon.name') }} </th>--}}
                                    {{--                                 <th>{{ __('addon.type') }} </th>--}}
                                    {{--                                 <th>{{ __('addon.active') }} </th>--}}
                                    {{--                                 <th>{{ __('addon.count') }} </th>--}}
                                    {{--                                 <th>{{ __('addon.actions') }} </th>--}}
                                    {{--                              </tr>--}}
                                    {{--                           </thead>--}}
                                    {{--                        </table>--}}
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
        // var add_onTable = null;

        $(document).ready(function(){

            ItemTable =$('#item-table').DataTable({
                "processing": true,
            });



            // add_onTable = $('#add_on-table').DataTable({
            //       "processing": true,
            //       "serverSide": true,
            //       "deferLoading" : 0,
            {{--   "ajax":{--}}
            {{--      "url": "{{ route('api.fetch.addons') }}",--}}
            {{--      "dataType": "json",--}}
            {{--      "type": "POST",--}}
            {{--      "data": function(d){--}}
            {{--            }--}}
            {{--   },--}}
            {{--   "columns": [--}}
            {{--      {--}}
            {{--         sortable : false,--}}
            {{--         'className': 'dt-body-center',--}}
            {{--         'render': function (column,row,data,other){--}}
            {{--            console.log()--}}
            {{--            return`--}}
            {{--            <div class="custom-control custom-checkbox">--}}
            {{--               <input type="checkbox" class="custom-control-input add_on-row" data-row="${other.row}" data-add_on-id="${data.id}" id="check-${data.id}">--}}
            {{--               <label class="custom-control-label" for="check-${data.id}">&nbsp;</label>--}}
            {{--            </div>--}}
            {{--            `--}}
            {{--         }--}}
            {{--      },--}}
            {{--      {--}}
            {{--      data: 'name',--}}
            {{--      render: function(column, row, data) {--}}

            {{--         return `--}}
            {{--               <div class="row no-gutters">--}}
            {{--                     <div class="col">--}}
            {{--                        <div class="card-block px-2">--}}
            {{--                           <span class="card-title">${data.name}</span>--}}
            {{--                        </div>--}}
            {{--                     </div>--}}
            {{--               </div>--}}
            {{--         `;--}}
            {{--      }--}}
            {{--   },--}}
            {{--    {--}}
            {{--      data: 'type',--}}
            {{--      render: function(column, row, data) {--}}

            {{--         return `--}}
            {{--               <div class="row no-gutters">--}}
            {{--                     <div class="col">--}}
            {{--                        <div class="card-block px-2">--}}
            {{--                           <span class="card-title">${data.type}</span>--}}
            {{--                        </div>--}}
            {{--                     </div>--}}
            {{--               </div>--}}
            {{--         `;--}}
            {{--      }--}}
            {{--   },--}}
            {{--      { data: 'active' , render : function(column,row,data){--}}
            {{--         return `--}}
            {{--         <div class="custom-control custom-switch center-align">--}}
            {{--            <input type="checkbox" class="custom-control-input" id="active-${data.id}" ${ data.active == 1 ? "checked" : "" } >--}}
            {{--            <label onclick="toggleAddOn('active',${data.id})" class="custom-control-label" for="active-${data.id}">&nbsp;</label>--}}
            {{--         </div>--}}
            {{--         `;--}}
            {{--      } },--}}
            {{--      { data: 'count' },--}}
            {{--      { data: 'actions' , sortable : false , render : function(column,row,data){--}}
            {{--         return `--}}
            {{--            <a href="{{ url('add_on/${data.id}/edit') }}" class="badge btn-primary">--}}
            {{--               {{ __('addon.edit') }}--}}
            {{--            </a>--}}
            {{--            <button type="button" onclick="openDeleteModal(${data.id})" class="badge btn-primary deleteaddon" data-add_onid="${data.id}">--}}
            {{--               {{ __('addon.delete') }}--}}
            {{--            </button>--}}
            {{--         `;--}}
            {{--      }},--}}


            {{--],--}}


        // add_onTable.ajax.reload();
        // $('#add_on-table').removeClass('dataTable');
        });


        function openDeleteModal(add_onId){
            $('#item_id').val(add_onId);
            $('#items_delete_form').attr('action', "{{ route('delete.item') }}" );
            $('#items_delete_modal').modal('show');
        }

        $(function(){
            $('#items_delete_form').submit(function(e){
                e.preventDefault();
                $.ajax({
                    url: $(this).attr('action'),
                    type: $(this).attr('method'),
                    data: $(this).serialize(),
                    success: function (response) {
                        if(response.IsValid){
                            $('#items_delete_modal').modal('hide');
                            toastr.success(response.Message,'Success');
                           window.location.reload();
                        }else{
                            $('#items_delete_modal').modal('hide');
                            toastr.error( response.Message,'Error');
                        }

                    }
                })
                return false;
            });
        });

        $(function(){
            $('#Item-all').change(function(){
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
                let item = [];
                let rows = [];


                $('.add_on-row').each(function(){
                    if($(this).is(':checked')){
                        item.push(parseInt($(this).data('add_on-id')));
                        rows.push(parseInt($(this).data('row')));
                        count++;
                    }
                });

                if(count == 0){
                    toastr.error('No row(s) selected','Error');
                    return;
                }

                let val = parseInt($('#bulk-action').val());

                if(item.length > 0 && val > 0){
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

                    toggleAllBItem(type,item,rows,active);
                }
            });

        });


        function toggleAllBItem(type,ids,rows,active){
            $.ajax({
                url : "{{ route('toggle.item.all') }}",
                data : { item : ids , type : type , action : active },
                type : 'POST',
                success : function(response){
                    console.log(response);
                    if(response.status = true){
                        toastr.success(response.Message,'Success');
                        $('#Item-all').prop('checked',false).trigger('change');
                        setTimeout(() => {
                            window.location.reload();
                        }, 3000);

                    }else if(response.status = false){
                        toastr.error(response.Message,'error');
                        $('#Item-all').prop('checked',false).trigger('change');
                        setTimeout(() => {
                            window.location.reload();
                        }, 3000);
                    }
                }
            });
        }


        function toggleBrands(type,id,row){
            $.ajax({
                url : "{{ route('api.toggle.brands') }}",
                data : { id : id , type : type },
                type : 'POST',
                success : function(response){
                    if(response.IsValid){
                        if(row != null){
                            ItemTable.row(row).data(response.Payload).draw();
                        }
                        toastr.success(response.Message,'Success');
                    }
                }
            });
        }

    </script>
@endsection
