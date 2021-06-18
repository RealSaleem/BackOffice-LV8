@extends('layouts.backoffice')
@section('content')
<!-- content -->




<div class="row" > 
    <div class="col-sm-12">
        <div class="greybg1 rounded p-4">
            <div class="row">
                <!-- Inner Div -->
                <div class="col-sm-12">
                    <div class="common_title">
                        <h1>@lang('site.general_setup')
                            <a href="{{route('outlets.index')}}"class="m-b-xs w-auto btn-primary btn-sm pull-right">
                                {{ __('payment.back') }}
                            </a>
                        </h1>
                    </div>
                </div>
            </div>
              <p style="text-align: center;">Fields marked with <span style="color: red;">*</span> are mandatory to fill</p>
            <form action="{{route('outlets.edit',$store->id)}}" method="post" id="store_form">

            {{ csrf_field() }}
            @method('PUT')
            <div class="card bg-light mt-3 pl-3 pr-3  rounded  border-0">
                <div class="row">
                    <div class="card-body">
                        <div class="row">
                            <div class="col-sm-12">
                                <div class="row">
                                    <div class="col-md-4">
                                        <span style="color: red">*</span>
                                        <label>@lang('site.store_name')</label>&nbsp;
                                        <a href="#" data-toggle="tooltip" data-placement="right" title="@lang('site.enter_business')!"><i class="fa fa-info-circle" style="font-size:20px;color:grey"></i></a>

                                        <input name="name" class="form-control rounded" value="{{$store->name}}" type="text" placeholder="@lang('site.super_market')" required autofocus>
                                    </div>
                                    <div class="col-md-4">
                                        <span style="color: red">*</span>
                                        <label>@lang('site.industry_type')</label>
                                        <a href="#" data-toggle="tooltip" data-placement="bottom" title="@lang('site.note')."><i class="fa fa-info-circle" style="font-size:20px;color:grey"></i></a>
                                        <select class="form-control rounded select2 " name="industry_id" >
                                        @foreach ($industries as $industry)
                                            <option value="{{$store->industry->name}}"  
                                                {{ isset($store->industry_id) && $store->industry_id == $industry->id ?'selected' : '' }}
                                                >{{$industry->name}}</option>
                                        @endforeach
                                        </select>
                                    </div>
                                    <div class="col-md-4">
                                        <span style="color: red">*</span>
                                        <label>@lang('site.default_currency')</label>&nbsp;
                                        <a href="#" data-toggle="tooltip" data-placement="bottom" title="@lang('site.currency')">
                                            <i class="fa fa-info-circle" style="font-size:20px;color:grey"></i></a>
                                            @if($is_edit)
                                        <input type="hidden" name="default_currency" value="{{$store->default_currency}}">
                                        @endif
                                        <select class="form-control rounded select2" name="default_currency"  {{ isset($store->default_currency) && $store->default_currency != null  ? 'disabled' : '' }}  >
                                            @foreach ($currencies as $currency)
                                                <option value="{{$store->default_currency}}"  
                                                    {{ isset($store->default_currency) && $store->default_currency == $currency->currency_symbol ?'selected disabled' : '' }} 

                                                    >{{$currency->currency_name}}</option>
                                            @endforeach
                                            </select>
                                            
                                    </div>
                                    &nbsp;
                                    <div class="col-md-12">
                                        <div class="hide" id="moreless2">
                                            <div class="row">
                                                <div class="col-md-4">
                                                    <label>@lang('site.SKU_generation')</label>
                                                    <a href="#" data-toggle="tooltip" data-placement="right" title="
                                                    @lang('site.SKU_gen')">
                                                        <i class="fa fa-info-circle" style="font-size:20px;color:grey"></i></a>
                                                    <select name="sku_generation" style="width:100%;" class="form-control m-b ">
                                                        <option value="1" {{ isset($store->sku_generation) && $store->sku_generation == 1 ?'selected' : '' }} >@lang('site.generate_by_sequence_number')</option>
                                                        <option value="0" {{ isset($store->sku_generation) && $store->sku_generation == 0 ?'selected' : '' }}>@lang('site.generate_by_name')</option>
                                                    </select>
                                                </div>
                                                <div class="col-md-4">
                                                    <label>@lang('site.sku_sequence_number')</label>
                                                    <a href="#" data-toggle="tooltip" data-placement="right" title="@lang('site.sku_seq')">
                                                        <i class="fa fa-info-circle" style="font-size:20px;color:grey"></i></a>
                                                    <input name="current_sequence_number" value="{{$store->current_sequence_number}}" class="form-control rounded" placeholder=" 1000" type="text">
                                                </div>
                                                <div class="col-md-4">
                                                    <label>@lang('site.stock_min_threshold_value')&nbsp;
                                                        <a href="#" data-toggle="tooltip" data-placement="right" title="@lang('site.stock_min')">
                                                            <i class="fa fa-info-circle" style="font-size:20px;color:grey"></i></a>
                                                    </label>
                                                    <input name="stock_threshold" value="{{$store->stock_threshold}}"  class="form-control rounded" placeholder=" 1000" type="text">
                                                </div>
                                            </div>
                                        </div>
                                    &nbsp;

                                            <div class="row">
                                            <div class="col-md-4">
                                                <label>@lang('site.amount_round_to')</label>
                                                <a href="#" data-toggle="tooltip" data-placement="right" title="@lang('site.amount_round')">
                                                    <i class="fa fa-info-circle" style="font-size:20px;color:grey"></i></a>
                                                <select id="physical_country" name="round_off" style="width:100%;" class="form-control m-b " >
                                                    <option value="0"  {{ isset($store->round_off) && $store->round_off == 0 ?'selected' : '' }} >@lang('site.select_decimal_place')</option>
                                                    <option value="1" {{ isset($store->round_off) && $store->round_off == 1 ?'selected' : '' }}  >1 @lang('site.decimal_place')</option>
                                                    <option value="2"  {{ isset($store->round_off) && $store->round_off == 2 ?'selected' : '' }} >2 @lang('site.decimal_place')</option>
                                                    <option value="3" {{ isset($store->round_off) && $store->round_off == 3 ?'selected' : '' }}  >3 @lang('site.decimal_place')</option>
                                                    <option value="4" {{ isset($store->round_off) && $store->round_off == 4 ?'selected' : '' }}  >4 @lang('site.decimal_place')</option>
                                                </select>
                                            </div>
                                            <div class="col-md-4">
                                                <span style="color: red">*</span>
                                                <label>Languages</label>
                                                <a href="#" data-toggle="tooltip" data-placement="bottom" title="Languages"><i class="fa fa-info-circle" style="font-size:20px;color:grey"></i></a>
                                                <select class=" form-control rounded  select2 " multiple name="language_ids[]"  searchable="Search here.." required>
                                                    @foreach($languages as $language)

                                                        <option value="{{$language['id']}}" {{ in_array($language['id'], $store_languages,  true) ? 'selected' : ''}}>{{$language['name']}}</option>
                                                    @endforeach
                                                </select>
                                            </div>
                                         </div>
                                    </div>
                                        
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
            <div class="card bg-light mt-3 pl-3 pr-3  rounded  border-0">
                <div class="row">
                    <div class="card-body">
                        <div class="row">
                            <div class="col-sm-12">
                                <div class="row">
                                   <div class="col-md-4">
                                        <span style="color: red">*</span>
                                        <label>@lang('site.contact_name')</label>
                                        <a href="#" data-toggle="tooltip" data-placement="right" 
                                        title="@lang('site.owner_name')">
                                            <i class="fa fa-info-circle" style="font-size:20px;color:grey"></i></a>
                                        <input name="contact_name" value="{{$store->contact_name}}" class="form-control rounded" placeholder="@lang('site.name')" type="text" required>
                                    </div>
                                    <div class="col-md-4">
                                        <span style="color: red">*</span>
                                        <label>@lang('site.email')</label>
                                        <a href="#" data-toggle="tooltip" data-placement="right" 
                                        title="@lang('site.owner_email')">
                                            <i class="fa fa-info-circle" style="font-size:20px;color:grey"></i></a>
                                        <input name="email" value="{{$store->email}}" class="form-control rounded" placeholder="john@domain-name.com" type="email" required>
                                    </div>
                                    <div class="col-md-4">
                                        <span style="color: red">*</span>
                                        <label>@lang('site.phone')</label>
                                        <a href="#" data-toggle="tooltip" data-placement="right" title="
                                        @lang('site.owner_phone')">
                                            <i class="fa fa-info-circle" style="font-size:20px;color:grey"></i></a>
                                        <div>
                                            <input id="phone" name="phone" type="tel" name="phone" value="{{$store->phone}}" class="form-control rounded">
                                            <span id="valid-msg" class="hide valid-msg" hidden>✓ Valid </span>
                                            <span id="error-msg" class="hide error-msg"></span>
                                        </div>

                                    </div>
                                        
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
            <div class="card bg-light mt-3 pl-3 pr-3  rounded  border-0">
                <div class="row">
                    <div class="card-body">
                        <div class="row">
                            <div class="col-sm-12">
                                <div class="row">
                                   <div class="col-md-8">
                                        <span style="color: red">*</span>
                                        <label>@lang('site.reciept_disclaimer') </label>
                                        <a href="#" data-toggle="tooltip" data-placement="right" 
                                        title="@lang('site.receipt_dis')">
                                            <i class="fa fa-info-circle" style="font-size:20px;color:grey"></i></a>
                                        <textarea rows="6" name="reciept_disclaimer"  class="form-control rounded " type="text" required style="    min-height: 87%;">{{$store->reciept_disclaimer}}</textarea>
                                    </div>
                                    <input type="hidden"  name="store_logo" value="{{$store->store_logo}}" id="path">
                                    </form>
                                    <div class="col-md-4">
                                            <label>@lang('site.store_logo')</label>
                                                <form action="/file-upload" name="images" class="dropzone" id="my-awesome-dropzone" required style="min-height: 87%;" ></form>

                                           <!--  <a href="#" data-toggle="tooltip" data-placement="right" 
                                            title="@lang('site.logo_select')!">
                                                <i class="fa fa-info-circle" style="font-size:20px;color:grey"></i></a>
                                            <br>
                                            <input type="file" class="noneinput" name="store_logo" size="chars">
                                            <img src="{{$store->store_logo}}" width="133" height="133">
                                            <br>
                                            <span class="upoloadimages">
                                                <input class="btn m-b-xs w-auto btn-success" select="uploadFiles($files, $invalidFiles)" max-size="1.5MB" value="@lang('site.select_image')" class="addtocartbtn" type="button">
                                            </span> -->
                                    </div>

                                        
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
            <div class="card bg-light mt-3 pl-3 pr-3  rounded  border-0">
                <div class="row">
                    <div class="card-body">
                        <div class="row">
                            <div class="col-sm-12">
                                <div class="row">
                                 <div class="col-md-12 ">
                                    <button  type="submit" form="store_form" class="btn btn-primary">@lang('site.save')</button>&nbsp;&nbsp;
                                    <a  class="btn btn-light" href="{{route('outlets.index')}}">@lang('site.cancel')</a> 
                                </div>

                                </div>
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
<script src="{{CustomUrl::asset('js/cropper.min.js') }}"></script>
<script src="{{CustomUrl::asset('js/dropzone.js') }}"></script>
<script type="text/javascript">
var p_images = [];
var images = JSON.parse('{!! json_encode($store) !!}');
var images_path = JSON.parse('{!! json_encode($image) !!}');

var img = images;
    img.url = images.store_logo;
var myzone = null;

if( images.store_logo != null ){
    p_images.push(images);
}

Dropzone.autoDiscover = false;
var zone = null;

var image_data = [];

$(document).ready(function(){
let options = {
  paramName: "image", // The name that will be used to transfer the file
  url: images_path,
  acceptedFiles : 'image/*',
  maxFilesize: 4, // MB
  addRemoveLinks: true,
  capture : true,
  dictRemoveFile : 'Remove',
  parallelUploads: 1,
  uploadMultiple: false,
  thumbnailWidth: 220,
  thumbnailHeight: 166,
  maxFiles: 1,
  dictRemoveFileConfirmation:'Are you sure you want to remove this image?',
  accept: function(file, done) {
    done();
  },
  success:function(file,response)
  {
    if(response.path){
      file.path = response.path;
      // console.log(response);
      image_data.push(response);
      let $button = $('<a href="#" class="js-open-cropper-modal" data-url="'+ file.path +'" data-file-name="' + file.name + '">Crop</a>');
      $(file.previewElement).append($button);
      $('#path').val(response.path);
      
    }else{
      this.removeFile(file);
      toastr.error(response.image[0],'Error');
    }
  },
  init: function() {
    this.on("removedfile", deleteFile); 
    myzone = this;
    initFileUpload();      
  }    
};

zone = $('#my-awesome-dropzone').dropzone(options);

let suboptions = {
  paramName: "image", // The name that will be used to transfer the file
  url: images_path,
  acceptedFiles : 'image/*',
  maxFilesize: 4, // MB
  addRemoveLinks: true,
  capture : true,
  dictRemoveFile : 'Remove',
  parallelUploads: 10,
  uploadMultiple: false,
  thumbnailWidth: 220,
  thumbnailHeight: 166,
  maxFiles: 3,
  strict:true,
  dictRemoveFileConfirmation:'Are you sure you want to remove this image?',
  success:function(file,response)
  {
  if(response.path){
  file.path = response.path;
  sub_image_data.push(response);
  let $button = $('<a href="#" class="js-open-cropper-modal" data-url="'+ file.path +'" data-file-name="' + file.name + '">Crop</a>');
  $(file.previewElement).append($button);
    $('#mobile_path').val(response.path);

  }else{
  this.removeFile(file);
  toastr.error(response.image[0],'Error');
  }
  },
  init: function() {
  this.on("removedfile", deleteSubFile); 
  this.on("maxfilesexceeded", function(file){
  toastr.error('Unable to upload more images','Error');
  this.removeFile(file);
  });
  mysubzone = this;
  initFileUpload();      
  }    
};

subzone = $('#my-awesome-dropzone-sub').dropzone(suboptions);    
})


function initFileUpload() 
{
  if(p_images != null && p_images.length > 0)
  {
    $.each(p_images, function (key, file) {
      let mockFile = {
        name: file.name,
        size: file.size,
        accepted: true,
        kind: 'image',
        upload: {
        filename: file.name,
        },
        dataURL: file.url,
        path : file.url
      };

      image_data.push({ 
        name : mockFile.name , 
        path : mockFile.dataURL, 
        size : mockFile.size ,
       
        id : file.id
      });

      myzone.files.push(mockFile);
      myzone.emit("addedfile", mockFile);
      myzone.createThumbnailFromUrl(mockFile,
      myzone.options.thumbnailWidth,myzone.options.thumbnailHeight,
      myzone.options.thumbnailMethod,true,function(thumbnail) {
        myzone.emit('thumbnail', mockFile, thumbnail);
        myzone.emit("complete", mockFile);
      });

      let $button = $('<a href="#" class="js-open-cropper-modal" data-url="'+ mockFile.path +'" data-file-name="' + mockFile.name + '">Crop</a>');
      $(mockFile.previewElement).append($button);
    }); 
  }
}

function deleteFile(file)
{
let items = image_data.filter(e => e.path != file.path);
image_data = [];
image_data = items;
$('#path').val('');
}


var c = 0;

var dataURItoBlob = function (dataURI) {
    var byteString = atob(dataURI.split(',')[1]);
    var ab = new ArrayBuffer(byteString.length);
    var ia = new Uint8Array(ab);
    for (var i = 0; i < byteString.length; i++) {
    ia[i] = byteString.charCodeAt(i);
    }
    return new Blob([ab], {type: 'image/jpeg'});
};

$(function(){
    $('#my-awesome-dropzone').on('click', '.js-open-cropper-modal', function (e) {
    e.preventDefault();
    var fileUrl = $(this).data('url');

    var modalTemplate =
    '<div class="modal fade" tabindex="-1" role="dialog">' +
    '<div class="modal-dialog modal-lg" role="document">' +
    '<div class="modal-content">' +
    '<div class="modal-header">' +
    '<button type="button" class="close" data-dismiss="modal" aria-label="Close"><span aria-hidden="true">&times;</span></button>' +
    // '<h4 class="modal-title">Crop</h4>' +
    '</div>' +
    '<div class="modal-body">' +
    '<div class="image-container">' +
    '<img id="img-' + ++c + '" src="' + fileUrl + '">' +
    '</div>' +
    '</div>' +
    '<div class="modal-footer">' +
    '<button type="button" class="btn btn-warning rotate-left"><span class="fa fa-rotate-left"></span></button>' +
    '<button type="button" class="btn btn-warning rotate-right"><span class="fa fa-rotate-right"></span></button>' +
    '<button type="button" class="btn btn-warning scale-x" data-value="-1"><span class="fa fa-arrows-h"></span></button>' +
    '<button type="button" class="btn btn-warning scale-y" data-value="-1"><span class="fa fa-arrows-v"></span></button>' +
    '<button type="button" class="btn btn-warning reset"><span class="fa fa-refresh"></span></button>' +
    '<button type="button" class="btn btn-default" data-dismiss="modal">Close</button>' +
    '<button type="button" class="btn btn-primary crop-upload">Crop & Upload</button>' +
    '</div>' +
    '</div>' +
    '</div>' +
    '</div>';

    var $cropperModal = $(modalTemplate);

    $cropperModal.modal('show').on("shown.bs.modal", function () {
    var cropper = new Cropper(document.getElementById('img-' + c), {
    autoCropArea: 1,
    movable: true,
    cropBoxResizable: true,
    rotatable: true,
    aspectRatio : 16 / 9,
    minCropBoxWidth: 1100,
    minCropBoxHeight: 459,
    });
    var $this = $(this);
    $this
    .on('click', '.crop-upload', function () {
    // get cropped image data
    var blob = cropper.getCroppedCanvas().toDataURL('image/jpeg');
    // transform it to Blob object
    var croppedFile = dataURItoBlob(blob);
    croppedFile.name = fileUrl;

    var files = myzone.getAcceptedFiles();

    for (var i = 0; i < files.length; i++) {
    var file = files[i];
    if (file.path === fileUrl) {
    myzone.removeFile(file);
    deleteFile(file);
    }
    }

    myzone.addFile(croppedFile);
    image_data.push(croppedFile);
    $this.modal('hide');
    })
    .on('click', '.rotate-right', function () {
    cropper.rotate(90);
    })
    .on('click', '.rotate-left', function () {
    cropper.rotate(-90);
    })
    .on('click', '.reset', function () {
    cropper.reset();
    })
    .on('click', '.scale-x', function () {
    var $this = $(this);
    cropper.scaleX($this.data('value'));
    $this.data('value', -$this.data('value'));
    })
    .on('click', '.scale-y', function () {
    var $this = $(this);
    cropper.scaleY($this.data('value'));
    $this.data('value', -$this.data('value'));
    });
    });
    });
})


</script>

<!-- <script type="text/javascript">
    $('#same').click(function()
        {
        $('#postal_street_1').val($('#physical_street_1').val());
        // $('#postal_street_2').val($('#physical_street_2').val());
        // $('#postal_suburb').val($('#physical_suburb').val());
        $('#postal_city').val($('#physical_city').val());
        // $('#postal_postcode').val($('#physical_postcode').val());
        $('#postal_state').val($('#physical_state').val());
        $('#postal_country').val($('#physical_country').val());
    });

    $(document).ready(function() {
        $("#accordian h3").click(function() {
            $("#accordian").removeClass("active");
            document.querySelector('.fa').className = 'fa fa-angle-up';
            $(this).find('i').toggleClass('fa-angle-down fa-angle-up');
            // $(this).parent().toggleClass('active');
            $("#accordian ul ul").slideUp();
            if(!$(this).next().is(":visible"))
            {
                $(this).next().slideDown();
            }
        })
    
        $("#accordian1 h3").click(function() {
            $("#accordian1").removeClass("active");
            document.querySelector('.fa').className = 'fa fa-angle-up';
            $(this).find('i').toggleClass('fa-angle-down fa-angle-up');
            // $(this).parent().toggleClass('active');
            $("#accordian1 ul ul").slideUp();
            if(!$(this).next().is(":visible"))
            {
                $(this).next().slideDown();
            }
        })
   
  $('[data-toggle="tooltip"]').tooltip(); 
});

</script> -->
@endsection