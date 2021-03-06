@extends('layouts.backoffice')
@section('content')
    <!-- content -->


    <?php
    $name = array();

    ?>

    @foreach($data['model']->role['permissions'] as $row)
        <?php
        $name[] = $row->name;
        ?>
    @endforeach


    <div class="row">
        <form method="POST" action="{{$data['model']->route}}" id="role-form">
            {{csrf_field()}}
            {{--            @if($model->edit_mode)--}}
            {{--                <input id="id" type="hidden" form="role-form" name="id" value="{{$model->role['id']}}">--}}
            {{--            @endif--}}

            <div class="col-md-12">
                <div class="row">
                    @if ($errors->any())
                        <div class="alert alert-danger">
                            <ul>
                                @foreach ($errors->all() as $error)
                                    <li>{{ $error }}</li>
                                @endforeach
                            </ul>
                        </div>
                    @endif
                </div>
            </div>
            <div class="container-fluid">
                <div class="row">
                    <div class="col-sm-12 pl-0 pr-0">
                        <div class="greybg1 rounded p-4 mb-3">
                            <div class="row">
                                <div class="col-sm-12">
                                    <div class="common_title">
                                        <h1 style="margin-left: 20px;">
                                            {{$data['model']->title}}
                                            <a href="{{route('roles.index')}}"
                                               class="m-b-xs w-auto btn-primary btn-sm pull-right">
                                                {{ __('backoffice.back') }}
                                            </a>
                                        </h1>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>

            <div id="content" class="app-content" role="main">
                <div class="app-content-body ">

                    <div class="hbox hbox-auto-xs hbox-auto-sm">
                        <div class="col">
                            <div class="wrapper-md pl-4 pt-4">
                                <div class="col-sm-12">
                                    <div class="panel panel-default">


                                        <div class="padding-panel">
                                            <div class="row">
                                                <div class="col-sm-4">
                                                    <label class="rolelable">@lang('backoffice.RoleTitle')</label>
                                                </div>
                                                <div class="col-sm-4">
                                                    <input type="text" name="display_name"
                                                           value="{{old('display_name',$data['model']->role['role']->display_name)}}"
                                                           class="roletitle form-control "
                                                           placeholder="Add Role" required>
                                                    <input type="hidden" name="id"
                                                           value="{{$data['model']->role['role']->id }}">
                                                </div>
                                                <div class="col-sm-4 pr-5 pt-2">
                                                    <label class="i-checks pull-right">
                                                        <input type="checkbox" class="pull-right" id="check_all"
                                                               style="margin-left: 10px;">
                                                        <i></i>@lang('backoffice.select_all')
                                                    </label>

                                                </div>
                                                <div class="clearfix"></div>
                                            </div>
                                        </div>
                                        <hr>
                                        <div class="padding-panel">
                                            <table class="table table-hover table-boarderd">
                                                <thead>
                                                <td class="col-md-3"><b>Module Name</b></td>
                                                <td class="col-md-2"><b></b></td>
                                                <td class="col-md-2"><b></b></td>
                                                <td class="col-md-2"><b></b></td>
                                                <td class="col-md-2"><b></b></td>
                                                </thead>
                                                <tbody>
                                                <tr>
                                                    <td> @lang('backoffice.dashboard') </td>
                                                    <td>
                                                        <div class="custom-control custom-checkbox ">
                                                            <input type="checkbox" class="custom-control-input"
                                                                   id="customCheck1" form="role-form"
                                                                   name="permissions[]"
                                                                   value="dashboard_dashboard" <?php if (in_array("dashboard_dashboard", $name)) {
                                                                echo "checked";
                                                            } ?>>
                                                            <label class="custom-control-label text-secondary"
                                                                   for="customCheck1">@lang('backoffice.list')</label>
                                                        </div>
                                                    </td>
                                                    <td></td>
                                                    <td></td>
                                                    <td></td>
                                                </tr>
                                                <tr>
                                                    <td> @lang('backoffice.products') </td>
                                                    <td>
                                                        <div class="custom-control custom-checkbox  ">
                                                            <input type="checkbox" class="custom-control-input"
                                                                   id="customCheck2" form="role-form"
                                                                   name="permissions[]"
                                                                   value="product_list" <?php if (in_array("product_list", $name)) {
                                                                echo "checked";
                                                            } ?>>
                                                            <label class="custom-control-label text-secondary"
                                                                   for="customCheck2">@lang('backoffice.list')</label>
                                                        </div>
                                                    </td>
                                                    <td>
                                                        <div class="custom-control custom-checkbox  ">
                                                            <input type="checkbox" class="custom-control-input"
                                                                   id="customCheck3" form="role-form"
                                                                   name="permissions[]"
                                                                   value="product_add" <?php if (in_array("product_add", $name)) {
                                                                echo "checked";
                                                            } ?>>
                                                            <label class="custom-control-label text-secondary"
                                                                   for="customCheck3">@lang('backoffice.add')</label>
                                                        </div>

                                                    </td>
                                                    <td>
                                                        <div class="custom-control custom-checkbox  ">
                                                            <input type="checkbox" class="custom-control-input"
                                                                   id="customCheck4" form="role-form"
                                                                   name="permissions[]"
                                                                   value="product_edit" <?php if (in_array("product_edit", $name)) {
                                                                echo "checked";
                                                            } ?>>
                                                            <label class="custom-control-label text-secondary"
                                                                   for="customCheck4">@lang('backoffice.edit')</label>
                                                        </div>
                                                    </td>
                                                    <td>
                                                        <div class="custom-control custom-checkbox  ">
                                                            <input type="checkbox" class="custom-control-input"
                                                                   id="customCheck5" form="role-form"
                                                                   name="permissions[]"
                                                                   value="product_delete" <?php if (in_array("product_delete", $name)) {
                                                                echo "checked";
                                                            } ?>>
                                                            <label class="custom-control-label text-secondary"
                                                                   for="customCheck5">@lang('backoffice.delete')</label>
                                                        </div>

                                                    </td>
                                                </tr>
                                                <tr>
                                                    <td> @lang('backoffice.import_catalogue') </td>
                                                    <td>
                                                        <div class="custom-control custom-checkbox col-sm-2 ">
                                                            <input type="checkbox" class="custom-control-input"
                                                                   id="customCheck6" form="role-form"
                                                                   name="permissions[]"
                                                                   value="import_catalogue" <?php if (in_array("import_catalogue", $name)) {
                                                                echo "checked";
                                                            } ?>>
                                                            <label class="custom-control-label text-secondary"
                                                                   for="customCheck6">@lang('backoffice.list')</label>
                                                        </div>
                                                    </td>
                                                    <td>


                                                    </td>
                                                    <td>

                                                    </td>
                                                    <td>


                                                    </td>
                                                </tr>
                                                <tr>
                                                    <td> @lang('backoffice.category')  </td>
                                                    <td>
                                                        <div class="custom-control custom-checkbox col-sm-2 ">
                                                            <input type="checkbox" class="custom-control-input"
                                                                   id="customCheck7" form="role-form"
                                                                   name="permissions[]"
                                                                   value="product_type_list" <?php if (in_array("product_type_list", $name)) {
                                                                echo "checked";
                                                            } ?>>
                                                            <label class="custom-control-label text-secondary"
                                                                   for="customCheck7">@lang('backoffice.list')</label>
                                                        </div>
                                                    </td>
                                                    <td>
                                                        <div class="custom-control custom-checkbox col-sm-2 ">
                                                            <input type="checkbox" class="custom-control-input"
                                                                   id="customCheck8" form="role-form"
                                                                   name="permissions[]"
                                                                   value="product_type_add" <?php if (in_array("product_type_add", $name)) {
                                                                echo "checked";
                                                            } ?>>
                                                            <label class="custom-control-label text-secondary"
                                                                   for="customCheck8">@lang('backoffice.add')</label>
                                                        </div>

                                                    </td>
                                                    <td>
                                                        <div class="custom-control custom-checkbox col-sm-2 ">
                                                            <input type="checkbox" class="custom-control-input"
                                                                   id="customCheck9" form="role-form"
                                                                   name="permissions[]"
                                                                   value="product_type_edit" <?php if (in_array("product_type_edit", $name)) {
                                                                echo "checked";
                                                            } ?>>
                                                            <label class="custom-control-label text-secondary"
                                                                   for="customCheck9">@lang('backoffice.edit')</label>
                                                        </div>
                                                    </td>
                                                    <td>
                                                        <div class="custom-control custom-checkbox col-sm-2 ">
                                                            <input type="checkbox" class="custom-control-input"
                                                                   id="customCheck10" form="role-form"
                                                                   name="permissions[]"
                                                                   value="product_type_delete" <?php if (in_array("product_type_delete", $name)) {
                                                                echo "checked";
                                                            } ?>>
                                                            <label class="custom-control-label text-secondary"
                                                                   for="customCheck10">@lang('backoffice.delete')</label>
                                                        </div>

                                                    </td>
                                                </tr>
                                                <tr>
                                                    <td> @lang('backoffice.supplier')  </td>
                                                    <td>
                                                        <div class="custom-control custom-checkbox col-sm-2 ">
                                                            <input type="checkbox" class="custom-control-input"
                                                                   id="customCheck11" form="role-form"
                                                                   name="permissions[]"
                                                                   value="supplier_list" <?php if (in_array("supplier_list", $name)) {
                                                                echo "checked";
                                                            } ?>>
                                                            <label class="custom-control-label text-secondary"
                                                                   for="customCheck11">@lang('backoffice.list')</label>
                                                        </div>
                                                    </td>
                                                    <td>
                                                        <div class="custom-control custom-checkbox col-sm-2 ">
                                                            <input type="checkbox" class="custom-control-input"
                                                                   id="customCheck12" form="role-form"
                                                                   name="permissions[]"
                                                                   value="supplier_add" <?php if (in_array("supplier_add", $name)) {
                                                                echo "checked";
                                                            } ?>>
                                                            <label class="custom-control-label text-secondary"
                                                                   for="customCheck12">@lang('backoffice.add')</label>
                                                        </div>

                                                    </td>
                                                    <td>
                                                        <div class="custom-control custom-checkbox col-sm-2 ">
                                                            <input type="checkbox" class="custom-control-input"
                                                                   id="customCheck13" form="role-form"
                                                                   name="permissions[]"
                                                                   value="supplier_edit" <?php if (in_array("supplier_edit", $name)) {
                                                                echo "checked";
                                                            } ?>>
                                                            <label class="custom-control-label text-secondary"
                                                                   for="customCheck13">@lang('backoffice.edit')</label>
                                                        </div>
                                                    </td>
                                                    <td>
                                                        <div class="custom-control custom-checkbox col-sm-2 ">
                                                            <input type="checkbox" class="custom-control-input"
                                                                   id="customCheck14" form="role-form"
                                                                   name="permissions[]"
                                                                   value="supplier_delete" <?php if (in_array("supplier_delete", $name)) {
                                                                echo "checked";
                                                            } ?>>
                                                            <label class="custom-control-label text-secondary"
                                                                   for="customCheck14">@lang('backoffice.delete')</label>
                                                        </div>

                                                    </td>
                                                </tr>
                                                <tr>
                                                    <td> @lang('backoffice.addons')  </td>
                                                    <td>
                                                        <div class="custom-control custom-checkbox col-sm-2 ">
                                                            <input type="checkbox" class="custom-control-input"
                                                                   id="customCheck15" form="role-form"
                                                                   name="permissions[]"
                                                                   value="addons_list" <?php if (in_array("addons_list", $name)) {
                                                                echo "checked";
                                                            } ?> >
                                                            <label class="custom-control-label text-secondary"
                                                                   for="customCheck15">@lang('backoffice.list')</label>
                                                        </div>
                                                    </td>
                                                    <td>
                                                        <div class="custom-control custom-checkbox col-sm-2 ">
                                                            <input type="checkbox" class="custom-control-input"
                                                                   id="customCheck16" form="role-form"
                                                                   name="permissions[]"
                                                                   value="addons_add" <?php if (in_array("addons_add", $name)) {
                                                                echo "checked";
                                                            } ?>>
                                                            <label class="custom-control-label text-secondary"
                                                                   for="customCheck16">@lang('backoffice.add')</label>
                                                        </div>

                                                    </td>
                                                    <td>
                                                        <div class="custom-control custom-checkbox col-sm-2 ">
                                                            <input type="checkbox" class="custom-control-input"
                                                                   id="customCheck17" form="role-form"
                                                                   name="permissions[]"
                                                                   value="addons_edit" <?php if (in_array("addons_edit", $name)) {
                                                                echo "checked";
                                                            } ?>>
                                                            <label class="custom-control-label text-secondary"
                                                                   for="customCheck17">@lang('backoffice.edit')</label>
                                                        </div>
                                                    </td>
                                                    <td>
                                                        <div class="custom-control custom-checkbox col-sm-2 ">
                                                            <input type="checkbox" class="custom-control-input"
                                                                   id="customCheck18" form="role-form"
                                                                   name="permissions[]"
                                                                   value="addons_delete" <?php if (in_array("addons_delete", $name)) {
                                                                echo "checked";
                                                            } ?>>
                                                            <label class="custom-control-label text-secondary"
                                                                   for="customCheck18">@lang('backoffice.delete')</label>
                                                        </div>

                                                    </td>
                                                </tr>
                                                <tr>
                                                    <td> @lang('backoffice.addonsitem')  </td>
                                                    <td>
                                                        <div class="custom-control custom-checkbox col-sm-2 ">
                                                            <input type="checkbox" class="custom-control-input"
                                                                   id="customCheck19" form="role-form"
                                                                   name="permissions[]"
                                                                   value="addonsitem_list" <?php if (in_array("addonsitem_list", $name)) {
                                                                echo "checked";
                                                            } ?>>
                                                            <label class="custom-control-label text-secondary"
                                                                   for="customCheck19">@lang('backoffice.list')</label>
                                                        </div>
                                                    </td>
                                                    <td>
                                                        <div class="custom-control custom-checkbox col-sm-2 ">
                                                            <input type="checkbox" class="custom-control-input"
                                                                   id="customCheck20" form="role-form"
                                                                   name="permissions[]"
                                                                   value="addonsitem_add" <?php if (in_array("addonsitem_add", $name)) {
                                                                echo "checked";
                                                            } ?>>
                                                            <label class="custom-control-label text-secondary"
                                                                   for="customCheck20">@lang('backoffice.add')</label>
                                                        </div>

                                                    </td>
                                                    <td>
                                                        <div class="custom-control custom-checkbox col-sm-2 ">
                                                            <input type="checkbox" class="custom-control-input"
                                                                   id="customCheck21" form="role-form"
                                                                   name="permissions[]"
                                                                   value="addonsitem_edit" <?php if (in_array("addonsitem_edit", $name)) {
                                                                echo "checked";
                                                            } ?>>
                                                            <label class="custom-control-label text-secondary"
                                                                   for="customCheck21">@lang('backoffice.edit')</label>
                                                        </div>
                                                    </td>
                                                    <td>
                                                        <div class="custom-control custom-checkbox col-sm-2 ">
                                                            <input type="checkbox" class="custom-control-input"
                                                                   id="customCheck22" form="role-form"
                                                                   name="permissions[]"
                                                                   value="addonsitem_delete" <?php if (in_array("addonsitem_delete", $name)) {
                                                                echo "checked";
                                                            } ?>>
                                                            <label class="custom-control-label text-secondary"
                                                                   for="customCheck22">@lang('backoffice.delete')</label>
                                                        </div>

                                                    </td>
                                                </tr>
                                                <tr>
                                                    <td> @lang('backoffice.brand')  </td>
                                                    <td>
                                                        <div class="custom-control custom-checkbox col-sm-2 ">
                                                            <input type="checkbox" class="custom-control-input"
                                                                   id="customCheck23" form="role-form"
                                                                   name="permissions[]"
                                                                   value="brand_list" <?php if (in_array("brand_list", $name)) {
                                                                echo "checked";
                                                            } ?>>
                                                            <label class="custom-control-label text-secondary"
                                                                   for="customCheck23">@lang('backoffice.list')</label>
                                                        </div>
                                                    </td>
                                                    <td>
                                                        <div class="custom-control custom-checkbox col-sm-2 ">
                                                            <input type="checkbox" class="custom-control-input"
                                                                   id="customCheck24" form="role-form"
                                                                   name="permissions[]"
                                                                   value="brand_add" <?php if (in_array("brand_add", $name)) {
                                                                echo "checked";
                                                            } ?>>
                                                            <label class="custom-control-label text-secondary"
                                                                   for="customCheck24">@lang('backoffice.add')</label>
                                                        </div>

                                                    </td>
                                                    <td>
                                                        <div class="custom-control custom-checkbox col-sm-2 ">
                                                            <input type="checkbox" class="custom-control-input"
                                                                   id="customCheck25" form="role-form"
                                                                   name="permissions[]"
                                                                   value="brand_edit" <?php if (in_array("brand_edit", $name)) {
                                                                echo "checked";
                                                            } ?>>
                                                            <label class="custom-control-label text-secondary"
                                                                   for="customCheck25">@lang('backoffice.edit')</label>
                                                        </div>
                                                    </td>
                                                    <td>
                                                        <div class="custom-control custom-checkbox col-sm-2 ">
                                                            <input type="checkbox" class="custom-control-input"
                                                                   id="customCheck26" form="role-form"
                                                                   name="permissions[]"
                                                                   value="brand_delete" <?php if (in_array("brand_delete", $name)) {
                                                                echo "checked";
                                                            } ?>>
                                                            <label class="custom-control-label text-secondary"
                                                                   for="customCheck26">@lang('backoffice.delete')</label>
                                                        </div>

                                                    </td>
                                                </tr>
                                                <tr>
                                                    <td> @lang('backoffice.customer')  </td>
                                                    <td>
                                                        <div class="custom-control custom-checkbox col-sm-2 ">
                                                            <input type="checkbox" class="custom-control-input"
                                                                   id="customCheck27" form="role-form"
                                                                   name="permissions[]"
                                                                   value="customer_list" <?php if (in_array("customer_list", $name)) {
                                                                echo "checked";
                                                            } ?>>
                                                            <label class="custom-control-label text-secondary"
                                                                   for="customCheck27">@lang('backoffice.list')</label>
                                                        </div>
                                                    </td>
                                                    <td>
                                                        <div class="custom-control custom-checkbox col-sm-2 ">
                                                            <input type="checkbox" class="custom-control-input"
                                                                   id="customCheck28" form="role-form"
                                                                   name="permissions[]"
                                                                   value="customer_add" <?php if (in_array("customer_add", $name)) {
                                                                echo "checked";
                                                            } ?>>
                                                            <label class="custom-control-label text-secondary"
                                                                   for="customCheck28">@lang('backoffice.add')</label>
                                                        </div>

                                                    </td>
                                                    <td>
                                                        <div class="custom-control custom-checkbox col-sm-2 ">
                                                            <input type="checkbox" class="custom-control-input"
                                                                   id="customCheck29" form="role-form"
                                                                   name="permissions[]"
                                                                   value="customer_edit" <?php if (in_array("customer_edit", $name)) {
                                                                echo "checked";
                                                            } ?>>
                                                            <label class="custom-control-label text-secondary"
                                                                   for="customCheck29">@lang('backoffice.edit')</label>
                                                        </div>
                                                    </td>
                                                    <td>
                                                    </td>
                                                </tr>

                                                <tr>
                                                    <td> @lang('backoffice.customer_group')  </td>
                                                    <td>
                                                        <div class="custom-control custom-checkbox col-sm-2 ">
                                                            <input type="checkbox" class="custom-control-input"
                                                                   id="customCheck30" form="role-form"
                                                                   name="permissions[]"
                                                                   value="customer_group_list">
                                                            <label class="custom-control-label text-secondary"
                                                                   for="customCheck30">@lang('backoffice.list')</label>
                                                        </div>
                                                    </td>
                                                    <td>
                                                        <div class="custom-control custom-checkbox col-sm-2 ">
                                                            <input type="checkbox" class="custom-control-input"
                                                                   id="customCheck31" form="role-form"
                                                                   name="permissions[]"
                                                                   value="customer_group_add">
                                                            <label class="custom-control-label text-secondary"
                                                                   for="customCheck31">@lang('backoffice.add')</label>
                                                        </div>

                                                    </td>
                                                    <td>
                                                        <div class="custom-control custom-checkbox col-sm-2 ">
                                                            <input type="checkbox" class="custom-control-input"
                                                                   id="customCheck32" form="role-form"
                                                                   name="permissions[]"
                                                                   value="customer_group_edit">
                                                            <label class="custom-control-label text-secondary"
                                                                   for="customCheck32">@lang('backoffice.edit')</label>
                                                        </div>
                                                    </td>
                                                    <td>
                                                        <div class="custom-control custom-checkbox col-sm-2 ">
                                                            <input type="checkbox" class="custom-control-input"
                                                                   id="customCheck33" form="role-form"
                                                                   name="permissions[]"
                                                                   value="customer_group_delete">
                                                            <label class="custom-control-label text-secondary"
                                                                   for="customCheck33">@lang('backoffice.delete')</label>
                                                        </div>
                                                    </td>
                                                </tr>

                                                <tr>
                                                    <td> @lang('backoffice.general_setup') </td>
                                                    <td></td>
                                                    <td></td>
                                                    <td>
                                                        <div class="custom-control custom-checkbox ">
                                                            <input type="checkbox" class="custom-control-input"
                                                                   id="customCheck34" form="role-form"
                                                                   name="permissions[]"
                                                                   value="general_setup_edit">
                                                            <label class="custom-control-label text-secondary"
                                                                   for="customCheck34">@lang('backoffice.edit')</label>
                                                        </div>
                                                    </td>
                                                    <td></td>
                                                </tr>
                                                <tr>
                                                    <td> @lang('backoffice.outlets_and_registers')  </td>
                                                    <td>
                                                        <div class="custom-control custom-checkbox col-sm-2 ">
                                                            <input type="checkbox" class="custom-control-input"
                                                                   id="customCheck35" form="role-form"
                                                                   name="permissions[]"
                                                                   value="outlet_list">
                                                            <label class="custom-control-label text-secondary"
                                                                   for="customCheck35">@lang('backoffice.list')</label>
                                                        </div>
                                                    </td>
                                                    <td>
                                                        <div class="custom-control custom-checkbox col-sm-2 ">
                                                            <input type="checkbox" class="custom-control-input"
                                                                   id="customCheck36" form="role-form"
                                                                   name="permissions[]"
                                                                   value="outlet_add">
                                                            <label class="custom-control-label text-secondary"
                                                                   for="customCheck36">@lang('backoffice.add')</label>
                                                        </div>

                                                    </td>
                                                    <td>
                                                        <div class="custom-control custom-checkbox col-sm-2 ">
                                                            <input type="checkbox" class="custom-control-input"
                                                                   id="customCheck37" form="role-form"
                                                                   name="permissions[]"
                                                                   value="outlet_edit">
                                                            <label class="custom-control-label text-secondary"
                                                                   for="customCheck37">@lang('backoffice.edit')</label>
                                                        </div>
                                                    </td>
                                                    <td>
                                                        <div class="custom-control custom-checkbox col-sm-2 ">
                                                            <input type="checkbox" class="custom-control-input"
                                                                   id="customCheck38" form="role-form"
                                                                   name="permissions[]"
                                                                   value="outlet_delete">
                                                            <label class="custom-control-label text-secondary"
                                                                   for="customCheck38">@lang('backoffice.delete')</label>
                                                        </div>
                                                    </td>
                                                </tr>
                                                <tr>
                                                    <td> @lang('backoffice.register')  </td>
                                                    <td>
                                                    </td>
                                                    <td>
                                                        <div class="custom-control custom-checkbox col-sm-2 ">
                                                            <input type="checkbox" class="custom-control-input"
                                                                   id="customCheck39" form="role-form"
                                                                   name="permissions[]"
                                                                   value="register_add">
                                                            <label class="custom-control-label text-secondary"
                                                                   for="customCheck39">@lang('backoffice.add')</label>
                                                        </div>

                                                    </td>
                                                    <td>
                                                        <div class="custom-control custom-checkbox col-sm-2 ">
                                                            <input type="checkbox" class="custom-control-input"
                                                                   id="customCheck40" form="role-form"
                                                                   name="permissions[]"
                                                                   value="register_edit">
                                                            <label class="custom-control-label text-secondary"
                                                                   for="customCheck40">@lang('backoffice.edit')</label>
                                                        </div>
                                                    </td>
                                                    <td>
                                                        <div class="custom-control custom-checkbox col-sm-2 ">
                                                            <input type="checkbox" class="custom-control-input"
                                                                   id="customCheck41" form="role-form"
                                                                   name="permissions[]"
                                                                   value="register_delete">
                                                            <label class="custom-control-label text-secondary"
                                                                   for="customCheck41">@lang('backoffice.delete')</label>
                                                        </div>
                                                    </td>
                                                </tr>
                                                <tr>
                                                    <td> @lang('backoffice.user')  </td>
                                                    <td>
                                                        <div class="custom-control custom-checkbox col-sm-2 ">
                                                            <input type="checkbox" class="custom-control-input"
                                                                   id="customCheck42" form="role-form"
                                                                   name="permissions[]"
                                                                   value="user_list">
                                                            <label class="custom-control-label text-secondary"
                                                                   for="customCheck42">@lang('backoffice.list')</label>
                                                        </div>
                                                    </td>
                                                    <td>
                                                        <div class="custom-control custom-checkbox col-sm-2 ">
                                                            <input type="checkbox" class="custom-control-input"
                                                                   id="customCheck43" form="role-form"
                                                                   name="permissions[]"
                                                                   value="user_add">
                                                            <label class="custom-control-label text-secondary"
                                                                   for="customCheck43">@lang('backoffice.add')</label>
                                                        </div>

                                                    </td>
                                                    <td>
                                                        <div class="custom-control custom-checkbox col-sm-2 ">
                                                            <input type="checkbox" class="custom-control-input"
                                                                   id="customCheck44" form="role-form"
                                                                   name="permissions[]"
                                                                   value="user_edit">
                                                            <label class="custom-control-label text-secondary"
                                                                   for="customCheck44">@lang('backoffice.edit')</label>
                                                        </div>
                                                    </td>
                                                    <td>
                                                        <div class="custom-control custom-checkbox col-sm-2 ">
                                                            <input type="checkbox" class="custom-control-input"
                                                                   id="customCheck45" form="role-form"
                                                                   name="permissions[]"
                                                                   value="user_delete">
                                                            <label class="custom-control-label text-secondary"
                                                                   for="customCheck45">@lang('backoffice.delete')</label>
                                                        </div>
                                                    </td>
                                                </tr>
                                                <tr>
                                                    <td> @lang('backoffice.roles')  </td>
                                                    <td>
                                                        <div class="custom-control custom-checkbox col-sm-2 ">
                                                            <input type="checkbox" class="custom-control-input"
                                                                   id="customCheck46" form="role-form"
                                                                   name="permissions[]"
                                                                   value="roles_list">
                                                            <label class="custom-control-label text-secondary"
                                                                   for="customCheck46">@lang('backoffice.list')</label>
                                                        </div>
                                                    </td>
                                                    <td>
                                                        <div class="custom-control custom-checkbox col-sm-2 ">
                                                            <input type="checkbox" class="custom-control-input"
                                                                   id="customCheck47" form="role-form"
                                                                   name="permissions[]"
                                                                   value="roles_add">
                                                            <label class="custom-control-label text-secondary"
                                                                   for="customCheck47">@lang('backoffice.add')</label>
                                                        </div>

                                                    </td>
                                                    <td>
                                                        <div class="custom-control custom-checkbox col-sm-2 ">
                                                            <input type="checkbox" class="custom-control-input"
                                                                   id="customCheck48" form="role-form"
                                                                   name="permissions[]"
                                                                   value="roles_edit">
                                                            <label class="custom-control-label text-secondary"
                                                                   for="customCheck48">@lang('backoffice.edit')</label>
                                                        </div>
                                                    </td>
                                                    <td>
                                                        <div class="custom-control custom-checkbox col-sm-2 ">
                                                            <input type="checkbox" class="custom-control-input"
                                                                   id="customCheck49" form="role-form"
                                                                   name="permissions[]"
                                                                   value="roles_delete">
                                                            <label class="custom-control-label text-secondary"
                                                                   for="customCheck49">@lang('backoffice.delete')</label>
                                                        </div>
                                                    </td>
                                                </tr>
                                                <tr>
                                                    <td> @lang('backoffice.apps')  </td>
                                                    <td>
                                                        <div class="custom-control custom-checkbox col-sm-2 ">
                                                            <input type="checkbox" class="custom-control-input"
                                                                   id="customCheck50" form="role-form"
                                                                   name="permissions[]"
                                                                   value="apps_view">
                                                            <label class="custom-control-label text-secondary"
                                                                   for="customCheck50">@lang('backoffice.list')</label>
                                                        </div>
                                                    </td>
                                                    <td></td>
                                                    <td></td>
                                                    <td></td>
                                                </tr>
                                                <tr>
                                                    <td> @lang('backoffice.plugin')  </td>
                                                    <td>
                                                        <div class="custom-control custom-checkbox col-sm-2 ">
                                                            <input type="checkbox" class="custom-control-input"
                                                                   id="customCheck51" form="role-form"
                                                                   name="permissions[]"
                                                                   value="plugin_view">
                                                            <label class="custom-control-label text-secondary"
                                                                   for="customCheck51">@lang('backoffice.list')</label>
                                                        </div>
                                                    </td>
                                                    <td></td>
                                                    <td></td>
                                                    <td></td>
                                                </tr>
                                                <!-----------------------------------------------Website Permissions Start-->
                                                <tr>
                                                    <td> @lang('backoffice.Ecommerce_Setup') </td>
                                                    <td></td>
                                                    <td></td>
                                                    <td>
                                                        <div class="custom-control custom-checkbox ">
                                                            <input type="checkbox" class="custom-control-input"
                                                                   id="customCheck72" form="role-form"
                                                                   name="permissions[]"
                                                                   value="ecommerce_setup_edit">
                                                            <label class="custom-control-label text-secondary"
                                                                   for="customCheck72"> @lang('backoffice.edit')</label>
                                                        </div>
                                                    </td>
                                                    <td></td>
                                                </tr>
                                                <tr>
                                                    <td> @lang('backoffice.orders_management') </td>
                                                    <td> <div class="custom-control custom-checkbox ">
                                                            <input type="checkbox" class="custom-control-input"
                                                                   id="customCheck85" form="role-form"
                                                                   name="permissions[]"
                                                                   value="order_web_management_list">
                                                            <label class="custom-control-label text-secondary"
                                                                   for="customCheck85"> @lang('backoffice.list')</label>
                                                        </div></td>
                                                    <td></td>
                                                    <td>

                                                    </td>
                                                    <td></td>
                                                </tr>
                                                <tr>
                                                    <td> @lang('backoffice.WebSetting') </td>
                                                    <td> <div class="custom-control custom-checkbox ">
                                                            <input type="checkbox" class="custom-control-input"
                                                                   id="customCheck97" form="role-form"
                                                                   name="permissions[]"
                                                                   value="web_settings_list">
                                                            <label class="custom-control-label text-secondary"
                                                                   for="customCheck97"> @lang('backoffice.list')</label>
                                                        </div></td>
                                                    <td></td>
                                                    <td>

                                                    </td>
                                                    <td></td>
                                                </tr>
                                                <tr>
                                                    <td> @lang('backoffice.web_customer') </td>
                                                    <td>
                                                        <div class="custom-control custom-checkbox ">
                                                            <input type="checkbox" class="custom-control-input"
                                                                   id="customCheck86" form="role-form"
                                                                   name="permissions[]"
                                                                   value="web_customer_list">
                                                            <label class="custom-control-label text-secondary"
                                                                   for="customCheck86"> @lang('backoffice.list')</label>
                                                        </div>
                                                    </td>
                                                    <td></td>
                                                    <td>

                                                    </td>
                                                    <td></td>
                                                </tr>
                                                <tr>
                                                    <td> @lang('backoffice.web_subscribe_users') </td>
                                                    <td>
                                                        <div class="custom-control custom-checkbox ">
                                                            <input type="checkbox" class="custom-control-input"
                                                                   id="customCheck87" form="role-form"
                                                                   name="permissions[]"
                                                                   value="web_subscribe_user_list">
                                                            <label class="custom-control-label text-secondary"
                                                                   for="customCheck87"> @lang('backoffice.list')</label>
                                                        </div>
                                                    </td>
                                                    <td></td>
                                                    <td>

                                                    </td>
                                                    <td></td>
                                                </tr>
                                                <tr>
                                                    <td> @lang('backoffice.product_list')  </td>
                                                    <td>
                                                        <div class="custom-control custom-checkbox col-sm-2 ">
                                                            <input type="checkbox" class="custom-control-input"
                                                                   id="customCheck76" form="role-form"
                                                                   name="permissions[]"
                                                                   value="product_web_list">
                                                            <label class="custom-control-label text-secondary"
                                                                   for="customCheck76">@lang('backoffice.list')</label>
                                                        </div>
                                                    </td>
                                                    <td>
                                                        <div class="custom-control custom-checkbox col-sm-2 ">
                                                            <input type="checkbox" class="custom-control-input"
                                                                   id="customCheck73" form="role-form"
                                                                   name="permissions[]"
                                                                   value="product_web_add">
                                                            <label class="custom-control-label text-secondary"
                                                                   for="customCheck73">@lang('backoffice.add')</label>
                                                        </div>

                                                    </td>
                                                    <td>
                                                        <div class="custom-control custom-checkbox col-sm-2 ">
                                                            <input type="checkbox" class="custom-control-input"
                                                                   id="customCheck74" form="role-form"
                                                                   name="permissions[]"
                                                                   value="product_web_edit">
                                                            <label class="custom-control-label text-secondary"
                                                                   for="customCheck74">@lang('backoffice.edit')</label>
                                                        </div>
                                                    </td>
                                                    <td>
                                                        <div class="custom-control custom-checkbox col-sm-2 ">
                                                            <input type="checkbox" class="custom-control-input"
                                                                   id="customCheck75" form="role-form"
                                                                   name="permissions[]"
                                                                   value="product_web_delete">
                                                            <label class="custom-control-label text-secondary"
                                                                   for="customCheck75">@lang('backoffice.delete')</label>
                                                        </div>
                                                    </td>
                                                </tr>
                                                <tr>
                                                    <td> @lang('backoffice.shipping_method')  </td>
                                                    <td>
                                                        <div class="custom-control custom-checkbox col-sm-2 ">
                                                            <input type="checkbox" class="custom-control-input"
                                                                   id="customCheck77" form="role-form"
                                                                   name="permissions[]"
                                                                   value="shipping_web_list">
                                                            <label class="custom-control-label text-secondary"
                                                                   for="customCheck77">@lang('backoffice.list')</label>
                                                        </div>
                                                    </td>
                                                    <td>
                                                        <div class="custom-control custom-checkbox col-sm-2 ">
                                                            <input type="checkbox" class="custom-control-input"
                                                                   id="customCheck78" form="role-form"
                                                                   name="permissions[]"
                                                                   value="shipping_web_add">
                                                            <label class="custom-control-label text-secondary"
                                                                   for="customCheck78">@lang('backoffice.add')</label>
                                                        </div>

                                                    </td>
                                                    <td>
                                                        <div class="custom-control custom-checkbox col-sm-2 ">
                                                            <input type="checkbox" class="custom-control-input"
                                                                   id="customCheck79" form="role-form"
                                                                   name="permissions[]"
                                                                   value="shipping_web_edit">
                                                            <label class="custom-control-label text-secondary"
                                                                   for="customCheck79">@lang('backoffice.edit')</label>
                                                        </div>
                                                    </td>
                                                    <td>
                                                        <div class="custom-control custom-checkbox col-sm-2 ">
                                                            <input type="checkbox" class="custom-control-input"
                                                                   id="customCheck80" form="role-form"
                                                                   name="permissions[]"
                                                                   value="shipping_web_delete">
                                                            <label class="custom-control-label text-secondary"
                                                                   for="customCheck80">@lang('backoffice.delete')</label>
                                                        </div>
                                                    </td>
                                                </tr>
                                                <tr>
                                                    <td> @lang('backoffice.payment_gateways')  </td>
                                                    <td>
                                                        <div class="custom-control custom-checkbox col-sm-2 ">
                                                            <input type="checkbox" class="custom-control-input"
                                                                   id="customCheck81" form="role-form"
                                                                   name="permissions[]"
                                                                   value="payment_web_list">
                                                            <label class="custom-control-label text-secondary"
                                                                   for="customCheck81">@lang('backoffice.list')</label>
                                                        </div>
                                                    </td>
                                                    <td>
                                                        <div class="custom-control custom-checkbox col-sm-2 ">
                                                            <input type="checkbox" class="custom-control-input"
                                                                   id="customCheck82" form="role-form"
                                                                   name="permissions[]"
                                                                   value="payment_web_add">
                                                            <label class="custom-control-label text-secondary"
                                                                   for="customCheck82">@lang('backoffice.add')</label>
                                                        </div>

                                                    </td>
                                                    <td>
                                                        <div class="custom-control custom-checkbox col-sm-2 ">
                                                            <input type="checkbox" class="custom-control-input"
                                                                   id="customCheck83" form="role-form"
                                                                   name="permissions[]"
                                                                   value="payment_web_edit">
                                                            <label class="custom-control-label text-secondary"
                                                                   for="customCheck83">@lang('backoffice.edit')</label>
                                                        </div>
                                                    </td>
                                                    <td>
                                                        <div class="custom-control custom-checkbox col-sm-2 ">
                                                            <input type="checkbox" class="custom-control-input"
                                                                   id="customCheck84" form="role-form"
                                                                   name="permissions[]"
                                                                   value="payment_web_delete">
                                                            <label class="custom-control-label text-secondary"
                                                                   for="customCheck84">@lang('backoffice.delete')</label>
                                                        </div>
                                                    </td>
                                                </tr>
                                                <tr>
                                                    <td> @lang('backoffice.banners')  </td>
                                                    <td>
                                                        <div class="custom-control custom-checkbox col-sm-2 ">
                                                            <input type="checkbox" class="custom-control-input"
                                                                   id="customCheck89" form="role-form"
                                                                   name="permissions[]"
                                                                   value="banner_web_list">
                                                            <label class="custom-control-label text-secondary"
                                                                   for="customCheck89">@lang('backoffice.list')</label>
                                                        </div>
                                                    </td>
                                                    <td>
                                                        <div class="custom-control custom-checkbox col-sm-2 ">
                                                            <input type="checkbox" class="custom-control-input"
                                                                   id="customCheck90" form="role-form"
                                                                   name="permissions[]"
                                                                   value="banner_web_add">
                                                            <label class="custom-control-label text-secondary"
                                                                   for="customCheck90">@lang('backoffice.add')</label>
                                                        </div>

                                                    </td>
                                                    <td>
                                                        <div class="custom-control custom-checkbox col-sm-2 ">
                                                            <input type="checkbox" class="custom-control-input"
                                                                   id="customCheck91" form="role-form"
                                                                   name="permissions[]"
                                                                   value="banner_web_edit">
                                                            <label class="custom-control-label text-secondary"
                                                                   for="customCheck91">@lang('backoffice.edit')</label>
                                                        </div>
                                                    </td>
                                                    <td>
                                                        <div class="custom-control custom-checkbox col-sm-2 ">
                                                            <input type="checkbox" class="custom-control-input"
                                                                   id="customCheck92" form="role-form"
                                                                   name="permissions[]"
                                                                   value="banner_web_delete">
                                                            <label class="custom-control-label text-secondary"
                                                                   for="customCheck92">@lang('backoffice.delete')</label>
                                                        </div>
                                                    </td>
                                                </tr>
                                                <tr>
                                                    <td> @lang('backoffice.pages')  </td>
                                                    <td>
                                                        <div class="custom-control custom-checkbox col-sm-2 ">
                                                            <input type="checkbox" class="custom-control-input"
                                                                   id="customCheck93" form="role-form"
                                                                   name="permissions[]"
                                                                   value="pages_web_list">
                                                            <label class="custom-control-label text-secondary"
                                                                   for="customCheck93">@lang('backoffice.list')</label>
                                                        </div>
                                                    </td>
                                                    <td>
                                                        <div class="custom-control custom-checkbox col-sm-2 ">
                                                            <input type="checkbox" class="custom-control-input"
                                                                   id="customCheck94" form="role-form"
                                                                   name="permissions[]"
                                                                   value="pages_web_add">
                                                            <label class="custom-control-label text-secondary"
                                                                   for="customCheck94">@lang('backoffice.add')</label>
                                                        </div>

                                                    </td>
                                                    <td>
                                                        <div class="custom-control custom-checkbox col-sm-2 ">
                                                            <input type="checkbox" class="custom-control-input"
                                                                   id="customCheck95" form="role-form"
                                                                   name="permissions[]"
                                                                   value="pages_web_edit">
                                                            <label class="custom-control-label text-secondary"
                                                                   for="customCheck95">@lang('backoffice.edit')</label>
                                                        </div>
                                                    </td>
                                                    <td>
                                                        <div class="custom-control custom-checkbox col-sm-2 ">
                                                            <input type="checkbox" class="custom-control-input"
                                                                   id="customCheck96" form="role-form"
                                                                   name="permissions[]"
                                                                   value="pages_web_delete">
                                                            <label class="custom-control-label text-secondary"
                                                                   for="customCheck96">@lang('backoffice.delete')</label>
                                                        </div>
                                                    </td>
                                                </tr>

                                                </tbody>

                                            </table>







































                                    <!--<div class="padding-panel">
                                            <div class="row">
                                                <div class="col-sm-4">
                                                    <label class="rolelable">
                                                        <i></i> @lang('site.sell')
                                        </label>
                                    </div>
                                    <div class="col-sm-8">
                                        <p>
                                            <label class="i-checks">
                                                <input value="sell_sell_and_open_close" name="permissions[]"
                                                                   type="checkbox" <?php if (in_array("sell_sell_and_open_close", $name)) {
                                        echo "checked";
                                    } ?>>
                                                            <i></i> @lang('backoffice.SellandOpenClose')
                                        </label>

<!-- <label class="i-checks">
                                                <input value="sell_product_level_discount" name="permissions[]" type="checkbox">
                                                <i></i> @lang('backoffice.ProductLeveldiscount')
                                        </label>

                                        <label class="i-checks">
                                            <input value="sell_invoice_level_discount" name="permissions[]" type="checkbox">
                                                <i></i> @lang('backoffice.Invoiceleveldiscount')
                                        </label>
                                    </p>

                                </div>
                                <div class="clearfix"></div>
                            </div>
                        </div>
                        <hr>


                   <div class="padding-panel">
                            <div class="row">
                                <div class="col-sm-4">
                                    <label class="rolelable">
                                        <i></i> @lang('backoffice.cash_management')
                                        </label>
                                    </div>
                                    <div class="col-sm-8">
                                        <p>
                                            <label class="i-checks">
                                                <input value="cash_management_list" name="permissions[]"
                                                                   type="checkbox" <?php if (in_array("cash_management_list", $name)) {
                                        echo "checked";
                                    } ?>>
                                                            <i></i> @lang('backoffice.list')
                                        </label>

                                        <label class="i-checks">
                                            <input value="cash_management_add_cash" name="permissions[]"
                                                                   type="checkbox" <?php if (in_array("cash_management_add_cash", $name)) {
                                        echo "checked";
                                    } ?>>
                                                            <i></i> @lang('backoffice.add_cash')
                                        </label>

                                        <label class="i-checks">
                                            <input value="cash_management_remove_cash"
                                                   name="permissions[]"
                                                                   type="checkbox" <?php if (in_array("cash_management_remove_cash", $name)) {
                                        echo "checked";
                                    } ?>>
                                                            <i></i> @lang('backoffice.remove_cash')
                                        </label>
                                    </p>
                                </div>
                                <div class="clearfix"></div>
                            </div>
                        </div>
                        <hr>-->


                                    <!--<div class="padding-panel">
                                            <div class="row">
                                                <div class="col-sm-4">
                                                    <label class="rolelable">
                                                        @lang('backoffice.sales_ledger')
                                        </label>
                                    </div>
                                    <div class="col-sm-8">

                                        <p>
                                            <label class="i-checks">
                                                <input value="sales_ledger_sales_ledger"
                                                       name="permissions[]"
                                                                   type="checkbox" <?php if (in_array("sales_ledger_sales_ledger", $name)) {
                                        echo "checked";
                                    } ?>>
                                                            <i></i> @lang('backoffice.sales_ledger')
                                        </label>

                                        <label class="i-checks">
                                            <input value="sales_ledger_sales_return"
                                                   name="permissions[]"
                                                                   type="checkbox" <?php if (in_array("sales_ledger_sales_return", $name)) {
                                        echo "checked";
                                    } ?>>
                                                            <i></i> @lang('backoffice.SalesReturn')
                                        </label>

                                        <label class="i-checks">
                                            <input value="sales_ledger_void_sales" name="permissions[]"
                                                                   type="checkbox" <?php if (in_array("sales_ledger_void_sales", $name)) {
                                        echo "checked";
                                    } ?>>
                                                            <i></i> @lang('site.VoidSales')
                                        </label>

                                        <label class="i-checks">
                                            <input value="sales_ledger_view_receipt"
                                                   name="permissions[]"
                                                                   type="checkbox" <?php if (in_array("sales_ledger_view_receipt", $name)) {
                                        echo "checked";
                                    } ?>>
                                                            <i></i> @lang('site.view_receipt')
                                        </label>
                                    </p>
{{--
                                                    <p>
                                                        <label class="i-checks">
                                                            <input value="sales_ledger_edit_sales" name="permissions[]" type="checkbox" c>
                                                            <i></i> @lang('site.EditSales')
                                                        </label>
                                                    </p>--}}

                                        </div>
                                        <div class="clearfix"></div>
                                    </div>
                                </div>
                                <hr>-->


                                        <div class="padding-panel">
                                            <div class="row">
                                                <div class="col-sm-4">
                                                    <label class="rolelable">
                                                        @lang('backoffice.reporting')
                                                    </label>
                                                </div>
                                                <div class="col-sm-8">

                                                    <p>
                                                        <label class="i-checks">
                                                            <input value="reporting_sales_report" name="permissions[]"
                                                                   type="checkbox" <?php if (in_array("reporting_sales_report", $name)) {
                                                                echo "checked";
                                                            } ?>>
                                                            <i></i> @lang('backoffice.sales_reports')
                                                        </label>

                                                        <label class="i-checks">
                                                            <input value="sales_export" name="permissions[]"
                                                                   type="checkbox" <?php if (in_array("sales_export", $name)) {
                                                                echo "checked";
                                                            } ?>>
                                                            <i></i> @lang('backoffice.sales_export')
                                                        </label>
                                                    </p>
                                                    <p>
                                                        <label class="i-checks">
                                                            <input value="reporting_inventory_report"
                                                                   name="permissions[]"
                                                                   type="checkbox" <?php if (in_array("reporting_inventory_report", $name)) {
                                                                echo "checked";
                                                            } ?>>
                                                            <i></i> @lang('site.inventory_reports')
                                                        </label>
                                                        <label class="i-checks">
                                                            <input value="inventory_export" name="permissions[]"
                                                                   type="checkbox" <?php if (in_array("inventory_export", $name)) {
                                                                echo "checked";
                                                            } ?>>
                                                            <i></i> @lang('backoffice.inventory_export')
                                                        </label>
                                                    </p>
                                                    <p>
                                                        <label class="i-checks">
                                                            <input value="reporting_register_closure"
                                                                   name="permissions[]"
                                                                   type="checkbox" <?php if (in_array("reporting_register_closure", $name)) {
                                                                echo "checked";
                                                            } ?>>
                                                            <i></i> @lang('backoffice.register_losure')
                                                        </label>
                                                        <label class="i-checks">
                                                            <input value="register_export" name="permissions[]"
                                                                   type="checkbox" <?php if (in_array("register_export", $name)) {
                                                                echo "checked";
                                                            } ?>>
                                                            <i></i> @lang('backoffice.register_export')
                                                        </label>
                                                    </p>
                                                    <p>
                                                        <label class="i-checks">
                                                            <input value="product_report"
                                                                   name="permissions[]"
                                                                   type="checkbox"<?php if (in_array("product_report", $name)) {
                                                                echo "checked";
                                                            } ?>>
                                                            <i></i> @lang('backoffice.product_report')
                                                        </label>
                                                        <label class="i-checks">
                                                            <input value="product_export" name="permissions[]"
                                                                   type="checkbox"<?php if (in_array("product_export", $name)) {
                                                                echo "checked";
                                                            } ?>>
                                                            <i></i> @lang('backoffice.product_export')
                                                        </label>
                                                    </p>


                                                    <p>
                                                        <label class="i-checks">
                                                            <input value="category_report"
                                                                   name="permissions[]"
                                                                   type="checkbox"<?php if (in_array("category_report", $name)) {
                                                                echo "checked";
                                                            } ?>>
                                                            <i></i> @lang('backoffice.category_report')
                                                        </label>
                                                        <label class="i-checks">
                                                            <input value="category_export" name="permissions[]"
                                                                   type="checkbox"<?php if (in_array("category_export", $name)) {
                                                                echo "checked";
                                                            } ?>>
                                                            <i></i> @lang('backoffice.category_export')
                                                        </label>
                                                    </p>


                                                    <p>
                                                        <label class="i-checks">
                                                            <input value="customer_report"
                                                                   name="permissions[]"
                                                                   type="checkbox"<?php if (in_array("customer_report", $name)) {
                                                                echo "checked";
                                                            } ?>>
                                                            <i></i> @lang('backoffice.customer_report')
                                                        </label>
                                                        <label class="i-checks">
                                                            <input value="customer_export" name="permissions[]"
                                                                   type="checkbox"<?php if (in_array("customer_export", $name)) {
                                                                echo "checked";
                                                            } ?>>
                                                            <i></i> @lang('backoffice.customer_export')
                                                        </label>
                                                    </p>

                                                    <p>
                                                        <label class="i-checks">
                                                            <input value="customer_group_report"
                                                                   name="permissions[]"
                                                                   type="checkbox"<?php if (in_array("customer_group_report", $name)) {
                                                                echo "checked";
                                                            } ?>>
                                                            <i></i> @lang('backoffice.customer_group_report')
                                                        </label>
                                                        <label class="i-checks">
                                                            <input value="customer_group_export" name="permissions[]"
                                                                   type="checkbox"<?php if (in_array("customer_group_export", $name)) {
                                                                echo "checked";
                                                            } ?>>
                                                            <i></i> @lang('backoffice.customer_group_export')
                                                        </label>
                                                    </p>


                                                    <p>
                                                        <label class="i-checks">
                                                            <input value="supplier_report"
                                                                   name="permissions[]"
                                                                   type="checkbox"<?php if (in_array("supplier_report", $name)) {
                                                                echo "checked";
                                                            } ?>>
                                                            <i></i> @lang('backoffice.supplier_report')
                                                        </label>
                                                        <label class="i-checks">
                                                            <input value="supplier_export" name="permissions[]"
                                                                   type="checkbox" <?php if (in_array("supplier_export", $name)) {
                                                                echo "checked";
                                                            } ?>>
                                                            <i></i> @lang('backoffice.supplier_export')
                                                        </label>
                                                    </p>
                                                    <p>
                                                        <label class="i-checks">
                                                            <input value="brand_report"
                                                                   name="permissions[]"
                                                                   type="checkbox" <?php if (in_array("brand_report", $name)) {
                                                                echo "checked";
                                                            } ?>>
                                                            <i></i> @lang('backoffice.brand_report')
                                                        </label>
                                                        <label class="i-checks">
                                                            <input value="brand_export" name="permissions[]"
                                                                   type="checkbox" <?php if (in_array("brand_export", $name)) {
                                                                echo "checked";
                                                            } ?>>
                                                            <i></i> @lang('backoffice.brand_export')
                                                        </label>
                                                    </p>
                                                    <p>
                                                        <label class="i-checks">
                                                            <input value="user_report"
                                                                   name="permissions[]"
                                                                   type="checkbox"<?php if (in_array("user_report", $name)) {
                                                                echo "checked";
                                                            } ?>>
                                                            <i></i> @lang('backoffice.user_report')
                                                        </label>
                                                        <label class="i-checks">
                                                            <input value="user_export" name="permissions[]"
                                                                   type="checkbox"<?php if (in_array("user_export", $name)) {
                                                                echo "checked";
                                                            } ?>>
                                                            <i></i> @lang('backoffice.user_export')
                                                        </label>
                                                    </p>
                                                    <p>
                                                        <label class="i-checks">
                                                            <input value="addon_report"
                                                                   name="permissions[]"
                                                                   type="checkbox"<?php if (in_array("addon_report", $name)) {
                                                                echo "checked";
                                                            } ?>>
                                                            <i></i> @lang('backoffice.addon_report')
                                                        </label>
                                                        <label class="i-checks">
                                                            <input value="addon_export" name="permissions[]"
                                                                   type="checkbox"<?php if (in_array("addon_export", $name)) {
                                                                echo "checked";
                                                            } ?>>
                                                            <i></i> @lang('backoffice.addon_export')
                                                        </label>
                                                    </p>

                                                </div>
                                                <div class="clearfix"></div>
                                            </div>
                                        </div>
                                        <hr>
                                        <div class="padding-panel">
                                            <div class="row">
                                                <div class="col-sm-4">
                                                    <label class="rolelable"> @lang('backoffice.product') </label>
                                                </div>
                                                <div class="col-sm-8">
                                                    <p>
                                                        <label class="i-checks">
                                                            <input value="product_list" name="permissions[]"
                                                                   type="checkbox" <?php if (in_array("product_list", $name)) {
                                                                echo "checked";
                                                            } ?>>
                                                            <i></i> @lang('backoffice.list')
                                                        </label>
                                                        <label class="i-checks">
                                                            <input value="product_add" name="permissions[]"
                                                                   type="checkbox" <?php if (in_array("product_add", $name)) {
                                                                echo "checked";
                                                            } ?>>
                                                            <i></i> @lang('backoffice.add')
                                                        </label>
                                                        <label class="i-checks">
                                                            <input value="product_edit" name="permissions[]"
                                                                   type="checkbox" <?php if (in_array("product_edit", $name)) {
                                                                echo "checked";
                                                            } ?>>
                                                            <i></i> @lang('backoffice.edit')
                                                        </label>
                                                        <label class="i-checks">
                                                            <input value="product_delete" name="permissions[]"
                                                                   type="checkbox"<?php if (in_array("product_delete", $name)) {
                                                                echo "checked";
                                                            } ?>>
                                                            <i></i> @lang('backoffice.delete')
                                                        </label>

                                                    <!--<label class="i-checks">
                                                <input value="product_view" name="permissions[]" type="checkbox">
                                                <i></i> @lang('backoffice.view')
                                                        </label>
                                                        <label class="i-checks">
                                                            <input value="product_manage_inventory" name="permissions[]" type="checkbox">
                                                <i></i> @lang('backoffice.ManageInventory')
                                                        </label> -->
                                                    </p>

                                                </div>
                                                <div class="clearfix"></div>
                                            </div>
                                        </div>
                                        <hr>
                                        <div class="padding-panel">
                                            <div class="row">
                                                <div class="col-sm-4">
                                                    <label class="rolelable"> @lang('backoffice.import_catalogue') </label>
                                                </div>
                                                <div class="col-sm-8">
                                                    <p>
                                                        <label class="i-checks">
                                                            <input value="import_catalogue" name="permissions[]"
                                                                   type="checkbox" <?php if (in_array("import_catalogue", $name)) {
                                                                echo "checked";
                                                            } ?>>
                                                            <i></i> @lang('backoffice.catalogue_import')
                                                        </label>
                                                        {{--                                                        <label class="i-checks">--}}
                                                        {{--                                                            <input value="addons_add" name="permissions[]"--}}
                                                        {{--                                                                   type="checkbox">--}}
                                                        {{--                                                            <i></i> @lang('site.add')--}}
                                                        {{--                                                        </label>--}}
                                                        {{--                                                        <label class="i-checks">--}}
                                                        {{--                                                            <input value="addons_edit" name="permissions[]"--}}
                                                        {{--                                                                   type="checkbox">--}}
                                                        {{--                                                            <i></i> @lang('site.edit')--}}
                                                        {{--                                                        </label>--}}
                                                        {{--                                                        <label class="i-checks">--}}
                                                        {{--                                                            <input value="addons_view" name="permissions[]"--}}
                                                        {{--                                                                   type="checkbox">--}}
                                                        {{--                                                            <i></i> @lang('site.view')--}}
                                                        {{--                                                        </label>--}}
                                                        {{--                                                        <label class="i-checks">--}}
                                                        {{--                                                            <input value="addons_delete" name="permissions[]"--}}
                                                        {{--                                                                   type="checkbox">--}}
                                                        {{--                                                            <i></i> @lang('site.delete')--}}
                                                        {{--                                                        </label>--}}
                                                    </p>

                                                </div>
                                                <div class="clearfix"></div>
                                            </div>
                                        </div>
                                        <hr>
                                    <!--<div class="padding-panel">
                                            <div class="row">
                                                <div class="col-sm-4">
                                                    <label class="rolelable"> @lang('backoffice.stock_control') </label>
                                                </div>
                                                <div class="col-sm-8">
                                                    <p>
                                                        <label class="i-checks">
                                                            <input value="stock_control" name="permissions[]"
                                                                   type="checkbox" <?php if (in_array("stock_control", $name)) {
                                        echo "checked";
                                    } ?>>
                                                            <i></i>@lang('backoffice.list')
                                        </label>
                                        <label class="i-checks">
                                            <input value="view_Stock" name="permissions[]"
                                                                   type="checkbox" <?php if (in_array("view_Stock", $name)) {
                                        echo "checked";
                                    } ?>>
                                                            <i></i> @lang('backoffice.ViewStock')
                                        </label>
                                        <label class="i-checks">
                                            <input value="order_stock" name="permissions[]"
                                                                   type="checkbox" <?php if (in_array("order_stock", $name)) {
                                        echo "checked";
                                    } ?>>
                                                            <i></i> @lang('backoffice.order_stock')
                                        </label>
                                        <label class="i-checks">
                                            <input value="return_stock" name="permissions[]"
                                                                   type="checkbox" <?php if (in_array("return_stock", $name)) {
                                        echo "checked";
                                    } ?>>
                                                            <i></i> @lang('backoffice.return_stock')
                                        </label>
                                        <label class="i-checks">
                                            <input value="transfer_stock" name="permissions[]"
                                                                   type="checkbox" <?php if (in_array("transfer_stock", $name)) {
                                        echo "checked";
                                    } ?>>
                                                            <i></i> @lang('backoffice.transfer_stock')
                                        </label>
                                        <label class="i-checks">
                                            <input value="composite_stock" name="permissions[]"
                                                                   type="checkbox" <?php if (in_array("composite_stock", $name)) {
                                        echo "checked";
                                    } ?>>
                                                            <i></i> @lang('backoffice.composite_stock')
                                        </label>


                                    </p>

                                </div>
                                <div class="clearfix"></div>
                            </div>
                        </div>
                        <hr>-->

                                        <div class="padding-panel">
                                            <div class="row">
                                                <div class="col-sm-4">
                                                    <label class="rolelable"> @lang('backoffice.category') </label>
                                                </div>
                                                <div class="col-sm-8">
                                                    <p>
                                                        <label class="i-checks">
                                                            <input value="product_type_list" name="permissions[]"
                                                                   type="checkbox" <?php if (in_array("product_type_list", $name)) {
                                                                echo "checked";
                                                            } ?>>
                                                            <i></i> @lang('backoffice.list')
                                                        </label>
                                                        <label class="i-checks">
                                                            <input value="product_type_add" name="permissions[]"
                                                                   type="checkbox" <?php if (in_array("product_type_add", $name)) {
                                                                echo "checked";
                                                            } ?>>
                                                            <i></i> @lang('backoffice.add')
                                                        </label>
                                                        <label class="i-checks">
                                                            <input value="product_type_edit" name="permissions[]"
                                                                   type="checkbox" <?php if (in_array("product_type_edit", $name)) {
                                                                echo "checked";
                                                            } ?>>
                                                            <i></i> @lang('backoffice.edit')
                                                        </label>

                                                    <!-- <label class="i-checks">
                                                <input value="product_type_view" name="permissions[]" type="checkbox">
                                                <i></i> @lang('site.view')
                                                        </label> -->
                                                        <label class="i-checks">
                                                            <input value="product_type_delete" name="permissions[]"
                                                                   type="checkbox"<?php if (in_array("product_type_delete", $name)) {
                                                                echo "checked";
                                                            } ?>>
                                                            <i></i> @lang('backoffice.delete')
                                                        </label>
                                                    </p>

                                                </div>
                                                <div class="clearfix"></div>
                                            </div>
                                        </div>
                                        <hr>

                                    <!--<div class="padding-panel">
                                            <div class="row">
                                                <div class="col-sm-4">
                                                    <label class="rolelable">@lang('backoffice.product_tags') </label>
                                                </div>
                                                <div class="col-sm-8">
                                                    <p>
                                                        <label class="i-checks">
                                                            <input value="product_tag_list" name="permissions[]"
                                                                   type="checkbox" <?php if (in_array("product_tag_list", $name)) {
                                        echo "checked";
                                    } ?>>
                                                            <i></i> @lang('backoffice.list')
                                        </label>
                                        <label class="i-checks">
                                            <input value="product_tag_add" name="permissions[]"
                                                                   type="checkbox" <?php if (in_array("product_tag_add", $name)) {
                                        echo "checked";
                                    } ?>>
                                                            <i></i> @lang('backoffice.add')

                                        </label>
                                        <label class="i-checks">
                                            <input value="product_tag_edit" name="permissions[]"
                                                                   type="checkbox" <?php if (in_array("product_tag_edit", $name)) {
                                        echo "checked";
                                    } ?>>
                                                            <i></i> @lang('backoffice.edit')
                                        </label>
                                        <label class="i-checks">
                                            <input value="product_tag_view" name="permissions[]"
                                                                   type="checkbox" <?php if (in_array("product_tag_view",
                                        $name)) {
                                        echo "checked";
                                    } ?>>
                                                            <i></i> @lang('backoffice.view')
                                        </label>
                                        <label class="i-checks">
                                            <input value="product_tag_delete" name="permissions[]"
                                                                   type="checkbox" <?php if (in_array("product_tag_delete", $name)) {
                                        echo "checked";
                                    } ?>>
                                                            <i></i> @lang('backoffice.delete')
                                        </label>
                                    </p>

                                </div>
                                <div class="clearfix"></div>
                            </div>
                        </div>
                        <hr>-->

                                        <div class="padding-panel">
                                            <div class="row">
                                                <div class="col-sm-4">
                                                    <label class="rolelable"> @lang('backoffice.supplier') </label>
                                                </div>
                                                <div class="col-sm-8">
                                                    <p>
                                                        <label class="i-checks">
                                                            <input value="supplier_list" name="permissions[]"
                                                                   type="checkbox" <?php if (in_array("supplier_list", $name)) {
                                                                echo "checked";
                                                            } ?>>
                                                            <i></i> @lang('backoffice.list')
                                                        </label>
                                                        <label class="i-checks">
                                                            <input value="supplier_add" name="permissions[]"
                                                                   type="checkbox" <?php if (in_array("supplier_add", $name)) {
                                                                echo "checked";
                                                            } ?>>
                                                            <i></i> @lang('backoffice.add')
                                                        </label>
                                                        <label class="i-checks">
                                                            <input value="supplier_edit" name="permissions[]"
                                                                   type="checkbox" <?php if (in_array("supplier_edit", $name)) {
                                                                echo "checked";
                                                            } ?>>
                                                            <i></i> @lang('backoffice.edit')
                                                        </label>
                                                        <label class="i-checks">
                                                            <input value="supplier_view" name="permissions[]"
                                                                   type="checkbox" <?php if (in_array("supplier_view", $name)) {
                                                                echo "checked";
                                                            } ?>>
                                                            <i></i> @lang('backoffice.view')
                                                        </label>
                                                        <label class="i-checks">
                                                            <input value="supplier_delete" name="permissions[]"
                                                                   type="checkbox" <?php if (in_array("supplier_delete", $name)) {
                                                                echo "checked";
                                                            } ?>>
                                                            <i></i> @lang('backoffice.delete')
                                                        </label>
                                                    </p>

                                                </div>
                                                <div class="clearfix"></div>
                                            </div>
                                        </div>
                                        <hr>

                                        <div class="padding-panel">
                                            <div class="row">
                                                <div class="col-sm-4">
                                                    <label class="rolelable"> @lang('backoffice.addons') </label>
                                                </div>
                                                <div class="col-sm-8">
                                                    <p>
                                                        <label class="i-checks">
                                                            <input value="addons_list" name="permissions[]"
                                                                   type="checkbox"<?php if (in_array("addons_list", $name)) {
                                                                echo "checked";
                                                            } ?>>
                                                            <i></i> @lang('backoffice.list')
                                                        </label>
                                                        <label class="i-checks">
                                                            <input value="addons_add" name="permissions[]"
                                                                   type="checkbox"<?php if (in_array("addons_add", $name)) {
                                                                echo "checked";
                                                            } ?>>
                                                            <i></i> @lang('backoffice.add')
                                                        </label>
                                                        <label class="i-checks">
                                                            <input value="addons_edit" name="permissions[]"
                                                                   type="checkbox"<?php if (in_array("addons_edit", $name)) {
                                                                echo "checked";
                                                            } ?>>
                                                            <i></i> @lang('backoffice.edit')
                                                        </label>
                                                        <label class="i-checks">
                                                            <input value="addons_view" name="permissions[]"
                                                                   type="checkbox"<?php if (in_array("addons_view", $name)) {
                                                                echo "checked";
                                                            } ?>>
                                                            <i></i> @lang('backoffice.view')
                                                        </label>
                                                        <label class="i-checks">
                                                            <input value="addons_delete" name="permissions[]"
                                                                   type="checkbox"<?php if (in_array("addons_delete", $name)) {
                                                                echo "checked";
                                                            } ?>>
                                                            <i></i> @lang('backoffice.delete')
                                                        </label>
                                                    </p>

                                                </div>
                                                <div class="clearfix"></div>
                                            </div>
                                        </div>
                                        <div class="padding-panel">
                                            <div class="row">
                                                <div class="col-sm-4">
                                                    <label class="rolelable"> @lang('backoffice.addonsitem') </label>
                                                </div>
                                                <div class="col-sm-8">
                                                    <p>
                                                        <label class="i-checks">
                                                            <input value="addonsitem_list" name="permissions[]"
                                                                   type="checkbox"<?php if (in_array("addonsitem_list", $name)) {
                                                                echo "checked";
                                                            } ?>>
                                                            <i></i> @lang('backoffice.list')
                                                        </label>
                                                        <label class="i-checks">
                                                            <input value="addonsitem_add" name="permissions[]"
                                                                   type="checkbox"<?php if (in_array("addonsitem_add", $name)) {
                                                                echo "checked";
                                                            } ?>>
                                                            <i></i> @lang('backoffice.add')
                                                        </label>
                                                        <label class="i-checks">
                                                            <input value="addonsitem_edit" name="permissions[]"
                                                                   type="checkbox"<?php if (in_array("addonsitem_edit", $name)) {
                                                                echo "checked";
                                                            } ?>>
                                                            <i></i> @lang('backoffice.edit')
                                                        </label>
                                                        <label class="i-checks">
                                                            <input value="addonsitem_view" name="permissions[]"
                                                                   type="checkbox"<?php if (in_array("addonsitem_view", $name)) {
                                                                echo "checked";
                                                            } ?>>
                                                            <i></i> @lang('backoffice.view')
                                                        </label>
                                                        <label class="i-checks">
                                                            <input value="addonsitem_delete" name="permissions[]"
                                                                   type="checkbox"<?php if (in_array("addonsitem_delete", $name)) {
                                                                echo "checked";
                                                            } ?>>
                                                            <i></i> @lang('backoffice.delete')
                                                        </label>
                                                    </p>

                                                </div>
                                                <div class="clearfix"></div>
                                            </div>
                                        </div>
                                        <hr>
                                        <div class="padding-panel">
                                            <div class="row">
                                                <div class="col-sm-4">
                                                    <label class="rolelable">@lang('backoffice.brand') </label>
                                                </div>
                                                <div class="col-sm-8">
                                                    <p>
                                                        <label class="i-checks">
                                                            <input value="brand_list" name="permissions[]"
                                                                   type="checkbox" <?php if (in_array("brand_list", $name)) {
                                                                echo "checked";
                                                            } ?>>
                                                            <i></i> @lang('backoffice.list')
                                                        </label>
                                                        <label class="i-checks">
                                                            <input value="brand_add" name="permissions[]"
                                                                   type="checkbox" <?php if (in_array("brand_add", $name)) {
                                                                echo "checked";
                                                            } ?>>
                                                            <i></i> @lang('backoffice.add')
                                                        </label>
                                                        <label class="i-checks">
                                                            <input value="brand_edit" name="permissions[]"
                                                                   type="checkbox" <?php if (in_array("brand_edit", $name)) {
                                                                echo "checked";
                                                            } ?>>
                                                            <i></i> @lang('backoffice.edit')
                                                        </label>
                                                        <label class="i-checks">
                                                            <input value="brand_view" name="permissions[]"
                                                                   type="checkbox" <?php if (in_array("brand_view", $name)) {
                                                                echo "checked";
                                                            } ?>>
                                                            <i></i> @lang('backoffice.view')
                                                        </label>
                                                        <label class="i-checks">
                                                            <input value="brand_delete" name="permissions[]"
                                                                   type="checkbox" <?php if (in_array("brand_delete", $name)) {
                                                                echo "checked";
                                                            } ?>>
                                                            <i></i> @lang('backoffice.delete')
                                                        </label>
                                                    </p>

                                                </div>
                                                <div class="clearfix"></div>
                                            </div>
                                        </div>
                                        <hr>
                                        <div class="padding-panel">
                                            <div class="row">
                                                <div class="col-sm-4">
                                                    <label class="rolelable"> @lang('site.customer') </label>
                                                </div>
                                                <div class="col-sm-8">
                                                    <p>
                                                        <label class="i-checks">
                                                            <input value="customer_list" name="permissions[]"
                                                                   type="checkbox" <?php if (in_array("customer_list", $name)) {
                                                                echo "checked";
                                                            } ?>>
                                                            <i></i> @lang('backoffice.list')
                                                        </label>
                                                        <label class="i-checks">
                                                            <input value="customer_add" name="permissions[]"
                                                                   type="checkbox" <?php if (in_array("customer_add", $name)) {
                                                                echo "checked";
                                                            } ?>>
                                                            <i></i> @lang('backoffice.add')
                                                        </label>
                                                        <label class="i-checks">
                                                            <input value="customer_edit" name="permissions[]"
                                                                   type="checkbox" <?php if (in_array("customer_edit", $name)) {
                                                                echo "checked";
                                                            } ?>>
                                                            <i></i> @lang('backoffice.edit')
                                                        </label>
                                                    <!-- <label class="i-checks">
                                                <input value="customer_view" name="permissions[]" type="checkbox">
                                                <i></i> @lang('site.view')
                                                        </label> -->
                                                    </p>

                                                </div>
                                                <div class="clearfix"></div>
                                            </div>
                                        </div>
                                        <hr>
                                        <div class="padding-panel">
                                            <div class="row">
                                                <div class="col-sm-4">
                                                    <label class="rolelable"> @lang('backoffice.customer_group') </label>
                                                </div>
                                                <div class="col-sm-8">
                                                    <p>
                                                        <label class="i-checks">
                                                            <input value="customer_group_list" name="permissions[]"
                                                                   type="checkbox" <?php if (in_array("customer_group_list", $name)) {
                                                                echo "checked";
                                                            } ?>>
                                                            <i></i> @lang('backoffice.list')
                                                        </label>
                                                        <label class="i-checks">
                                                            <input value="customer_group_add" name="permissions[]"
                                                                   type="checkbox" <?php if (in_array("customer_group_add", $name)) {
                                                                echo "checked";
                                                            } ?>>
                                                            <i></i> @lang('backoffice.add')
                                                        </label>
                                                        <label class="i-checks">
                                                            <input value="customer_group_edit" name="permissions[]"
                                                                   type="checkbox" <?php if (in_array("customer_group_edit", $name)) {
                                                                echo "checked";
                                                            } ?>>
                                                            <i></i> @lang('backoffice.edit')
                                                        </label>
                                                        <label class="i-checks">
                                                            <input value="customer_group_view" name="permissions[]"
                                                                   type="checkbox" <?php if (in_array("customer_group_view", $name)) {
                                                                echo "checked";
                                                            } ?>>
                                                            <i></i> @lang('backoffice.view')
                                                        </label>
                                                        <label class="i-checks">
                                                            <input value="customer_group_delete" name="permissions[]"
                                                                   type="checkbox" <?php if (in_array("customer_group_delete", $name)) {
                                                                echo "checked";
                                                            } ?>>
                                                            <i></i> @lang('backoffice.delete')
                                                        </label>
                                                    </p>

                                                </div>
                                                <div class="clearfix"></div>
                                            </div>
                                        </div>
                                        <hr>
                                        <div class="padding-panel">
                                            <div class="row">
                                                <div class="col-sm-4">
                                                    <label class="rolelable">@lang('backoffice.general_setup') </label>
                                                </div>
                                                <div class="col-sm-8">
                                                    <p>
                                                        <label class="i-checks">
                                                            <input value="general_setup_edit" name="permissions[]"
                                                                   type="checkbox" <?php if (in_array("general_setup_edit", $name)) {
                                                                echo "checked";
                                                            } ?>>
                                                            <i></i> @lang('backoffice.edit')
                                                        </label>
                                                    </p>

                                                </div>
                                                <div class="clearfix"></div>
                                            </div>
                                        </div>
                                        <hr>

                                        <div class="padding-panel">
                                            <div class="row">
                                                <div class="col-sm-4">
                                                    <label
                                                        class="rolelable"> @lang('backoffice.outlets_and_registers') </label>
                                                </div>
                                                <div class="col-sm-8">
                                                    <p>
                                                        <label class="i-checks">
                                                            <input value="outlet_list" name="permissions[]"
                                                                   type="checkbox" <?php if (in_array("outlet_list", $name)) {
                                                                echo "checked";
                                                            } ?>>
                                                            <i></i> @lang('backoffice.list')
                                                        </label>
                                                        <label class="i-checks">
                                                            <input value="outlet_add" name="permissions[]"
                                                                   type="checkbox" <?php if (in_array("outlet_add", $name)) {
                                                                echo "checked";
                                                            } ?>>
                                                            <i></i> @lang('backoffice.add')
                                                        </label>
                                                        <label class="i-checks">
                                                            <input value="outlet_edit" name="permissions[]"
                                                                   type="checkbox" <?php if (in_array("outlet_edit", $name)) {
                                                                echo "checked";
                                                            } ?>>
                                                            <i></i> @lang('backoffice.edit')
                                                        </label>
                                                        <label class="i-checks">
                                                            <input value="outlet_view" name="permissions[]"
                                                                   type="checkbox" <?php if (in_array("outlet_view", $name)) {
                                                                echo "checked";
                                                            } ?>>
                                                            <i></i> @lang('backoffice.view')
                                                        </label>
                                                    <!-- <label class="i-checks">
                                                <input value="outlet_delete" name="permissions[]" type="checkbox">
                                                <i></i> @lang('site.delete')
                                                        </label> -->
                                                    </p>

                                                </div>
                                                <div class="clearfix"></div>
                                            </div>
                                        </div>
                                        <hr>
                                        <div class="padding-panel">
                                            <div class="row">
                                                <div class="col-sm-4">
                                                    <label class="rolelable">@lang('backoffice.register') </label>
                                                </div>
                                                <div class="col-sm-8">
                                                    <p>
                                                        <label class="i-checks">
                                                            <input value="register_add" name="permissions[]"
                                                                   type="checkbox" <?php if (in_array("register_add", $name)) {
                                                                echo "checked";
                                                            } ?>>
                                                            <i></i> @lang('backoffice.add')
                                                        </label>
                                                        <label class="i-checks">
                                                            <input value="register_edit" name="permissions[]"
                                                                   type="checkbox" <?php if (in_array("register_edit", $name)) {
                                                                echo "checked";
                                                            } ?>>
                                                            <i></i> @lang('backoffice.edit')
                                                        </label>
                                                    <!-- <label class="i-checks">
                                                <input value="register_view" name="permissions[]" type="checkbox">
                                                <i></i> @lang('backoffice.view')
                                                        </label> -->
                                                    <!-- <label class="i-checks">
                                                <input value="register_delete" name="permissions[]" type="checkbox">
                                                <i></i> @lang('backoffice.delete')
                                                        </label> -->
                                                    </p>

                                                </div>
                                                <div class="clearfix"></div>
                                            </div>
                                        </div>

                                        <hr>
                                        <div class="padding-panel">
                                            <div class="row">
                                                <div class="col-sm-4">
                                                    <label class="rolelable">@lang('backoffice.user') </label>
                                                </div>
                                                <div class="col-sm-8">
                                                    <p>
                                                        <label class="i-checks">
                                                            <input value="user_list" name="permissions[]"
                                                                   type="checkbox" <?php if (in_array("user_list", $name)) {
                                                                echo "checked";
                                                            } ?>>
                                                            <i></i> @lang('backoffice.list')
                                                        </label>
                                                        <label class="i-checks">
                                                            <input value="user_add" name="permissions[]"
                                                                   type="checkbox" <?php if (in_array("user_add", $name)) {
                                                                echo "checked";
                                                            } ?>>
                                                            <i></i> @lang('backoffice.add')
                                                        </label>
                                                        <label class="i-checks">
                                                            <input value="user_edit" name="permissions[]"
                                                                   type="checkbox" <?php if (in_array("user_edit", $name)) {
                                                                echo "checked";
                                                            } ?>>
                                                            <i></i> @lang('backoffice.edit')
                                                        </label>
                                                    <!-- <label class="i-checks">
                                                <input value="user_view" name="permissions[]" type="checkbox">
                                                <i></i> @lang('site.view')
                                                        </label> -->
                                                        <label class="i-checks">
                                                            <input value="user_delete" name="permissions[]"
                                                                   type="checkbox" <?php if (in_array("user_delete", $name)) {
                                                                echo "checked";
                                                            } ?>>
                                                            <i></i> @lang('backoffice.delete')
                                                        </label>
                                                    </p>

                                                </div>
                                                <div class="clearfix"></div>
                                            </div>
                                        </div>
                                        <hr>
                                        <div class="padding-panel">
                                            <div class="row">
                                                <div class="col-sm-4">
                                                    <label> @lang('backoffice.roles') </label>
                                                </div>
                                                <div class="col-sm-8">
                                                    <p>
                                                        <label class="i-checks">
                                                            <input value="roles_list" name="permissions[]"
                                                                   type="checkbox" <?php if (in_array("roles_list", $name)) {
                                                                echo "checked";
                                                            } ?>>
                                                            <i></i> @lang('backoffice.list')
                                                        </label>
                                                        <label class="i-checks">
                                                            <input value="roles_add" name="permissions[]"
                                                                   type="checkbox" <?php if (in_array("roles_add", $name)) {
                                                                echo "checked";
                                                            } ?>>
                                                            <i></i> @lang('backoffice.add')
                                                        </label>
                                                        <label class="i-checks">
                                                            <input value="roles_edit" name="permissions[]"
                                                                   type="checkbox" <?php if (in_array("roles_edit", $name)) {
                                                                echo "checked";
                                                            } ?>>
                                                            <i></i> @lang('backoffice.edit')
                                                        </label>
                                                    <!-- <label class="i-checks">
                                                <input value="roles_view" name="permissions[]" type="checkbox">
                                                <i></i> @lang('site.view')
                                                        </label> -->
                                                        <label class="i-checks">
                                                            <input value="roles_delete" name="permissions[]"
                                                                   type="checkbox" <?php if (in_array("roles_delete", $name)) {
                                                                echo "checked";
                                                            } ?>>
                                                            <i></i> @lang('backoffice.delete')
                                                        </label>
                                                    </p>

                                                </div>
                                                <div class="clearfix"></div>
                                            </div>
                                        </div>
                                        <hr>
                                        <div class="padding-panel">
                                            <div class="row">
                                                <div class="col-sm-4">
                                                    <label>
                                                        <i></i> @lang('backoffice.Ecommerce_Setup')

                                                    </label>
                                                </div>
                                                <div class="col-sm-8">
                                                    <p>
                                                        <label class="i-checks">
                                                            <input value="ecommerce_setup_edit" name="permissions[]"
                                                                   type="checkbox" <?php if (in_array("ecommerce_setup_edit", $name)) {
                                                                echo "checked";
                                                            } ?>>
                                                            <i></i> @lang('backoffice.list')
                                                        </label>

                                                    </p>

                                                </div>
                                                <div class="clearfix"></div>
                                            </div>
                                        </div>

                                        <hr>
                                        <div class="padding-panel">
                                            <div class="row">
                                                <div class="col-sm-4">
                                                    <label>
                                                        <i></i> @lang('backoffice.product_list')
                                                    </label>
                                                </div>
                                                <div class="col-sm-8">
                                                    <p>
                                                        <label class="i-checks">
                                                            <input value="product_web_list" name="permissions[]"
                                                                   type="checkbox" <?php if (in_array("product_web_list", $name)) {
                                                                echo "checked";
                                                            } ?>>
                                                            <i></i> @lang('backoffice.list')
                                                        </label>
                                                        <label class="i-checks">
                                                            <input value="product_web_add" name="permissions[]"
                                                                   type="checkbox" <?php if (in_array("product_web_add", $name)) {
                                                                echo "checked";
                                                            } ?>>
                                                            <i></i> @lang('backoffice.add')
                                                        </label>
                                                        <label class="i-checks">
                                                            <input value="product_web_edit" name="permissions[]"
                                                                   type="checkbox" <?php if (in_array("product_web_edit", $name)) {
                                                                echo "checked";
                                                            } ?>>
                                                            <i></i> @lang('backoffice.edit')
                                                        </label>
                                                    <!--label class="i-checks">
                                                <input value="product_web_view" name="permissions[]" type="checkbox">
                                                <i></i> @lang('site.view')
                                                        </label> -->
                                                        <label class="i-checks">
                                                            <input value="product_web_delete" name="permissions[]"
                                                                   type="checkbox" <?php if (in_array("product_web_delete", $name)) {
                                                                echo "checked";
                                                            } ?>>
                                                            <i></i> @lang('backoffice.delete')
                                                        </label>

                                                    </p>

                                                </div>
                                                <div class="clearfix"></div>
                                            </div>
                                        </div>
                                        <hr>

                                        <div class="padding-panel">
                                            <div class="row">
                                                <div class="col-sm-4">
                                                    <label>
                                                        <i></i> @lang('backoffice.shipping_method')
                                                    </label>
                                                </div>
                                                <div class="col-sm-8">
                                                    <p>
                                                        <label class="i-checks">
                                                            <input value="shipping_web_list" name="permissions[]"
                                                                   type="checkbox" <?php if (in_array("shipping_web_list", $name)) {
                                                                echo "checked";
                                                            } ?>>
                                                            <i></i> @lang('backoffice.list')
                                                        </label>
                                                        <label class="i-checks">
                                                            <input value="shipping_web_add" name="permissions[]"
                                                                   type="checkbox" <?php if (in_array("shipping_web_add", $name)) {
                                                                echo "checked";
                                                            } ?>>
                                                            <i></i> @lang('backoffice.add')
                                                        </label>
                                                        <label class="i-checks">
                                                            <input value="shipping_web_edit" name="permissions[]"
                                                                   type="checkbox" <?php if (in_array("shipping_web_edit", $name)) {
                                                                echo "checked";
                                                            } ?>>
                                                            <i></i> @lang('backoffice.edit')
                                                        </label>
                                                    <!-- <label class="i-checks">
                                                <input value="shipping_web_view" name="permissions[]" type="checkbox">
                                                <i></i> @lang('backoffice.view')
                                                        </label> -->
                                                        <label class="i-checks">
                                                            <input value="shipping_web_delete" name="permissions[]"
                                                                   type="checkbox" <?php if (in_array("shipping_web_delete", $name)) {
                                                                echo "checked";
                                                            } ?>>
                                                            <i></i> @lang('backoffice.delete')
                                                        </label>

                                                    </p>

                                                </div>
                                                <div class="clearfix"></div>
                                            </div>
                                        </div>
                                        <hr>

                                        <div class="padding-panel">
                                            <div class="row">
                                                <div class="col-sm-4">
                                                    <label>
                                                        <i></i> @lang('backoffice.payment_gateways')
                                                    </label>
                                                </div>
                                                <div class="col-sm-8">
                                                    <p>
                                                        <label class="i-checks">
                                                            <input value="payment_web_list" name="permissions[]"
                                                                   type="checkbox" <?php if (in_array("payment_web_list", $name)) {
                                                                echo "checked";
                                                            } ?>>
                                                            <i></i> @lang('backoffice.list')
                                                        </label>
                                                        <label class="i-checks">
                                                            <input value="payment_web_add" name="permissions[]"
                                                                   type="checkbox" <?php if (in_array("payment_web_add", $name)) {
                                                                echo "checked";
                                                            } ?>>
                                                            <i></i> @lang('backoffice.add')
                                                        </label>
                                                        <label class="i-checks">
                                                            <input value="payment_web_edit" name="permissions[]"
                                                                   type="checkbox" <?php if (in_array("payment_web_edit", $name)) {
                                                                echo "checked";
                                                            } ?>>
                                                            <i></i> @lang('backoffice.edit')
                                                        </label>
                                                    <!-- <label class="i-checks">
                                                <input value="payment_web_view" name="permissions[]" type="checkbox">
                                                <i></i> @lang('site.view')
                                                        </label> -->
                                                        <label class="i-checks">
                                                            <input value="payment_web_delete" name="permissions[]"
                                                                   type="checkbox" <?php if (in_array("payment_web_delete", $name)) {
                                                                echo "checked";
                                                            } ?>>
                                                            <i></i> @lang('backoffice.delete')
                                                        </label>

                                                    </p>

                                                </div>
                                                <div class="clearfix"></div>
                                            </div>
                                        </div>
                                        <hr>

                                        <div class="padding-panel">
                                            <div class="row">
                                                <div class="col-sm-4">
                                                    <label>
                                                        <i></i> @lang('backoffice.orders_management')
                                                    </label>
                                                </div>
                                                <div class="col-sm-8">
                                                    <p>
                                                        <label class="i-checks">
                                                            <input value="order_web_management_list"
                                                                   name="permissions[]"
                                                                   type="checkbox" <?php if (in_array("order_web_management_list", $name)) {
                                                                echo "checked";
                                                            } ?>>
                                                            <i></i> @lang('backoffice.list')
                                                        </label>
                                                    </p>

                                                </div>
                                                <div class="clearfix"></div>
                                            </div>
                                        </div>
                                        <hr>
                                        <div class="padding-panel">
                                            <div class="row">
                                                <div class="col-sm-4">
                                                    <label>
                                                        <i></i> @lang('backoffice.web_customer')
                                                    </label>
                                                </div>
                                                <div class="col-sm-8">
                                                    <p>
                                                        <label class="i-checks">
                                                            <input value="web_customer_list" name="permissions[]"
                                                                   type="checkbox" <?php if (in_array("web_customer_list", $name)) {
                                                                echo "checked";
                                                            } ?>>
                                                            <i></i> @lang('backoffice.list')
                                                        </label>
                                                    </p>

                                                </div>
                                                <div class="clearfix"></div>
                                            </div>
                                        </div>
                                        <hr>
                                        <div class="padding-panel">
                                            <div class="row">
                                                <div class="col-sm-4">
                                                    <label>
                                                        <i></i> @lang('backoffice.web_subscribe_users')
                                                    </label>
                                                </div>
                                                <div class="col-sm-8">
                                                    <p>
                                                        <label class="i-checks">
                                                            <input value="web_subscribe_user_list" name="permissions[]"
                                                                   type="checkbox" <?php if (in_array("web_subscribe_user_list", $name)) {
                                                                echo "checked";
                                                            } ?>>
                                                            <i></i> @lang('backoffice.list')
                                                        </label>
                                                    </p>

                                                </div>
                                                <div class="clearfix"></div>
                                            </div>
                                        </div>

                                        <hr>
                                        <div class="padding-panel">
                                            <div class="row">
                                                <div class="col-sm-4">
                                                    <label>
                                                        <i></i> @lang('backoffice.banners')
                                                    </label>
                                                </div>
                                                <div class="col-sm-8">
                                                    <p>
                                                        <label class="i-checks">
                                                            <input value="banner_web_list" name="permissions[]"
                                                                   type="checkbox" <?php if (in_array("banner_web_list", $name)) {
                                                                echo "checked";
                                                            } ?>>
                                                            <i></i> @lang('backoffice.list')
                                                        </label>
                                                        <label class="i-checks">
                                                            <input value="banner_web_add" name="permissions[]"
                                                                   type="checkbox" <?php if (in_array("banner_web_add", $name)) {
                                                                echo "checked";
                                                            } ?>>
                                                            <i></i> @lang('backoffice.add')
                                                        </label>
                                                        <label class="i-checks">
                                                            <input value="banner_web_edit" name="permissions[]"
                                                                   type="checkbox" <?php if (in_array("banner_web_edit", $name)) {
                                                                echo "checked";
                                                            } ?>>
                                                            <i></i> @lang('backoffice.edit')
                                                        </label>
                                                    <!-- <label class="i-checks">
                                                <input value="banner_web_view" name="permissions[]" type="checkbox" >
                                                <i></i> @lang('backoffice.view')
                                                        </label> -->
                                                        <label class="i-checks">
                                                            <input value="banner_web_delete" name="permissions[]"
                                                                   type="checkbox" <?php if (in_array("banner_web_delete", $name)) {
                                                                echo "checked";
                                                            } ?>>
                                                            <i></i> @lang('backoffice.delete')
                                                        </label>

                                                    </p>

                                                </div>
                                                <div class="clearfix"></div>
                                            </div>
                                        </div>
                                        <hr>

                                        <div class="padding-panel">
                                            <div class="row">
                                                <div class="col-sm-4">
                                                    <label>
                                                        <i></i> @lang('backoffice.pages')
                                                    </label>
                                                </div>
                                                <div class="col-sm-8">
                                                    <p>
                                                        <label class="i-checks">
                                                            <input value="pages_web_list" name="permissions[]"
                                                                   type="checkbox" <?php if (in_array("pages_web_list", $name)) {
                                                                echo "checked";
                                                            } ?>>
                                                            <i></i> @lang('backoffice.list')
                                                        </label>
                                                        <label class="i-checks">
                                                            <input value="pages_web_add" name="permissions[]"
                                                                   type="checkbox" <?php if (in_array("pages_web_add", $name)) {
                                                                echo "checked";
                                                            } ?>>
                                                            <i></i> @lang('backoffice.add')
                                                        </label>
                                                        <label class="i-checks">
                                                            <input value="pages_web_edit" name="permissions[]"
                                                                   type="checkbox" <?php if (in_array("pages_web_edit", $name)) {
                                                                echo "checked";
                                                            } ?>>
                                                            <i></i> @lang('backoffice.edit')
                                                        </label>
                                                        <label class="i-checks">
                                                            <input value="pages_web_view" name="permissions[]"
                                                                   type="checkbox" <?php if (in_array("pages_web_view", $name)) {
                                                                echo "checked";
                                                            } ?>>
                                                            <i></i>@lang('backoffice.change_status')
                                                        </label>
                                                        <label class="i-checks">
                                                            <input value="pages_web_delete" name="permissions[]"
                                                                   type="checkbox" <?php if (in_array("pages_web_delete", $name)) {
                                                                echo "checked";
                                                            } ?>>
                                                            <i></i> @lang('backoffice.delete')
                                                        </label>

                                                    </p>

                                                </div>
                                                <div class="clearfix"></div>
                                            </div>
                                        </div>
                                        <hr>

                                        <div class="padding-panel">
                                            <div class="row">
                                                <div class="col-sm-4">
                                                    <label>
                                                        <i></i> @lang('backoffice.WebSetting')
                                                    </label>
                                                </div>
                                                <div class="col-sm-8">
                                                    <p>
                                                        <label class="i-checks">
                                                            <input value="web_settings_list" name="permissions[]"
                                                                   type="checkbox" <?php if (in_array("web_settings_list", $name)) {
                                                                echo "checked";
                                                            } ?>>
                                                            <i></i> @lang('backoffice.list')
                                                        </label>
                                                    </p>

                                                </div>
                                                <div class="clearfix"></div>
                                            </div>
                                        </div>
                                        <hr>

                                    <!--<div class="padding-panel">
                                            <div class="row">
                                                <div class="col-sm-4">
                                                    <label>
                                                        <i></i> @lang('backoffice.Invoices')
                                        </label>
                                    </div>
                                    <div class="col-sm-8">
                                        <p>
                                            <label class="i-checks">

                                                <input value="invoices" name="permissions[]"
                                                                   type="checkbox" <?php if (in_array("invoices", $name)) {
                                        echo "checked";
                                    } ?>>
                                                            <i></i> @lang('backoffice.list')
                                        </label>

                                        <label class="i-checks">
                                            <input value="invoice_create" name="permissions[]"
                                                                   type="checkbox" <?php if (in_array("invoice_create", $name)) {
                                        echo "checked";
                                    } ?>>
                                                            <i></i>@lang('backoffice.add')
                                        </label>

                                        <label class="i-checks">
                                            <input value="invoice_view" name="permissions[]"
                                                                   type="checkbox" <?php if (in_array("invoice_view", $name)) {
                                        echo "checked";
                                    } ?>>
                                                            <i></i>@lang('backoffice.view')
                                        </label>

                                        <label class="i-checks">
                                            <input value="invoice_send" name="permissions[]"
                                                                   type="checkbox" <?php if (in_array("invoice_send", $name)) {
                                        echo "checked";
                                    } ?>>
                                                            <i></i>@lang('backoffice.send')
                                        </label>
                                    </p>

                                </div>
                                <div class="clearfix"></div>
                            </div>
                        </div>
                        <hr>
                        <div class="padding-panel">
                            <div class="row">
                                <div class="col-sm-4">
                                    <label>
                                        <i></i> @lang('backoffice.accounts')
                                        </label>
                                    </div>
                                    <div class="col-sm-8">
                                        <p>
                                            <label class="i-checks">
                                                <input value="accounts_summary" name="permissions[]"
                                                                   type="checkbox" <?php if (in_array("accounts_summary", $name)) {
                                        echo "checked";
                                    } ?>>
                                                            <i></i> @lang('backoffice.summary')
                                        </label>
                                    </p>
                                </div>
                                <div class="clearfix"></div>
                            </div>
                        </div>
                        <hr>
                        <div class="padding-panel">
                            <div class="row">
                                <div class="col-sm-4">
                                    <label>
                                        <i></i>@lang('backoffice.Income_Head')
                                        </label>
                                    </div>
                                    <div class="col-sm-8">
                                         <p>
                                        <label class="i-checks">
                                            <input value="income_head_list" name="permissions[]"
                                                               type="checkbox" <?php if (in_array("income_head_list", $name)) {
                                        echo "checked";
                                    } ?>>
                                                        <i></i>@lang('backoffice.list')
                                        </label>


                                        <label class="i-checks">
                                            <input value="income_head_add" name="permissions[]"
                                                               type="checkbox" <?php if (in_array("income_head_add", $name)) {
                                        echo "checked";
                                    } ?>>
                                                        <i></i> @lang('backoffice.add')
                                        </label>
                                          </p>

                                         <p>
                                        <label class="i-checks">
                                            <input value="income_head_edit" name="permissions[]"
                                                               type="checkbox" <?php if (in_array("income_head_edit", $name)) {
                                        echo "checked";
                                    } ?>>
                                                        <i></i> @lang('backoffice.edit')
                                        </label>

                                        </p>
                                         <p>
                                        <label class="i-checks">
                                            <input value="income_head_delete" name="permissions[]"
                                                               type="checkbox" <?php if (in_array("income_head_delete", $name)) {
                                        echo "checked";
                                    } ?>>
                                                        <i></i>@lang('backoffice.delete')
                                        </label>
                                        </p>
                                    </div>
                                    <div class="clearfix"></div>
                                </div>
                            </div>
                            <hr> -->
                                    <!--<div class="padding-panel">
                                            <div class="row">
                                                <div class="col-sm-4">
                                                    <label>
                                                        <i></i>@lang('backoffice.incomeledger')
                                        </label>
                                    </div>
                                    <div class="col-sm-8">
                                         <p>
                                        <label class="i-checks">
                                            <input value="income_ledger_list" name="permissions[]"
                                                               type="checkbox" <?php if (in_array("income_ledger_list", $name)) {
                                        echo "checked";
                                    } ?>>
                                                        <i></i> @lang('backoffice.list')
                                        </label>


                                        <label class="i-checks">
                                            <input value="income_ledger_add" name="permissions[]"
                                                               type="checkbox" <?php if (in_array("income_ledger_add", $name)) {
                                        echo "checked";
                                    } ?>>
                                                        <i></i>@lang('backoffice.add')
                                        </label>

                                        <label class="i-checks">
                                            <input value="income_ledger_edit" name="permissions[]"
                                                               type="checkbox" <?php if (in_array("income_ledger_edit", $name)) {
                                        echo "checked";
                                    } ?>>
                                                        <i></i>@lang('backoffice.edit')
                                        </label>


                                        <label class="i-checks">
                                            <input value="income_ledger_delete" name="permissions[]"
                                                               type="checkbox" <?php if (in_array("income_ledger_delete", $name)) {
                                        echo "checked";
                                    } ?>>
                                                        <i></i> @lang('backoffice.delete')
                                        </label>
                                         </p>
                                    </div>
                                    <div class="clearfix"></div>
                                </div>
                            </div>
                            <hr>-->
                                    <!--<div class="padding-panel">
                                            <div class="row">
                                                <div class="col-sm-4">
                                                    <label>
                                                        <i></i> @lang('backoffice.Expense_Head')
                                        </label>
                                    </div>
                                    <div class="col-sm-8">
<!-- <p>
                                                    <label class="i-checks">
                                                        <input value="expense_head_list" name="permissions[]"
                                                               type="checkbox" <?php if (in_array("expense_head_list", $name)) {
                                        echo "checked";
                                    } ?>>
                                                        <i></i> @lang('backoffice.list')
                                        </label>
<!--  </p>

                                                     <p>
                                                    <label class="i-checks">
                                                        <input value="expense_head_add" name="permissions[]"
                                                               type="checkbox" <?php if (in_array("expense_head_add", $name)) {
                                        echo "checked";
                                    } ?>>
                                                        <i></i> @lang('backoffice.add')
                                        </label>


                                        <label class="i-checks">
                                            <input value="expense_head_edit" name="permissions[]"
                                                               type="checkbox" <?php if (in_array("expense_head_edit", $name)) {
                                        echo "checked";
                                    } ?>>
                                                        <i></i> @lang('backoffice.edit')
                                        </label>

                                        <label class="i-checks">
                                            <input value="expense_head_delete" name="permissions[]"
                                                               type="checkbox" <?php if (in_array("expense_head_delete", $name)) {
                                        echo "checked";
                                    } ?>>
                                                        <i></i> @lang('backoffice.delete')
                                        </label>
<!-- </p>


                                                </div>
                                                <div class="clearfix"></div>
                                            </div>
                                        </div>
                                        <hr>-->
                                    <!--<div class="padding-panel">
                                            <div class="row">
                                                <div class="col-sm-4">
                                                    <label>
                                                        <i></i> @lang('backoffice.expenseledger')
                                        </label>
                                    </div>
                                    <div class="col-sm-8">

                                        <label class="i-checks">
                                            <input value="expense_ledger_list" name="permissions[]"
                                                               type="checkbox" <?php if (in_array("expense_ledger_list", $name)) {
                                        echo "checked";
                                    } ?>>
                                                        <i></i> @lang('backoffice.list')
                                        </label>

                                        <label class="i-checks">
                                            <input value="expense_ledger_add" name="permissions[]"
                                                               type="checkbox" <?php if (in_array("expense_ledger_add", $name)) {
                                        echo "checked";
                                    } ?>>
                                                        <i></i> @lang('backoffice.add')
                                        </label>

                                        <label class="i-checks">
                                            <input value="expense_ledger_edit" name="permissions[]"
                                                               type="checkbox" <?php if (in_array("expense_ledger_edit", $name)) {
                                        echo "checked";
                                    } ?>>
                                                        <i></i> @lang('backoffice.edit')
                                        </label>

                                        <label class="i-checks">
                                            <input value="expense_ledger_delete" name="permissions[]"
                                                               type="checkbox" <?php if (in_array("expense_ledger_delete", $name)) {
                                        echo "checked";
                                    } ?>>
                                                        <i></i> @lang('backoffice.delete')
                                        </label>
                                    </div>
                                    <div class="clearfix"></div>
                                </div>
                            </div>
                            <hr>-->


                                        <div class="padding-panel">
                                            <div class="row">
                                                <div class="col-sm-4">
                                                    <label>
                                                        <i></i> @lang('backoffice.apps')
                                                    </label>
                                                </div>
                                                <div class="col-sm-8">

                                                    <label class="i-checks">
                                                        <input value="apps_view" name="permissions[]"
                                                               type="checkbox"<?php if (in_array("apps_view", $name)) {
                                                            echo "checked";
                                                        } ?>>
                                                        <i></i> @lang('backoffice.list')
                                                    </label>

                                                </div>
                                                <div class="clearfix"></div>
                                            </div>
                                        </div>
                                        <hr>

                                        <div class="padding-panel">
                                            <div class="row">
                                                <div class="col-sm-4">
                                                    <label>
                                                        <i></i> @lang('backoffice.plugin')
                                                    </label>
                                                </div>
                                                <div class="col-sm-8">

                                                    <label class="i-checks">
                                                        <input value="plugin_view" name="permissions[]"
                                                               type="checkbox"<?php if (in_array("plugin_view", $name)) {
                                                            echo "checked";
                                                        } ?>>
                                                        <i></i> @lang('backoffice.list')
                                                    </label>
                                                </div>
                                                <div class="clearfix"></div>
                                            </div>
                                        </div>
                                        <hr>
                                    </div>
                                    <div class="panel-default">
                                        <button type="submit" class="btn btn-primary" name="create"
                                                value="Create">@lang('backoffice.update')</button>
                                        &nbsp
                                        <a href="{{URL::previous()}}" ng-disabled="button.disabled"
                                           class="btn btn-light">@lang('backoffice.cancel')</a>
                                        <div class="clearfix"></div>
                                    </div>

                                </div>
                            </div>


                        </div>
                    </div>
                </div>
            </div>
        </form>
        @endsection
        @section('scripts')

            <script>

                $("#check_all").change(function () {
                    $("input:checkbox").prop('checked', $(this).prop('checked'));

                });
                $('#CheckPermissions').on('change', function () {
                    if ($(this).is(':checked')) {
                        $('.checkAll').prop('checked', true).trigger('change');
                    } else {
                        $('.checkAll').prop('checked', false).trigger('change');
                    }
                });


                $(function () {
                    $('#role-form').submit(function () {
                        if ($(this)[0].checkValidity()) {
                            $.ajax({
                                url: $(this).attr('action'),
                                type: $(this).attr('method'),
                                data: $(this).serialize(),
                                success: function (response) {
                                    console.log(response.Message);

                                    if (response.IsValid) {
                                      toastr.success(response.Message, 'Success');
                                      setTimeout(() => {
                                        window.location.href = site_url('usermanagement/roles');
                                      }, 3000);
                                    } else {
                                      if (response.Errors.lenght > 0) {
                                        response.Errors.map((error) => {
                                          toastr.error(error, 'Error');
                                        });
                                      } else {
                                        toastr.error(response.Errors[0], 'Error')
                                      }
                                    }

                                },





                            });
                        }
                        return false;
                    });
                });
            </script>
@endsection
