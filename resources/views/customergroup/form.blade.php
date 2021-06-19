@extends('layouts.backoffice')
@section('content')
<!-- content -->
<div class="row">
  <form method="POST" action="{{ $model->route }}" id="customergroup-form">
    {{csrf_field()}}
    @if($model->edit_mode)
    <input id="id" type="hidden" form="customergroup-form" name="id" value="{{$model->customergroup['id']}}">
    @endif
  </form>
  <div class="col-md-12">
    <div class="row">
      @if ($errors->any())
      <div class="alert alert-danger">
        <ul>
          @foreach ($errors->all() as $error)
          <li>{{ $error }}</li>
          @endforeach
        </ul>
      </div>
      @endif
    </div>
  </div>
  <div class="col-sm-12">
    <div class="greybg1 rounded p-4 mb-3">
      <div class="row">
        <div class="col-sm-12">

          <div class="common_title">
            <h1>
              {{ $model->title }}
              <a href="{{ route('customergroup.index')}}" class="m-b-xs w-auto btn-primary btn-sm pull-right">
                {{ __('backoffice.back') }}
              </a>
            </h1>
          </div>
        </div>
      </div>


    </div>
    <div class="bg-light p-4 rounded">
      <div class="rounded">
        <div class="row">
          <div class="col-md-3 rounded p-4 mb-1">
            <i class="" tabindex="0" data-toggle="tooltip" data-original-title="Required"></i>

            <label for="">{{ __('backoffice.name') }}</label>
            <span style="color: red">*</span>



            <input type="text" name="name" form="customergroup-form" value="{{old('name',$model->customergroup['name'])}}" class="form-control" placeholder="{{ __('backoffice.name') }}" required>
          </div>


          <div class="col-md-12 pb-5" style="padding-left: 22px;">
            <button type="submit" form="customergroup-form" class="btn btn-primary">{{ $model->button_title }}</button>
            <a href="{{ route('customergroup.index')}}" class="btn btn-light">{{ __('backoffice.cancel') }}</a>
          </div>

        </div>

        @endsection
        @section('scripts')
        <script>
          $(function() {
            $('#customergroup-form').submit(function() {


              var name = $('#title').val();
              $('#name').val(name);

              if ($(this)[0].checkValidity()) {
                $.ajax({
                  url: $(this).attr('action'),
                  type: $(this).attr('method'),
                  data: $(this).serialize(),
                  success: function(response) {
                    if (response.IsValid) {
                      toastr.success(response.Message, 'Success');
                      setTimeout(() => {
                        window.location.href = site_url('customers/customergroup');
                      }, 1000);
                    } else {
                        if (Object.keys(response.Errors).length > 1) {
                        response.Errors.map((error) => {
                          toastr.error(error, 'Error');
                        });
                      } else {
                        toastr.error(response.Errors[0], 'Error')
                      }
                    }
                  }
                });
              }
              return false;
            });
          });
        </script>
        @endsection
