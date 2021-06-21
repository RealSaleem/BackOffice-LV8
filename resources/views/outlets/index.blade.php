@extends('layouts.backoffice')
@section('content')
    @include('outlets.delete-modal')

    <div class="row">
        <div class="col-sm-12 ">
            <div class="greybg1 rounded p-4 mb-3">
                <div class="row">
                    <div class="col-sm-12  ">

                        <div class="common_title">
                            <h1>
                                {{ __('backoffice.store') }}
                                @can('edit-store')
                                    <a href="{{ route('store.edit',$store->id)}}"
                                       class="m-b-xs w-auto btn-primary btn-sm pull-right">
                                        {{ __('backoffice.edit_store') }}
                                    </a>
                                @endcan
                                @can('add-outlet')
                                    <a href="{{ route('outlets.create') }}"
                                       class="m-b-xs w-auto btn-primary btn-sm pull-right">
                                        {{ __('backoffice.add_outlet') }}
                                    </a>
                                @endcan
                            </h1>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>

    <div class="bg-light rounded">
        <div class="row pl-4 pr-4">
            <div class="col-lg-4 col-md-6 col-sm-12 mb-4">
                <div class="card card-bg">
                    <div class="card-body">
                        <ul class="list-group list-group-flush">
                            <li class="list-group-item d-flex justify-content-between ">
                                <span class="font-weight-bold">{{ __('backoffice.store_name') }} :</span>
                                <span>{{ $store->name }}</span>
                            </li>
                            <li class="list-group-item d-flex justify-content-between ">
                                <span class="font-weight-bold">{{ __('backoffice.currency') }} :</span>
                                <span>{{ $store->default_currency}}</span>
                            </li>
                            <li class="list-group-item d-flex justify-content-between ">
                                <span class="font-weight-bold">{{ __('backoffice.industry') }} :</span>
                                <span>{{ $store->industry->name}}</span>
                            </li>
                        <!-- <li class="list-group-item d-flex justify-content-between ">
                     <span>{{ __('backoffice.min_stock_threshold') }}</span>
                     <span>{{$store->stock_threshold}}</span>
                  </li>
                  <li class="list-group-item d-flex justify-content-between ">
                     <span>{{ __('backoffice.sku_generation') }}</span>
                     <span>{{$store->default_currency}}</span>
                  </li> -->
                            <li class="list-group-item d-flex justify-content-between ">
                                <span class="font-weight-bold">{{ __('backoffice.round_off') }} :</span>
                                <span>{{$store->round_off}} {{ __('backoffice.decimal_places') }}</span>
                            </li>
                        </ul>
                    </div>
                </div>
            </div>

            <div class="col-lg-4 col-md-6 col-sm-12 ">
                <div class="card card-bg">
                    <div class="card-body">
                        <ul class="list-group list-group-flush">
                            <li class="list-group-item d-flex justify-content-between ">
                                <span class="font-weight-bold">{{ __('backoffice.business_owner') }} :</span>
                                <span>{{ $store->contact_name }}</span>
                            </li>
                            <li class="list-group-item d-flex justify-content-between ">
                                <span class="font-weight-bold">{{ __('backoffice.business_email') }} :</span>
                                <span>{{ $store->email }}</span>
                            </li>
                            <li class="list-group-item d-flex justify-content-between ">
                                <span class="font-weight-bold">{{ __('backoffice.business_phone') }} :</span>
                                <span>{{ $store->phone }}</span>
                            </li>
                            <li class="list-group-item d-flex justify-content-between ">&nbsp;
                            <!--  <span class="font-weight-bold">{{ __('backoffice.website') }} :</span>
                     <span> <a href="http://{{ $store->website }}" target="_blank"> {{$store->website}}</a> </span> -->
                            </li>
                        <!-- <li class="list-group-item d-flex justify-content-between ">
                     <span>{{ __('backoffice.sku_generation') }}</span>
                     <span>{{$store->default_currency}}</span>
                  </li>
                  <li class="list-group-item d-flex justify-content-between ">
                     <span>{{ __('backoffice.round_off') }}</span>
                     <span>{{$store->round_off}}</span>
                  </li> -->
                        </ul>
                    </div>
                </div>
            </div>

            <div class="col-lg-4 col-md-12 col-sm-12 mt-4 mt-lg-0">
                <div class="card">
                    <div class="card-body">
                        <div class="row">
                            <div class="col-md-5 text-center mb-4"><img src="{{ $store->store_logo }}" class="w-100"
                                                                        style="width: 65% !important;"></div>
                            <div class="col-md-7">
                                <ul class="list-group list-group-flush">
                                    <li class="list-group-item">
                                        <span>{{ __('backoffice.disclaimer') }}</span>
                                    </li>
                                    <li class="list-group-item">
                                        <p>{{\App\Helpers\Helper::compressString($store->reciept_disclaimer)}}</p>
                                    </li>
                                </ul>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>

    <div class="row p-4 mb-4">

        @foreach($store->outlets as $outlet)
            <div class="col-lg-6 col-sm-12 mt-2 mb-4 ">
                <div class="card">

                    <h5 class="card-header">{{ $outlet->name }}
                        <span class="float-right">
                            @can('edit-outlet')
                                <a href="{{ route('outlets.edit', $outlet->id) }}"
                                   class=" badge m-b-xs w-auto btn-primary btn-sm">
                        {{ __('backoffice.edit_outlets') }}
                     </a>
                            @endcan
                            @can('list-outlet')
                                <a href="{{ route('outlets.show', $outlet->id) }}"
                                   class="badge m-b-xs w-auto btn-primary btn-sm">
                        {{ __('backoffice.view_outlet') }}
                     </a>
                            @endcan
               </span>
                    </h5>
                    <div class="card-body">
                        {{--@dd($outlet)--}}
                        <div class="row">
                            <div class="col-md-6">
                                <div id="map-{{ $outlet->id }}" class="map" data-latitude="{{ $outlet->latitude }}"
                                     data-longitude="{{ $outlet->longitude }}" style="width:100%;height:324px"></div>
                            </div>
                            <div class="col-md-6">
                                <ul class="list-group list-group-flush">
                                    <li class="list-group-item">
                                        <i class="fa fa-envelope"></i>
                                        <span>{{ $outlet->email }}</span>
                                    </li>
                                    <li class="list-group-item">
                                        <i class="fa fa-phone"></i>
                                        <span>{{ $outlet->phone }}</span>
                                    </li>
                                    <li class="list-group-item d-flex justify-content-between ">
                                        <span>{{ __('backoffice.min_order') }}</span>
                                        <span>{{$store->default_currency}} {{ $outlet->min_order_value != null ? $outlet->min_order_value : 0 }}</span>
                                    </li>
                                    <li class="list-group-item d-flex justify-content-between ">
                                        <span>{{ __('backoffice.pickup') }}</span>
                                        <span>{{ $outlet->pickup ?  __('backoffice.yes') : __('backoffice.no') }}</span>
                                    </li>
                                    <li class="list-group-item d-flex justify-content-between ">
                                        <span>{{ __('backoffice.zone_enabled') }}</span>
                                        <span>{{$outlet->enable_zone ?  __('backoffice.yes') : __('backoffice.no') }}</span>
                                    </li>
                                    <li class="list-group-item d-flex justify-content-between ">
                                        <span>{{ __('backoffice.active') }}</span>
                                        <span>{{ $outlet->is_active?  __('backoffice.yes') : __('backoffice.no') }}</span>
                                    </li>
                                    <br>
                                    <li class=" d-flex justify-content-between">

                                        @if($outlet->enable_zone == 1)
                                            <a href="{{ url('delivery_zones', $outlet->id) }}"
                                               class="m-b-xs w-auto btn-primary btn-sm">
                                                {{ __('backoffice.delivery_zones') }}
                                            </a>
                                        @endif

                                        @if($outlet->enable_business_hours == 1)
                                            <a href="{{ url('business_hours', $outlet->id) }}"
                                               class="m-b-xs w-auto btn-primary btn-sm">
                                                {{ __('backoffice.Business_hours') }}
                                            </a>
                                        @endif
                                    </li>
                                </ul>
                            </div>
                        </div>

                    </div>
                </div>
            </div>
        @endforeach
    </div>
    </div>

@endsection

@section('scripts')
    <script type="text/javascript">
        function myMap() {
            $('.map').each(function () {

                let latitude = $(this).data('latitude');
                let longitude = $(this).data('longitude');
                let myCenter = new google.maps.LatLng(latitude, longitude);
                let mapOptions = {
                    center: myCenter,
                    zoom: 17,
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
    <script
        src="https://maps.googleapis.com/maps/api/js?key=AIzaSyANRj-jLIgydGCb1M2dG7WjMsVVpC8xjjs&callback=myMap"></script>
@endsection
