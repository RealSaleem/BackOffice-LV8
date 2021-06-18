<div class="tabs">

    <ul class="nav nav-tabs row-eq-height  mt-4  @if($model->edit_mode) @if($model->product['is_composite'] == 1) d-none @endif @if($model->product['is_composite'] == 0) d-none @endif @endif " id="myTab" role="tablist">
        <li class="nav-item mt-3  mt-md-0">
            <a class="active nav-link bg-light mr-3 rounded pt-4 text-center" for="standard_product" id="home-tab" data-toggle="tab" href="#home" role="tab" aria-controls="home" aria-selected="true">
                <i class="icon-check"></i>
                <h3>{{ __('backoffice.standard_product') }}</h3>
                <p class="text-muted">{{ __('backoffice.standard_product_message') }}</p>
                <input id="standard_product" name="is_combo" class="d-none" type="radio" form="product-form" value="0" checked="checked">
            </a>
        </li>
        <li class="nav-item  mt-3  mt-md-0">
            <a class="nav-link bg-light mr-3 rounded pt-4 text-center" for="variant_product" id="profile-tab" data-toggle="tab" href="#profile" role="tab" aria-controls="profile" aria-selected="false">
                <i class="icon-check"></i>
                <h3>{{ __('backoffice.variant_product') }}</h3>
                <p class="text-muted">{{ __('backoffice.variant_product_message') }}</p>
                <input id="variant_product" name="is_combo" class="d-none" value="-1" type="radio" class="variant_product" form="product-form" @if($model->edit_mode) @if($model->product['is_composite'] == 1) disabled @endif @endif >
            </a>
        </li>
        <li class="nav-item  mt-3  mt-md-0">
            <a class="nav-link bg-light rounded pt-4 text-center" for="combo_product" id="contact-tab" data-toggle="tab" href="#contact" role="tab" aria-controls="contact" aria-selected="false">
                <i class="icon-check"></i>
                <h3>{{ __('backoffice.composite_product') }}</h3>
                <p class="text-muted">{{ __('backoffice.composite_product_message') }}</p>
                <input id="combo_product" name="is_combo" class="d-none" value="1" type="radio" class="composite" form="product-form" {!! old('is_combo', $model->product['is_composite'] == 1 ) ? 'checked ' : '' !!} />
            </a>
        </li>
    </ul>

    <div class="tab-content" id="myTabContent">

        <div class="mt-5">
            <label>
                <i class="icon-info pr-1" tabindex="0" data-toggle="popover" data-trigger="focus" title="" data-content="And here's some amazing content. It's very engaging. Right?" data-original-title="Dismissible popover"></i>
                {{ __('backoffice.stock_keeping_unit') }}<em style="color:red">*</em>
            </label>
            <div class="btn-block">
                <div class="custom-control radiopaddiing  rounded custom-radio custom-control-inline" style="padding-right: 25px; padding-left: 40px;">
                    <input id="auto_sku" name="sku_type" class="custom-control-input" type="radio" form="product-form" value='1' {!! old('sku_type', $model->sku==$model->sku) ? ' checked' : '' !!}>
                    <label class="custom-control-label" for="auto_sku"> {{ __('backoffice.auto_generate_sku') }}</label>
                </div>
                <div class="custom-control radiopaddiing bg-light  rounded custom-radio custom-control-inline mt-3  mt-md-0" style="padding-left: 35px;">
                    <input id="sku_custom" form="product-form" type="radio" class="custom-control-input p-4" name="sku_type" value='0' {{-- $model->product['sku_type'] == 'custom' ? 'checked' : '' --}}{{--!! old('sku_type', $model->product['sku'] ) ? 'checked ' : '' !!--}}>
                    <label class="custom-control-label " for="sku_custom"> {{ __('backoffice.custom_sku') }}</label>
                </div>
            </div>

            <input
                id="auto_sku_input" type="text" form="product-form" name="sku"
                    class="form-control  mt-3 {{ (!$model->edit_mode || ($model->edit_mode && $model->product['sku_type'] != "custom")) ? 'show' : 'd-none' }}"
                    placeholder="Ex.1000 or FGK229911"  style="font-style:italic;"  value="{{ old('sku_custom',$model->product['sku']) }}" for="auto_sku" readonly
                >
                <input
                    id="sku_custom_input" type="text" form="product-form"
                    class="form-control  mt-3 {{ $model->edit_mode && $model->product['sku_type'] == "custom" ? 'show' : 'd-none' }}"
                    name="sku_custom" placeholder="Ex.1000" style="font-style:italic;" value="{{ old('sku_custom',$model->product['sku']) }}" for="sku_custom"
                >


        </div>

        <div class="tab-pane fade show active" id="home" role="tabpanel" aria-labelledby="home-tab">

        </div>

        <div class="tab-pane fade" id="profile" role="tabpanel" aria-labelledby="profile-tab">

        </div>

        <div class="tab-pane fade" id="contact" role="tabpanel" aria-labelledby="contact-tab">

            @include('catalogue.product.composite-product')

            <!-- Stock Unite -->

        </div>

        <div class="" id="outlets-section">
            @include('catalogue.product.standard-product')
        </div>
    </div>
</div>
