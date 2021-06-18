@extends('layouts.backoffice')
@section('content')
    <!-- content -->
    <div class="container-fluid">
        <div class="row">

            <div class="col-sm-12 pl-0 pr-0">
                <div class="greybg1 rounded p-4 mb-3">
                    <div class="row">
                        <div class="col-sm-12">
                            <div class="common_title">
                                <h1>{{ __('backoffice.general_setup') }}
                                    <a href="{{route('outlets.index')}}"
                                       class="m-b-xs w-auto btn-primary btn-sm pull-right">
                                        {{ __('backoffice.back') }}
                                    </a>
                                </h1>
                            </div>
                        </div>
                    </div>
                </div>
            </div>

            <div class="col-sm-12 pl-0 pr-0">
                <div class="card bg-light mt-3  rounded  border-0">
                    <!-- <p style="text-align: center;">Fields marked with <span style="color: red;">*</span> are mandatory to fill</p> -->
                    <form action="{{route('api.store.save')}}" method="post" id="store_form">
                        {{ csrf_field() }}
                    </form>

                    <div class="card bg-light mt-3 pl-3 pr-3  rounded  border-0">
                        <div class="row">
                            <div class="card-body">
                                <div class="row">
                                    <div class="col-sm-12">
                                        <div class="row">
                                            <div class="col-md-4">
                                                <span style="color: red">*</span>
                                                <label>{{ __('backoffice.store_name') }}</label>&nbsp;
                                                <a href="#" data-toggle="tooltip" data-placement="right"
                                                   title="{{ __('backoffice.enter_business') }}!"><i
                                                        class="fa fa-info-circle" style="font-size:20px;color:grey"></i></a>

                                                <input form="store_form" name="name" id="name_check"
                                                       class="form-control rounded" value="{{$store->name}}" type="text"
                                                       placeholder="{{ __('backoffice.super_market') }}" required s>
                                                <input form="store_form" name="id" value="{{$store->id}}"
                                                       type="hidden"/>
                                            </div>
                                            <div class="col-md-4">
                                                <span style="color: red">*</span>
                                                <label>{{ __('backoffice.industry_type') }}</label>
                                                <a href="#" data-toggle="tooltip" data-placement="bottom"
                                                   title="{{ __('backoffice.type_inustry') }}."><i class="fa fa-info-circle"
                                                                                              style="font-size:20px;color:grey"></i></a>
                                                <select class="form-control rounded select2 " name="industry_id"
                                                        id="industry_id" form="store_form">
                                                    @foreach ($industries as $industry)
                                                        <option
                                                            value="{{$industry->id}}" {{ isset($store->industry_id) && $store->industry_id == $industry->id ?'selected' : '' }}>{{$industry->name}}</option>
                                                    @endforeach
                                                </select>
                                            </div>
                                            <div class="col-md-4">
                                                <span style="color: red">*</span>
                                                <label>{{ __('backoffice.default_currency') }}</label>&nbsp;
                                                <a href="#" data-toggle="tooltip" data-placement="bottom"
                                                   title="{{ __('backoffice.tt_currency') }}">
                                                    <i class="fa fa-info-circle" style="font-size:20px;color:grey"></i>
                                                </a>
                                                <select class="form-control rounded select2" name="default_currency"
                                                        required id="currency" form="store_form">
                                                    @foreach ($currencies as $currency)
                                                        <option
                                                            value="{{ $currency->currency_symbol }}" {{ isset($store->default_currency) && $store->default_currency == $currency->currency_symbol ?'selected' : '' }}>
                                                            {{$currency->currency_symbol}}
                                                        </option>
                                                    @endforeach
                                                </select>
                                            </div>

                                            &nbsp;
                                            <div class="col-md-12">
                                                <div class="" id="moreless2">
                                                    <div class="row">
                                                        <div class="col-md-4">
                                                            <span style="color: red">*</span>
                                                            <label>Languages</label>
                                                            <a href="#" data-toggle="tooltip" data-placement="bottom"
                                                               title="{{ __('backoffice.tt_language') }}"><i
                                                                    class="fa fa-info-circle"
                                                                    style="font-size:20px;color:grey"></i></a>
                                                            <select class=" form-control rounded  select2 " multiple
                                                                    name="language_ids[]" searchable="Search here.."
                                                                    id="language" form="store_form">
                                                                @foreach($languages as $language)
                                                                    <option
                                                                        value="{{$language['id']}}" {{ in_array($language['id'], $store_languages,  true) ? 'selected' : ''}}>

                                                                        {{$language['name']}}

                                                                    </option>
                                                                @endforeach
                                                            </select>
                                                        </div>

                                                        <div class="col-md-4">
                                                            <label>{{ __('backoffice.SKU_generation') }}</label>
                                                            <a href="#" data-toggle="tooltip" data-placement="right"
                                                               title="
                                                                {{ __('backoffice.tt_SKU_gen') }}">
                                                                <i class="fa fa-info-circle"
                                                                   style="font-size:20px;color:grey"></i></a>
                                                            <select name="sku_generation" style="width:100%;"
                                                                    class="form-control m-b " id="sku_generation"
                                                                    form="store_form">
                                                                <option
                                                                    value="1" {{ isset($store->sku_generation) && $store->sku_generation == 1 ?'selected' : '' }}>{{ __('backoffice.generate_by_sequence_number') }}</option>
                                                                <option
                                                                    value="0" {{ isset($store->sku_generation) && $store->sku_generation == 0 ?'selected' : '' }}>{{ __('backoffice.generate_by_name') }}</option>
                                                            </select>
                                                        </div>
                                                        <div class="col-md-4">
                                                            <label>{{ __('backoffice.sku_sequence_number') }}</label>
                                                            <a href="#" data-toggle="tooltip" data-placement="right"
                                                               title="{{ __('backoffice.tt_sku_generation_sequence_num') }}">
                                                                <i class="fa fa-info-circle"
                                                                   style="font-size:20px;color:grey"></i></a>
                                                            <input name="current_sequence_number"
                                                                   value="{{$store->current_sequence_number}}"
                                                                   class="form-control rounded" placeholder=" 1000"
                                                                   type="text" form="store_form">
                                                        </div>

                                                    </div>
                                                </div>
                                                &nbsp;

                                                <div class="row">
                                                    <div class="col-md-4">
                                                        <label>{{ __('backoffice.stock_min_threshold_value') }}&nbsp;
                                                            <a href="#" data-toggle="tooltip" data-placement="right"
                                                               title="{{ __('backoffice.stock_min_threshold_value') }}">
                                                                <i class="fa fa-info-circle"
                                                                   style="font-size:20px;color:grey"></i></a>
                                                        </label>
                                                        <input name="stock_threshold"
                                                               value="{{$store->stock_threshold}}"
                                                               class="form-control rounded" placeholder=" 1000"
                                                               type="text" form="store_form">
                                                    </div>
                                                    <div class="col-md-4">
                                                        <label>{{ __('backoffice.amount_round_to') }}</label>
                                                        <a href="#" data-toggle="tooltip" data-placement="right"
                                                           title="{{ __('backoffice.amount_round_to') }}">
                                                            <i class="fa fa-info-circle"
                                                               style="font-size:20px;color:grey"></i></a>
                                                        <select id="round_to" name="round_off" style="width:100%;"
                                                                class="form-control m-b " form="store_form">
                                                            <option
                                                                value="0" {{ isset($store->round_off) && $store->round_off == 0 ?'selected' : '' }}>{{ __('store.select_decimal_place') }}</option>
                                                            <option
                                                                value="1" {{ isset($store->round_off) && $store->round_off == 1 ?'selected' : '' }}>
                                                                1 {{ __('backoffice.decimal_place') }}</option>
                                                            <option
                                                                value="2" {{ isset($store->round_off) && $store->round_off == 2 ?'selected' : '' }}>
                                                                2 {{ __('backoffice.decimal_place') }}</option>
                                                            <option
                                                                value="3" {{ isset($store->round_off) && $store->round_off == 3 ?'selected' : '' }}>
                                                                3 {{ __('backoffice.decimal_place') }}</option>
                                                            <option
                                                                value="4" {{ isset($store->round_off) && $store->round_off == 4 ?'selected' : '' }}>
                                                                4 {{ __('backoffice.decimal_place') }}</option>
                                                        </select>
                                                    </div>

                                                </div>
                                            </div>

                                        </div>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>
                    <div class="card bg-light mt-3 pl-3 pr-3  rounded  border-0">
                        <div class="row">
                            <div class="card-body">
                                <div class="row">
                                    <div class="col-sm-12">
                                        <div class="row">
                                            <div class="col-md-4">
                                                <span style="color: red">*</span>
                                                <label>{{ __('backoffice.contact_name') }}</label>
                                                <a href="#" data-toggle="tooltip" data-placement="right"
                                                   title="{{ __('backoffice.tt_owner_name') }}">
                                                    <i class="fa fa-info-circle" style="font-size:20px;color:grey"></i></a>
                                                <input name="contact_name" id="contact_name_check"
                                                       value="{{$store->contact_name}}" class="form-control rounded"
                                                       placeholder="{{ __('backoffice.name') }}" type="text" required
                                                       form="store_form">
                                            </div>
                                            <div class="col-md-4">
                                                <span style="color: red">*</span>
                                                <label>{{ __('backoffice.email') }}</label>
                                                <a href="#" data-toggle="tooltip" data-placement="right"
                                                   title="{{ __('backoffice.tt_owner_email') }}">
                                                    <i class="fa fa-info-circle" style="font-size:20px;color:grey"></i></a>
                                                <input name="email" id="email-check" value="{{$store->email}}"
                                                       class="form-control rounded" placeholder="john@domain-name.com"
                                                       type="email" required form="store_form">
                                            </div>
                                            <div class="col-md-4">
                                                <span style="color: red">*</span>
                                                <label>{{ __('backoffice.phone') }}</label>
                                                <a href="#" data-toggle="tooltip" data-placement="right"
                                                   title=" {{ __('backoffice.tt_owner_phone') }}">
                                                    <i class="fa fa-info-circle" style="font-size:20px;color:grey"></i>
                                                </a>
                                                <div>
                                                    <input id="mobile" name="phone" type="tel" name="phone"
                                                           value="{{$store->phone}}" class="form-control rounded"
                                                           form="store_form" style="    padding: 5px 53px;">
                                                    <span id="valid-msg" class="hide valid-msg" hidden>✓ Valid </span>
                                                    <span id="error-msg" class="hide error-msg"></span>
                                                </div>

                                            </div>

                                        </div>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>
                    <div class="card bg-light mt-3 pl-3 pr-3  rounded  border-0">
                        <div class="row">
                            <div class="card-body">
                                <div class="row">
                                    <div class="col-sm-12">
                                        <div class="row">
                                            <div class="col-md-8">
                                                <span style="color: red">*</span>
                                                <label>{{ __('backoffice.reciept_disclaimer') }} </label>
                                                <a href="#" data-toggle="tooltip" data-placement="right"
                                                   title="{{ __('backoffice.tt_reciept_disclaimer') }}">
                                                    <i class="fa fa-info-circle" style="font-size:20px;color:grey"></i></a>
                                                <textarea rows="6" name="reciept_disclaimer"
                                                          class="form-control rounded " type="text" required
                                                          style="min-height: 87%;"
                                                          form="store_form">{{$store->reciept_disclaimer}}</textarea>
                                            </div>

                                            <div class="col-md-4">
                                                <label>{{ __('backoffice.store_logo') }}</label>
                                                <form action="/file-upload" name="images" class="dropzone"
                                                      id="my-awesome-dropzone" required style="min-height: 87%;"></form>
                                                <div class="d-none">
                                                    <div id="hidden-images">
                                                        <input type="hidden" form="store_form" name="images[0][name]"
                                                               value="{{ $store->name }}"/>
                                                        <input type="hidden" form="store_form" name="images[0][path]"
                                                               value="{{ $store->store_logo }}"/>
                                                        <input type="hidden" form="store_form" name="images[0][size]"
                                                               value="0"/>
                                                    </div>
                                                </div>
                                            </div>


                                        </div>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>
                    <div class="card bg-light mt-3 pl-3 pr-3  rounded  border-0">
                        <div class="row">
                            <div class="card-body">
                                <div class="row">
                                    <div class="col-sm-12">
                                        <div class="row">
                                            <div class="col-md-12 ">
                                                <button type="submit" form="store_form"
                                                        class="btn btn-primary submit">{{ __('backoffice.update_store') }}</button>&nbsp;&nbsp;
                                                <a class="btn btn-light"
                                                   href="{{route('outlets.index')}}">{{ __('backoffice.cancel') }}</a>
                                            </div>

                                        </div>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>

@endsection

@section('scripts')

    <script type="text/javascript">

        var itemsToKeep = $('#language').val();
        $('#language').on('select2:unselecting', function (event) {
            if (event.params.args.data.text.trim() == 'English') {
                event.preventDefault();
            }
        })

        $(document).ready(function () {
            $('#industry_id').select2();
            $('#currency').select2();
            $('#sku_generation').select2();
            $('#round_to').select2();
            $('#language').select2();


        });


        $(function () {
            $('#store_form').submit(function () {

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
                                    window.location.href = site_url('outlets');
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


        var image_upload_path = "{{ route('api.upload.store.image') }}";
        var form_id = 'store_form';
        var p_images = '';
        p_images = JSON.parse('{!! json_encode($image) !!}');
        let maxFiles = 1;
    </script>




    @include('partials.backoffice.JSintel-plugin')
@endsection
