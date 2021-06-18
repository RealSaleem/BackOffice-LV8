
<div class="row date-filter4">
    <div class="col-sm-3 mb-3">
        <label>Payments Methods</label>
        <select id="payment_filter" name="payment_method" class="form-control rounded custom-select payment_method-select2" form="main-filters">
            <option value="">{{ __('backoffice.select')  }}</option>
            {{-- <option value="cash">Cash on POS</option> --}}
            @foreach($payment_methods as $payment_method)
                <option value="{{ $payment_method['id'] }}" {{isset($filters['payment_method']) && $filters['payment_method'] == $payment_method['id'] ? 'selected' : ''}}>{{ $payment_method['name'] }}</option>
            @endforeach
        </select>
    </div>
    <div class="col-sm-3 mb-3">
        <label>Payments Status</label>
        <select id="payment_filter" name="payment_status" class="form-control rounded custom-select payment_method-select2" form="main-filters">
            <option value="">{{ __('backoffice.select')  }}</option>
            <option value="Complete" {{isset($filters['payment_status']) && $filters['payment_status'] == 'Complete' ? 'selected' : ''}} >Complete</option>
            <option value="Pending" {{isset($filters['payment_status']) && $filters['payment_status'] == 'Pending' ? 'selected' : ''}} >Pending</option>
        </select>
    </div>
</div>
<div class="row">
    <div class="col-sm-12 dasboard_table">
        <h2 class="mt-3 mb-3">Payments</h2>
        <div class="table-responsive">
            <table class="table table-hover display responsive nowrap" id="payments-table">
                <thead>
                <tr>
                    <th scope="col">{{ __('backoffice.date_and_time') }}</th>
                    <th scope="col">{{ __('backoffice.amount') }}</th>
                    <th scope="col">{{ __('backoffice.payment_method') }}</th>
                    <th scope="col">{{ __('backoffice.status') }}</th>
                </tr>
                </thead>

                <tbody>
                @foreach($payment_reports as $payment_report)
                    @php
                        $payment_method = str_replace(' ', '', $payment_report['payment_method']);
                        $payment_method = strtolower($payment_method);
                    @endphp
                    <tr>
                        <td><div class="expend float-left mr-1"> {{ $payment_report['created_at']  }}</td>
                        <td>{{ Auth::user()->store->default_currency .' '.number_format($payment_report['paid_amount'] ,Auth::user()->store->round_off) }}
                        </td>
                        <td>{{ ucfirst($payment_report['payment_method']) }}</td>
                        @if($payment_method == 'cashondelivery')

                            <td>{{ $payment_report['web_order']['status'] == 'Complete' ? 'Complete' : 'Pending' }}</td>
                        @else
                            <td>{{ $payment_report['payment_status']   }}</td>

                            {{--
                                <td>{{ ($payment_report['is_completed'] == 1) ? 'Confirmed' : 'Failed' }}</td>--}}
                        @endif
                    </tr>
                @endforeach
                </tbody>
            </table>
        </div>
    </div>
</div>
