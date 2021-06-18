
@extends('layouts.backoffice')
@section('content')

<div class="row">
    <div class="col-sm-12">
        <div class="greybg1 rounded p-4 mb-3">
            <div class="row">
                <div class="col-sm-12">
                    <div class="common_title">
                        <h1 class="m-n font-thin h3 text-black">
                            {{ isset($plugin_name) ? $plugin_name : 'Inhouse Print Reciept' }}
                            <span class="pull-right">
                                <a href="{{ route('plugins.index')}}" class="m-b-xs w-auto btn-primary btn-sm pull-r">
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
    <!-- stats -->
    <div class="col-md-12">
        <form action="{{ route('add.inhouse_reciept') }}" method="post">
            <div class="panel panel-body">
                <div class="row">
                    <div class="table-responsive ml-3">
                        <table class="table">
                            <thead>
                                <tr>
                                    <td style="width: 20%;">Total Number of tables in your restaurant</td>
                                    <td style="width: 20%;"><input type="number" name="table_count" value="{{$table_count}}" min="0" /></td>
                                </tr>
                            </thead>
                        </table>
                    </div>
                </div>

                <div class="row">
                    <div class="col-sm-12 col-xs-12 mt-3 mb-3 ml-3">
                        <span class="addremovecash">
                            <button class="btn btn-primary" type="submit">Submit</button>
                            <a href="{{route('plugins.index')}}" class="btn btn-light">Cancel </a>
                        </span>
                    </div>
                </div>
            </div>
        </form>
    </div>
</div>

@endsection
@section('scripts')
@endsection