@extends('layouts.backoffice')
@section('content')
    <style>
        .select2-container--default .select2-selection--single {
            height: 45px;
        }
    </style>

    <form method="POST" action="{{ $model->route }}" id="permission-form">
        {{csrf_field()}}
        @if($model->edit_mode)
            <input id="id" type="hidden" form="permission-form" name="id" value="{{$model->user['id']}}">

        @endif


    </form>
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
                                <h1>
                                    {{ $model->title }}
                                    <a href="{{route('users.index')}}"
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

    <div class="bg-light p-4 rounded">
        <div class="rounded">
            <div class="row">
                <div class="col-lg-12 mb-3">
                    <div class="row">
                        <div class="col-md-4 mb-3 rounded ">
                            <i class="" tabindex="0" data-toggle="tooltip" data-original-title="Required"></i>
                            <label for="">{{__('backoffice.name')}}</label>
                            <span style="color: red">*</span>
                            <input type="text" name="name" id="name" form="permission-form"
                                   value="{{old('name')}}" class="form-control" placeholder="Name"
                                   required>
                        </div>
                        <div class="col-md-4 mb-3 rounded ">
                            <i class="" tabindex="0" data-toggle="tooltip" data-original-title="Required"></i>
                            <label for="">{{__('backoffice.slug')}}</label>
                            <span style="color: red">*</span>
                            <input type="text" name="slug" id="slug" form="permission-form"
                                   value="{{old('name')}}" class="form-control" placeholder="Name"
                                   required>
                        </div>

                        <div class="col-md-4 mb-3 rounded  ">
                            <label for="">{{ __('backoffice.module') }}</label>
                            <span style="color: red">*</span>
                            <select class=" select2 form-control entity" id="entity"  name="entity" form="permission-form" required>
                                    <option value="o">Select</option>
                                @foreach($entity as $enty)
                                    <option value="{{$enty->name}}">{{$enty->name}}</option>
                                @endforeach
                            </select>
                        </div>


                    </div>
                    <div class="row mt-3 ">
                        <div class="col-md-4 mb-3 rounded  ">
                            @if($model->edit_mode)

                            @endif
                        </div>
                    </div>

                </div>

            </div>
        </div>
        <div class="col-md-12 pb-5 ml-md-2">
            <button type="submit" form="permission-form"
                    class="btn btn-primary submit">{{ $model->button_title }}</button>
            <a href="{{ route('users.index')}}" class="btn btn-light">{{ __('backoffice.cancel') }}</a>
        </div>
    </div>


@endsection
@section('scripts')
    <script>
        $(function () {
           $('#entity').change(function (){
                   $('#name').val("");
                   let entity = $(this).val();
                   $('#slug').val(entity);
           });
           $('#name').change(function (){
               let entity = $('#entity').val();
if(entity != 0){
    let slug = $('#slug').val();
    let name = $(this).val();
    $('#slug').val(name+'-'+slug)
    console.log(name+slug+entity);
}else{
     $('#name').val("");
    $('#entity').focus();
    toastr.error('Kindly select Entity First', 'Error');
}



           });


        });
    </script>
    <script>




        $(function () {

            $('#permission-form').submit(function () {

                    $.ajax({
                        url: $(this).attr('action'),
                        type: $(this).attr('method'),
                        data: $(this).serialize(),
                        success: function (response) {
                            if (response.IsValid) {
                                toastr.success(response.Message, 'Success');
                                setTimeout(() => {
                                    window.location.href = site_url('usermanagement/permission');
                                }, 1000);
                            } else {
                                if (Object.keys(response.Errors).length > 1) {
                                    response.Errors.map((error) => {
                                        toastr.error(error, 'Error');
                                    });
                                } else {
                                    toastr.error(response.Errors, 'Error')
                                }
                            }
                        }
                    });

                return false;
            });
        });
    </script>



    @include('partials.backoffice.JSintel-plugin')
@endsection
