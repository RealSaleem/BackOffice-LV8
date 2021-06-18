<div class="card bg-light mt-3 rounded  border-0  ">
    <div class="">
        <div class="row mt-4">
            <div class="col-md-8">
                <label>
                    <i class="icon-info pr-1" tabindex="0" data-toggle="popover" data-trigger="focus" title="" ></i>
                    {{ __('backoffice.add_products') }} <em style="color:red">*</em>
                    <small>{{ __('backoffice.add_products_message') }}</small>
                </label>
                <div class="input-group">
                    <div class="input-group-prepend">
                        <span class="input-group-text" id="validationTooltipUsernamePrepend"><i class="icon-magnifier"></i></span>
                    </div>
                    <input type="text" class="form-control typehead" form="product-form" name="{{ __('backoffice.search_product') }}" id="search_prod">
                </div>
            </div>
            <div class="col-md-4">
                <label class="btn-block">&nbsp;</label>
                <a href="javascript:;" class="btn btn-primary btn-block add_search_prod" id="add_search_prod">{{ __('backoffice.add') }}</a>
            </div>
        </div>

        @php
            $pr_total =0;
            $pr_total_cost = 0;
            $counter = 0;
        @endphp


        <div class="table-responsive">
            <table class="table  table-striped mt-4">
                <tbody>

                    <tr class="rounded">
                        <th class="align-middle">{{ __('backoffice.item') }}</th>
                        <th class="align-middle">{{ __('backoffice.quantity') }}</th>
                        <th class="align-middle">{{ __('backoffice.unit') }}</th>
                        <th class="align-middle">{{ __('backoffice.retail_price') }}</th>
                        <th class="align-middle">{{ __('backoffice.action') }}</th>
                    </tr>

                    <tbody id="element">
                        @if(sizeof($model->composite_products) > 0)
                            @foreach($model->composite_products as $composite)
                            <tr id="combo-{{$composite->product_variant->id}}">

                                <td style="width: 40%">
                                    <input type="hidden" name="composite_products[{{$counter}}][id]" form="product-form" value="{{$model->composite->product_variant->id}}">
                                    <input type="hidden" name="composite_products[{{$counter}}][name]" form="product-form" value="{{$model->composite->product_variant->name}}">
                                    <input type="hidden" name="composite_products[{{$counter}}][retail_price]" form="product-form" value="{{$model->composite->product_variant->retail_price}}">
                                    <span>{{$model->composite->product_variant->name}}</div>
                                </td>

                                <td>
                                    <input type="number" id="quantity-{{$model->composite->product_variant->id}}" onchange="calculatePrice(this.value,{{$model->composite->product_variant->id}})"
                                    form="product-form"   name="composite_products[{{$counter}}][quantity]"  class="form-control form-rounded" placeholder="Quantity" value="{{$model->composite->quantity}}"
                                    min="0.0001" step="any">
                                </td>

                                <td>{{isset($model->composite->product_variant->product->unit) ? $model->composite->product_variant->product->unit : ''}}</td>

                                <td>
                                <span  id="retail_price-{{ $model->composite->product_variant->id }}" data-retail-price="{{ $model->composite->product_variant->retail_price}}" class="retail_prices" > {{ $store_currency }} {{$model->composite->product_variant->retail_price  * $model->composite->quantity}}</span>
                                @php
                                $total = $model->composite->quantity * $model->composite->product_variant->retail_price;
                                $pr_total += $total;

                                $total_cost = $model->composite->quantity * $model->composite->product_variant->supplier_price;
                                $pr_total_cost += $total_cost;

                                $counter ++;
                                @endphp
                                <!-- Total: {{$total}} -->
                                </td>
                                <td> <i class="fa fa-trash" onClick="deleteRow({{$model->composite->product_variant->id}})"></i></td>
                            </tr>
                            @endforeach
                        @endif
                    </tbody>
                </tbody>
            </table>
        </div>
        <p class="text-right mt-3">{{ __('backoffice.total_item_price') }} <b>{{ $model->currency }} <span id="total">{{ $pr_total }}</span></b></p>
    </div>
</div>
