<div class="row">
    <div class="col-sm-3 mb-3">
        <label>Customer Name</label>
        <input name="customer_name" class="form-control rounded" type="text" placeholder="name" form="main-filters" value="{{ request()->get('customer_name') }}">
    </div>
    <div class="col-sm-3 mb-3">
        <label>{{ __('backoffice.mobile') }}</label>
        <input name="customer_mobile" class="form-control rounded" type="number" placeholder="+123456" form="main-filters" value="{{ request()->get('customer_mobile') }}">
    </div>
    <div class="col-sm-3 mb-3">
        <label>{{ __('backoffice.customer_group') }}</label>
        <select id="customer_group" name="customer_group" class="form-control rounded custom-select customer_group-select2" form="main-filters">
           <option value="">Select</option>
           @foreach($customer_groups as $customer_group)
           <option value="{{ $customer_group->id }}" {{ request()->get('customer_group') == $customer_group->id ? 'selected' : null }}>{{ $customer_group->name }}</option>
           @endforeach
        </select>
    </div>
</div>

<div class="row">
	<div class="col-sm-12 dasboard_table">
		<h2 class="mt-3 mb-3">{{ __('backoffice.customer') }}</h2>
		<div class="table-responsive">
			<table class="table table-hover" id="customer-reports-table">
				<thead>
					<tr>
						<th scope="col">{{ __('backoffice.date') }}</th>
						<th scope="col">{{ __('backoffice.name') }}</th>
						<th scope="col">{{ __('backoffice.email') }}</th>
						<th scope="col">{{ __('backoffice.mobile') }}</th>
						<th scope="col">{{ __('backoffice.group') }}</th>
						<th scope="col">{{ __('backoffice.total_order') }}</th>
						<th scope="col">{{ __('backoffice.total_shopping') }}</th>
					</tr>
				</thead>
				<tbody>
					@foreach($customer_reports['customers'] as $customer_report)
					<tr>
						<td>{{ date('d-m-Y', strtotime($customer_report->created_at)) }}</td>
						<td>{{ $customer_report->name }}</td>
						<td>{{ $customer_report->email != null ? $customer_report->email : '-' }}</td>
						<td>{{ $customer_report->mobile }}</td>
						<td>{{ $customer_report->customer_group != null ? $customer_report->customer_group->name : '-' }}</td>
						<td>{{ $customer_report->order_count }}</td>
						<td>{{ $customer_report->order != null ? PriceHelper::number_format($customer_report->order->sum('total')) : PriceHelper::number_format(0) }}</td>
					</tr>
					@endforeach
				</tbody>
			</table>
		</div>
	</div>
</div>
