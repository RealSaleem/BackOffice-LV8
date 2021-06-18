@extends('layouts.backoffice')
@section('content')
    <style>
        .register-edit {
            background-color: dodgerblue;
            padding: 5px 15px 4px 17px;
            color: white;
            border-radius: 5px;
        }

        .taction {
            width: 50px;
            text-align: center;
        }

    </style>

    <div class="container-fluid">
        <div class="row">
            <div class="col-sm-12 pl-0 pr-0">
                <div class="greybg1 rounded p-4 mb-3">
                    <div class="row">
                        <div class="col-sm-12">
                            <div class="common_title">
                                <h1>

                                    @if($model->edit_mode)
                                        {{__('backoffice.update_outlet')}}
                                    @else
                                        {{__('backoffice.add_outlet')}}
                                    @endif


                                    <a href="{{ route('outlets.index') }}"
                                       class="m-b-xs w-auto btn-primary btn-sm pull-right">
                                        {{ __('backoffice.back') }}
                                    </a>
                                </h1>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
            <div class="col-sm-8 pl-0 pr-0">
                <div class="card bg-light mt-3  rounded  border-0">
                    <div class="card-body pb-0 pt-0">

                        @if($model->edit_mode)
                            <form method="POST" action="{{ route('api.update.outlets') }}" id="outlets-form">
                                <input id="id" type="hidden" form="outlets-form" name="id"
                                       value="{{$model->outlet['id']}}">
                                {{csrf_field()}}
                                @else
                                    <form method="POST" action="{{ route('api.add.outlets') }}" id="outlets-form">
                                        {{csrf_field()}}
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
                                            @if(Session::has('success'))
                                            @endif
                                        </div>
                                    </div>


                    </div>
                    <div class="bg-light p-4 rounded">
                        <div class="rounded">
                            <div class="row">
                                <div class="col-md-4 rounded p-4 mb-1">

                                    <i class="" tabindex="0" data-toggle="tooltip" data-original-title="Required"></i>
                                    <label for="">{{ __('backoffice.outlet_name') }}</label>
                                    <span style="color: red">*</span>
                                    <input type="text" name="name" form="outlets-form" id="name_check"
                                           value="{{old('name', isset($model->outlet['name']) ?  $model->outlet['name'] : NULL)}}"
                                           class="form-control outletName" placeholder="Name" required>

                                </div>
                                <div class="col-md-4 rounded p-4 mb-1">
                                    <i class="" tabindex="0" data-toggle="tooltip"></i>
                                    <label for="">{{ __('backoffice.outlet_phone') }}</label>
                                    <span style="color: red">*</span>
                                    <input type="text" name="phone" id="mobile" form="outlets-form"
                                           value="{{old('phone', isset($model->outlet['phone']) ? $model->outlet['phone'] : NULL)}}"
                                           class="form-control" placeholder="123456789" required style="    padding: 5px 53px;">
                                    <span id="valid-msg" class="d-none valid-msg">✓ Valid </span>
                                    <span id="error-msg" class="d-none error-msg"></span>
                                </div>
                                <div class="col-md-4 rounded p-4 mb-1">
                                    <i class="" tabindex="0" data-toggle="tooltip"></i>
                                    <label for="">{{ __('backoffice.outlet_email') }}</label>
                                    <span style="color: red">*</span>
                                    <input type="email" name="email" form="outlets-form"
                                           id="outlets-email"
                                           value="{{old('email',isset($model->outlet['email']) ? $model->outlet['email'] : NULL)}}"
                                           class="form-control" placeholder="example@gmail.com" required>
                                </div>
                            </div>
                            <div class="row" style="    display: flow-root;">
                            <span class=" toogle-btn" style="float: right; margin-right: 20px; margin-bottom: 10px;">more <i
                                    class="fa fa-chevron-down mb-4" aria-hidden="true"></i></span>
                                </span>
                            </div>

                            <div class="rounded ml-0 social-toogle mt-4">
                                <div class="row">
                                    <div class="col-md-4 rounded p-4 " style="margin-top: -41px;">
                                        <i class="" tabindex="0" data-toggle="tooltip"></i>
                                        <label for="">{{ __('backoffice.outlet_facebook') }}</label>
                                        <input type="text" name="facebook" form="outlets-form"
                                               value="{{old('facebook', isset($model->outlet['facebook']) ?  $model->outlet['facebook'] : NULL)}}"
                                               class="form-control" placeholder="facebook.com/@YourId">
                                    </div>

                                    <div class="col-md-4 rounded p-4 mb-1" style="margin-top: -41px;">
                                        <i class="" tabindex="0" data-toggle="tooltip"></i>
                                        <label for="">{{ __('backoffice.outlet_twitter') }}</label>
                                        <input type="text" name="twitter" form="outlets-form"
                                               value="{{old('twitter',isset($model->outlet['twitter']) ?  $model->outlet['twitter'] : NULL)}}"
                                               class="form-control" placeholder="@YourId">
                                    </div>


                                    <div class="col-md-4 rounded p-4 mb-1" style="margin-top: -41px;">
                                        <i class="" tabindex="0" data-toggle="tooltip"></i>
                                        <label for="">{{ __('backoffice.outlet_instagram') }}</label>
                                        <input type="text" name="instagram" form="outlets-form"
                                               value="{{old('instagram',isset($model->outlet['instagram']) ?  $model->outlet['instagram'] : NULL)}}"
                                               class="form-control" placeholder="@YourId">
                                    </div>


                                    <div class="col-md-4 rounded p-4 mb-1" style="margin-top: -41px;">
                                        <i class="" tabindex="0" data-toggle="tooltip"></i>
                                        <label for="">{{ __('backoffice.outlet_snapchat') }}</label>
                                        <input type="text" name="snapchat" form="outlets-form"
                                               value="{{old('snapchat',isset($model->outlet['snap_chat']) ? $model->outlet['snap_chat'] : NULL)}}"
                                               class="form-control" placeholder="@YourId">
                                    </div>
                                </div>
                            </div>

                            <div class="card mb-4 pt-2 pl-2 pb-2 pr-2">
                                <div class="row">
                                    <div class="col-md-4 p-3 rounded ">
                                        <div class="custom-control custom-checkbox ">
                                            <input id="customCheck008" name="is_active" form="outlets-form"
                                                   type="checkbox"
                                                   class="custom-control-input"
                                                   value="1" @if(isset($model->outlet['id'])? $model->outlet['id'] !== 0 : NULL) {{ $model->outlet['is_active'] == 1 ? 'checked' : ''}}  @endif >
                                            <label class="custom-control-label ml-2"
                                                   for="customCheck008">{{ __('backoffice.active') }}</label>
                                        </div>
                                    </div>
                                    <div class=" col-md-4 p-3 rounded ">
                                        <div class="custom-control custom-checkbox ">
                                            <input id="customCheck006" name="allow_online_order" form="outlets-form"
                                                   type="checkbox" class="custom-control-input" value="1"
                                            @if(isset($model->outlet['id'])? $model->outlet['id'] !== 0 : null) {{$model->outlet['allow_online_order'] == 1 ? 'checked' : ''}} @endif
                                            >
                                            <label class="custom-control-label ml-2"
                                                   for="customCheck006">{{ __('backoffice.allow_online_orders') }}</label>
                                        </div>
                                    </div>
                                    <div class="col-md-4 p-3 rounded ">
                                        <div class="custom-control custom-checkbox">
                                            <input id="customCheck007" name="enable_zone" form="outlets-form"
                                                   type="checkbox"
                                                   class="custom-control-input"
                                                   value="1" @if(isset($model->outlet['id'])? $model->outlet['id'] !== 0 : NULL) {{$model->outlet['enable_zone'] == 1 ? 'checked' : ''}} @endif>
                                            <label class="custom-control-label ml-2"
                                                   for="customCheck007">{{ __('backoffice.enable_delivery_zone') }}</label>
                                        </div>
                                    </div>
                                    <div class="col-md-4 p-3 rounded ">
                                        <div class="custom-control custom-checkbox">
                                            <input id="customCheck009" name="allow_pickup" form="outlets-form"
                                                   type="checkbox"
                                                   class="custom-control-input"
                                                   value="1" @if(isset($model->outlet['id'])? $model->outlet['id'] !== 0 : NULL) {{$model->outlet['pickup'] == 1 ? 'checked' : ''}} @endif >
                                            <label class="custom-control-label ml-2"
                                                   for="customCheck009">{{ __('backoffice.allow_pickup') }}</label>
                                        </div>
                                    </div>
                                    <div class="col-md-4 p-3 rounded ">
                                        <div class="custom-control custom-checkbox">
                                            <input id="customCheck0010" name="enable_business_hours" form="outlets-form"
                                                   type="checkbox" class="custom-control-input"
                                                   value="1" @if(isset($model->outlet['id'])? $model->outlet['id'] !== 0 : NULL) {{$model->outlet['enable_business_hours'] == 1 ? 'checked' : ''}} @endif>
                                            <label class="custom-control-label ml-2"
                                                   for="customCheck0010">{{ __('backoffice.enable_Business_hours') }}</label>
                                        </div>
                                    </div>
                                </div>
                            </div>

                            <div class="row">
                                <div class="col-md-6 rounded p-4 mb-1" style="margin-top: -34px;">
                                    <i class="" tabindex="0" data-toggle="tooltip"></i>
                                    <label for="">{{ __('backoffice.outlet_address') }}</label>
                                    <span style="color: red">*</span>
                                    <input type="text" placeholder="Outlet Address" id="mymap" name="address"
                                           form="outlets-form"
                                           value="{{old('address',isset($model->outlet['street_1']) ? $model->outlet['street_1'] : NULL)}}"
                                           class="form-control" placeholder="" required>
                                </div>
                                <div class="col-md-6 rounded p-4 mb-1" style="margin-top: -34px;">
                                    <label class="">{{ __('backoffice.min_order_amount') }}</label>
                                    <div>
                                        <input class="form-control" form="outlets-form" type="text"
                                               name="min_order_amount"
                                               value="{{old('min_order_amount', isset($model->outlet['min_order_value']) ? $model->outlet['min_order_value'] : NULL)}}"
                                               placeholder="000.00">
                                    </div>
                                </div>
                            </div>


                            <div class="row" style="margin-top: -48px;">
                                <div class="col-md-6 rounded p-4 mb-1">
                                    <div class="form-group">
                                        <div id="map" style="width:100%;height:224px;"></div>
                                        <input form="outlets-form" style="display: none;"   class="form-control"
                                               value="{{old('latitude', $model->outlet["latitude"] )}}" name="longitude"
                                               placeholder="Longitude" id="longitude" type="text" >
                                        <input form="outlets-form" style="display: none;"     class="form-control"
                                               value="{{old('longitude', $model->outlet["longitude"]) }}"
                                               name="latitude" placeholder="Latitude" id="latitude" type="text"
                                               >
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>

            <div class="col-sm-4 ">
                <div class="rounded p-4 mt-3">
                    <div class="card p-3 rounded ">
                        <form name="outlet_images" action="/file-upload" class="dropzone"
                              id="my-awesome-dropzone" enctype="multipart/form-data">
                            <div class="fallback">
                                <input name="file" type="file" style="display: none;">
                            </div>
                        </form>


                        <div class="hidden">
                            <div id="hidden-images">

                                @if(isset($model->outlet['images']))

                                    <input type="hidden" form="outlets-form" name="images[0][name]"
                                           value="{{ $model->outlet['name'] }}"/>
                                    <input type="hidden" form="outlets-form" name="images[0][path]"
                                           value="{{ $model->outlet['images'] }}"/>
                                    <input type="hidden" form="outlets-form" name="images[0][size]" value=""/>


                                @endif
                            </div>
                        </div>
                    </div>

                    <div class="card p-3 rounded mt-4">
                        <label class="ml-2">{{ __('backoffice.registers') }}</label>

                        @error('backoffice')
                        <div class="error">{{ $message }}</div>
                        @enderror
                        <input class="form-control outlet_register_id" type="hidden" name="outlet_id"
                               value="{{isset($model->outlet['id']) ? $model->outlet['id'] : NULL}}">
                        <input class="form-control outlet_register_name" type="text" name="outlet_register_name"
                               placeholder="Add Register Name">
                        <div class="btn btn-info add-row">Add Register</div>

                    </div>
                    <div class=" rounded ml-0" style="margin-top: -41px;">
                        <div class="row">
                            <div class="col-md-8 rounded p-4 mb-1">
                            </div>
                        </div>
                        <table class="table table-bordered">
                            <thead>
                            <tr class="  @if($model->edit_mode)   @else register-head @endif ">
                                <th class="text-center" style="background-color: #EFEFE9;">Register Name</th>
                                <th class="text-center taction" style="background-color: #EFEFE9;">Action</th>
                            </tr>

                            </thead>
                            <tbody>
                            @if(Route::is('outlets.edit'))
                                @php
                                    $register = $model->outlet['registers'];
                                    $oldregisters=  [];
                                @endphp
                                @if($register && $register !== NULL)
                                    @foreach($register as $reg)
                                        @php
                                            array_push($oldregisters, $reg['name']);
                                        @endphp
                                        @if($reg !== NULL)
                                            <tr>
                                                <td>{{isset($reg['name'] )? $reg['name'] :NULL }} <input
                                                        type='hidden'
                                                        form='outlets-form'
                                                        class='form-control'
                                                        id='check-{{$reg['id']}}'
                                                        value="{{isset($reg['name']) ? $reg['name'] : NULL}}">

                                                </td>
                                                <td>
                                                    <div class='register-edit '
                                                         data-name='{{isset($reg['name']) ? $reg['name'] : NULL}}'
                                                         onclick='edit({{$reg['id']}})'>
                                                        Edit
                                                    </div>
                                                </td>
                                            </tr>
                                        @endif
                                    @endforeach
                                @endif
                            @endif
                            @php
                                $oldregisters=   isset( $oldregisters ) ? implode(",", $oldregisters) : NULL;
                            @endphp
                            </tbody>
                            <tbody class="newreg">
                            </tbody>
                        </table>
                    </div>
                    {{--@dd($model->outlet["ImageArray"])--}}

                </div>
            </div>

        </div>
        <div class="col-md-12 pb-5 ml-2" style="padding-left: 22px;">
            @if($model->edit_mode)
                <button type="submit" form="outlets-form"
                        class="btn btn-primary submit">{{ __('backoffice.update') }}</button>
            @else
                <button type="submit" form="outlets-form"
                        class="btn btn-primary submit">{{ __('backoffice.add') }}</button>
            @endif
            <a href="{{ route('outlets.index')}}" class="btn btn-light">{{ __('backoffice.cancel') }}</a>
        </div>
    </div>




@include('outlets.register_edit_model')
@endsection

@section('scripts')


{{--    <script src="{{ CustomUrl::asset('js/intel-plugin.js') }}" type="text/javascript"></script>--}}
    <script type="text/javascript">
        var image_upload_path = '{{ route("api.upload.outlet.image")}}';
        var form_id = 'outlets-form';
        var p_images = '';
        p_images = JSON.parse('{!! json_encode(isset($model->outlet["ImageArray"][0]['url'])? $model->outlet["ImageArray"] : null ) !!}');
        let maxFiles = 1;

        $('.social-toogle').hide();
        $('.toogle-btn').on('click', function () {
            $('.social-toogle').toggle();
        });

        $('.register-head').hide();

        var add_count = 0;
        var RegisterNameArray = [];
        let oldregisters = [];
        oldregisters = "{{$oldregisters}}".split(",")
        console.log(oldregisters);
        $(".add-row").click(function () {
            $('.register-head').show();
            // var seq = Math.floor(Math.random() * 10);

            var RegName = $(".outlet_register_name").val();
            if ($(".outlet_register_name").val().trim() != "" && !RegisterNameArray.includes(RegName) && !oldregisters.includes(RegName)) {
                RegisterNameArray.push($(".outlet_register_name").val().trim());
                printRegister(RegisterNameArray)
                $(".outlet_register_name").val("")
            } else {
                if ($(".outlet_register_name").val().trim() != "") {
                    toastr.warning('Name Already Exist');
                    // alert('Name Already Exist')
                } else {
                    toastr.warning('Enter the name');
                    // alert('Enter the name')
                }

                $(".outlet_register_name").val("")
            }


        });




        function printRegister(nl) {
            $('.newreg').html('');
            if (nl.length > 0) {
                nl.map(n =>
                    $('.newreg').append("<tr id='reg_row" + n + "' data-name=" + n + "><td>" + n + "<input type='hidden'  form='outlets-form'  name='outlet_register_name[]' class='form-control register_name' value='" + n + "'><input type='hidden' value='" + n + "' class='id'>    </td><td>   <div class='register-edit check remove_register'  style='padding:5px 15px 4px 24px;   background-color:coral !Important;'  id='edit-" + n + "'  data-name='" + n + "'> X</div></td></tr>")
                );
            }
            $(".remove_register").on('click', function () {
                let n = $(this).data('name');
                RegisterNameArray = RegisterNameArray.filter(function (item) {
                    return item != n
                })
                printRegister(RegisterNameArray)
                $('#reg_row-' + n).hide();


            })
        }


        function edit($id) {
            let OldRegName = $('#check-' + $id).val();
            let id = $id;
            var str = "<input type='hidden' name='id' value='" + id + "' ><input class='form-control outlet_register_old_name' type='text' onchange='RegisterCheck()'  name='outlet_register_old_name' value='" + OldRegName + "' >"
            $("#register_edit_modal .modal-body").html(str);
            $('#register_edit_modal ').modal('show')
        }

        function RegisterCheck() {

            let NewName = $(".outlet_register_old_name").val();

            // toastr.warning(NewName);

            if ($(".outlet_register_old_name").val() != "" && !RegisterNameArray.includes(NewName) && !oldregisters.includes(NewName)) {
                $('.update_register').show();
            } else {
                if ($(".outlet_register_old_name").val() != "") {
                    $('.update_register').hide();
                    toastr.warning('Name already exist');
                    // alert('Name Already Exist')
                } else {
                    toastr.warning('Enter the name');
                    // alert('Enter the name')
                }
            }
        }


        function updateRegister() {

            $.ajax({
                url: $(this).attr('action'),
                type: $(this).attr('method'),
                data: $(this).serialize(),
                success: function (response) {
                    if (response.true) {
                        toastr.success('\Lang::get("outlets.outlet_register_updated_successfully")');
                        setTimeout(() => {
                            window.location.reload();
                        }, 3000);
                    } else {
                        if (response.Errors.lenght > 0) {
                            response.Errors.map((error) => {
                                toastr.error(error, 'Error');
                            });
                        } else {
                            toastr.error(response.Errors[0], 'Error');
                        }
                    }
                },
            });
        }

           $(function () {
            $('#outlets-form').submit(function () {


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
                            // console.log(response.Message);
                            if (response.IsValid) {
                                toastr.success(response.Message, 'Success');
                                setTimeout(() => {
                                    window.location.href = site_url('outlets');
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




<script type="text/javascript">
    function myMap() {
        let latitude = $('#latitude').val();
        let longitude = $('#longitude').val();

        var map = new google.maps.Map(document.getElementById('map'), {
            center: map,
            zoom: 13
        });







        // var map = new google.maps.Map(document.getElementById('map'), {
        //     var myCenter = new google.maps.LatLng(latitude, longitude);
        //
        //     center:{
        //         lat: latitude,
        //         lng: longitude
        //     },
        //     zoom: 13
        // });



        var input = document.getElementById('mymap');
        autocomplete = new google.maps.places.Autocomplete(input);
        searchBox = new google.maps.places.SearchBox(input);

        autocomplete.setFields(['address_components']);
        autocomplete.addListener('places_changed', function() {
            var place = autocomplete.getPlace();
            let result = place.address_components.map(a => a.long_name);

        });

        // Listen for the event fired when the user selects a prediction and retrieve
        // more details for that place.
        searchBox.addListener('places_changed', function() {
            var places = searchBox.getPlaces();
            console.log(places[0].geometry.location.lat());
            $('#longitude').val(places[0].geometry.location.lng().toFixed(6));
            $('#latitude').val(places[0].geometry.location.lat().toFixed(6));

            if (places.length == 0) {
                return;
            }

            var myCenter = new google.maps.LatLng(places[0].geometry.location.lat(), places[0].geometry.location.lng());
            var mapOptions = {
                center: myCenter,
                zoom: 15
            };

            var mapCanvas = document.getElementById("map");
            var map2 = new google.maps.Map(mapCanvas, mapOptions);
            var marker = new google.maps.Marker({
                position: myCenter
            });
            marker.setMap(map2);

            $(document).ready(function() {
                // click on map and set you marker to that position
                google.maps.event.addListener(map2, 'click', function(event) {
                    marker.setPosition(event.latLng);
                    getAddress(event.latLng.lat(), event.latLng.lng());
                    $('#longitude').val(event.latLng.lng().toFixed(6)).trigger("change");
                    $('#latitude').val(event.latLng.lat().toFixed(6)).trigger("change");
                    // alert( "Latitude: "+event.latLng.lat()+" "+", longitude: "+event.latLng.lng() );
                });
            });

        });
    }

    function getAddress(lat, long) {
        $.ajax({
            url: 'https://maps.googleapis.com/maps/api/geocode/json?latlng=' + lat + ',' + long + '&sensor=false&key=AIzaSyANRj-jLIgydGCb1M2dG7WjMsVVpC8xjjs',
            success: function(data) {
                $('#mymap').val(data.results[0].formatted_address);

            }
        });
    }

    function getLocation() {
        // console.log(navigator.geolocation);
        navigator.geolocation.getCurrentPosition(function(position) {
            latitude = position.coords.latitude;
            longitude = position.coords.longitude;

            getAddress(latitude, longitude);

            var myCenter = new google.maps.LatLng(latitude, longitude);
            var mapOptions = {
                center: myCenter,
                zoom: 13
            };

            var mapCanvas = document.getElementById("map");
            var map = new google.maps.Map(mapCanvas, mapOptions);
            var marker = new google.maps.Marker({
                position: myCenter
            });

            marker.setMap(map);

            $(document).ready(function() {
                // click on map and set you marker to that position
                google.maps.event.addListener(map, 'click', function(event) {
                    marker.setPosition(event.latLng);
                    getAddress(event.latLng.lat(), event.latLng.lng());

                    $('#longitude').val(event.latLng.lng().toFixed(6)).trigger("change");
                    $('#latitude').val(event.latLng.lat().toFixed(6)).trigger("change");

                });
            });
        });

        if (navigator.geolocation) {
            navigator.geolocation.getCurrentPosition(showPosition);
        } else {
            alert("Geolocation is not supported by this browser.");
        }
    }

    function showPosition(position) {
        getAddress(position.coords.latitude, position.coords.longitude);

    }

    getLocation();

</script>
<script src="https://maps.googleapis.com/maps/api/js?key=AIzaSyANRj-jLIgydGCb1M2dG7WjMsVVpC8xjjs&callback=myMap&libraries=places"></script>


    @include('outlets.script')
@include('partials.backoffice.JSintel-plugin')
@endsection
