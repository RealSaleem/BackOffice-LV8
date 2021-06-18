<!-- content -->
<!-- content -->

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
                                <h1>

                                    <a href="{{ route('item.index') }}"
                                       class="m-b-xs w-auto btn-primary btn-sm pull-right">
                                        {{ __('backoffice.back') }}
                                    </a>
                                </h1>
                            </div>
                        </div>
                    </div>
                </div>
            </div>

            <form method="POST" action="{{ $route }}" id="addonItem_form">
                {{csrf_field()}}
                <input name="is_item" type="hidden" value="1">
                <input name="category_id" type="hidden" value="0">
                <input name="is_combo" type="hidden" value="0">
                @if($is_edit)
                    <input name="_method" type="hidden" value="PUT">
                    <input name="item_id" type="hidden" value="{{$items->id}}">
                @endif
            </form>

            <div class="col-sm-8 pl-0 pr-0">
                <div class="card bg-light mt-3  rounded  border-0">
                    <div class="card-body pb-0 pt-0">

                        {{--                        @include('catalogue/item/section')--}}
                        <ul class="nav nav-tabs nav-justified" id="myTab" role="tablist">
                            @php
                                $index = 0;
                            @endphp


                            @foreach($languages as $language)
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


                        <div class="tab-content mt-4">
                            @php
                                $index = 0;
                            @endphp
                            @foreach($languages as $language)
                                @php
                                    $name = 'title_' . $language['short_name'];
                                    $counter = 0;
                                @endphp

                                <div id="{{ strtolower($language['name']) }}"
                                     class="tab-pane fade show {{ ($index == 0) ? 'active' : '' }}">
                                    <div class="card-body">
                                        <div class="row">
                                            <div class="col-md-12">
                                                <div class="form-group">
                                                    <label>{{ __('backoffice.add_name')}}</label>
                                                    @if($index == 0)
                                                        <span style="color: red">*</span>
                                                    @endif
                                                    <input type="text" form="addonItem_form" id="title_{{ $language['short_name'] }}" name="title_{{ $language['short_name'] }}" value="{{isset($product[$name]) ? $product[$name] : null }}" class="form-control" style="font-style:italic" type="text" @if($index==0)@endif form="addonItem_form" >
                                                </div>
                                            </div>

                                        </div>
                                    </div>
                                    </div>

                                @php
                                    $index++;
                                    $counter++;
                                @endphp
                            @endforeach
                                    <hr/>
                                    <div class="card-body">


                                        <div class="row">
                                            <div class="col-md-6">
                                                <div class="form-group">
                                                    <b>
                                <span data-toggle="tooltip" class="tooltipTest" data-placement="top"
                                      title="Generate Barcode for products">
                                <i class="fa fa-info-circle"></i></span> {{__('backoffice.sku')}}
                                                    </b>
                                                </div>
                                            </div>
                                        </div>
                                        <div class="row">
                                            <div class="col-md-6">
                                                <div class="form-group">
                                                    <div class="pure-radiobutton">
                                                        <input id="auto_sku" name="sku_type" type="radio" form="addonItem_form" value='1'{!! old('sku_type', $sku == $sku) ? ' checked' : '' !!}>
                                                        <label for="auto_sku">@lang('backoffice.auto_generate')</label>
                                                    </div>
                                                </div>
                                            </div>
                                            <div class="col-md-6">
                                                <div class="form-group">
                                                    <div class="pure-radiobutton">
                                                        <input id="sku_custom" form="addonItem_form" type="radio" name="sku_type" value='0'{{ $product['sku_type'] == 'custom' ? 'checked' : '' }}{!! old('sku_type', $product['sku'] ) ? 'checked ' : '' !!}>
                                                        <label for="sku_custom">@lang('backoffice.custom_SKU')</label>
                                                        <span data-toggle="tooltip" class="tooltipTest" data-placement="top" title="Enter or scan Barcode to enter SKU and generate the universal Barcode or enter custom SKU for the custom Barcode">
                                                <i class="fa fa-info-circle" style="margin-left: -10px;"></i></span>
                                                    </div>
                                                </div>
                                            </div>

                                            <input id="auto_sku_input" type="text" form="addonItem_form" class="form-control form-rounded
                                            {{ ( $product['sku_custom'] == null) ? 'show' : 'hidden'
                                            }}" name="sku" placeholder="Ex.1000 or FGK229911" style="font-style:italic;" value="{{ old('sku',$sku) }}" for="auto_sku" readonly>

                                            <input id="sku_custom_input" type="text" form="addonItem_form" class="form-control form-rounded {{ $product['sku_type'] == "custom" ? 'show' : 'hidden' }}" name="sku_custom" placeholder="Ex.1000" style="font-style:italic;" value="{{ old('sku_custom',$product['sku']) }}" for="sku_custom">
                                        </div>

                                        <hr>
                                        <div class="row">
                                            <div class="col-md-6">
                                                <div class="form-group">
                                                    <h3>
                                                        <span data-toggle="tooltip" class="tooltipTest" data-placement="top" title="Price of product according to currency">
                                                            <i class="fa fa-info-circle"></i>
                                                        </span> @lang('backoffice.pricing')
                                                    </h3>
                                                </div>
                                            </div>
                                        </div>


                                        <div class="row">
                                            <div class="col-md-4">
                                                <div class="form-group">
                                                    <label for="">@lang('backoffice.retail_price') ({{ $store_currency }} )<span style="color: #ff0000">*</span></label>
                                                    <input type="number" form="addonItem_form" step="any" id="retail_price" class="form-control" name="retail_price" min="0" placeholder="Enter Price" value="{{old('retail_price',isset($items_variant) ? number_format($items_variant->retail_price,Auth::user()->store->round_off) : null )}}">
                                                </div>
                                            </div>
                                            <div class="col-md-4">
                                                <div class="form-group">
                                                    <label for="">@lang('backoffice.discounted_price')</label>
                                                    <input type="number" step="any" min="0" form="addonItem_form" class="form-control" name="before_discount_price" placeholder="Enter Price" value="{{ old('before_discount_price',$product['before_discount_price'] ? number_format($product['before_discount_price'],Auth::user()->store->round_off) :'') }}"
                                                    >
                                                </div>
                                            </div>

                                            @if($show_unit)
                                                <div class="col-md-4">
                                                    <div class="form-group" id="show-unit">

                                                        <label for="">@lang('backoffice.unit')</label>
                                                        <select name="unit" class="form-control m-b" form="addonItem_form"{{ $is_edit ? "readonly disabled " : "required" }} id="unit-item">
                                                        <!-- <option value="">@lang('backoffice.select_unit')</option> -->
                                                            <option value="qty" {{ $product['unit'] == 'qty' ? "selected" : old('qty') }} >@lang('backoffice.number')</option>
                                                            <option value="kg" {{ $product['unit'] == 'kg' ? "selected" :  old('kg') }} >@lang('backoffice.kilogram')</option>
                                                            <option value="l" {{ $product['unit'] == 'l' ? "selected" :  old('l') }}>@lang('backoffice.liter')</option>
                                                            <option value="m" {{ $product['unit'] == 'm' ? "selected" :  old('m') }}>@lang('backoffice.meter')</option>
                                                        </select>
                                                    </div>
                                                </div>

                                            @endif
                                        </div>
                                        <hr>
                                        <div class="row">

                                            <div class="col-md-12">
                                                <div class="form-group">
                                                    <h3><span data-toggle="tooltip" class="tooltipTest"
                                                              data-placement="top"
                                                              title="Branch name and product availability">
                                            <i class="fa fa-info-circle"></i></span> @lang('backoffice.branches')
                                                    </h3>
                                                </div>
                                            </div>
                                            <div class="row">
                                                <div class="col-md-12">
                                                    <div class="form-group">
                                                        <div class="row">
                                                            @foreach($outlets as $outlet)
                                                                @if($is_edit)
                                                                    @php
                                                                        $index = $outlet['id'];

                                                                        $diff = $product['retail_price'] - $outlet['supply_price'];
                                                                        if($product['retail_price'] > 0){
                                                                                $margin = $diff / $product['retail_price'] * 100;
                                                                           }else{
                                                                                $margin = 0;
                                                                           }

                                                                    @endphp
                                                                @else
                                                                    @php
                                                                        $index = $outlet['outlet_id'];
                                                                        $diff = 0;
                                                                        $margin = 0;
                                                                    @endphp
                                                                @endif

                                                                <div class="col-md-12">
                                                                    <div class="row">
                                                                        <div class="col-md-3">
                                                                            <label for="">{{ $outlet['name'] }}</label>
                                                                        </div>
                                                                        <div class="col-md-9">
                                                                            <div class="row">
                                                                                <div class="col-md-4">
                                                                                    <input type="hidden"
                                                                                           form="addonItem_form"
                                                                                           name="branches[{{ $index }}][id]"
                                                                                           value="{{ $outlet['id'] }}">
                                                                                    <input
                                                                                        type="number"
                                                                                        step="any"
                                                                                        form="addonItem_form"
                                                                                        class="form-control"
                                                                                        name='branches[{{ $index }}][quantity]'
                                                                                        placeholder="Quantity"
                                                                                        value="{{$outlet['quantity'] ? number_format($outlet['quantity'],Auth::user()->store->round_off ): '' }}"
                                                                                    >
                                                                                </div>
                                                                                <div class="col-md-4">
                                                                                    <input
                                                                                        type="number"
                                                                                        step="any"
                                                                                        min="0"
                                                                                        form="addonItem_form"
                                                                                        class="form-control mt-2"
                                                                                        name='branches[{{ $index }}][re_order_point]'
                                                                                        placeholder="Re-Order Pt."
                                                                                        value="{{ $outlet['re_order_point'] }}"
                                                                                    >
                                                                                </div>
                                                                                <div class="col-md-4">
                                                                                    <input
                                                                                        type="number"
                                                                                        step="any"
                                                                                        min="0"
                                                                                        form="addonItem_form"
                                                                                        class="form-control mt-2"
                                                                                        name='branches[{{ $index }}][re_order_quantity]'
                                                                                        placeholder="Qty Re-Order"
                                                                                        value="{{ $outlet['re_order_quantity']  }}"
                                                                                    >
                                                                                </div>
                                                                            </div>
                                                                            <div class="row">
                                                                                <div class="col-md-4">
                                                                                    <input
                                                                                        type="number"
                                                                                        step="any"
                                                                                        min="0"
                                                                                        form="addonItem_form"
                                                                                        onchange="calculateMargin(this.value,{{$index}})"
                                                                                        class="form-control mt-2"
                                                                                        name='branches[{{ $index }}][supply_price]'
                                                                                        placeholder="Supply Price"
                                                                                        value="{{$outlet['supply_price'] ? number_format($outlet['supply_price'],Auth::user()->store->round_off) : '' }}"
                                                                                    >
                                                                                </div>
                                                                                <div class="col-md-4">
                                                                                    <div class="btn-group" role="group"
                                                                                         aria-label="..."
                                                                                         >
                                                                                        <input type="button"
                                                                                               class="btn btn-outline-info col-md-6"
                                                                                               form="addonItem_form"
                                                                                               id="diff-{{ $index }}"
                                                                                               value="{{ $diff }}"/>
                                                                                        <input type="button"
                                                                                               form="addonItem_form"
                                                                                               class="btn btn-outline-info col-md-6"
                                                                                               id="margin-{{ $index }}"
                                                                                               value="{{number_format( $margin,Auth::user()->store->round_off) }} %"/>
                                                                                    </div>
                                                                                </div>
                                                                            </div>
                                                                        </div>
                                                                    </div>
                                                                    <hr/>
                                                                </div>
                                                            @endforeach
                                                        </div>
                                                    </div>
                                                </div>
                                            </div>
                                            <div class="row">
                                                <div class="col-md-12 right">
                                                    <div class="btn-group">
                                                        <div class="pure-checkbox">
                                                            <input
                                                                id="allow_out_of_stock"
                                                                form="addonItem_form"
                                                                name="allow_out_of_stock"
                                                                type="checkbox"
                                                                value="1"
                                                                {!! old('allow_out_of_stock', $product['allow_out_of_stock']) ? ' checked' : '' !!}
                                                            >
                                                            <label
                                                                for="allow_out_of_stock">@lang('backoffice.allow_customer')</label>
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

            <div class="col-sm-4">
                <div class="rounded p-4 ">
                    <div class="card p-3 rounded">
                        <div class="row form-group">
                            <div class="col-md-12">
                                    <span data-toggle="tooltip" class="tooltipTest" data-placement="top"
                                          title="Additional options to select category and other things.">
                                        <i class="fa fa-info-circle"></i>
                                    </span>
                                <label>@lang('backoffice.other_options')</label>
                            </div>
                        </div>

                        <div
                            class="row form-group req  @if($is_edit) @if($product['is_composite'] == 1) hidden @endif @endif">
                            <div class="col-md-12">
                                <label>
                                            <span data-toggle="tooltip" class="tooltipTest" data-placement="top"
                                                  title="Select supplier of product">
                                                <i class="fa fa-info-circle"></i>
                                            </span>
                                    @lang('backoffice.select_supplier')
                                    <span style="color: red">*</span>
                                </label>

                                <select class="form-control input-md select2 itemformselect2" name="supplier_id[]" form="addonItem_form"
                                        multiple id="supplier_id" required>
                                    @foreach ($suppliers as $supplier)
                                        <option
                                            value="{{ $supplier->id }}" {{ (in_array($supplier->id, $supplier_ids )) ? "selected" : "" }}>{{ $supplier->name }}</option>
                                    @endforeach
                                </select>


                            </div>
                        </div>
                    </div>

                </div>
            </div>






            <div class="col-md-12 pb-5" style="padding-left: 36px;">
                <button type="submit" form="addonItem_form" class="btn btn-primary">{{isset($submitbtn)? $submitbtn : 'Save'}}</button>
                <a href="{{ route('item.index')}}" class="btn btn-light">{{ __('backoffice.cancel') }}</a>
            </div>


        </div>
    </div>


@endsection

@section('scripts')
    <script>

        $('.itemformselect2').select2({
            tags: true
        });


        $(function () {
            $('#sku_custom_input').hide();
            // $('#auto_sku_input').hide();
            $(document).on("change", `input[id='auto_sku']:radio`, function () {
                if ($('#auto_sku').is(':checked')) {
                    $('#auto_sku_input').show();
                    $('#sku_custom_input').hide();
                }
            });
            $(document).on("change", `input[id='sku_custom']:radio`, function () {
                if ($('#sku_custom').is(':checked')) {
                    $('#sku_custom_input').show();
                    $('#auto_sku_input').hide();
                }
            });
        });


$(function(){
    $(document).on('submit','#addonItem_form',function(event){

        event.preventDefault();

        if ($('#variant_product').is(':checked') ){
            $('#retail_price').removeAttr('required');
        }else{
            $('#retail_price').attr('required',true);
        }

        if($('#addonItem_form')[0].checkValidity()) {
            $('.product_description').each(function(e){
                let text =CKEDITOR.instances[$(this).attr('id')].getData();
                $(this).html(text);

            });
            var pro_name = $('#title_en').val();
            $('#name').val(pro_name);

            $.ajax({
                url: $(this).attr('action'),
                type: $(this).attr('method'),
                data: $(this).serialize(),
                success: function (response) {
                    if(response.IsValid){
                        toastr.success(response.Message,'Success');
                        setTimeout(()=>{
                            window.location.href = site_url('item');
                        },1500);
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
