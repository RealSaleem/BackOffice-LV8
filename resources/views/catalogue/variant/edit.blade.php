@extends('layouts.backoffice')
@section('content')

    <div class="container-fluid" ng-app="posApp" ng-controller="baseCtrl">
        <div class="row" ng-controller="productCtrl" ng-init="loadData()">
            <div class="col-sm-12 pl-0 pr-0">
                <div class="greybg1 rounded p-4 mb-3">
                    <div class="row">
                        <div class="col-sm-12">
                            <div class="common_title">
                                <h1>
                                    {{ __('variant.edit_variants_for') }} {{$name}}
                                    <a href="{{route('product.index')}}"
                                       class="m-b-xs w-auto btn-primary btn-sm pull-right">
                                        {{ __('variant.back') }}
                                    </a>
                                </h1>
                            </div>
                        </div>
                    </div>
                </div>
            </div>

            <div class="col-sm-12 pl-0 pr-0">
                <div class="card bg-light rounded  border-0">
                    <div class="card bg-light rounded  border-0 add_variant">
                        <div class="card-body">
                            <div class="col-md-12">
                                <div class="row">
                                    <div class="col-md-12 attibutes-container">
                                        <div>
                                            <h4>@lang('site.variants')</h4>
                                            <p style="font-style: italic; color: grey;">@lang('site.var_att')</p>
                                        </div>
                                        <div class="row">
                                            <div class="col-md-4">
                                                <label>@lang('site.attribute')</label> <span
                                                    class="text-warning">@lang('site.eg_variant')</span>
                                            </div>
                                            <div class="col-md-8 ">
                                                <label>Value &nbsp;</label> <span
                                                    class="text-warning">@lang('site.eg_color')</span>
                                            </div>
                                        </div>
                                        <div class="row">
                                            <div class="col-md-4">
                                                <input form="productform" id="attribute-1" class="form-control rounded "
                                                       placeholder="Color" ng-model="product.attribute_1" type="text">
                                            </div>
                                            <div class="col-md-5 ">
                                                {{--                                            <md-chips ng-model="product.attr1_values" name="attr1_values" id="values-1" placeholder="@lang('site.Press_Enter_Once_Added')">--}}
                                                {{--                                                <md-chip-template>--}}
                                                {{--                                                    <strong ng-cloak>@{{$chip}}</strong>--}}
                                                {{--                                                </md-chip-template>--}}
                                                {{--                                            </md-chips>--}}
                                                <input type="text" name="attr1_values[]" id="values-1"
                                                       placeholder="@lang('site.Press_Enter_Once_Added')"
                                                       class="form-control rounded getattribute1"
                                                       onkeypress="getAttribute1Value()"
                                                       style="height: 46px; padding: 7px 6px;">


                                            </div>
                                            <div class="col-md-3">
                                                {{--                                            <button type="button" class="btn btn-primary" ng-click="validateAttribute(1)">@lang('site.add_trans')</button>--}}
                                                <button type="button" class="btn btn-primary" data-toggle="modal"
                                                        onclick="openAttribute_1_Model()">@lang('site.add_trans')</button>
                                            </div>
                                        </div>
                                        <div class="row">
                                            <div class="col-md-4">
                                                <input form="productform" id="attribute-2" class="form-control rounded "
                                                       placeholder="Size" type="text">
                                            </div>
                                            <div class="col-md-5 ">
                                                {{--                                            <md-chips ng-model="product.attr2_values" id="values-2" name="attr2_values" placeholder="@lang('site.Press_Enter_Once_Added')">--}}
                                                {{--                                                <md-chip-template>--}}
                                                {{--                                                    <strong ng-cloak>@{{$chip}}</strong>--}}
                                                {{--                                                </md-chip-template>--}}
                                                {{--                                            </md-chips>--}}

                                                <input type="text" id="values-2" name="attr2_values"
                                                       class="form-control rounded getattribute2"
                                                       placeholder="@lang('site.Press_Enter_Once_Added')"
                                                       style="height: 46px; padding: 7px 6px;">
                                            </div>

                                            <div class="col-md-3">
                                                <button type="button" class="btn btn-primary"
                                                        onclick="openAttribute_2_Model()">@lang('site.add_trans')</button>
                                            </div>
                                        </div>
                                        <div class="row">
                                            <div class="col-md-4">
                                                <input form="productform" id="attribute-3" class="form-control rounded"
                                                       placeholder="Material" ng-model="product.attribute_3"
                                                       type="text">
                                            </div>
                                            <div class="col-md-5">
                                                {{--                                            <md-chips ng-model="product.attr3_values" id="values-3" name="attr3_values" placeholder="@lang('site.Press_Enter_Once_Added')">--}}
                                                {{--                                                <md-chip-template>--}}
                                                {{--                                                    <strong ng-cloak>@{{$chip}}</strong>--}}
                                                {{--                                                </md-chip-template>--}}
                                                {{--                                            </md-chips>--}}
                                                <input type="text" id="values-3" name="attr3_values"
                                                       placeholder="@lang('site.Press_Enter_Once_Added')"
                                                       class="form-control rounded getattribute3"
                                                       style="height: 46px; padding: 7px 6px;">

                                            </div>
                                            <div class="col-md-3">
                                                <button type="button" class="btn btn-primary"
                                                        onclick="openAttribute_3_Model()">@lang('site.add_trans')</button>
                                            </div>
                                        </div>

                                        <br/>
                                        <hr/>
                                        <br/>
                                        <div class="row" style="margin-top: -22px;">
                                            <div class="col-md-12" style="display: flex;">
                                                <h3>
                                            <span data-toggle="tooltip" class="tooltipTest" data-placement="top"
                                                  title="Generate code for products">
                                            <i class="fa fa-info-circle"></i></span> @lang('site.stock_keeping_unit')
                                                </h3>
                                                @if($product->sku_type == "custom")
                                                    <input
                                                        id="auto_sku_input"
                                                        type="text"
                                                        form="product-form"
                                                        class="form-control form-rounded show col-md-4"
                                                        name="sku"
                                                        placeholder="Ex.1000 or FGK229911"
                                                        style="font-style:italic;margin: -3px 25px; "
                                                        readonly
                                                        value={{ $product->prefix }}
                                                    >
                                                @else
                                                    <input
                                                        id="auto_sku_input"
                                                        type="text"
                                                        form="product-form"
                                                        class="form-control form-rounded show col-md-4"
                                                        name="sku"
                                                        placeholder="Ex.1000 or FGK229911"
                                                        style="font-style:italic;margin: -3px 25px; "
                                                        ng-model="product.sku"
                                                        readonly
                                                    >
                                                @endif

                                            </div>



                                        </div>
                                        <div class="row mt-4">
                                            <div class="col-md-1"></div>
                                            <div class="col-md-2">
                                                <label>@lang('site.variant_name')</label>
                                            </div>
                                            <div class="col-md-2">
                                                <label>@lang('site.sku')/@lang('site.barcode')</label>
                                            </div>
                                            <div class="col-md-2">
                                                <label>@lang('site.retail_price')</label>
                                            </div>
                                            <div class="col-md-2">
                                                <label>@lang('site.before_discount_price')</label>
                                            </div>
                                            <div class="col-md-2"></div>
                                            <div class="col-md-1"></div>
                                        </div>


                                        <div class="panel-group" id="accordion">
{{--                                            <input type="text" class="form-control" id="n">--}}
{{--                                            <div class="form-group"><div class="row">--}}
{{--                                                    <div class="col-md-1"><a role="button" data-toggle="collapse"data-parent="#accordion" href="#rowoutlet"aria-expanded="true"><i class="details-control fa fa-chevron-right"></i></a></div>--}}
{{--                                                    <div class="col-md-2 color_selector"><span class="colr1" id="color1"style="color: blue;"></span><span class="colr2" id="color2" style="color: red;"> </span><span class="colr3" id="color3" style="color: blue;"> </span></div>--}}
{{--                                                    <div class="col-md-2"><input form="productform" id="check" required ng-disabled="!v.editable"  name="variant[sku][]" type="text" title="@{{v.sku}}" ng-model="v.sku" class="sqty2 form-control"></div><div class="col-md-2">--}}
{{--                                                        <input form="productform" required name="variant[retail_price][]" type="float" class="sqty2 form-control" ng-model="v.retail_price" ng-change="MarkupChange(v.retail_price,v)"> </div>--}}
{{--                                                    <div class="col-md-2"> <input form="productform" required name="variant[before_discount_price][]" type="float" ng-model="v.before_discount_price" class="sqty2 form-control"></div><div class="col-md-1">--}}
{{--                                                        <div class="custom-control custom-switch center-align"><input type="checkbox" class="custom-control-input" > <label class="custom-control-label">&nbsp;</label></div>--}}
{{--                                                        </div> <div class="col-md-1"><a href="javascript:void(0)"><i class="fa fa-trash"></i></a></div> </div> <div class="panel-collapse collapse" id="rowoutlet"> <div class="row">--}}
{{--                                                        <div class="col-md-12"> <div class="row"> <div class="col-md-12">  <div class="col-md-1"></div>--}}
{{--                                                                    <div class="col-md-3"><h4>Branches</h4></div>  <div class="col-md-8"></div> </div> </div> <hr/>--}}
{{--                                                             @foreach($outlets as $outlet)--}}
{{--                                                            <div class="row">--}}
{{--                                                               <div class="col-md-12" >--}}
{{--                                                                    <div class="row">--}}
{{--                                                                        <div class="col-md-12">--}}
{{--                                                                            <div class="row" style="margin-right: 50px !important; margin-left: 50px  !important ;  margin-bottom: -11px; margin-top: -7px;"> <div class="col-md-2 "><label class="pl-4">  {{$outlet['name']}}</label></div>--}}
{{--                                                                                <div class="col-md-2"> <input type="number" class="form-control" ng-model="o.quantity" placeholder="Quantity"></div> <div class="col-md-2"> <input type="number" min="0" class="form-control" ng-model="o.re_order_point" placeholder="Re-Order Pt."> </div>--}}
{{--                                                                                <div class="col-md-2"> <input type="number" min="0" class="form-control" ng-model="o.re_order_quantity" placeholder="Re-Order Qty"> </div>--}}
{{--                                                                                <div class="col-md-2"> <input type="number" min="0" class="form-control" ng-model="o.supply_price" placeholder="Supply Price"> </div>--}}
{{--                                                                                <div class="col-md-2"> <div class="btn-group" role="group" aria-label="..." style="width:100%;margin-top: -8px; "><input type="button" style="height: 43px; margin: 6px -1px;" class="border-0 bg-primary text-white col-md-6" value="0"/> <input type="button" style="height: 43px; margin: 6px -1px;" class=" border-0 bg-secondary text-white col-md-6" value="0%"/>--}}
{{--                                                                                        </div> </div> </div> </div></div> <hr/>  </div> </div>--}}
{{--                                                            @endforeach--}}
{{--                                                            <div class="col-md-12" style="margin-left: 73px !important;"> <div class="pure-checkbox"><input id="allow_out_of_stock-@{{$index}}" form="product-form" name="allow_out_of_stock" type="checkbox" ng-model="v.allow_out_of_stock" ng-true-value="1" ng-false-value="0"> <label for="allow_out_of_stock-@{{$index}}">Allow customer to purchase this product when it is out of stock</label></div> </div> </div> </div> </div> <hr/> </div>--}}
{{--                                        --}}
                                        </div>


                                        {{--                                    <div class="panel-group" id="accordion" role="tablist" aria-multiselectable="true">--}}
                                        {{--                                        <div class="form-group" ng-repeat="v in product.product_variants" ng-if="!v.is_deleted">--}}
                                        {{--                                            <div class="row">--}}
                                        {{--                                                <div class="col-md-1">--}}
                                        {{--                                                    <a role="button" data-toggle="collapse" data-parent="#accordion" href="#collapse@{{ $index }}" aria-expanded="true">--}}
                                        {{--                                                        <i class="more-less glyphicon glyphicon-chevron-right" onClick="toggleIcon()"></i>--}}
                                        {{--                                                    </a>--}}
                                        {{--                                                </div>--}}
                                        {{--                                                <div class="col-md-2 color_selector">--}}
                                        {{--                                                    <span class="colr1">--}}
                                        {{--                                                    @{{ v.attribute_value_1 }}--}}
                                        {{--                                                    </span>--}}
                                        {{--                                                    <span class="colr2" ng-if="v.attribute_value_2">--}}
                                        {{--                                                    @{{ v.attribute_value_2 }}--}}
                                        {{--                                                    </span>--}}
                                        {{--                                                    <span class="colr3" ng-if="v.attribute_value_3">--}}
                                        {{--                                                    @{{ v.attribute_value_3 }}--}}
                                        {{--                                                    </span>--}}
                                        {{--                                                </div>--}}
                                        {{--                                                <div class="col-md-2">--}}
                                        {{--                                                    <input form="productform" required ng-disabled="!v.editable" name="variant[sku][]" type="text" title="@{{v.sku}}" ng-model="v.sku" class="sqty2 form-control">--}}
                                        {{--                                                </div>--}}
                                        {{--                                                <div class="col-md-2">--}}
                                        {{--                                                    <input form="productform" required name="variant[retail_price][]" type="float" class="sqty2 form-control" ng-model="v.retail_price" ng-change="MarkupChange(v.retail_price,v)">--}}
                                        {{--                                                </div>--}}
                                        {{--                                                <div class="col-md-2">--}}
                                        {{--                                                    <input form="productform" required name="variant[before_discount_price][]" type="float" ng-model="v.before_discount_price" class="sqty2 form-control">--}}
                                        {{--                                                </div>--}}
                                        {{--                                                <div class="col-md-1">--}}
                                        {{--                                               <label class="i-switch m-t-xs m-r">--}}
                                        {{--                                               <input ng-model="v.is_active" ng-true-value="1" ng-false-value="0" type="checkbox">--}}
                                        {{--                                               <i></i>--}}
                                        {{--                                               </label>--}}
                                        {{--                                                </div>--}}

                                        {{--                                                <div class="col-md-1">--}}
                                        {{--                                                    <a ng-if="product.variants.length > 0" href="javascript:void(0)" ng-click="removeVariant(v)" class="remove_combo_row"><i class="fa fa-trash"></i></a>--}}
                                        {{--                                                </div>--}}
                                        {{--                                            </div>--}}
                                        {{--                                            <div id="collapse@{{ $index }}" class="panel-collapse collapse" role="tabpanel" aria-labelledby="headingOne">--}}
                                        {{--                                                <div class="row">--}}
                                        {{--                                                    <div class="col-md-12">--}}
                                        {{--                                                        <div class="row">--}}
                                        {{--                                                            <div class="col-md-12">--}}
                                        {{--                                                                <div class="col-md-1"></div>--}}
                                        {{--                                                                <div class="col-md-3"><h4>@lang('site.branches')</h4></div>--}}
                                        {{--                                                                <div class="col-md-8"></div>--}}
                                        {{--                                                            </div>--}}
                                        {{--                                                        </div>--}}
                                        {{--                                                        <div class="row">--}}
                                        {{--                                                            <div class="col-md-12" ng-repeat="o in v.outlets">--}}
                                        {{--                                                                <div class="row">--}}
                                        {{--                                                                    <div class="col-md-2"></div>--}}
                                        {{--                                                                    <div class="col-md-2">--}}
                                        {{--                                                                        <label>@{{ o.name }}</label>--}}
                                        {{--                                                                    </div>--}}
                                        {{--                                                                    <div class="col-md-7">--}}
                                        {{--                                                                        <div class="row">--}}
                                        {{--                                                                            <div class="col-md-4">--}}
                                        {{--                                                                                <input type="number"  class="form-control" ng-model="o.quantity" placeholder="Quantity">--}}
                                        {{--                                                                            </div>--}}
                                        {{--                                                                            <div class="col-md-4">--}}
                                        {{--                                                                                <input type="number" min="0"  class="form-control" ng-model="o.re_order_point" placeholder="Re-Order Pt.">--}}
                                        {{--                                                                            </div>--}}
                                        {{--                                                                            <div class="col-md-4">--}}
                                        {{--                                                                                <input type="number" min="0"  class="form-control" ng-model="o.re_order_quantity" placeholder="Re-Order Qty">--}}
                                        {{--                                                                            </div>--}}
                                        {{--                                                                        </div>--}}
                                        {{--                                                                        <div class="row">--}}
                                        {{--                                                                            <div class="col-md-4">--}}
                                        {{--                                                                                <input type="number" min="0"  class="form-control" ng-model="o.supply_price" placeholder="Supply Price">--}}
                                        {{--                                                                            </div>--}}
                                        {{--                                                                            <div class="col-md-4">--}}
                                        {{--                                                                                <div class="btn-group" role="group" aria-label="..." style="width:100%">--}}
                                        {{--                                                                                    <input type="button" class="btn btn-default col-md-6" value="@{{ calculateDiff(v,o) }}" />--}}
                                        {{--                                                                                    <input type="button" class="btn btn-default col-md-6" value="@{{ calculateMargin(v,o) }}" />--}}
                                        {{--                                                                                </div>--}}
                                        {{--                                                                            </div>--}}
                                        {{--                                                                        </div>--}}
                                        {{--                                                                    </div>--}}
                                        {{--                                                                    <div class="col-md-1"></div>--}}
                                        {{--                                                                </div>--}}
                                        {{--                                                                <hr />--}}
                                        {{--                                                            </div>--}}
                                        {{--                                                        </div>--}}
                                        {{--                                                        <div class="col-md-12">--}}
                                        {{--                                                            <div class="pure-checkbox">--}}
                                        {{--                                                                <input--}}
                                        {{--                                                                id="allow_out_of_stock-@{{$index}}"--}}
                                        {{--                                                                form="product-form"--}}
                                        {{--                                                                name="allow_out_of_stock"--}}
                                        {{--                                                                type="checkbox"--}}
                                        {{--                                                                ng-model="v.allow_out_of_stock"--}}
                                        {{--                                                                ng-true-value="1" ng-false-value="0"--}}
                                        {{--                                                                >--}}
                                        {{--                                                                <label for="allow_out_of_stock-@{{$index}}">@lang('site.allow_customer')</label>--}}
                                        {{--                                                            </div>--}}
                                        {{--                                                        </div>--}}
                                        {{--                                                    </div>--}}
                                        {{--                                                </div>--}}
                                        {{--                                            </div>--}}
                                        {{--                                            <hr />--}}
                                        {{--                                        </div>--}}
                                        {{--                                    </div>--}}

                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>



                <div class="row pt-4">
                    <div class="col-sm-12 col-xs-12">
                        <h1 class="m-n font-thin h3 text-black">
                        <span class="text-left pl-4 mt-4">
                            <button type="submit" class="btn btn-primary" ng-click="Upadte(product)">Update</button>
                            <a href="{{ route('product.index') }}" class="btn btn-light">@lang('site.cancel')</a>
                        </span>
                        </h1>
                    </div>
                </div>





            @include('catalogue.variant.attribute', ['id' => 'modal-2', 'heading' => 'Attribute 2' ])
            @include('catalogue.variant.attribute2', ['id' => 'modal-2',  'heading' => 'Attribute 2' ])
            @include('catalogue.variant.attribute3', ['id' => 'modal-3', 'heading' => 'Attribute 3' ])


    {{--    @foreach($outlets as $outlet)--}}
    {{--        <span>{{$outlet['name']}}</span>--}}
    {{--    @endforeach--}}

@endsection

@section('scripts')
    <script type="text/javascript">
        function toggleIcon(e) {
            $(e.target)
                .toggleClass('glyphicon-chevron-right glyphicon-chevron-down');
        }

        $('.more-less').on('hidden.bs.collapse', toggleIcon);
        $('.more-less').on('shown.bs.collapse', toggleIcon);

        $('#values-1').amsifySuggestags();
        $('#values-2').amsifySuggestags();
        $('#values-3').amsifySuggestags();

        //--------------------------Open Attribute 1 Model--------------->
        function openAttribute_1_Model() {
            let Attribute1 = $('#attribute-1').val();
            let value1 = $("#values-1").val();

            if (Attribute1 != "" && value1 != "") {
                $('#attr_1').val(Attribute1);
                $('#value1').val(value1);
                $('#attribteModel_1').modal('show');
            } else {
                toastr.error('One or more value is required', 'Error');
            }

        }

        //--------------------------Open Attribute 1 Model--------------->
        function openAttribute_2_Model() {
            let Attribute2 = $('#attribute-2').val();
            let value2 = $("#values-2").val();
            if (Attribute2 != "" && value2 != "") {
                $('#attr_2').val(Attribute2);
                $('#value2').val(value2);
                $('#attribteModel_2').modal('show');
            } else {
                toastr.error('One or more value is required', 'Error');
            }

        }

        //--------------------------Open Attribute 1 Model--------------->
        function openAttribute_3_Model() {
            let Attribute3 = $('#attribute-3').val();
            let value3 = $("#values-3").val();

            if (Attribute3 != "" && value3 != "") {
                $('#attr_3').val(Attribute3);
                $('#value3').val(value3);
                $('#attribteModel_3').modal('show');
            } else {
                toastr.error('One or more value is required', 'Error');
            }

        }






        $('.getattribute1').on('keydown', function (event){
            if (event.keyCode == 13) {

                if ($('#values-1').val().split(",") != "") {
                    let value1 = $('#values-1').val().split(",");
                    const d = new Date();
                    let sku = 'SKU'+(d.getFullYear())+''+(d.getDate())+''+(d.getSeconds());
                    let id = Math.floor((Math.random() * 99) + 1);

                    $('#color1').text(value1);
                    $('#sku').val(sku);

                    $('#accordion').append('<div class="unique' + id + '"><input type="hidden" id="count'+id+'" value="'+id+'" />   <div class="form-group "><div class="row">' +
                        '<div class="col-md-1"><a role="row" data-toggle="collapse"data-parent="#accordion" href="#rowoutlet' + id + '"aria-expanded="true"><i class="details-control fa fa-chevron-right"></i></a></div>' +
                        '<div class="col-md-2 color_selector"><span class="colr1" id="color1"style="color: blue;"></span><span class="colr2" id="color2" style="color: red;"> </span><span class="colr3" id="color3" style="color: blue;"> </span></div>' +
                        '<div class="col-md-2"><input form="productform"  required id="sku"  name="variant[sku][]" value="" type="text"  class="form-control "></div><div class="col-md-2">' +
                        '<input form="productform" required name="variant[retail_price][]" type="float" class="sqty2 form-control" ng-model="v.retail_price" ng-change="MarkupChange(v.retail_price,v)"> </div>' +
                        '<div class="col-md-2"> <input form="productform" required name="variant[before_discount_price][]" type="float" ng-model="v.before_discount_price" class="sqty2 form-control"></div><div class="col-md-1"> ' +
                        '<div class="custom-control custom-switch center-align"><input type="checkbox" class="custom-control-input" > <label class="custom-control-label">&nbsp;</label></div> ' +
                        '</div> <div class="col-md-1"><a href="javascript:void(0)"  onclick="removeRow(' + id + ')"   ><i class="fa fa-trash"></i></a></div> </div> <div class="panel-collapse collapse" id="rowoutlet' + id + '"> <div class="row"> ' +
                        '<div class="col-md-12"   > <div class="row"> ' +
                        '<div class="col-md-3"><h4>Branches</h4></div></div> <hr/>' +
                        ' @foreach($outlets as $outlet)' +
                        '<div class="row">' +
                        '<div class="col-md-12"  >' +
                        '<div class="row">' +
                        '<div class="col-md-12"  > ' +
                        '<div class="row" style="margin-right: 50px !important; margin-left: 50px  !important ;  margin-bottom: -11px; margin-top: -7px;"> <div class="col-md-2 "><label class="pl-4">  {{$outlet['name']}}</label></div> ' +
                        '<div class="col-md-2"> <input type="number" class="form-control" ng-model="o.quantity" placeholder="Quantity"></div> <div class="col-md-2"> <input type="number" min="0" class="form-control" ng-model="o.re_order_point" placeholder="Re-Order Pt."> </div>' +
                        '<div class="col-md-2"> <input type="number" min="0" class="form-control" ng-model="o.re_order_quantity" placeholder="Re-Order Qty"> </div>' +
                        '<div class="col-md-2"> <input type="number" min="0" class="form-control" ng-model="o.supply_price" placeholder="Supply Price"> </div> ' +
                        '<div class="col-md-2"> <div class="btn-group" role="group" aria-label="..." style="width:100%;margin-top: -8px; "><input type="button" style="height: 43px; margin: 6px -1px;" class="border-0 bg-primary text-white col-md-6" value="0"/> <input type="button" style="height: 43px; margin: 6px -1px;" class=" border-0 bg-secondary text-white col-md-6" value="0%"/>' +
                        '</div> </div> </div> </div></div> <hr/>  </div> </div>' +
                        '@endforeach ' +
                        '<div class="col-md-12"> <div class="pure-checkbox"><input id="allow_out_of_stock-@{{$index}}" form="product-form" name="allow_out_of_stock" type="checkbox" ng-model="v.allow_out_of_stock" ng-true-value="1" ng-false-value="0"> <label for="allow_out_of_stock-@{{$index}}">Allow customer to purchase this product when it is out of stock</label></div> </div> </div> </div> </div> <hr/></div> </div>');

                }



            }


        });
        $('.getattribute2').on('keydown', function (event) {
            if (event.keyCode == 13) {
                let value2 = $('#values-2').val();
                // let  id = Math.floor((Math.random() * 99) + 1);
                let val = value2.split(",");
                $('#color2').text(val[0]);
            }
        });


        $('.getattribute3').on('keydown', function (event) {
            if (event.keyCode == 13) {
                let value3 = $('#values-3').val();
                // let  id = Math.floor((Math.random() * 99) + 1);
                let val = value3.split(",");
                $('#color3').text(val[0]);
            }
        });

        function removeRow(id){
            let rw = $('.unique'+id).remove();
            toastr.error("row number "+id+" is deleted", 'Error');

        }


    </script>
@endsection
