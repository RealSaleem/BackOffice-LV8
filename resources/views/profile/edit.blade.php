<!-- content -->
@extends('layouts.backoffice')
@section('content')
    <!-- content -->

    <div class="row">
        <form method="POST" action="{{ route('api.user.save') }}" id="user-form">
            {{csrf_field()}}
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
                                {{__('backoffice.user_profile')}}
                                <a href="{{route('profile_index')}}"
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
                        <div class="col-md-8">
                            <div class="row">
                                <div class="col-md-8 rounded mb-1">
                                    <i class="" tabindex="0" data-toggle="tooltip" data-original-title="Required"></i>
                                    <label for="">Name</label>
                                    <span style="color: red">*</span>

                                    <i class="fa fa-info-circle"></i>
                                    <input type="text" name="name" id="name_check" form="user-form"
                                           value="{{$user['user']->name}}" class="form-control" placeholder="Name"
                                           required>
                                    <input type="hidden" name="id" form="user-form" value="{{$user['user']->id}}"/>
                                </div>

                                <div class="col-md-4 rounded mb-1">
                                    <i class="" tabindex="0" data-toggle="tooltip"></i>
                                    <label for="">Mobile </label>
                                    <span style="color: red">*</span>
                                    <i class="fa fa-info-circle"></i>
                                    <input type="text" name="mobile" id="mobile" form="user-form"
                                           value="{{$user['user']->mobile}}" class="form-control"
                                           placeholder="Mobile Number" style="    padding: 5px 53px;"
                                           required>
                                    <span id="valid-msg" class="hide valid-msg" hidden>✓ Valid </span>
                                    <span id="error-msg" class="hide error-msg"></span>
                                </div>
                            </div>


                            <div class="row password">
                                {{--                @if(request()->segment(count(request()->segments())) !== 'edit')--}}
                                <div class="col-md-6 rounded p-4 mb-1">
                                    <i class="" tabindex="0" data-toggle="tooltip" data-original-title="Required"></i>
                                    <label for="">{{__('backoffice.password')}}</label>

                                    <input type="password" form="user-form" id="pass" name="password"
                                           placeholder="********"
                                           class="form-control">
                                </div>

                                <div class="col-md-6 rounded p-4">
                                    <i class="" tabindex="0" data-toggle="tooltip" data-original-title="Required"></i>
                                    <label for="">{{__('backoffice.re_password')}}</label>

                                    <input type="password" id="confirm_password" onchange="checkPassword()"
                                           name="confirm_password" form="user-form" value="" class="form-control"
                                           placeholder="********">
                                </div>
                            </div>
                        </div>

                        <div class="col-md-4">
                            <label>{{ __('backoffice.image') }}</label>
                            <form action="/file-upload" name="images" class="dropzone" id="my-awesome-dropzone" required
                                  style="min-height: 87%;"></form>
                            <div class="d-none">
                                <div id="hidden-images">
                                    <input type="hidden" form="user-form" name="images[0][name]"
                                           value="{{ $user['image'][0]['name'] }}"/>
                                    <input type="hidden" form="user-form" name="images[0][path]"
                                           value="{{ $user['image'][0]['url'] }}"/>
                                    <input type="hidden" form="user-form" name="images[0][size]" value="0"/>
                                </div>
                            </div>
                        </div>
                    </div>

                    <div class="col-md-12 pb-5 mt-5" style="padding-left:0px;">
                        <button type="submit" form="user-form"
                                class="btn btn-primary submit">{{__('backoffice.save')}}</button>
                        <a href="{{ route('profile_index')}}" class="btn btn-light">{{ __('backoffice.cancel') }}</a>
                    </div>


                    @endsection

                    @section('scripts')


                        <script type="text/javascript">
                            var image_upload_path = "{{ route('api.upload.user.image') }}";
                            var form_id = 'user-form';
                            var p_images = '';
                            p_images = JSON.parse('{!! json_encode($user['image']) !!}');
                            let maxFiles = 1;


                            $(function () {
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
                                                        window.location.href = site_url('profile');
                                                    }, 3000);
                                                } else {
                                                    if (response.Errors.lenght > 0) {
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
                            $(document).ready(function () {


                                $('[data-toggle="tooltip"]').tooltip();
                                $('#user-outlets').select2();
                            });

                        </script>


    @include('partials.backoffice.JSintel-plugin')
@endsection
