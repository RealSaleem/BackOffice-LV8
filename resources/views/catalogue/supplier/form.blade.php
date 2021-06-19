<!-- content -->

@extends('layouts.backoffice')
@section('content')
    <!-- content -->
    <div class="row">
        <form method="POST" action="{{ $model->route }}" id="supplier-form">
            {{csrf_field()}}
            @if($model->edit_mode)
                <input id="id" type="hidden" form="supplier-form" name="id" value="{{$model->supplier['id']}}">
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
                                <a href="{{ route('supplier.index')}}"
                                   class="m-b-xs w-auto btn-primary btn-sm pull-right">
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
                        <div class="col-md-5 rounded pl-4 pr-4 pt-4 mb-1">
                            <i class="" tabindex="0" data-toggle="tooltip" data-original-title="Required"></i>

                            <label for="">{{__('backoffice.name')}}</label>
                            <span style="color: red">*</span>


                            <input type="text" name="name" id="name_check" form="supplier-form"
                                   value="{{old('name',$model->supplier['name'])}}" class="form-control"
                                   placeholder="Name" required>

                        </div>

                        <!-- <div class="text-muted" class "col-md-3 rounded p-4 mb-1"> -->

                        {{--                        <div class="col-md-3 rounded pl-4 pr-4 pt-4 mb-1">--}}
                        <div class="col-md-3 rounded p-4 mb-1">

                            <i class="" tabindex="0" data-toggle="tooltip"></i>

                            <label for="">{{__('backoffice.mobile')}} </label>
                            <span style="color: red">*</span>


                            <input type="text" name="mobile" id="mobile" form="supplier-form"
                                   value="{{old('mobile',$model->supplier['mobile'])}}" class="form-control"
                                   placeholder="Mobile Number" required style="    padding: 5px 53px;">
                            <span id="valid-msg" class="d-none valid-msg">✓ Valid </span>
                            <span id="error-msg" class="d-none error-msg"></span>
                        </div>
                        {{--                <div class="col-md-3 rounded pl-4 pr-4 pt-4 mb-1">--}}
                        {{--					<i class="" tabindex="0" data-toggle="tooltip" ></i>--}}

                        {{--					<label for="">{{__('supplier.phone')}}</label>--}}
                        {{--                    <span style="color: red">*</span>--}}


                        {{--					<input type="text" name="phone" form="supplier-form" value="{{old('phone',$model->supplier['phone'])}}" class="form-control" placeholder="123456789" required>--}}
                        {{--				</div>--}}

                        <div class="col-md-3 rounded pl-4 pr-4 pt-4 mb-1">
                            <i class="" tabindex="0" data-toggle="tooltip"></i>


                            <label for="">{{__('backoffice.email')}}</label>
                            <span style="color: red">*</span>

                            <input type="email" name="email" id="supplier-email" style="padding-right: 80px !important;" form="supplier-form"
                                   value="{{old('email',$model->supplier['email'])}}" class="form-control"
                                   placeholder="example@gmail.com" required>
                        </div>
                        <div class="col-md-6 rounded pl-4 pr-4 pt-4 mb-1">
                            <i class="" tabindex="0" data-toggle="tooltip"></i>

                            <label for="">{{__('backoffice.address')}}</label>
                            <span style="color: red">*</span>

                            <input type="text" id="mymap" name="street" form="supplier-form"
                                   value="{{old('street',isset($model->supplier['street'])? $model->supplier['street'] : NULL )}}"
                                   class="form-control" placeholder="" required>
                        </div>
                        <div class="col-md-6 rounded pl-4 pr-4 pt-md-4 mb-1" style="margin-top: 12px;">
                            <div class="custom-control custom-checkbox col-sm-2 mt-4 mb-5">
                                <input type="checkbox" class="custom-control-input" id="customCheck1"
                                       form="supplier-form" name="active"
                                       value="1" {{ $model->supplier['active'] ? 'checked' : '' }}>
                                <label class="custom-control-label text-secondary"
                                       for="customCheck1">{{__('backoffice.active')}}</label>
                            </div>
                        </div>


                    </div>
                    <div class="row" style="margin-top: -58px;">

                        <div class="col-md-12 rounded pl-4 pr-4 pt-4 mb-1">
                            <div class="form-group">

                                <div id="map" style="width:100%;height:400px"></div>
                                <input form="supplier-form" style="display: none;" class="form-control"
                                       value="{{old('latitude', $model->supplier["latitude"] )}}" name="latitude"
                                       placeholder="Longitude" id="longitude" type="decimal" readonly>
                                <input form="supplier-form" style="display: none;" class="form-control"
                                       value="{{old('longitude', $model->supplier["longitude"]) }}" name="longitude"
                                       placeholder="Latitude" id="latitude" type="decimal" readonly>
                            </div>
                        </div>
                    </div>
                    <div class="container">

                    </div>
                </div>
            </div>
        </div>

        <div class="col-md-12 pb-5" style="padding-left: 22px;">
            <button type="submit" form="supplier-form"
                    class="btn btn-primary ml-4 submit">{{ $model->button_title }}</button>
            <a href="{{ route('supplier.index')}}" class="btn btn-light">{{ __('backoffice.cancel') }}</a>
        </div>

    </div>

@endsection

@section('scripts')
    <script>

        $(function () {
            $('#supplier-form').submit(function () {

                if (!intl.isValidNumber()) {
                    document.getElementById('mobile').focus();
                    $('#error-msg').show();
                    return false;
                }
                if ($(this)[0].checkValidity() && intl.isValidNumber()) {
                    $.ajax({
                        url: $(this).attr('action'),
                        type: $(this).attr('method'),
                        data: $(this).serialize(),
                        success: function (response) {
                            if (response.IsValid) {
                                toastr.success(response.Message, 'Success');
                                setTimeout(() => {
                                    window.location.href = site_url('catalogue/supplier');
                                }, 3000);
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


@include('partials.backoffice.google_map')
@include('partials.backoffice.JSintel-plugin')
@endsection





