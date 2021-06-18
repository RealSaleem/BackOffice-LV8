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
                                <a href="{{route('product.index')}}" class="m-b-xs w-auto btn-primary btn-sm pull-right">
                                    {{ __('product.back') }}
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
                    <ul class="nav nav-tabs nav-justified" id="myTab" role="tablist">
                        @php
                        $index = 0;
                        @endphp
                        @foreach($model->languages as $language)
                        <li class="nav-item greybg1">
                            <a class="tab-section nav-link show {{ ($index == 0) ? 'active' : '' }}" data-lang="{{ strtolower($language['name']) }}" data-toggle="tab" href="#{{ strtolower($language['name']) }}" role="tab" aria-selected="false">
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
                        <div id="{{ strtolower($language['name']) }}" class="tab-pane fade show {{ ($index == 0) ? 'active' : '' }}">
                            <div class="card-body">
                                <label>{{ __('product.name') }} @if($index == 0)<em style="color:red">*</em>@endif </label>
                                <input type="text" id="title" name="title_{{ strtolower($language['short_name']) }}" class="form-control" form="product-form" @if($index==0) required @endif />
                            </div>
                            <div class="col-md-12">
                                <div class="form-group">
                                    <label for="exampleInputEmail1">{{ __('product.description') }} @if($index == 0)<em style="color:red">*</em>@endif </label>
                                    <textarea name=" description_{{ strtolower($language['short_name']) }}" id="editor-{{ strtolower($language['name']) }}" class="form-control rounded product_description" form="product-form" rows="15"></textarea>
                                </div>
                            </div>
                        </div>
                        @php
                        $index++;
                        @endphp
                        @endforeach
                    </div>
                </div>
                <div class="col-md-12">

                    <div class="card bg-light mt-4  rounded  border-0">
                        <div class="card-body">
                            <label>
                                <i class="icon-info pr-1" tabindex="0" data-toggle="popover" data-trigger="focus" title="Product Image" data-content="Select your product Images. Image size should be (120 x 120)"></i>
                                {{ __('product.add_images') }}
                            </label>
                            <div class="">
                                <form name="product_images" action="/file-upload" class="dropzone" id="my-awesome-dropzone" enctype="multipart/form-data">
                                    <div class="fallback" required>
                                        <input name="file" type="file" style="display: none;">
                                    </div>
                                </form>
                                <div class="hidden">
                                    <div id="hidden-images">
                                        @php
                                        $index = 0;
                                        @endphp

                                        @if(is_array($model->product['product_images']) && sizeof($model->product['product_images'])>0)
                                        @foreach($model->product['product_images'] as $image)
                                        <input type="hidden" form="product-form" name="images[{{ $index }}][name]" value="{{ $image['name'] }}" />
                                        <input type="hidden" form="product-form" name="images[{{ $index }}][path]" value="{{ $image['url'] }}" />
                                        <input type="hidden" form="product-form" name="images[{{ $index }}][size]" value="{{ $image['size'] }}" />
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


                    <div class="col-md-12">
                        <div class="tabs">
                            <ul class="nav nav-tabs row-eq-height  mt-4" id="myTab" role="tablist">

                                <li class="nav-item mt-3  mt-md-0">
                                    <a class="active nav-link bg-light mr-3 rounded pt-4 text-center" for="standard_product" id="home-tab" data-toggle="tab" href="#home" role="tab" aria-controls="home" aria-selected="true">
                                        <i class="icon-check"></i>
                                        <h3>{{ __('product.standard_product') }}</h3>
                                        <p class="text-muted">{{ __('product.standard_product_message') }}</p>
                                        <input id="standard_product" name="is_combo" class="d-none" type="radio" form="product-form" value="0" checked="checked">
                                    </a>
                                </li>
                                <li class="nav-item  mt-3  mt-md-0">
                                    <a class="nav-link bg-light mr-3 rounded pt-4 text-center" for="variant_product" id="profile-tab" data-toggle="tab" href="#profile" role="tab" aria-controls="profile" aria-selected="false">
                                        <i class="icon-check"></i>
                                        <h3>{{ __('product.variant_product') }}</h3>
                                        <p class="text-muted">{{ __('product.variant_product_message') }}</p>
                                        <input id="variant_product" name="is_combo" class="d-none" value="-1" type="radio" class="variant_product" form="product-form" @if($model->edit_mode) @if($model->product['is_composite'] == 1) disabled @endif @endif >
                                    </a>
                                </li>
                                <li class="nav-item  mt-3  mt-md-0">
                                    <a class="nav-link bg-light rounded pt-4 text-center" for="combo_product" id="contact-tab" data-toggle="tab" href="#contact" role="tab" aria-controls="contact" aria-selected="false">
                                        <i class="icon-check"></i>
                                        <h3>{{ __('product.composite_product') }}</h3>
                                        <p class="text-muted">{{ __('product.composite_product_message') }}</p>
                                        <input id="combo_product" name="is_combo" class="d-none" value="1" type="radio" class="composite" form="product-form" {!! old('is_combo', $model->product['is_composite'] == 1 ) ? 'checked ' : '' !!} />
                                    </a>
                                </li>

                            </ul>
                            <div class="tab-content" id="myTabContent">

                                <div class="mt-5">
                                    <label>
                                        <i class="icon-info pr-1" tabindex="0" data-toggle="popover" data-trigger="focus" title="" data-content="And here's some amazing content. It's very engaging. Right?" data-original-title="Dismissible popover"></i>
                                        {{ __('product.stock_keeping_unit') }}<em style="color:red">*</em>
                                    </label>
                                    <div class="btn-block">
                                        <div class="custom-control radiopaddiing  rounded custom-radio custom-control-inline" style="padding-right: 25px; padding-left: 40px;">
                                            <input id="auto_sku" name="sku_type" class="custom-control-input" type="radio" form="product-form" value='1' {--!! old('sku_type', $sku==$sku) ? ' checked' : '' !!--}>
                                            <label class="custom-control-label" for="auto_sku"> {{ __('product.auto_generate_sku') }}</label>
                                        </div>
                                        <div class="custom-control radiopaddiing bg-light  rounded custom-radio custom-control-inline mt-3  mt-md-0" style="padding-left: 35px;">
                                            <input id="sku_custom" form="product-form" type="radio" class="custom-control-input p-4" name="sku_type" value='0' {{-- $model->product['sku_type'] == 'custom' ? 'checked' : '' --}}{{--!! old('sku_type', $model->product['sku'] ) ? 'checked ' : '' !!--}}>
                                            <label class="custom-control-label " for="sku_custom"> {{ __('product.custom_sku') }}</label>
                                        </div>
                                    </div>
                                    <input type="text" class="form-control mt-3" placeholder="Ex. 1000 for FGK5485218">
                                </div>

                                <div class="tab-pane fade show active" id="home" role="tabpanel" aria-labelledby="home-tab">

                                    <div class="card bg-light mt-3  rounded  border-0">
                                        <hr>
                                        <div class=" pt-0">
                                            <form>
                                                <div class="form-row pt-0">
                                                    <div class="col-sm-12 pl-0">
                                                        <label><i class="icon-info pr-1" tabindex="0" data-toggle="popover" data-trigger="focus" title="Dismissible popover" data-content="And here's some amazing content. It's very engaging. Right?"></i> Pricing</label>
                                                    </div>
                                                    <div class="col-sm-6">
                                                        <label>Retail Price <em>*</em></label>
                                                        <input type="email" class="form-control" placeholder="Enter Price">
                                                    </div>
                                                    <div class="col-sm-6">
                                                        <label>Before Discount Price</label>
                                                        <input type="email" class="form-control" placeholder="Enter Price">
                                                    </div>
                                                </div>
                                                <div class="form-row mt-3">
                                                    <div class="col-sm-12">
                                                        <label><i class="icon-info pr-1" tabindex="0" data-toggle="popover" data-trigger="focus" title="Dismissible popover" data-content="And here's some amazing content. It's very engaging. Right?"></i> Branches</label>
                                                        <span class="btn-block">Gulshan</span>
                                                    </div>
                                                    <div class="col-sm-3">
                                                        <input type="email" class="form-control mt-3  mt-md-0" placeholder="Quantity">
                                                    </div>
                                                    <div class="col-sm-3">
                                                        <input type="email" class="form-control mt-3  mt-md-0" placeholder="Re-Order Pt">
                                                    </div>
                                                    <div class="col-sm-3">
                                                        <input type="email" class="form-control mt-3  mt-md-0" placeholder="Qty Re-Order">
                                                    </div>
                                                    <div class="col-sm-3">
                                                        <input type="email" class="form-control mt-3  mt-md-0" placeholder="Supply Price">
                                                    </div>
                                                </div>
                                                <div class="row no-gutters mt-3">
                                                    <div class="col-sm-2">
                                                        <div class="input-group">
                                                            <input type="email" class="form-control border-0 bg-primary text-white" value="KD0">
                                                            <input type="email" class="form-control border-0 bg-secondary text-white" value="%">
                                                        </div>
                                                    </div>
                                                    <div class="col-sm-12 mt-3">
                                                        <div class="custom-control custom-checkbox">
                                                            <input type="checkbox" class="custom-control-input" id="customCheck1">
                                                            <label class="custom-control-label text-secondary" for="customCheck1">Allow customer to purchase this product when it is out of stock</label>
                                                        </div>
                                                    </div>
                                                </div>
                                            </form>
                                        </div>
                                    </div>
                                </div>

                                <div class="tab-pane fade" id="profile" role="tabpanel" aria-labelledby="profile-tab">

                                </div>

                                <div class="tab-pane fade" id="contact" role="tabpanel" aria-labelledby="contact-tab">
                                    <!-- Stock Unite -->
                                    <div class="card bg-light mt-3  rounded  border-0">
                                        <div class="card-body">
                                            <div class="table-responsive">
                                                <table class="table  table-striped mt-4">
                                                    <tbody>
                                                        <tr class="rounded">
                                                            <td>Chicken</td>
                                                            <td><input type="text" class="form-control" name=""></td>
                                                            <td class="align-middle"><span class="badge rounded badge-warning p-2 text-white">23 Available</span></td>
                                                            <td class="align-middle"><span class="badge rounded badge-info p-2 text-white">KD 53</span></td>
                                                            <td class="align-middle"><a href="#"><i class="icon-trash text-warning"></i></a></td>
                                                        </tr>
                                                        <tr>
                                                            <td>Chicken</td>
                                                            <td><input type="text" class="form-control" name=""></td>
                                                            <td class="align-middle"><span class="badge rounded badge-warning p-2 text-white">23 Available</span></td>
                                                            <td class="align-middle"><span class="badge rounded badge-info p-2 text-white">KD 53</span></td>
                                                            <td class="align-middle"><a href="#"><i class="icon-trash text-warning"></i></a></td>
                                                        </tr>
                                                        <tr class="rounded">
                                                            <td>Chicken</td>
                                                            <td><input type="text" class="form-control" name=""></td>
                                                            <td class="align-middle"><span class="badge rounded badge-warning p-2 text-white">23 Available</span></td>
                                                            <td class="align-middle"><span class="badge rounded badge-info p-2 text-white">KD 53</span></td>
                                                            <td class="align-middle"><a href="#"><i class="icon-trash text-warning"></i></a></td>
                                                        </tr>
                                                    </tbody>
                                                </table>
                                            </div>
                                            <p class="text-right mt-3">Total Items price: <b>kd 5478</b></p>
                                        </div>
                                    </div>
                                    <div class="card bg-light mt-3  rounded  border-0">
                                        <div class="card-body">
                                            <form>
                                                <div class="form-row">
                                                    <div class="col-sm-12">
                                                        <label><i class="icon-info pr-1" tabindex="0" data-toggle="popover" data-trigger="focus" title="Dismissible popover" data-content="And here's some amazing content. It's very engaging. Right?"></i> Pricing</label>
                                                    </div>
                                                    <div class="col-sm-6">
                                                        <label>Retail Price <em>*</em></label>
                                                        <input type="email" class="form-control" placeholder="Enter Price">
                                                    </div>
                                                    <div class="col-sm-6">
                                                        <label>Before Discount Price</label>
                                                        <input type="email" class="form-control" placeholder="Enter Price">
                                                    </div>
                                                </div>
                                                <div class="form-row mt-3">
                                                    <div class="col-sm-12">
                                                        <label><i class="icon-info pr-1" tabindex="0" data-toggle="popover" data-trigger="focus" title="Dismissible popover" data-content="And here's some amazing content. It's very engaging. Right?"></i> Branches</label>
                                                        <span class="btn-block">Gulshan</span>
                                                    </div>
                                                    <div class="col-sm-3">
                                                        <input type="email" class="form-control" placeholder="Quantity">
                                                    </div>
                                                    <div class="col-sm-3">
                                                        <input type="email" class="form-control" placeholder="Re-Order Pt">
                                                    </div>
                                                    <div class="col-sm-3">
                                                        <input type="email" class="form-control" placeholder="Qty Re-Order">
                                                    </div>
                                                    <div class="col-sm-3">
                                                        <input type="email" class="form-control" placeholder="Supply Price">
                                                    </div>
                                                </div>
                                                <div class="row no-gutters mt-3">
                                                    <div class="col-sm-1">
                                                        <input type="email" class="form-control border-0 bg-primary text-white" value="KD0">
                                                    </div>
                                                    <div class="col-sm-1">
                                                        <input type="email" class="form-control border-0 bg-secondary text-white" value="%">
                                                    </div>
                                                    <div class="col-sm-12 mt-3">
                                                        <div class="custom-control custom-checkbox">
                                                            <input type="checkbox" class="custom-control-input" id="customCheck1">
                                                            <label class="custom-control-label text-secondary" for="customCheck1">Allow customer to purchase this product when it is out of stock</label>
                                                        </div>
                                                    </div>
                                                </div>
                                            </form>
                                        </div>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
        <div class="col-sm-4 ">
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
                <div id="seo-{{ strtolower($language['name']) }}" class="seo-section {{ ($index == 0) ? 'show' : 'd-none' }}">
                    <div class="rounded">
                        <div class="custom-control custom-checkbox">
                            <input type="checkbox" class="custom-control-input has_seo" id="checkbox-{{ strtolower($language['short_name']) }}" form="product-form" name="{{ $has_seo }}" data-lang="{{ strtolower($language['short_name']) }}" {{ $model->product[$has_seo] ? 'checked' : '' }} value="1">
                            <label class="custom-control-label" for="checkbox-{{ strtolower($language['short_name']) }}">@lang('site.SEO_options')
                                ( @lang('site.search_engine') )</label>
                        </div>
                        <p class="mt-3 text-secondary">
                            @lang('site.add_desc_product')
                        </p>
                        <div class="seo-content-{{ strtolower($language['short_name']) }} {{ $model->product[$has_seo] ? 'show' : 'd-none' }}">
                            <div class="form-group">
                                <label class="btn-block">{{ __('product.meta_title') }}<i class="icon-info pr-1 float-right" tabindex="0" data-toggle="popover" data-trigger="focus" title="Title for SEO" data-content="Enter product name"></i>
                                    <span class="meta_title_span_{{ strtolower($language['short_name']) }} counter-p">(0 of 70 characters)</span>
                                </label>
                                <input type="text" name="meta_title_{{ strtolower($language['short_name']) }}" id="meta_title_{{ strtolower($language['short_name']) }}" class="form-control" style="font-style:italic" data-charcount-enable="true" data-charcount-textformat="([used] of [max] characters)" data-charcount-position=".meta_title_span_{{ strtolower($language['short_name']) }}" data-charcount-maxlength="70" maxlength="70" form="product-form" value="{{ old($meta_title,$model->product[$meta_title]) }}">
                            </div>
                            <div class="form-group">
                                <label class="btn-block">{{ __('product.meta_keywords') }}
                                    <i class="icon-info pr-1 float-right" tabindex="0" data-toggle="popover" data-trigger="focus" title="Short Keyword" data-content="Enter short keyword for your product"></i>
                                    <span class="meta_keywords_span_{{ strtolower($language['short_name']) }} counter-p"></span>
                                </label>
                                <input type="text" name="meta_keywords_{{ strtolower($language['short_name']) }}" id="meta_keywords_{{ strtolower($language['short_name']) }}" class="form-control" style="font-style:italic" data-charcount-enable="true" data-charcount-textformat="([used] of [max] characters)" data-charcount-position=".meta_keywords_span_{{ strtolower($language['short_name']) }}" data-charcount-maxlength="50" maxlength="50" form="product-form" value="{{ old($meta_keywords,$model->product[$meta_keywords]) }}">
                            </div>
                            <div class="form-group">
                                <label class="btn-block">{{ __('product.meta_description') }}<i class="icon-info pr-1 float-right" tabindex="0" data-toggle="popover" data-trigger="focus" title=" Short Description"></i>
                                    <span class="meta_description_span_{{ strtolower($language['short_name']) }} counter-p"></span>
                                </label>
                                <textarea class="form-control" rows="6" style="font-style:italic" name="meta_description_{{ strtolower($language['short_name']) }}" id="meta_description_{{ strtolower($language['short_name']) }}" data-charcount-enable="true" data-charcount-textformat="([used] of [max] characters)" data-charcount-position=".meta_description_span_{{ strtolower($language['short_name']) }}" data-charcount-maxlength="160" maxlength="160" form="product-form">{{ old($meta_description,$model->product[$meta_description]) }}</textarea>
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

                        <input type="checkbox" class="custom-control-input" name="active" id="display-control" form="product-form" value="1" {!! old('active', $model->product['active'] == 1) ? ' checked' : '' !!}>

                        <label class="custom-control-label" for="display-control">{{ __('product.product_visibility') }}</label>
                    </div>
                    <label class="mt-3">
                        {{ __('product.display_on') }} <em style="color:red">*</em>
                    </label>

                    @if($has_pos)
                    <div class="mt-2 border rounded p-3">
                        <div class="custom-control custom-checkbox">

                            <input id="customCheck0004" name="is_featured" type="checkbox" class="custom-control-input product-control" form="product-form" value="1" {!! old('is_featured', $model->product['is_featured']) ? ' checked' : '' !!}>

                            <label class="custom-control-label" for="customCheck0004">{{ __('product.pos') }}</label>
                        </div>
                    </div>
                    @endif

                    @if($has_website)
                    <div class="mt-3 border rounded p-3">
                        <div class="custom-control custom-checkbox">
                            <input id="customCheck04" name="web_display" type="checkbox" class="custom-control-input product-control" form="product-form" value="1" {!! old('web_display', $model->product['web_display'] == 1) ? ' checked' : '' !!}>
                            <label class="custom-control-label" for="customCheck04">{{ __('product.website') }}</label>
                        </div>
                    </div>
                    @endif

                    @if($has_dinein || $has_dinein_catalogue)
                    <div class="mt-3 border rounded p-3">
                        <div class="custom-control custom-checkbox">

                            <input id="customCheck004" name="dinein_display" type="checkbox" class="custom-control-input product-control" form="product-form" value="1" {!! old('dinein_display', $model->product['dinein_display'] == 1) ? ' checked' : '' !!}>
                            <label class="custom-control-label" for="customCheck004">{{ __('product.catalogue') }}</label>
                        </div>
                    </div>
                    @endif


                </div>

                <div class="card p-3 mt-3 rounded">
                    <label class="mt-3">
                        {{ __('product.other_options') }}
                    </label>
                    <label class="mt-2">
                        {{ __('product.choose_category') }}<em style="color:red">*</em>
                    </label>
                    <select class="form-control  input-md select2" id="category_ids" name="category_id[]" form="product-form" multiple required>
                        @foreach ($model->categories as $category)
                        <option value="{{ $category->id }}" {{ (in_array($category->id, $model->product['category_ids'] )) ? "selected" : "" }}>
                            {{ $category->name }}
                        </option>
                        @endforeach
                    </select>

                    <label class="mt-2">
                        {{ __('product.choose_brand') }}<em style="color:red">*</em>
                    </label>
                    <select class="form-control input-md select2" name="brand_id" form="product-form" id="brand_id" required>
                        @foreach ($model->brands as $brand)
                        <option value="{{ $brand->id }}" {{ ($model->product['brand_id'] == $brand->id) ? "selected" : "" }}>{{ $brand->name }}</option>
                        @endforeach
                    </select>

                    <label class="mt-2">
                        {{ __('product.choose_supplier') }}<em style="color:red">*</em>
                    </label>
                    <select class="form-control input-md select2" name="supplier_id[]" form="product-form" multiple id="supplier_id" {{ !$model->edit_mode ? 'required' : '' }}>
                        @foreach ($model->suppliers as $supplier)
                        <option value="{{ $supplier->id }}" {{ (in_array($supplier->id, $model->product['supplier_ids'] )) ? "selected" : "" }}>{{ $supplier->name }}</option>
                        @endforeach
                    </select>

                    <label class="mt-2">
                        {{ __('product.choose_related_products') }}<em style="color:red">*</em>
                    </label>

                    <select class="form-control input-md select2" id="related_products" name="related_id[]" multiple="multiple" form="product-form">
                        @foreach ($model->related_products as $related)
                        <option value="{{ $related['id'] }}" {{ (in_array($related['id'], $model->product['related_ids'] )) ? "selected" : "" }}>{{ $related['name'] }}</option>
                        @endforeach
                    </select>

                    <label class="mt-2">
                        {{ __('product.choose_addons') }}<em style="color:red">*</em>
                    </label>
                    <select class="form-control input-md select2" id="product_addons" name="add_ons[]" multiple="multiple" form="product-form">
                        @foreach ($model->addons as $add_on_item)
                        <option value="{{ $add_on_item['id'] }}" {{ (in_array($add_on_item['id'], $model->product['add_ons_related'] )) ? "selected" : "" }}>{{ $add_on_item['name'] }}</option>
                        @endforeach
                    </select>

                    <label class="mt-2">
                        {{ __('product.choose_options') }}<em style="color:red">*</em>
                    </label>
                    <select class="form-control input-md select2" id="product_options" name="add_ons[]" multiple="multiple" form="product-form">
                        @foreach ($model->options as $option)
                        <option value="{{ $option['id'] }}" {{ (in_array($option['id'], $model->product['add_ons_related'] )) ? "selected" : "" }}>{{ $option['name'] }}</option>
                        @endforeach
                    </select>
                </div>

                <div class="card p-3 mt-3 rounded">
                    <label class="mt-3">
                        {{ __('product.more_options') }}
                    </label>

                    <div class="mt-3 border rounded p-3">
                        <div class="custom-control custom-checkbox">
                            <input id="customCheck0400" name="allow_subscription" type="checkbox" class="custom-control-input" form="product-form" value="1" {!! old('allow_subscription', $model->product['allow_subscription'] == 1) ? ' checked' : '' !!}>
                            <label class="custom-control-label" for="customCheck0400">{{ __('product.allow_subscription') }}</label>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
    <form method="POST" action="{{ url('brand.store') }}" id="brand-form">
        {{csrf_field()}}
        <input id="name" type="hidden" form="brand-form" name="name" value="">
        @if($model->edit_mode)
        <input name="_method" type="hidden" value="PUT">
        @endif
    </form>
</div>
<div class="col-md-12 pb-5" style="padding-left: 33px;">
    <button type="submit" form="product-form" class="btn btn-primary">Save</button>
    <a href="{{ route('product.index')}}" class="btn btn-light">Cancel</a>
</div>
@endsection
@section('scripts')
<script src="{{CustomUrl::asset('js/cropper.min.js') }}"></script>
<script src="https://cdn.ckeditor.com/4.10.1/standard/ckeditor.js"></script>
<script src="{{CustomUrl::asset('js/charactercount/jquery.character-counter.js') }}"></script>
<script src="{{CustomUrl::asset('js/cropper.min.js') }}"></script>
<script src="{{CustomUrl::asset('js/dropzone.js') }}"></script>
<script type="text/javascript">
    $(document).ready(function() {
        $('.product_description').each(function(e) {
            CKEDITOR.replace($(this).attr('id'));
        });
        $('[data-toggle="tooltip"]').tooltip();


        $('#category_ids').select2();
        $('#brand_id').select2();
        $('#supplier_id').select2();
        $('#related_products').select2();
        $('#product_addons').select2();
        $('#product_options').select2();



    });

    $(function() {
        $(document).on('click', '#display-control', function() {
            if ($(this).is(':checked')) {
                $('.product-control').prop('checked', true).trigger('change');
            } else {
                $('.product-control').prop('checked', false).trigger('change');
            }
        });
    });

    $(function() {
        $('.tab-section').click(function() {
            let lang = $(this).data('lang');
            console.log(lang);
            $('.seo-section').removeClass('show');
            $('.seo-section').addClass('d-none');
            $(`#seo-${lang}`).removeClass('d-none').addClass('show');
        });
    });

    $(function() {
        $('.has_seo').click(function() {
            let lang = $(this).data('lang');
            if ($(this).is(':checked')) {
                $(`.seo-content-${lang}`).removeClass('d-none').addClass('show');
            } else {
                $(`.seo-content-${lang}`).addClass('d-none').removeClass('show');
            }
        });
    });

    $(function() {
        $('#product-form').submit(function() {

            var name = $('#title').val();
            $('#name').val(name);

            if ($(this)[0].checkValidity()) {
                $.ajax({
                    url: $(this).attr('action'),
                    type: $(this).attr('method'),
                    data: $(this).serialize(),
                    success: function(response) {
                        if (response.IsValid) {
                            toastr.success(response.Message, 'Success');
                            setTimeout(() => {
                                window.location.href = site_url('catalogue/product');
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




    var image_upload_path = site_url('product/image/upload');
    var form_id = 'product-form';
    var p_images = '';
    p_images = JSON.parse('{!! json_encode($model->product["product_images"]) !!}');
</script>
<script src="{{ CustomUrl::asset('js/my-dropzone.js') }}" type="text/javascript"></script>
@endsection
