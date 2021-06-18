<ul class="nav nav-tabs nav-justified">
    @php
    $index = 0;
    @endphp
    @foreach($model->languages as $language)
        <li class="nav-item greybg1">
            <a class="tab-section nav-link show {{ ($index == 0) ? 'active' : '' }}" data-lang="{{ $language['name'] }}" data-toggle="tab" href="#{{ $language['name'] }}">
            {{ $language['name'] }}
            </a>
        </li>
    @php
    $index++;
    @endphp
    @endforeach
</ul>

<div class="tab-content mt-4">
    <input type="hidden" name="type" value="{{ $type }}" />
    <input type="hidden" name="is_active" value="1" />
    @php
    $index = 0;
    @endphp
    @foreach($model->languages as $language)
    @php
    $name = 'name_' . $language['short_name'];
    $counter = 0;
    @endphp
    <div id="{{ $language['name'] }}" class="tab-pane fade show {{ ($index == 0) ? 'active' : '' }}">
        <div class="card-body">
            <div class="row">
                <div class="col-md-{{ $index == 0 ? '6' : '12' }}">
                    <div class="form-group">
                        <label>{{ __('backoffice.addon_name') }}</label>
                        @if($index == 0)
                        <span style="color: red">*</span>
                        @endif
                        <input
                        type="text"
                        name="{{ $language['short_name'] }}.name"
                        class="form-control"
                        style="font-style:italic"
                        type="text"
                        @if($index == 0)
                        required
                        @endif

                        value="{{ old($name,$addon[$name]) }}" >
                    </div>
                </div>

                @if($index == 0)
                    <div class="col-md-6">
                        <div class="form-group">
                            <label>@lang('backoffice.code')</label>
                            @if($index == 0)
                            <span style="color: red">*</span>
                            @endif
                            <input
                            type="text" id="{{ $language['short_name'] }}-code" name="code" class="form-control" style="font-style:italic" type="text"
                            @if($index == 0)
                            required
                            @endif value="{{ old('code') }}" >
                        </div>
                    </div>
                @endif
            </div>

            <hr />
            <div class="section-{{ $language['short_name'] }}">
                @foreach($addon['add_on_items'] as $it)
                <div class="row">
                    <div class="col-md-5">
                        <div class="form-group">
                            <label>{{ __('backoffice.item_name') }}</label>
                            @if($index == 0)
                            <span style="color: red">*</span>
                            <select class="select2 form-control" onchange="fillOption(this)"
                                @if($type == 'add_on')
                                id="{{ $language['short_name'] }}-{{ $counter }}-name"
                                @endif
                                @if($index == 0) required @endif
                                name="{{ $language['short_name'] }}.items[{{ $counter }}][name]"
                                style="width:100%"
                            >
                                <option value="">{{ __('backoffice.select_addon_item') }}</option>
                                @php
                                    $itemKey = $language['short_name'].'-name';
                                @endphp
                                @foreach($model->items as $item)
                                    @php
                                        $info = json_encode($item);
                                    @endphp
                                    <option value="{{ $item[$itemKey] }}" data-info="{{ $info }}" >{{ $item[$itemKey] }}</option>
                                @endforeach
                            </select>
                            @else
                                <input
                                type="text"
                                name="{{ $language['short_name'] }}.items[{{ $counter }}][name]"
                                @if($type == 'add_on')
                                id="{{ $language['short_name'] }}-{{ $counter }}-name"
                                @endif
                                class="form-control"
                                placeholder="Add Item Name" style="font-style:italic"
                                type="text"
                                @if($index == 0)
                                required
                                @endif
                                >
                            @endif
                        </div>
                    </div>

                    @if($index == 0)
                        <div class="col-md-5">
                            <div class="form-group">
                                <label>{{ __('backoffice.price') }}</label>
                                <input
                                id="{{ $language['short_name'] }}-{{ $counter }}-price"
                                name="{{ $language['short_name'] }}.items[{{ $counter }}][price]"
                                class="form-control"
                                style="font-style:italic"
                                type="number"
                                >
                            </div>
                        </div>
                    @endif
                </div>
                @endforeach
            </div>
            @if($index == 0)
            <div class="row">

                <div class="col-md-6">
                    <button type="button" class="btn-sm btn-primary " onclick="renderAddOnItems()">{{ __('backoffice.add_another_item') }}</button>
                </div>
                <div class="col-md-3">
                    <div class="form-group">
                        <div class="custom-control custom-checkbox">
                            <input id="type-{{ $type }}" name="is_active" type="checkbox" value="1" class="custom-control-input">
                            <label class="custom-control-label ml-2" for="type-{{ $type }}">{{ __('backoffice.active') }}</label>
                        </div>
                    </div>
                </div>
            </div>
            @if($type == 'add_on')
            <hr/>
            <div class="row">
                <div class="col-md-6">
                    <div class="form-group">
                        <div class="pure-checkbox" >
                            <label>{{ __('backoffice.min') }}</label>
                            <input name="min" id="addon-min" class="form-control" type="number" min="0"  >
                        </div>
                    </div>
                </div>
                <div class="col-md-6">
                    <div class="form-group">
                        <div class="pure-checkbox" >
                            <label>{{ __('backoffice.max') }}</label>
                            <input name="max" id="addon-max" class="form-control" type="number" min="0"  >
                        </div>
                    </div>
                </div>
            </div>
            @endif
            @endif
        </div>
    </div>
    @php
    $index++;
    $counter++;
    @endphp
    @endforeach
</div>
