@extends('layouts.backoffice')
@section('content')
    <div class="row">
        <div class="col-sm-12">
            <div class="greybg1 rounded p-4 mb-3">
                <div class="row">
                    <div class="col-sm-12">
                        <div class="common_title">
                            <h1>
                                {{ __('backoffice.plugins') }}
                            </h1>
                        </div>
                    </div>
                </div>
            </div>

            <div class="col-sm-12">
                <div class=" rounded">
                    <div class="row">
                        <!-- <div class="ml-4 mt-4">
                            <form class="example" action="action_page.php">
                                <input type="text" placeholder="Search.." id="plugSearch" name="search">
                                <button class="btn-primary btn-sm">Search</button>
                            </form>
                        </div> -->
                        <div class="col-md-12 mt-4">
                            <div class="bg-light rounded">
                                <div class=" table-responsive">
                                    <table class="table table-striped" id="plugins-table">
                                        <thead>
                                        <tr>
                                            <th>{{ __('backoffice.name') }} </th>
                                            <th>{{ __('backoffice.action') }} </th>
                                        </tr>
                                        </thead>
                                        <tbody>
                                        @if(count($plugins) > 0)
                                            @foreach($plugins as $plugin)
                                                <tr>
                                                    <td>{{$plugin->plugin->name}}</td>
                                                    <td>
                                                        <div class="row">
                                                        @if(
                                                            $plugin->plugin->slug != 'delivery-zone'  && $plugin->plugin->slug != 'guest-checkout' && $plugin->plugin->slug != 'allow_pickup'
                                                        )
                                                            <div class="col-md-1">
                                                                <a href="{{$plugin->plugin->slug}}" class="badge btn-primary">
                                                                    {{ __('backoffice.view') }}
                                                                </a>
                                                            </div>
                                                        @endif
                                                        @if($plugin->active == 0)
                                                            <div class="col-md-1">
                                                                <a href="javascript:;" data-id="{{$plugin->id}}" onclick="instalPlugin({{$plugin->id}})" onchange="InstalPlug({{$plugin->id}})" class="badge btn-primary loader install">
                                                                {{ __('backoffice.install') }}
                                                            </a>
                                                            </div>
                                                        @endif
                                                        </div>
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
    <script type="text/javascript">
        pluginsTable = $('#plugins-table').DataTable({
            "responsive": {
                "details": {
                    "type": "column"
                }
            },
        })
        function instalPlugin(id) {
            $.ajax({
                url: site_url('plugins/toggle'),
                type: 'POST',
                data: {id: id},
                success: function (response) {
                    if (response.IsValid) {
                        toastr.success(response.message, 'Success');
                        window.location.reload();
                    } else {
                        toastr.error(response.message, 'Error');
                    }
                }
            })
        };
    </script>
@endsection
