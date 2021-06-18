<ul class="nav nav-tabs nav-justified" id="myTab" role="tablist">
    @php
    $index = 0;
    @endphp
    @foreach($model->languages as $language)
    <li class="nav-item greybg1">
        <a class="tab-section nav-link show {{ ($index == 0) ? 'active' : '' }}" data-lang="{{ strtolower($language['name']) }}" data-toggle="tab" href="#{{ strtolower($language['short_name']) }}" role="tab" aria-selected="false">
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

    @foreach($model->addon as $add_on)
    @php
    $name = $add_on['language_key'].'.name';
    @endphp

    <div id="{{ $add_on['language_key'] }}" class="tab-pane fade show {{ ($index == 0) ? 'active' : '' }}">
        <div class="card-body">
            <div class="row">
                <div class="col-md-{{ $index == 0 ? '6' : '12' }}">
                    <div class="form-group">

                        <label>@lang('backoffice.addon_name')</label>
                        @if($index == 0)
                        <span style="color: red">*</span>
                        @endif
                        <input type="text" id="title_{{ $add_on['language_key'] }}_name" name="{{ $add_on['language_key'] }}.name" class="form-control"  style="font-style:italic" type="text" @if($index==0) required @endif form="addon_form" value="{{ $add_on['name'] }}">
                    </div>
                </div>
                @if($index == 0)
                    <div class="col-md-6">
                        <div class="form-group">
                            <label>@lang('backoffice.code')</label>
                            <input
                            type="text" id="{{ $add_on['language_key'] }}-code" name="code" class="form-control" style="font-style:italic" type="text"
                            @if($index == 0)
                            required
                            @endif form="addon_form" value="{{ old('code',$add_on['code']) }}" disabled>
                        </div>
                    </div>
                @endif
            </div>

            <hr />
            <div class="section-{{ $add_on['language_key'] }}">
                @php
                    $counter = 0;
                @endphp
                @foreach($add_on['items'] as $it)
                <div class="row delete-{{ $counter }}">
                    <div class="col-md-5">
                        <div class="form-group">
                            <label>{{ __('backoffice.item_name') }}</label>
                            @if($index == 0)
                                <span style="color: red">*</span>
                                <select class="select2 form-control" onchange="fillOption(this)" id="{{ $add_on['language_key'] }}-{{ $counter }}-name"   @if($index == 0) required @endif form="addon_form" name="{{ $it['language_key'] }}.items[{{ $counter }}][name]" style="width:100%">
                                    <option value="">{{ __('backoffice.select_addon_item') }}</option>
                                    @php
                                        $itemKey = $add_on['language_key'].'-name';
                                    @endphp
                                    @foreach($items as $item)
                                        @php
                                            $info = json_encode($item);
                                        @endphp
                                        <option value="{{ $item[$itemKey] }}" data-info="{{ $info }}" {{ $it['name'] == $item[$itemKey] ? "selected" :"" }} >{{ $item[$itemKey] }}</option>
                                    @endforeach
                                </select>
                            @else
                                <input
                                type="text"
                                id="{{ $add_on['language_key'] }}-{{ $counter }}-name"
                                name="{{ $it['language_key'] }}.items[{{ $counter }}][name]"
                                class="form-control"
                                placeholder="Add Item Name" style="font-style:italic"
                                type="text"
                                @if($index == 0)
                                required
                                @endif
                                form="addon_form"
                                value="{{ $it['name'] }}"
                                >
                            @endif
                        </div>
                    </div>

                    @if($index == 0)
                        <div class="col-md-5">
                            <div class="form-group">
                                <label>@lang('backoffice.price')</label>
                                <input
                                name="{{ $it['language_key'] }}.items[{{ $counter }}][price]"
                                id="{{ $add_on['language_key'] }}-{{ $counter }}-price"
                                class="form-control"
                                placeholder="Add On price" style="font-style:italic"
                                type="number"
                                form="addon_form"
                                step="any" min="0"
                                value="{{ $it['price'] }}"
                                >
                            </div>
                        </div>
                        <div class="col-md-2">
                            <div class="form-group">
                                <i class="fa fa-trash " style="margin-top: 40%;" onclick="$('.delete-{{ $counter }}').remove()"></i>
                            </div>
                        </div>
                    @endif
                    </div>
                    @php
                        $counter++;
                    @endphp
                @endforeach
            </div>
            <div class="row">
                <div class="col-md-12">
                    <button type="button" class="btn-primary btn-sm" onclick="renderAddOnItems()">{{ __('backoffice.add_another_item') }}</button>
                </div>
            </div>
        </div>
    </div>
    @php
    $index++;
    @endphp

    @endforeach
</div>
