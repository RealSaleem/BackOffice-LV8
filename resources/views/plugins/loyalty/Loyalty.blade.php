@extends('layouts.backoffice')
@section('content')
<!-- content -->

<div class="row">
    <div class="col-sm-12">
        <div class="greybg1 rounded p-4 mb-3">
            <div class="row">
                <div class="col-sm-12">
                    <div class="common_title">
                        <h1>
                            {{ __('backoffice.loyalty') }}
                            <span class="pull-right">
                                <a href="{{ url('loyalty')}}" class="m-b-xs w-auto btn-primary btn-sm pull-right">
                                    {{ __('backoffice.back') }}
                                </a>
                            </span>
                        </h1>
                    </div>
                </div>
            </div>
        </div>
        <div class="wrapper-md" style="padding-left: 10px;">
            <div class="row">
                <div class="col-sm-12">
                    <div class="panel panel-default">
                        <form action="{{ route('loyalty.update') }}" method="POST" id="loyality-form">
                            {{csrf_field()}}
                            <div class="col-md-12">
                                <div class="row">
                                    <div class="col-md-3 mt-4">
                                        <label>Amount</label>
                                        <span style="color: red;">*</span>
                                    </div>
                                    <div class="col-md-8 mt-3">
                                        <div class="row">
                                            <div class="col-md-3">
                                                <input name="cap_amount" type="number" class="form-control rounded" placeholder="0.00" value="{{ old('cap_amount', $loyalty->cap_amount) }}" required />
                                            </div>
                                            Enter the amount
                                        </div>
                                    </div>
                                </div>
                                <div class="row">
                                    <div class="col-md-3 mt-4">
                                        <label>Points</label>
                                        <span style="color: red;">*</span>
                                    </div>
                                    <div class="col-md-8 mt-3">
                                        <div class="row">
                                            <div class="col-md-3">
                                                <input name="points" type="number" class="form-control rounded" placeholder="0.00" value="{{ old('cap_amount', $loyalty->points) }}" required />
                                            </div>
                                            Enter the number of points for above amount
                                        </div>
                                    </div>
                                </div>
                                <div class="row">
                                    <div class="col-md-3 mt-4">
                                        <label>Redeem Rate</label>
                                        <span style="color: red;">*</span>
                                    </div>
                                    <div class="col-md-8 mt-3">
                                        <div class="row">
                                            <div class="col-md-3">
                                                <input name="redeem_rate" type="number" class="form-control rounded" placeholder="0.00" value="{{ old('cap_amount', $loyalty->redeem_rate) }}" required />
                                            </div>
                                            Redeem rate for above points
                                        </div>
                                    </div>
                                </div>
                                <div class="row">
                                    <div class="col-md-3 mt-4">
                                        <label>Max Reword Discount</label>
                                        <span style="color: red;">*</span>
                                    </div>
                                    <div class="col-md-8 mt-3">
                                        <div class="row">
                                            <div class="col-md-3">
                                                <input name="max_reword_discount" type="number" class="form-control rounded" placeholder="0.00" value="{{ old('cap_amount', $loyalty->max_reword_discount) }}" required />
                                            </div>
                                            Enter the MAX reward value for above points
                                        </div>
                                    </div>
                                </div>
                                <div class="row">
                                    <div class="col-md-3 mt-4">
                                        <label>Expiry</label>
                                    </div>
                                    <div class="col-md-8 mt-3">
                                        <div class="row">
                                            <div class="col-md-3">
                                                <input name="expire_after" type="number" class="form-control rounded" placeholder="0.00" value="{{ old('cap_amount', $loyalty->expire_after) }}"  />
                                            </div>
                                            Set the expiry of customers points
                                        </div>
                                    </div>
                                </div>
                                <div class="col-md-12 pb-5 mt-5" style="padding-left: 2px;">
                                    <button type="submit" form="loyality-form" class="btn btn-primary">{{ __('backoffice.save') }}</button>
                                    <a href="{{ url('loyalty')}}" class="btn btn-light">{{ __('backoffice.cancel') }}</a>
                                </div>
                            </div>
                        </form>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>

@endsection
@section('scripts')
<script>
$(function () {
    $("#loyality-form").submit(function () {
        if ($(this)[0].checkValidity()) {
            $.ajax({
                url: $(this).attr("action"),
                type: $(this).attr("method"),
                data: $(this).serialize(),
                success: function (response) {
                    if (response.IsValid) {
                        toastr.success(response.Message, "Success");
                        setTimeout(() => {
                            window.location.href = site_url("loyalty");
                        }, 3000);
                    } else {
                        if (response.Errors.lenght > 0) {
                            response.Errors.map((error) => {
                                toastr.error(error, "Error");
                            });
                        } else {
                            toastr.error(response.Errors[0], "Error");
                        }
                    }
                },
            });
        }
        return false;
    });
});

</script>
@endsection
