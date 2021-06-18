@extends('layouts.backoffice')
@section('content')
    <style>
        .select2-container--default .select2-selection--single {
            height: 45px;
        }
    </style>

    <form method="POST" action="{{ $model->route }}" id="user-form">
        {{csrf_field()}}
        @if($model->edit_mode)
            <input id="id" type="hidden" form="user-form" name="id" value="{{$model->user['id']}}">

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
    <div class="container-fluid">
        <div class="row">
            <div class="col-sm-12 pl-0 pr-0">
                <div class="greybg1 rounded p-4 mb-3">
                    <div class="row">
                        <div class="col-sm-12">
                            <div class="common_title">
                                <h1>
                                    {{ $model->title }}
                                    <a href="{{route('users.index')}}"
                                       class="m-b-xs w-auto btn-primary btn-sm pull-right">
                                        {{ __('backoffice.back') }}
                                    </a>
                                </h1>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>

    <div class="bg-light p-4 rounded">
        <div class="rounded">
            <div class="row">
                <div class="col-lg-8 mb-3">
                    <div class="row">
                        <div class="col-md-4 mb-3 rounded ">
                            <i class="" tabindex="0" data-toggle="tooltip" data-original-title="Required"></i>
                            <label for="">{{__('backoffice.name')}}</label>
                            <span style="color: red">*</span>
                            <input type="text" name="name" id="name_check" form="user-form"
                                   value="{{old('name',$model->user['name'])}}" class="form-control" placeholder="Name"
                                   required>
                        </div>
                        <div class="col-md-4 mb-3 rounded ">
                            <i class="" tabindex="0" data-toggle="tooltip" data-original-title="Required"></i>
                            <label for="">{{__('backoffice.email')}}</label>
                            <span style="color: red">*</span>
                            <input type="email" name="email" id="email-check" form="user-form"
                                   value="{{old('email',$model->user['email'])}}" class="form-control"
                                   placeholder="Email" required>
                        </div>
                        <div class="col-md-4 mb-3 rounded ">
                            <i class="" tabindex="0" data-toggle="tooltip" data-original-title="Required"></i>
                            <label for="">{{__('backoffice.mobile')}}</label>
                            <span style="color: red">*</span><br>
                            <input type="text" name="mobile" id="mobile" form="user-form"
                                   value="{{old('mobile',$model->user['mobile'])}}" class="form-control"
                                   placeholder="Mobile" required style="    padding: 5px 53px;">

                            <span>
                               <span id="valid-msg" class="d-none valid-msg">✓ Valid </span>
                          <span id="error-msg" class="d-none error-msg"></span>

                          </span>

                        </div>
                    </div>
                    <div class="row mt-3 ">
                        <div class="col-md-4 mb-3 rounded  ">
                            <label for="">{{ __('backoffice.roles') }}</label>
                            <span style="color: red">*</span>
                            <select class="custom-select select2 form-control" name="role" form="user-form" required>
                                <option value="">Select</option>
                                @foreach($role as $rol)
                                    @if($rol->name != 'admin')
                                        @php
                                            if($model->edit_mode){
                                              $selected = ($rol->id == $model->user['role_id']) ? 'selected': '';
                                            } else {
                                              $selected = null;
                                            }
                                        @endphp

                                        <option value="{{old('role', $rol->id)}}" {{ $selected }}>
                                            {{ $rol->display_name }}
                                        </option>
                                    @endif
                                @endforeach
                            </select>
                        </div>
                        <div class="col-md-4 mb-3 rounded  ">
                            <label>{{ __('backoffice.outlet')  }}</label>
                            <select form="user-form" id="outlet_ids" class="form-control rounded custom-select select2"
                                    multiple name="outlets[]" required>

                                @if(isset($outlets) && sizeof($outlets))
                                    @foreach($outlets as $outlet)
                                        <option
                                            value="{{$outlet->id}}" {{in_array($outlet->id, $model->user['user_outlet_ids']) ? 'selected' : null }}>{{$outlet->name}}</option>
                                    @endforeach
                                @endif
                            </select>
                        </div>
                        <div class="col-md-4 mb-3 rounded  ">
                            @if($model->edit_mode)
                                <div class="col-md-12 mt-3 text-right">
                                    <a href="#" class="update_password">
                                        <i class="fa fa-caret-down"
                                           aria-hidden="true"></i>&nbsp; {{__('backoffice.change_password')}}</a>

                                </div>
                            @endif
                        </div>
                    </div>
                    <div class="row mt-3">

                        <div class="col-md-6 rounded    @if($model->edit_mode) password @endif ">
                            <i class="" tabindex="0" data-toggle="tooltip" data-original-title="Required"></i>
                            <label for="">{{__('backoffice.password')}}</label>
                            @if(!$model->edit_mode)
                                <span style="color: red">*</span>
                            @endif
                            <input type="password"
                                   name="password"
                                   id="password"
                                   form="user-form"
                                   value="{{old('password')}}"
                                   class="form-control" placeholder="********"
                                   @if(!$model->edit_mode)
                                   required
                                @endif
                            >
                        </div>
                        <div class="col-md-6 rounded  @if($model->edit_mode)con_password @endif">
                            <i class="" tabindex="0" data-toggle="tooltip" data-original-title="Required"></i>
                            <label for="">{{__('backoffice.re_password')}}</label>
                            @if(!$model->edit_mode)
                                <span style="color: red">*</span>
                            @endif
                            <input type="password"
                                   name="confirm_password"
                                   form="user-form"
                                   id="con_password"
                                   value="{{old('password')}}"
                                   class="form-control "
                                   placeholder="********"
                                   @if(!$model->edit_mode)
                                   required
                                @endif
                            >
                            <span id='message'></span>
                        </div>

                    </div>
                </div>
                <div class="col-lg-4 mb-3">
                    <div class="row ">
                        <div class="col-md-12 rounded  ">

                            <label>
                                <i class="" tabindex="0" data-toggle="popover" data-trigger="focus"
                                   title="User Iage"
                                   data-content="Select your product Images. Image size should be (120 x 120)"></i>
                                {{ __('backoffice.add_image') }}
                            </label>
                            <form name="user_image" action="/file-upload" class="dropzone"
                                  id="my-awesome-dropzone" enctype="multipart/form-data">
                                <div class="fallback">
                                    <input name="file" type="file" style="display: none;">
                                </div>
                            </form>


                            <div class="hidden">
                                <div id="hidden-images">
                                    @if(isset($model->user['user_image']))
                                        <input type="hidden" form="user-form" name="image[name]"
                                               value="{{ $model->user['user_image']['name'] }}"/>
                                        <input type="hidden" form="user-form" name="image[url]"
                                               value="{{ $model->user['user_image']['url'] }}"/>
                                        <input type="hidden" form="user-form" name="image[size]"
                                               value="{{ $model->user['user_image']['size'] }}"/>
                                    @endif
                                </div>
                            </div>

                        </div>
                    </div>
                </div>
            </div>
        </div>
        <div class="col-md-12 pb-5 ml-md-2">
            <button type="submit" form="user-form"
                    class="btn btn-primary submit">{{ $model->button_title }}</button>
            <a href="{{ route('users.index')}}" class="btn btn-light">{{ __('backoffice.cancel') }}</a>
        </div>
    </div>


@endsection
@section('scripts')


    <script type="text/javascript">
        var image_upload_path = '{{ route("api.upload.user.image")  }}';
        var form_id = 'user-form';
        var p_images = '';
        p_images = JSON.parse('{!!  json_encode([$model->user["user_image"]]) !!}');
        let maxFiles = 1;

    </script>

    <script>

        $('.password').hide();
        $('.con_password').hide();

        $('.update_password').on('click', function () {
            $('.password').toggle();
            $('.con_password').toggle();
        });


        $(function () {
            $('#outlet_ids').select2();

            $('#user-form').submit(function () {

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
                                    window.location.href = site_url('users');
                                }, 1000);
                            } else {
                                if (Object.keys(response.Errors).length > 1) {
                                    response.Errors.map((error) => {
                                        toastr.error(error, 'Error');
                                    });
                                } else {
                                    toastr.error(response.Errors, 'Error')
                                }
                            }
                        }
                    });
                }
                return false;
            });
        });
    </script>



    @include('partials.backoffice.JSintel-plugin')
@endsection
