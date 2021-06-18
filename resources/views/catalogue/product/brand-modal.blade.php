<div class="modal fade" id="brandModal" tabindex="-1" role="dialog" aria-labelledby="exampleModalLabel" aria-hidden="true">
    <div class="modal-dialog" role="document">
        <form method="POST" action="{{ route('api.add.brand') }}" id="brand-form">
            {{csrf_field()}}
            <div class="modal-content">
                <div class="modal-header">
                    <h4 class="modal-title">{{ __('backoffice.add') }}</h4>
                    <button type="button" class="close pull-right" data-dismiss="modal">&times;</button>
                </div>
                <div class="modal-body">
                    <ul class="nav nav-tabs nav-justified" id="myTab" role="tablist">
                        @php
                        $index = 0;
                        @endphp
                        @foreach($model->languages as $language)
                        <li class="nav-item greybg1">
                            <a class="tab-section nav-link show {{ ($index == 0) ? 'active' : '' }}" data-toggle="tab" href="#brand-{{ strtolower($language['name']) }}" role="tab" aria-selected="false">
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
                        @endphp
                        <div id="brand-{{ strtolower($language['name']) }}" class="tab-pane fade show {{ ($index == 0) ? 'active' : '' }}">
                            <div class="card-body">
                                <i class="icon-info pr-1" tabindex="0" data-toggle="popover" data-trigger="focus" title="" data-content="Enter your brands name" data-original-title=></i>
                                <label>{{ __('backoffice.name')  }}
                                    @if($index == 0)
                                    <em style="color: red">*</em>
                                    @endif </label>
                                <input type="text" name="title_{{ strtolower($language['short_name']) }}" class="form-control brand-name" form="brand-form" >
                            </div>
                        </div>
                        @php
                        $index++;
                        @endphp
                        @endforeach
                        <input type="hidden" name="name" form="brand-form" id="brand-name" >
                        <input type="hidden" name="active" form="brand-form" value="1" >
                    </div>
                </div>
                <div class="modal-footer">
                    <button type="submit" class="btn-primary btn-sm pull-left">{{ __('backoffice.save') }}</button>
                </div>
            </div>
        </form>
    </div>
</div>
