@extends('layouts.backoffice')
@section('content')
<!-- content -->

@include('catalogue.stockcontrol.delete-modal')


<div class="row">
   <div class="col-sm-12">
      <div class="greybg1 rounded p-4 mb-3">
         <div class="row">
            <div class="col-sm-12">
          <div class="common_title">
            <h1>
               {{ __('stockcontrol.stockcontrol') }}
              <a href="{{ route('stockcontrol.create')}}"class=" m-b-xs w-auto btn-primary btn-sm pull-right" >
                  {{ __('stockcontrol.stock_add') }}
              </a>
              <a href="{{ route('stockcontrol.create')}}"class="m-b-xs w-auto btn-primary btn-sm pull-right">
                  {{ __('stockcontrol.stock_transfer') }}
              </a>
              <a href="{{ route('stockcontrol.create')}}"class="m-b-xs w-auto btn-primary btn-sm pull-right">
                  {{ __('stockcontrol.stock_return') }}
              </a>
              <a href="{{ route('stockcontrol.create')}}"class="m-b-xs w-auto btn-primary btn-sm pull-right">
                  {{ __('stockcontrol.order_Stock') }}
              </a>
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
                        <table class="table table-striped" id="stockcontrol-table">
                           <thead>
                              <tr>
                                 <th>{{ __('stockcontrol.name') }} </th>
                                 <th>{{ __('stockcontrol.order type') }} </th>
                                 <th>{{ __('stockcontrol.date') }} </th>
                                 <th>{{ __('stockcontrol.delivery due') }} </th>
                                 <th>{{ __('stockcontrol.order no') }} </th>
                                 <th>{{ __('stockcontrol.outlet') }} </th>
                                 <th>{{ __('stockcontrol.supplier') }} </th>
                                 <th>{{ __('stockcontrol.status') }} </th>
                                 <th>{{ __('stockcontrol.action') }} </th>
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
var stockcontrol = null;

$(document).ready(function(){
   stockcontrolTable = $('#stockcontrol-table').DataTable({
         "processing": true,
			"serverSide": true,
			"deferLoading" : 0,
            "ajax":{
               "url": "{{ route('api.fetch.stockcontrol') }}",
               "dataType": "json",
               "type": "POST",
               "data": function(d){ 
                     }
				},
            "columns": [
               { data: 'name' },
               { data: 'order_type' },
               { data: 'due_date' },
               { data: 'delivery_due' },
               { data: 'order_number' },
               { data: 'outlet' },
               { data: 'supplier' },
               { data: 'status' },
               { data: 'action' },
            
			],
		});
		
		stockcontrolTable.ajax.reload();
});



</script>
@endsection