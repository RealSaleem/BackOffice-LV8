@extends('layouts.backoffice')
@section('content')
    <div class="container-fluid">
        <div class="row">
            <div class="col-sm-12 pl-0 pr-0">
                <div class="greybg1 rounded p-4 mb-3">
                    <div class="row">
                        <div class="col-sm-12">
                            <div class="common_title">
                                <h1>
                                    {{ $model->title }}
                                    <a href="{{ route('category.index') }}"
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
                        <form method="POST" action="{{ $model->route }}" id="category-form">
                            {{csrf_field()}}
                            <input id="name" type="hidden" form="category-form" name="name" value="">
                            @if($model->edit_mode)
                                <input id="category-id" type="hidden" form="category-form" name="id"
                                       value="{{ $model->category['id'] }}">
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
                        {{--@dd($model)--}}
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
                                {{--                @dd($language)--}}
                                @php
                                    $title = 'title_' . strtolower($language['short_name']);
                                @endphp
                                <div id="{{ strtolower($language['name']) }}"
                                     class="tab-pane fade show {{ ($index == 0) ? 'active' : '' }}">
                                    <div class="card-body">

                                        <label>@lang('backoffice.title')
                                            @if($index == 0)
                                                <em style="color: red">*</em>
                                            @endif </label>
                                        <input type="text" id="title_{{ strtolower($language['short_name']) }}"
                                               name="title_{{ strtolower($language['short_name']) }}"
                                               class="form-control" form="category-form"
                                               @if($index==0) @endif placeholder="Add Category Name"
                                               value="{{ old($title,$model->category[$title]) }}">
                                    </div>
                                </div>
                                @php
                                    $index++;
                                @endphp
                            @endforeach
                        </div>
                        <div class="card bg-light mt-4  rounded  border-0">
                            <div class="card-body">
                                <label>
                                    <i class="" tabindex="0" data-toggle="popover" data-trigger="focus"
                                       title="Product Image"
                                       data-content="Select your product Images. Image size should be (120 x 120)"></i>
                                    {{ __('backoffice.add_images') }}
                                </label>
                                <form name="category_images" action="/file-upload" class="dropzone"
                                      id="my-awesome-dropzone" enctype="multipart/form-data">
                                    <div class="fallback">
                                        <input name="file" type="file" style="display: none;">
                                    </div>
                                </form>
                                <div class="hidden">
                                    <div id="hidden-images">
                                        @if(isset($model->category['category_images']))
                                            @php
                                                $index = 0;
                                            @endphp
                                            @foreach($model->category['category_images'] as $image)
                                                <input type="hidden" form="category-form"
                                                       name="images[{{ $index }}][name]" value="{{ $image['name'] }}"/>
                                                <input type="hidden" form="category-form"
                                                       name="images[{{ $index }}][path]" value="{{ $image['url'] }}"/>
                                                <input type="hidden" form="category-form"
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
                        <div class="card bg-light mt-3 rounded border-0">
                            <div class="card-body">
                                <form>
                                    <div class="form-row">
                                        <div class="col-sm-6">
                                            <label>{{  __('backoffice.parent_category') }} </label>
                                            <select class="custom-select select2 form-control" name="parent_id"
                                                    form="category-form">
                                                <option value="">Select</option>
                                                @foreach ($model->categories as $parent_category)
                                                    @php
                                                        $selected = ($parent_category['id'] == $model->category['parent_id']) ? 'selected': '';
                                                    @endphp

                                                    <option value="{{ $parent_category['id'] }}" {{ $selected }}>
                                                        {{ $parent_category->name }}
                                                    </option>
                                                @endforeach
                                            </select>
                                        </div>
                                        <div class="col-sm-6">
                                            <label>{{ __('backoffice.sort_order') }}</label>
                                            <input type="number"
                                                   value="{{ old('sort_order', $model->category['sort_order'] == 1 ? $model->sortOrder : $model->category['sort_order'] ) }}"
                                                   form="category-form" min="1" class="form-control" name="sort_order">
                                        </div>
                                    </div>
                                </form>
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
                                           id="checkbox-{{ strtolower($language['short_name']) }}" form="category-form"
                                           name="{{ $has_seo }}" data-lang="{{ strtolower($language['short_name']) }}"
                                           {{ $model->category[$has_seo] ? 'checked' : '' }} value="1">
                                    <label class="custom-control-label"
                                           for="checkbox-{{ strtolower($language['short_name']) }}">@lang('backoffice.SEO_options')
                                        ( @lang('backoffice.search_engine') )</label>
                                </div>
                                <p class="mt-3 text-secondary">
                                    @lang('backoffice.add_desc_category')
                                </p>
                                <div
                                    class="seo-content-{{ strtolower($language['short_name']) }} {{ $model->category[$has_seo] ? 'show' : 'd-none' }}">
                                    <div class="form-group">
                                        <label class="btn-block">{{ __('backoffice.meta_title') }}
                                            <i class="icon-info pr-1 float-right" tabindex="0" data-toggle="popover"
                                                data-trigger="focus" title="Title for SEO"
                                                data-content="Enter category name"></i>
                                            <span class="meta_title_span_{{ strtolower($language['short_name']) }} counter-p">(0 of 70 characters)</span>
                                        </label>

                                        <input type="text" name="meta_title_{{ strtolower($language['short_name']) }}"
                                               id="meta_title{{ strtolower($language['short_name']) }}"
                                               class="form-control" placeholder="Add your page's meta title"
                                               style="font-style:italic" data-charcount-enable="true"
                                               data-charcount-textformat="([used] of [max] characters)"
                                               data-charcount-position=".meta_title_span_{{ strtolower($language['short_name']) }}"
                                               data-charcount-maxlength="70" maxlength="70" form="category-form"
                                               value="{{ old($meta_title,$model->category[$meta_title]) }}">
                                    </div>
                                    <div class="form-group">
                                        <label class="btn-block">{{ __('backoffice.meta_keywords') }}
                                            <i class="icon-info pr-1 float-right" tabindex="0" data-toggle="popover"
                                               data-trigger="focus" title="Short Keyword"
                                               data-content="Enter short keyword for your category"></i>
                                            <span
                                                class="meta_keywords_span_{{ strtolower($language['short_name']) }} counter-p"></span>
                                        </label>
                                        <input type="text"
                                               name="meta_keywords_{{ strtolower($language['short_name']) }}"
                                               id="meta_keywords{{ strtolower($language['short_name']) }}"
                                               class="form-control" placeholder="Add keywords to your page to help"
                                               style="font-style:italic" data-charcount-enable="true"
                                               data-charcount-textformat="([used] of [max] characters)"
                                               data-charcount-position=".meta_keywords_span_{{ strtolower($language['short_name']) }}"
                                               data-charcount-maxlength="50" maxlength="50" form="category-form"
                                               value="{{ old($meta_keywords,$model->category[$meta_keywords]) }}">
                                    </div>
                                    <div class="form-group">
                                        <label class="btn-block">{{ __('backoffice.meta_description') }}<i
                                                class="icon-info pr-1 float-right" tabindex="0" data-toggle="popover"
                                                data-trigger="focus" title=" Short Description"
                                                data-content="Enter short description of your category."></i>
                                            <span
                                                class="meta_description_span_{{ strtolower($language['short_name']) }} counter-p"></span>
                                        </label>
                                        <textarea class="form-control" rows="6" placeholder="Write meta description"
                                                  style="font-style:italic"
                                                  name="meta_description_{{ strtolower($language['short_name']) }}"
                                                  id="meta_description{{ strtolower($language['short_name']) }}"
                                                  data-charcount-enable="true"
                                                  data-charcount-textformat="([used] of [max] characters)"
                                                  data-charcount-position=".meta_description_span_{{ strtolower($language['short_name']) }}"
                                                  data-charcount-maxlength="160" maxlength="160"
                                                  form="category-form">{{ old($meta_description,$model->category[$meta_description]) }}</textarea>
                                    </div>
                                </div>
                            </div>
                        </div>
                        @php
                            $index++;
                        @endphp
                    @endforeach

                    <div class="card p-3 rounded">
                        <div class="custom-control custom-switch" style="padding-left: 36px;">

                            <input type="checkbox" class="custom-control-input" form="category-form" name="active"
                                   id="display-control"
                                   value="1" {!! old('active', $model->category['active'] == 1) ? ' checked' : '' !!}>

                            <label class="custom-control-label"
                                   for="display-control">{{ __('backoffice.category_visibility') }}</label>
                        </div>
                        <label class="mt-3">
                            {{ __('backoffice.display_on') }} <em style="color:red">*</em>
                        </label>

                        @if($has_pos)
                            <div class="mt-2 border rounded p-3">
                                <div class="custom-control custom-checkbox">
                                    <input id="customCheck1" name="pos_display" type="checkbox" form="category-form"
                                           class="custom-control-input category-control"
                                           value="1" {!! old('pos_display', $model->category['pos_display'] == 1) ? ' checked' : '' !!}>
                                    <label class="custom-control-label text-secondary" for="customCheck1"
                                           style="width:80px">{{ __('backoffice.pos') }}</label>
                                </div>
                            </div>
                        @endif

                        @if($has_dinein || $has_dinein_catalogue)
                            <div class="mt-3 border rounded p-3">
                                <div class="custom-control custom-checkbox">

                                    <input id="customCheck004" name="dinein_display" type="checkbox"
                                           form="category-form" class="custom-control-input category-control"
                                           value="1" {!! old('dinein_display', $model->category['dinein_display'] == 1) ? ' checked' : '' !!}>
                                    <label class="custom-control-label"
                                           for="customCheck004">{{ __('backoffice.catalogue') }}</label>
                                </div>
                            </div>
                        @endif

                        @if($has_website)
                            <div class="mt-3 border rounded p-3">
                                <div class="custom-control custom-checkbox">
                                    <input id="customCheck04" name="web_display" type="checkbox" form="category-form"
                                           class="custom-control-input category-control"
                                           value="1" {!! old('web_display', $model->category['web_display'] == 1) ? ' checked' : '' !!}>
                                    <label class="custom-control-label"
                                           for="customCheck04">{{ __('backoffice.website') }}</label>
                                </div>
                            </div>
                        @endif


                    </div>

                    <div class="card p-3 mt-3 rounded">
                        <label class="mt-3">
                            {{ __('backoffice.more_options') }}
                        </label>

                        <div class="mt-3 border rounded p-3">
                            <div class="custom-control custom-checkbox">
                                <input id="customCheck0400" name="is_featured" type="checkbox" form="category-form"
                                       class="custom-control-input"
                                       value="1" {!! old('is_featured', $model->category['is_featured'] == 1) ? ' checked' : '' !!}>
                                <label class="custom-control-label"
                                       for="customCheck0400">{{ __('backoffice.is_featured') }}</label>
                            </div>
                        </div>
                    </div>

                </div>
            </div>
        </div>
        <div class="col-md-12 pb-5" style="padding-left: 22px;">
            <button type="submit" form="category-form" class="btn btn-primary">{{ $model->button_title }}</button>
            <a href="{{ route('category.index')}}" class="btn btn-light">{{ __('backoffice.cancel') }}</a>
        </div>
    </div>
@endsection

@section('scripts')


    <script type="text/javascript">
        var image_upload_path = '{{ route("api.upload.category.image")  }}';
        var form_id = 'category-form';
        var p_images = JSON.parse('{!! json_encode($model->category["category_images"]) !!}');
        let maxFiles = 1;
        console.log(p_images);
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


            $('#category-form').submit(function () {

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
                                    window.location.href = site_url('catalogue/category');
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

        $(function () {
            $(document).on('click', '#display-control', function () {
                if ($(this).is(':checked')) {
                    $('.category-control').prop('checked', true).trigger('change');
                } else {
                    $('.category-control').prop('checked', false).trigger('change');
                }
            });
        });
    </script>

@endsection
