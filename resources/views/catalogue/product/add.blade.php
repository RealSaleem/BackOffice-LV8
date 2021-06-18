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
                <form method="POST" action="{{ $model->route }}" id="product-form">
                    {{csrf_field()}}
                    <input name="name" id="name" type="hidden" value="">
                    @if($model->edit_mode)
                    <input name="id" type="hidden" value="{{ $model->product['id'] }}">
                    @endif
                </form>
                @include('catalogue.product.product-info')

                <div class="col-md-12">

                    @include('catalogue.product.product-images')

                    @if(!$model->edit_mode || ($model->edit_mode && !$model->product['has_variant']))

                    <div class="col-md-12">
                        @include('catalogue.product.product-types')
                    </div>

                    @endif
                </div>
            </div>
        </div>

        @include('catalogue.product.right-sidebar')

    </div>
</div>
<div class="col-md-12 pb-5 mt-3 ml-2">
    <button type="submit" form="product-form" class="btn btn-primary" id="submit_btn">{{$model->button_title}}</button>
    <a href="{{ route('product.index')}}" class="btn btn-light">{{__('backoffice.cancel')}}</a>
</div>

@include('catalogue.product.brand-modal')
@include('catalogue.product.category-modal')
@include('catalogue.product.supplier-modal')
@include('catalogue.product.addon-modal')
@include('catalogue.product.option-modal')

@php
    $step = str_pad('',\Auth::user()->store->round_off -1,'0');
    $step = $step .'1';
@endphp

@endsection
@section('scripts')
<script src="https://cdnjs.cloudflare.com/ajax/libs/bootstrap-3-typeahead/4.0.2/bootstrap3-typeahead.js" type="text/javascript"></script>
<script src="{{CustomUrl::asset('js/cropper.min.js') }}"></script>
<script src="https://cdn.ckeditor.com/4.10.1/standard/ckeditor.js"></script>
<script src="{{CustomUrl::asset('js/charactercount/jquery.character-counter.js') }}"></script>

<script type="text/javascript">
    $(document).ready(function() {
        $('.product_description').each(function(e) {
            CKEDITOR.replace($(this).attr('id'));
        });
        $('[data-toggle="tooltip"]').tooltip();


        $('#category_ids').select2();
        $('#brand_id').select2({maximumSelectionLength: 1});
        $('#supplier_id').select2();
        $('#addon_id').select2();
        $('#option_id').select2();
        $('#related_products').select2();
        $('#product_addons').select2();
        $('#product_options').select2();
    });

    $(function() {
        $('#profile-tab').click(function() {
            $('#outlets-section').hide();

        });
    });

    $(function() {
        $('#home-tab').click(function() {
            $('#outlets-section').show();

        });
    });

    $(function() {
        $('#contact-tab').click(function() {
            $('#outlets-section').show();
            $('#brand_id').prop('required',false).prop('disabled',true).trigger('change');
            $('#supplier_id').prop('required',false).prop('disabled',true).trigger('change');
            $('.combo-toggle').addClass('d-none');
            $('#retail_price').prop('required',true);
            $('#standard_product').prop('checked',false).trigger('change');
            $('#combo_product').prop('checked',true).trigger('change');
            $('#variant_product').prop('checked',false).trigger('change');
            $('#submit_btn').text('Save');
        });
    });

    $(function() {
        $('#home-tab').click(function() {
            $('#brand_id').prop('required',true).prop('disabled',false).trigger('change');
            $('#supplier_id').prop('required',true).prop('disabled',false).trigger('change');
            $('.combo-toggle').removeClass('d-none');
            $('#retail_price').prop('required',true);
            $('#standard_product').prop('checked',true).trigger('change');
            $('#combo_product').prop('checked',false).trigger('change');
            $('#variant_product').prop('checked',false).trigger('change');
            $('#submit_btn').text('Add');
        });
    });

    $(function() {
        $('#profile-tab').click(function() {
            $('#brand_id').prop('required',true).prop('disabled',false).trigger('change');
            $('#supplier_id').prop('required',true).prop('disabled',false).trigger('change');
            $('.combo-toggle').removeClass('d-none');
            $('#retail_price').prop('required',false);
            $('#standard_product').prop('checked',false).trigger('change');
            $('#combo_product').prop('checked',false).trigger('change');
            $('#variant_product').prop('checked',true).trigger('change');
            $('#submit_btn').text('Next');
        });
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
        $(document).on("change", `#auto_sku`, function() {
            if ($(this).is(':checked')) {
                $('#auto_sku_input').removeClass('d-none');
                $('#sku_custom_input').addClass('d-none');
            }
        });
        $(document).on("change", `#sku_custom`, function() {
            if ($(this).is(':checked')) {
                $('#sku_custom_input').removeClass('d-none');
                $('#auto_sku_input').addClass('d-none');
            }
        });
    });

    @if($model->sku_by_name)
        $(function() {
            $('#title_en').change(function() {
                let name = $('#title_en').val();

                if (name != undefined) {
                    name = name.replace(/\s/g, '-');

                    if (name.length > 20) {
                        name = name.substr(0, 20);
                    }

                    name = name.toLowerCase();
                }
                $('#auto_sku_input').val(name);
            })
        })
    @endif

    function calculateMargin(cost_price, id) {
        let sell_price = $('#retail_price').val();
        let diff = parseFloat(sell_price) - parseFloat(cost_price);
        let margin = diff / parseFloat(sell_price);


        $(`#diff-${id}`).val('' + parseFloat(diff).toFixed(2));
        $(`#margin-${id}`).val(parseFloat(margin * 100).toFixed(2) + '%');
    }

    var products = [];
    var cp = JSON.parse('{!! json_encode($model->composite_products) !!}');
    var combo_products = [];

    if (cp != null && cp.length > 0) {
        cp.map(v => {
            let item = {
                id: v.product_variant.id,
                name: v.product_variant.name,
                retail_price: v.product_variant.retail_price,
                quantity: v.quantity,
                supplier_price: v.product_variant.supplier_price,
                unit: v.product_variant.product.unit
            }

            combo_products.push(item);
        })
    }

    var total_price = 0;
    var supplier_price = 0;

    $(document).ready(function() {
        $('#search_prod').typeahead({
            source: function(name, result) {
                $.ajax({
                    url: "{{ route('api.search.product') }}",
                    data: 'name=' + name,
                    dataType: "json",
                    type: "POST",
                    success: function(data) {
                        products = [];
                        result($.map(data.Payload, function(item) {
                            products.push(item);
                            return item.name;
                        }));
                    }
                });
            }
        });
    });


    $(document).ready(function() {
        $('#add_search_prod').click(function() {
            if (products.length > 0) {
                let item = products.filter(item => {
                    if (item.name == $('#search_prod').val()) {
                        if (!checkduplicate(item, combo_products)) {
                            combo_products.push(item);
                            renderComboProducts(combo_products);
                            $('#search_prod').val('');
                        }
                    }
                });
            }
        });
    });

    function checkduplicate(item, combo_products) {
        return combo_products.some(prod => prod.id === item.id);
    }


    function renderComboProducts(products) {
        console.log(products);
        let str = '';

        var counter = 0;

        products.map(prod => {
            total_price += parseInt(prod.retail_price);
            supplier_price += parseInt(prod.supplier_price);

            if (prod.quantity == undefined) {
                prod.quantity = 1;
            }
            if (prod.unit == undefined) {
                prod.unit = '';
            }

            str += `
                <tr id="combo-${prod.id}">
                    <td>
                        <input type="hidden" name="composite_products[${counter}][id]" form="product-form" value="${prod.id}">
                        <input type="hidden" name="composite_products[${counter}][name]" form="product-form" value="${prod.name}">
                        <input type="hidden" name="composite_products[${counter}][total_price]" form="product-form" value="${total_price}">
                        <div class="row no-gutters">
                              <div class="col-auto">
                                 <img src="${prod.image}" class="img-fluid" alt="" style="width:50px;height:50px;object-fit:cover;">
                              </div>
                              <div class="col">
                                 <div class="card-block px-2">
                                    <span class="card-title">${prod.name}</span>
                                    <hr><span class="card-title badge badge-dark">SKU : ${prod.sku}</span>
                                 </div>
                              </div>
                        </div>
                    </td>
                    <td>
                        <input type="number" id="quantity-${prod.id}" onChange="calculatePrice(this.value,${prod.id})" form="product-form" name="composite_products[${counter}][quantity]" class="form-control form-rounded" placeholder="Quantity" step="any" min="0.0001" value="${prod.quantity}">
                    </td>
                    <td>${prod.unit}</td>
                    <td>
                        {{ $model->currency }} <span class="retail_prices" id="retail_price-${prod.id}" data-retail-price=${prod.retail_price}>${prod.retail_price * prod.quantity}</span>
                    </td>
                    <td> <i class="fa fa-trash" onClick="deleteRow(${prod.id})"></i></td>
                </tr>`;
            counter++;
        });

        $('#total').html(parseFloat(total_price).toFixed(2));
        $('#total_cost').html(parseFloat(supplier_price).toFixed(2));

        total_price = 0;
        supplier_price = 0;
        $('#element').html(str);
    }

    function calculatePrice(quantity, id) {
        console.log(quantity, id);
        let retail_price = $(`#retail_price-${id}`).data('retail-price');
        $(`#retail_price-${id}`).html(parseFloat(retail_price * quantity).toFixed(2));

        let total = 0;
        $('.retail_prices').each(function() {
            total += parseFloat($(this).html());
        });

        $('#total').html(parseFloat(total).toFixed(2));

    }

    function deleteRow(id) {
        let temp = combo_products.filter(prod => prod.id != id);
        composite_products = [];
        combo_products = temp;

        console.log(combo_products);

        let total = 0;
        combo_products.map(item => {
            total += $(`#quantity-${item.id}`).val() * item.retail_price;
        })
        $('#total').html(parseFloat(total).toFixed(2));
        $('#combo-' + id).remove();

    }

    $(":input").keypress(function(event) {
        if (event.which == '10' || event.which == '13') {
            event.preventDefault();
        }
    });


    $(function() {
        $('#product-form').submit(function() {

            var name = $('#title_en').val();
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
                                if(response.Payload.has_variant){
                                    window.location.href = `{{ url('variant/${response.Payload.id}/edit') }}`;
                                }else{
                                    window.location.href = "{{ route('product.index') }}";
                                }
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
                    },error: function(row){
                        console.log(row);
                    }
                });
            }
            return false;
        });
    });


    var image_upload_path = site_url('api/product-image');
    var form_id = 'product-form';
    var p_images = '';
    p_images = JSON.parse('{!! json_encode($model->product["product_images"]) !!}');
    let maxFiles = 10;
</script>
<script type="text/javascript">
    $(function() {
        $('#brand-form').submit(function() {

            var name = $('.brand-name').eq(0).val();
            $('#brand-name').val(name);

            if ($(this)[0].checkValidity()) {
                $.ajax({
                    url: $(this).attr('action'),
                    type: $(this).attr('method'),
                    data: $(this).serialize(),
                    success: function(response) {
                        if (response.IsValid) {
                            toastr.success(response.Message, 'Success');
                            let item = response.Payload;
                            let option = new Option(item.name, item.id, false, false);
                            $('#brand_id').append(option).trigger('change');
                            $('#brand_id').val(item.id).trigger('change');
                            $('#brandModal').modal('hide');
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

    $(function() {
        $('#category-form').submit(function() {

            var name = $('.category-name').eq(0).val();
            $('#category-name').val(name);

            if ($(this)[0].checkValidity()) {
                $.ajax({
                    url: $(this).attr('action'),
                    type: $(this).attr('method'),
                    data: $(this).serialize(),
                    success: function(response) {
                        if (response.IsValid) {
                            toastr.success(response.Message, 'Success');
                            let item = response.Payload;
                            let option = new Option(item.name, item.id, false, false);
                            $('#category_ids').append(option).trigger('change');
                            $('#category_ids').val(item.id).trigger('change');
                            $('#categoryModal').modal('hide');
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

    $(function() {
        $('#supplier-form').submit(function() {

            var name = $('.supplier-name').eq(0).val();
            $('#supplier-name').val(name);

            if ($(this)[0].checkValidity()) {
                $.ajax({
                    url: $(this).attr('action'),
                    type: $(this).attr('method'),
                    data: $(this).serialize(),
                    success: function(response) {
                        if (response.IsValid) {
                            toastr.success(response.Message, 'Success');
                            let item = response.Payload;
                            let option = new Option(item.name, item.id, false, false);
                            $('#supplier_id').append(option).trigger('change');
                            $('#supplier_id').val(item.id).trigger('change');
                            $('#supplierModal').modal('hide');
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

    var languages = JSON.parse('{!! json_encode($model->languages) !!}');

    /*function renderAddOnItems(){

        let counter = 0;

        languages.map((lang) => {

            let section = `.section-${lang.short_name}`;
            let required = counter == 0 ? '<span style="color: red">*</span>' : '';
            let index = $(section + ' > div.row:visible').length;

            let row = `<div class="row delete-${index}">`;

            row +=`<div class="col-md-5">
                        <div class="form-group">
                            <input type="text" name="${lang.short_name}.items[${index}][name]" class="form-control" style="font-style:italic" ${counter == 0 ? 'required' : ''} value="">
                        </div>
                    </div>`;

            if(lang.short_name == 'en'){
                row +=`<div class="col-md-5">
                    <div class="form-group">
                        <input name="${lang.short_name}.items[${index}][price]" class="form-control" style="font-style:italic" type="number" value="">
                    </div>
                </div>
                <div class="col-md-2">
                    <div class="form-group">
                        <i class="fa fa-trash " style="margin-top: 40%;" onclick="$('.delete-${index}').remove()"></i>
                    </div>
                </div>
                `;
            }


            row +=`</div>`;

            $(section).append(row);

            counter++;
        });
    }*/

    function renderAddOnItems() {

        languages.map((lang) => {

            let section = `.section-${lang.short_name}`;
            let required = lang.short_name == 'en' ? '<span style="color: red">*</span>' : '';
            let index = $(section).find('.row').length;

            let row = `<div class="row delete-${index}">`;
            let input = '';
            let selectApply = false;
            let items = JSON.parse('{!! json_encode($model->items) !!}');

            if(lang.short_name == 'en'){
                row += `<div class="col-md-5">
                    <div class="form-group">
                        <label>{{ __('backoffice.item_name') }}</label>

                        <select class="select2 form-control" onchange="fillOption(this)" required name="${lang.short_name}.items[${ index }][name]" style="width:100%" id="${lang.short_name}-${index}-name">
                            <option value="">Select or add item</option>
                            ${
                                items.map(i => {
                                    let key = i[`${lang.short_name}-name`];
                                    let info = JSON.stringify(i);
                                    return `<option value="${key}" data-info='${info}'>${key}</option>`
                                })
                            }
                        </select>

                    </div>
                </div>`;
            } else {
                row += `
                        <div class="col-md-5">
                            <div class="form-group">
                            <label>{{ __('backoffice.item_name') }}</label>
                            <input type="text" name="${lang.short_name}.items[${index}][name]" id="${lang.short_name}-${index}-name" class="form-control" placeholder="Item Name" style="font-style:italic" ${index == 0 ? 'required' : ''} value="">
                            </div>
                        </div>
                    `;
            }

            if (lang.short_name == 'en') {
                selectApply = true;

                row += `<div class="col-md-5">
                            <div class="form-group">
                                <label>{{ __('backoffice.price') }}</label>
                                <input name="${lang.short_name}.items[${index}][price]" id="${lang.short_name}-${index}-price" class="form-control" style="font-style:italic" type="number" value="">
                            </div>
                        </div>
                        <div class="col-md-2">
                            <div class="form-group">
                                <i class="fa fa-trash " style="margin-top: 40%;" onclick="$('.delete-${index}').remove()"></i>
                            </div>
                        </div>
                        `;
            }

            row += `</div>`;

            $(section).append(row);
        });
    }

    function fillOption(element){
        let data= $(element).find(':selected').data('info');
        let id = $(element).prop('id');
        let parts = id.split('-');
        let priceElement = `${parts[0]}-${parts[1]}-price`;
        if(data != undefined){
            $(`#${priceElement}`).val(data.price);

            for (const property in data) {
                if(property != 'price'){
                    let lparts = property.split('-');
                    if(lparts[0] != 'en'){
                        let ele = `${lparts[0]}-${parts[1]}-name`;
                        $(`#${ele}`).val(data[property]);
                    }
                }
            }
        }
    }

    const arrayNonUniq = array => {
        if (!Array.isArray(array)) {
            throw new TypeError("An array must be provided!")
        }

        return array.filter((value, index) => array.indexOf(value) === index && array.lastIndexOf(value) !== index)
    }

    //submit addons form
    $(function(){
        $('#add_on_form').submit(function(){
            event.preventDefault();

            if($(this)[0].checkValidity()) {

                let errors = [];
                $('.select2').each(function(){
                    errors.push($(this).val().trim());
                });

                const result = errors.filter(word => word.trim().length > 0);

                let last = arrayNonUniq(result);

                if(last.length > 0){
                    toastr.error(`{{ __('site.repeated_value',['item' => '${last[0]}']) }}`,'Error');
                    return false;
                }

                if($('#addon-min').val() > $('#addon-max').val()){
                    toastr.error("{{ __('addon.invalid_max_value') }}",'Error');
                    return false;
                }

                if($('#addon-max').val() > $('.section-en .row').length){
                    toastr.error("{{ __('addon.max_value_items') }}",'Error');
                    return false;
                }

                $.ajax({
                    url: $(this).attr('action'),
                    type: $(this).attr('method'),
                    data: $(this).serialize(),
                    success: function (response) {
                        if(response.IsValid){
                            toastr.success(response.Message,'Success');
                            let item = response.Payload;
                            let option = new Option(item.name, item.id, false, false);
                            $('#product_addons').append(option).trigger('change');
                            $('#product_addons').val(item.id).trigger('change');
                            $('#addonModal').modal('hide');
                        }else{
                            if (response.Errors.lenght > 0) {
                                response.Errors.map((error) => {
                                toastr.error(error,'Error');
                                });
                            }else{
                                toastr.error(response.Errors[0],'Error')
                            }
                        }
                    }
                })
            }
            return false;
        });
    });

    /*function renderAddOnItemss(){

        let counter = 0;

        languages.map((lang) => {

            let section = `.section-${lang.short_name}`;
            let required = counter == 0 ? '<span style="color: red">*</span>' : '';
            let index = $(section + ' > div.row:visible').length;

            let row = `<div class="row delete-${index}">`;

            row +=`<div class="col-md-5">
                        <div class="form-group">
                            <input type="text" name="${lang.short_name}.items[${index}][name]" class="form-control" style="font-style:italic" ${counter == 0 ? 'required' : ''} value="">
                        </div>
                    </div>`;

            if(lang.short_name == 'en'){
                row +=`<div class="col-md-5">
                    <div class="form-group">
                        <input name="${lang.short_name}.items[${index}][price]" class="form-control" style="font-style:italic" type="number" value="">
                    </div>
                </div>
                <div class="col-md-2">
                    <div class="form-group">
                        <i class="fa fa-trash " style="margin-top: 40%;" onclick="$('.delete-${index}').remove()"></i>
                    </div>
                </div>`;
            }

            row +=`</div>`;

            $(section).append(row);

            counter++;
        });
    }*/

    function renderOptionAddOnItems() {

        languages.map((lang) => {

            let section = `.section-${lang.short_name}`;
            let required = lang.short_name == 'en' ? '<span style="color: red">*</span>' : '';
            let index = $(section).find('.row').length;

            let row = `<div class="row delete-${index}">`;
            let input = '';
            let selectApply = false;
            let items = JSON.parse('{!! json_encode($model->items) !!}');

            if(lang.short_name == 'en'){
                row += `<div class="col-md-5">
                    <div class="form-group">
                        <label>{{ __('backoffice.item_name') }}</label>

                        <select class="select2 form-control" onchange="fillOptionFormPrice(this)" required name="${lang.short_name}.items[${ index }][name]" style="width:100%" id="${lang.short_name}-${index}-option_name">
                            <option value="">Select or add item</option>
                            ${
                                items.map(i => {
                                    let key = i[`${lang.short_name}-name`];
                                    let info = JSON.stringify(i);
                                    return `<option value="${key}" data-info='${info}'>${key}</option>`
                                })
                            }
                        </select>

                    </div>
                </div>`;
            } else {
                row += `
                        <div class="col-md-5">
                            <div class="form-group">
                            <label>{{ __('backoffice.item_name') }}</label>
                            <input type="text" name="${lang.short_name}.items[${index}][name]" id="${lang.short_name}-${index}-option_name" class="form-control" placeholder="Item Name" style="font-style:italic" ${index == 0 ? 'required' : ''} value="">
                            </div>
                        </div>
                    `;
            }

            if (lang.short_name == 'en') {
                selectApply = true;

                row += `<div class="col-md-5">
                            <div class="form-group">
                                <label>{{ __('backoffice.price') }}</label>
                                <input name="${lang.short_name}.items[${index}][price]" id="${lang.short_name}-${index}-option_price" class="form-control" style="font-style:italic" type="number" value="">
                            </div>
                        </div>
                        <div class="col-md-2">
                            <div class="form-group">
                                <i class="fa fa-trash " style="margin-top: 40%;" onclick="$('.delete-${index}').remove()"></i>
                            </div>
                        </div>
                        `;
            }

            row += `</div>`;

            $(section).append(row);
        });
    }

    function fillOptionFormPrice(element){
        let data= $(element).find(':selected').data('info');
        let id = $(element).prop('id');
        let parts = id.split('-');
        console.log(element);
        let priceElement = `${parts[0]}-${parts[1]}-option_price`;
        if(data != undefined){
            $(`#${priceElement}`).val(data.price);

            for (const property in data) {
                if(property != 'price'){
                    let lparts = property.split('-');
                    if(lparts[0] != 'en'){
                        let ele = `${lparts[0]}-${parts[1]}-option_name`;
                        $(`#${ele}`).val(data[property]);
                    }
                }
            }
        }
    }

    //submit options form
    $(function(){
        $('#option_form').submit(function(){

            if($(this)[0].checkValidity()) {
                $.ajax({
                    url: $(this).attr('action'),
                    type: $(this).attr('method'),
                    data: $(this).serialize(),
                    success: function (response) {
                        if(response.IsValid){
                            toastr.success(response.Message,'Success');
                            let item = response.Payload;
                            let option = new Option(item.name, item.id, false, false);
                            $('#product_options').append(option).trigger('change');
                            $('#product_options').val(item.id).trigger('change');
                            $('#optionModal').modal('hide');
                        }else{
                            if (response.Errors.lenght > 0) {
                                response.Errors.map((error) => {
                                toastr.error(error,'Error');
                                });
                            }else{
                                toastr.error(response.Errors[0],'Error')
                            }
                        }
                    }
                })
            }
            return false;
        });
    });
</script>
@endsection
