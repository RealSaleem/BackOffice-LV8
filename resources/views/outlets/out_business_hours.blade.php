@extends('layouts.backoffice')
@section('content')

<div class="row" >
    <div class="col-sm-12">
        <div class="greybg1 rounded p-4">
            <div class="row">
                <!-- Inner Div -->
                <div class="col-sm-12">
                    <div class="common_title">
                        <h1>
                          <a href="{{url('business_hours').'/'.$outlet->id}}" >{{$outlet->name}} </a> <span>/ @lang('site.Business_hours') </span>
                          <a href="{{route('outlets.index')}}" class="m-b-xs w-auto btn-primary btn-sm pull-right">Back </a>
                        </h1>
                    </div>
                </div>
            </div>

            <form  action="{{ $route  }}" id="delivery_zones_form" method="POST">
              {{ csrf_field() }}
              @php
                $day = $outlet->business_hours ?? '';
              @endphp
              <div class="card bg-light mt-3 pl-3 pr-3  rounded  border-0">
                  <div class="row">
                      <div class="card-body">
                          <div class="row">
                              <div class="col-sm-12">
                                <div class="list-group ">
                                  <div class="list-group-item float">
                                    <input type="checkbox" name="day[0][set_daily]" id="set_daily"  {{ isset($day[0]['set_daily']) && $day[0]['set_daily'] == 'set_daily' ? 'checked' : '' }} />
                                      <label for="set_daily">@lang('site.set_daily')</label>



                                      <div class="form-inline pull-right float1 " >
                                          <input type="hidden" name="day[0][set_daily]" id="set_daily" value="set_daily" />
                                        <div class="form-group  ">
                                          <input type="text" class="form-control timepicker" name="day[0][slot_start]" id="day_0_slot_start" value="{{isset($day[0]['slot_start']) ? $day[0]['slot_start'] : ''}}" style="margin-bottom: 0px; width:42%;" placeholder="Start Time" readonly> &nbsp;
                                          <input type="text" class="form-control timepicker" name="day[0][slot_end]" id="day_0_slot_end" value="{{isset($day[0]['slot_end']) ? $day[0]['slot_end'] : ''}}" style="margin-bottom: 0px; width: 42%;" placeholder="End Time" readonly>
                                        </div>
                                      </div>
                                  </div>





                                  <div class="list-group-item float">
                                      <input type="checkbox" name="day[1]" id="sunday" {{ isset($day[1]['name']) && $day[1]['name'] == 'sunday' ? 'checked' : '' }} />
                                      <input type="hidden" name="day[1][name]" id="sunday" value="sunday" />
                                      <label for="sunday">@lang('site.sunday')</label>
                                      <div class="form-inline pull-right float1" >
                                        <div class="form-group">
                                          <input type="text" class="form-control timepicker" name="day[1][slot_start]" id="day_1_slot_start" value="{{isset($day[1]['slot_start']) ? $day[1]['slot_start'] : ''}}" style="margin-bottom: 0px; width:42%;" placeholder="Start Time" readonly> &nbsp;
                                          <input type="text" class="form-control timepicker" name="day[1][slot_end]" id="day_1_slot_end" value="{{isset($day[1]['slot_end']) ? $day[1]['slot_end'] : ''}}" style="margin-bottom: 0px; width: 42%;" placeholder="End Time" readonly>
                                        </div>
                                      </div>
                                  </div>
                                  <div class="list-group-item float">
                                      <input type="checkbox" name="day[2]" id="monday" {{ isset($day[2]['name']) && $day[2]['name'] == 'monday'  ? 'checked' : '' }} />
                                      <input type="hidden" name="day[2][name]" id="monday" value="monday" />
                                      <label for="monday">@lang('site.monday')</label>
                                      <div class="form-inline pull-right float1" >
                                        <div class="form-group">
                                          <input type="text" class="form-control timepicker" name="day[2][slot_start]" id="day_2_slot_start" value="{{isset($day[2]['slot_start']) ? $day[2]['slot_start'] : ''}}" style="margin-bottom: 0px; width:42%;" placeholder="Start Time" readonly> &nbsp;
                                          <input type="text" class="form-control timepicker" name="day[2][slot_end]" id="day_2_slot_end" value="{{isset($day[2]['slot_end']) ? $day[2]['slot_end'] : ''}}" style="margin-bottom: 0px; width: 42%;" placeholder="End Time" readonly>
                                        </div>
                                      </div>
                                  </div>
                                  <div class="list-group-item float">
                                      <input type="checkbox" name="day[3]" id="tuesday" {{ isset($day[3]['name']) && $day[3]['name'] == 'tuesday'  ? 'checked' : '' }} />
                                      <input type="hidden" name="day[3][name]" id="tuesday" value="tuesday" />
                                      <label for="tuesday">@lang('site.tuesday')</label>
                                      <div class="form-inline pull-right float1" >
                                        <div class="form-group">
                                          <input type="text" class="form-control timepicker" name="day[3][slot_start]" id="day_3_slot_start" value="{{isset($day[3]['slot_start']) ? $day[3]['slot_start'] : ''}}" style="margin-bottom: 0px; width:42%;" placeholder="Start Time" readonly> &nbsp;
                                          <input type="text" class="form-control timepicker" name="day[3][slot_end]" id="day_3_slot_end" value="{{isset($day[3]['slot_end']) ? $day[3]['slot_end'] : ''}}" style="margin-bottom: 0px; width: 42%;" placeholder="End Time" readonly>
                                        </div>
                                      </div>
                                  </div>
                                  <div class="list-group-item float">
                                      <input type="checkbox" name="day[4]" id="wednesday" {{ isset($day[4]['name']) && $day[4]['name'] == 'wednesday'  ? 'checked' : '' }} />
                                      <input type="hidden" name="day[4][name]" id="wednesday" value="wednesday" />
                                      <label for="wednesday">@lang('site.wednesday')</label>
                                      <div class="form-inline pull-right float1" >
                                        <div class="form-group">
                                          <input type="text" class="form-control timepicker" name="day[4][slot_start]" id="day_4_slot_start" value="{{isset($day[4]['slot_start']) ? $day[4]['slot_start'] : ''}}" style="margin-bottom: 0px; width:42%;" placeholder="Start Time" readonly>&nbsp;
                                          <input type="text" class="form-control timepicker" name="day[4][slot_end]" id="day_4_slot_end"value="{{isset($day[4]['slot_end']) ? $day[4]['slot_end'] : ''}}"  style="margin-bottom: 0px; width: 42%;" placeholder="End Time" readonly>
                                        </div>
                                      </div>
                                  </div>
                                  <div class="list-group-item float">
                                      <input type="checkbox" name="day[5]" id="thursday" {{ isset($day[5]['name']) && $day[5]['name'] == 'thursday'  ? 'checked' : '' }} />
                                      <input type="hidden" name="day[5][name]" id="thursday" value="thursday" />
                                      <label for="thursday">@lang('site.thursday')</label>
                                      <div class="form-inline pull-right float1" >
                                        <div class="form-group">
                                          <input type="text" class="form-control timepicker" name="day[5][slot_start]" id="day_5_slot_start" value="{{isset($day[5]['slot_start']) ? $day[5]['slot_start'] : ''}}" style="margin-bottom: 0px; width:42%;" placeholder="Start Time" readonly>&nbsp;
                                          <input type="text" class="form-control timepicker" name="day[5][slot_end]" id="day_5_slot_end" value="{{isset($day[5]['slot_end']) ? $day[5]['slot_end'] : ''}}" style="margin-bottom: 0px; width: 42%;" placeholder="End Time" readonly>
                                        </div>
                                      </div>
                                  </div>
                                  <div class="list-group-item float">
                                      <input type="checkbox" name="day[6]" id="friday" {{ isset($day[6]['name']) && $day[6]['name'] == 'friday'  ? 'checked' : '' }} />
                                      <input type="hidden" name="day[6][name]" id="friday" value="friday" />
                                      <label for="friday">@lang('site.friday')</label>
                                      <div class="form-inline pull-right float1" >
                                        <div class="form-group">
                                          <input type="text" class="form-control timepicker" name="day[6][slot_start]" id="day_6_slot_start" value="{{isset($day[6]['slot_start']) ? $day[6]['slot_start'] : ''}}" style="margin-bottom: 0px; width:42%;" placeholder="Start Time" readonly>&nbsp;
                                          <input type="text" class="form-control timepicker" name="day[6][slot_end]" id="day_6_slot_end" value="{{isset($day[6]['slot_end']) ? $day[6]['slot_end'] : ''}}" style="margin-bottom: 0px; width: 42%;" placeholder="End Time" readonly>
                                        </div>
                                      </div>
                                  </div>
                                  <div class="list-group-item float">
                                      <input type="checkbox" name="day[7]" id="saturday" {{ isset($day[7]['name']) && $day[7]['name'] == 'saturday'  ? 'checked' : '' }} />
                                      <input type="hidden" name="day[7][name]" id="saturday" value="saturday" />
                                      <label for="saturday">@lang('site.saturday')</label>
                                      <div class="form-inline pull-right ml-3 float1" >
                                        <div class="form-group ">
                                          <input type="text" class="form-control timepicker" name="day[7][slot_start]" id="day_7_slot_start" value="{{isset($day[7]['slot_start']) ? $day[7]['slot_start'] : ''}}"   style="margin-bottom: 0px; width:42%;" placeholder="Start Time" readonly>&nbsp;
                                          <input type="text" class="form-control timepicker" name="day[7][slot_end]" id="day_7_slot_end" value="{{isset($day[7]['slot_end']) ? $day[7]['slot_end'] : ''}}" style="margin-bottom: 0px; width: 42%;" placeholder="End Time" readonly>
                                        </div>
                                      </div>
                                  </div>
                                </div>
                              </div>
                          </div>
                          <div class="row">
                            <div class="col-md-12 pb-5 pt-5" >
                              <button type="submit" form="delivery_zones_form" class="btn btn-primary">@lang('site.save')</button>
                              <a href="{{ route('outlets.index')}}" class="btn btn-light">{{ __('outlets.cancel') }}</a>
                            </div>
                          </div>
                      </div>
                  </div>
              </div>
            </form>
        </div>
    </div>
</div>


{{--
<div id="content" class="app-content" role="main" >
  <div class="app-content-body ">
    <div class="hbox hbox-auto-xs hbox-auto-sm">
      <div class="col">
        <div class="bg-light lter b-b wrapper-md">
          <div class="row">
            <div class="col-sm-12 col-xs-12">
              <h1 class="m-n font-thin h3 text-black" >
                <a href="{{url('business_hours').'/'.$outlet->id}}" >{{$outlet->name}} </a>
                <a href="{{route('outlets.index')}}" class="m-b-xs w-auto btn-primary btn-sm pull-right">Back </a>
              </h1>
            </div>
          </div>
        </div>
        <div class="wrapper-md">
          <div class="col-sm-12">
            <div class="panel panel-default">
              <div class="panel-heading">
                <span class="">
                    @lang('site.Business_hours')
                </span>
                <div class="clearFix"></div>
              </div>
              <div class="products-dec">
               <form  action="{{ $route  }}" id="delivery_zones_form" method="POST">
              {{ csrf_field() }}
              @php
                $day = $outlet->business_hours ?? '';
              @endphp
              <div class="list-group ">
                  <div class="list-group-item float">
                    <input type="checkbox" name="day[0][set_daily]" id="set_daily"  {{ isset($day[0]['set_daily']) && $day[0]['set_daily'] == 'set_daily' ? 'checked' : '' }} />
                      <label for="set_daily">@lang('site.set_daily')</label>
                      <div class="form-inline pull-right float1 " >
                          <input type="hidden" name="day[0][set_daily]" id="set_daily" value="set_daily" />
                        <div class="form-group  ">
                          <input type="text" class="form-control timepicker" name="day[0][slot_start]" id="day_0_slot_start" value="{{isset($day[0]['slot_start']) ? $day[0]['slot_start'] : ''}}" style="margin-bottom: 0px; width:42%;" placeholder="start Time" readonly>
                          <input type="text" class="form-control timepicker" name="day[0][slot_end]" id="day_0_slot_end" value="{{isset($day[0]['slot_end']) ? $day[0]['slot_end'] : ''}}" style="margin-bottom: 0px; width: 42%;" placeholder="End Time" readonly>
                        </div>
                      </div>
                  </div>
                  <div class="list-group-item float">
                      <input type="checkbox" name="day[1]" id="sunday" {{ isset($day[1]['name']) && $day[1]['name'] == 'sunday' ? 'checked' : '' }} />
                      <input type="hidden" name="day[1][name]" id="sunday" value="sunday" />
                      <label for="sunday">@lang('site.sunday')</label>
                      <div class="form-inline pull-right float1" >
                        <div class="form-group">
                          <input type="text" class="form-control timepicker" name="day[1][slot_start]" id="day_1_slot_start" value="{{isset($day[1]['slot_start']) ? $day[1]['slot_start'] : ''}}" style="margin-bottom: 0px; width:42%;" placeholder="start Time" readonly>
                          <input type="text" class="form-control timepicker" name="day[1][slot_end]" id="day_1_slot_end" value="{{isset($day[1]['slot_end']) ? $day[1]['slot_end'] : ''}}" style="margin-bottom: 0px; width: 42%;" placeholder="End Time" readonly>
                        </div>
                      </div>
                  </div>
                  <div class="list-group-item float">
                      <input type="checkbox" name="day[2]" id="monday" {{ isset($day[2]['name']) && $day[2]['name'] == 'monday'  ? 'checked' : '' }} />
                      <input type="hidden" name="day[2][name]" id="monday" value="monday" />
                      <label for="monday">@lang('site.monday')</label>
                      <div class="form-inline pull-right float1" >
                        <div class="form-group">
                          <input type="text" class="form-control timepicker" name="day[2][slot_start]" id="day_2_slot_start" value="{{isset($day[2]['slot_start']) ? $day[2]['slot_start'] : ''}}" style="margin-bottom: 0px; width:42%;" placeholder="start Time" readonly>
                          <input type="text" class="form-control timepicker" name="day[2][slot_end]" id="day_2_slot_end" value="{{isset($day[2]['slot_end']) ? $day[2]['slot_end'] : ''}}" style="margin-bottom: 0px; width: 42%;" placeholder="End Time" readonly>
                        </div>
                      </div>
                  </div>
                  <div class="list-group-item float">
                      <input type="checkbox" name="day[3]" id="tuesday" {{ isset($day[3]['name']) && $day[3]['name'] == 'tuesday'  ? 'checked' : '' }} />
                      <input type="hidden" name="day[3][name]" id="tuesday" value="tuesday" />
                      <label for="tuesday">@lang('site.tuesday')</label>
                      <div class="form-inline pull-right float1" >
                        <div class="form-group">
                          <input type="text" class="form-control timepicker" name="day[3][slot_start]" id="day_3_slot_start" value="{{isset($day[3]['slot_start']) ? $day[3]['slot_start'] : ''}}" style="margin-bottom: 0px; width:42%;" placeholder="start Time" readonly>
                          <input type="text" class="form-control timepicker" name="day[3][slot_end]" id="day_3_slot_end" value="{{isset($day[3]['slot_end']) ? $day[3]['slot_end'] : ''}}" style="margin-bottom: 0px; width: 42%;" placeholder="End Time" readonly>
                        </div>
                      </div>
                  </div>
                  <div class="list-group-item float">
                      <input type="checkbox" name="day[4]" id="wednesday" {{ isset($day[4]['name']) && $day[4]['name'] == 'wednesday'  ? 'checked' : '' }} />
                      <input type="hidden" name="day[4][name]" id="wednesday" value="wednesday" />
                      <label for="wednesday">@lang('site.wednesday')</label>
                      <div class="form-inline pull-right float1" >
                        <div class="form-group">
                          <input type="text" class="form-control timepicker" name="day[4][slot_start]" id="day_4_slot_start" value="{{isset($day[4]['slot_start']) ? $day[4]['slot_start'] : ''}}" style="margin-bottom: 0px; width:42%;" placeholder="start Time" readonly>
                          <input type="text" class="form-control timepicker" name="day[4][slot_end]" id="day_4_slot_end"value="{{isset($day[4]['slot_end']) ? $day[4]['slot_end'] : ''}}"  style="margin-bottom: 0px; width: 42%;" placeholder="End Time" readonly>
                        </div>
                      </div>
                  </div>
                  <div class="list-group-item float">
                      <input type="checkbox" name="day[5]" id="thursday" {{ isset($day[5]['name']) && $day[5]['name'] == 'thursday'  ? 'checked' : '' }} />
                      <input type="hidden" name="day[5][name]" id="thursday" value="thursday" />
                      <label for="thursday">@lang('site.thursday')</label>
                      <div class="form-inline pull-right float1" >
                        <div class="form-group">
                          <input type="text" class="form-control timepicker" name="day[5][slot_start]" id="day_5_slot_start" value="{{isset($day[5]['slot_start']) ? $day[5]['slot_start'] : ''}}" style="margin-bottom: 0px; width:42%;" placeholder="start Time" readonly>
                          <input type="text" class="form-control timepicker" name="day[5][slot_end]" id="day_5_slot_end" value="{{isset($day[5]['slot_end']) ? $day[5]['slot_end'] : ''}}" style="margin-bottom: 0px; width: 42%;" placeholder="End Time" readonly>
                        </div>
                      </div>
                  </div>
                  <div class="list-group-item float">
                      <input type="checkbox" name="day[6]" id="friday" {{ isset($day[6]['name']) && $day[6]['name'] == 'friday'  ? 'checked' : '' }} />
                      <input type="hidden" name="day[6][name]" id="friday" value="friday" />
                      <label for="friday">@lang('site.friday')</label>
                      <div class="form-inline pull-right float1" >
                        <div class="form-group">
                          <input type="text" class="form-control timepicker" name="day[6][slot_start]" id="day_6_slot_start" value="{{isset($day[6]['slot_start']) ? $day[6]['slot_start'] : ''}}" style="margin-bottom: 0px; width:42%;" placeholder="start Time" readonly>
                          <input type="text" class="form-control timepicker" name="day[6][slot_end]" id="day_6_slot_end" value="{{isset($day[6]['slot_end']) ? $day[6]['slot_end'] : ''}}" style="margin-bottom: 0px; width: 42%;" placeholder="End Time" readonly>
                        </div>
                      </div>
                  </div>
                  <div class="list-group-item float">
                      <input type="checkbox" name="day[7]" id="saturday" {{ isset($day[7]['name']) && $day[7]['name'] == 'saturday'  ? 'checked' : '' }} />
                      <input type="hidden" name="day[7][name]" id="saturday" value="saturday" />
                      <label for="saturday">@lang('site.saturday')</label>
                      <div class="form-inline pull-right ml-3 float1" >
                        <div class="form-group ">
                          <input type="text" class="form-control timepicker" name="day[7][slot_start]" id="day_7_slot_start" value="{{isset($day[7]['slot_start']) ? $day[7]['slot_start'] : ''}}"   style="margin-bottom: 0px; width:42%;" placeholder="start Time" readonly>
                          <input type="text" class="form-control timepicker" name="day[7][slot_end]" id="day_7_slot_end" value="{{isset($day[7]['slot_end']) ? $day[7]['slot_end'] : ''}}" style="margin-bottom: 0px; width: 42%;" placeholder="End Time" readonly>
                        </div>
                      </div>
                  </div>
                 <div class="col-md-12">
                    <div class="rightfloat">
                        <a href="{{ url('outlet') }}" class="btn m-b-xs w-xs btn-danger  maring-top1 pull-right">Cancel</a>&nbsp;&nbsp;
                        <button type="submit" class="btn m-b-xs w-xs btn-success  maring-top1 pull-left">@lang('site.save')</button>
                    </div>
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
</div>
--}}
        <!-- </div> -->
    <!-- </div> -->

<!-- </div> -->

@endsection @section('scripts')
  <!-- //business_hours_start -->
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

    $('#enable_business_hours').on('change',function(){
        if($("#enable_business_hours").is(':checked')){
            $('.enable_business_hours').show();
        }else{
            $('.enable_business_hours').hide();

        }
    })
    $('#set_daily').on('change',function(){
        if($("#set_daily").is(':checked')){
            check(condition=true);
            let slot_start = $('#day_0_slot_start').val();
            let slot_end = $('#day_0_slot_end').val();
            setDaily(slot_start,slot_end)
        }else{
            setDaily(slot_start='',slot_end='');
            check(condition=false);
        }
    })
    function check(condition){
        $("#sunday").prop('checked',condition);
        $("#monday").prop('checked',condition);
        $("#tuesday").prop('checked',condition);
        $("#wednesday").prop('checked',condition);
        $("#thursday").prop('checked',condition);
        $("#friday").prop('checked',condition);
        $("#saturday").prop('checked',condition);
    }
    function setDaily(slot_start,slot_end){
        $('#day_1_slot_start').val(slot_start);
        $('#day_1_slot_end').val(slot_end);
        $('#day_2_slot_start').val(slot_start);
        $('#day_2_slot_end').val(slot_end);
        $('#day_3_slot_start').val(slot_start);
        $('#day_3_slot_end').val(slot_end);
        $('#day_4_slot_start').val(slot_start);
        $('#day_4_slot_end').val(slot_end);
        $('#day_5_slot_start').val(slot_start);
        $('#day_5_slot_end').val(slot_end);
        $('#day_6_slot_start').val(slot_start);
        $('#day_6_slot_end').val(slot_end);
        $('#day_7_slot_start').val(slot_start);
        $('#day_7_slot_end').val(slot_end);
    }
</script>
    <!-- //business_hours_end -->

<
@endsection
