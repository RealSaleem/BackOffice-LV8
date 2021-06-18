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
                                @lang('site.add_variant')
                                <a href="{{route('product.index')}}" class="m-b-xs w-auto btn-primary btn-sm pull-right">
                                    {{ __('variant.back') }}
                                </a>
                            </h1>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>

<div class="app-content-body" ng-app="posApp" ng-controller="baseCtrl">
    <div class="hbox hbox-auto-xs hbox-auto-sm" ng-controller="productCtrl" ng-init="loadData()">
        <div class="bg-light lter b-b wrapper-md">
            <div class="row">
                <div class="col-sm-12 col-xs-12">
                    <h1 class="m-n font-thin h3 text-black">
                        @lang('site.add_variant')
                        <span class="pull-right">
                            <button type="submit" class="btn btn-success" ng-click="Save(product)">Save</button>
                           <a href="{{url('catalogue/product')}}/{{$id}}/edit" class="btn btn-success">@lang('site.back')</a>
                            <a href="{{ route('product.index') }}" class="btn btn-danger">@lang('site.cancel')</a>
                        </span>
                    </h1>
                </div>
            </div>
        </div>
        <div class="wrapper-md">
            <div class="col-md-12">
                <div class="row">
                    <div class="panel panel-default panel-body">
                        <div>
                            <h4>@lang('site.variants')</h4>
                            <p style="font-style: italic; color: grey;">@lang('site.var_att')</p>
                        </div>
                        <div class="row">
                            <div class="col-md-4">
                                <label>@lang('site.attribute')</label> <span style="color: grey;">@lang('site.eg_variant')</span>
                            </div>
                            <div class="col-md-8 form-inline">
                                <label>Value</label> <span style="color: grey;">@lang('site.eg_color')</span>
                            </div>
                        </div>
                        <div class="row">
                            <div class="col-md-4">
                                <input form="productform" id="attribute-1" class="form-control rounded " placeholder="Color" ng-model="product.attribute_1" type="text">
                            </div>
                            <div class="col-md-6 form-inline">
                                <md-chips ng-model="product.attr1_values" name="attr1_values" id="values-1" placeholder="@lang('site.Press_Enter_Once_Added')">
                                    <md-chip-template>
                                        <strong ng-cloak>@{{$chip}}</strong>
                                    </md-chip-template>
                                </md-chips>
                            </div>
                            <div class="col-md-2">
                                <button type="button" class="btn btn-styled" ng-click="validateAttribute(1)">@lang('site.add_trans')</button>
                            </div>
                        </div>

                        <div class="row">
                            <div class="col-md-4">
                                <input form="productform" id="attribute-2" class="form-control rounded " placeholder="Size" ng-model="product.attribute_2" type="text">
                            </div>
                            <div class="col-md-6 form-inline">
                                <md-chips ng-model="product.attr2_values" id="values-2" name="attr2_values" placeholder="@lang('site.Press_Enter_Once_Added')">
                                    <md-chip-template>
                                        <strong ng-cloak>@{{$chip}}</strong>
                                    </md-chip-template>
                                </md-chips>
                            </div>
                            <div class="col-md-2">
                                <button type="button" class="btn btn-styled" ng-click="validateAttribute(2)">@lang('site.add_trans')</button>
                            </div>
                        </div>
                        <div class="row">
                            <div class="col-md-4">
                                <input form="productform" id="attribute-3" class="form-control rounded" placeholder="Material" ng-model="product.attribute_3" type="text">
                            </div>
                            <div class="col-md-6 form-inline">
                                <md-chips ng-model="product.attr3_values" id="values-3" name="attr3_values" placeholder="@lang('site.Press_Enter_Once_Added')">
                                    <md-chip-template>
                                        <strong ng-cloak>@{{$chip}}</strong>
                                    </md-chip-template>
                                </md-chips>
                            </div>
                            <div class="col-md-2">
                                <button type="button" class="btn btn-styled" ng-click="validateAttribute(3)">@lang('site.add_trans')</button>
                            </div>
                        </div>

                        <br />
                        <hr />
                        <br />
                        <div class="row">
                            <div class="col-md-5">
                                <h3>
                                    <span data-toggle="tooltip" class="tooltipTest" data-placement="top" title="Generate code for products">
                                    <i class="fa fa-info-circle" ></i></span> @lang('site.stock_keeping_unit')
                                </h3>
                            </div>
                            <div class="col-md-4">
                                <label for="">@lang('site.unit')</label>
                            </div>
                        </div>
                        <div class="row">
                            <div class="col-md-4">
                                @if($product->sku_type == 'name' || $product->sku_type == 'number')
                                <input
                                        id="auto_sku_input"
                                        type="text"
                                        form="product-form"
                                        class="form-control form-rounded show"
                                        name="sku"
                                        placeholder="Ex.1000 or FGK229911"
                                        style="font-style:italic;"
                                        ng-model="product.sku"
                                        readonly
                                        >
                                @else
                                    <input
                                        id="auto_sku_input"
                                        type="text"
                                        form="product-form"
                                        class="form-control form-rounded show"
                                        name="sku"
                                        placeholder="Ex.1000 or FGK229911"
                                        style="font-style:italic;"
                                        readonly
                                        value={{ $product->sku_type == 'custom' || $product->sku_type == 'custom_sku' ? $product->prefix : '' }}
                                    >
                                @endif
                            </div>
                            <div class="col-md-1"></div>
                            <div class="col-md-4 form-group" id="show-unit">
                            @if(!is_null($product['unit']))
                                <select name="unit" class="form-control m-b" form="product-form" readonly disabled >
                                    <option>@lang('site.select_unit')</option>
                                    <option value="kg" {{ $product->unit == 'kg' ? "selected" : "" }} >@lang('site.kilogram')</option>
                                    <option value="l" {{ $product->unit == 'l' ? "selected" : "" }}>@lang('site.liter')</option>
                                    <option value="m" {{ $product->unit == 'm' ? "selected" : "" }}>@lang('site.meter')</option>
                                </select>                    `
                            @endif
                            </div>
                        </div>
                        <div class="row">
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

                        <div class="panel-group" id="accordion" role="tablist" aria-multiselectable="true">
                            <div class="form-group" ng-repeat="v in product.variants">
                                <div class="row">
                                    <div class="col-md-1">
                                        <a role="button" data-toggle="collapse" data-parent="#accordion" href="#collapse@{{ $index }}" aria-expanded="true">
                                            <i class="more-less glyphicon glyphicon-chevron-right" onClick="toggleIcon()"></i>
                                        </a>
                                    </div>
                                    <div class="col-md-2 color_selector">
                                        <span class="colr1">
                                        @{{ v.attribute_value_1 }}
                                        </span>
                                        <span class="colr2" ng-if="v.attribute_value_2">
                                        @{{ v.attribute_value_2 }}
                                        </span>
                                        <span class="colr3" ng-if="v.attribute_value_3">
                                        @{{ v.attribute_value_3 }}
                                        </span>
                                    </div>
                                    <div class="col-md-2">
                                        <input form="productform" required ng-disabled="!v.editable" name="variant[sku][]" type="text" title="@{{v.sku}}" ng-model="v.sku" class="sqty2 form-control">
                                    </div>
                                    <div class="col-md-2">
                                        <input form="productform" required name="variant[retail_price][]" type="number" min="0" class="sqty2 form-control" ng-model="v.retail_price" ng-change="MarkupChange(v.retail_price,v)">
                                    </div>
                                    <div class="col-md-2">
                                        <input form="productform" required name="variant[before_discount_price][]" type="number" min="0" ng-model="v.before_discount_price" class="sqty2 form-control">
                                    </div>
                                    <div class="col-md-1">
                                   <label class="i-switch m-t-xs m-r">
                                   <input ng-model="v.is_active" ng-true-value="true" ng-false-value="false" type="checkbox">
                                   <i></i>
                                   </label>
                                    </div>

                                    <div class="col-md-1">
                                        <a ng-if="product.variants.length > 0" href="javascript:void(0)" ng-click="removeVariant(v)" class="remove_combo_row"><i class="fa fa-trash"></i></a>
                                    </div>
                                </div>
                                <div id="collapse@{{ $index }}" class="panel-collapse collapse" role="tabpanel" aria-labelledby="headingOne">
                                    <div class="row">
                                        <div class="col-md-12">
                                            <div class="row">
                                                <div class="col-md-1"></div>
                                                <div class="col-md-2"><h4>@lang('site.branches')</h4></div>
                                                <div class="col-md-7"></div>
                                            </div>
                                            <div class="row">
                                                <div class="col-md-12" ng-repeat="o in v.outlets">
                                                    <div class="row">
                                                        <div class="col-md-2"></div>
                                                        <div class="col-md-2">
                                                            <label>@{{ o.name }}</label>
                                                        </div>
                                                        <div class="col-md-7">
                                                            <div class="row">
                                                                <div class="col-md-4">
                                                                    <input type="number" min="0" class="form-control" ng-model="o.quantity" placeholder="Quantity">
                                                                </div>
                                                                <div class="col-md-4">
                                                                    <input type="number" min="0"  class="form-control" ng-model="o.re_order_point" placeholder="Re-Order Pt.">
                                                                </div>
                                                                <div class="col-md-4">
                                                                    <input type="number" min="0"  class="form-control" ng-model="o.re_order_quantity" placeholder="Re-Order Qty">
                                                                </div>
                                                            </div>
                                                            <div class="row">
                                                                <div class="col-md-4">
                                                                    <input type="number" min="0"  class="form-control" ng-model="o.supply_price" placeholder="Supply Price">
                                                                </div>
                                                                <div class="col-md-4">
                                                                    <div class="btn-group" role="group" aria-label="..." style="width:100%">
                                                                        <input type="button" class="btn btn-default col-md-6" value="@{{ calculateDiff(v,o) }}" />
                                                                        <input type="button" class="btn btn-default col-md-6" value="@{{ calculateMargin(v,o) }}" />
                                                                    </div>
                                                                </div>
                                                            </div>
                                                        </div>
                                                        <div class="col-md-1"></div>
                                                    </div>
                                                    <hr />
                                                </div>
                                            </div>
                                            <div class="col-md-12">
                                                <div class="pure-checkbox">
                                                    <input
                                                    id="allow_out_of_stock-@{{$index}}"
                                                    form="product-form"
                                                    name="allow_out_of_stock"
                                                    type="checkbox"
                                                    value="1"
                                                    ng-model="v.allow_out_of_stock"
                                                    >
                                                    <label for="allow_out_of_stock-@{{$index}}">@lang('site.allow_customer')</label>
                                                </div>
                                            </div>
                                        </div>
                                    </div>
                                </div>
                                <hr />
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>

        @include('catalogue.variant.attribute', ['id' => 'modal-1', 'languages' => $languages , 'heading' => 'Attribute 1' ])
        @include('catalogue.variant.attribute2', ['id' => 'modal-2', 'languages' => $languages , 'heading' => 'Attribute 2' ])
        @include('catalogue.variant.attribute3', ['id' => 'modal-3', 'languages' => $languages , 'heading' => 'Attribute 3' ])
    </div>
</div>

@endsection

@push('scripts')
<script type="text/javascript">
    var sku_type = '{{ $product->sku_type }}';
</script>
<script src="{{CustomUrl::asset('js/angular-app/services/data/store-data-service.js')}} "></script>
<script src="{{CustomUrl::asset('js/angular-app/services/data/product-data-service.js')}} "></script>
<script src="{{CustomUrl::asset('js/angular-app/services/data/currency-data-service.js')}} "></script>
<script src="{{CustomUrl::asset('js/angular-app/services/data/category-data-service.js') }}"></script>
<script src="{{CustomUrl::asset('js/angular-app/services/data/tag-data-service.js') }}"></script>
<script src="{{CustomUrl::asset('js/angular-app/services/data/supplier-data-service.js') }}"></script>
<script src="{{CustomUrl::asset('js/angular-app/services/data/city-data-service.js')}} "></script>
<script src="{{CustomUrl::asset('js/angular-app/services/data/brand-data-service.js') }}"></script>
<script src="{{CustomUrl::asset('js/angular-app/services/data/variant-data-service.js') }}"></script>
<script src="{{CustomUrl::asset('js/angular-app/services/data/outlet-data-service.js') }}"></script>
<script src="{{CustomUrl::asset('js/angular-app/controllers/product/add-product-controller.js') }}"></script>
<script src="{{CustomUrl::asset('js/angular-app/services/language-service.js')}} "></script>
<script type="text/javascript">
    function toggleIcon(e) {
        $(e.target)
            .toggleClass('glyphicon-chevron-right glyphicon-chevron-down');
    }
    $('.more-less').on('hidden.bs.collapse', toggleIcon);
    $('.more-less').on('shown.bs.collapse', toggleIcon);
</script>
@endpush
