@extends('layouts.backoffice')
@section('content')
<!-- content -->

<div class="row">
   <div class="col-sm-12">
      <div class="greybg1 rounded p-4 mb-3">
         <div class="row">
            <div class="col-sm-12">
              <div class="common_title">
                <h1 class="m-n font-thin h3 text-black">
                    @lang('site.delivery_time')
                    <span class="pull-right">
                      <a href="{{ route('plugins.index')}}" class="m-b-xs w-auto btn-primary btn-sm pull-right">
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
    <!-- stats -->
    <div class="col-md-12">
        <form action="{{ route('add.delivery_time') }}" id="delivery_time_form" method="post">
            <div class="panel panel-body">
                <div class="row">
                    <div class="table-responsive">
                        <table class="table">
                            <thead>
                                <tr>
                                    <td style="width: 20%;">@lang('site.do_not_deliver')</td>
                                    <td style="width: 20%;">@lang('site.holidays')</td>
                                    <td style="width: 60%;"><span style="margin-left: 30%;">@lang('site.same_day_delivery')</span><br /></td>
                                </tr>
                            </thead>
                            <tbody>
                                <tr>
                                    <td style="width: 20%;">
                                        <ul class="list-group">
                                            <li class="list-group-item">
                                                <input type="checkbox" name="sunday" id="sunday" {{ strtolower($do_not_deliver->Sunday) == '0' ? 'checked' : '' }} />
                                                <label for="sunday">@lang('site.sunday')</label>
                                            </li>
                                            <li class="list-group-item">
                                                <input type="checkbox" name="monday" id="monday" {{ strtolower($do_not_deliver->Monday) == '1' ? 'checked' : '' }} />
                                                <label for="monday">@lang('site.monday')</label>
                                            </li>
                                            <li class="list-group-item">
                                                <input type="checkbox" name="tuesday" id="tuesday" {{ strtolower($do_not_deliver->Tuesday) == '2' ? 'checked' : '' }} />
                                                <label for="tuesday">@lang('site.tuesday')</label>
                                            </li>
                                            <li class="list-group-item">
                                                <input type="checkbox" name="wednesday" id="wednesday" {{ strtolower($do_not_deliver->Wednesday) == '3' ? 'checked' : '' }} />
                                                <label for="wednesday">@lang('site.wednesday')</label>
                                            </li>
                                            <li class="list-group-item">
                                                <input type="checkbox" name="thursday" id="thursday" {{ strtolower($do_not_deliver->Thursday) == '4' ? 'checked' : '' }} />
                                                <label for="thursday">@lang('site.thursday')</label>
                                            </li>
                                            <li class="list-group-item">
                                                <input type="checkbox" name="friday" id="friday" {{ strtolower($do_not_deliver->Friday) == '5' ? 'checked' : '' }} />
                                                <label for="friday">@lang('site.friday')</label>
                                            </li>
                                            <li class="list-group-item">
                                                <input type="checkbox" name="saturday" id="saturday" {{ strtolower($do_not_deliver->Saturday) == '6' ? 'checked' : '' }} />
                                                <label for="saturday">@lang('site.saturday')</label>
                                            </li>
                                        </ul>
                                    </td>
                                    <td style="width: 26%;">
                                        <div class="form-inline">
                                            <div class="form-group">
                                                <input type="date" class="form-control" id="holiday-date" style="margin-bottom: 0px;" />
                                                <button type="button" id="holiday-button" class="btn btn-default">@lang('site.add')</button>
                                            </div>
                                        </div>
                                        <ul id="holidays" class="list-group"></ul>
                                    </td>
                                    <td style="width: 60%;">
                                        <ul class="list-group">
                                            <span style="margin-left: 42%;">
                                                <input type="checkbox" name="same_day_delivery" id="same_day_delivery" {{ strtolower($model->same_day_delivery) == 1 ? 'checked' : '' }} /> <label for="saturday"></label><br />
                                            </span>
                                            <span id="slots_h">@lang('site.time_slots')</span>

                                            <span id="slots">
                                                <div class="form-inline">
                                                    <div class="form-group">
                                                        <input type="text" class="form-control timepicker" id="slot-start" style="margin-bottom: 0px;" placeholder="start Time" readonly />
                                                        <input type="text" class="form-control timepicker" id="slot-end" style="margin-bottom: 0px;" placeholder="End Time" readonly />
                                                        <button type="button" id="slot-button" class="btn btn-default">@lang('site.add')</button>
                                                    </div>
                                                </div>
                                                <ul id="time-slots" class="list-group" style="width: 87%;"></ul>
                                                <input type="hidden" id="holidays_arr" name="holidays" />
                                                <input type="hidden" id="time_slots_arr" name="time_slots" />
                                            </span>
                                        </ul>
                                    </td>
                                </tr>
                            </tbody>
                        </table>
                    </div>
                </div>

                <div class="row">
                    <div class="col-sm-12 col-xs-12">
                        <span class="addremovecash">
                            <button id="save_delivery_time" form="delivery_time_form" class="btn btn-primary mt-5 mb-5 save_delivery_time" type="submit">Submit</button>
                            <a href="{{ route('plugins.index')}}" class="btn btn-light">{{ __('plugins.cancel') }}</a>
                        </span>
                    </div>
                </div>
            </div>
        </form>
    </div>
</div>

@endsection
@section('scripts')
<script src="{{CustomUrl::asset('js/cropper.min.js') }}"></script>
<script src="//cdnjs.cloudflare.com/ajax/libs/timepicker/1.3.5/jquery.timepicker.min.js"></script>
<script type="text/javascript">

  $('.timepicker').timepicker({
      timeFormat: 'h:mm p',
      interval: 60,
      minTime: '12:00 AM',
      maxTime: '11:59 PM',
      // defaultTime: '12',
      // startTime: '12:00',
      dynamic: false,
      dropdown: true,
      scrollbar: true
  });

  let holidays = [];
  let slots = [];
  // var olidays = '{!! $holidays !!}';
  // var lots  = '{!! $time_slots !!}';

  holiday = '{!! $holidays !!}'.split(",");
  slots = JSON.parse('{!! json_encode($time_sl) !!}');

  holidays = holiday.filter(function (e) {
      return e.replace(/(\r\n|\n|\r)/gm, "");
  });

  function removeHoliday(item) {
      holidays = holidays.filter((x) => !(x == item));
      $("#holidays_arr").val(holidays);
      if (holidays.length == 0) {
          $("#holidays_arr").html();
          $("#holidays_arr").val("");
      }
  }
  function removeslot(item) {
      slots = slots.filter((x) => !(x == item));
      $("#time_slots_arr").val(slots);
      if (slots.length == 0) {
          $("#time_slots_arr").html();
          $("#time_slots_arr").val("");
      }
  }

  $(function () {
      if (holidays.length >= 1) {
          let hols = [...new Set(holidays)];
          $("#holidays").html("");
          hols.map((item) => {
              $("#holidays").append(`<li class="list-group-item abc">${item} <i class="fa fa-remove pull-right deletebtn" onclick="removeHoliday('${item}')" ></i> </li></li>`);
          });
          $("#holidays_arr").val(hols);
      }
      $("#holiday-button").click(function () {
          if ($("#holiday-date").val()) {
              holidays.push($("#holiday-date").val());

              let hols = [...new Set(holidays)];
              $("#holidays").html("");

              hols.map((item) => {
                  $("#holidays").append(`<li class="list-group-item">${item} <i class="fa fa-remove pull-right deletebtn"  onclick="removeHoliday('${item}')"></i> </li></li>`);
              });
              $("#holidays_arr").val(hols);
              $("#holiday-date").val("");
          }
      });
  });

  $(function () {
      if (slots.length >= 1) {
          // slots.push($('#slot-start').val()+' - '+$('#slot-end').val());
          let hols = slots;
          hols.map((item) => {
              $("#time-slots").append(`<li class="list-group-item">${item} <i class="fa fa-remove pull-right deletebtn" onclick="removeslot('${item}')"></i> </li>`);
          });
          $("#time_slots_arr").val(hols);
      }
      $("#slot-button").click(function () {
          if ($("#slot-start").val() && $("#slot-end").val()) {
              slots.push($("#slot-start").val() + " - " + $("#slot-end").val());

              let hols = [...new Set(slots)];
              $("#time-slots").html("");
              hols.map((item) => {
                  // console.log(hols);
                  $("#time-slots").append(`<li class="list-group-item">${item} <i class="fa fa-remove pull-right deletebtn"  onclick="removeslot('${item}')"></i> </li>`);
              });

              $("#time_slots_arr").val(hols);
              $("#slot-start").val("");
              $("#slot-end").val("");
          }
      });
      if (!$("#same_day_delivery").is(":checked")) {
          $("#slots").hide();
          $("#slots_h").hide();
      } else {
          $("#slots").show();
          $("#slots_h").show();
      }
      $("#same_day_delivery").click(function () {
          if (!$(this).is(":checked")) {
              $("#slots").hide();
              $("#slots_h").hide();
          } else {
              $("#slots").show();
              $("#slots_h").show();
          }
      });
  });

  $(document).on("click", "i.deletebtn", function () {
      $(this).closest("li").remove();
      return false;
  });
</script>
@endsection
   




