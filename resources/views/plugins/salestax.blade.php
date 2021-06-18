@extends('layouts.backoffice')
@section('content')

<div class="row">
    <div class="col-sm-12">
        <div class="greybg1 rounded p-4 mb-3">
            <div class="row">
                <div class="col-sm-12">
                    <div class="common_title">
                        <h1 class="m-n font-thin h3 text-black">
                            @lang('plugins.salestax')
                            <span class="pull-right">
                                <a href="{{ route('plugins.index')}}" class="m-b-xs w-auto btn-primary btn-sm pull-right">
                                    {{ __('plugins.back') }}
                                </a>
                            </span>
                        </h1>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>
<div class="wrapper-md">
    <div class="panel-heading"></div>
    <div class="row">
        <div class="col-sm-12">
            <div class="panel panel-default">
                <div class="panel-body">
                    <a href="{{ route('edit.salestax') }}" class="btn btn-primary ml-5 mt-5 mb-5" style="margin-bottom: 30px;" m>
                        {{ __('plugins.setting') }}
                    </a>
                </div>
            </div>
        </div>
    </div>
</div>

@endsection
@section('scripts')

@endsection