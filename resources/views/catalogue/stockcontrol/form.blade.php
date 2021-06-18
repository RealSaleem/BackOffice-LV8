@extends('layouts.backoffice.default')
@section('content')
<style>
  .select2-container--default .select2-selection--single {
    height: 45px;
  }
</style>
<div class="container-fluid">
  <div class="row">
      <div class="col-sm-12 pl-0 pr-0">
          <div class="greybg1 rounded p-4 mb-3">
              <div class="row">
                  <div class="col-sm-12">
                      <div class="common_title">
                          <h1>
                              Add brands
                              <a href="{{route('backoffice.brands')}}"class="m-b-xs w-auto btn-primary btn-sm pull-right">
                              Back
                              </a>
                          </h1>
                      </div>
                  </div>
              </div>
          </div>
      </div>
      <div class="col-sm-8 pl-0 pr-0">
        <div class="card bg-light mt-3  rounded  border-0">
          <div class="card-body pb-0 pt-0">
            <form method="POST" action="{{ $route }}" id="brands-form">
                {{csrf_field()}}
                <input id="name" type="hidden" form="brands-form" name="name" value="" >
                @if($is_edit)
                    <input name="_method" type="hidden" value="PUT">
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

            <ul class="nav nav-tabs nav-justified" id="myTab" role="tablist">
              @php
                $index = 0;
              @endphp
              @foreach($languages as $language)
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
              @foreach($languages as $language)
                  @php
                    $title = 'title_' . strtolower($language['short_name']);
                  @endphp
                <div id="{{ strtolower($language['name']) }}" class="tab-pane fade show {{ ($index == 0) ? 'active' : '' }}">
                  <div class="card-body">
                      <i class="icon-info pr-1" tabindex="0" data-toggle="popover" data-trigger="focus" title="" data-content="Enter your brands name" data-original-title=></i>
                      <label>@lang('site.title') @if($index == 0)<em>*</em>@endif </label>
                      <input 
                      type="text" 
                      id="title" 
                      name="title_{{ strtolower($language['short_name']) }}" 
                      class="form-control" 
                      form="brands-form" 
                      @if($index == 0) 
                         
                      @endif 
                      placeholder="Add brands Name"
                      value="{{ old($title,$brands[$title]) }}" >
                  </div>
                </div>
                @php
                  $index++;
                @endphp                                
                @endforeach
            </div>
            <hr>
            <div class="card bg-light mt-4  rounded  border-0">
              <div class="card-body">
                <form name="product_images" action="/file-upload" class="dropzone" id="my-awesome-dropzone" enctype="multipart/form-data">
                  <div class="fallback">
                      <input name="file" type="file" style="display: none;">
                  </div>
                </form>
                <div class="hidden">
                  <div id="hidden-images">
                    @php
                        $index = 0;
                    @endphp                                        
                    @foreach($brands['brands_images'] as $image)
                      <input type="hidden" form="brands-form" name="images[{{ $index }}][name]" value="{{ $image['name'] }}" />
                      <input type="hidden" form="brands-form" name="images[{{ $index }}][path]" value="{{ $image['url'] }}" />
                      <input type="hidden" form="brands-form" name="images[{{ $index }}][size]" value="{{ $image['size'] }}" />
                    @php
                        $index++;
                    @endphp
                    @endforeach
                  </div>
                </div>
              </div>
            </div>
            <div class="card bg-light mt-3 rounded border-0">
              <div class="card-body">
                <form>
                  <div class="form-row">
                    <div class="col-sm-6">
                      <label>Parent brands </label>
                      <select class="custom-select select2 form-control" name="parent_id" form="brands-form">
                        <option value="">Select</option>
                        @foreach ($categories as $parent_brands)
                          @php
                            $selected = ($parent_brands['id'] == $brands['parent_id']) ? 'selected': '';
                          @endphp

                          <option value="{{ $parent_brands['id'] }}" {{ $selected }} >
                              {{ $parent_brands->name }}
                          </option>
                        @endforeach
                      </select>
                    </div>
                    <div class="col-sm-6">
                        <label>Sort Order</label>
                        <input type="number" value="{{ old('sort_order', $brands['sort_order'] == null ? 1 : $brands['sort_order'] ) }}" form="brands-form" min="1" class="form-control" name="sort_order">
                    </div>
                  </div>
                </form>
              </div>
            </div>
            <div class="card bg-light mt-3  rounded  border-0">
                <div class="card-body product_displayed">
                    <i class="icon-info pr-1" tabindex="0" data-toggle="popover" data-trigger="focus" title="" data-content="Select where you want to display your products" data-original-title=""></i>
                    <label>Display Product on <em>*</em></label>
                    <div class="container">
                        <div class="row">
                          <div class="col-sm-3">
                            <div class="custom-control custom-checkbox col-md-2">
                              <input type="checkbox" class="custom-control-input" id="customCheck1" form="brands-form" name="pos_display" value="1" 
                              {!! old('pos_display', $brands['pos_display']) ? ' checked' : '' !!}
                              >
                              <label class="custom-control-label text-secondary" for="customCheck1" style="width:80px">Sell Screen</label>
                            </div>
                            </div>
                            <div class="col-sm-3">
                            <div class="custom-control custom-checkbox col-sm-2">
                                <input type="checkbox" class="custom-control-input" id="customCheck2" form="brands-form" name="web_display" value="1" {{ $brands['web_display'] ? 'checked' : '' }}>
                                <label class="custom-control-label text-secondary" for="customCheck2">Ecommerce</label>
                            </div>
                            </div>
                            <div class="col-sm-3">
                            <div class="custom-control custom-checkbox col-sm-2">
                                <input type="checkbox" class="custom-control-input" id="customCheck3" form="brands-form" name="dinein_display" value="1" {{ $brands['dinein_display'] ? 'checked' : '' }}>
                                <label class="custom-control-label text-secondary" for="customCheck3">Dinein</label>
                            </div>
                            </div>
                            <div class="col-sm-3">
                            <div class="custom-control custom-checkbox col-sm-2 pl-0">
                                <input type="checkbox" class="custom-control-input" id="customCheck4" form="brands-form" name="is_featured" value="0" {{ $brands['is_featured'] ? 'checked' : '' }}>
                                <label class="custom-control-label text-secondary" for="customCheck4">Featured</label>
                            </div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
          </div>
        </div>
      </div>
      <div class="col-sm-4 pl-4 ">
        @php
          $index = 0;
        @endphp
        @foreach($languages as $language)
            @php
              $has_seo = 'has_seo_' . strtolower($language['short_name']);
              $meta_title = 'meta_title_' . strtolower($language['short_name']);
              $meta_keywords = 'meta_keywords_' . strtolower($language['short_name']);
              $meta_description = 'meta_description_' . strtolower($language['short_name']);
            @endphp
          <div id="seo-{{ strtolower($language['name']) }}" class="seo-section {{ ($index == 0) ? 'show' : 'd-none' }}">
            <div class="rounded p-4">
                <div class="custom-control custom-checkbox">
                    <input 
                    type="checkbox" 
                    class="custom-control-input has_seo"
                    id="checkbox-{{ strtolower($language['short_name']) }}" 
                    form="brands-form" 
                    name="{{ $has_seo }}"
                    data-lang="{{ strtolower($language['short_name']) }}"
                    {{ $brands[$has_seo] ? 'checked' : '' }}
                    value="1"
                    >
                    <label class="custom-control-label" for="checkbox-{{ strtolower($language['short_name']) }}">@lang('site.SEO_options')
                    ( @lang('site.search_engine') )</label>
                </div>
                <p class="mt-3 text-secondary">
                  @lang('site.add_desc_brands')
                </p>
                <div class="seo-content-{{ strtolower($language['short_name']) }} {{ $brands[$has_seo] ? 'show' : 'd-none' }}"  >
                  <div class="form-group">
                      <label class="btn-block">Meta Title <i class="icon-info pr-1 float-right" tabindex="0" data-toggle="popover" data-trigger="focus" title="Title for SEO" data-content="Enter brands name"></i>
                          <span class="meta_title_span_{{ strtolower($language['short_name']) }} counter-p">(0 of 70 characters)</span>
                      </label>
                      
                      <input
                      type="text" 
                      name="meta_title_{{ strtolower($language['short_name']) }}"
                      id="meta_title_{{ strtolower($language['short_name']) }}"
                      class="form-control" 
                      placeholder="Add your page's meta title" 
                      style="font-style:italic" 
                      data-charcount-enable="true" 
                      data-charcount-textformat="([used] of [max] characters)"
                      data-charcount-position=".meta_title_span_{{ strtolower($language['short_name']) }}" 
                      data-charcount-maxlength="70"
                      maxlength="70"
                      form="brands-form"
                      value="{{ old($meta_title,$brands[$meta_title]) }}"
                      >
                  </div>
                  <div class="form-group">
                      <label class="btn-block">Meta Keywords 
                        <i class="icon-info pr-1 float-right" tabindex="0" data-toggle="popover" data-trigger="focus" title="Short Keyword" data-content="Enter short keyword for your brands"></i>
                        <span class="meta_keywords_span_{{ strtolower($language['short_name']) }} counter-p"></span>   
                      </label>
                      <input
                      type="text" 
                      name="meta_keywords_{{ strtolower($language['short_name']) }}" 
                      id="meta_keywords_{{ strtolower($language['short_name']) }}"
                      class="form-control" 
                      placeholder="Add keywords to your page to help" 
                      style="font-style:italic" 
                      data-charcount-enable="true" 
                      data-charcount-textformat="([used] of [max] characters)"
                      data-charcount-position=".meta_keywords_span_{{ strtolower($language['short_name']) }}" 
                      data-charcount-maxlength="50"
                      maxlength="50" 
                      form="brands-form"
                      value="{{ old($meta_keywords,$brands[$meta_keywords]) }}"
                      >
                  </div>
                  <div class="form-group">
                      <label class="btn-block">Meta Description<i class="icon-info pr-1 float-right" tabindex="0" data-toggle="popover" data-trigger="focus" title=" Short Description" data-content="Enter short description of your product."></i>
                        <span class="meta_description_span_{{ strtolower($language['short_name']) }} counter-p"></span>
                      </label>
                      <textarea
                      class="form-control" 
                      rows="6" 
                      placeholder="Write meta description" 
                      style="font-style:italic"
                      name="meta_description_{{ strtolower($language['short_name']) }}" 
                      id="meta_description_{{ strtolower($language['short_name']) }}"
                      data-charcount-enable="true" 
                      data-charcount-textformat="([used] of [max] characters)"
                      data-charcount-position=".meta_description_span_{{ strtolower($language['short_name']) }}" 
                      data-charcount-maxlength="160"   
                      maxlength="160"
                      form="brands-form"
                      >{{ old($meta_description,$brands[$meta_description]) }}</textarea>
                  </div>
                </div>
            </div>
          </div>
        @php
          $index++;
        @endphp
        @endforeach  
      </div>
  </div>
  <div class="col-md-12 pb-5" style="padding-left: 22px;">
    <button type="submit" form="brands-form" class="btn btn-primary">Save</button>
    <a href="{{ route('brands.index')}}" class="btn btn-light" >Cancel</a>
  </div>
</div>
@endsection

@section('scripts')
<script src="{{CustomUrl::asset('js/cropper.min.js') }}"></script>
<script src="{{CustomUrl::asset('js/dropzone.js') }}"></script>
<script src="{{CustomUrl::asset('js/charactercount/jquery.character-counter.js') }}"></script>

<script type="text/javascript">
  var image_upload_path = site_url('product/image/upload');
  var form_id = 'brands-form';
  var p_images = JSON.parse('{!! json_encode($brands["brands_images"]) !!}');
  console.log(p_images);
  $(function(){
      $('.tab-section').click(function(){
          let lang = $(this).data('lang');
          console.log(lang);
          $('.seo-section').removeClass('show');
          $('.seo-section').addClass('d-none');
          $(`#seo-${lang}`).removeClass('d-none').addClass('show');
      });
  });

  $(function(){
    $('.has_seo').click(function(){
        let lang = $(this).data('lang');
        if($(this).is(':checked')){
            $(`.seo-content-${lang}`).removeClass('d-none').addClass('show');
        }else{
            $(`.seo-content-${lang}`).addClass('d-none').removeClass('show');
        }
    });
  });
    
  $(document).ready(function(){
      $('[data-toggle="tooltip"]').tooltip(); 
      $('.select2').select2();
  });

  $(function(){
    $('#brands-form').submit(function(){

      var name = $('#title').val();
      $('#name').val(name);
        
      if($(this)[0].checkValidity()) {
        $.ajax({
            url: $(this).attr('action'),
            type: $(this).attr('method'),
            data: $(this).serialize(),
            success: function (response) {
                if(response.IsValid){
                    toastr.success(response.Message,'Success');
                    setTimeout(()=>{
                        window.location.href = site_url('backoffice/brands');
                    },3000);
                }else{
                    if (response.Errors.lenght > 0) {
                        response.Errors.map((error) => {
                        toastr.error(error,'Error');
                        });
                    }else{
                        toastr.error(response.Errors[0],'Error')
                    }
                }
            }
        });
      }
      return false;
    });
  });
</script>
<script src="{{ CustomUrl::asset('js/my-dropzone.js') }}" type="text/javascript"></script>

@endsection