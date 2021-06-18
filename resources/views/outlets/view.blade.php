@extends('layouts.backoffice')
@section('content')
    <!-- content -->

    <div class="row">
        <div class="col-sm-12 mb-5 ">
            <div class="greybg1 rounded p-4 mb-3">
                <div class="row">
                    <div class="col-sm-12  ">


                        <div class="common_title">

                            <h1>
                                {{ __('backoffice.view_outlet') }}

                                <a href="{{ route('outlets.index') }}"
                                   class="m-b-xs w-auto btn-primary btn-sm pull-right">
                                    {{ __('backoffice.back') }}
                                </a>
                                <a href="{{ route('outlets.edit',$model->outlet['id']) }}"
                                   class="m-b-xs w-auto btn-primary btn-sm pull-right">
                                    {{ __('backoffice.edit_outlets') }}
                                </a>
                            </h1>
                        </div>
                    </div>
                </div>
            </div>

            <style>

                .outlet_img {
                    width: 77%;
                    margin: 8px;
                }
            </style>

            <div class="bg-light rounded">
                <div class="row">
                    <div class="col-sm-4">
                        <div class="rounded p-4 mt-3">
                            <div class="card p-3 rounded">
                                <h4>{{ __('backoffice.detail')}}</h4>
                                <div class="card-body">
                                    <table class="table ">
                                        <thead>
                                        <tr>
                                            <td>{{ __('backoffice.outlet_name') }}</td>
                                            <td>{{$model->outlet['name']  }}</td>
                                        </tr>
                                        <tr>
                                            <td>{{ __('backoffice.outlet_email') }}</td>
                                            <td>{{$model->outlet['email']  }}</td>
                                        </tr>
                                        <tr>
                                            <td>{{ __('backoffice.outlet_phone') }}</td>
                                            <td>{{$model->outlet['phone']  }}</td>
                                        </tr>
                                        <tr >
                                            <td>{{ __('backoffice.min_order_amount') }}</td>
                                            <td>{{ $model->outlet['min_order_value']  ? $model->outlet['min_order_value'] : '000.00'}}</td>
                                        </tr>
                                        </thead>
                                    </table>
                                </div>
                            </div>
                        </div>
                    </div>
                    <div class="col-sm-4">
                        <div class="rounded p-4 mt-3">
                            <div class="card p-3 rounded" style="height: 296px;">
                                <h4>{{__('backoffice.action')}}</h4>
                                <div class="card-body">
                                    <table class="table ">
                                        <thead>

                                    <tr >
                                        <td>{{ __('backoffice.active') }}</td>
                                        <td id="active">{{ $model->outlet['is_active'] == 1 ? 'YES' : 'NO'}}</td>
                                    </tr>
                                    <tr >
                                        <td>{{ __('backoffice.enable_delivery_zone') }}</td>
                                        <td id="enable_zone"> {{ $model->outlet['enable_zone'] == 1 ? 'YES' : 'NO'}}</td>
                                    </tr>
                                    <tr >
                                        <td>{{ __('backoffice.allow_pickup') }}</td>
                                        <td id="pickup">{{ $model->outlet['pickup'] == 1 ? 'YES' : 'NO'}}</td>
                                    </tr>
                                    <tr >
                                        <td>{{ __('backoffice.enable_Business_hours') }}</td>
                                        <td id="enable_business_hours"> {{ $model->outlet['enable_business_hours'] == 1 ? 'YES' : 'NO'}}</td>
                                    </tr>


                                        </thead>
                                    </table>
                                </div>
                        </div>
                        </div>
                    </div>
                    <div class="col-sm-4">
                        <div class="rounded p-4 mt-3">
                            <div class="card p-3 rounded " style="height: 278px;">
                                	<span class="btn-block text-center">
                                    @if($model->outlet['images'] != null)
                                            <img src="{{ CustomUrl::asset($model->outlet['images']) }}"
                                                 class="img-responsive  outlet_img">
                                        @else
                                            <img
                                                src="http://{{$_SERVER["HTTP_HOST"]}}/uploads/outlets/upload_outlet.jpg"
                                                class="img-responsive outlet_img">
                                        @endif

					            </span>
                                <div class="inline text-center mt-4">
                                    <span>
                                        @if($model->outlet['facebook'])
                                            <a href="{{$model->outlet['facebook']}}" class="mr-3"> <i class="fa fa-facebook "
                                                                         style="    font-size: 33px;"> </i></a>
                                        @endif
                                        @if($model->outlet['snap_chat'])
                                            <a href="{{$model->outlet['snap_chat']}}" class="mr-3"> <i class="fa fa-snapchat"
                                                                         style="font-size: 33px;"> </i></a>
                                        @endif
                                        @if($model->outlet['twitter'])

                                            <a href="{{ $model->outlet['twitter'] }}" class="mr-3"> <i class="fa fa-twitter"
                                                                         style="font-size: 33px;"> </i></a>
                                        @endif
                                        @if($model->outlet['instagram'])
                                            <a href="{{$model->outlet['instagram']}}" class="mr-3"> <i class="fa fa-instagram"
                                                                         style="font-size: 33px;"> </i></a>
                                        @endif
                                    </span>

                                </div>


                            </div>
                        </div>

                    </div>
                </div>
                <div class="row">
                    <div class="col-sm-6  ">
                        <div class="rounded p-4">

                            <div class="card p-3 rounded">
                                <h4>{{ __('backoffice.outlet_address')}}</h4>
                                <div class="card-body">
                                    <span>{{$model->outlet['street_1']  }}</span>
                                    <div id="map-{{ $model->outlet['id'] }}" class="map" data-latitude="{{$model->outlet['latitude'] }}"
                                         data-longitude="{{$model->outlet['longitude']}}" style="width:100%;height:324px"></div>

                                </div>
                            </div>
                        </div>
                    </div>
                    <div class="col-sm-6  ">
                        <div class="rounded p-4">

                            <div class="card p-3 rounded">
                                <h4>{{ __('backoffice.registers') }}</h4>
                                <div class="card-body">
                                    <ol class="list-group list-group-flush">
                                        @foreach($model->outlet['registers'] as $reg)
                                            <li class="list-group-item d-flex justify-content-between ">
                                                <span>{{$reg['name']}}</span>
                                            </li>
                                        @endforeach
                                    </ol>
                                </div>
                            </div>
                        </div>
                    </div>


                </div>


                @endsection

                @section('scripts')
                    @include('partials.backoffice.google_map')
                    <script type="text/javascript">
                        function myMap() {
                            $('.map').each(function () {

                                let latitude = $(this).data('latitude');
                                let longitude = $(this).data('longitude');

                                var myCenter = new google.maps.LatLng(latitude, longitude);
                                let mapOptions = {
                                    center: myCenter,
                                    zoom: 13,
                                    disableDefaultUI: true
                                };
                                let mapCanvas = document.getElementById($(this).attr('id'));
                                let map = new google.maps.Map(mapCanvas, mapOptions);
                                let marker = new google.maps.Marker({
                                    position: myCenter
                                });

                                marker.setMap(map);
                            });

                        }
                    </script>
                    <script src="https://maps.googleapis.com/maps/api/js?key=AIzaSyANRj-jLIgydGCb1M2dG7WjMsVVpC8xjjs&callback=myMap"></script>
@endsection
