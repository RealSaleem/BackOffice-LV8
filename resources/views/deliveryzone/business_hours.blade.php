
{{--
  <!-- <div class="col-md-12"> -->
      <!-- <div class="panel panel-body"> -->
          <!-- <div class="row"> -->
            <label>@lang('site.Business_hours') </label>
            <div class="list-group ">
                <div class="list-group-item float">
                    <label >@lang('site.set_daily')</label>
                    <div class="form-inline pull-right float1 " >
                        <input type="hidden" name="day[0][set_daily]" id="set_daily" value="set_daily" />
                      <div class="form-group  ">
                        <input type="text" class="form-control timepicker" name="day[0][slot_start]" value="{{isset($day[0]['slot_start']) ? $day[0]['slot_start'] : ''}}" style="margin-bottom: 0px; width:42%;" placeholder="start Time" readonly>
                        <input type="text" class="form-control timepicker" name="day[0][slot_end]" value="{{isset($day[0]['slot_end']) ? $day[0]['slot_end'] : ''}}" style="margin-bottom: 0px; width: 42%;" placeholder="End Time" readonly>
                      </div>
                    </div>
                </div>
                <div class="list-group-item float">
                    <input type="checkbox" name="day[1]" id="sunday" {{ isset($day[1]['name']) && $day[1]['name'] == 'sunday' ? 'checked' : '' }} />
                    <input type="hidden" name="day[1][name]" id="sunday" value="sunday" />
                    <label for="sunday">@lang('site.sunday')</label>
                    <div class="form-inline pull-right float1" >
                      <div class="form-group">
                        <input type="text" class="form-control timepicker" name="day[1][slot_start]" value="{{isset($day[1]['slot_start']) ? $day[1]['slot_start'] : ''}}" style="margin-bottom: 0px; width:42%;" placeholder="start Time" readonly>
                        <input type="text" class="form-control timepicker" name="day[1][slot_end]" value="{{isset($day[1]['slot_end']) ? $day[1]['slot_end'] : ''}}" style="margin-bottom: 0px; width: 42%;" placeholder="End Time" readonly>
                      </div>
                    </div>
                </div>
                <div class="list-group-item float">
                    <input type="checkbox" name="day[2]" id="monday" {{ isset($day[2]['name']) && $day[2]['name'] == 'monday'  ? 'checked' : '' }} />
                    <input type="hidden" name="day[2][name]" id="monday" value="monday" />
                    <label for="monday">@lang('site.monday')</label>
                    <div class="form-inline pull-right float1" >
                      <div class="form-group">
                        <input type="text" class="form-control timepicker" name="day[2][slot_start]" value="{{isset($day[2]['slot_start']) ? $day[2]['slot_start'] : ''}}" style="margin-bottom: 0px; width:42%;" placeholder="start Time" readonly>
                        <input type="text" class="form-control timepicker" name="day[2][slot_end]" value="{{isset($day[2]['slot_end']) ? $day[2]['slot_end'] : ''}}" style="margin-bottom: 0px; width: 42%;" placeholder="End Time" readonly>
                      </div>
                    </div>
                </div>
                <div class="list-group-item float">
                    <input type="checkbox" name="day[3]" id="tuesday" {{ isset($day[3]['name']) && $day[3]['name'] == 'tuesday'  ? 'checked' : '' }} />
                    <input type="hidden" name="day[3][name]" id="tuesday" value="tuesday" />
                    <label for="tuesday">@lang('site.tuesday')</label>
                    <div class="form-inline pull-right float1" >
                      <div class="form-group">
                        <input type="text" class="form-control timepicker" name="day[3][slot_start]" value="{{isset($day[3]['slot_start']) ? $day[3]['slot_start'] : ''}}" style="margin-bottom: 0px; width:42%;" placeholder="start Time" readonly>
                        <input type="text" class="form-control timepicker" name="day[3][slot_end]" value="{{isset($day[3]['slot_end']) ? $day[3]['slot_end'] : ''}}" style="margin-bottom: 0px; width: 42%;" placeholder="End Time" readonly>
                      </div>
                    </div>
                </div>
                <div class="list-group-item float">
                    <input type="checkbox" name="day[4]" id="wednesday" {{ isset($day[4]['name']) && $day[4]['name'] == 'wednesday'  ? 'checked' : '' }} />
                    <input type="hidden" name="day[4][name]" id="wednesday" value="wednesday" />
                    <label for="wednesday">@lang('site.wednesday')</label>
                    <div class="form-inline pull-right float1" >
                      <div class="form-group">
                        <input type="text" class="form-control timepicker" name="day[4][slot_start]" value="{{isset($day[4]['slot_start']) ? $day[4]['slot_start'] : ''}}" style="margin-bottom: 0px; width:42%;" placeholder="start Time" readonly>
                        <input type="text" class="form-control timepicker" name="day[4][slot_end]"value="{{isset($day[4]['slot_end']) ? $day[4]['slot_end'] : ''}}"  style="margin-bottom: 0px; width: 42%;" placeholder="End Time" readonly>
                      </div>
                    </div>
                </div>
                <div class="list-group-item float">
                    <input type="checkbox" name="day[5]" id="thursday" {{ isset($day[5]['name']) && $day[5]['name'] == 'thursday'  ? 'checked' : '' }} />
                    <input type="hidden" name="day[5][name]" id="thursday" value="thursday" />
                    <label for="thursday">@lang('site.thursday')</label>
                    <div class="form-inline pull-right float1" >
                      <div class="form-group">
                        <input type="text" class="form-control timepicker" name="day[5][slot_start]" value="{{isset($day[5]['slot_start']) ? $day[5]['slot_start'] : ''}}" style="margin-bottom: 0px; width:42%;" placeholder="start Time" readonly>
                        <input type="text" class="form-control timepicker" name="day[5][slot_end]" value="{{isset($day[5]['slot_end']) ? $day[5]['slot_end'] : ''}}" style="margin-bottom: 0px; width: 42%;" placeholder="End Time" readonly>
                      </div>
                    </div>
                </div>
                <div class="list-group-item float">
                    <input type="checkbox" name="day[6]" id="friday" {{ isset($day[6]['name']) && $day[6]['name'] == 'friday'  ? 'checked' : '' }} />
                    <input type="hidden" name="day[6][name]" id="friday" value="friday" />
                    <label for="friday">@lang('site.friday')</label>
                    <div class="form-inline pull-right float1" >
                      <div class="form-group">
                        <input type="text" class="form-control timepicker" name="day[6][slot_start]" value="{{isset($day[6]['slot_start']) ? $day[6]['slot_start'] : ''}}" style="margin-bottom: 0px; width:42%;" placeholder="start Time" readonly>
                        <input type="text" class="form-control timepicker" name="day[6][slot_end]" value="{{isset($day[6]['slot_end']) ? $day[6]['slot_end'] : ''}}" style="margin-bottom: 0px; width: 42%;" placeholder="End Time" readonly>
                      </div>
                    </div>
                </div>
                <div class="list-group-item float">
                    <input type="checkbox" name="day[7]" id="saturday" {{ isset($day[7]['name']) && $day[7]['name'] == 'saturday'  ? 'checked' : '' }} />
                    <input type="hidden" name="day[7][name]" id="saturday" value="saturday" />
                    <label for="saturday">@lang('site.saturday')</label>
                    <div class="form-inline pull-right ml-3 float1" >
                      <div class="form-group ">
                        <input type="text" class="form-control timepicker" name="day[7][slot_start]" value="{{isset($day[7]['slot_start']) ? $day[7]['slot_start'] : ''}}"   style="margin-bottom: 0px; width:42%;" placeholder="start Time" readonly>
                        <input type="text" class="form-control timepicker" name="day[7][slot_end]" value="{{isset($day[7]['slot_end']) ? $day[7]['slot_end'] : ''}}" style="margin-bottom: 0px; width: 42%;" placeholder="End Time" readonly>
                      </div>
                    </div>
                </div>
             </div>


          <!-- </div> -->
      <!-- </div> -->

  <!-- </div> -->
--}}
{{--    @dd($day[0]['set_daily'])--}}
<label>@lang('site.Business_hours') </label>
<div class="list-group ">
  <div class="list-group-item float">
    <input type="checkbox" name="day[0][set_daily]" id="set_daily"   {{ isset($day[0]['set_daily']) && $day[0]['set_daily'] == 'set_daily' ? 'checked' : '' }} />
      <label for="set_daily">@lang('site.set_daily')</label>
      <div class="form-inline pull-right float1 " >
          <input type="hidden" name="day[0][set_daily]" id="set_daily" />
{{--          <input type="hidden" name="day[0][active]" id="set_daily" class="setdaily"  />--}}
        <div class="form-group  ">
          <input type="text" class="form-control timepicker" name="day[0][slot_start]" id="day_0_slot_start" value="{{isset($day[0]['slot_start']) ? $day[0]['slot_start'] : ''}}" style="margin-bottom: 0px; width:42%;" placeholder="Start Time" readonly>
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
          <input type="text" class="form-control timepicker" name="day[1][slot_start]" id="day_1_slot_start" value="{{isset($day[1]['slot_start']) ? $day[1]['slot_start'] : ''}}" style="margin-bottom: 0px; width:42%;" placeholder="Start Time" readonly>
          <input type="text" class="form-control timepicker" name="day[1][slot_end]" id="day_1_slot_end" value="{{isset($day[1]['slot_end']) ? $day[1]['slot_end'] : ''}}" style="margin-bottom: 0px; width: 42%;" placeholder="End Time" readonly>
        </div>
      </div>
  </div>
  <div class="list-group-item float">
      <input type="checkbox" name="day[2]" id="monday" {{ isset($day[2]['name']) && $day[2]['name'] == 'monday'  ? 'checked' : '' }} />
      <input type="hidden" name="day[2][name]" id="monday" value="monday"/>
      <label for="monday">@lang('site.monday')</label>
      <div class="form-inline pull-right float1" >
        <div class="form-group">
          <input type="text" class="form-control timepicker" name="day[2][slot_start]" id="day_2_slot_start" value="{{isset($day[2]['slot_start']) ? $day[2]['slot_start'] : ''}}" style="margin-bottom: 0px; width:42%;" placeholder="Start Time" readonly>
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
          <input type="text" class="form-control timepicker" name="day[3][slot_start]" id="day_3_slot_start" value="{{isset($day[3]['slot_start']) ? $day[3]['slot_start'] : ''}}" style="margin-bottom: 0px; width:42%;" placeholder="Start Time" readonly>
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
          <input type="text" class="form-control timepicker" name="day[4][slot_start]" id="day_4_slot_start" value="{{isset($day[4]['slot_start']) ? $day[4]['slot_start'] : ''}}" style="margin-bottom: 0px; width:42%;" placeholder="Start Time" readonly>
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
          <input type="text" class="form-control timepicker" name="day[5][slot_start]" id="day_5_slot_start" value="{{isset($day[5]['slot_start']) ? $day[5]['slot_start'] : ''}}" style="margin-bottom: 0px; width:42%;" placeholder="Start Time" readonly>
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
          <input type="text" class="form-control timepicker" name="day[6][slot_start]" id="day_6_slot_start" value="{{isset($day[6]['slot_start']) ? $day[6]['slot_start'] : ''}}" style="margin-bottom: 0px; width:42%;" placeholder="Start Time" readonly>
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
          <input type="text" class="form-control timepicker" name="day[7][slot_start]" id="day_7_slot_start" value="{{isset($day[7]['slot_start']) ? $day[7]['slot_start'] : ''}}"   style="margin-bottom: 0px; width:42%;" placeholder="Start Time" readonly>
          <input type="text" class="form-control timepicker" name="day[7][slot_end]" id="day_7_slot_end" value="{{isset($day[7]['slot_end']) ? $day[7]['slot_end'] : ''}}" style="margin-bottom: 0px; width: 42%;" placeholder="End Time" readonly>
        </div>
      </div>
  </div>
</div>
