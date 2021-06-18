@extends('layouts.backoffice')
@section('content')


<div class="row">
   <div class="col-sm-12">
      <div class="greybg1 rounded p-4 mb-3">
         <div class="row">
            <div class="col-sm-12">
              <div class="common_title">
                <h1>
                  @if($is_edit)
                    {{$model->name}}
                  @else
                   {{__('sales-rep.add_represent')}}
                  @endif
                   <a href="{{ route('sales-rep.index') }}"class="m-b-xs w-auto btn-primary btn-sm pull-right">
                      {{ __('site.back') }}
                  </a>
                </h1>
              </div>
            </div>
         </div>
      </div>
      <div class="bg-light p-4 rounded">
         <div class=" rounded" >
            <div class="row">
               <div class="col-md-12">
                  <div class="bg-light rounded">
                    <div class="wrapper-md container">
                      <div class="row">
                        <div class="col-md-8">
                            <div class="form-group">
                              <label>{{__('sales-rep.seller_name')}}</label>
                               <span style="color: red">*</span>
                              <input 
                                type="text" 
                                id="title"
                                name="name" 
                                class="form-control" 
                                placeholder="Seller Name" style="font-style:italic" 
                                type="text"
                                form="sales-form"
                                value="{{$model->name}}" required="">
                            </div>
                        </div>
                        <div class="col-md-4">
                          <div style="height: 23px"></div>
                            <div class="pure-checkbox">
                              <input id="checkbox" form="sales-form" name="active" type="checkbox" {{$model->is_active == 1 ? 'checked' : ''}} 
                               >
                              <label for="checkbox">{{__('sales-rep.active')}}</label>
                            </div>
                        </div>
                      </div>
                      <div class="row">
                        <div class="col-md-8" style="padding-right: 5px;">
                          <div class="tab-content">
                            <div class="panel panel-body">
                              <div class="row">
                                <div class="col-md-6">
                                  <div class="form-group">
                                    <label>{{__('sales-rep.code')}}</label>
                                     <span style="color: red">*</span>
                                    <input 
                                      type="text" 
                                      id="title"
                                      name="code" 
                                      class="form-control" 
                                      placeholder="Code" style="font-style:italic" 
                                      type="text"
                                      form="sales-form"
                                      value="{{$model->code}}" required="">
                                  </div>
                                </div>
                                <div class="col-md-6">
                                  <div class="form-group">
                                    <label>{{__('sales-rep.contact')}}</label>
                                     <span style="color: red">*</span>
                                    <input 
                                      type="number" 
                                      id="title"
                                      name="phone" 
                                      class="form-control" 
                                      placeholder="Contact No." style="font-style:italic" 
                                      type="text"
                                      form="sales-form"
                                      value="{{$model->phone}}" required="">
                                  </div>
                                </div>
                                <div class="col-md-6">
                                  <div class="form-group">
                                    <label>{{__('sales-rep.national_id')}}</label>
                                     <span style="color: red">*</span>
                                    <input 
                                      type="text" 
                                      id="title"
                                      name="national_id" 
                                      class="form-control" 
                                      placeholder="National ID#" style="font-style:italic" 
                                      type="text"
                                      form="sales-form"
                                      value="{{$model->national_id}}" required="">
                                  </div>
                                </div>
                                <div class="col-md-6">
                                  <div class="form-group">
                                    <label>{{__('sales-rep.salary')}}</label>
                                    <input 
                                      type="text" 
                                      id="title"
                                      name="salary" 
                                      class="form-control" 
                                      placeholder="Salary" style="font-style:italic" 
                                      type="text"
                                      form="sales-form"
                                      value="{{$model->salary}}">
                                  </div>
                                </div>
                                <div class="col-md-6">
                                  <div class="form-group">
                                    <label>{{__('sales-rep.commission')}}</label>
                                    <input 
                                      step="1"
                                      type="number" 
                                      id="title"
                                      min="1"
                                      max="100" 
                                      name="commission" 
                                      class="form-control" 
                                      placeholder="Commission" style="font-style:italic" 
                                      type="text"
                                      form="sales-form"
                                      value="{{$model->commission}}">
                                  </div>
                                </div>
                              </div>
                            </div>
                          </div>
                        </div>
                      </div>
                      <div class="row">
                        <div class="col-md-12 pt-3">
                          <button type="submit" form="sales-form" class="btn btn-primary">{{__('sales-rep.save')}}</button>
                          <a href="{{ route('sales-rep.index') }}" type="button" class="btn btn-light">{{__('sales-rep.cancel')}}</a>
                        </div>
                      </div>  
                    </div>
                  </div>
               </div>
            </div>
         </div>
      </div>
   </div>
@if($is_edit)
<form action="{{$route}}" method="POST" id="sales-form">
{{csrf_field()}}
<input name="_method" value="PUT" type="hidden">                   
</form>
@else
<form action="{{$route}}" method="POST" id="sales-form">
{{csrf_field()}}
</form>
@endif
</div>

@endsection
@section('scripts')
@endsection