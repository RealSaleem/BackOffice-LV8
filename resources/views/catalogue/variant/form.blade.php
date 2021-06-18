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
                                {{ $model->title }}
                                <a href="{{route('product.index')}}" class="m-b-xs w-auto btn-primary btn-sm pull-right">
                                    {{ __('variant.back') }}
                                </a>
                            </h1>
                        </div>
                    </div>
                </div>
            </div>
        </div>
        <div class="col-sm-12 pl-0 pr-0">
            <div class="card bg-light mt-3  rounded  border-0">
                <div class="card bg-light mt-3  rounded  border-0 add_variant">
                    <div class="card-body">
                        <form>
                            <div class="row">
                                <div class="col-sm-3">
                                    <label>{{ __('variant.back') }} <span class="text-warning">{{ __('variant.back') }}</span></label>
                                    <input type="text" class="form-control" placeholder="{{ __('variant.back') }}">
                                </div>
                                <div class="col-sm-7">
                                    <label>{{ __('variant.back') }} <span class="text-warning">{{ __('variant.back') }}</span></label>
                                    <input type="text" class="form-control" placeholder="{{ __('variant.back') }}">
                                </div>
                                <div class="col-sm-2">
                                    <label></label>
                                    <button type="button" class="btn btn-primary btn-block">{{ __('variant.back') }}</button>
                                </div>
                            </div>
                            <div class="row mt-3">
                                <div class="col-sm-3">
                                    <input type="text" class="form-control" placeholder="{{ __('variant.back') }}">
                                </div>
                                <div class="col-sm-7">
                                    <input type="text" class="form-control" placeholder="{{ __('variant.back') }}">
                                </div>
                                <div class="col-sm-2">
                                    <button type="button" class="btn btn-primary btn-block">{{ __('variant.back') }}</button>
                                </div>
                            </div>

                            <div class="row mt-3">
                                <div class="col-sm-3">
                                    <input type="text" class="form-control" placeholder="{{ __('variant.back') }}">
                                </div>
                                <div class="col-sm-7">
                                    <input type="text" class="form-control" placeholder="{{ __('variant.back') }}">
                                </div>
                                <div class="col-sm-2">
                                    <button type="button" class="btn btn-primary btn-block">{{ __('variant.back') }}</button>
                                </div>
                            </div>
                        </form>
                    </div>

                </div>
                <div class="card bg-light mt-3  rounded  border-0">
                    <div class="card-body">
                        <form>
                            <div class="form-row">
                                <div class="col-sm-6">
                                    <label> Stock Keeping Unit (SKU) <i class="icon-info pr-1" tabindex="0" data-toggle="popover" data-trigger="focus" title="" data-content="And here's some amazing content. It's very engaging. Right?" data-original-title="Dismissible popover"></i></label>
                                    <input type="email" class="form-control" disabled="" placeholder="Ex:1000 or FGK229911">
                                </div>
                            </div>
                        </form>
                        <div class="row">
                            <div class="col-sm-12">
                                <div class="table-responsive">
                                    <table class="table table-borderless  mt-3">
                                        <thead>
                                            <tr>
                                                <th></th>
                                                <th>Variant Name </th>
                                                <th>SKU</th>
                                                <th>Retail Price</th>
                                                <th>Before Discount Price</th>
                                                <td></td>
                                                <td></td>
                                            </tr>
                                        </thead>
                                        <tbody>
                                            <tr>
                                                <td><i class="fa fa-chevron-right fa-1x text-warning" id="show_table"></i></td>
                                                <td><a href="#">Blue</a></td>
                                                <td><input type="text" class="form-control" placeholder=""></td>
                                                <td><input type="text" class="form-control" placeholder=""></td>
                                                <td><input type="text" class="form-control" placeholder=""></td>
                                                <td class="toggle_on_off align-middle">
                                                    <div class="custom-control custom-switch">
                                                        <input type="checkbox" class="custom-control-input" id="customSwitch1">
                                                        <label class="custom-control-label" for="customSwitch1"></label>
                                                    </div>
                                                </td>
                                                <td class="align-middle"><i class="fa fa-trash-o fa-2x text-warning"></i></td>
                                            </tr>
                                            <tr id="table_products" style="display: none;">
                                                <td colspan="7">
                                                    <div class="card greybg1 offset-2  rounded  border-0">
                                                        <div class="card-body">
                                                            <div class="form-row ">
                                                                <div class="col-sm-12">
                                                                    <label>Branches <i class="icon-info pr-1" tabindex="0" data-toggle="popover" data-trigger="focus" title="" data-content="And here's some amazing content. It's very engaging. Right?" data-original-title="Dismissible popover"></i> </label>
                                                                    <span class="btn-block mb-2"><strong class="strong">Gulshan</strong></span>
                                                                </div>
                                                                <div class="col-sm-2">
                                                                    <input type="email" class="form-control mt-3  mt-md-0" placeholder="Quantity">
                                                                </div>
                                                                <div class="col-sm-2">
                                                                    <input type="email" class="form-control mt-3  mt-md-0" placeholder="Re-Order Pt">
                                                                </div>
                                                                <div class="col-sm-3">
                                                                    <input type="email" class="form-control mt-3  mt-md-0" placeholder="Qty Re-Order">
                                                                </div>
                                                                <div class="col-sm-3">
                                                                    <input type="email" class="form-control mt-3  mt-md-0" placeholder="Supply Price">
                                                                </div>
                                                                <div class="col-sm-2">
                                                                    <div class="input-group">
                                                                        <input type="email" class="form-control cursor" disabled="" value="KD0">
                                                                        <input type="email" class="form-control cursor" disabled="" value="%">
                                                                    </div>
                                                                </div>
                                                            </div>
                                                        </div>
                                                    </div>
                                                </td>
                                            </tr>
                                            <tr>
                                                <td><i class="fa fa-chevron-right fa-1x text-warning" id="show_table"></i></td>
                                                <td><a href="#">Green</a></td>
                                                <td><input type="text" class="form-control" placeholder=""></td>
                                                <td><input type="text" class="form-control" placeholder=""></td>
                                                <td><input type="text" class="form-control" placeholder=""></td>
                                                <td class="toggle_on_off align-middle">
                                                    <div class="custom-control custom-switch">
                                                        <input type="checkbox" class="custom-control-input" id="customSwitch1">
                                                        <label class="custom-control-label" for="customSwitch1"></label>
                                                    </div>
                                                </td>
                                                <td class="align-middle"><i class="fa fa-trash-o fa-2x text-warning"></i></td>
                                            </tr>
                                        </tbody>
                                    </table>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>

    </div>
</div>
<div class="col-md-12 pb-5 mt-3 ml-2">
    <button type="submit" form="product-form" class="btn btn-primary">{{ __('variant.save') }}</button>
    <a href="{{ route('product.index')}}" class="btn btn-light">{{ __('variant.cancel') }}</a>
</div>


@endsection
@section('scripts')


@endsection
