@extends('layouts.backoffice')
@section('content')
    <!-- content -->

    {{--    @dd($model->role['role']->permissions)--}}
    @if($model->edit_mode)
        <?php
        $name = array();

        ?>

        @foreach($model->role['role']->permissions as $row)
            <?php
            $name[] = $row->name;
            ?>
        @endforeach
    @endif
    <div class="row">
        <form method="POST" action="{{ $model->route }}" id="role-form">
            {{csrf_field()}}
            @if($model->edit_mode)
                <input id="id" type="hidden" form="role-form" name="id" value="{{$model->role['role']->id}}">
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
                                                           placeholder="Add Role" required
                                                           value="{{ isset($model->role['role']) ? $model->role['role']->name : null}}">
                                                </div>
                                                <div class="col-sm-4 pr-5 pt-2">
                                                    {{--                                                    <label class="i-checks pull-right">--}}
                                                    {{--                                                        <input type="checkbox" class="pull-right" id="check_all"--}}
                                                    {{--                                                               style="margin-left: 10px;">--}}
                                                    {{--                                                        <i></i>@lang('backoffice.select_all')--}}
                                                    {{--                                                    </label>--}}

                                                    <div class="custom-control custom-checkbox  pull-right">
                                                        <input type="checkbox" class="custom-control-input"
                                                               id="check_all" form="role-form"
                                                               name="chekkall"
                                                               value="0">
                                                        <label class="custom-control-label text-secondary"
                                                               for="check_all">@lang('backoffice.select_all')</label>
                                                    </div>

                                                </div>
                                                <div class="clearfix"></div>
                                            </div>
                                        </div>
                                        <div class="padding-panel mt-3">

                                            <table class="table table-hover table-boarderd">

                                                <tbody>

                                                @foreach($permissions as $key => $permission)
                                                    @if($key != 'Report' && $key != 'Export')
                                                        <tr>
                                                            <td>{{$key}}</td>
                                                            @foreach($permission->sortBy('name') as $per )
                                                                <td>
                                                                    <div class="custom-control custom-checkbox ">
                                                                        <input type="checkbox"
                                                                               class="custom-control-input"
                                                                               id="{{$per->id}}" form="role-form"
                                                                               name="permissions[]"
                                                                               value="{{$per->id}}"
                                                                               @if($model->edit_mode)
                                                                               @if(in_array($per->name,$name))
                                                                               checked
                                                                            @endif
                                                                            @endif
                                                                        >
                                                                        <label
                                                                            class="custom-control-label text-secondary"
                                                                            for="{{$per->id}}">{{$per->display_name}}</label>
                                                                    </div>
                                                                </td>
                                                            @endforeach
                                                        </tr>
                                                    @endif
                                                @endforeach


                                                </tbody>
                                            </table>

                                            <br>
                                            <div class="row">
                                            @foreach($reports as $key => $report)


                                                <div class="col-md-3 inline">
                                                    <p>{{$key}}</p>
                                                    @foreach($report as $index =>$reports)

                                                            <div class="custom-control custom-checkbox  pb-2">
                                                                <input type="checkbox" class="custom-control-input"
                                                                       id="{{$reports->id}}" form="role-form"
                                                                       name="permissions[]"
                                                                       value="{{$reports->id}}"
                                                                       @if($model->edit_mode)
                                                                       @if(in_array($reports->name,$name))
                                                                       checked
                                                                    @endif
                                                                    @endif>

                                                                <label class="custom-control-label text-secondary"
                                                                       for="{{$reports->id}}">{{$reports->display_name}}</label>
                                                            </div>


                                                    @endforeach
                                                </div>

                                            @endforeach
                                            </div>




                                            {{--                                            <table class="table table-hover table-boarderd">--}}
                                            {{--                                                <thead>--}}
                                            {{--                                                <td class="col-md-3"><b></b></td>--}}
                                            {{--                                                <td class="col-md-2"><b></b></td>--}}
                                            {{--                                                <td class="col-md-2"><b></b></td>--}}
                                            {{--                                                <td class="col-md-2"><b></b></td>--}}
                                            {{--                                                <td class="col-md-2"><b></b></td>--}}
                                            {{--                                                </thead>--}}
                                            {{--                                                <tbody>--}}


                                            {{--                                                @foreach($reports as $key => $report)--}}
                                            {{--                                                    <tr>--}}
                                            {{--                                                        <td>{{$key}}</td>--}}

                                            {{--                                                        @foreach($report as $index =>$reports)--}}
                                            {{--                                                            <td>--}}
                                            {{--                                                                <div class="custom-control custom-checkbox ">--}}
                                            {{--                                                                    <input type="checkbox" class="custom-control-input"--}}
                                            {{--                                                                           id="{{$reports->id}}" form="role-form"--}}
                                            {{--                                                                           name="permissions[]"--}}
                                            {{--                                                                           value="{{$reports->slug}}">--}}

                                            {{--                                                                    <label class="custom-control-label text-secondary"--}}
                                            {{--                                                                           for="{{$reports->id}}">{{$reports->display_name}}</label>--}}
                                            {{--                                                                </div>--}}
                                            {{--                                                                <br>--}}
                                            {{--                                                            </td>--}}

                                            {{--                                                            @if($index==3)--}}
                                            {{--                                                              @break--}}
                                            {{--                                                            @endif--}}
                                            {{--                                                        @endforeach--}}
                                            {{--                                                    </tr>--}}
                                            {{--                                                @endforeach--}}
                                            {{--                                                </tbody>--}}
                                            {{--                                            </table>--}}


                                            <div class="panel-default mt-4">
                                                <button type="submit" class="btn btn-primary" name="create"
                                                        value="Create">@lang('backoffice.submit')</button>
                                                &nbsp
                                                <a href="{{URL::previous()}}" ng-disabled="button.disabled"
                                                   class="btn btn-light">@lang('backoffice.cancel')</a>
                                                <div class="clearfix"></div>
                                            </div>


                                            {{--                                            <table class="table table-hover table-boarderd">--}}
                                            {{--                                                <thead>--}}
                                            {{--                                                <td class="col-md-3"><b>Module Name</b></td>--}}
                                            {{--                                                <td class="col-md-2"><b></b></td>--}}
                                            {{--                                                <td class="col-md-2"><b></b></td>--}}
                                            {{--                                                <td class="col-md-2"><b></b></td>--}}
                                            {{--                                                <td class="col-md-2"><b></b></td>--}}
                                            {{--                                                </thead>--}}
                                            {{--                                                <tbody>--}}
                                            {{--                                                <tr>--}}
                                            {{--                                                    <td> @lang('backoffice.dashboard') </td>--}}
                                            {{--                                                    <td>--}}
                                            {{--                                                        <div class="custom-control custom-checkbox ">--}}
                                            {{--                                                            <input type="checkbox" class="custom-control-input"--}}
                                            {{--                                                                   id="customCheck1" form="role-form"--}}
                                            {{--                                                                   name="permissions[]"--}}
                                            {{--                                                                   value="dashboard_dashboard">--}}
                                            {{--                                                            <label class="custom-control-label text-secondary"--}}
                                            {{--                                                                   for="customCheck1">@lang('backoffice.list')</label>--}}
                                            {{--                                                        </div>--}}
                                            {{--                                                    </td>--}}
                                            {{--                                                    <td></td>--}}
                                            {{--                                                    <td></td>--}}
                                            {{--                                                    <td></td>--}}
                                            {{--                                                </tr>--}}
                                            {{--                                                <tr>--}}
                                            {{--                                                    <td> @lang('backoffice.products') </td>--}}
                                            {{--                                                    <td>--}}
                                            {{--                                                        <div class="custom-control custom-checkbox  ">--}}
                                            {{--                                                            <input type="checkbox" class="custom-control-input"--}}
                                            {{--                                                                   id="customCheck2" form="role-form"--}}
                                            {{--                                                                   name="permissions[]"--}}
                                            {{--                                                                   value="product_list">--}}
                                            {{--                                                            <label class="custom-control-label text-secondary"--}}
                                            {{--                                                                   for="customCheck2">@lang('backoffice.list')</label>--}}
                                            {{--                                                        </div>--}}
                                            {{--                                                    </td>--}}
                                            {{--                                                    <td>--}}
                                            {{--                                                        <div class="custom-control custom-checkbox  ">--}}
                                            {{--                                                            <input type="checkbox" class="custom-control-input"--}}
                                            {{--                                                                   id="customCheck3" form="role-form"--}}
                                            {{--                                                                   name="permissions[]"--}}
                                            {{--                                                                   value="product_add">--}}
                                            {{--                                                            <label class="custom-control-label text-secondary"--}}
                                            {{--                                                                   for="customCheck3">@lang('backoffice.add')</label>--}}
                                            {{--                                                        </div>--}}

                                            {{--                                                    </td>--}}
                                            {{--                                                    <td>--}}
                                            {{--                                                        <div class="custom-control custom-checkbox  ">--}}
                                            {{--                                                            <input type="checkbox" class="custom-control-input"--}}
                                            {{--                                                                   id="customCheck4" form="role-form"--}}
                                            {{--                                                                   name="permissions[]"--}}
                                            {{--                                                                   value="product_edit">--}}
                                            {{--                                                            <label class="custom-control-label text-secondary"--}}
                                            {{--                                                                   for="customCheck4">@lang('backoffice.edit')</label>--}}
                                            {{--                                                        </div>--}}
                                            {{--                                                    </td>--}}
                                            {{--                                                    <td>--}}
                                            {{--                                                        <div class="custom-control custom-checkbox  ">--}}
                                            {{--                                                            <input type="checkbox" class="custom-control-input"--}}
                                            {{--                                                                   id="customCheck5" form="role-form"--}}
                                            {{--                                                                   name="permissions[]"--}}
                                            {{--                                                                   value="product_delete">--}}
                                            {{--                                                            <label class="custom-control-label text-secondary"--}}
                                            {{--                                                                   for="customCheck5">@lang('backoffice.delete')</label>--}}
                                            {{--                                                        </div>--}}

                                            {{--                                                    </td>--}}
                                            {{--                                                </tr>--}}
                                            {{--                                                <tr>--}}
                                            {{--                                                    <td> @lang('backoffice.import_catalogue') </td>--}}
                                            {{--                                                    <td>--}}
                                            {{--                                                        <div class="custom-control custom-checkbox col-sm-2 ">--}}
                                            {{--                                                            <input type="checkbox" class="custom-control-input"--}}
                                            {{--                                                                   id="customCheck6" form="role-form"--}}
                                            {{--                                                                   name="permissions[]"--}}
                                            {{--                                                                   value="import_catalogue">--}}
                                            {{--                                                            <label class="custom-control-label text-secondary"--}}
                                            {{--                                                                   for="customCheck6">@lang('backoffice.list')</label>--}}
                                            {{--                                                        </div>--}}
                                            {{--                                                    </td>--}}
                                            {{--                                                    <td>--}}


                                            {{--                                                    </td>--}}
                                            {{--                                                    <td>--}}

                                            {{--                                                    </td>--}}
                                            {{--                                                    <td>--}}


                                            {{--                                                    </td>--}}
                                            {{--                                                </tr>--}}
                                            {{--                                                <tr>--}}
                                            {{--                                                    <td> @lang('backoffice.category')  </td>--}}
                                            {{--                                                    <td>--}}
                                            {{--                                                        <div class="custom-control custom-checkbox col-sm-2 ">--}}
                                            {{--                                                            <input type="checkbox" class="custom-control-input"--}}
                                            {{--                                                                   id="customCheck7" form="role-form"--}}
                                            {{--                                                                   name="permissions[]"--}}
                                            {{--                                                                   value="product_type_list">--}}
                                            {{--                                                            <label class="custom-control-label text-secondary"--}}
                                            {{--                                                                   for="customCheck7">@lang('backoffice.list')</label>--}}
                                            {{--                                                        </div>--}}
                                            {{--                                                    </td>--}}
                                            {{--                                                    <td>--}}
                                            {{--                                                        <div class="custom-control custom-checkbox col-sm-2 ">--}}
                                            {{--                                                            <input type="checkbox" class="custom-control-input"--}}
                                            {{--                                                                   id="customCheck8" form="role-form"--}}
                                            {{--                                                                   name="permissions[]"--}}
                                            {{--                                                                   value="product_type_add">--}}
                                            {{--                                                            <label class="custom-control-label text-secondary"--}}
                                            {{--                                                                   for="customCheck8">@lang('backoffice.add')</label>--}}
                                            {{--                                                        </div>--}}

                                            {{--                                                    </td>--}}
                                            {{--                                                    <td>--}}
                                            {{--                                                        <div class="custom-control custom-checkbox col-sm-2 ">--}}
                                            {{--                                                            <input type="checkbox" class="custom-control-input"--}}
                                            {{--                                                                   id="customCheck9" form="role-form"--}}
                                            {{--                                                                   name="permissions[]"--}}
                                            {{--                                                                   value="product_type_edit">--}}
                                            {{--                                                            <label class="custom-control-label text-secondary"--}}
                                            {{--                                                                   for="customCheck9">@lang('backoffice.edit')</label>--}}
                                            {{--                                                        </div>--}}
                                            {{--                                                    </td>--}}
                                            {{--                                                    <td>--}}
                                            {{--                                                        <div class="custom-control custom-checkbox col-sm-2 ">--}}
                                            {{--                                                            <input type="checkbox" class="custom-control-input"--}}
                                            {{--                                                                   id="customCheck10" form="role-form"--}}
                                            {{--                                                                   name="permissions[]"--}}
                                            {{--                                                                   value="product_type_delete">--}}
                                            {{--                                                            <label class="custom-control-label text-secondary"--}}
                                            {{--                                                                   for="customCheck10">@lang('backoffice.delete')</label>--}}
                                            {{--                                                        </div>--}}

                                            {{--                                                    </td>--}}
                                            {{--                                                </tr>--}}
                                            {{--                                                <tr>--}}
                                            {{--                                                    <td> @lang('backoffice.supplier')  </td>--}}
                                            {{--                                                    <td>--}}
                                            {{--                                                        <div class="custom-control custom-checkbox col-sm-2 ">--}}
                                            {{--                                                            <input type="checkbox" class="custom-control-input"--}}
                                            {{--                                                                   id="customCheck11" form="role-form"--}}
                                            {{--                                                                   name="permissions[]"--}}
                                            {{--                                                                   value="supplier_list">--}}
                                            {{--                                                            <label class="custom-control-label text-secondary"--}}
                                            {{--                                                                   for="customCheck11">@lang('backoffice.list')</label>--}}
                                            {{--                                                        </div>--}}
                                            {{--                                                    </td>--}}
                                            {{--                                                    <td>--}}
                                            {{--                                                        <div class="custom-control custom-checkbox col-sm-2 ">--}}
                                            {{--                                                            <input type="checkbox" class="custom-control-input"--}}
                                            {{--                                                                   id="customCheck12" form="role-form"--}}
                                            {{--                                                                   name="permissions[]"--}}
                                            {{--                                                                   value="supplier_add">--}}
                                            {{--                                                            <label class="custom-control-label text-secondary"--}}
                                            {{--                                                                   for="customCheck12">@lang('backoffice.add')</label>--}}
                                            {{--                                                        </div>--}}

                                            {{--                                                    </td>--}}
                                            {{--                                                    <td>--}}
                                            {{--                                                        <div class="custom-control custom-checkbox col-sm-2 ">--}}
                                            {{--                                                            <input type="checkbox" class="custom-control-input"--}}
                                            {{--                                                                   id="customCheck13" form="role-form"--}}
                                            {{--                                                                   name="permissions[]"--}}
                                            {{--                                                                   value="supplier_edit">--}}
                                            {{--                                                            <label class="custom-control-label text-secondary"--}}
                                            {{--                                                                   for="customCheck13">@lang('backoffice.edit')</label>--}}
                                            {{--                                                        </div>--}}
                                            {{--                                                    </td>--}}
                                            {{--                                                    <td>--}}
                                            {{--                                                        <div class="custom-control custom-checkbox col-sm-2 ">--}}
                                            {{--                                                            <input type="checkbox" class="custom-control-input"--}}
                                            {{--                                                                   id="customCheck14" form="role-form"--}}
                                            {{--                                                                   name="permissions[]"--}}
                                            {{--                                                                   value="supplier_delete">--}}
                                            {{--                                                            <label class="custom-control-label text-secondary"--}}
                                            {{--                                                                   for="customCheck14">@lang('backoffice.delete')</label>--}}
                                            {{--                                                        </div>--}}

                                            {{--                                                    </td>--}}
                                            {{--                                                </tr>--}}
                                            {{--                                                <tr>--}}
                                            {{--                                                    <td> @lang('backoffice.addons')  </td>--}}
                                            {{--                                                    <td>--}}
                                            {{--                                                        <div class="custom-control custom-checkbox col-sm-2 ">--}}
                                            {{--                                                            <input type="checkbox" class="custom-control-input"--}}
                                            {{--                                                                   id="customCheck15" form="role-form"--}}
                                            {{--                                                                   name="permissions[]"--}}
                                            {{--                                                                   value="addons_list">--}}
                                            {{--                                                            <label class="custom-control-label text-secondary"--}}
                                            {{--                                                                   for="customCheck15">@lang('backoffice.list')</label>--}}
                                            {{--                                                        </div>--}}
                                            {{--                                                    </td>--}}
                                            {{--                                                    <td>--}}
                                            {{--                                                        <div class="custom-control custom-checkbox col-sm-2 ">--}}
                                            {{--                                                            <input type="checkbox" class="custom-control-input"--}}
                                            {{--                                                                   id="customCheck16" form="role-form"--}}
                                            {{--                                                                   name="permissions[]"--}}
                                            {{--                                                                   value="addons_add">--}}
                                            {{--                                                            <label class="custom-control-label text-secondary"--}}
                                            {{--                                                                   for="customCheck16">@lang('backoffice.add')</label>--}}
                                            {{--                                                        </div>--}}

                                            {{--                                                    </td>--}}
                                            {{--                                                    <td>--}}
                                            {{--                                                        <div class="custom-control custom-checkbox col-sm-2 ">--}}
                                            {{--                                                            <input type="checkbox" class="custom-control-input"--}}
                                            {{--                                                                   id="customCheck17" form="role-form"--}}
                                            {{--                                                                   name="permissions[]"--}}
                                            {{--                                                                   value="addons_edit">--}}
                                            {{--                                                            <label class="custom-control-label text-secondary"--}}
                                            {{--                                                                   for="customCheck17">@lang('backoffice.edit')</label>--}}
                                            {{--                                                        </div>--}}
                                            {{--                                                    </td>--}}
                                            {{--                                                    <td>--}}
                                            {{--                                                        <div class="custom-control custom-checkbox col-sm-2 ">--}}
                                            {{--                                                            <input type="checkbox" class="custom-control-input"--}}
                                            {{--                                                                   id="customCheck18" form="role-form"--}}
                                            {{--                                                                   name="permissions[]"--}}
                                            {{--                                                                   value="addons_delete">--}}
                                            {{--                                                            <label class="custom-control-label text-secondary"--}}
                                            {{--                                                                   for="customCheck18">@lang('backoffice.delete')</label>--}}
                                            {{--                                                        </div>--}}

                                            {{--                                                    </td>--}}
                                            {{--                                                </tr>--}}
                                            {{--                                                <tr>--}}
                                            {{--                                                    <td> @lang('backoffice.addonsitem')  </td>--}}
                                            {{--                                                    <td>--}}
                                            {{--                                                        <div class="custom-control custom-checkbox col-sm-2 ">--}}
                                            {{--                                                            <input type="checkbox" class="custom-control-input"--}}
                                            {{--                                                                   id="customCheck19" form="role-form"--}}
                                            {{--                                                                   name="permissions[]"--}}
                                            {{--                                                                   value="addonsitem_list">--}}
                                            {{--                                                            <label class="custom-control-label text-secondary"--}}
                                            {{--                                                                   for="customCheck19">@lang('backoffice.list')</label>--}}
                                            {{--                                                        </div>--}}
                                            {{--                                                    </td>--}}
                                            {{--                                                    <td>--}}
                                            {{--                                                        <div class="custom-control custom-checkbox col-sm-2 ">--}}
                                            {{--                                                            <input type="checkbox" class="custom-control-input"--}}
                                            {{--                                                                   id="customCheck20" form="role-form"--}}
                                            {{--                                                                   name="permissions[]"--}}
                                            {{--                                                                   value="addonsitem_add">--}}
                                            {{--                                                            <label class="custom-control-label text-secondary"--}}
                                            {{--                                                                   for="customCheck20">@lang('backoffice.add')</label>--}}
                                            {{--                                                        </div>--}}

                                            {{--                                                    </td>--}}
                                            {{--                                                    <td>--}}
                                            {{--                                                        <div class="custom-control custom-checkbox col-sm-2 ">--}}
                                            {{--                                                            <input type="checkbox" class="custom-control-input"--}}
                                            {{--                                                                   id="customCheck21" form="role-form"--}}
                                            {{--                                                                   name="permissions[]"--}}
                                            {{--                                                                   value="addonsitem_edit">--}}
                                            {{--                                                            <label class="custom-control-label text-secondary"--}}
                                            {{--                                                                   for="customCheck21">@lang('backoffice.edit')</label>--}}
                                            {{--                                                        </div>--}}
                                            {{--                                                    </td>--}}
                                            {{--                                                    <td>--}}
                                            {{--                                                        <div class="custom-control custom-checkbox col-sm-2 ">--}}
                                            {{--                                                            <input type="checkbox" class="custom-control-input"--}}
                                            {{--                                                                   id="customCheck22" form="role-form"--}}
                                            {{--                                                                   name="permissions[]"--}}
                                            {{--                                                                   value="addonsitem_delete">--}}
                                            {{--                                                            <label class="custom-control-label text-secondary"--}}
                                            {{--                                                                   for="customCheck22">@lang('backoffice.delete')</label>--}}
                                            {{--                                                        </div>--}}

                                            {{--                                                    </td>--}}
                                            {{--                                                </tr>--}}
                                            {{--                                                <tr>--}}
                                            {{--                                                    <td> @lang('backoffice.brand')  </td>--}}
                                            {{--                                                    <td>--}}
                                            {{--                                                        <div class="custom-control custom-checkbox col-sm-2 ">--}}
                                            {{--                                                            <input type="checkbox" class="custom-control-input"--}}
                                            {{--                                                                   id="customCheck23" form="role-form"--}}
                                            {{--                                                                   name="permissions[]"--}}
                                            {{--                                                                   value="brand_list">--}}
                                            {{--                                                            <label class="custom-control-label text-secondary"--}}
                                            {{--                                                                   for="customCheck23">@lang('backoffice.list')</label>--}}
                                            {{--                                                        </div>--}}
                                            {{--                                                    </td>--}}
                                            {{--                                                    <td>--}}
                                            {{--                                                        <div class="custom-control custom-checkbox col-sm-2 ">--}}
                                            {{--                                                            <input type="checkbox" class="custom-control-input"--}}
                                            {{--                                                                   id="customCheck24" form="role-form"--}}
                                            {{--                                                                   name="permissions[]"--}}
                                            {{--                                                                   value="brand_add">--}}
                                            {{--                                                            <label class="custom-control-label text-secondary"--}}
                                            {{--                                                                   for="customCheck24">@lang('backoffice.add')</label>--}}
                                            {{--                                                        </div>--}}

                                            {{--                                                    </td>--}}
                                            {{--                                                    <td>--}}
                                            {{--                                                        <div class="custom-control custom-checkbox col-sm-2 ">--}}
                                            {{--                                                            <input type="checkbox" class="custom-control-input"--}}
                                            {{--                                                                   id="customCheck25" form="role-form"--}}
                                            {{--                                                                   name="permissions[]"--}}
                                            {{--                                                                   value="brand_edit">--}}
                                            {{--                                                            <label class="custom-control-label text-secondary"--}}
                                            {{--                                                                   for="customCheck25">@lang('backoffice.edit')</label>--}}
                                            {{--                                                        </div>--}}
                                            {{--                                                    </td>--}}
                                            {{--                                                    <td>--}}
                                            {{--                                                        <div class="custom-control custom-checkbox col-sm-2 ">--}}
                                            {{--                                                            <input type="checkbox" class="custom-control-input"--}}
                                            {{--                                                                   id="customCheck26" form="role-form"--}}
                                            {{--                                                                   name="permissions[]"--}}
                                            {{--                                                                   value="brand_delete">--}}
                                            {{--                                                            <label class="custom-control-label text-secondary"--}}
                                            {{--                                                                   for="customCheck26">@lang('backoffice.delete')</label>--}}
                                            {{--                                                        </div>--}}

                                            {{--                                                    </td>--}}
                                            {{--                                                </tr>--}}
                                            {{--                                                <tr>--}}
                                            {{--                                                    <td> @lang('backoffice.customer')  </td>--}}
                                            {{--                                                    <td>--}}
                                            {{--                                                        <div class="custom-control custom-checkbox col-sm-2 ">--}}
                                            {{--                                                            <input type="checkbox" class="custom-control-input"--}}
                                            {{--                                                                   id="customCheck27" form="role-form"--}}
                                            {{--                                                                   name="permissions[]"--}}
                                            {{--                                                                   value="customer_list">--}}
                                            {{--                                                            <label class="custom-control-label text-secondary"--}}
                                            {{--                                                                   for="customCheck27">@lang('backoffice.list')</label>--}}
                                            {{--                                                        </div>--}}
                                            {{--                                                    </td>--}}
                                            {{--                                                    <td>--}}
                                            {{--                                                        <div class="custom-control custom-checkbox col-sm-2 ">--}}
                                            {{--                                                            <input type="checkbox" class="custom-control-input"--}}
                                            {{--                                                                   id="customCheck28" form="role-form"--}}
                                            {{--                                                                   name="permissions[]"--}}
                                            {{--                                                                   value="customer_add">--}}
                                            {{--                                                            <label class="custom-control-label text-secondary"--}}
                                            {{--                                                                   for="customCheck28">@lang('backoffice.add')</label>--}}
                                            {{--                                                        </div>--}}

                                            {{--                                                    </td>--}}
                                            {{--                                                    <td>--}}
                                            {{--                                                        <div class="custom-control custom-checkbox col-sm-2 ">--}}
                                            {{--                                                            <input type="checkbox" class="custom-control-input"--}}
                                            {{--                                                                   id="customCheck29" form="role-form"--}}
                                            {{--                                                                   name="permissions[]"--}}
                                            {{--                                                                   value="customer_edit">--}}
                                            {{--                                                            <label class="custom-control-label text-secondary"--}}
                                            {{--                                                                   for="customCheck29">@lang('backoffice.edit')</label>--}}
                                            {{--                                                        </div>--}}
                                            {{--                                                    </td>--}}
                                            {{--                                                    <td>--}}
                                            {{--                                                    </td>--}}
                                            {{--                                                </tr>--}}
                                            {{--                                                <tr>--}}
                                            {{--                                                    <td> @lang('backoffice.customer_group')  </td>--}}
                                            {{--                                                    <td>--}}
                                            {{--                                                        <div class="custom-control custom-checkbox col-sm-2 ">--}}
                                            {{--                                                            <input type="checkbox" class="custom-control-input"--}}
                                            {{--                                                                   id="customCheck30" form="role-form"--}}
                                            {{--                                                                   name="permissions[]"--}}
                                            {{--                                                                   value="customer_group_list">--}}
                                            {{--                                                            <label class="custom-control-label text-secondary"--}}
                                            {{--                                                                   for="customCheck30">@lang('backoffice.list')</label>--}}
                                            {{--                                                        </div>--}}
                                            {{--                                                    </td>--}}
                                            {{--                                                    <td>--}}
                                            {{--                                                        <div class="custom-control custom-checkbox col-sm-2 ">--}}
                                            {{--                                                            <input type="checkbox" class="custom-control-input"--}}
                                            {{--                                                                   id="customCheck31" form="role-form"--}}
                                            {{--                                                                   name="permissions[]"--}}
                                            {{--                                                                   value="customer_group_add">--}}
                                            {{--                                                            <label class="custom-control-label text-secondary"--}}
                                            {{--                                                                   for="customCheck31">@lang('backoffice.add')</label>--}}
                                            {{--                                                        </div>--}}

                                            {{--                                                    </td>--}}
                                            {{--                                                    <td>--}}
                                            {{--                                                        <div class="custom-control custom-checkbox col-sm-2 ">--}}
                                            {{--                                                            <input type="checkbox" class="custom-control-input"--}}
                                            {{--                                                                   id="customCheck32" form="role-form"--}}
                                            {{--                                                                   name="permissions[]"--}}
                                            {{--                                                                   value="customer_group_edit">--}}
                                            {{--                                                            <label class="custom-control-label text-secondary"--}}
                                            {{--                                                                   for="customCheck32">@lang('backoffice.edit')</label>--}}
                                            {{--                                                        </div>--}}
                                            {{--                                                    </td>--}}
                                            {{--                                                    <td>--}}
                                            {{--                                                        <div class="custom-control custom-checkbox col-sm-2 ">--}}
                                            {{--                                                            <input type="checkbox" class="custom-control-input"--}}
                                            {{--                                                                   id="customCheck33" form="role-form"--}}
                                            {{--                                                                   name="permissions[]"--}}
                                            {{--                                                                   value="customer_group_delete">--}}
                                            {{--                                                            <label class="custom-control-label text-secondary"--}}
                                            {{--                                                                   for="customCheck33">@lang('backoffice.delete')</label>--}}
                                            {{--                                                        </div>--}}
                                            {{--                                                    </td>--}}
                                            {{--                                                </tr>--}}
                                            {{--                                                <tr>--}}
                                            {{--                                                    <td> @lang('backoffice.general_setup') </td>--}}
                                            {{--                                                    <td></td>--}}
                                            {{--                                                    <td></td>--}}
                                            {{--                                                    <td>--}}
                                            {{--                                                        <div class="custom-control custom-checkbox ">--}}
                                            {{--                                                            <input type="checkbox" class="custom-control-input"--}}
                                            {{--                                                                   id="customCheck34" form="role-form"--}}
                                            {{--                                                                   name="permissions[]"--}}
                                            {{--                                                                   value="general_setup_edit">--}}
                                            {{--                                                            <label class="custom-control-label text-secondary"--}}
                                            {{--                                                                   for="customCheck34">@lang('backoffice.edit')</label>--}}
                                            {{--                                                        </div>--}}
                                            {{--                                                    </td>--}}
                                            {{--                                                    <td></td>--}}
                                            {{--                                                </tr>--}}
                                            {{--                                                <tr>--}}
                                            {{--                                                    <td> @lang('backoffice.outlets_and_registers')  </td>--}}
                                            {{--                                                    <td>--}}
                                            {{--                                                        <div class="custom-control custom-checkbox col-sm-2 ">--}}
                                            {{--                                                            <input type="checkbox" class="custom-control-input"--}}
                                            {{--                                                                   id="customCheck35" form="role-form"--}}
                                            {{--                                                                   name="permissions[]"--}}
                                            {{--                                                                   value="outlet_list">--}}
                                            {{--                                                            <label class="custom-control-label text-secondary"--}}
                                            {{--                                                                   for="customCheck35">@lang('backoffice.list')</label>--}}
                                            {{--                                                        </div>--}}
                                            {{--                                                    </td>--}}
                                            {{--                                                    <td>--}}
                                            {{--                                                        <div class="custom-control custom-checkbox col-sm-2 ">--}}
                                            {{--                                                            <input type="checkbox" class="custom-control-input"--}}
                                            {{--                                                                   id="customCheck36" form="role-form"--}}
                                            {{--                                                                   name="permissions[]"--}}
                                            {{--                                                                   value="outlet_add">--}}
                                            {{--                                                            <label class="custom-control-label text-secondary"--}}
                                            {{--                                                                   for="customCheck36">@lang('backoffice.add')</label>--}}
                                            {{--                                                        </div>--}}

                                            {{--                                                    </td>--}}
                                            {{--                                                    <td>--}}
                                            {{--                                                        <div class="custom-control custom-checkbox col-sm-2 ">--}}
                                            {{--                                                            <input type="checkbox" class="custom-control-input"--}}
                                            {{--                                                                   id="customCheck37" form="role-form"--}}
                                            {{--                                                                   name="permissions[]"--}}
                                            {{--                                                                   value="outlet_edit">--}}
                                            {{--                                                            <label class="custom-control-label text-secondary"--}}
                                            {{--                                                                   for="customCheck37">@lang('backoffice.edit')</label>--}}
                                            {{--                                                        </div>--}}
                                            {{--                                                    </td>--}}
                                            {{--                                                    <td>--}}
                                            {{--                                                        <div class="custom-control custom-checkbox col-sm-2 ">--}}
                                            {{--                                                            <input type="checkbox" class="custom-control-input"--}}
                                            {{--                                                                   id="customCheck38" form="role-form"--}}
                                            {{--                                                                   name="permissions[]"--}}
                                            {{--                                                                   value="outlet_delete">--}}
                                            {{--                                                            <label class="custom-control-label text-secondary"--}}
                                            {{--                                                                   for="customCheck38">@lang('backoffice.delete')</label>--}}
                                            {{--                                                        </div>--}}
                                            {{--                                                    </td>--}}
                                            {{--                                                </tr>--}}
                                            {{--                                                <tr>--}}
                                            {{--                                                    <td> @lang('backoffice.register')  </td>--}}
                                            {{--                                                    <td>--}}
                                            {{--                                                    </td>--}}
                                            {{--                                                    <td>--}}
                                            {{--                                                        <div class="custom-control custom-checkbox col-sm-2 ">--}}
                                            {{--                                                            <input type="checkbox" class="custom-control-input"--}}
                                            {{--                                                                   id="customCheck39" form="role-form"--}}
                                            {{--                                                                   name="permissions[]"--}}
                                            {{--                                                                   value="register_add">--}}
                                            {{--                                                            <label class="custom-control-label text-secondary"--}}
                                            {{--                                                                   for="customCheck39">@lang('backoffice.add')</label>--}}
                                            {{--                                                        </div>--}}

                                            {{--                                                    </td>--}}
                                            {{--                                                    <td>--}}
                                            {{--                                                        <div class="custom-control custom-checkbox col-sm-2 ">--}}
                                            {{--                                                            <input type="checkbox" class="custom-control-input"--}}
                                            {{--                                                                   id="customCheck40" form="role-form"--}}
                                            {{--                                                                   name="permissions[]"--}}
                                            {{--                                                                   value="register_edit">--}}
                                            {{--                                                            <label class="custom-control-label text-secondary"--}}
                                            {{--                                                                   for="customCheck40">@lang('backoffice.edit')</label>--}}
                                            {{--                                                        </div>--}}
                                            {{--                                                    </td>--}}
                                            {{--                                                    <td>--}}
                                            {{--                                                        <div class="custom-control custom-checkbox col-sm-2 ">--}}
                                            {{--                                                            <input type="checkbox" class="custom-control-input"--}}
                                            {{--                                                                   id="customCheck41" form="role-form"--}}
                                            {{--                                                                   name="permissions[]"--}}
                                            {{--                                                                   value="register_delete">--}}
                                            {{--                                                            <label class="custom-control-label text-secondary"--}}
                                            {{--                                                                   for="customCheck41">@lang('backoffice.delete')</label>--}}
                                            {{--                                                        </div>--}}
                                            {{--                                                    </td>--}}
                                            {{--                                                </tr>--}}
                                            {{--                                                <tr>--}}
                                            {{--                                                    <td> @lang('backoffice.user')  </td>--}}
                                            {{--                                                    <td>--}}
                                            {{--                                                        <div class="custom-control custom-checkbox col-sm-2 ">--}}
                                            {{--                                                            <input type="checkbox" class="custom-control-input"--}}
                                            {{--                                                                   id="customCheck42" form="role-form"--}}
                                            {{--                                                                   name="permissions[]"--}}
                                            {{--                                                                   value="user_list">--}}
                                            {{--                                                            <label class="custom-control-label text-secondary"--}}
                                            {{--                                                                   for="customCheck42">@lang('backoffice.list')</label>--}}
                                            {{--                                                        </div>--}}
                                            {{--                                                    </td>--}}
                                            {{--                                                    <td>--}}
                                            {{--                                                        <div class="custom-control custom-checkbox col-sm-2 ">--}}
                                            {{--                                                            <input type="checkbox" class="custom-control-input"--}}
                                            {{--                                                                   id="customCheck43" form="role-form"--}}
                                            {{--                                                                   name="permissions[]"--}}
                                            {{--                                                                   value="user_add">--}}
                                            {{--                                                            <label class="custom-control-label text-secondary"--}}
                                            {{--                                                                   for="customCheck43">@lang('backoffice.add')</label>--}}
                                            {{--                                                        </div>--}}

                                            {{--                                                    </td>--}}
                                            {{--                                                    <td>--}}
                                            {{--                                                        <div class="custom-control custom-checkbox col-sm-2 ">--}}
                                            {{--                                                            <input type="checkbox" class="custom-control-input"--}}
                                            {{--                                                                   id="customCheck44" form="role-form"--}}
                                            {{--                                                                   name="permissions[]"--}}
                                            {{--                                                                   value="user_edit">--}}
                                            {{--                                                            <label class="custom-control-label text-secondary"--}}
                                            {{--                                                                   for="customCheck44">@lang('backoffice.edit')</label>--}}
                                            {{--                                                        </div>--}}
                                            {{--                                                    </td>--}}
                                            {{--                                                    <td>--}}
                                            {{--                                                        <div class="custom-control custom-checkbox col-sm-2 ">--}}
                                            {{--                                                            <input type="checkbox" class="custom-control-input"--}}
                                            {{--                                                                   id="customCheck45" form="role-form"--}}
                                            {{--                                                                   name="permissions[]"--}}
                                            {{--                                                                   value="user_delete">--}}
                                            {{--                                                            <label class="custom-control-label text-secondary"--}}
                                            {{--                                                                   for="customCheck45">@lang('backoffice.delete')</label>--}}
                                            {{--                                                        </div>--}}
                                            {{--                                                    </td>--}}
                                            {{--                                                </tr>--}}
                                            {{--                                                <tr>--}}
                                            {{--                                                    <td> @lang('backoffice.roles')  </td>--}}
                                            {{--                                                    <td>--}}
                                            {{--                                                        <div class="custom-control custom-checkbox col-sm-2 ">--}}
                                            {{--                                                            <input type="checkbox" class="custom-control-input"--}}
                                            {{--                                                                   id="customCheck46" form="role-form"--}}
                                            {{--                                                                   name="permissions[]"--}}
                                            {{--                                                                   value="roles_list">--}}
                                            {{--                                                            <label class="custom-control-label text-secondary"--}}
                                            {{--                                                                   for="customCheck46">@lang('backoffice.list')</label>--}}
                                            {{--                                                        </div>--}}
                                            {{--                                                    </td>--}}
                                            {{--                                                    <td>--}}
                                            {{--                                                        <div class="custom-control custom-checkbox col-sm-2 ">--}}
                                            {{--                                                            <input type="checkbox" class="custom-control-input"--}}
                                            {{--                                                                   id="customCheck47" form="role-form"--}}
                                            {{--                                                                   name="permissions[]"--}}
                                            {{--                                                                   value="roles_add">--}}
                                            {{--                                                            <label class="custom-control-label text-secondary"--}}
                                            {{--                                                                   for="customCheck47">@lang('backoffice.add')</label>--}}
                                            {{--                                                        </div>--}}

                                            {{--                                                    </td>--}}
                                            {{--                                                    <td>--}}
                                            {{--                                                        <div class="custom-control custom-checkbox col-sm-2 ">--}}
                                            {{--                                                            <input type="checkbox" class="custom-control-input"--}}
                                            {{--                                                                   id="customCheck48" form="role-form"--}}
                                            {{--                                                                   name="permissions[]"--}}
                                            {{--                                                                   value="roles_edit">--}}
                                            {{--                                                            <label class="custom-control-label text-secondary"--}}
                                            {{--                                                                   for="customCheck48">@lang('backoffice.edit')</label>--}}
                                            {{--                                                        </div>--}}
                                            {{--                                                    </td>--}}
                                            {{--                                                    <td>--}}
                                            {{--                                                        <div class="custom-control custom-checkbox col-sm-2 ">--}}
                                            {{--                                                            <input type="checkbox" class="custom-control-input"--}}
                                            {{--                                                                   id="customCheck49" form="role-form"--}}
                                            {{--                                                                   name="permissions[]"--}}
                                            {{--                                                                   value="roles_delete">--}}
                                            {{--                                                            <label class="custom-control-label text-secondary"--}}
                                            {{--                                                                   for="customCheck49">@lang('backoffice.delete')</label>--}}
                                            {{--                                                        </div>--}}
                                            {{--                                                    </td>--}}
                                            {{--                                                </tr>--}}
                                            {{--                                                <tr>--}}
                                            {{--                                                    <td> @lang('backoffice.apps')  </td>--}}
                                            {{--                                                    <td>--}}
                                            {{--                                                        <div class="custom-control custom-checkbox col-sm-2 ">--}}
                                            {{--                                                            <input type="checkbox" class="custom-control-input"--}}
                                            {{--                                                                   id="customCheck50" form="role-form"--}}
                                            {{--                                                                   name="permissions[]"--}}
                                            {{--                                                                   value="apps_view">--}}
                                            {{--                                                            <label class="custom-control-label text-secondary"--}}
                                            {{--                                                                   for="customCheck50">@lang('backoffice.list')</label>--}}
                                            {{--                                                        </div>--}}
                                            {{--                                                    </td>--}}
                                            {{--                                                    <td></td>--}}
                                            {{--                                                    <td></td>--}}
                                            {{--                                                    <td></td>--}}
                                            {{--                                                </tr>--}}
                                            {{--                                                <tr>--}}
                                            {{--                                                    <td> @lang('backoffice.plugin')  </td>--}}
                                            {{--                                                    <td>--}}
                                            {{--                                                        <div class="custom-control custom-checkbox col-sm-2 ">--}}
                                            {{--                                                            <input type="checkbox" class="custom-control-input"--}}
                                            {{--                                                                   id="customCheck51" form="role-form"--}}
                                            {{--                                                                   name="permissions[]"--}}
                                            {{--                                                                   value="plugin_view">--}}
                                            {{--                                                            <label class="custom-control-label text-secondary"--}}
                                            {{--                                                                   for="customCheck51">@lang('backoffice.list')</label>--}}
                                            {{--                                                        </div>--}}
                                            {{--                                                    </td>--}}
                                            {{--                                                    <td></td>--}}
                                            {{--                                                    <td></td>--}}
                                            {{--                                                    <td></td>--}}
                                            {{--                                                </tr>--}}
                                            {{--<!-----------------------------------------------Website Permissions Start-->--}}
                                            {{--                                                <tr>--}}
                                            {{--                                                    <td> @lang('backoffice.Ecommerce_Setup') </td>--}}
                                            {{--                                                    <td></td>--}}
                                            {{--                                                    <td></td>--}}
                                            {{--                                                    <td>--}}
                                            {{--                                                        <div class="custom-control custom-checkbox ">--}}
                                            {{--                                                            <input type="checkbox" class="custom-control-input"--}}
                                            {{--                                                                   id="customCheck72" form="role-form"--}}
                                            {{--                                                                   name="permissions[]"--}}
                                            {{--                                                                   value="ecommerce_setup_edit">--}}
                                            {{--                                                            <label class="custom-control-label text-secondary"--}}
                                            {{--                                                                   for="customCheck72"> @lang('backoffice.edit')</label>--}}
                                            {{--                                                        </div>--}}
                                            {{--                                                    </td>--}}
                                            {{--                                                    <td></td>--}}
                                            {{--                                                </tr>--}}
                                            {{--                                                <tr>--}}
                                            {{--                                                    <td> @lang('backoffice.orders_management') </td>--}}
                                            {{--                                                    <td> <div class="custom-control custom-checkbox ">--}}
                                            {{--                                                            <input type="checkbox" class="custom-control-input"--}}
                                            {{--                                                                   id="customCheck85" form="role-form"--}}
                                            {{--                                                                   name="permissions[]"--}}
                                            {{--                                                                   value="order_web_management_list">--}}
                                            {{--                                                            <label class="custom-control-label text-secondary"--}}
                                            {{--                                                                   for="customCheck85"> @lang('backoffice.list')</label>--}}
                                            {{--                                                        </div></td>--}}
                                            {{--                                                    <td></td>--}}
                                            {{--                                                    <td>--}}

                                            {{--                                                    </td>--}}
                                            {{--                                                    <td></td>--}}
                                            {{--                                                </tr>--}}
                                            {{--                                                <tr>--}}
                                            {{--                                                    <td> @lang('backoffice.WebSetting') </td>--}}
                                            {{--                                                    <td> <div class="custom-control custom-checkbox ">--}}
                                            {{--                                                            <input type="checkbox" class="custom-control-input"--}}
                                            {{--                                                                   id="customCheck97" form="role-form"--}}
                                            {{--                                                                   name="permissions[]"--}}
                                            {{--                                                                   value="web_settings_list">--}}
                                            {{--                                                            <label class="custom-control-label text-secondary"--}}
                                            {{--                                                                   for="customCheck97"> @lang('backoffice.list')</label>--}}
                                            {{--                                                        </div></td>--}}
                                            {{--                                                    <td></td>--}}
                                            {{--                                                    <td>--}}

                                            {{--                                                    </td>--}}
                                            {{--                                                    <td></td>--}}
                                            {{--                                                </tr>--}}
                                            {{--                                                <tr>--}}
                                            {{--                                                    <td> @lang('backoffice.web_customer') </td>--}}
                                            {{--                                                    <td>--}}
                                            {{--                                                        <div class="custom-control custom-checkbox ">--}}
                                            {{--                                                            <input type="checkbox" class="custom-control-input"--}}
                                            {{--                                                                   id="customCheck86" form="role-form"--}}
                                            {{--                                                                   name="permissions[]"--}}
                                            {{--                                                                   value="web_customer_list">--}}
                                            {{--                                                            <label class="custom-control-label text-secondary"--}}
                                            {{--                                                                   for="customCheck86"> @lang('backoffice.list')</label>--}}
                                            {{--                                                        </div>--}}
                                            {{--                                                    </td>--}}
                                            {{--                                                    <td></td>--}}
                                            {{--                                                    <td>--}}

                                            {{--                                                    </td>--}}
                                            {{--                                                    <td></td>--}}
                                            {{--                                                </tr>--}}
                                            {{--                                                <tr>--}}
                                            {{--                                                    <td> @lang('backoffice.web_subscribe_users') </td>--}}
                                            {{--                                                    <td>--}}
                                            {{--                                                        <div class="custom-control custom-checkbox ">--}}
                                            {{--                                                            <input type="checkbox" class="custom-control-input"--}}
                                            {{--                                                                   id="customCheck87" form="role-form"--}}
                                            {{--                                                                   name="permissions[]"--}}
                                            {{--                                                                   value="web_subscribe_user_list">--}}
                                            {{--                                                            <label class="custom-control-label text-secondary"--}}
                                            {{--                                                                   for="customCheck87"> @lang('backoffice.list')</label>--}}
                                            {{--                                                        </div>--}}
                                            {{--                                                    </td>--}}
                                            {{--                                                    <td></td>--}}
                                            {{--                                                    <td>--}}

                                            {{--                                                    </td>--}}
                                            {{--                                                    <td></td>--}}
                                            {{--                                                </tr>--}}
                                            {{--                                                <tr>--}}
                                            {{--                                                    <td> @lang('backoffice.product_list')  </td>--}}
                                            {{--                                                    <td>--}}
                                            {{--                                                        <div class="custom-control custom-checkbox col-sm-2 ">--}}
                                            {{--                                                            <input type="checkbox" class="custom-control-input"--}}
                                            {{--                                                                   id="customCheck76" form="role-form"--}}
                                            {{--                                                                   name="permissions[]"--}}
                                            {{--                                                                   value="product_web_list">--}}
                                            {{--                                                            <label class="custom-control-label text-secondary"--}}
                                            {{--                                                                   for="customCheck76">@lang('backoffice.list')</label>--}}
                                            {{--                                                        </div>--}}
                                            {{--                                                    </td>--}}
                                            {{--                                                    <td>--}}
                                            {{--                                                        <div class="custom-control custom-checkbox col-sm-2 ">--}}
                                            {{--                                                            <input type="checkbox" class="custom-control-input"--}}
                                            {{--                                                                   id="customCheck73" form="role-form"--}}
                                            {{--                                                                   name="permissions[]"--}}
                                            {{--                                                                   value="product_web_add">--}}
                                            {{--                                                            <label class="custom-control-label text-secondary"--}}
                                            {{--                                                                   for="customCheck73">@lang('backoffice.add')</label>--}}
                                            {{--                                                        </div>--}}

                                            {{--                                                    </td>--}}
                                            {{--                                                    <td>--}}
                                            {{--                                                        <div class="custom-control custom-checkbox col-sm-2 ">--}}
                                            {{--                                                            <input type="checkbox" class="custom-control-input"--}}
                                            {{--                                                                   id="customCheck74" form="role-form"--}}
                                            {{--                                                                   name="permissions[]"--}}
                                            {{--                                                                   value="product_web_edit">--}}
                                            {{--                                                            <label class="custom-control-label text-secondary"--}}
                                            {{--                                                                   for="customCheck74">@lang('backoffice.edit')</label>--}}
                                            {{--                                                        </div>--}}
                                            {{--                                                    </td>--}}
                                            {{--                                                    <td>--}}
                                            {{--                                                        <div class="custom-control custom-checkbox col-sm-2 ">--}}
                                            {{--                                                            <input type="checkbox" class="custom-control-input"--}}
                                            {{--                                                                   id="customCheck75" form="role-form"--}}
                                            {{--                                                                   name="permissions[]"--}}
                                            {{--                                                                   value="product_web_delete">--}}
                                            {{--                                                            <label class="custom-control-label text-secondary"--}}
                                            {{--                                                                   for="customCheck75">@lang('backoffice.delete')</label>--}}
                                            {{--                                                        </div>--}}
                                            {{--                                                    </td>--}}
                                            {{--                                                </tr>--}}
                                            {{--                                                <tr>--}}
                                            {{--                                                    <td> @lang('backoffice.shipping_method')  </td>--}}
                                            {{--                                                    <td>--}}
                                            {{--                                                        <div class="custom-control custom-checkbox col-sm-2 ">--}}
                                            {{--                                                            <input type="checkbox" class="custom-control-input"--}}
                                            {{--                                                                   id="customCheck77" form="role-form"--}}
                                            {{--                                                                   name="permissions[]"--}}
                                            {{--                                                                   value="shipping_web_list">--}}
                                            {{--                                                            <label class="custom-control-label text-secondary"--}}
                                            {{--                                                                   for="customCheck77">@lang('backoffice.list')</label>--}}
                                            {{--                                                        </div>--}}
                                            {{--                                                    </td>--}}
                                            {{--                                                    <td>--}}
                                            {{--                                                        <div class="custom-control custom-checkbox col-sm-2 ">--}}
                                            {{--                                                            <input type="checkbox" class="custom-control-input"--}}
                                            {{--                                                                   id="customCheck78" form="role-form"--}}
                                            {{--                                                                   name="permissions[]"--}}
                                            {{--                                                                   value="shipping_web_add">--}}
                                            {{--                                                            <label class="custom-control-label text-secondary"--}}
                                            {{--                                                                   for="customCheck78">@lang('backoffice.add')</label>--}}
                                            {{--                                                        </div>--}}

                                            {{--                                                    </td>--}}
                                            {{--                                                    <td>--}}
                                            {{--                                                        <div class="custom-control custom-checkbox col-sm-2 ">--}}
                                            {{--                                                            <input type="checkbox" class="custom-control-input"--}}
                                            {{--                                                                   id="customCheck79" form="role-form"--}}
                                            {{--                                                                   name="permissions[]"--}}
                                            {{--                                                                   value="shipping_web_edit">--}}
                                            {{--                                                            <label class="custom-control-label text-secondary"--}}
                                            {{--                                                                   for="customCheck79">@lang('backoffice.edit')</label>--}}
                                            {{--                                                        </div>--}}
                                            {{--                                                    </td>--}}
                                            {{--                                                    <td>--}}
                                            {{--                                                        <div class="custom-control custom-checkbox col-sm-2 ">--}}
                                            {{--                                                            <input type="checkbox" class="custom-control-input"--}}
                                            {{--                                                                   id="customCheck80" form="role-form"--}}
                                            {{--                                                                   name="permissions[]"--}}
                                            {{--                                                                   value="shipping_web_delete">--}}
                                            {{--                                                            <label class="custom-control-label text-secondary"--}}
                                            {{--                                                                   for="customCheck80">@lang('backoffice.delete')</label>--}}
                                            {{--                                                        </div>--}}
                                            {{--                                                    </td>--}}
                                            {{--                                                </tr>--}}
                                            {{--                                                <tr>--}}
                                            {{--                                                    <td> @lang('backoffice.payment_gateways')  </td>--}}
                                            {{--                                                    <td>--}}
                                            {{--                                                        <div class="custom-control custom-checkbox col-sm-2 ">--}}
                                            {{--                                                            <input type="checkbox" class="custom-control-input"--}}
                                            {{--                                                                   id="customCheck81" form="role-form"--}}
                                            {{--                                                                   name="permissions[]"--}}
                                            {{--                                                                   value="payment_web_list">--}}
                                            {{--                                                            <label class="custom-control-label text-secondary"--}}
                                            {{--                                                                   for="customCheck81">@lang('backoffice.list')</label>--}}
                                            {{--                                                        </div>--}}
                                            {{--                                                    </td>--}}
                                            {{--                                                    <td>--}}
                                            {{--                                                        <div class="custom-control custom-checkbox col-sm-2 ">--}}
                                            {{--                                                            <input type="checkbox" class="custom-control-input"--}}
                                            {{--                                                                   id="customCheck82" form="role-form"--}}
                                            {{--                                                                   name="permissions[]"--}}
                                            {{--                                                                   value="payment_web_add">--}}
                                            {{--                                                            <label class="custom-control-label text-secondary"--}}
                                            {{--                                                                   for="customCheck82">@lang('backoffice.add')</label>--}}
                                            {{--                                                        </div>--}}

                                            {{--                                                    </td>--}}
                                            {{--                                                    <td>--}}
                                            {{--                                                        <div class="custom-control custom-checkbox col-sm-2 ">--}}
                                            {{--                                                            <input type="checkbox" class="custom-control-input"--}}
                                            {{--                                                                   id="customCheck83" form="role-form"--}}
                                            {{--                                                                   name="permissions[]"--}}
                                            {{--                                                                   value="payment_web_edit">--}}
                                            {{--                                                            <label class="custom-control-label text-secondary"--}}
                                            {{--                                                                   for="customCheck83">@lang('backoffice.edit')</label>--}}
                                            {{--                                                        </div>--}}
                                            {{--                                                    </td>--}}
                                            {{--                                                    <td>--}}
                                            {{--                                                        <div class="custom-control custom-checkbox col-sm-2 ">--}}
                                            {{--                                                            <input type="checkbox" class="custom-control-input"--}}
                                            {{--                                                                   id="customCheck84" form="role-form"--}}
                                            {{--                                                                   name="permissions[]"--}}
                                            {{--                                                                   value="payment_web_delete">--}}
                                            {{--                                                            <label class="custom-control-label text-secondary"--}}
                                            {{--                                                                   for="customCheck84">@lang('backoffice.delete')</label>--}}
                                            {{--                                                        </div>--}}
                                            {{--                                                    </td>--}}
                                            {{--                                                </tr>--}}
                                            {{--                                                <tr>--}}
                                            {{--                                                    <td> @lang('backoffice.banners')  </td>--}}
                                            {{--                                                    <td>--}}
                                            {{--                                                        <div class="custom-control custom-checkbox col-sm-2 ">--}}
                                            {{--                                                            <input type="checkbox" class="custom-control-input"--}}
                                            {{--                                                                   id="customCheck89" form="role-form"--}}
                                            {{--                                                                   name="permissions[]"--}}
                                            {{--                                                                   value="banner_web_list">--}}
                                            {{--                                                            <label class="custom-control-label text-secondary"--}}
                                            {{--                                                                   for="customCheck89">@lang('backoffice.list')</label>--}}
                                            {{--                                                        </div>--}}
                                            {{--                                                    </td>--}}
                                            {{--                                                    <td>--}}
                                            {{--                                                        <div class="custom-control custom-checkbox col-sm-2 ">--}}
                                            {{--                                                            <input type="checkbox" class="custom-control-input"--}}
                                            {{--                                                                   id="customCheck90" form="role-form"--}}
                                            {{--                                                                   name="permissions[]"--}}
                                            {{--                                                                   value="banner_web_add">--}}
                                            {{--                                                            <label class="custom-control-label text-secondary"--}}
                                            {{--                                                                   for="customCheck90">@lang('backoffice.add')</label>--}}
                                            {{--                                                        </div>--}}

                                            {{--                                                    </td>--}}
                                            {{--                                                    <td>--}}
                                            {{--                                                        <div class="custom-control custom-checkbox col-sm-2 ">--}}
                                            {{--                                                            <input type="checkbox" class="custom-control-input"--}}
                                            {{--                                                                   id="customCheck91" form="role-form"--}}
                                            {{--                                                                   name="permissions[]"--}}
                                            {{--                                                                   value="banner_web_edit">--}}
                                            {{--                                                            <label class="custom-control-label text-secondary"--}}
                                            {{--                                                                   for="customCheck91">@lang('backoffice.edit')</label>--}}
                                            {{--                                                        </div>--}}
                                            {{--                                                    </td>--}}
                                            {{--                                                    <td>--}}
                                            {{--                                                        <div class="custom-control custom-checkbox col-sm-2 ">--}}
                                            {{--                                                            <input type="checkbox" class="custom-control-input"--}}
                                            {{--                                                                   id="customCheck92" form="role-form"--}}
                                            {{--                                                                   name="permissions[]"--}}
                                            {{--                                                                   value="banner_web_delete">--}}
                                            {{--                                                            <label class="custom-control-label text-secondary"--}}
                                            {{--                                                                   for="customCheck92">@lang('backoffice.delete')</label>--}}
                                            {{--                                                        </div>--}}
                                            {{--                                                    </td>--}}
                                            {{--                                                </tr>--}}
                                            {{--                                                <tr>--}}
                                            {{--                                                    <td> @lang('backoffice.pages')  </td>--}}
                                            {{--                                                    <td>--}}
                                            {{--                                                        <div class="custom-control custom-checkbox col-sm-2 ">--}}
                                            {{--                                                            <input type="checkbox" class="custom-control-input"--}}
                                            {{--                                                                   id="customCheck93" form="role-form"--}}
                                            {{--                                                                   name="permissions[]"--}}
                                            {{--                                                                   value="pages_web_list">--}}
                                            {{--                                                            <label class="custom-control-label text-secondary"--}}
                                            {{--                                                                   for="customCheck93">@lang('backoffice.list')</label>--}}
                                            {{--                                                        </div>--}}
                                            {{--                                                    </td>--}}
                                            {{--                                                    <td>--}}
                                            {{--                                                        <div class="custom-control custom-checkbox col-sm-2 ">--}}
                                            {{--                                                            <input type="checkbox" class="custom-control-input"--}}
                                            {{--                                                                   id="customCheck94" form="role-form"--}}
                                            {{--                                                                   name="permissions[]"--}}
                                            {{--                                                                   value="pages_web_add">--}}
                                            {{--                                                            <label class="custom-control-label text-secondary"--}}
                                            {{--                                                                   for="customCheck94">@lang('backoffice.add')</label>--}}
                                            {{--                                                        </div>--}}

                                            {{--                                                    </td>--}}
                                            {{--                                                    <td>--}}
                                            {{--                                                        <div class="custom-control custom-checkbox col-sm-2 ">--}}
                                            {{--                                                            <input type="checkbox" class="custom-control-input"--}}
                                            {{--                                                                   id="customCheck95" form="role-form"--}}
                                            {{--                                                                   name="permissions[]"--}}
                                            {{--                                                                   value="pages_web_edit">--}}
                                            {{--                                                            <label class="custom-control-label text-secondary"--}}
                                            {{--                                                                   for="customCheck95">@lang('backoffice.edit')</label>--}}
                                            {{--                                                        </div>--}}
                                            {{--                                                    </td>--}}
                                            {{--                                                    <td>--}}
                                            {{--                                                        <div class="custom-control custom-checkbox col-sm-2 ">--}}
                                            {{--                                                            <input type="checkbox" class="custom-control-input"--}}
                                            {{--                                                                   id="customCheck96" form="role-form"--}}
                                            {{--                                                                   name="permissions[]"--}}
                                            {{--                                                                   value="pages_web_delete">--}}
                                            {{--                                                            <label class="custom-control-label text-secondary"--}}
                                            {{--                                                                   for="customCheck96">@lang('backoffice.delete')</label>--}}
                                            {{--                                                        </div>--}}
                                            {{--                                                    </td>--}}
                                            {{--                                                </tr>--}}

                                            {{--                                                </tbody>--}}

                                            {{--                                            </table>--}}
                                            {{--                                            <table class="table table-hover table-boarderd">--}}
                                            {{--                                                <thead>--}}
                                            {{--                                                <td>@lang('backoffice.reporting')</td>--}}
                                            {{--                                                <td><b></b></td>--}}
                                            {{--                                                <td><b></b></td>--}}
                                            {{--                                                <td><b></b></td>--}}
                                            {{--                                                </thead>--}}
                                            {{--                                                <tbody>--}}
                                            {{--                                                <div class="row">--}}
                                            {{--                                                    <tr>--}}

                                            {{--                                                        <td>--}}
                                            {{--                                                            <div class="custom-control custom-checkbox  ">--}}
                                            {{--                                                                <input type="checkbox" class="custom-control-input"--}}
                                            {{--                                                                       id="customCheck52" form="role-form"--}}
                                            {{--                                                                       name="permissions[]"--}}
                                            {{--                                                                       value="reporting_sales_report">--}}
                                            {{--                                                                <label class="custom-control-label text-secondary"--}}
                                            {{--                                                                       for="customCheck52">@lang('backoffice.sales_reports')</label>--}}
                                            {{--                                                            </div>--}}

                                            {{--                                                        </td>--}}
                                            {{--                                                        <td>--}}
                                            {{--                                                            <div class="custom-control custom-checkbox  ">--}}
                                            {{--                                                                <input type="checkbox" class="custom-control-input"--}}
                                            {{--                                                                       id="customCheck53" form="role-form"--}}
                                            {{--                                                                       name="permissions[]"--}}
                                            {{--                                                                       value="sales_export">--}}
                                            {{--                                                                <label class="custom-control-label text-secondary"--}}
                                            {{--                                                                       for="customCheck53">@lang('backoffice.sales_export')</label>--}}
                                            {{--                                                            </div>--}}

                                            {{--                                                        </td>--}}
                                            {{--                                                        <td>--}}
                                            {{--                                                            <div class="custom-control custom-checkbox  ">--}}
                                            {{--                                                                <input type="checkbox" class="custom-control-input"--}}
                                            {{--                                                                       id="customCheck54" form="role-form"--}}
                                            {{--                                                                       name="permissions[]"--}}
                                            {{--                                                                       value="reporting_inventory_report">--}}
                                            {{--                                                                <label class="custom-control-label text-secondary"--}}
                                            {{--                                                                       for="customCheck54">@lang('backoffice.inventory_reports')</label>--}}
                                            {{--                                                            </div>--}}
                                            {{--                                                        </td>--}}
                                            {{--                                                        <td>--}}
                                            {{--                                                            <div class="custom-control custom-checkbox  ">--}}
                                            {{--                                                                <input type="checkbox" class="custom-control-input"--}}
                                            {{--                                                                       id="customCheck55" form="role-form"--}}
                                            {{--                                                                       name="permissions[]"--}}
                                            {{--                                                                       value="inventory_export">--}}
                                            {{--                                                                <label class="custom-control-label text-secondary"--}}
                                            {{--                                                                       for="customCheck55">@lang('backoffice.inventory_export')</label>--}}
                                            {{--                                                            </div>--}}
                                            {{--                                                        </td>--}}
                                            {{--                                                    </tr>--}}
                                            {{--                                                </div>--}}


                                            {{--                                                <tr>--}}

                                            {{--                                                    <td>--}}
                                            {{--                                                        <div class="custom-control custom-checkbox  ">--}}
                                            {{--                                                            <input type="checkbox" class="custom-control-input"--}}
                                            {{--                                                                   id="customCheck56" form="role-form"--}}
                                            {{--                                                                   name="permissions[]"--}}
                                            {{--                                                                   value="reporting_payment_report">--}}
                                            {{--                                                            <label class="custom-control-label text-secondary"--}}
                                            {{--                                                                   for="customCheck56">@lang('backoffice.payment_reports')</label>--}}
                                            {{--                                                        </div>--}}

                                            {{--                                                    </td>--}}
                                            {{--                                                    <td>--}}
                                            {{--                                                        <div class="custom-control custom-checkbox  ">--}}
                                            {{--                                                            <input type="checkbox" class="custom-control-input"--}}
                                            {{--                                                                   id="customCheck57" form="role-form"--}}
                                            {{--                                                                   name="permissions[]"--}}
                                            {{--                                                                   value="payment_export">--}}
                                            {{--                                                            <label class="custom-control-label text-secondary"--}}
                                            {{--                                                                   for="customCheck57">@lang('backoffice.payment_export')</label>--}}
                                            {{--                                                        </div>--}}

                                            {{--                                                    </td>--}}
                                            {{--                                                    <td>--}}
                                            {{--                                                        <div class="custom-control custom-checkbox  ">--}}
                                            {{--                                                            <input type="checkbox" class="custom-control-input"--}}
                                            {{--                                                                   id="customCheck58" form="role-form"--}}
                                            {{--                                                                   name="permissions[]"--}}
                                            {{--                                                                   value="reporting_register_closure">--}}
                                            {{--                                                            <label class="custom-control-label text-secondary"--}}
                                            {{--                                                                   for="customCheck58">@lang('backoffice.register_losure')</label>--}}
                                            {{--                                                        </div>--}}
                                            {{--                                                    </td>--}}
                                            {{--                                                    <td>--}}
                                            {{--                                                        <div class="custom-control custom-checkbox  ">--}}
                                            {{--                                                            <input type="checkbox" class="custom-control-input"--}}
                                            {{--                                                                   id="customCheck59" form="role-form"--}}
                                            {{--                                                                   name="permissions[]"--}}
                                            {{--                                                                   value="register_export">--}}
                                            {{--                                                            <label class="custom-control-label text-secondary"--}}
                                            {{--                                                                   for="customCheck59">@lang('backoffice.register_export')</label>--}}
                                            {{--                                                        </div>--}}
                                            {{--                                                    </td>--}}
                                            {{--                                                </tr>--}}

                                            {{--                                                <tr>--}}

                                            {{--                                                    <td>--}}
                                            {{--                                                        <div class="custom-control custom-checkbox  ">--}}
                                            {{--                                                            <input type="checkbox" class="custom-control-input"--}}
                                            {{--                                                                   id="customCheck60" form="role-form"--}}
                                            {{--                                                                   name="permissions[]"--}}
                                            {{--                                                                   value="product_report">--}}
                                            {{--                                                            <label class="custom-control-label text-secondary"--}}
                                            {{--                                                                   for="customCheck60">@lang('backoffice.product_report')</label>--}}
                                            {{--                                                        </div>--}}

                                            {{--                                                    </td>--}}
                                            {{--                                                    <td>--}}
                                            {{--                                                        <div class="custom-control custom-checkbox  ">--}}
                                            {{--                                                            <input type="checkbox" class="custom-control-input"--}}
                                            {{--                                                                   id="customCheck61" form="role-form"--}}
                                            {{--                                                                   name="permissions[]"--}}
                                            {{--                                                                   value="product_export">--}}
                                            {{--                                                            <label class="custom-control-label text-secondary"--}}
                                            {{--                                                                   for="customCheck61">@lang('backoffice.product_export')</label>--}}
                                            {{--                                                        </div>--}}

                                            {{--                                                    </td>--}}
                                            {{--                                                    <td>--}}
                                            {{--                                                        <div class="custom-control custom-checkbox  ">--}}
                                            {{--                                                            <input type="checkbox" class="custom-control-input"--}}
                                            {{--                                                                   id="customCheck62" form="role-form"--}}
                                            {{--                                                                   name="permissions[]"--}}
                                            {{--                                                                   value="category_report">--}}
                                            {{--                                                            <label class="custom-control-label text-secondary"--}}
                                            {{--                                                                   for="customCheck62">@lang('backoffice.category_report')</label>--}}
                                            {{--                                                        </div>--}}
                                            {{--                                                    </td>--}}
                                            {{--                                                    <td>--}}
                                            {{--                                                        <div class="custom-control custom-checkbox  ">--}}
                                            {{--                                                            <input type="checkbox" class="custom-control-input"--}}
                                            {{--                                                                   id="customCheck63" form="role-form"--}}
                                            {{--                                                                   name="permissions[]"--}}
                                            {{--                                                                   value="category_export">--}}
                                            {{--                                                            <label class="custom-control-label text-secondary"--}}
                                            {{--                                                                   for="customCheck63">@lang('backoffice.category_export')</label>--}}
                                            {{--                                                        </div>--}}
                                            {{--                                                    </td>--}}
                                            {{--                                                </tr>--}}


                                            {{--                                                <tr>--}}

                                            {{--                                                    <td>--}}
                                            {{--                                                        <div class="custom-control custom-checkbox  ">--}}
                                            {{--                                                            <input type="checkbox" class="custom-control-input"--}}
                                            {{--                                                                   id="customCheck64" form="role-form"--}}
                                            {{--                                                                   name="permissions[]"--}}
                                            {{--                                                                   value="customer_report">--}}
                                            {{--                                                            <label class="custom-control-label text-secondary"--}}
                                            {{--                                                                   for="customCheck64">@lang('backoffice.customer_report')</label>--}}
                                            {{--                                                        </div>--}}

                                            {{--                                                    </td>--}}
                                            {{--                                                    <td>--}}
                                            {{--                                                        <div class="custom-control custom-checkbox  ">--}}
                                            {{--                                                            <input type="checkbox" class="custom-control-input"--}}
                                            {{--                                                                   id="customCheck65" form="role-form"--}}
                                            {{--                                                                   name="permissions[]"--}}
                                            {{--                                                                   value="customer_export">--}}
                                            {{--                                                            <label class="custom-control-label text-secondary"--}}
                                            {{--                                                                   for="customCheck65">@lang('backoffice.customer_export')</label>--}}
                                            {{--                                                        </div>--}}

                                            {{--                                                    </td>--}}
                                            {{--                                                    <td>--}}
                                            {{--                                                        <div class="custom-control custom-checkbox  ">--}}
                                            {{--                                                            <input type="checkbox" class="custom-control-input"--}}
                                            {{--                                                                   id="customCheck66" form="role-form"--}}
                                            {{--                                                                   name="permissions[]"--}}
                                            {{--                                                                   value="customer_group_report">--}}
                                            {{--                                                            <label class="custom-control-label text-secondary"--}}
                                            {{--                                                                   for="customCheck66">@lang('backoffice.customer_group_report')</label>--}}
                                            {{--                                                        </div>--}}
                                            {{--                                                    </td>--}}
                                            {{--                                                    <td>--}}
                                            {{--                                                        <div class="custom-control custom-checkbox  ">--}}
                                            {{--                                                            <input type="checkbox" class="custom-control-input"--}}
                                            {{--                                                                   id="customCheck67" form="role-form"--}}
                                            {{--                                                                   name="permissions[]"--}}
                                            {{--                                                                   value="customer_group_export">--}}
                                            {{--                                                            <label class="custom-control-label text-secondary"--}}
                                            {{--                                                                   for="customCheck67">@lang('backoffice.customer_group_export')</label>--}}
                                            {{--                                                        </div>--}}
                                            {{--                                                    </td>--}}
                                            {{--                                                </tr>--}}


                                            {{--                                                <tr>--}}

                                            {{--                                                    <td>--}}
                                            {{--                                                        <div class="custom-control custom-checkbox  ">--}}
                                            {{--                                                            <input type="checkbox" class="custom-control-input"--}}
                                            {{--                                                                   id="customCheck72" form="role-form"--}}
                                            {{--                                                                   name="permissions[]"--}}
                                            {{--                                                                   value="user_report">--}}
                                            {{--                                                            <label class="custom-control-label text-secondary"--}}
                                            {{--                                                                   for="customCheck72">@lang('backoffice.user_report')</label>--}}
                                            {{--                                                        </div>--}}

                                            {{--                                                    </td>--}}
                                            {{--                                                    <td>--}}
                                            {{--                                                        <div class="custom-control custom-checkbox  ">--}}
                                            {{--                                                            <input type="checkbox" class="custom-control-input"--}}
                                            {{--                                                                   id="customCheck73" form="role-form"--}}
                                            {{--                                                                   name="permissions[]"--}}
                                            {{--                                                                   value="user_export">--}}
                                            {{--                                                            <label class="custom-control-label text-secondary"--}}
                                            {{--                                                                   for="customCheck73">@lang('backoffice.user_export')</label>--}}
                                            {{--                                                        </div>--}}

                                            {{--                                                    </td>--}}
                                            {{--                                                    <td>--}}
                                            {{--                                                        <div class="custom-control custom-checkbox  ">--}}
                                            {{--                                                            <input type="checkbox" class="custom-control-input"--}}
                                            {{--                                                                   id="customCheck74" form="role-form"--}}
                                            {{--                                                                   name="permissions[]"--}}
                                            {{--                                                                   value="addon_report">--}}
                                            {{--                                                            <label class="custom-control-label text-secondary"--}}
                                            {{--                                                                   for="customCheck74">@lang('backoffice.addon_report')</label>--}}
                                            {{--                                                        </div>--}}
                                            {{--                                                    </td>--}}
                                            {{--                                                    <td>--}}
                                            {{--                                                        <div class="custom-control custom-checkbox  ">--}}
                                            {{--                                                            <input type="checkbox" class="custom-control-input"--}}
                                            {{--                                                                   id="customCheck75" form="role-form"--}}
                                            {{--                                                                   name="permissions[]"--}}
                                            {{--                                                                   value="addon_export">--}}
                                            {{--                                                            <label class="custom-control-label text-secondary"--}}
                                            {{--                                                                   for="customCheck75">@lang('backoffice.addon_export')</label>--}}
                                            {{--                                                        </div>--}}
                                            {{--                                                    </td>--}}
                                            {{--                                                </tr>--}}

                                            {{--                                                <tr>--}}

                                            {{--                                                    <td>--}}
                                            {{--                                                        <div class="custom-control custom-checkbox  ">--}}
                                            {{--                                                            <input type="checkbox" class="custom-control-input"--}}
                                            {{--                                                                   id="customCheck68" form="role-form"--}}
                                            {{--                                                                   name="permissions[]"--}}
                                            {{--                                                                   value="supplier_report">--}}
                                            {{--                                                            <label class="custom-control-label text-secondary"--}}
                                            {{--                                                                   for="customCheck68">@lang('backoffice.supplier_report')</label>--}}
                                            {{--                                                        </div>--}}

                                            {{--                                                    </td>--}}
                                            {{--                                                    <td>--}}
                                            {{--                                                        <div class="custom-control custom-checkbox  ">--}}
                                            {{--                                                            <input type="checkbox" class="custom-control-input"--}}
                                            {{--                                                                   id="customCheck69" form="role-form"--}}
                                            {{--                                                                   name="permissions[]"--}}
                                            {{--                                                                   value="supplier_export">--}}
                                            {{--                                                            <label class="custom-control-label text-secondary"--}}
                                            {{--                                                                   for="customCheck69">@lang('backoffice.supplier_export')</label>--}}
                                            {{--                                                        </div>--}}

                                            {{--                                                    </td>--}}
                                            {{--                                                    <td>--}}
                                            {{--                                                        <div class="custom-control custom-checkbox  ">--}}
                                            {{--                                                            <input type="checkbox" class="custom-control-input"--}}
                                            {{--                                                                   id="customCheck70" form="role-form"--}}
                                            {{--                                                                   name="permissions[]"--}}
                                            {{--                                                                   value="brand_report">--}}
                                            {{--                                                            <label class="custom-control-label text-secondary"--}}
                                            {{--                                                                   for="customCheck70">@lang('backoffice.brand_report')</label>--}}
                                            {{--                                                        </div>--}}
                                            {{--                                                    </td>--}}
                                            {{--                                                    <td>--}}
                                            {{--                                                        <div class="custom-control custom-checkbox  ">--}}
                                            {{--                                                            <input type="checkbox" class="custom-control-input"--}}
                                            {{--                                                                   id="customCheck71" form="role-form"--}}
                                            {{--                                                                   name="permissions[]"--}}
                                            {{--                                                                   value="brand_export">--}}
                                            {{--                                                            <label class="custom-control-label text-secondary"--}}
                                            {{--                                                                   for="customCheck71">@lang('backoffice.brand_export')</label>--}}
                                            {{--                                                        </div>--}}
                                            {{--                                                    </td>--}}
                                            {{--                                                </tr>--}}

                                            {{--                                                </tbody>--}}

                                            {{--                                            </table>--}}
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
                {{--                <div class="panel-default">--}}
                {{--                    <button type="submit" class="btn btn-primary" name="create"--}}
                {{--                            value="Create">@lang('backoffice.submit')</button>--}}
                {{--                    &nbsp--}}
                {{--                    <a href="{{URL::previous()}}" ng-disabled="button.disabled"--}}
                {{--                       class="btn btn-light">@lang('backoffice.cancel')</a>--}}
                {{--                    <div class="clearfix"></div>--}}
                {{--                </div>--}}

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
                                    window.location.href = site_url('usermanagement/roles');
                                }, 1000);
                            } else {
                                if (response.Errors.lenght > 0) {
                                    response.Errors.map((error) => {
                                        toastr.error(error, 'Error');
                                    });
                                } else {
                                    toastr.error(response.Errors, 'Error')
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
