@extends('layouts.wizard')
@section('content')
    <!-- col1 -->

    <div class="w-1">
        <div class="m-col">
            <div class="row">
                <div class="col-xl-12 col-md-12 m-lg-5 ">
                    <div class="card shadow border-0 h-100 py-2">
                        <div class="card-body pb-0">
                            <div class="text-center"><img src="{{ url('backoffice/assets/img/icon2.png') }}"></div>
                            <h2 class="mt-4 text-center">{{ __('setup.import_catalogue') }}</h2>


                            <div class="w-forms ml-lg-5 mr-lg-5">
                                <div class="form-row">
                                    <div class="col-md-12 mb-2" style="margin-top: 31px;">
                                        <div class="custom-file">
                                            <label
                                                for="validationDefault04">{{ __('setup.download_excel') }}</label>
                                            <!-- <input type="file" class="form-control form-logo" id="customFile"> -->
                                            <a href="{{ route('export.products',['export']) }}"
                                               id="validationDefault04"
                                               class="btn m-b-xs w-auto btn-success" target="_top"
                                               style="margin: 4px 10px -12px 18px;">
                                                Download Excel
                                            </a>
                                        </div>
                                    </div>


                                    <div class="col-md-10 mb-2" style="margin-top: 22px;">
                                        <label for="validationDefault04">{{ __('setup.upload_excel') }}</label>
                                        <form action="{{ route('import.products')}}" method="post"
                                              enctype="multipart/form-data">
                                            @csrf
                                            <input type="file" style="padding: 21px 17px;"
                                                   class="form-control form-logo " name="imported-file" size="chars"
                                                   required accept=".xlsx">
                                            <div>
                                                <input type="hidden" name="stock" value="update">
                                                <input type="hidden" name="language" value="en">
                                            </div>
                                    </div>
                                    <div class="col-md-2 mb-2" style="margin-top: 44px;">
                                        <button type="submit" href=""
                                                class="btn btn-primary mb-2  ">{{ __('setup.upload') }}&nbsp;<i
                                                class="icon-arrow-right"></i></button>
                                        <i class="icon-download"></i>
                                    </div>
                                    </form>


                                    <div class="col-md-10 mb-2">
                                        <label for="validationDefault04">{{ __('setup.upload_zip') }}</label>
                                        <form action="{{ route('product.import.images')}}" method="post"
                                              enctype="multipart/form-data">
                                            @csrf

                                            <input type="file" style="padding: 21px 17px;"
                                                   class="form-control form-logo" name="imported-file" size="chars"
                                                   required accept=".zip">
                                            <div>
                                                <input type="hidden" name="stock" value="update">
                                                <input type="hidden" name="language" value="en">
                                            </div>
                                    </div>
                                    <div class="col-md-2 mb-2" style="margin-top: 23px;">
                                        <button type="submit" href=""
                                                class="btn btn-primary mb-2  ">{{ __('setup.upload') }}&nbsp; <i
                                                class="icon-arrow-right"></i></button>
                                    </div>
                                    </form>

                                    <div class="col-md-12 mb-2 text-center">
                                        <div class=" mt-4">{{ __('setup.or') }}</div>
                                        <br>
                                        <form action="{{ route('import.products')}}" method="post"
                                              enctype="multipart/form-data">
                                            @csrf
                                            <input type="hidden" name="stock" value="update">
                                            <input type="hidden" name="language" value="en">
                                            <input type="hidden" name="custome_upload" value="1">
                                            <input type="hidden" name="imported-file"
                                                   value="{{ asset('Sample_File.xlsx') }}">
                                            <button
                                                class="btn btn-primary mt-4">{{ __('setup.install_sample_data') }}</button>
                                        </form>
                                    </div>

                                </div>
                            </div>

                            @if (is_array($errors) && count($errors) > 0)
                                <div class="wrapper-md">
                                    <div class="col-md-12">
                                        <div class="alert alert-danger" style="margin: 15px 54px; width: 43%;">
                                            <label>Please fix the errors below to continue</label>
                                        </div>
                                    </div>
                                </div>
                            @endif
                            @if (\Session::has('success'))
                                <div class="alert alert-success success" style="margin: 15px 54px; width: 43%;">
                                    <ul>
                                        <li>{!! \Session::get('success') !!}</li>
                                    </ul>
                                </div>
                            @endif
                            @if (\Session::has('errors'))
                                <div class="alert alert-danger danger" style="margin: 15px 54px; width: 43%;">
                                    <ul>
                                        <li>{!! \Session::get('errors') !!}</li>
                                    </ul>
                                </div>
                            @endif
                            @if (\Session::has('Exception'))
                                <div class="alert alert-danger exception" style="margin: 15px 54px; width: 43%;">
                                    <ul>
                                        <li>{!! \Session::get('Exception') !!}</li>
                                    </ul>
                                </div>
                            @endif
                            @if (is_array($errors) && count($errors) > 0)
                                <div class="wrapper-md">
                                    <div class="col-md-12">
                                        @php
                                            $issues = $errors;
                                            $count = sizeof($issues) == 3 ? 4 : (sizeof($issues) == 2 ? 6 : 12)
                                        @endphp
                                        @foreach ($issues as $key => $value)
                                            <div class="col-md-{{ $count }} ">
                                                <div class="alert alert-danger">
                                                    <label>{{ $key }}</label> <br>
                                                    @foreach($value as $line => $rows)
                                                        @if(is_array($rows))
                                                            <span>{{ $line }}</span><br>
                                                            <ul>
                                                                @foreach($rows as $row)
                                                                    <li>{{ $row }}</li>
                                                                @endforeach
                                                            </ul>
                                                        @else
                                                            <label>{{ isset($value[0]) ? $value[0] : '' }}</label>
                                                        @endif
                                                    @endforeach
                                                </div>
                                            </div>
                                        @endforeach
                                    </div>
                                </div>
                            @endif
                            <div class="mt-5 mb-5"><br></div>
                            <div class="mt-5 mb-5"><br></div>

                            <div class="top bottom-btns row pb-0 pt-3 p-2 mt-5">
                                <div class="col-md-6 text-lg-left">
                                <!-- <button type="submit" class="btn btn-grey mb-2 text-muted">{{ __('setup.cancel') }}</button> -->

                                <!-- <a href="{{ route('setup.step1')}}" class="btn btn-grey mb-2 btn-secondary  mr-3"><i class="icon-arrow-left"></i> {{ __('setup.previous') }} </a> -->
                                </div>
                                <div class="col-md-6 text-lg-right">
                                    <a href="{{ route('setup.step3')}}"
                                       class="btn btn-primary mb-2  pull-right">{{ __('setup.next') }}<i
                                            class="icon-arrow-right"></i></a>
                                    <a href="{{ route('setup.step3')}}"
                                       class="btn btn-grey mb-2 text-muted">{{ __('setup.skip') }}</a>

                                </div>
                            </div>
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
                <li>
                    <span class="unfilled">1</span>
                    <h3 class="mb-2">{{ __('setup.store_setup') }}</h3>
                    <p>{{ __('setup.message1') }}</p>
                </li>
                <li class="active">
                    <span class="active filled"><i class="fa fa-check"></i></span>
                    <h3 class="mb-2">{{ __('setup.import_catalogue') }}</h3>
                    <p>{{ __('setup.message2') }}</p>
                </li>
                <li>
                    <span class="unfilled">3</span>
                    <h3 class="mb-2">{{ __('setup.complete_congrate') }}</h3>
                    <p>{{ __('setup.message3') }}</p>
                </li>
                <div class="text-white n-help">
                    <h3>{{ __('setup.helping_hand') }}</h3>
                    <p class=" pt-2">{{ __('setup.call_us') }} : <a href="tel:{{ $phone }}">{{ $phone }}</a>
                        <br> {{ __('setup.email') }} : <a href="mailto:{{ $email }}">{{ $email }}</a>
                    </p>
                </div>
            </ul>
        </div>
    </div>
    <!-- JavaScript Libraries -->
@endsection
@section('scripts')
    <script type="text/javascript">
        $(document).ready(function () {
            if ($('.error').html() != null || $('.error').html()) {
                toastr.error($('.error').find('ul').find('li').eq(0).html(), 'Error');
            }
            if ($('.success').html() != null || $('.success').html()) {
                toastr.success($('.success').find('ul').find('li').eq(0).html(), 'Success');
            }
            if ($('.danger').html() != null || $('.danger').html()) {
                toastr.error($('.danger').find('ul').find('li').eq(0).html(), 'Exception');
            }

        })


    </script>




@endsection
