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
                                <a href="{{ route('plugins.index')}}" class="m-b-xs w-auto btn-primary btn-sm pull-right">
                                    {{ __('backoffice.back') }}
                                </a>
                            </span>
                        </h1>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>
<div class="row">
    <div class="col-sm-12">
        <div class="panel panel-default">
            <div class="panel-body mt-4 mb-2" style="padding-left: 40px;">
                <p>Customer earn {{$loyalty['points']}} reward point for every {{Auth::user()->store->default_currency}} {{$loyalty['cap_amount']}} spent.</p>
                <p>Customer get {{$loyalty['redeem_rate']}} discount for {{$loyalty['points']}} redeemed reward point.</p>
                <p>The maximum discount that a customer can get for an order by redeeming his reward point is {{$loyalty['max_reword_discount']}} .</p>

                <a href="{{ url('loyalty/edit') }}" class="btn btn-primary" style="margin-bottom: 30px;" m>
                    {{ __('backoffice.setting') }}
                </a>
            </div>
        </div>
    </div>
</div>

@endsection
