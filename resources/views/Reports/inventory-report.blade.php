<div class="row">
     <div class="col-sm-3 mb-3">
	  <label>{{ __('backoffice.inventory_type') }}</label>
	        <select class="form-control select2 inventory-select2" name="inventory_type" form="main-filters">
	        <option id="inventory_report" value="inventory_report" selected>@lang('backoffice.inventory_reports')</option>
	        <option id="low_stock" value="low_stock" {{isset($filters['inventory_type']) && $filters['inventory_type'] == 'low_stock' ? 'selected' : ''}}>{{ __('backoffice.low_stock') }}</option>
	        <option id="out_of_stock" value="out_of_stock" {{isset($filters['inventory_type']) && $filters['inventory_type'] == 'out_of_stock' ? 'selected' : ''}}>{{ __('backoffice.out_of_stock') }}</option>
	      </select>
	  </div>
	  <div class="col-sm-3 mb-3">
        <label>{{ __('backoffice.stock_range')  }}</label>
        <input type="text" class="form-control" id="stock_range_filter" name="stock_range" placeholder="0 - 100" value="{{isset($filters['stock_range']) ? $filters['stock_range'] : ''}}" form="main-filters">
     </div>
     <div class="col-sm-3 mb-3">
        <label>{{ __('backoffice.price_range')  }}</label>
        <input type="text" class="form-control" id="price_range" placeholder="0 - 500" value="{{isset($filters['price_range_filter']) ? $filters['price_range_filter'] : ''}}" name="price_range_filter" form="main-filters">
     </div>
</div>

<div class="row">
	<div class="col-sm-12 dasboard_table">
		<h2 class="mt-3 mb-3">{{ __('backoffice.inventory') }}</h2>
		<div class="table-responsive">
			<table class="table table-hover" id="inventory-reports-table">
				<thead>
					<tr>
						<th scope="col">{{ __('backoffice.name/sku') }}</th>
						<th scope="col">{{ __('backoffice.supplier_price') }}</th>
						<th scope="col">{{ __('backoffice.retail_price') }}</th>
						<th scope="col">{{ __('backoffice.stock') }}</th>
					</tr>
				</thead>

				<tbody>
					@foreach($inventory_reports['inventories'] as $inventory)
					<tr>
						<td>
							<div class="card-block px-2">
                                <span class="card-title">
                                @if($inventory['name'] == $inventory['attribute_value_1'])
									{{$inventory['attribute_value_1']}}
								@else
									{{ $inventory['name'] }}
								@endif

								@if(($inventory['name'] == $inventory['attribute_value_1']) == false && strlen($inventory['attribute_value_1']) > 1)
									{{ $inventory['attribute_value_1'] }}
								@endif

								@if(strlen($inventory['attribute_value_2']) > 1)
									/ {{ $inventory['attribute_value_2'] }}
								@endif

								@if(strlen($inventory['attribute_value_3']) > 1)
									/ {{ $inventory['attribute_value_3'] }}
								@endif
                                </span>
                                <hr>
                                <span class="card-title badge badge-dark">{{ __('backoffice.sku_sm') }} : {{ $inventory['sku'] }}</span>
                            </div>
						</td>
						<td>{{ Auth::user()->store->default_currency .' '. number_format($inventory['supplier_price'], Auth::user()->store->round_off) }}</td>
						<td>{{ Auth::user()->store->default_currency .' '. number_format($inventory['retail_price'], Auth::user()->store->round_off)}}</td>
						<td>{{ $inventory['stock'] }}</td>
					</tr>
					@endforeach
				</tbody>
				<tfoot>
					<td></td>
					<td></td>
					<td> {{ __('backoffice.total') }}<br>
						{{ Auth::user()->store->default_currency .' '. number_format($inventory_reports['total_stock_amount'], Auth::user()->store->round_off) }}
					</td>
					<td> {{ __('backoffice.total_stock') }} <br>
						{{ $inventory_reports['total_stock'] }}
					</td>

				</tfoot>
			</table>
		</div>
	</div>
</div>
