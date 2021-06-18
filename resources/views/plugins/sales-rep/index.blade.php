@extends('layouts.backoffice')
@section('content')
<div id="sales_delete_modal" class="modal modal-danger fade" tabindex="-1" role="dialog" aria-labelledby="custom-width-modalLabel" aria-hidden="true" style="display: none;">
    <div class="modal-dialog">
        <div class="modal-content">
            <form id="sales_delete_form" method="POST" class="remove-record-model">
              {{ method_field('delete') }}
              {{ csrf_field() }}

               <div class="modal-header">
                   <button type="button" class="close" data-dismiss="modal" aria-hidden="true">×</button>
                   <h4 class="modal-title text-center" id="custom-width-modalLabel"></h4>
               </div>
               <div class="modal-body">
                   <h4>Are you sure you want to delete this sales representative?</h4>
                   <input type="hidden", name="sales_id" id="sales_id">
               </div>
               <div class="modal-footer">
                   <button type="submit" class="btn btn-danger waves-effect remove-data-from-delete-form">Delete</button>
                   <button type="button" class="btn btn-default waves-effect" data-dismiss="modal">Close</button>
               </div>

             </form>
        </div>
    </div>                        
</div>

<div class="row">
   <div class="col-sm-12">
      <div class="greybg1 rounded p-4 mb-3">
         <div class="row">
            <div class="col-sm-12">
	          <div class="common_title">
	            <h1>
	            	{{__('sales-rep.sales_rep')}} 
					<span class="pull-right">
						<a href="{{ route('plugins.index')}}"class="m-b-xs w-auto btn-primary btn-sm pull-right">{{ __('site.back') }} </a>
						<a href="{{ route('sales-rep.create') }}" class="m-b-xs w-auto btn-primary btn-sm pull-right">{{__('sales-rep.add_sales_rep')}} </a>
					</span>
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
                        <table class="table table-striped m-b-none" id="table">
							<thead>
								<tr>
									<th>{{__('sales-rep.name')}} </th>
									<th>{{__('sales-rep.code')}} </th>
									<th>{{__('sales-rep.id')}} </th>
									<th>{{__('sales-rep.commission')}} </th>
									<th>{{__('sales-rep.phone')}} </th>
									<th>{{__('sales-rep.active')}} </th>
									<th>{{__('sales-rep.actions')}} </th>
								</tr>
							</thead>
							<tbody>
								@foreach($salesrep as $rep)
								<tr>
									<td>{{$rep->name}}</td>
									<td>{{$rep->code}}</td>
									<td>{{$rep->national_id}}</td>
									<td>{{$rep->commission}}</td>
									<td>{{$rep->phone}}</td>
									<td>{{$rep->is_active ? 'Yes' : 'No'}}</td>
									<td>
										<a href="{{route('sales-rep.edit', $rep->id)}}" ><i class="fa fa-pencil"></i></a>&nbsp;&nbsp;
										<a href="{{route('sales-rep.show', $rep->id)}}" ><i class="fa fa-eye"></i></a>&nbsp;&nbsp;
										<a type="buttom" class="deleteRep" data-sales_rep_id="{{$rep->id}}" ><i class="fa fa-trash"></i></a>
									</td>
								</tr>
								@endforeach
							</tbody>
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
	$(document).ready( function () {
	    $('#table').DataTable({
	                serverSide: false,
	                processing: false,
	                responsive: true,
	                columns: [
	                    { name: 'name' },
	                    { name: 'code' },
	                    { name: 'national_id' },
	                    { name: 'commission' },
	                    { name: 'phone' },
	                    { name: 'active' },
	                    { name: 'actions' , orderable: false }      
	                ],
	            })
	        });
 $(function(){
     $('.deleteRep').click(function(){
     var sales_rep_id=$(this).attr('data-sales_rep_id');
     $('#sales_id').val(sales_rep_id);
     $('#sales_delete_form').attr('action',site_url('sales-rep/'+sales_rep_id));
     $('#sales_delete_modal').modal('show');
     });
 });
 $(function(){
    $('#sales_delete_form').submit(function(e){
        e.preventDefault();
         $.ajax({
               url: $(this).attr('action'),
               type: $(this).attr('method'),
               data: $(this).serialize(),
               success: function (response) {
                   console.log(response);
                   if(response){
                    	$('#sales_delete_modal').modal('hide');
                       toastr.success(response.Message,'Success');
                       setTimeout(()=>{
                           window.location.href = site_url('sales-rep');
                       },500);
                   }else{
                    $('#sales_delete_modal').modal('hide');
                    toastr.error( response.Errors[0],'Error');
                   }
             }
          })
         return false;
    });
 });
</script>
@endsection