@extends('layouts.backoffice')
@section('content')
    <!-- content -->
    <div class="row">
        <div class="col-sm-12">
            <div class="greybg1 rounded p-4 mb-3">
                <div class="row">
                    <div class="col-sm-12">
                        <div class="common_title">
                            <h1>
                                {{ __('backoffice.apps') }}
                            </h1>
                        </div>
                    </div>
                </div>
            </div>
            <div class="col-sm-12">
                <div class=" rounded">
                    <div class="row">
                        <div class="col-md-12 mt-4">
                            <div class="bg-light rounded">
                                <div class=" table-responsive">
                                    <table class="table table-striped" id="app-store-table">
                                        <thead>
                                        <tr>
                                            <th>{{ __('backoffice.name') }} </th>
                                            <th>{{ __('backoffice.action') }} </th>
                                        </tr>
                                        </thead>
                                        <tbody>
                                        @if(isset($apps) && sizeof($apps) > 0)
                                            @foreach($apps as $app)
                                                <tr>
                                                    <td>{{$app->app->name}}</td>
                                                    <td>
                                                        @if($app->active == 0)
                                                            <button href="javascript:;" data-id="{{$app->id}}"
                                                                    class="badge btn-primary loader install">
                                                                {{ __('backoffice.activate') }}
                                                            </button>
                                                        @endif
                                                    </td>
                                                </tr>
                                            @endforeach
                                        @endif
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
@endsection
@section('scripts')
    <script>
        $(document).ready(function () {
            appStoreTable = $('#app-store-table').DataTable({
                "responsive": {
                    "details": {
                        "type": "column"
                    }
                },
            }),
            $('.install').on('click', function () {
                let id = $(this).data('id');
                $.ajax({
                    url: "{{ route('app.toggle') }}",
                    type: 'POST',
                    data: {id: id},
                    success: function (data) {
                        if (data.response == true) {
                            toastr.success(data.message, 'Success');
                            setTimeout(() => {
                                window.location.reload();
                            }, 1000);
                        } else {
                            toastr.warning(data.message, 'Error');
                        }
                    }
                })
            });
        });
    </script>
@endsection
