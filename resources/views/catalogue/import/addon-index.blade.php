@extends('layouts.backoffice')
@section('content')


<div class="row">
    <div class="col-sm-12">
        <div class="greybg1 rounded p-4 mb-3">
            <div class="row">
                <div class="col-sm-12">
                    <div class="common_title">
                        <h1>
                            {{ __('addon.import') }}
                            <a href="{{ url('addon')}}" class="m-b-xs w-auto btn-primary btn-sm pull-right">
                                {{ __('product.back') }}
                             </a>
                        </h1>
                    </div>
                </div>
            </div>
        </div>
        {{-- @dd(Hash::make('Abc123!!')) --}}
        <div class="bg-light p-4 rounded">
            <div class=" rounded">
                <div class="">
                    @if (count($errors) > 0)
                    <div class="wrapper-md">
                        <div class="col-md-12">
                            <div class="alert alert-danger">
                                <label>Please fix the errors below to continue</label>
                            </div>
                        </div>
                    </div>
                    @endif
                    @if (\Session::has('success'))
                    <div class="alert alert-success success">
                        <ul>
                            <li>{!! \Session::get('success') !!}</li>
                        </ul>
                    </div>
                    @endif
                    @if (\Session::has('Exception'))
                    <div class="alert alert-danger exception" >
                        <ul>
                            <li>{!! \Session::get('Exception') !!}</li>
                        </ul>
                    </div>
                    @endif
                    <div class="wrapper-md">
                        <div class="col-md-12">
                            <div class="panel panel-body">
                                  <ul class="nav nav-tabs nav-justified" id="myTab" role="tablist">
                                    @php
                                    $index = 0;
                                    @endphp
                                    @foreach(Auth::user()->store->languages as $language)
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
                                    @foreach(Auth::user()->store->languages as $language)
                                    @php
                                    $title = 'title_' . strtolower($language['short_name']);
                                    @endphp
                                    <div id="{{ strtolower($language['name']) }}" class="tab-pane fade show {{ ($index == 0) ? 'active' : '' }}">
                                      <div class="card-body">
                                        @if($index == 0)
                                            @include('catalogue.import.addon-simple-import')
                                            <div class="row">
                                                <div class="col-md-12">
                                                    <h4>Instructions</h4>
                                                    <ol>
                                                        <li>Download Sample Excel Sheet</li>
                                                        <li>Fill products data in excel sheet and upload</li>
                                                        <li>Zip images folder and upload zip file</li>
                                                        <li>For more instructions <a href="{{ url('public/storage/sample/Sample_Instructions.pdf') }}" target="_blank">Click here</a></li>
                                                    </ol>
                                                </div>
                                            </div>
                                        @endif
                                        @if($index != 0 && $index == $index)

                                            @include('catalogue.import.addon_translated_excel_import')
                                        @endif
                                      </div>
                                    </div>
                                    @php
                                    $index++;
                                    @endphp
                                    @endforeach
                                  </div>


                            </div>
                        </div>
                    </div>
                    @if (count($errors) > 0)
                    <div class="wrapper-md">
                        <div class="col-md-12">
                            @php
                            $issues = $errors;
                            $count = sizeof($issues) == 3 ? 4 : (sizeof($issues) == 2 ? 6 : 12)
                            @endphp
                            @foreach ($issues as $key => $value)
                            <div class="col-md-{{ $count }} ">
                                <div class="alert alert-danger">
                                    <label>{{ $key }}</label> <br>
                                    @foreach($value as $line => $rows)
                                    @if(is_array($rows))
                                    <span>{{ $line }}</span><br>
                                    <ul>
                                        @foreach($rows as $row)
                                        <li>{{ $row }}</li>
                                        @endforeach
                                    </ul>
                                    @else
                                    <label>{{ isset($value[0]) ? $value[0] : '' }}</label>
                                    @endif
                                    @endforeach
                                </div>
                            </div>
                            @endforeach
                        </div>
                    </div>
                    @endif
                </div>
            </div>
        </div>
    </div>
</div>

<div id="info_modal" class="modal fade" role="dialog">
    <div class="modal-dialog">
        <div class="modal-content">
            <div class="modal-header">
                <h4 class="modal-title">Stock Information</h4>
                <button type="button" class="close close-modal" data-dismiss="modal">&times;</button>
            </div>
            <div class="modal-body">
                <div class="row">
                    <div class="col-sm-12">
                        <label class="container">
                            <div class="custom-control custom-radio">
                                <input type="radio" form="upload_excel" class="custom-control-input" id="update_stock" name="stock" value="update" checked>
                                <label class="custom-control-label" for="update_stock">
                                    Update Stock if addon already exist <br>eg. Available Stock : 50 , Sheet Stock : 70 , New Stock : 70
                                </label>
                            </div>
                        </label>
                    </div>
                    <br><br><br>
                    <div class="col-sm-12">
                        <label class="container">
                            <div class="custom-control custom-radio">
                                <input type="radio" form="upload_excel" class="custom-control-input" id="add_stock" name="stock" value="add">
                                <label class="custom-control-label" for="add_stock">
                                    Add Stock if addon already exist <br>eg. Available Stock : 50 , Sheet Stock : 70 , New Stock : 120
                                </label>
                            </div>
                        </label>
                    </div>
                </div>
                <div class="clearfix"></div>
            </div>
            <div class="modal-footer">
                <div class="row">
                    <div class="col-md-12">
                        <button required type="submit" form="upload_excel" class="m-b-xs w-auto btn-primary btn-sm">Upload</button>
                        <a type="a" class="m-b-xs w-auto btn-light btn-sm" data-dismiss="modal">Cancel</a>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>

<div id="download_excel" class="modal fade" role="dialog">
    <div class="modal-dialog">
        <div class="modal-content">
            <div class="modal-header">
                <h4 class="modal-title">Download Excel Information</h4>
                <button type="button" class="close close-modal" data-dismiss="modal">&times;</button>
            </div>
            <div class="modal-body">
                <div class="row">
                    <div class="col-sm-12">
                        <label class="container">
                            <label>
                                Are you want to download Sample file for Add-ons uploads ? <br>
                            </label>
                            {{-- <a href="{{ url('storage/sample/Sample.xlsx') }}"  target="_blank" class="m-b-xs w-auto btn-primary btn-sm">Click for Sample Excel</a> --}}
                            <a href="{{ route('export.addons',['export']) }}" class="m-b-xs w-auto btn-primary btn-sm">Click for Sample Excel</a>
                        </label>
                    </div>
                    <br><br><br>
                    <br><br><br>
                    <div class="col-sm-12">
                        <label class="container">
                            <label>
                                Are you want to download the uploaded Add-ons record ?
                            </label>
                                <br>
                            <a href="{{ route('export.addons') }}" class="m-b-xs w-auto btn-primary btn-sm">Click for Uploaded Records</a>
                        </label>
                    </div>
                </div>
                <div class="clearfix"></div>
            </div>
            <div class="modal-footer">
                <div class="row">
                    <div class="col-md-12">
                        <a type="a" class="m-b-xs w-auto btn-light btn-sm" data-dismiss="modal">Cancel</a>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>

@endsection
@section('scripts')
<script type="text/javascript">
    $(document).ready(function() {
        if ($('.error').html() != null || $('.error').html()) {
            toastr.error($('.error').find('ul').find('li').eq(0).html(), 'Error');
        }

        if ($('.success').html() != null || $('.success').html()) {
            toastr.success($('.success').find('ul').find('li').eq(0).html(), 'Success');
        }
        if ($('.danger').html() != null || $('.danger').html()) {
            toastr.error($('.danger').find('ul').find('li').eq(0).html(), 'Exception');
        }
    })
</script>
@endsection
