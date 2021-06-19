@extends('layouts.backoffice')
@section('content')
    <style>
        .select2-container--default .select2-selection--single {
            height: 45px;
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
                                    {{ $model->title }}
                                    <a href="{{route('brands.index')}}"
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
                        <form method="POST" action="{{ $model->route }}" id="brands-form">
                            {{csrf_field()}}
                            <input id="name" type="hidden" form="brands-form" name="name" value="">
                            @if($model->edit_mode)
                                <input name="id" type="hidden" value="{{ $model->brand['id'] }}">
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


                        <ul class="nav nav-tabs nav-justified" id="myTab" role="tablist">
                            @php
                                $index = 0;
                            @endphp
                            @foreach($model->languages as $language)
                                <li class="nav-item greybg1">
                                    <a class="tab-section nav-link show {{ ($index == 0) ? 'active' : '' }}"
                                       data-lang="{{ strtolower($language['name']) }}" data-toggle="tab"
                                       href="#{{ strtolower($language['name']) }}" role="tab" aria-selected="false">
                                        {{ $language['name'] }}
                                    </a>
                                </li>
                                @php
                                    $index++;
                                @endphp
                            @endforeach
                        </ul>
                        <div class="tab-content mt-4" id="myTabContent">
                            @php
                                $index = 0;
                            @endphp
                            @foreach($model->languages as $language)
                                @php
                                    $title = 'title_' . strtolower($language['short_name']);
                                @endphp
                                <div id="{{ strtolower($language['name']) }}"
                                     class="tab-pane fade show {{ ($index == 0) ? 'active' : '' }}">
                                    <div class="card-body">
                                        <i class="" tabindex="0" data-toggle="popover" data-trigger="focus" title=""
                                           data-content="Enter your brands name" data-original-title=></i>
                                        <label>{{  __('backoffice.name')  }}
                                            @if($index == 0)
                                                <em style="color: red">*</em>
                                            @endif </label>
                                        <input type="text" id="title_{{ strtolower($language['short_name']) }}"
                                               name="title_{{ strtolower($language['short_name']) }}"
                                               class="form-control" form="brands-form"
                                               @if($index==0) @endif  value="{{ old($title,$model->brand[$title]) }}">
                                    </div>
                                </div>
                                @php
                                    $index++;
                                @endphp
                            @endforeach
                        </div>
                        <div class="card bg-light mt-lg-4  rounded  border-0">
                            <div class="card-body">
                                <label>
                                    <i class="" tabindex="0" data-toggle="popover" data-trigger="focus"
                                       title="Brand Image"
                                       data-content="Select Image. Image size should be (120 x 120)"></i>
                                    {{ __('backoffice.add_image') }}
                                </label>
                                <form name="brand_images" action="/file-upload" class="dropzone"
                                      id="my-awesome-dropzone" enctype="multipart/form-data">
                                    <div class="fallback">
                                        <input name="file" type="file" style="display: none;">
                                    </div>
                                </form>

                                {{--                  @dd($model->brand)--}}
                                <div class="hidden">
                                    <div id="hidden-images">
                                        @if(isset($model->brand['brands_images']))
                                            @php
                                                $index = 0;
                                            @endphp
                                            @foreach($model->brand['brands_images'] as $image)
                                                <input type="hidden" form="brands-form"
                                                       name="images[{{ $index }}][name]" value="{{ $image['name'] }}"/>
                                                <input type="hidden" form="brands-form"
                                                       name="images[{{ $index }}][path]" value="{{ $image['url'] }}"/>
                                                <input type="hidden" form="brands-form"
                                                       name="images[{{ $index }}][size]" value="{{ $image['size'] }}"/>
                                                @php
                                                    $index++;
                                                @endphp
                                            @endforeach
                                        @endif
                                    </div>
                                </div>
                            </div>

                        </div>
                    </div>
                </div>
            </div>
            <div class="col-sm-4">
                <div class="rounded p-4">
                    @php
                        $index = 0;
                    @endphp
                    @foreach($model->languages as $language)
                        @php
                            $has_seo = 'has_seo_' . strtolower($language['short_name']);
                            $meta_title = 'meta_title_' . strtolower($language['short_name']);
                            $meta_keywords = 'meta_keywords_' . strtolower($language['short_name']);
                            $meta_description = 'meta_description_' . strtolower($language['short_name']);
                        @endphp
                        <div id="seo-{{ strtolower($language['name']) }}"
                             class="seo-section {{ ($index == 0) ? 'show' : 'd-none' }}">
                            <div class="rounded">
                                <div class="custom-control custom-checkbox">
                                    <input type="checkbox" class="custom-control-input has_seo"
                                           id="checkbox-{{ strtolower($language['short_name']) }}" form="brands-form"
                                           name="{{ $has_seo }}" data-lang="{{ strtolower($language['short_name']) }}"
                                           {{ $model->brand[$has_seo] ? 'checked' : '' }} value="1">
                                    <label class="custom-control-label"
                                           for="checkbox-{{ strtolower($language['short_name']) }}">@lang('backoffice.SEO_options')
                                        ( @lang('backoffice.search_engine') )</label>
                                </div>
                                <p class="mt-3 text-secondary">
                                    @lang('backoffice.add_desc_brand')
                                </p>
                                <div
                                    class="seo-content-{{ strtolower($language['short_name']) }} {{$model->brand[$has_seo] ? 'show' : 'd-none' }}">
                                    <div class="form-group">
                                        <label class="btn-block">{{ __('brand.meta_title') }} <i
                                                class="icon-info pr-1 float-right" tabindex="0" data-toggle="popover"
                                                data-trigger="focus" title="Title for SEO"
                                                data-content="Enter Brand name"></i>
                                            <span
                                                class="meta_title_span_{{ strtolower($language['short_name']) }} counter-p">(0 of 70 characters)</span>
                                        </label>
                                        <input type="text" name="meta_title_{{ strtolower($language['short_name'])}}"
                                               id="meta_title_{{ strtolower($language['short_name']) }}"
                                               class="form-control" placeholder="Add your page's meta title"
                                               style="font-style:italic" data-charcount-enable="true"
                                               data-charcount-textformat="([used] of [max] characters)"
                                               data-charcount-position=".meta_title_span_{{ strtolower($language['short_name']) }}"
                                               data-charcount-maxlength="70" maxlength="70" form="brands-form"
                                               value="{{ old($meta_title,$model->brand[$meta_title]) }}">
                                    </div>
                                    <div class="form-group">
                                        <label class="btn-block">{{ __('backoffice.meta_keywords') }}
                                            <i class="icon-info pr-1 float-right" tabindex="0" data-toggle="popover"
                                               data-trigger="focus" title="Short Keyword"
                                               data-content="Enter short keyword for your brands"></i>
                                            <span
                                                class="meta_keywords_span_{{ strtolower($language['short_name']) }} counter-p"></span>
                                        </label>
                                        <input type="text"
                                               name="meta_keywords_{{ strtolower($language['short_name']) }}"
                                               id="meta_keywords_{{ strtolower($language['short_name']) }}"
                                               class="form-control" placeholder="Add keywords to your page to help"
                                               style="font-style:italic" data-charcount-enable="true"
                                               data-charcount-textformat="([used] of [max] characters)"
                                               data-charcount-position=".meta_keywords_span_{{ strtolower($language['short_name']) }}"
                                               data-charcount-maxlength="50" maxlength="50" form="brands-form"
                                               value="{{ old($meta_keywords,$model->brand[$meta_keywords]) }}">
                                    </div>
                                    <div class="form-group">
                                        <label class="btn-block">{{ __('backoffice.meta_description') }}<i
                                                class="icon-info pr-1 float-right" tabindex="0" data-toggle="popover"
                                                data-trigger="focus" title=" Short Description"
                                                data-content="Enter short description of your brand."></i>
                                            <span
                                                class="meta_description_span_{{ strtolower($language['short_name']) }} counter-p"></span>
                                        </label>
                                        <textarea class="form-control" rows="6" placeholder="Write meta description"
                                                  style="font-style:italic"
                                                  name="meta_description_{{ strtolower($language['short_name']) }}"
                                                  id="meta_description_{{ strtolower($language['short_name']) }}"
                                                  data-charcount-enable="true"
                                                  data-charcount-textformat="([used] of [max] characters)"
                                                  data-charcount-position=".meta_description_span_{{ strtolower($language['short_name']) }}"
                                                  data-charcount-maxlength="160" maxlength="160"
                                                  form="brands-form">{{ old($meta_description,$model->brand[$meta_description]) }}</textarea>
                                    </div>
                                </div>
                            </div>
                        </div>
                        @php
                            $index++;
                        @endphp
                    @endforeach
                    {{--          @dd($index)--}}
                </div>

                <div class="card p-3 rounded">
                    <div class="custom-control custom-switch" style="padding-left: 36px;">

                        <input type="checkbox" class="custom-control-input" form="brands-form" name="active"
                               id="display-control"
                               value="1" {!! old('active', $model->brand['active'] == 1) ? ' checked' : '' !!}>

                        <label class="custom-control-label"
                               for="display-control">{{ __('backoffice.brand_visibility') }}</label>
                    </div>
                    {{--
                    <label class="mt-3">
                      {{ __('brand.display_on') }} <em style="color:red">*</em>
                    </label>
                    --}}

                </div>

                <div class="card p-3 mt-3 rounded">
                    <label class="mt-3">
                        {{ __('backoffice.more_options') }}
                    </label>

                    <div class="mt-3 border rounded p-3">
                        <div class="custom-control custom-checkbox">
                            <input id="customCheck0400" name="is_featured" form="brands-form" type="checkbox"
                                   class="custom-control-input"
                                   value="1" {!! old('is_featured', $model->brand['is_featured'] == 1) ? ' checked' : '' !!}>
                            <label class="custom-control-label"
                                   for="customCheck0400">{{ __('backoffice.is_featured') }}</label>
                        </div>
                    </div>
                </div>

            </div>
        </div>
        <div class="col-md-12 pb-5" style="padding-left: 22px;">
            <button type="submit" form="brands-form" class="btn btn-primary">{{ $model->button_title }}</button>
            <a href="{{ route('brands.index')}}" class="btn btn-light">{{ __('backoffice.cancel') }}</a>
        </div>
    </div>
@endsection





@section('scripts')


    <script type="text/javascript">
        var image_upload_path = '{{ route("api.upload.brand.image")  }}';
        var form_id = 'brands-form';
        var p_images = JSON.parse('{!! json_encode($model->brand["brands_images"]) !!}');
        let maxFiles = 1;

        $(function () {
            $('.tab-section').click(function () {
                let lang = $(this).data('lang');
                console.log(lang);
                $('.seo-section').removeClass('show');
                $('.seo-section').addClass('d-none');
                $(`#seo-${lang}`).removeClass('d-none').addClass('show');
            });
        });

        $(function () {
            $('.has_seo').click(function () {
                let lang = $(this).data('lang');
                if ($(this).is(':checked')) {
                    $(`.seo-content-${lang}`).removeClass('d-none').addClass('show');
                } else {
                    $(`.seo-content-${lang}`).addClass('d-none').removeClass('show');
                }
            });
        });

        $(document).ready(function () {
            $('[data-toggle="tooltip"]').tooltip();
            $('.select2').select2();
        });

        $(function () {
            $('#brands-form').submit(function () {

                var name = $('#title_en').val();
                $('#name').val(name);

                if ($(this)[0].checkValidity()) {
                    $.ajax({
                        url: $(this).attr('action'),
                        type: $(this).attr('method'),
                        data: $(this).serialize(),
                        success: function (response) {
                            if (response.IsValid) {
                                toastr.success(response.Message, 'Success');
                                setTimeout(() => {
                                    window.location.href = site_url('catalogue/brands');
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
                return false;
            });
        });
    </script>
@endsection
