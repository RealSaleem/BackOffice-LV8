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
            <div id="seo-{{ strtolower($language['name']) }}"
                 class="seo-section {{ ($index == 0) ? 'show' : 'd-none' }}">
                <div class="rounded">
                    <div class="custom-control custom-checkbox">
                        <input type="checkbox" class="custom-control-input has_seo"
                               id="checkbox-{{ strtolower($language['short_name']) }}" form="product-form"
                               name="{{ $has_seo }}" data-lang="{{ strtolower($language['short_name']) }}"
                               {{ $model->product[$has_seo] ? 'checked' : '' }} value="1">
                        <label class="custom-control-label"
                               for="checkbox-{{ strtolower($language['short_name']) }}">@lang('backoffice.SEO_options')
                            ( @lang('backoffice.search_engine') )</label>
                    </div>
                    <p class="mt-3 text-secondary">
                        @lang('backoffice.add_desc_product')
                    </p>
                    <div
                        class="seo-content-{{ strtolower($language['short_name']) }} {{ $model->product[$has_seo] ? 'show' : 'd-none' }}">
                        <div class="form-group">
                            <label class="btn-block">{{ __('product.meta_title') }}<i class="icon-info pr-1 float-right"
                                                                                      tabindex="0" data-toggle="popover"
                                                                                      data-trigger="focus"
                                                                                      title="Title for SEO"
                                                                                      data-content="Enter product name"></i>
                                <span class="meta_title_span_{{ strtolower($language['short_name']) }} counter-p">(0 of 70 characters)</span>
                            </label>
                            <input type="text" name="meta_title_{{ strtolower($language['short_name']) }}"
                                   id="meta_title_{{ strtolower($language['short_name']) }}" class="form-control"
                                   style="font-style:italic" data-charcount-enable="true"
                                   data-charcount-textformat="([used] of [max] characters)"
                                   data-charcount-position=".meta_title_span_{{ strtolower($language['short_name']) }}"
                                   data-charcount-maxlength="70" maxlength="70" form="product-form"
                                   value="{{ old($meta_title,$model->product[$meta_title]) }}">
                        </div>
                        <div class="form-group">
                            <label class="btn-block">{{ __('backoffice.meta_keywords') }}
                                <i class="icon-info pr-1 float-right" tabindex="0" data-toggle="popover"
                                   data-trigger="focus" title="Short Keyword"
                                   data-content="Enter short keyword for your product"></i>
                                <span
                                    class="meta_keywords_span_{{ strtolower($language['short_name']) }} counter-p"></span>
                            </label>
                            <input type="text" name="meta_keywords_{{ strtolower($language['short_name']) }}"
                                   id="meta_keywords_{{ strtolower($language['short_name']) }}" class="form-control"
                                   style="font-style:italic" data-charcount-enable="true"
                                   data-charcount-textformat="([used] of [max] characters)"
                                   data-charcount-position=".meta_keywords_span_{{ strtolower($language['short_name']) }}"
                                   data-charcount-maxlength="50" maxlength="50" form="product-form"
                                   value="{{ old($meta_keywords,$model->product[$meta_keywords]) }}">
                        </div>
                        <div class="form-group">
                            <label class="btn-block">{{ __('backoffice.meta_description') }}<i
                                    class="icon-info pr-1 float-right" tabindex="0" data-toggle="popover"
                                    data-trigger="focus" title=" Short Description"></i>
                                <span
                                    class="meta_description_span_{{ strtolower($language['short_name']) }} counter-p"></span>
                            </label>
                            <textarea class="form-control" rows="6" style="font-style:italic"
                                      name="meta_description_{{ strtolower($language['short_name']) }}"
                                      id="meta_description_{{ strtolower($language['short_name']) }}"
                                      data-charcount-enable="true"
                                      data-charcount-textformat="([used] of [max] characters)"
                                      data-charcount-position=".meta_description_span_{{ strtolower($language['short_name']) }}"
                                      data-charcount-maxlength="160" maxlength="160"
                                      form="product-form">{{ old($meta_description,$model->product[$meta_description]) }}</textarea>
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

                <input type="checkbox" class="custom-control-input" name="active" id="display-control"
                       form="product-form"
                       value="1" {!! old('active', $model->product['active'] == 1) ? ' checked' : '' !!}>

                <label class="custom-control-label"
                       for="display-control">{{ __('backoffice.product_visibility') }}</label>
            </div>
            <label class="mt-3">
                {{ __('backoffice.display_on') }}
            </label>

            @if($has_pos)
                <div class="mt-2 border rounded p-3">
                    <div class="custom-control custom-checkbox">

                        <input id="customCheck0004" name="is_featured" type="checkbox"
                               class="custom-control-input product-control" form="product-form"
                               value="1" {!! old('is_featured', $model->product['is_featured']) ? ' checked' : '' !!}>

                        <label class="custom-control-label" for="customCheck0004">{{ __('backoffice.pos') }}</label>
                    </div>
                </div>
            @endif

            @if($has_dinein || $has_dinein_catalogue)
                <div class="mt-3 border rounded p-3">
                    <div class="custom-control custom-checkbox">

                        <input id="customCheck004" name="dinein_display" type="checkbox"
                               class="custom-control-input product-control" form="product-form"
                               value="1" {!! old('dinein_display', $model->product['dinein_display'] == 1) ? ' checked' : '' !!}>
                        <label class="custom-control-label"
                               for="customCheck004">{{ __('backoffice.catalogue') }}</label>
                    </div>
                </div>
            @endif

            @if($has_website)
                <div class="mt-3 border rounded p-3">
                    <div class="custom-control custom-checkbox">
                        <input id="customCheck04" name="web_display" type="checkbox"
                               class="custom-control-input product-control" form="product-form"
                               value="1" {!! old('web_display', $model->product['web_display'] == 1) ? ' checked' : '' !!}>
                        <label class="custom-control-label" for="customCheck04">{{ __('backoffice.website') }}</label>
                    </div>
                </div>
            @endif


        </div>

        <div class="card p-3 mt-3 rounded">
            <label class="mt-3">
                {{ __('backoffice.options') }}
            </label>
            <div class="d-flex justify-content-between">
                <label class="mt-2">
                    {{ __('backoffice.choose_category') }}<em style="color:red">*</em>
                </label>
                <label class="mt-2"><a href="" data-toggle="modal"
                                       data-target="#categoryModal">+ {{ __('backoffice.add') }}</a></label>
            </div>
            <select class="form-control  input-md select2" id="category_ids" name="category_id[]" form="product-form"
                    multiple required>
                @foreach ($model->categories as $category)
                    <option
                        value="{{ $category->id }}" {{ in_array($category->id, $model->product['category_ids'] ) ? "selected" : "" }}>
                        {{ $category->name }}
                    </option>
                @endforeach
            </select>

            @if(!$model->edit_mode || ($model->edit_mode && $model->product['is_composite'] == 0))

                <div class="d-flex justify-content-between mt-3">
                    <label class="mt-2">
                        {{ __('backoffice.choose_brand') }}<em class="combo-toggle" style="color:red">*</em>
                    </label>
                    <label class="mt-2 combo-toggle"><a href="" data-toggle="modal"
                                                        data-target="#brandModal">+ {{ __('backoffice.add') }}</a></label>
                </div>
                <select class="form-control input-md select2" name="brand_id[]" multiple form="product-form"
                        id="brand_id" required>
                    @foreach ($model->brands as $brand)
                        <option
                            value="{{ $brand->id }}" {{ ($model->product['brand_id'] == $brand->id) ? "selected" : "" }}>{{ $brand->name }}</option>
                    @endforeach
                </select>
                <div class="d-flex justify-content-between mt-3">
                    <label class="mt-2">
                        {{ __('backoffice.choose_supplier') }}<em class="combo-toggle" style="color:red">*</em>
                    </label>
                    <label class="mt-2 combo-toggle"><a href="" data-toggle="modal"
                                                        data-target="#supplierModal">+ {{ __('backoffice.add') }}</a></label>
                </div>
                <select class="form-control input-md select2" name="supplier_id[]" form="product-form" multiple
                        id="supplier_id" {{ !$model->edit_mode ? 'required' : '' }}>
                    @foreach ($model->suppliers as $supplier)
                        <option
                            value="{{ $supplier['id'] }}" {{ (in_array($supplier['id'], $model->product['supplier_ids'] )) ? "selected" : "" }}>{{ $supplier['name'] }}</option>
                    @endforeach
                </select>


                <div class="d-flex justify-content-between mt-3">
                    <label class="mt-2">
                        {{ __('backoffice.choose_addons') }}
                    </label>
                    <label class="mt-2 combo-toggle"><a href="" data-toggle="modal"
                                                        data-target="#addonModal">+ {{ __('backoffice.add') }}</a></label>
                </div>
                <select class="form-control input-md select2" name="addon_id[]" form="product-form" multiple
                        id="addon_id" {{ !$model->edit_mode ? 'required' : '' }}>
                    @foreach ($model->addons as $addon)
                        <option
                            value="{{ $addon['id'] }}" {{($model->product['brand_id'] == $addon['id']) ? "selected" : "" }} >{{ $addon['name'] }}</option>
                    @endforeach
                </select>


                <div class="d-flex justify-content-between mt-3">
                    <label class="mt-2">
                        {{ __('backoffice.choose_options') }}
                    </label>
                    <label class="mt-2 combo-toggle"><a href="" data-toggle="modal"
                                                        data-target="#optionModal">+ {{ __('backoffice.add') }}</a></label>
                </div>
                <select class="form-control input-md select2" name="option_id[]" form="product-form" multiple
                        id="option_id" {{ !$model->edit_mode ? 'required' : '' }}>
                    @foreach ($model->options as $option)
                        <option
                            value="{{ $option['id'] }}" {{ ($model->product['brand_id'] == $option['id']) ? "selected" : "" }} >{{ $option['name'] }}</option>
                    @endforeach
                </select>



            @endif
        </div>


        <div class="card p-3 mt-3 rounded">
            <label class="mt-3">
                {{ __('backoffice.other_options') }}
            </label>
            <div class="d-flex justify-content-between mt-3">
                <label class="mt-2">
                    {{ __('backoffice.choose_related_products') }}
                </label>

            </div>

            <select class="form-control input-md select2" id="related_products" name="related_id[]" multiple="multiple"
                    form="product-form">
                @foreach ($model->related_products as $related)
                    <option
                        value="{{ $related['id'] }}" {{ (in_array($related['id'], $model->product['related_ids'] )) ? "selected" : "" }}>{{ $related['name'] }}</option>
                @endforeach
            </select>

            @if($model->has_addons)
                <div class="d-flex justify-content-between mt-3">
                    <label class="mt-2">
                        {{ __('backoffice.choose_addons') }}
                    </label>
                    <label class="mt-2"><a href="" data-toggle="modal"
                                           data-target="#addonModal">+ {{ __('backoffice.add') }}</a></label>
                </div>
                <select class="form-control input-md select2" id="product_addons" name="add_ons[]" multiple="multiple"
                        form="product-form">
                    @foreach ($model->addons as $add_on_item)
                        <option
                            value="{{ $add_on_item['id'] }}" {{ (in_array($add_on_item['id'], $model->product['addon_ids'] )) ? "selected" : "" }}>{{ $add_on_item['name'] }}</option>
                    @endforeach
                </select>

                <div class="d-flex justify-content-between mt-3">
                    <label class="mt-2">
                        {{ __('backoffice.choose_options') }}
                    </label>
                    <label class="mt-2"><a href="" data-toggle="modal"
                                           data-target="#optionModal">+ {{ __('backoffice.add') }}</a></label>
                </div>
                <select class="form-control input-md select2" id="product_options" name="add_ons[]" multiple="multiple"
                        form="product-form">
                    @foreach ($model->options as $option)
                        <option
                            value="{{ $option['id'] }}" {{ (in_array($option['id'], $model->product['option_ids'] )) ? "selected" : "" }}>{{ $option['name'] }}</option>
                    @endforeach
                </select>

            @endif
        </div>

        <div class="card p-3 mt-3 rounded">
            <label class="mt-3">
                {{ __('backoffice.more_options') }}
            </label>

            <div class="mt-3 border rounded p-3">
                <div class="custom-control custom-checkbox">
                    <input id="customCheck0400" name="allow_subscription" type="checkbox" class="custom-control-input"
                           form="product-form"
                           value="1" {!! old('allow_subscription', $model->product['allow_subscription'] == 1) ? ' checked' : '' !!}>
                    <label class="custom-control-label"
                           for="customCheck0400">{{ __('backoffice.allow_subscription') }}</label>
                </div>
            </div>
        </div>
    </div>
</div>
