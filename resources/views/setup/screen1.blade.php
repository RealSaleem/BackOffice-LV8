@extends('layouts.wizard')
@section('content')

    <!-- col1 -->

    <div class="w-1">
        <div class="m-col">
            <div class="row">
                <div class="col-xl-12 col-md-12 m-lg-5 ">
                    <div class="card shadow border-0 h-100 py-2">
                        <div class="card-body pb-0">
                            <div class="text-center"><img src="{{ url('backoffice/assets/img/icon1.png') }}"></div>
                            <h2 class="mt-4 text-center">{{ __('setup.store_setup') }}</h2>
                            <form action="{{ route('setup.step1.save') }}" method="POST " id="setup-form">
                                <div class="w-forms ml-lg-5 mr-lg-5">
                                    <div class="col-md-12 mb-3">
                                        <div class="form-row">
                                            <div class="col-md-6 mb-2">
                                                <label for="name_check">{{ __('setup.store_name') }} <span
                                                        style="color: red">*</span></label>
                                                <input type="text" name="name" class="form-control" id="name_check"
                                                       required/>
                                            </div>
                                        <!-- <div class="col-md-6 mb-2">
                                  <label for="phone">{{ __('setup.phone') }} <span style="color: red">*</span></label>
                                  <input type="number" name="phone" class="form-control" id="phone" required />
                               </div> -->


                                            <div class="col-md-6 mb-2">
                                                <label for="phone">{{ __('setup.phone') }} <span
                                                        style="color: red">*</span></label>
                                                <input type="text" name="phone" class="form-control" id="mobile"
                                                       required/>
                                                <span id="valid-msg" class="d-none valid-msg">✓ Valid </span>
                                                <span id="error-msg" class="d-none error-msg"></span>
                                            </div>


                                            <div class="col-md-6 mb-2">
                                                <label for="">{{ __('setup.industry') }}</label>
                                                <select name="industry_id" class="form-control m-b " id="industry"
                                                        required>
                                                    @foreach($industries as $industry)
                                                        <option
                                                            value="{{ $industry->id }}">{{ $industry->name }}</option>
                                                    @endforeach
                                                </select>
                                            </div>
                                            <div class="col-md-6 rounded">
                                                <label for="">{{ __('setup.currency') }}</label>
                                                <select name="default_currency" id="currency" class="form-control m-b"
                                                        required>
                                                    @foreach($currencies as $currency)
                                                        <option
                                                            value="{{ $currency->currency_symbol }}">{{ $currency->currency_symbol }}</option>
                                                    @endforeach
                                                </select>
                                            </div>
                                            <div class="col-md-6 mb-3">
                                                <div class="custom-file">
                                                    <label for="">{{ __('setup.logo') }}</label>
                                                    <input type="file" class="form-control form-logo" id="customFile"
                                                           accept="image/png, image/jpeg">
                                                </div>
                                            </div>

                                            <div class="col-md-12 mt-3">
                                                <div class="custom-file">
                                                    <label for="">{{ __('setup.address') }} <span
                                                            style="color: red">*</span> </label>
                                                    <input type="text" name="address" class="form-control" id="mymap"
                                                           placeholder="" required>
                                                </div>
                                            </div>
                                            <div class="col-md-12 mt-3">
                                                <div class="form-group">
                                                    <div id="map" style="width:100%;height:322px"></div>
                                                    <div class="d-none">
                                                        <input class="form-control" value="67.0589736" id="longitude"
                                                               name="longitude"/>
                                                        <input class="form-control" value="24.9554899" id="latitude"
                                                               name="latitude"/>
                                                    </div>
                                                </div>
                                            </div>
                                        </div>
                                    <!-- <h2 class="mt-2 text-center">{{ __('setup.receipt_information') }}</h2>
                            <div class="form-row">
                               <label for="">{{ __('setup.disclaimer') }}</label>
                               <i class="" style="font-size:20px;color:grey"></i>
                               <textarea rows="6" class="form-control rounded " type="text"></textarea>
                               <div class="col-md-12 mb-6">
                               </div>
                            </div> -->
                                    </div>
                                </div>
                                <div class="top bottom-btns row pb-0 pt-3 p-2 mt-5">
                                    <div class="col-md-6 text-lg-left">
                                    <!-- <button type="button" class="btn btn-grey mb-2 text-muted">{{ __('setup.cancel') }}</button> -->
                                    </div>
                                    <div class="col-md-6 text-lg-right">
                                        <button type="submit" class="btn btn-primary mb-2 submit">{{ __('setup.next') }}
                                            <i class="icon-arrow-right"></i></button>
                                    </div>
                                </div>
                            </form>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>

    <!-- col2 -->
    <div class="w-2">
        <div class="right_content">
            <ul>
                <li class="active">
                    <span class="active filled"><i class="fa fa-check"></i></span>
                    <h3 class="mb-2">{{ __('setup.store_setup') }}</h3>
                    <p>{{ __('setup.message1') }}</p>
                </li>
                <li>
                    <span class="unfilled">2</span>
                    <h3 class="mb-2">{{ __('setup.import_catalogue') }}</h3>
                    <p>{{ __('setup.message2') }}</p>
                </li>
                <li>
                    <span class="unfilled">3</span>
                    <h3 class="mb-2">{{ __('setup.complete_congrate') }}</h3>
                    <p>{{ __('setup.message3') }}</p>
                </li>
                <div class="text-white n-help pt-4 mt-4 pb-4 mb-4">
                    <h3>{{ __('setup.helping_hand') }}</h3>
                    <p class=" pt-2">{{ __('setup.call_us') }} : <a href="tel:{{ $phone }}">{{ $phone }}</a>
                        <br> {{ __('setup.email') }} : <a href="mailto:{{ $email }}">{{ $email }}</a>
                    </p>
                </div>
            </ul>
        </div>
    </div>

@endsection
<!-- JavaScript Libraries -->

@section('scripts')


    <script src="https://cdn.jsdelivr.net/jquery.validation/1.16.0/jquery.validate.min.js"></script>
    <script type="text/javascript">


        $(document).ready(function () {
            $('#industry').select2();
            $('#currency').select2();

            $('#setup-form').submit(function (e) {
                e.preventDefault();
                if (!intl.isValidNumber()) {
                    document.getElementById('mobile').focus();
                    $('#error-msg').show();
                    return false;
                }
                if ($(this)[0].checkValidity() && intl.isValidNumber()) {

                    var fd = new FormData($(this)[0]);
                    fd.append('image', $('#customFile')[0].files[0]);

                    $.ajax({
                        url: $(this).attr('action'),
                        type: 'POST',
                        processData: false,
                        contentType: false,
                        data: fd,
                        success: function (response) {
                            if (response.IsValid) {
                                window.location.href = '/step2';
                            }
                        },
                        error(data) {
                            console.log('data') // handle the error
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
