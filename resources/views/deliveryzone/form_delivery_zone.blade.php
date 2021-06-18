@extends('layouts.backoffice')

@section('content')
    <style type="text/css">
        .select2-selection__rendered {
            white-space: unset !important;
        }

        .float {
            width: 100%;
        }

        .float1 {
            margin-right: -12% !important;
        }
    </style>


    <div class="row">
        <div class="col-sm-12">
            <div class="greybg1 rounded p-4">
                <div class="row">
                    <!-- Inner Div -->
                    <div class="col-sm-12">
                        <div class="common_title">
                            <h1>
                                <a href="{{url('delivery_zones').'/'.$outlet->id}}">{{$outlet->name}} </a>
                                / {{$zone_name}}
                                <a href="{{url('delivery_zones').'/'.$outlet->id}}"
                                   class="m-b-xs w-auto btn-primary btn-sm pull-right">Back </a>
                            </h1>
                        </div>
                    </div>
                </div>
                <p style="text-align: center;">Fields marked with <span style="color: red;">*</span> are mandatory to
                    fill</p>
                <form action="{{ $route  }}" id="delivery_zones_form" method="POST">
                    {{ csrf_field() }}
                    <div class="card bg-light mt-3 pl-3 pr-3  rounded  border-0">
                        <div class="row">
                            <div class="card-body">
                                <div class="row">
                                    <div class="col-sm-12">
                                        <div class="row">
                                            <div class="col-md-6">
                                                <div class="col-md-12 form-group">
                                                    <label>@lang('site.zone_name') </label>
                                                    <span style="color: red">*</span>
                                                    <input name="name" class="form-control rounded" form="delivery_zones_form"
                                                           placeholder=" @lang('site.zone_name')" type="text"
                                                           value="{{$zone->name}}" required>
                                                </div>

                                                <div class="col-md-12 form-group">
                                                    <label>@lang('site.Delivery_time') </label>
                                                    <span style="color: red">*</span>
                                                    <input name="delivery_time" class="form-control rounded" form="delivery_zones_form"
                                                           placeholder=" @lang('site.30_min')" type="text"
                                                           value="{{$zone->delivery_time}}" required>
                                                </div>
                                                <div class="col-md-12 form-group">
                                                    <label>@lang('site.Delivery_price') </label>
                                                    <span style="color: red">*</span>
                                                    <input name="delivery_charges" class="form-control rounded"
                                                           placeholder="00.00" type="number" step="any" min="0"
                                                           value="{{$zone->delivery_charges}}" required>
                                                </div>
                                                <div class="col-md-12 form-group">
                                                    <label>@lang('site.Min_Order_amount') </label>
                                                    <input name="min_order" class="form-control rounded"
                                                           placeholder="00.00" type="number" step="any"
                                                           value="{{$zone->min_order}}" min="0">
                                                </div>
                                                <br>
                                                <div class=" col-md-12 form-group ">
                                                    <input type="checkbox" name="is_active"
                                                           id="is_active" {{ isset($zone->is_active)  == 1  ? 'checked' : '' }} />
                                                    <label for="is_active">@lang('site.status')</label>
                                                </div>
                                                <div class="col-md-12 form-group">
                                                    @include('deliveryzone.business_hours')
                                                </div>
                                                <div class="clearfix"></div>
                                            </div>
                                            <div class="col-md-6 ">
                                                <div class="col-md-12 form-group zone_coverage">
                                                    <label>Zone Coverage </label>
                                                    <span style="color: red">*</span>
                                                    @if($is_edit == true)
                                                        <input type="hidden" id="outlet_id" value="{{$outlet->id}}}" form="delivery_zones_form">
                                                    @endif
                                                    <select name="zone_coverage" class="form-control "
                                                            id="zone_coverage"
                                                            required {{$is_edit == true ? 'disabled' : ''}}>
                                                        <!-- <option value="">Select</option> -->
                                                        @if(isset($zone->zone_coverage)  && $zone->zone_coverage == 'country')
                                                            <option value="country" selected data-country-id="country">
                                                                International
                                                            </option>
                                                        @elseif(isset($zone->zone_coverage)  && $zone->zone_coverage == 'cities')
                                                            <option value="cities" selected data-cities-id="cities">
                                                                Multiple Cities
                                                            </option>
                                                        @elseif(isset($zone->zone_coverage)  && $zone->zone_coverage == 'area')
                                                            <option value="area" selected data-area-id="area">Single
                                                                City
                                                            </option>
                                                        @else
                                                            <option value="country" data-country-id="country">
                                                                International
                                                            </option>
                                                            <option value="cities" data-cities-id="cities">Multiple
                                                                Cities
                                                            </option>
                                                            <option value="area" selected data-area-id="area">Single
                                                                City
                                                            </option>
                                                        @endif
                                                    </select>
                                                </div>

                                                <div class="col-md-12  country_section ">
                                                    <label>@lang('site.country') </label>
                                                    <span style="color: red">*</span>
                                                    @if($is_edit == false || $zone->zone_coverage == 'country')
                                                        <span class="pull-right select_all_country">
			                                		<input type="checkbox" id="select_all_country"/>
									              	<label for="select_all_country">@lang('site.select_all')</label>
												</span>
                                                    @endif
                                                    <select name="country[]" class="form-control select2 " id="country"
                                                            multiple required
                                                            style="width: 100%" {{$zone->zone_coverage == 'area' || $zone->zone_coverage == 'cities' ? 'disabled' : ''}}>
                                                        @foreach ($countries as $country)
                                                            @php
                                                                $selected = (in_array($country->id , $countries_id )) ? 'selected': '';
                                                            @endphp

                                                            <option value="{{ $country->id }}"
                                                                    data-country-id="{{ $country->id }}" {{ $selected }}>
                                                                {{ $country->country }}
                                                            </option>

                                                        @endforeach
                                                    </select>
                                                </div>
                                                @if($zone->zone_coverage == 'area' || $zone->zone_coverage == 'cities' || $is_edit == false)
                                                    &nbsp;
                                                    <div class="col-md-12  city_section   " onchange="load()">
                                                        <label>@lang('site.city') </label>
                                                        <span style="color: red">*</span>
                                                        @if($is_edit == false || $zone->zone_coverage == 'cities')
                                                            <span class="pull-right select_all_city">
			                                		<input type="checkbox" id="select_all_city"/>
									              	<label for="select_all_city">@lang('site.select_all')</label>
												</span>
                                                        @endif
                                                        <select name="city[]" onchange="load()"
                                                                class="form-control select2" id="city" multiple required
                                                                style="width: 100%"
                                                            {{$zone->zone_coverage == 'area' ? 'disabled' : ''}}
                                                        >
                                                            <!-- <option value="" data-country-id="0">Select</option> -->
                                                            @foreach ($cities as $city)
                                                                @php
                                                                    $selected = (in_array($city->id , $cities_id )) ? 'selected': '';
                                                                    $edit_city = isset($zone->counties[0]) ?  $city->country_id == $zone->counties[0]->country_id : true;
                                                                @endphp
                                                                @if($edit_city)
                                                                    <option value="{{ $city->id }}" onclick="load()"
                                                                            data-country-id="{{ $city->country_id }}" {{ $selected }} >
                                                                        {{ $city->city }}
                                                                    </option>
                                                                @endif

                                                            @endforeach
                                                        </select>
                                                    </div>
                                                @endif
                                                &nbsp;
                                                @if($zone->zone_coverage == 'area' || $is_edit == false)
                                                    <div class="col-md-12 areas_section ">
                                                        <label>@lang('site.areas') </label>
                                                        <span style="color: red">*</span>
                                                        <span class="pull-right">
			                                		<input type="checkbox" id="select_all_area"/>
									              	<label for="select_all_area">@lang('site.select_all')</label>
												</span>
                                                        <select id="area" name="area[]" onchange="load()"
                                                                class="form-control select2" multiple="" required
                                                                style="width: 100%">
                                                            @foreach ($areas as $area)
                                                                @php
                                                                    $selected = (in_array($area->id , $areas_ids )) ? 'selected': '';
                                                                    $disabled = ($area->areas_count > 0 && !in_array($area->id , $areas_ids )) ? 'disabled': '';
                                                                    $disabled_msg = ($area->areas_count > 0 && !in_array($area->id , $areas_ids )) ? 'Already selected in other outlet': '';
                                                                    $edit = isset($zone->cities[0]) ?  $area->city_id == $zone->cities[0]->city_id : true;
                                                                @endphp
                                                                @if($disabled_msg == ''  && $edit)
                                                                    <option value="{{ $area->id }}"
                                                                            data-city-id="{{ $area->city_id }}" {{ $selected }} {{ $disabled }} >
                                                                        {{ $area->name }} &nbsp; &nbsp;
                                                                        &nbsp;{{$disabled_msg}}
                                                                    </option>
                                                                @endif
                                                            @endforeach

                                                        </select>
                                                    </div>
                                                @endif
                                                <div class="clearfix"></div>
                                            </div>

                                        </div>
                                        <div class="row">
                                            <div class="col-md-12 form-group ">
                                                <button type="submit" form="delivery_zones_form"
                                                        class="btn m-b-xs w-xs btn-success  maring-top1 pull-pull-right">

                                                    {{$button}}

                                                </button>
                                                &nbsp;&nbsp;
                                                <a href="{{url('delivery_zones').'/'.$outlet->id}}"
                                                   class="btn m-b-xs w-xs btn-secondry  maring-top1 pull-left">Cancel</a>
                                            </div>
                                        </div>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>
                </form>
            </div>
        </div>
    </div>


@endsection

@section('scripts')
    <script src="//cdnjs.cloudflare.com/ajax/libs/timepicker/1.3.5/jquery.timepicker.min.js"></script>
    <script type="text/javascript">

        $('.timepicker').timepicker({
            timeFormat: 'h:mm p',
            interval: 60,
            minTime: '12:00 AM',
            maxTime: '11:59 PM',
            // defaultTime: '12',
            // startTime: '12:00',
            dynamic: false,
            dropdown: true,
            scrollbar: true
        });
        $('#set_daily').on('change', function () {
            if ($("#set_daily").is(':checked')) {
                check(condition = true);
                let slot_start = $('#day_0_slot_start').val();
                let slot_end = $('#day_0_slot_end').val();
                setDaily(slot_start, slot_end)
            } else {
                setDaily(slot_start = '', slot_end = '');
                check(condition = false);
            }
        })

        function check(condition) {
            $("#sunday").prop('checked', condition);
            $("#monday").prop('checked', condition);
            $("#tuesday").prop('checked', condition);
            $("#wednesday").prop('checked', condition);
            $("#thursday").prop('checked', condition);
            $("#friday").prop('checked', condition);
            $("#saturday").prop('checked', condition);
        }

        function setDaily(slot_start, slot_end) {
            $('#day_1_slot_start').val(slot_start);
            $('#day_1_slot_end').val(slot_end);
            $('#day_2_slot_start').val(slot_start);
            $('#day_2_slot_end').val(slot_end);
            $('#day_3_slot_start').val(slot_start);
            $('#day_3_slot_end').val(slot_end);
            $('#day_4_slot_start').val(slot_start);
            $('#day_4_slot_end').val(slot_end);
            $('#day_5_slot_start').val(slot_start);
            $('#day_5_slot_end').val(slot_end);
            $('#day_6_slot_start').val(slot_start);
            $('#day_6_slot_end').val(slot_end);
            $('#day_7_slot_start').val(slot_start);
            $('#day_7_slot_end').val(slot_end);
        }

        $(document).ready(function () {
            // $("#zone_coverage").select2();
            let is_edit = JSON.parse('{!! json_encode($is_edit) !!}');

            $("#country").select2();
            $("#area").select2();

            $("#city").select2();
            if (is_edit == false) {
                load();
                onloadArea();
            }

            // filterSelectOptions($("#city"), "data-country-id",  $("#country").val());
            // filterSelectOptions($("#area"), "data-city-id",  $("#city").val());

            $("#country").on('change', function () {
                load();
                filterSelectOptions($("#city"), "data-country-id", $(this).val());

                $("#city > option").prop("selected", false);
                $('#city').val('');
                $("#city").trigger("change");

                // filterSelectOptions($("#area"), "data-city-id",  $("#city").val());
            });

            $("#city").on('change', function () {
                load();
                filterSelectOptions($("#area"), "data-city-id", $(this).val());
            });

            function filterSelectOptions(selectElement, attributeName, attributeValue) {

                if (selectElement.data("currentFilter") != attributeValue) {
                    selectElement.data("currentFilter", attributeValue);
                    var originalHTML = selectElement.data("originalHTML");
                    if (originalHTML)
                        selectElement.html(originalHTML)
                    else {
                        var clone = selectElement.clone();
                        clone.children("option[selected]").removeAttr("selected");
                        selectElement.data("originalHTML", clone.html());
                    }
                    if (attributeValue) {
                        selectElement.children("option:not([" + attributeName + "='" + attributeValue + "'],:not([" + attributeName + "]))").remove();
                    }
                }
            }

        })


        // })
        function emptyData() {
            load();
            $("#country > option").prop("selected", false);
            $('#country').val('');
            $("#country").trigger("change");

            $("#city > option").prop("selected", false);
            $('#city').val('');
            $("#city").trigger("change");

            $("#area > option").prop("selected", false);
            $('#area').val('');
            $("#area").trigger("change");
        }

        function onloadArea() {
            load();
            $('.country_section').show();
            $('#country').removeAttr('multiple');
            $('.select_all_country').hide();

            $('#city').removeAttr('multiple');
            $('.select_all_city').hide();
            $('.city_section').show();
            $('#city').attr('required', 'required');


            $('#area').attr('multiple', 'multiple');
            $('#area').attr('required', 'required');

            $('.city_section').show();
            $('.areas_section').show();
        }


        $('#zone_coverage').change(function () {
            load();
            val = $('#zone_coverage option:selected').val();

            console.log(val);
            if (val == 'country') {
                load();
                $('.country_section').show();


                $('.city_section').hide();
                $('#city').removeAttr('multiple');
                $('.select_all_country').show();
                $('#country').attr('multiple', 'multiple');

                $('.areas_section').hide();
                $('#area').removeAttr('multiple');
                $('#city').removeAttr('required');
                $('#area').removeAttr('required');
                emptyData();

            } else if (val == 'cities') {
                load();

                $('.country_section').show();
                $('#country').removeAttr('multiple');

                $('.select_all_country').hide();

                $('#city').attr('multiple', 'multiple');
                $('#city').attr('required', 'required');
                $('.city_section').show();
                $('.select_all_city').show();
                $('#city').attr('required', 'required');

                $('.areas_section').hide();
                $('#area').removeAttr('multiple');
                $('#area').removeAttr('required');
                emptyData();

            } else if (val == 'area') {
                load();
                emptyData();
                onloadArea();
            } else {
                load();
                $('.country_section').hide();
                $('#country').removeAttr('multiple');
                $('.select_all_country').hide();


                $('#city').removeAttr('multiple');
                $('.select_all_city').hide();
                $('.city_section').hide();


                $('.areas_section').hide();
                $('#area').removeAttr('multiple');
                emptyData();

            }
        })

        $("#select_all_country").click(function () {
            if ($("#select_all_country").is(':checked')) {
                load();
                $("#country > option").prop("selected", "selected");
                $("#country").trigger("change");
            } else {
                load();
                $("#country > option").prop("selected", false);
                // console.log($("#country > option"));
                $("#country").trigger("change");
            }
        });

        $("#select_all_city").click(function () {
            if ($("#select_all_city").is(':checked')) {
                load();
                $("#city > option").prop("selected", "selected");
                $("#city").trigger("change");
            } else {
                load();
                $("#city > option").prop("selected", false);
                $("#city").trigger("change");
            }
        });

        $("#select_all_area").click(function () {
            if ($("#select_all_area").is(':checked')) {
                load();
                $("#area > option").prop("selected", "selected");
                $("#area").trigger("change");
            } else {
                load();
                $("#area > option").prop("selected", false);
                $("#area").trigger("change");
            }
        });

        {{--if(is_edit){--}}
        {{--    let val1 =  JSON.parse('{!! json_encode($zone->zone_coverage) !!}');--}}

        {{--	console.log('val1',val1);--}}
        {{--	if(val1 == 'country'){--}}
        {{--	console.log('1');--}}

        {{--		// $('.country_section').show();--}}

        {{--		// $("#city").val();--}}
        {{--		// $("#city").trigger("change");--}}
        {{--		// $('.city_section').hide();--}}
        {{--		// $('#city').removeAttr('multiple');--}}
        {{--		// $('.select_all_country').show();--}}

        {{--		// $("#area").val();--}}
        {{--		// $("#area").trigger("change");--}}
        {{--		// $('.areas_section').hide();--}}
        {{--		// $('#area').removeAttr('multiple');--}}
        {{--		// $('#city').removeAttr('required');--}}
        {{--		// $('#area').removeAttr('required');--}}

        {{--		$('#country').attr('multiple','multiple');--}}
        {{--		$('.country_section').show();--}}
        {{--		$('.city_section').hide();--}}
        {{--		$('.select_all_country').show();--}}

        {{--		$('.areas_section').hide();--}}
        {{--		$('#area').removeAttr('multiple');--}}
        {{--		$('#city').removeAttr('required');--}}
        {{--		$('#area').removeAttr('required');--}}
        {{--	 // emptyData();--}}


        {{--	}else if(val1 == 'cities'){--}}
        {{--        load();--}}
        {{--		console.log('2');--}}
        {{--		$('#city').attr('multiple','multiple');--}}

        {{--		$('.select_all_country').hide();--}}
        {{--		$('.country_section').show();--}}
        {{--		$('#country').removeAttr('multiple');--}}

        {{--		$('#city').attr('required','required');--}}
        {{--		$('.city_section').show();--}}
        {{--		$('.select_all_city').show();--}}
        {{--		$('#city').attr('required','required');--}}


        {{--		// $('.areas_section').hide();--}}
        {{--		// $('#area').removeAttr('multiple');--}}
        {{--		// $('#area').removeAttr('required');--}}

        {{--	}else if(val1 == 'area'){--}}
        {{--		$('.country_section').show();--}}
        {{--		$('#country').removeAttr('multiple');--}}
        {{--		$('.select_all_country').hide();--}}

        {{--		$('#city').removeAttr('multiple');--}}
        {{--		$('.select_all_city').hide();--}}
        {{--		$('.city_section').show();--}}
        {{--		$('#city').attr('required','required');--}}

        {{--		$('#area').attr('multiple','multiple');--}}
        {{--		$('#area').attr('required','required');--}}

        {{--		$('.city_section').show();--}}
        {{--		$('.areas_section').show();--}}
        {{--	}else{--}}
        {{--	console.log('4');--}}

        {{--		$("#country").val();--}}
        {{--		$("#country").trigger("change");--}}
        {{--		$('.country_section').hide();--}}
        {{--		$('#country').removeAttr('multiple');--}}
        {{--		$('.select_all_country').hide();--}}

        {{--		$("#city").val();--}}
        {{--		$("#city").trigger("change");--}}
        {{--		$('#city').removeAttr('multiple');--}}
        {{--		$('.select_all_city').hide();--}}
        {{--		$('.city_section').hide();--}}

        {{--		$("#area").val();--}}
        {{--		$("#area").trigger("change");--}}
        {{--		$('.areas_section').hide();--}}
        {{--		$('#area').removeAttr('multiple');--}}

        {{--	}--}}






        {{--}--}}

        // $(function () {
        //     $('#delivery_zones_form').submit(function () {
        //
        //         // var name = $('#title_en').val();
        //         // $('#name').val(name);
        //
        //         if ($(this)[0].checkValidity()) {
        //             $.ajax({
        //                 url: $(this).attr('action'),
        //                 type: $(this).attr('method'),
        //                 data: $(this).serialize(),
        //                 success: function (response) {
        //                     if (response.IsValid) {
        //                         toastr.success(response.Message, 'Success');
        //                         setTimeout(() => {
        //                             window.location.href = site_url('delivery_zones/')+response.outlet_id;
        //                         }, 3000);
        //                     } else {
        //                         if (response.Errors.lenght > 0) {
        //                             response.Errors.map((error) => {
        //                                 toastr.error(error, 'Error');
        //                             });
        //                         } else {
        //                             toastr.error(response.Errors[0], 'Error');
        //                         }
        //                     }
        //                 },
        //             });
        //         }
        //         return false;
        //     });
        // });


        function load() {
            $loading.show();
            setTimeout(() => {
                $loading.hide();
            }, 1500);
        }



    </script>
@endsection
