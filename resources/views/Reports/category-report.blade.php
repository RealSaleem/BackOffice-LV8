<div class="row">
    <div class="col-sm-3 mb-3">
        <label>{{ __('backoffice.category_name') }}</label>
        <input name="category_name" class="form-control rounded" type="text" placeholder="{{ __('backoffice.category_name') }}" form="main-filters" value="{{ request()->get('category_name') }}">
    </div>
    <div class="col-sm-3 mb-3">
        <label>{{ __('backoffice.no_of_products') }}</label>
        <input name="number_of_product" class="form-control rounded" type="number" placeholder="{{ __('backoffice.no_of_products') }}" form="main-filters" value="{{ request()->get('number_of_product') }}">
    </div>
    <div class="col-sm-3 mb-3">
	  <label>{{ __('backoffice.available_in') }}</label>
	        <select class="form-control select2 inventory-select2" name="categories_available_on" form="main-filters">
	        <option value="" selected>{{ __('backoffice.select') }}</option>
	        <option value="pos_display" {{ request()->get('categories_available_on') == 'pos_display' ? 'selected' : ''}}>{{ __('backoffice.pos') }}</option>
	        <option value="dinein_display" {{ request()->get('categories_available_on') == 'dinein_display' ? 'selected' : ''}}>{{ __('backoffice.dinein') }}</option>
	        <option value="web_display" {{ request()->get('categories_available_on') == 'web_display' ? 'selected' : '' }}>{{ __('backoffice.website') }}</option>
	      </select>
	  </div>
</div>

<div class="row">
	<div class="col-sm-12 dasboard_table">
		<h2 class="mt-3 mb-3">{{ __('backoffice.category') }}</h2>
		<div class="table-responsive">
			<table class="table table-hover" id="category-reports-table">
				<thead>
					<tr>
						<th scope="col">Created date</th>
						<th scope="col">{{ __('backoffice.category_name') }}</th>
						<th scope="col">{{ __('backoffice.parent_category') }}</th>
						<th scope="col">{{ __('backoffice.total_products') }}</th>
						<th scope="col">{{ __('backoffice.pos') }}</th>
						<th scope="col">{{ __('backoffice.dinein') }}</th>
						<th scope="col">{{ __('backoffice.website') }}</th>
					</tr>
				</thead>
				<tbody>
					@foreach($category_reports as $category_report)
					<tr>
						<td>{{ date('d-m-Y', strtotime($category_report['created_at'])) }}</td>
						<td>{{ $category_report['name'] }}</td>
						<td>{{ isset($category_report['parent']) ? $category_report['parent']['name'] : __('backoffice.n/a') }}</td>
						<td>{{ $category_report['products_count'] }}</td>
						<td>{{ $category_report['pos_display'] == 1 ? __('backoffice.yes') : __('backoffice.no') }}</td>
						<td>{{ $category_report['dinein_display'] == 1 ? __('backoffice.yes') : __('backoffice.no') }}</td>
						<td>{{ $category_report['web_display'] == 1 ? __('backoffice.yes') : __('backoffice.no') }}</td>
					</tr>
					@endforeach
				</tbody>
			</table>
		</div>
	</div>
</div>
