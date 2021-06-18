@extends('layouts.backoffice')
@section('content')
    <div class="row">
        <div class="col-md-12 mb-2">
            <div class="greybg1 rounded p-4">
                <div class="row">
                    <div class="col-sm-12">
                        <div class="common_title">
                            <h1>@lang('backoffice.plugin_store')</h1>
                        </div>
                    </div>
                </div>

            </div>
        </div>
    </div>
    <div class="">
        <div class="col-12">
            <div class="row plugin-box">
            @foreach($plugins as $plugin)
                <div class="col-12 col-lg-4 mt-lg-4 mb-2">
                    <div class="bg-light border shadow sbox text-lg-center rounded p-4 pd-2">
                        <h1 class="mt-3 " style="padding-top: 3px; padding-bottom: 13px;">{{$plugin->name}} </h1>
                        <p style="    height: 60px;"> {{$plugin->description}}</p>
                        <meta name="csrf-token" content="{{ csrf_token() }}" />
                        <div class="mt-4 text-right text-lg-center" style="font-size: 24px; color: white;">

                            @if($plugin->pluginsStore->where('store_id',Auth::user()->store_id)->count() > 0)
                                <span class="btn btn-success">@lang('backoffice.purchased')</span>
                            @else
                                <button type="submit" class=" btn btn-info PluginPurchase PluginPurchase-{{$plugin->id}}" data-id="{{$plugin->id}}" id="PluginPurchase">@lang('backoffice.bynow')</button>
                                <span style="display: none;" class="btn btn-success pur-{{$plugin->id}}">@lang('backoffice.purchased')</span>
                            @endif
                        </div>
                    </div>
                </div>
            @endforeach
            </div>
        </div>
    </div>
@endsection
@section('scripts')
    <script >
        $(function(){
            $('.PluginPurchase').click(function(){
                $.ajaxSetup({
                    headers: {
                        'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')
                    }
                });
                var plugin_id = $(this).attr('data-id');
                $.ajax({
                    url:'plugin-purchase/',
                    data:{id:plugin_id},
                    success: function(response){
                        if(response.message){
                            toastr.success(response.message, 'Success');
                            $('.pur-'+plugin_id).show();
                            $('.PluginPurchase-'+plugin_id).hide();
                        }
                        if(response.error){
                            toastr.error(response.error, 'Error');
                        }
                    }
                });
            });
        });
    </script>
@endsection
