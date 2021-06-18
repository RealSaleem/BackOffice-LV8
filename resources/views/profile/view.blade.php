@extends('layouts.backoffice')
@section('content')

    <div class="row">
        <div class="col-sm-12 ">
            <div class="greybg1 rounded p-4 mb-3">
                <div class="row">
                    <div class="col-sm-12  ">

                        <div class="common_title">
                            <h1>
                                {{ __('backoffice.user_profile') }}
                                <a href="{{route('edit_profile')}}" class="m-b-xs w-auto btn-primary btn-sm pull-right">
                                    {{ __('backoffice.edit') }}
                                </a>
                            </h1>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>

    <div class="bg-light rounded">
        <div class="row">
            <div class="col-sm-4 ">
                <div class="">
                    <div class="card-body">
                        <ul class="list-group list-group-flush">
                            <li class="list-group-item d-flex justify-content-between ">
                                <span>{{ __('backoffice.user_name') }}</span>

                                <span>{{ $user['user']->name }}</span>
                            </li>
                            <li class="list-group-item d-flex justify-content-between ">
                                <span>{{ __('backoffice.email') }}</span>
                                <span>{{ $user['user']->email }}</span>
                            </li>
                            <li class="list-group-item d-flex justify-content-between ">
                                <span>{{ __('backoffice.mobile') }}</span>
                                <span>{{$user['user']->mobile  }}</span>
                            </li>

                        </ul>
                    </div>
                </div>
            </div>


            <div class="col-sm-4 ">
                <div class="">
                    <div class="card-body">
                        <ul class="list-group list-group-flush">
                            <li class="list-group-item d-flex justify-content-between ">
                                <span>{{ __('backoffice.role') }}</span>
                                <span>{{$user['role']->display_name  }}</span>
                            </li>
                            <li class="list-group-item d-flex justify-content-between ">
                                <span>{{ __('backoffice.outlet') }}</span>
                                <ul>
                                    @foreach($user['outlets'] as $outlet)
                                        <li><span>{{$outlet->name  }}</span></li>

                                    @endforeach

                                </ul>

                            </li>

                        </ul>
                    </div>

                </div>
            </div>


            <div class="col-sm-4">
                <div class="">
                    <div style="text-align: center">
                        @if(Auth::user()->user_image != null)
                            <img src="{{ CustomUrl::asset(Auth::user()->user_image) }}"
                                 class="img-responsive p-2 biglogo">
                        @else
                            <img src="{{ CustomUrl::asset('backoffice/assets/img/dummy-user.png') }}"
                                 class="img-responsive p-2 biglogo" style="    width: 40%;">
                        @endif
                    </div>
                </div>
            </div>
        </div>


@endsection



