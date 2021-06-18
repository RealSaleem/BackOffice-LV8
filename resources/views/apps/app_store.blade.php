@extends('layouts.backoffice')
@section('content')
    <div class="row">
        <div class="col-md-12 mb-2">
            <div class="greybg1 rounded p-4 pl-5">
                <h1>
                    @lang('backoffice.app_store')
                </h1>
            </div>
        </div>
    </div>
    <div class="container">
    <div class="row pos-box">
      @if($apps)
        @foreach($apps as $app)
            <div class="col-12 col-md-4 mt-md-4 mb-md-2">
                <div class="bg-light border shadow sbox text-center rounded p-4 pd-2" style="height: 230px !important;">
                  <i class=""></i>
                    <h1 class="mt-3 " style="padding-top: 3px; padding-bottom: 13px;">{{$app->name}} </h1>
                    <p style="height: 60px;"> {{\App\Helpers\Helper::substrwords($app->description,150)}}</p>
                    <meta name="csrf-token" content="{{ csrf_token() }}" />
                    <div class="mt-2 mt-md-4" style="font-size: 24px; color: white;">
                        @if($app->StoreApps->where('store_id',Auth::user()->store_id)->count() > 0)
                            <span class="btn btn-success">@lang('backoffice.purchased')</span>
                       @else
                            <button type="submit" class="btn btn-info AppPurchase AppPurchase-{{$app->id}}" data-id="{{$app->id}}" id="AppPurchase">@lang('backoffice.bynow')</button>
                            <span style="display: none;" class="btn btn-success app-{{$app->id}}">@lang('backoffice.purchased')</span>
                       @endif
                    </div>
                </div>
            </div>
        @endforeach
      @endif
    </div>
  </div>
@endsection
@section('scripts')
<script >
  $(function(){
        $('.AppPurchase').click(function(){
          $.ajaxSetup({
                  headers: {
              'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')
                  }
                  });
             var app_id = $(this).attr('data-id');
          $.ajax({
            url:'app-purchase/',
            data:{id:app_id},
            success: function(response){
              if(response.message){
                toastr.success(response.message, 'Success');
                $('.app-'+app_id).show();
                $('.AppPurchase-'+app_id).hide();
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
