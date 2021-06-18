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
               {{ __('plugins.qrcode') }}
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
<div class="row">
  @if($theme != 'foodie')
  <div class="col-sm-12 mt-4 mb-4">
      <div class="visible-print text-center">
      <p style="color: red;">Your current theme is {{ucfirst($theme)}}.<br> You need to set your theme to Foodie to use this.</p>
    </div>
  </div>
  @endif
   <div class="col-sm-12 mt-4 mb-3">
      <div class="visible-print text-center" id="visible-print">
       {!! QrCode::size(200)->generate($url) !!}
      </div>
    </div>
</div>
<div class="row">
  <div class="col-sm-12 mt-4 mb-4">
    <div class="visible-print text-center">
      <a href="" onclick="printDiv('visible-print')" class="m-b-xs w-auto btn-primary btn-lg">
        {{ __('plugins.print') }}
      </a>
    </div>
  </div>
</div>

@endsection
@section('scripts')
<script>
  function printDiv(divName) {
     var printContents        = document.getElementById(divName).innerHTML;
     var originalContents     = document.body.innerHTML;
     document.body.innerHTML  = printContents;
     window.print();
     document.body.innerHTML  = originalContents;
}
</script>
@endsection
