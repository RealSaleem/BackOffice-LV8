<div class="card bg-light mt-3  rounded  border-0">
    <hr>
    <div class=" pt-0">
        <div class="form-row pt-0">
            <div class="col-sm-12 pl-0">
                <label>
                    <i class="icon-info pr-1" tabindex="0" data-toggle="popover" data-trigger="focus" ></i>
                    {{ __('backoffice.pricing') }}
                </label>
            </div>
            <div class="col-sm-6">
                <label>{{ __('backoffice.retail_price') }} ( {{ $model->currency }} )<em style="color:red">*</em></label>
                <input type="number" min="0" form="product-form" name="retail_price" class="form-control" required id="retail_price" value="{{ isset($model->product['retail_price']) ? $model->product['retail_price'] : ''  }}" >
            </div>
            <div class="col-sm-6">
                <label>{{ __('backoffice.before_discount_price') }} ( {{ $model->currency }} )</label>
                <input type="number" min="0" form="product-form" name="before_discount_price" class="form-control"  value="{{ isset($model->product['before_discount_price']) ? $model->product['before_discount_price'] : ''  }}" >
            </div>
        </div>
        <div class="form-row mt-3">
            <div class="col-sm-12">
                <label>
                    <i class="icon-info pr-1" tabindex="0" data-toggle="popover" data-trigger="focus" ></i>
                    {{ __('backoffice.branches') }}
                </label>
            </div>
        </div>
        <hr>
        @foreach($model->outlets as $outlet)
            @if($model->edit_mode)
                @php
                    $index = $outlet['id'];

                    $diff = (float)$model->product['retail_price'] - (float)$outlet['supply_price'];
                    if((float)$model->product['retail_price'] > 0){
                            $margin = $diff / (float)$model->product['retail_price'] * 100;
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

            <div class="form-row mt-3">
                <div class="col-sm-12">
                    <span class="btn-block mb-2">{{ $outlet['name'] }}</span>
                </div>
                <div class="col-sm-3">
                    <input type="number" step="any" min="0" form="product-form" onchange="calculateMargin(this.value,{{$index}})" class="form-control mt-3"
                    name='branches[{{ $index }}][supply_price]' placeholder="Supply Price" value="{{ $outlet['supply_price'] }}" >
                </div>
                <div class="col-sm-3">
                    <input type="hidden" form="product-form"  name="branches[{{ $index }}][id]" value="{{ $outlet['outlet_id'] }}">
                    <input type="number" step="any" form="product-form" class="form-control mt-3" name='branches[{{ $index }}][quantity]' placeholder="Quantity" value="{{ $outlet['quantity'] }}">
                </div>
                <div class="col-sm-3">
                    <input type="number" step="any" min="0"form="product-form" class="form-control mt-3"
                    name='branches[{{ $index }}][re_order_point]'placeholder="Re-Order Pt."value="{{ $outlet['re_order_point'] }}" >
                </div>
                <div class="col-sm-3">
                    <input type="number" step="any" min="0" form="product-form" class="form-control mt-3"
                    name='branches[{{ $index }}][re_order_quantity]' placeholder="Qty Re-Order" value="{{ $outlet['re_order_quantity'] }}">
                </div>
            </div>

            <div class="row no-gutters mt-3">
                <div class="col-sm-3">
                    <div class="input-group">
                        <input type="button" class="form-control border-0 bg-primary text-white" id="diff-{{ $index }}" value="{{ $diff }}" />
                        <input type="button" class="form-control border-0 bg-secondary text-white" id="margin-{{ $index }}" value="{{ $margin }} %" />
                    </div>
                </div>
                <div class="col-sm-12 mt-3">
                    <div class="custom-control custom-checkbox">
                        <input id="allow_out_of_stock_{{ $outlet['outlet_id'] }}" class="custom-control-input" form="product-form" name="allow_out_of_stock" type="checkbox"  value="1"
                            {!! old('allow_out_of_stock', $model->product['allow_out_of_stock']) ? ' checked' : '' !!} />
                        <label for="allow_out_of_stock_{{ $outlet['outlet_id'] }}" class="custom-control-label text-secondary">{{ __('backoffice.allow_customer') }}</label>
                    </div>
                </div>
            </div>
            <hr>
        @endforeach
    </div>
</div>
