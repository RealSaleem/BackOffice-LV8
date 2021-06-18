<ul class="nav nav-tabs nav-justified" id="myTab" role="tablist">
    @php
    $index = 0;
    @endphp
    @foreach($model->languages as $language)
    <li class="nav-item greybg1">
        <a class="tab-section nav-link show {{ ($index == 0) ? 'active' : '' }}" data-lang="{{ strtolower($language['name']) }}" data-toggle="tab" href="#{{ strtolower($language['name']) }}" role="tab" aria-selected="false">
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
    @foreach($model->languages as $language)
    @php
        $name = 'name_' . $language['short_name'];
        $counter = 0;
    @endphp
    <div id="{{ strtolower($language['name']) }}" class="tab-pane fade show {{ ($index == 0) ? 'active' : '' }}">
        <div class="card-body">
            <div class="row">
                <div class="col-md-{{ $index == 0 ? '6' : '12' }}">
                    <div class="form-group">

                        <label>{{ __('backoffice.addon_name') }}</label>
                        @if($index == 0)
                        <span style="color: red">*</span>
                        @endif
                        <input type="text" form="addon_form" id="title_{{ $language['short_name'] }}_name" name="{{ $language['short_name'] }}.name" class="form-control" style="font-style:italic" type="text" @if($index==0) required @endif form="add_on_form" value="{{ old($name,$model->addon[$name]) }}">
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
                            @endif form="add_on_form" value="{{ old($name,$model->addon['code']) }}" >
                        </div>
                    </div>
                @endif
            </div>

            <hr />
            <div class="section-{{ $language['short_name'] }}">
                @foreach($model->addon['add_on_items'] as $it)
                <div class="row">
                    <div class="col-md-5">
                        <div class="form-group">
                            <label>{{ __('backoffice.item_name') }}</label>
                            @if($index == 0)
                            <span style="color: red">*</span>
                            <select class="select2 form-control" onchange="fillOption(this)" id="{{ $language['short_name'] }}-{{ $counter }}-name"   @if($index == 0) required @endif form="addon_form" name="{{ $language['short_name'] }}.items[{{ $counter }}][name]" style="width:100%">
                                <option value="">{{ __('backoffice.select_addon_item') }}</option>
                                @php
                                    $itemKey = $language['short_name'].'-name';
                                @endphp
                                @foreach($items as $item)
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
                            id="{{ $language['short_name'] }}-{{ $counter }}-name"
                            class="form-control"
                            placeholder="Item Name" style="font-style:italic"
                            type="text"
                            form="addon_form"
                            @if($index == 0)
                            required
                            @endif

                            >
                            {{-- <input type="text" id="title_{{ $language['short_name'] }}" form="addon_form" name="{{ $language['short_name'] }}.items[{{ $counter }}][name]" class="form-control" style="font-style:italic" type="text" @if($index==0) required @endif form="add_on_form"> --}}
                            @endif
                        </div>
                    </div>

                    @if($index == 0)
                    <div class="col-md-5">
                        <div class="form-group">
                            <label>{{ __('backoffice.price') }}</label>
                            <input id="{{ $language['short_name'] }}-{{ $counter }}-price" name="{{ $language['short_name'] }}.items[{{ $counter }}][price]" form="addon_form" class="form-control" style="font-style:italic" type="number" form="add_on_form">
                        </div>
                    </div>
                    @endif
                </div>
                @endforeach
            </div>
            @if($index == 0)
            <div class="row">
                <div class="col-md-12">
                    <button type="button" class="btn-primary btn-sm" onclick="renderAddOnItems()">{{ __('backoffice.add_another_item') }}</button>
                </div>
            </div>
            @endif
        </div>
    </div>
    @php
        $index++;
        $counter++;
    @endphp
    @endforeach
</div>
