@extends('layouts.backoffice')
@section('content')
<div class="row">
    <div class="col-sm-12">
        <div class="greybg1 rounded p-4 mb-3">
            <div class="row">
                <div class="col-sm-12">
                    <div class="common_title">
                        <h1 class="m-n font-thin h3 text-black">
                            @lang('plugins.salestax')
                            <span class="pull-right">
                                <a href="{{ route('index.salestax')}}" class="m-b-xs w-auto btn-primary btn-sm pull-right">
                                    {{ __('plugins.back') }}
                                </a>
                            </span>
                        </h1>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>
<div class="wrapper-md">
    <div class="row">
        <div class="col-sm-12">
            <div class="panel panel-default">
                <div class="panel-body">
                    <div class="wrapper-md">
                        <div class="col-md-12">
                            <form action="{{ route('salestax.save') }}" method="post" id="salestax-form">
                                {{ csrf_field() }}
                                <!--  -->
                                <div class="col-md-12 mt-3">
                                    <div class="row">
                                        <div class="col-md-3 mt-3 mb-3">
                                            <label>Name</label>
                                            <span style="color: red;">*</span>
                                        </div>
                                        <div class="col-md-8">
                                            <div class="row">
                                                <div class="col-md-3">
                                                    <input name="name" form="salestax-form" value="{{$salestax->name}}" type="text" class="form-control rounded" placeholder="GST" required />
                                                </div>
                                                <!-- Enter the amount -->
                                                <!-- <span data-toggle="tooltip" class="tooltipTest" data-placement="right" title="Enter the amount"> -->
                                                <!-- <i class="fa fa-info-circle" style="font-size: 30px; color: #0066ff;" ></i></span> -->
                                            </div>
                                        </div>
                                    </div>
                                    <div class="row">
                                        <div class="col-md-3 mt-3 mb-3">
                                            <label>Tax %</label>
                                            <span style="color: red;">*</span>
                                        </div>
                                        <div class="col-md-8">
                                            <div class="row">
                                                <div class="col-md-3">
                                                    <input name="tax_per" step="any" max="100" form="salestax-form" value="{{$salestax->tax_per}}" type="number" class="form-control rounded" placeholder="0.00" required />
                                                </div>
                                                <!-- Enter the number of points for above amount -->
                                                <!-- <span data-toggle="tooltip" class="tooltipTest" data-placement="right" title="Enter the number of points on above amount"> -->
                                                <!-- <i class="fa fa-info-circle" style="font-size: 30px; color: #0066ff;" ></i></span> -->
                                            </div>
                                        </div>
                                    </div>
                                    <div class="row">
                                        <div class="col-md-3 mt-3 mb-3">
                                            <label>Country</label>
                                            <span style="color: red;">*</span>
                                        </div>
                                        <div class="col-md-8">
                                            <div class="row">
                                                <div class="col-md-3">
                                                    <select name="country" form="salestax-form" class="form-control select2" id="country" required>
                                                        <option value="">Select</option>
                                                        @foreach ($countries as $country) @php $selected = ($country->id == $salestax->country) ? 'selected': ''; @endphp

                                                        <option value="{{ $country->id }}" data-country-id="{{ $country->id }}" {{ $selected }}>
                                                            {{ $country->country }}
                                                        </option>
                                                        @endforeach
                                                    </select>
                                                </div>
                                                <!-- Redeem rate for above points -->
                                                <!-- <span data-toggle="tooltip" class="tooltipTest" data-placement="right" title="Enter the points value"> -->
                                                <!-- <i class="fa fa-info-circle" style="font-size: 30px; color: #0066ff;" ></i></span> -->
                                            </div>
                                        </div>
                                    </div>
                                    <div class="row">
                                        <div class="col-md-3 mt-3 mb-3">
                                            <label>State</label>
                                            <span style="color: red;">*</span>
                                        </div>
                                        <div class="col-md-8">
                                            <div class="row">
                                                <div class="col-md-3">
                                                    <select name="state" form="salestax-form" class="form-control select2" id="city" required>
                                                        <option value="" data-country-id="0">Select</option>
                                                        @foreach ($cities as $city) @php $selected = ($city->id == $salestax->state) ? 'selected': ''; @endphp

                                                        <option value="{{ $city->id }}" data-country-id="{{ $city->country_id }}" {{ $selected }}>
                                                            {{ $city->city }}
                                                        </option>
                                                        @endforeach
                                                    </select>
                                                </div>
                                                <!-- Enter the MAX reward value for above points -->
                                                <!-- <span data-toggle="tooltip" class="tooltipTest" data-placement="right" title="Enter the MAX reward value for above points"> -->
                                                <!-- <i class="fa fa-info-circle" style="font-size: 30px; color: #0066ff;" ></i></span> -->
                                            </div>
                                        </div>
                                    </div>

                                    <div class="row">
                                        <div class="col-md-3 mt-3 mb-3">
                                            <label>Inc Shipping</label>
                                        </div>
                                        <div class="col-md-8 mt-3">
                                            <div class="row">
                                                <div class="col-md-3">
                                                    <div class="pure-checkbox">
                                                        <input id="inc_shipping" form="salestax-form" name="inc_shipping" value="1" type="checkbox" {{$salestax->inc_shipping == 1 ? 'checked' : ''}} />
                                                        <label for="inc_shipping"> </label>
                                                    </div>
                                                </div>
                                            </div>
                                        </div>
                                    </div>
                                    <div class="row">
                                        <div class="col-md-3">
                                            <label>Inc Discount</label>
                                        </div>
                                        <div class="col-md-8">
                                            <div class="row">
                                                <div class="col-md-3">
                                                    <div class="pure-checkbox">
                                                        <input form="salestax-form" id="inc_discount" name="inc_discount" value="1" type="checkbox" {{$salestax->inc_discount == 1 ? 'checked' : ''}} />
                                                        <label for="inc_discount"> </label>
                                                    </div>
                                                </div>
                                            </div>
                                        </div>
                                    </div>
                                    <div class="row">
                                        <div class="col-md-3 mt-3 mb-3">
                                            <label>Status</label>
                                        </div>
                                        <div class="col-md-8 mt-3">
                                            <div class="row">
                                                <div class="col-md-3">
                                                    <div class="pure-checkbox">
                                                        <input form="salestax-form" id="status" name="status" value="1" type="checkbox" {{$salestax->status == 1 ? 'checked' : ''}} />
                                                        <label for="status"> </label>
                                                    </div>
                                                </div>
                                            </div>
                                        </div>
                                    </div>
                                </div>
                                <div class="row">
                                    <div class="col-sm-12 col-xs-12">
                                        <span class="addremovecash">
                                            <button i class="btn btn-primary mt-5 mb-5 save_delivery_time" type="submit">@lang('site.save')</button>
                                            <a href="{{ route('plugins.index')}}" class="btn btn-light">{{ __('plugins.cancel') }}</a>
                                        </span>
                                    </div>
                                </div>
                            </form>
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
  $(document).ready(function () {
      $("#country").change(function () {
          filterSelectOptions($("#city"), "data-country-id", $(this).val());
      });
      function filterSelectOptions(selectElement, attributeName, attributeValue) {
          if (selectElement.data("currentFilter") != attributeValue) {
              selectElement.data("currentFilter", attributeValue);
              var originalHTML = selectElement.data("originalHTML");
              if (originalHTML) selectElement.html(originalHTML);
              else {
                  var clone = selectElement.clone();
                  clone.children("option[selected]").removeAttr("selected");
                  selectElement.data("originalHTML", clone.html());
              }
              if (attributeValue) {
                  selectElement.children("option:not([" + attributeName + "='" + attributeValue + "'],:not([" + attributeName + "]))").remove();
              }
          }
      }
  });

  $(function () {
      $("#salestax-form").submit(function () {
          if ($(this)[0].checkValidity()) {
              $.ajax({
                  url: $(this).attr("action"),
                  type: $(this).attr("method"),
                  data: $(this).serialize(),
                  success: function (response) {
                      if (response.IsValid) {
                          toastr.success(response.Message, "Success");
                          setTimeout(() => {
                              window.location.href = site_url("salestax");
                          }, 3000);
                      } else {
                          if (response.Errors.lenght > 0) {
                              response.Errors.map((error) => {
                                  toastr.error(error, "Error");
                              });
                          } else {
                              toastr.error(response.Errors[0], "Error");
                          }
                      }
                  },
              });
          }
          return false;
      });
  });
</script>
@endsection