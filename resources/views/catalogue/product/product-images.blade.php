<div class="card bg-light mt-4  rounded  border-0">
    <div class="card-body">
        <label>
            <i class="icon-info pr-1" tabindex="0" data-toggle="popover" data-trigger="focus" ></i>
            {{ __('backoffice.add_images') }}
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
