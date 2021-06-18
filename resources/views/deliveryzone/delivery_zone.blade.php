@extends('layouts.backoffice')

@section('content')
    @include('deliveryzone.delete-model')

<div class="row">
   <div class="col-sm-12 ">
      <div class="greybg1 rounded p-4 mb-3">
         <div class="row">
            <div class="col-sm-12  ">

               <div class="common_title">
                  <h1>
                    {{$outlet->name}}
                    <a href="{{url('outlets') }}" class="m-b-xs w-auto btn-primary btn-sm pull-right">
								Back
					</a>
                  </h1>
               </div>
            </div>
         </div>
      </div>
   </div>
</div>



<div  class="app-content" role="main" >
	<div class="app-content-body ">
		<div class="hbox hbox-auto-xs hbox-auto-sm">
			<div class="col">
				<div class="col-md-4" style="display: none">
                    <div class="panel panel-default mar1">
                        <form action="{{url('area/import')}}" id="upload_excel" method="post" enctype="multipart/form-data">
                            {{csrf_field()}}
                            <div class="form-group">
                                <br>
                                <div class="col-sm-12">
                                    <p class="text-center">
                                        <input type="file" style="padding: 21px 17px;" class="broswebutton broswebutton2" name="imported-file" size="chars" required accept=".xlsx">
                                    </p>
                                </div>
                            </div>
                            <div class="col-md-12 text-center">
                                <span class="upoloadimages">
                                    <button required  type="submit" form="upload_excel" class="btn m-b-xs w-auto btn-success pull-right">Upload</button>
                                    <!-- <a required data-toggle="modal"  data-target="#info_modal" class="btn m-b-xs w-auto btn-success">Upload Excel</a> -->
                                    <!-- <requiredrequired required  type="submit" class="btn m-b-xs w-auto btn-success">Upload Excel</button> -->
                                </span>
                            </div>
                            <div class="clearfix"></div>
                        </form>
                    </div>
                </div>
				<div class="wrapper-md">
					<div class="col-sm-12">
						<div class="panel panel-default">
							<div class="panel-heading">
								@lang('site.Delivery_Zones_list')
								<span class="addremovecash">
									<a href="{{	url('delivery_zones').'/'.$outlet->id.'/add'  }}" class="btn m-b-xs w-auto btn-success pull-right">
										@lang('site.add_zone')
									</a>
								</span>
								<div class="clearFix"></div>
							</div>
							@if(!is_null($zones) && sizeof($zones) == 0)
                           <img src="{{ CustomUrl::asset('img/empty/empty.gif')}}" style="padding-left: 31%; margin-top:2%; width: 60%;" >
                               @endif
                           @if(!is_null($zones) && sizeof($zones) > 0)
							<div class="table-outer">

                                @if(Session::has('done'))
                                    <span class="alert alert-success text-right">{{Session::get('done')}}</span>
                                @endif
								<table class="table table-striped m-b-none">
									<thead>
										<tr>
											<th width="20%">@lang('site.zone_name')</th>
											<th width="15%">@lang('site.Delivery_time')</th>
											<th width="15%">@lang('site.Delivery_price')</th>
											<th width="15%">@lang('site.Min_Order_amount')</th>

											<th width="15%">@lang('site.Coverage')</th>
											<th width="10%">@lang('site.status')</th>
											<th width=""></th>
											<th width="10%">@lang('site.action')</th>
										</tr>
									</thead>
									<tbody>
										@if (count($zones) > 0)

						   				@foreach ($zones as $zone)

										<tr>
											<td>
												{{	$zone->name }}
											</td>
											<td>
												{{	$zone->delivery_time }}
											</td>
											<td>
												{{	$zone->delivery_charges }}
											</td>
											<td>
												{{	$zone->min_order }}
											</td>
											<td>
												@if(isset($zone->zone_coverage) && $zone->zone_coverage  == 'country')
													{{	$zone->counties_count }} : @lang('site.countries')
		                                        @elseif(isset($zone->zone_coverage) && $zone->zone_coverage  == 'cities')
													{{	$zone->cities_count }} : @lang('site.cities')
		                                        @elseif(isset($zone->zone_coverage) && $zone->zone_coverage  == 'area')
													{{	$zone-> areas_count}} : @lang('site.are')
		                                        @else

		                                        @endif
											</td>
											<td>
												@if($zone->is_active == 1)
													@lang('site.active')
												@else
													@lang('site.inctive')
												@endif
											</td>
											<td></td>
											<td>
												<a href="{{	url('delivery_zones').'/'.$outlet->id.'/edit/'.$zone->id  }}">
													@lang('site.edit_zone')
												</a>
												&nbsp;/&nbsp;
{{--												<a href="{{	url('delivery_zones').'/'.$outlet->id.'/delete/'.$zone->id  }}"    >--}}
{{--													@lang('site.delete_zone')--}}
{{--												</a>--}}
                                                <a href="javascript:;" id="delivery_zone_delete" onclick="openDeleteModal({{$outlet->id}})" data-id="{{$zone->id}}">
                                                    @lang('site.delete_zone')
                                                </a>

											</td>
										</tr>
								   		@endforeach
									@endif
									</tbody>
								</table>
							</div>
							@endif
						</div>
					</div>
				</div>
			</div>
		</div>
	</div>
</div>


@endsection

@section('scripts')
    <script>




        function openDeleteModal(outlet_Id){
            var zone_id = $('#delivery_zone_delete').attr("data-id")
            $('#outlet_id').val(outlet_Id);
            $('#zone_id').val(zone_id);
            {{--$('#brands_delete_form').attr('action', "{{ route('api.delete.brands') }}" );--}}
            $('#delivery_zone_delete_form').attr('action', "{{url('delivery_zones')}}" );
            $('#delivery_zone_delete_modal').modal('show');
        }
    </script>
@endsection
