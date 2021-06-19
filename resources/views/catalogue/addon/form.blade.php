<!-- content -->

@extends('layouts.backoffice')
@section('content')
<!-- content -->
<div class="container-fluid">
    <div class="row">
        <div class="col-sm-12 pl-0 pr-0">
            <div class="greybg1 rounded p-4 mb-3">
                <div class="row">
                    <div class="col-sm-12">
                        <div class="common_title">
                            <h1>
                                {{ $model->title }}
                                <a href="{{ route('addon.index') }}" class="m-b-xs w-auto btn-primary btn-sm pull-right">
                                    {{ __('backoffice.back') }}
                                </a>
                            </h1>
                        </div>
                    </div>
                </div>
            </div>
        </div>
        <form id="addon_form" action="{{ $model->route }}" method="POST">
            {{ csrf_field() }}
            @if($model->edit_mode)
            <input type="hidden" name="identifier" value="{{ $model->addon[0]['identifier'] }}">
            @endif
        </form>
        <div class="col-sm-8 pl-0 pr-0">
            <div class="card bg-light mt-3  rounded  border-0">
                <div class="card-body pb-0 pt-0">
                    @if($model->edit_mode)
                        @include('catalogue/addon/edit-section')
                    @else
                        @include('catalogue/addon/section')
                    @endif

                </div>
            </div>
        </div>

        @php
            $check = $model->edit_mode ? $model->addon[0]['is_active'] : $model->addon['is_active'];
            $type = $model->edit_mode ? $model->addon[0]['type'] : $model->addon['type'];
            $add_on_min = $model->edit_mode ? $model->addon[0]['min'] : $model->addon["min"];
            $add_on_max = $model->edit_mode ? $model->addon[0]['max'] : $model->addon["max"];
        @endphp

        <div class="col-sm-4">
            <div class="rounded p-4 ">
                <div class="card p-3 rounded">
                    <div class="card p-3 rounded mt-2">
                        <div class="custom-control custom-checkbox">
                            <input id="customCheck10" name="is_active" form="addon_form" type="checkbox" class="custom-control-input" value="1" {{ $check ? 'checked' : '' }}>
                            <label class="custom-control-label ml-2" for="customCheck10">{{ __('backoffice.active') }}</label>
                        </div>
                    </div>
                </div>

                <div class="card p-3 rounded mt-4">
                    <label for="type">{{ __('backoffice.type') }}</label>
                    <div class="card p-3 rounded mt-2">
                        <div class="custom-control custom-radio">
                            <input type="radio" class="custom-control-input addon-type" form="addon_form" id="customRadio" name="type" value="add_on" {{ $type ==  "add_on" ? "checked" : '' }}>
                            <label class="custom-control-label" for="customRadio">{{ __('backoffice.addon') }}</label>
                        </div>
                        <div class="custom-control custom-radio mt-3">
                            <input type="radio" class="custom-control-input addon-type" form="addon_form" id="customRadio1" name="type" value="option" {{ $type ==  "option" ? "checked" : '' }}>
                            <label class="custom-control-label" for="customRadio1">{{ __('backoffice.option') }}</label>
                        </div>
                    </div>
                </div>

                <div class="card p-3 rounded mt-4" id="addon_options_section">
                    <div class="card p-3 rounded mt-2">
                        <div>
                            <label>@lang('backoffice.min')</label>
                            <input name="min" form="addon_form" id="addon-min" class="form-control" type="number" min="0" value="{{ old('min',$add_on_min) }}">
                        </div>
                        <div class="mt-3">
                            <label>@lang('backoffice.max')</label>
                            <input name="max" form="addon_form" id="addon-max" class="form-control" type="number" min="0" value="{{ old('max',$add_on_max) }}">
                        </div>
                    </div>
                </div>

            </div>
        </div>

        <div class="col-md-12 pb-5" style="padding-left: 36px;">
            <button type="submit" form="addon_form" class="btn btn-primary">{{ $model->button_title }}</button>
            <a href="{{ route('addon.index')}}" class="btn btn-light">{{ __('backoffice.cancel') }}</a>
        </div>


    </div>
</div>

@php
    $step = str_pad('',\Auth::user()->store->round_off -1,'0');
    $step = $step .'1';
@endphp

@endsection

@section('scripts')
<script type="text/javascript">

const arrayNonUniq = array => {
    if (!Array.isArray(array)) {
        throw new TypeError("An array must be provided!")
    }

    return array.filter((value, index) => array.indexOf(value) === index && array.lastIndexOf(value) !== index)
}

$(function() {
    $('#addon_form').submit(function() {
        if ($('#addon_form')[0].checkValidity()) {

            let errors = [];
            $('.select2').each(function(){
                errors.push($(this).val().trim());
            });

            const result = errors.filter(word => word.trim().length > 0);

            let last = arrayNonUniq(result);

            if(last.length > 0){
                toastr.error(`{{ __('addon.repeated_value',['item' => '${last[0]}']) }}`,'Error');
                return false;
            }

            if($('#addon-min').val() > $('#addon-max').val()){
                toastr.error("{{ __('addon.invalid_max_value') }}",'Error');
                return false;
            }

            if(!$('#addon_options_section').hasClass('d-none') && $('#addon-max').val() > $('.section-en .row').length){

                toastr.error("{{ __('addon.max_value_items') }}",'Error');
                return false;
            }

            $.ajax({
                url: $(this).attr('action'),
                type: $(this).attr('method'),
                data: $(this).serialize(),
                success: function(response) {
                    // console.log(response);
                    if (response.IsValid) {
                        toastr.success(response.Message, 'Success');
                        setTimeout(() => {
                            window.location.href = site_url('catalogue/addon');
                        }, 100);
                    } else {
                        if (response.Errors.lenght > 0) {
                            response.Errors.map((error) => {
                                toastr.error(error, 'Error');
                            });
                        } else {
                            toastr.error(response.Errors[0], 'Error')
                        }
                    }
                }
            })
        }
        return false;
    })
})
var add_on_items = JSON.parse('{!! json_encode($items) !!}');

let items = JSON.parse('{!! json_encode($items) !!}');

const step = '{{ $step }}';
var items_collections = [];
var languages = JSON.parse('{!! json_encode($model->languages) !!}');

if (add_on_items != null && add_on_items.length > 0) {
    add_on_items.map(item => {
        let ite = {
            id: item.id,
            name: item.name_en,
            price: item.price,
        }
        items_collections.push(ite);
    })
}

$(document).ready(function() {
    $('#add_on_items').click(function() {
        renderAddOnItems();
    });
});

function renderAddOnItems() {

    languages.map((lang) => {

        let section = `.section-${lang.short_name}`;
        let required = lang.short_name == 'en' ? '<span style="color: red">*</span>' : '';
        let index = $(section).find('.row').length;

        let row = `<div class="row delete-${index}">`;
        let input = '';
        let selectApply = false;

        if(lang.short_name == 'en'){
            row += `<div class="col-md-5">
                <div class="form-group">
                    <label>{{ __('addon.item_name') }}</label>

                    <select class="select2 form-control" onchange="fillOption(this)" required form="addon_form" name="${lang.short_name}.items[${ index }][name]" style="width:100%" id="${lang.short_name}-${index}-name">
                        <option value="">Select or add item</option>
                        ${
                            items.map(i => {
                                let key = i[`${lang.short_name}-name`];
                                let info = JSON.stringify(i);
                                return `<option value="${key}" data-info='${info}'>${key}</option>`
                            })
                        }
                    </select>

                </div>
            </div>`;
        } else {
            row += `
                    <div class="col-md-5">
                        <div class="form-group">
                        <label>{{ __('addon.item_name') }}</label>
                        <input type="text" name="${lang.short_name}.items[${index}][name]" id="${lang.short_name}-${index}-name" class="form-control" placeholder="Item Name" style="font-style:italic" ${index == 0 ? 'required' : ''} form="addon_form" value="">
                        </div>
                    </div>
                `;
        }

        if (lang.short_name == 'en') {
            selectApply = true;

            row += `<div class="col-md-5">
                        <div class="form-group">
                            <label>{{ __('addon.price') }}</label>
                            <input name="${lang.short_name}.items[${index}][price]" id="${lang.short_name}-${index}-price" class="form-control" style="font-style:italic" type="number" form="addon_form" value="">
                        </div>
                    </div>
                    <div class="col-md-2">
                        <div class="form-group">
                            <i class="fa fa-trash " style="margin-top: 40%;" onclick="$('.delete-${index}').remove()"></i>
                        </div>
                    </div>
                    `;
        }

        row += `</div>`;

        $(section).append(row);
    });
}

function fillOption(element){
    let data= $(element).find(':selected').data('info');
    let id = $(element).prop('id');
    let parts = id.split('-');
    let priceElement = `${parts[0]}-${parts[1]}-price`;
    console.log(id);
    if(data != undefined){
        $(`#${priceElement}`).val(data.price);

        for (const property in data) {
            if(property != 'price'){
                let lparts = property.split('-');
                if(lparts[0] != 'en'){
                    let ele = `${lparts[0]}-${parts[1]}-name`;
                    $(`#${ele}`).val(data[property]);
                }
            }
        }
    }
}

$(document).on('change', '.addon-type', function(){
    if($(this).val() == 'option'){
        $('#addon_options_section').addClass('d-none');
    } else {
        $('#addon_options_section').removeClass('d-none');
    }
});
</script>
@endsection
