<!-- content -->

@extends('layouts.backoffice')
@section('content')

    <!-- content -->
    <div class="row">
        <form method="POST" action="{{ $model->route }}" id="customer-form">
            {{csrf_field()}}
            @if($model->edit_mode)
                <input id="id" type="hidden" form="customer-form" name="id" value="{{$model->customer['id']}}">
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
                                <a href="{{ route('customer.index')}}"
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
                        <div class="col-md-3 rounded p-4 mb-1">
                            <i class="" tabindex="0" data-toggle="tooltip" data-original-title="Required"></i>

                            <label for="">{{__('backoffice.name')}}</label>
                            <span style="color: red">*</span>


                            <input type="text" name="name" id="name_check" form="customer-form"
                                   value="{{old('name',$model->customer['name'])}}" class="form-control"
                                   placeholder="Name" required>
                        </div>
                        <div class="col-md-3 rounded p-4 mb-1">

                            <label for="">{{__('backoffice.gender')}}</label>
                            <span style="color: red">*</span>

                            <select name="sex" id="gender" class="form-control m-b select2" form="customer-form"
                                    required>
                                <option value="M" {{ $model->customer['sex'] == 'M' ? "selected" : "" }}>Male</option>
                                <option value="F" {{ $model->customer['sex'] == 'F'  ? "selected" : ""}}>Female</option>
                            </select>
                        </div>
                        <div class="col-md-3 rounded p-4 mb-1">

                            <label for="">{{__('backoffice.group')}}</label>
                            <span style="color: red">*</span>
                            <select name="customer_group_id" id="group" form="customer-form"
                                    class="form-control m-b select2" required>
                                @foreach($model->customergroups as $customergroup)
                                    <option
                                        value="{{ $customergroup->id }}" {{ $model->customer['customer_group_id'] == $customergroup->id  ? "selected" : ""}}>
                                        {{ $customergroup->name }}
                                    </option>
                                @endforeach
                            </select>
                        </div>

                        <!-- <div class="text-muted" class "col-md-3 rounded p-4 mb-1"> -->

                        <div class="col-md-3 rounded p-4 mb-1">

                            <i class="" tabindex="0" data-toggle="tooltip"></i>

                            <label for="">{{__('backoffice.mobile')}} </label>
                            <span style="color: red">*</span>


                            <input type="text" name="mobile" id="mobile" form="customer-form"
                                   value="{{old('mobile',$model->customer['phone'])}}" class="form-control"
                                   placeholder="Mobile Number" required style="    padding: 5px 53px;">
                            <span id="valid-msg" class="d-none valid-msg">✓ Valid </span>
                            <span id="error-msg" class="d-none error-msg"></span>
                        </div>

                        <div class="col-md-3 rounded p-4 mb-1">
                            <i class="" tabindex="0" data-toggle="tooltip"></i>


                            <label for="">{{__('backoffice.email')}}</label>
                            <span style="color: red">*</span>

                            <input type="email" id="customer-email" name="email" form="customer-form"
                                   value="{{old('email',$model->customer['email'])}}" class="form-control"
                                   placeholder="example@gmail.com" required>
                        </div>


                        <div class="col-md-6 rounded p-4 mb-1">
                            <i class="" tabindex="0" data-toggle="tooltip"></i>

                            <label for="">{{__('customer.address')}}</label>

                            <input type="text" id="mymap" name="address" form="customer-form"
                                   value="{{old('sreet',isset($model->customer['street']) ? $model->customer['street'] : NULL )}}"
                                   class="form-control" placeholder="">
                        </div>
                    </div>
                    <div class="row" style="margin-top: -46px;">
                        <div class="col-md-12 rounded p-4 mb-1">
                            <div class="form-group">

                                <div id="map" style="width:100%;height:400px"></div>
                                <input form="customer-form" style="display: none;" class="form-control"
                                       value="{{old('latitude', $model->customer["latitude"] )}}" name="longitude"
                                       placeholder="Longitude" id="longitude" type="decimal" readonly>
                                <input form="customer-form" style="display: none;" class="form-control"
                                       value="{{old('longitude', $model->customer["longitude"]) }}" name="latitude"
                                       placeholder="Latitude" id="latitude" type="decimal" readonly>
                            </div>
                        </div>
                    </div>
                </div>
            </div>

            <div class="col-md-12 pb-5" style="padding-left: 22px;">
                <button type="submit" form="customer-form"
                        class="btn btn-primary ml-2 submit">{{ $model->button_title }}</button>
                <a href="{{ route('customer.index')}}" class="btn btn-light">{{ __('backoffice.cancel') }}</a>
            </div>

        </div>

        @endsection

        @section('scripts')

            <script>


                $(function () {
                    $('#customer-form').submit(function () {

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
                                            window.location.href = site_url('customers/customer');

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
