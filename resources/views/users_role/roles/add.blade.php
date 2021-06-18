@extends('layouts.backoffice')
@section('content')
    <!-- content -->

    <div class="row">
        <form method="POST" action="{{ $model->route }}" id="role-form">
            {{csrf_field()}}
            @if($model->edit_mode)
                <input id="id" type="hidden" form="role-form" name="id" value="{{$model->role['id']}}">
            @endif

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
                                            {{ $model->title }}
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
                                                    <input type="text" name="name" class="roletitle form-control "
                                                           placeholder="Add Role" required>
                                                </div>
                                                <div class="col-sm-4 pr-5 pt-2">
{{--                                                    <label class="i-checks pull-right">--}}
{{--                                                        <input type="checkbox" class="pull-right" id="check_all"--}}
{{--                                                               style="margin-left: 10px;">--}}
{{--                                                        <i></i>@lang('backoffice.select_all')--}}
{{--                                                    </label>--}}

                                                    <div class="custom-control custom-checkbox  pull-right">
                                                        <input type="checkbox" class="custom-control-input"
                                                               id="check_all" form="supplier-form"
                                                               name="permissions[]"
                                                               value="roles_edit">
                                                        <label class="custom-control-label text-secondary"
                                                               for="check_all">@lang('backoffice.select_all')</label>
                                                    </div>

                                                </div>
                                                <div class="clearfix"></div>
                                            </div>
                                        </div>
                                        <div class="padding-panel mt-3">
                                            <table class="table table-hover table-boarderd">
                                                <thead>
                                                <td class="col-md-3"><b>Module Name</b></td>
                                                <td class="col-md-2"><b>View</b></td>
                                                <td class="col-md-2"><b>Add</b></td>
                                                <td class="col-md-2"><b>Edit</b></td>
                                                <td class="col-md-2"><b>Delete</b></td>
                                                </thead>
                                                <tbody>
                                                <tr>
                                                    <td> @lang('backoffice.dashboard') </td>
                                                    <td>
                                                        <div class="custom-control custom-checkbox ">
                                                            <input type="checkbox" class="custom-control-input"
                                                                   id="customCheck1" form="supplier-form"
                                                                   name="permissions[]"
                                                                   value="dashboard_dashboard">
                                                            <label class="custom-control-label text-secondary"
                                                                   for="customCheck1"></label>
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
                                                                   id="customCheck2" form="supplier-form"
                                                                   name="permissions[]"
                                                                   value="product_list">
                                                            <label class="custom-control-label text-secondary"
                                                                   for="customCheck2"></label>
                                                        </div>
                                                    </td>
                                                    <td>
                                                        <div class="custom-control custom-checkbox  ">
                                                            <input type="checkbox" class="custom-control-input"
                                                                   id="customCheck3" form="supplier-form"
                                                                   name="permissions[]"
                                                                   value="product_add">
                                                            <label class="custom-control-label text-secondary"
                                                                   for="customCheck3"></label>
                                                        </div>

                                                    </td>
                                                    <td>
                                                        <div class="custom-control custom-checkbox  ">
                                                            <input type="checkbox" class="custom-control-input"
                                                                   id="customCheck4" form="supplier-form"
                                                                   name="permissions[]"
                                                                   value="product_edit">
                                                            <label class="custom-control-label text-secondary"
                                                                   for="customCheck4"></label>
                                                        </div>
                                                    </td>
                                                    <td>
                                                        <div class="custom-control custom-checkbox  ">
                                                            <input type="checkbox" class="custom-control-input"
                                                                   id="customCheck5" form="supplier-form"
                                                                   name="permissions[]"
                                                                   value="product_delete">
                                                            <label class="custom-control-label text-secondary"
                                                                   for="customCheck5"></label>
                                                        </div>

                                                    </td>
                                                </tr>


                                                <tr>
                                                    <td> @lang('backoffice.import_catalogue') </td>
                                                    <td>
                                                        <div class="custom-control custom-checkbox col-sm-2 ">
                                                            <input type="checkbox" class="custom-control-input"
                                                                   id="customCheck6" form="supplier-form"
                                                                   name="permissions[]"
                                                                   value="import_catalogue">
                                                            <label class="custom-control-label text-secondary"
                                                                   for="customCheck6"></label>
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
                                                                   id="customCheck7" form="supplier-form"
                                                                   name="permissions[]"
                                                                   value="product_type_list">
                                                            <label class="custom-control-label text-secondary"
                                                                   for="customCheck7"></label>
                                                        </div>
                                                    </td>
                                                    <td>
                                                        <div class="custom-control custom-checkbox col-sm-2 ">
                                                            <input type="checkbox" class="custom-control-input"
                                                                   id="customCheck8" form="supplier-form"
                                                                   name="permissions[]"
                                                                   value="product_type_add">
                                                            <label class="custom-control-label text-secondary"
                                                                   for="customCheck8"></label>
                                                        </div>

                                                    </td>
                                                    <td>
                                                        <div class="custom-control custom-checkbox col-sm-2 ">
                                                            <input type="checkbox" class="custom-control-input"
                                                                   id="customCheck9" form="supplier-form"
                                                                   name="permissions[]"
                                                                   value="product_type_edit">
                                                            <label class="custom-control-label text-secondary"
                                                                   for="customCheck9"></label>
                                                        </div>
                                                    </td>
                                                    <td>
                                                        <div class="custom-control custom-checkbox col-sm-2 ">
                                                            <input type="checkbox" class="custom-control-input"
                                                                   id="customCheck10" form="supplier-form"
                                                                   name="permissions[]"
                                                                   value="product_type_delete">
                                                            <label class="custom-control-label text-secondary"
                                                                   for="customCheck10"></label>
                                                        </div>

                                                    </td>
                                                </tr>

                                                <tr>
                                                    <td> @lang('backoffice.supplier')  </td>
                                                    <td>
                                                        <div class="custom-control custom-checkbox col-sm-2 ">
                                                            <input type="checkbox" class="custom-control-input"
                                                                   id="customCheck11" form="supplier-form"
                                                                   name="permissions[]"
                                                                   value="supplier_list">
                                                            <label class="custom-control-label text-secondary"
                                                                   for="customCheck11"></label>
                                                        </div>
                                                    </td>
                                                    <td>
                                                        <div class="custom-control custom-checkbox col-sm-2 ">
                                                            <input type="checkbox" class="custom-control-input"
                                                                   id="customCheck12" form="supplier-form"
                                                                   name="permissions[]"
                                                                   value="supplier_add">
                                                            <label class="custom-control-label text-secondary"
                                                                   for="customCheck12"></label>
                                                        </div>

                                                    </td>
                                                    <td>
                                                        <div class="custom-control custom-checkbox col-sm-2 ">
                                                            <input type="checkbox" class="custom-control-input"
                                                                   id="customCheck13" form="supplier-form"
                                                                   name="permissions[]"
                                                                   value="supplier_edit">
                                                            <label class="custom-control-label text-secondary"
                                                                   for="customCheck13"></label>
                                                        </div>
                                                    </td>
                                                    <td>
                                                        <div class="custom-control custom-checkbox col-sm-2 ">
                                                            <input type="checkbox" class="custom-control-input"
                                                                   id="customCheck14" form="supplier-form"
                                                                   name="permissions[]"
                                                                   value="supplier_delete">
                                                            <label class="custom-control-label text-secondary"
                                                                   for="customCheck14"></label>
                                                        </div>

                                                    </td>
                                                </tr>

                                                <tr>
                                                    <td> @lang('backoffice.addons')  </td>
                                                    <td>
                                                        <div class="custom-control custom-checkbox col-sm-2 ">
                                                            <input type="checkbox" class="custom-control-input"
                                                                   id="customCheck15" form="supplier-form"
                                                                   name="permissions[]"
                                                                   value="addons_list">
                                                            <label class="custom-control-label text-secondary"
                                                                   for="customCheck15"></label>
                                                        </div>
                                                    </td>
                                                    <td>
                                                        <div class="custom-control custom-checkbox col-sm-2 ">
                                                            <input type="checkbox" class="custom-control-input"
                                                                   id="customCheck16" form="supplier-form"
                                                                   name="permissions[]"
                                                                   value="addons_add">
                                                            <label class="custom-control-label text-secondary"
                                                                   for="customCheck16"></label>
                                                        </div>

                                                    </td>
                                                    <td>
                                                        <div class="custom-control custom-checkbox col-sm-2 ">
                                                            <input type="checkbox" class="custom-control-input"
                                                                   id="customCheck17" form="supplier-form"
                                                                   name="permissions[]"
                                                                   value="addons_edit">
                                                            <label class="custom-control-label text-secondary"
                                                                   for="customCheck17"></label>
                                                        </div>
                                                    </td>
                                                    <td>
                                                        <div class="custom-control custom-checkbox col-sm-2 ">
                                                            <input type="checkbox" class="custom-control-input"
                                                                   id="customCheck18" form="supplier-form"
                                                                   name="permissions[]"
                                                                   value="addons_delete">
                                                            <label class="custom-control-label text-secondary"
                                                                   for="customCheck18"></label>
                                                        </div>

                                                    </td>
                                                </tr>

                                                <tr>
                                                    <td> @lang('backoffice.addonsitem')  </td>
                                                    <td>
                                                        <div class="custom-control custom-checkbox col-sm-2 ">
                                                            <input type="checkbox" class="custom-control-input"
                                                                   id="customCheck19" form="supplier-form"
                                                                   name="permissions[]"
                                                                   value="addonsitem_list">
                                                            <label class="custom-control-label text-secondary"
                                                                   for="customCheck19"></label>
                                                        </div>
                                                    </td>
                                                    <td>
                                                        <div class="custom-control custom-checkbox col-sm-2 ">
                                                            <input type="checkbox" class="custom-control-input"
                                                                   id="customCheck20" form="supplier-form"
                                                                   name="permissions[]"
                                                                   value="addonsitem_add">
                                                            <label class="custom-control-label text-secondary"
                                                                   for="customCheck20"></label>
                                                        </div>

                                                    </td>
                                                    <td>
                                                        <div class="custom-control custom-checkbox col-sm-2 ">
                                                            <input type="checkbox" class="custom-control-input"
                                                                   id="customCheck21" form="supplier-form"
                                                                   name="permissions[]"
                                                                   value="addonsitem_edit">
                                                            <label class="custom-control-label text-secondary"
                                                                   for="customCheck21"></label>
                                                        </div>
                                                    </td>
                                                    <td>
                                                        <div class="custom-control custom-checkbox col-sm-2 ">
                                                            <input type="checkbox" class="custom-control-input"
                                                                   id="customCheck22" form="supplier-form"
                                                                   name="permissions[]"
                                                                   value="addonsitem_delete">
                                                            <label class="custom-control-label text-secondary"
                                                                   for="customCheck22"></label>
                                                        </div>

                                                    </td>
                                                </tr>

                                                <tr>
                                                    <td> @lang('backoffice.brand')  </td>
                                                    <td>
                                                        <div class="custom-control custom-checkbox col-sm-2 ">
                                                            <input type="checkbox" class="custom-control-input"
                                                                   id="customCheck23" form="supplier-form"
                                                                   name="permissions[]"
                                                                   value="brand_list">
                                                            <label class="custom-control-label text-secondary"
                                                                   for="customCheck23"></label>
                                                        </div>
                                                    </td>
                                                    <td>
                                                        <div class="custom-control custom-checkbox col-sm-2 ">
                                                            <input type="checkbox" class="custom-control-input"
                                                                   id="customCheck24" form="supplier-form"
                                                                   name="permissions[]"
                                                                   value="brand_add">
                                                            <label class="custom-control-label text-secondary"
                                                                   for="customCheck24"></label>
                                                        </div>

                                                    </td>
                                                    <td>
                                                        <div class="custom-control custom-checkbox col-sm-2 ">
                                                            <input type="checkbox" class="custom-control-input"
                                                                   id="customCheck25" form="supplier-form"
                                                                   name="permissions[]"
                                                                   value="brand_edit">
                                                            <label class="custom-control-label text-secondary"
                                                                   for="customCheck25"></label>
                                                        </div>
                                                    </td>
                                                    <td>
                                                        <div class="custom-control custom-checkbox col-sm-2 ">
                                                            <input type="checkbox" class="custom-control-input"
                                                                   id="customCheck26" form="supplier-form"
                                                                   name="permissions[]"
                                                                   value="brand_delete">
                                                            <label class="custom-control-label text-secondary"
                                                                   for="customCheck26"></label>
                                                        </div>

                                                    </td>
                                                </tr>

                                                <tr>
                                                    <td> @lang('backoffice.customer')  </td>
                                                    <td>
                                                        <div class="custom-control custom-checkbox col-sm-2 ">
                                                            <input type="checkbox" class="custom-control-input"
                                                                   id="customCheck27" form="supplier-form"
                                                                   name="permissions[]"
                                                                   value="customer_list">
                                                            <label class="custom-control-label text-secondary"
                                                                   for="customCheck27"></label>
                                                        </div>
                                                    </td>
                                                    <td>
                                                        <div class="custom-control custom-checkbox col-sm-2 ">
                                                            <input type="checkbox" class="custom-control-input"
                                                                   id="customCheck28" form="supplier-form"
                                                                   name="permissions[]"
                                                                   value="customer_add">
                                                            <label class="custom-control-label text-secondary"
                                                                   for="customCheck28"></label>
                                                        </div>

                                                    </td>
                                                    <td>
                                                        <div class="custom-control custom-checkbox col-sm-2 ">
                                                            <input type="checkbox" class="custom-control-input"
                                                                   id="customCheck29" form="supplier-form"
                                                                   name="permissions[]"
                                                                   value="customer_edit">
                                                            <label class="custom-control-label text-secondary"
                                                                   for="customCheck29"></label>
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
                                                                   id="customCheck30" form="supplier-form"
                                                                   name="permissions[]"
                                                                   value="customer_group_list">
                                                            <label class="custom-control-label text-secondary"
                                                                   for="customCheck30"></label>
                                                        </div>
                                                    </td>
                                                    <td>
                                                        <div class="custom-control custom-checkbox col-sm-2 ">
                                                            <input type="checkbox" class="custom-control-input"
                                                                   id="customCheck31" form="supplier-form"
                                                                   name="permissions[]"
                                                                   value="customer_group_add">
                                                            <label class="custom-control-label text-secondary"
                                                                   for="customCheck31"></label>
                                                        </div>

                                                    </td>
                                                    <td>
                                                        <div class="custom-control custom-checkbox col-sm-2 ">
                                                            <input type="checkbox" class="custom-control-input"
                                                                   id="customCheck32" form="supplier-form"
                                                                   name="permissions[]"
                                                                   value="customer_group_edit">
                                                            <label class="custom-control-label text-secondary"
                                                                   for="customCheck32"></label>
                                                        </div>
                                                    </td>
                                                    <td>
                                                        <div class="custom-control custom-checkbox col-sm-2 ">
                                                            <input type="checkbox" class="custom-control-input"
                                                                   id="customCheck33" form="supplier-form"
                                                                   name="permissions[]"
                                                                   value="customer_group_delete">
                                                            <label class="custom-control-label text-secondary"
                                                                   for="customCheck33"></label>
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
                                                                   id="customCheck34" form="supplier-form"
                                                                   name="permissions[]"
                                                                   value="general_setup_edit">
                                                            <label class="custom-control-label text-secondary"
                                                                   for="customCheck34"></label>
                                                        </div>
                                                    </td>
                                                    <td></td>
                                                </tr>

                                                <tr>
                                                    <td> @lang('backoffice.outlets_and_registers')  </td>
                                                    <td>
                                                        <div class="custom-control custom-checkbox col-sm-2 ">
                                                            <input type="checkbox" class="custom-control-input"
                                                                   id="customCheck35" form="supplier-form"
                                                                   name="permissions[]"
                                                                   value="outlet_list">
                                                            <label class="custom-control-label text-secondary"
                                                                   for="customCheck35"></label>
                                                        </div>
                                                    </td>
                                                    <td>
                                                        <div class="custom-control custom-checkbox col-sm-2 ">
                                                            <input type="checkbox" class="custom-control-input"
                                                                   id="customCheck36" form="supplier-form"
                                                                   name="permissions[]"
                                                                   value="outlet_add">
                                                            <label class="custom-control-label text-secondary"
                                                                   for="customCheck36"></label>
                                                        </div>

                                                    </td>
                                                    <td>
                                                        <div class="custom-control custom-checkbox col-sm-2 ">
                                                            <input type="checkbox" class="custom-control-input"
                                                                   id="customCheck37" form="supplier-form"
                                                                   name="permissions[]"
                                                                   value="outlet_edit">
                                                            <label class="custom-control-label text-secondary"
                                                                   for="customCheck37"></label>
                                                        </div>
                                                    </td>
                                                    <td>
                                                        <div class="custom-control custom-checkbox col-sm-2 ">
                                                            <input type="checkbox" class="custom-control-input"
                                                                   id="customCheck38" form="supplier-form"
                                                                   name="permissions[]"
                                                                   value="outlet_delete">
                                                            <label class="custom-control-label text-secondary"
                                                                   for="customCheck38"></label>
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
                                                                   id="customCheck39" form="supplier-form"
                                                                   name="permissions[]"
                                                                   value="register_add">
                                                            <label class="custom-control-label text-secondary"
                                                                   for="customCheck39"></label>
                                                        </div>

                                                    </td>
                                                    <td>
                                                        <div class="custom-control custom-checkbox col-sm-2 ">
                                                            <input type="checkbox" class="custom-control-input"
                                                                   id="customCheck40" form="supplier-form"
                                                                   name="permissions[]"
                                                                   value="register_edit">
                                                            <label class="custom-control-label text-secondary"
                                                                   for="customCheck40"></label>
                                                        </div>
                                                    </td>
                                                    <td>
                                                        <div class="custom-control custom-checkbox col-sm-2 ">
                                                            <input type="checkbox" class="custom-control-input"
                                                                   id="customCheck41" form="supplier-form"
                                                                   name="permissions[]"
                                                                   value="register_delete">
                                                            <label class="custom-control-label text-secondary"
                                                                   for="customCheck41"></label>
                                                        </div>
                                                    </td>
                                                </tr>

                                                <tr>
                                                    <td> @lang('backoffice.user')  </td>
                                                    <td>
                                                        <div class="custom-control custom-checkbox col-sm-2 ">
                                                            <input type="checkbox" class="custom-control-input"
                                                                   id="customCheck42" form="supplier-form"
                                                                   name="permissions[]"
                                                                   value="user_list">
                                                            <label class="custom-control-label text-secondary"
                                                                   for="customCheck42"></label>
                                                        </div>
                                                    </td>
                                                    <td>
                                                        <div class="custom-control custom-checkbox col-sm-2 ">
                                                            <input type="checkbox" class="custom-control-input"
                                                                   id="customCheck43" form="supplier-form"
                                                                   name="permissions[]"
                                                                   value="user_add">
                                                            <label class="custom-control-label text-secondary"
                                                                   for="customCheck43"></label>
                                                        </div>

                                                    </td>
                                                    <td>
                                                        <div class="custom-control custom-checkbox col-sm-2 ">
                                                            <input type="checkbox" class="custom-control-input"
                                                                   id="customCheck44" form="supplier-form"
                                                                   name="permissions[]"
                                                                   value="user_edit">
                                                            <label class="custom-control-label text-secondary"
                                                                   for="customCheck44"></label>
                                                        </div>
                                                    </td>
                                                    <td>
                                                        <div class="custom-control custom-checkbox col-sm-2 ">
                                                            <input type="checkbox" class="custom-control-input"
                                                                   id="customCheck45" form="supplier-form"
                                                                   name="permissions[]"
                                                                   value="user_delete">
                                                            <label class="custom-control-label text-secondary"
                                                                   for="customCheck45"></label>
                                                        </div>
                                                    </td>
                                                </tr>

                                                <tr>
                                                    <td> @lang('backoffice.roles')  </td>
                                                    <td>
                                                        <div class="custom-control custom-checkbox col-sm-2 ">
                                                            <input type="checkbox" class="custom-control-input"
                                                                   id="customCheck46" form="supplier-form"
                                                                   name="permissions[]"
                                                                   value="roles_list">
                                                            <label class="custom-control-label text-secondary"
                                                                   for="customCheck46"></label>
                                                        </div>
                                                    </td>
                                                    <td>
                                                        <div class="custom-control custom-checkbox col-sm-2 ">
                                                            <input type="checkbox" class="custom-control-input"
                                                                   id="customCheck47" form="supplier-form"
                                                                   name="permissions[]"
                                                                   value="roles_add">
                                                            <label class="custom-control-label text-secondary"
                                                                   for="customCheck47"></label>
                                                        </div>

                                                    </td>
                                                    <td>
                                                        <div class="custom-control custom-checkbox col-sm-2 ">
                                                            <input type="checkbox" class="custom-control-input"
                                                                   id="customCheck48" form="supplier-form"
                                                                   name="permissions[]"
                                                                   value="roles_edit">
                                                            <label class="custom-control-label text-secondary"
                                                                   for="customCheck48"></label>
                                                        </div>
                                                    </td>
                                                    <td>
                                                        <div class="custom-control custom-checkbox col-sm-2 ">
                                                            <input type="checkbox" class="custom-control-input"
                                                                   id="customCheck49" form="supplier-form"
                                                                   name="permissions[]"
                                                                   value="roles_delete">
                                                            <label class="custom-control-label text-secondary"
                                                                   for="customCheck49"></label>
                                                        </div>
                                                    </td>
                                                </tr>

                                                <tr>
                                                    <td> @lang('backoffice.apps')  </td>
                                                    <td>
                                                        <div class="custom-control custom-checkbox col-sm-2 ">
                                                            <input type="checkbox" class="custom-control-input"
                                                                   id="customCheck50" form="supplier-form"
                                                                   name="permissions[]"
                                                                   value="apps_view">
                                                            <label class="custom-control-label text-secondary"
                                                                   for="customCheck50"></label>
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
                                                                   id="customCheck51" form="supplier-form"
                                                                   name="permissions[]"
                                                                   value="plugin_view">
                                                            <label class="custom-control-label text-secondary"
                                                                   for="customCheck51"></label>
                                                        </div>
                                                    </td>
                                                    <td></td>
                                                    <td></td>
                                                    <td></td>
                                                </tr>


                                                </tbody>

                                            </table>
                                            <table class="table table-hover table-boarderd">
                                                <thead>
                                                <td>@lang('backoffice.reporting')</td>
                                                <td><b></b></td>
                                                <td><b></b></td>
                                                <td><b></b></td>
                                                </thead>
                                                <tbody>
                                                <div class="row">
                                                    <tr>

                                                        <td>
                                                            <div class="custom-control custom-checkbox  ">
                                                                <input type="checkbox" class="custom-control-input"
                                                                       id="customCheck52" form="supplier-form"
                                                                       name="permissions[]"
                                                                       value="reporting_sales_report">
                                                                <label class="custom-control-label text-secondary"
                                                                       for="customCheck52">@lang('backoffice.sales_reports')</label>
                                                            </div>

                                                        </td>
                                                        <td>
                                                            <div class="custom-control custom-checkbox  ">
                                                                <input type="checkbox" class="custom-control-input"
                                                                       id="customCheck53" form="supplier-form"
                                                                       name="permissions[]"
                                                                       value="sales_export">
                                                                <label class="custom-control-label text-secondary"
                                                                       for="customCheck53">@lang('backoffice.sales_export')</label>
                                                            </div>

                                                        </td>
                                                        <td>
                                                            <div class="custom-control custom-checkbox  ">
                                                                <input type="checkbox" class="custom-control-input"
                                                                       id="customCheck54" form="supplier-form"
                                                                       name="permissions[]"
                                                                       value="reporting_inventory_report">
                                                                <label class="custom-control-label text-secondary"
                                                                       for="customCheck54">@lang('backoffice.inventory_reports')</label>
                                                            </div>
                                                        </td>
                                                        <td>
                                                            <div class="custom-control custom-checkbox  ">
                                                                <input type="checkbox" class="custom-control-input"
                                                                       id="customCheck55" form="supplier-form"
                                                                       name="permissions[]"
                                                                       value="inventory_export">
                                                                <label class="custom-control-label text-secondary"
                                                                       for="customCheck55">@lang('backoffice.inventory_export')</label>
                                                            </div>
                                                        </td>
                                                    </tr>
                                                </div>


                                                <tr>

                                                    <td>
                                                        <div class="custom-control custom-checkbox  ">
                                                            <input type="checkbox" class="custom-control-input"
                                                                   id="customCheck56" form="supplier-form"
                                                                   name="permissions[]"
                                                                   value="reporting_payment_report">
                                                            <label class="custom-control-label text-secondary"
                                                                   for="customCheck56">@lang('backoffice.payment_reports')</label>
                                                        </div>

                                                    </td>
                                                    <td>
                                                        <div class="custom-control custom-checkbox  ">
                                                            <input type="checkbox" class="custom-control-input"
                                                                   id="customCheck57" form="supplier-form"
                                                                   name="permissions[]"
                                                                   value="payment_export">
                                                            <label class="custom-control-label text-secondary"
                                                                   for="customCheck57">@lang('backoffice.payment_export')</label>
                                                        </div>

                                                    </td>
                                                    <td>
                                                        <div class="custom-control custom-checkbox  ">
                                                            <input type="checkbox" class="custom-control-input"
                                                                   id="customCheck58" form="supplier-form"
                                                                   name="permissions[]"
                                                                   value="reporting_register_closure">
                                                            <label class="custom-control-label text-secondary"
                                                                   for="customCheck58">@lang('backoffice.register_losure')</label>
                                                        </div>
                                                    </td>
                                                    <td>
                                                        <div class="custom-control custom-checkbox  ">
                                                            <input type="checkbox" class="custom-control-input"
                                                                   id="customCheck59" form="supplier-form"
                                                                   name="permissions[]"
                                                                   value="register_export">
                                                            <label class="custom-control-label text-secondary"
                                                                   for="customCheck59">@lang('backoffice.register_export')</label>
                                                        </div>
                                                    </td>
                                                </tr>

                                                <tr>

                                                    <td>
                                                        <div class="custom-control custom-checkbox  ">
                                                            <input type="checkbox" class="custom-control-input"
                                                                   id="customCheck60" form="supplier-form"
                                                                   name="permissions[]"
                                                                   value="product_report">
                                                            <label class="custom-control-label text-secondary"
                                                                   for="customCheck60">@lang('backoffice.product_report')</label>
                                                        </div>

                                                    </td>
                                                    <td>
                                                        <div class="custom-control custom-checkbox  ">
                                                            <input type="checkbox" class="custom-control-input"
                                                                   id="customCheck61" form="supplier-form"
                                                                   name="permissions[]"
                                                                   value="product_export">
                                                            <label class="custom-control-label text-secondary"
                                                                   for="customCheck61">@lang('backoffice.product_export')</label>
                                                        </div>

                                                    </td>
                                                    <td>
                                                        <div class="custom-control custom-checkbox  ">
                                                            <input type="checkbox" class="custom-control-input"
                                                                   id="customCheck62" form="supplier-form"
                                                                   name="permissions[]"
                                                                   value="category_report">
                                                            <label class="custom-control-label text-secondary"
                                                                   for="customCheck62">@lang('backoffice.category_report')</label>
                                                        </div>
                                                    </td>
                                                    <td>
                                                        <div class="custom-control custom-checkbox  ">
                                                            <input type="checkbox" class="custom-control-input"
                                                                   id="customCheck63" form="supplier-form"
                                                                   name="permissions[]"
                                                                   value="category_export">
                                                            <label class="custom-control-label text-secondary"
                                                                   for="customCheck63">@lang('backoffice.category_export')</label>
                                                        </div>
                                                    </td>
                                                </tr>


                                                <tr>

                                                    <td>
                                                        <div class="custom-control custom-checkbox  ">
                                                            <input type="checkbox" class="custom-control-input"
                                                                   id="customCheck64" form="supplier-form"
                                                                   name="permissions[]"
                                                                   value="customer_report">
                                                            <label class="custom-control-label text-secondary"
                                                                   for="customCheck64">@lang('backoffice.customer_report')</label>
                                                        </div>

                                                    </td>
                                                    <td>
                                                        <div class="custom-control custom-checkbox  ">
                                                            <input type="checkbox" class="custom-control-input"
                                                                   id="customCheck65" form="supplier-form"
                                                                   name="permissions[]"
                                                                   value="customer_export">
                                                            <label class="custom-control-label text-secondary"
                                                                   for="customCheck65">@lang('backoffice.customer_export')</label>
                                                        </div>

                                                    </td>
                                                    <td>
                                                        <div class="custom-control custom-checkbox  ">
                                                            <input type="checkbox" class="custom-control-input"
                                                                   id="customCheck66" form="supplier-form"
                                                                   name="permissions[]"
                                                                   value="customer_group_report">
                                                            <label class="custom-control-label text-secondary"
                                                                   for="customCheck66">@lang('backoffice.customer_group_report')</label>
                                                        </div>
                                                    </td>
                                                    <td>
                                                        <div class="custom-control custom-checkbox  ">
                                                            <input type="checkbox" class="custom-control-input"
                                                                   id="customCheck67" form="supplier-form"
                                                                   name="permissions[]"
                                                                   value="customer_group_export">
                                                            <label class="custom-control-label text-secondary"
                                                                   for="customCheck67">@lang('backoffice.customer_group_export')</label>
                                                        </div>
                                                    </td>
                                                </tr>


                                                <tr>

                                                    <td>
                                                        <div class="custom-control custom-checkbox  ">
                                                            <input type="checkbox" class="custom-control-input"
                                                                   id="customCheck72" form="supplier-form"
                                                                   name="permissions[]"
                                                                   value="user_report">
                                                            <label class="custom-control-label text-secondary"
                                                                   for="customCheck72">@lang('backoffice.user_report')</label>
                                                        </div>

                                                    </td>
                                                    <td>
                                                        <div class="custom-control custom-checkbox  ">
                                                            <input type="checkbox" class="custom-control-input"
                                                                   id="customCheck73" form="supplier-form"
                                                                   name="permissions[]"
                                                                   value="user_export">
                                                            <label class="custom-control-label text-secondary"
                                                                   for="customCheck73">@lang('backoffice.user_export')</label>
                                                        </div>

                                                    </td>
                                                    <td>
                                                        <div class="custom-control custom-checkbox  ">
                                                            <input type="checkbox" class="custom-control-input"
                                                                   id="customCheck74" form="supplier-form"
                                                                   name="permissions[]"
                                                                   value="addon_report">
                                                            <label class="custom-control-label text-secondary"
                                                                   for="customCheck74">@lang('backoffice.addon_report')</label>
                                                        </div>
                                                    </td>
                                                    <td>
                                                        <div class="custom-control custom-checkbox  ">
                                                            <input type="checkbox" class="custom-control-input"
                                                                   id="customCheck75" form="supplier-form"
                                                                   name="permissions[]"
                                                                   value="addon_export">
                                                            <label class="custom-control-label text-secondary"
                                                                   for="customCheck75">@lang('backoffice.addon_export')</label>
                                                        </div>
                                                    </td>
                                                </tr>

                                                <tr>

                                                    <td>
                                                        <div class="custom-control custom-checkbox  ">
                                                            <input type="checkbox" class="custom-control-input"
                                                                   id="customCheck68" form="supplier-form"
                                                                   name="permissions[]"
                                                                   value="supplier_report">
                                                            <label class="custom-control-label text-secondary"
                                                                   for="customCheck68">@lang('backoffice.supplier_report')</label>
                                                        </div>

                                                    </td>
                                                    <td>
                                                        <div class="custom-control custom-checkbox  ">
                                                            <input type="checkbox" class="custom-control-input"
                                                                   id="customCheck69" form="supplier-form"
                                                                   name="permissions[]"
                                                                   value="supplier_export">
                                                            <label class="custom-control-label text-secondary"
                                                                   for="customCheck69">@lang('backoffice.supplier_export')</label>
                                                        </div>

                                                    </td>
                                                    <td>
                                                        <div class="custom-control custom-checkbox  ">
                                                            <input type="checkbox" class="custom-control-input"
                                                                   id="customCheck70" form="supplier-form"
                                                                   name="permissions[]"
                                                                   value="brand_report">
                                                            <label class="custom-control-label text-secondary"
                                                                   for="customCheck70">@lang('backoffice.brand_report')</label>
                                                        </div>
                                                    </td>
                                                    <td>
                                                        <div class="custom-control custom-checkbox  ">
                                                            <input type="checkbox" class="custom-control-input"
                                                                   id="customCheck71" form="supplier-form"
                                                                   name="permissions[]"
                                                                   value="brand_export">
                                                            <label class="custom-control-label text-secondary"
                                                                   for="customCheck71">@lang('backoffice.brand_export')</label>
                                                        </div>
                                                    </td>
                                                </tr>

                                                </tbody>

                                            </table>
                                            </p>

                                        </div>
                                        <div class="clearfix"></div>
                                    </div>
                                </div>




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
                                        <input value="ecommerce_setup_edit" name="permissions[]" type="checkbox">
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
                                        <input value="product_web_list" name="permissions[]" type="checkbox">
                                        <i></i> @lang('backoffice.list')
                                    </label>
                                    <label class="i-checks">
                                        <input value="product_web_add" name="permissions[]" type="checkbox">
                                        <i></i> @lang('backoffice.add')
                                    </label>
                                    <label class="i-checks">
                                        <input value="product_web_edit" name="permissions[]" type="checkbox">
                                        <i></i> @lang('backoffice.edit')
                                    </label>
                                <!--label class="i-checks">
                                                <input value="product_web_view" name="permissions[]" type="checkbox">
                                                <i></i> @lang('backoffice.view')
                                    </label> -->
                                    <label class="i-checks">
                                        <input value="product_web_delete" name="permissions[]" type="checkbox">
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
                                        <input value="shipping_web_list" name="permissions[]" type="checkbox">
                                        <i></i> @lang('backoffice.list')
                                    </label>
                                    <label class="i-checks">
                                        <input value="shipping_web_add" name="permissions[]" type="checkbox">
                                        <i></i> @lang('backoffice.add')
                                    </label>
                                    <label class="i-checks">
                                        <input value="shipping_web_edit" name="permissions[]" type="checkbox">
                                        <i></i> @lang('backoffice.edit')
                                    </label>
                                <!-- <label class="i-checks">
                                                <input value="shipping_web_view" name="permissions[]" type="checkbox">
                                                <i></i> @lang('backoffice.view')
                                    </label> -->
                                    <label class="i-checks">
                                        <input value="shipping_web_delete" name="permissions[]" type="checkbox">
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
                                        <input value="payment_web_list" name="permissions[]" type="checkbox">
                                        <i></i> @lang('backoffice.list')
                                    </label>
                                    <label class="i-checks">
                                        <input value="payment_web_add" name="permissions[]" type="checkbox">
                                        <i></i> @lang('backoffice.add')
                                    </label>
                                    <label class="i-checks">
                                        <input value="payment_web_edit" name="permissions[]" type="checkbox">
                                        <i></i> @lang('backoffice.edit')
                                    </label>
                                <!-- <label class="i-checks">
                                                <input value="payment_web_view" name="permissions[]" type="checkbox">
                                                <i></i> @lang('backoffice.view')
                                    </label> -->
                                    <label class="i-checks">
                                        <input value="payment_web_delete" name="permissions[]" type="checkbox">
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
                                        <input value="order_web_management_list" name="permissions[]" type="checkbox">
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
                                        <input value="web_customer_list" name="permissions[]" type="checkbox">
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
                                        <input value="web_subscribe_user_list" name="permissions[]" type="checkbox">
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
                                        <input value="banner_web_list" name="permissions[]" type="checkbox">
                                        <i></i> @lang('backoffice.list')
                                    </label>
                                    <label class="i-checks">
                                        <input value="banner_web_add" name="permissions[]" type="checkbox">
                                        <i></i> @lang('backoffice.add')
                                    </label>
                                    <label class="i-checks">
                                        <input value="banner_web_edit" name="permissions[]" type="checkbox">
                                        <i></i> @lang('backoffice.edit')
                                    </label>
                                <!-- <label class="i-checks">
                                                <input value="banner_web_view" name="permissions[]" type="checkbox" >
                                                <i></i> @lang('backoffice.view')
                                    </label> -->
                                    <label class="i-checks">
                                        <input value="banner_web_delete" name="permissions[]" type="checkbox">
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
                                        <input value="pages_web_list" name="permissions[]" type="checkbox">
                                        <i></i> @lang('backoffice.list')
                                    </label>
                                    <label class="i-checks">
                                        <input value="pages_web_add" name="permissions[]" type="checkbox">
                                        <i></i> @lang('backoffice.add')
                                    </label>
                                    <label class="i-checks">
                                        <input value="pages_web_edit" name="permissions[]" type="checkbox">
                                        <i></i> @lang('backoffice.edit')
                                    </label>
                                    <label class="i-checks">
                                        <input value="pages_web_view" name="permissions[]" type="checkbox">
                                        <i></i>@lang('backoffice.change_status')
                                    </label>
                                    <label class="i-checks">
                                        <input value="pages_web_delete" name="permissions[]" type="checkbox">
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
                                        <input value="web_settings_list" name="permissions[]" type="checkbox">
                                        <i></i> @lang('backoffice.list')
                                    </label>
                                </p>

                            </div>
                            <div class="clearfix"></div>
                        </div>
                    </div>
                    <hr>

                    {{-- <div class="padding-panel">--}}
                    {{-- <div class="row">--}}
                    {{-- <div class="col-sm-4">--}}
                    {{-- <label>--}}
                    {{-- <i></i> @lang('site.Invoices')--}}
                    {{-- </label>--}}
                    {{-- </div>--}}
                    {{-- <div class="col-sm-8">--}}
                    {{-- <p>--}}
                    {{-- <label class="i-checks">--}}

                    {{-- <input value="invoices" name="permissions[]"--}}
                    {{-- type="checkbox">--}}
                    {{-- <i></i> @lang('site.list')--}}
                    {{-- </label>--}}

                    {{-- <label class="i-checks">--}}
                    {{-- <input value="invoice_create" name="permissions[]"--}}
                    {{-- type="checkbox">--}}
                    {{-- <i></i>@lang('site.add')--}}
                    {{-- </label>--}}

                    {{-- <label class="i-checks">--}}
                    {{-- <input value="invoice_view" name="permissions[]"--}}
                    {{-- type="checkbox">--}}
                    {{-- <i></i>@lang('site.view')--}}
                    {{-- </label>--}}

                    {{-- <label class="i-checks">--}}
                    {{-- <input value="invoice_send" name="permissions[]"--}}
                    {{-- type="checkbox">--}}
                    {{-- <i></i>@lang('site.send')--}}
                    {{-- </label>--}}
                    {{-- </p>--}}

                    {{-- </div>--}}
                    {{-- <div class="clearfix"></div>--}}
                    {{-- </div>--}}
                    {{-- </div>--}}
                    {{-- <hr>--}}
                    {{-- <div class="padding-panel">--}}
                    {{-- <div class="row">--}}
                    {{-- <div class="col-sm-4">--}}
                    {{-- <label>--}}
                    {{-- <i></i> @lang('site.accounts')--}}
                    {{-- </label>--}}
                    {{-- </div>--}}
                    {{-- <div class="col-sm-8">--}}
                    {{-- <p>--}}
                    {{-- <label class="i-checks">--}}
                    {{-- <input value="accounts_summary" name="permissions[]"--}}
                    {{-- type="checkbox">--}}
                    {{-- <i></i> @lang('site.summary')--}}
                    {{-- </label>--}}
                    {{-- </p>--}}
                    {{-- </div>--}}
                    {{-- <div class="clearfix"></div>--}}
                    {{-- </div>--}}
                    {{-- </div>--}}
                    {{-- <hr>--}}
                    {{-- <div class="padding-panel">--}}
                    {{-- <div class="row">--}}
                    {{-- <div class="col-sm-4">--}}
                    {{-- <label>--}}
                    {{-- <i></i>@lang('site.Income_Head')--}}
                    {{-- </label>--}}
                    {{-- </div>--}}
                    {{-- <div class="col-sm-8">--}}
                    {{-- <!-- <p> -->--}}
                    {{-- <label class="i-checks">--}}
                    {{-- <input value="income_head_list" name="permissions[]"--}}
                    {{-- type="checkbox">--}}
                    {{-- <i></i>@lang('site.list')--}}
                    {{-- </label>--}}


                    {{-- <label class="i-checks">--}}
                    {{-- <input value="income_head_add" name="permissions[]"--}}
                    {{-- type="checkbox">--}}
                    {{-- <i></i> @lang('site.add')--}}
                    {{-- </label>--}}
                    {{-- <!--  </p>--}}

                    {{-- <p> -->--}}
                    {{-- <label class="i-checks">--}}
                    {{-- <input value="income_head_edit" name="permissions[]"--}}
                    {{-- type="checkbox">--}}
                    {{-- <i></i> @lang('site.edit')--}}
                    {{-- </label>--}}

                    {{-- <!-- </p> -->--}}
                    {{-- <!-- <p> -->--}}
                    {{-- <label class="i-checks">--}}
                    {{-- <input value="income_head_delete" name="permissions[]"--}}
                    {{-- type="checkbox">--}}
                    {{-- <i></i>@lang('site.delete')--}}
                    {{-- </label>--}}
                    {{-- <!-- </p> -->--}}
                    {{-- </div>--}}
                    {{-- <div class="clearfix"></div>--}}
                    {{-- </div>--}}
                    {{-- </div>--}}
                    {{-- <hr>--}}
                    {{-- <div class="padding-panel">--}}
                    {{-- <div class="row">--}}
                    {{-- <div class="col-sm-4">--}}
                    {{-- <label>--}}
                    {{-- <i></i>@lang('site.incomeledger')--}}
                    {{-- </label>--}}
                    {{-- </div>--}}
                    {{-- <div class="col-sm-8">--}}
                    {{-- <!-- <p> -->--}}
                    {{-- <label class="i-checks">--}}
                    {{-- <input value="income_ledger_list" name="permissions[]"--}}
                    {{-- type="checkbox">--}}
                    {{-- <i></i> @lang('site.list')--}}
                    {{-- </label>--}}


                    {{-- <label class="i-checks">--}}
                    {{-- <input value="income_ledger_add" name="permissions[]"--}}
                    {{-- type="checkbox">--}}
                    {{-- <i></i>@lang('site.add')--}}
                    {{-- </label>--}}

                    {{-- <label class="i-checks">--}}
                    {{-- <input value="income_ledger_edit" name="permissions[]"--}}
                    {{-- type="checkbox">--}}
                    {{-- <i></i>@lang('site.edit')--}}
                    {{-- </label>--}}


                    {{-- <label class="i-checks">--}}
                    {{-- <input value="income_ledger_delete" name="permissions[]"--}}
                    {{-- type="checkbox">--}}
                    {{-- <i></i> @lang('site.delete')--}}
                    {{-- </label>--}}
                    {{-- <!-- </p> -->--}}
                    {{-- </div>--}}
                    {{-- <div class="clearfix"></div>--}}
                    {{-- </div>--}}
                    {{-- </div>--}}
                    {{-- <hr>--}}
                    {{-- <div class="padding-panel">--}}
                    {{-- <div class="row">--}}
                    {{-- <div class="col-sm-4">--}}
                    {{-- <label>--}}
                    {{-- <i></i> @lang('site.Expense_Head')--}}
                    {{-- </label>--}}
                    {{-- </div>--}}
                    {{-- <div class="col-sm-8">--}}
                    {{-- <!-- <p> -->--}}
                    {{-- <label class="i-checks">--}}
                    {{-- <input value="expense_head_list" name="permissions[]"--}}
                    {{-- type="checkbox">--}}
                    {{-- <i></i> @lang('site.list')--}}
                    {{-- </label>--}}
                    {{-- <!--  </p>--}}

                    {{-- <p> -->--}}
                    {{-- <label class="i-checks">--}}
                    {{-- <input value="expense_head_add" name="permissions[]"--}}
                    {{-- type="checkbox">--}}
                    {{-- <i></i> @lang('site.add')--}}
                    {{-- </label>--}}


                    {{-- <label class="i-checks">--}}
                    {{-- <input value="expense_head_edit" name="permissions[]"--}}
                    {{-- type="checkbox">--}}
                    {{-- <i></i> @lang('site.edit')--}}
                    {{-- </label>--}}

                    {{-- <label class="i-checks">--}}
                    {{-- <input value="expense_head_delete" name="permissions[]"--}}
                    {{-- type="checkbox">--}}
                    {{-- <i></i> @lang('site.delete')--}}
                    {{-- </label>--}}
                    {{-- <!-- </p> -->--}}


                    {{-- </div>--}}
                    {{-- <div class="clearfix"></div>--}}
                    {{-- </div>--}}
                    {{-- </div>--}}
                    {{-- <hr>--}}
                    {{-- <div class="padding-panel">--}}
                    {{-- <div class="row">--}}
                    {{-- <div class="col-sm-4">--}}
                    {{-- <label>--}}
                    {{-- <i></i> @lang('site.expenseledger')--}}
                    {{-- </label>--}}
                    {{-- </div>--}}
                    {{-- <div class="col-sm-8">--}}

                    {{-- <label class="i-checks">--}}
                    {{-- <input value="expense_ledger_list" name="permissions[]"--}}
                    {{-- type="checkbox">--}}
                    {{-- <i></i> @lang('site.list')--}}
                    {{-- </label>--}}

                    {{-- <label class="i-checks">--}}
                    {{-- <input value="expense_ledger_add" name="permissions[]"--}}
                    {{-- type="checkbox">--}}
                    {{-- <i></i> @lang('site.add')--}}
                    {{-- </label>--}}

                    {{-- <label class="i-checks">--}}
                    {{-- <input value="expense_ledger_edit" name="permissions[]"--}}
                    {{-- type="checkbox">--}}
                    {{-- <i></i> @lang('site.edit')--}}
                    {{-- </label>--}}

                    {{-- <label class="i-checks">--}}
                    {{-- <input value="expense_ledger_delete" name="permissions[]"--}}
                    {{-- type="checkbox">--}}
                    {{-- <i></i> @lang('site.delete')--}}
                    {{-- </label>--}}
                    {{-- </div>--}}
                    {{-- <div class="clearfix"></div>--}}
                    {{-- </div>--}}
                    {{-- </div>--}}
                    {{-- <hr>--}}





                </div>
                <div class="panel-default">
                    <button type="submit" class="btn btn-primary" name="create"
                            value="Create">@lang('backoffice.submit')</button>
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
                            if (response.IsValid) {
                                toastr.success(response.Message, 'Success');
                                setTimeout(() => {
                                    window.location.href = site_url('roles');
                                }, 1000);
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
