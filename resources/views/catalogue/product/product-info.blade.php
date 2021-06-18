<div class="card-body pb-0 pt-0">
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
    <div class="tab-content mt-4" id="myTabContent">
        @php
        $index = 0;
        @endphp
        @foreach($model->languages as $language)
        @php
        $title = 'title_' . strtolower($language['short_name']);
        $description = 'description_' . strtolower($language['short_name']);
        @endphp
        <div id="{{ strtolower($language['name']) }}" class="tab-pane fade show {{ ($index == 0) ? 'active' : '' }}">
            <div class="card-body">
                <label>{{ __('backoffice.name') }} @if($index == 0)<em style="color:red">*</em>@endif </label>
                <input type="text" id="title_{{ strtolower($language['short_name']) }}" name="title_{{ strtolower($language['short_name']) }}" class="form-control" form="product-form" @if($index==0) required @endif value="{{ isset($model->product[$title]) ? $model->product[$title] : '' }}" />
            </div>
            <div class="col-md-12">
                <div class="form-group">
                    <label for="exampleInputEmail1">{{ __('backoffice.description') }} </label>
                    <textarea name=" description_{{ strtolower($language['short_name']) }}" id="editor-{{ strtolower($language['name']) }}" class="form-control rounded product_description" form="product-form" rows="15">
                        {{ isset($model->product[$description]) ? $model->product[$description] : '' }}
                    </textarea>
                </div>
            </div>
        </div>
        @php
        $index++;
        @endphp
        @endforeach
    </div>
</div>
