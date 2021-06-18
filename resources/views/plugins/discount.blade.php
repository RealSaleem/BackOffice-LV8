@extends('layouts.backoffice')
@section('content')
<!-- content -->

@php
$access_token = \Session()->get('discount_token');
$api_url = \Session()->get('discount_api_url');
@endphp

@include('plugins.delete-modal')
@include('plugins.new-voucher-modal')


<div class="row">
    <div class="col-sm-12">
        <div class="greybg1 rounded p-4 mb-3">
            <div class="row">
                <div class="col-sm-12">
                    <div class="common_title">
                        <h1>
                            {{ __('plugins.discount') }}
                            <span class="pull-right">
                                <a href="{{ route('plugins.index')}}"
                                    class="m-b-xs w-auto btn-primary btn-sm pull-right">
                                    {{ __('plugins.back') }}
                                </a>
                                {{-- <a href="javascript:void(0);" class="m-b-xs w-auto btn-primary btn-new-voucher btn-sm pull-right">
                                    New Voucher
                                </a> --}}
                                <button type="button" class="m-b-xs w-auto btn-primary btn-new-voucher btn-sm pull-right" data-toggle="modal" data-target="#newVoucher">

                                    New Voucher

                                  </button>
                            </span>
                        </h1>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>
<!--=================

  Main Wrapper Starts

  ==================-->

<!-- <div class="row">
    <div class="col-sm-6">
        <div class="card bg-light mt-3 pl-3 pr-3  rounded  border-0">
            <div class="card-body">
                <h2 class="mt-3 mb-3">User Detail</h2>

                <form>
                    <div class="form-group row">
                        <label for="staticUsername" class="col-sm-4 col-form-label">Username</label>
                        <div class="col-sm-8">
                            <input type="text" readonly class="form-control-plaintext" id="staticUsername">
                        </div>
                    </div>

                    <div class="form-group row">
                        <label for="staticEmail" class="col-sm-4 col-form-label">Email</label>
                        <div class="col-sm-8">
                            <input type="text" readonly class="form-control-plaintext" id="staticEmail">
                        </div>
                    </div>

                    <div class="form-group row">
                        <label for="staticDomain" class="col-sm-4 col-form-label">Domain</label>
                        <div class="col-sm-8">
                            <input type="text" readonly class="form-control-plaintext" id="staticDomain">
                        </div>
                    </div>

                    <div class="form-group row">
                        <label for="staticStatus" class="col-sm-4 col-form-label">Status</label>
                        <div class="col-sm-8">
                            <span class="badge badge-info" id="staticStatus"></span>
                        </div>
                    </div>

                </form>
            </div>
        </div>
    </div>
    <div class="col-sm-6">
        <div class="card bg-light mt-3 pl-3 pr-3  rounded  border-0">
            <div class="card-body">
                <h2 class="mt-3 mb-3">Settings</h2>

                <form>
                    <div class="form-group">
                        <div class="custom-control custom-switch">
                            <input type="checkbox" class="custom-control-input" id="enableSwitch" />
                            <label class="custom-control-label" for="enableSwitch">Enable Voucher System</label>
                        </div>
                    </div>
                </form>
            </div>
        </div>
    </div>
</div> -->

<div class="card bg-light mt-3 pl-3 pr-3  rounded  border-0">
    <div class="row">
        <div class="card-body">
            <div class="row">
                <div class="col-sm-12">
                    <h2 class="mt-3 mb-3">All Vouchers</h2>

                    <div class=" table-responsive">
                        <table class="table table-hover" id="vouchers">
                            <thead>
                                <tr>
                                    <th scope="col">Code</th>
                                    <th scope="col">Description</th>
                                    {{-- <th scope="col">Free Delivery</th> --}}
                                    <th scope="col">Discount Type</th>
                                    <th scope="col">Discount Amount</th>
                                    <th scope="col">Start From</th>
                                    <th scope="col">Expire On</th>
                                    {{-- <th scope="col">Happy Hour</th> --}}
                                    <th scope="col">Redemption Limit</th>
                                    {{-- <th scope="col">Limit Per Customer</th> --}}
                                    <th scope="col">Minimum Order Amount</th>
                                    <th scope="col">Status</th>
                                    {{-- <th scope="col">created_at</th> --}}
                                    <th width="105">Action</th>
                                </tr>
                            </thead>
                        </table>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>

<!--=================

    Ends

    ==================-->





@endsection
@section('scripts')
<script>

    $(document).ready(function() {

        // API URL and Access Token Init
        let token = "{!! $access_token !!}";
        let api_url = "{!! $api_url !!}";


        // Daterange Init
        $('input[name="daterange"]').daterangepicker({
	      autoUpdateInput: false,
          drops: 'up',
	      locale: {
	          cancelLabel: 'Clear',
	          format: "YYYY-MM-DD"
	      }
		});

		$('input[name="daterange"]').on('apply.daterangepicker', function(ev, picker) {
			$(this).val(picker.startDate.format('YYYY-MM-DD') + ' - ' + picker.endDate.format('YYYY-MM-DD'));
		});

		$('input[name="daterange"]').on('cancel.daterangepicker', function(ev, picker) {
			$(this).val('');
		});


        // New Voucher Form Submission
        $('#form-new-voucher').on('submit', function(e) {
            e.preventDefault();

            /*let free_delivery;
            if($('input[name=discount_free_delivery]').prop("checked") == true){
                free_delivery = 1;
            }
            else if($('input[name=discount_free_delivery]').prop("checked") == false){
                free_delivery = 0;
            }*/
            
            /* commented by irfan */
            /*let happy_hour;
            if($('input[name=discount_happy_hour]').prop("checked") == true){
                happy_hour = 1;
            }
            else if($('input[name=discount_happy_hour]').prop("checked") == false){
                happy_hour = 0;
            }*/
            let happy_hour = 0;
            let free_delivery = 0;

            let date_range = $('input[name=daterange]').val();
            let date_range_arr = date_range.split(' - ');
            let duration_start = date_range_arr[0];
            let duration_end = date_range_arr[1];


            var form = new FormData();
            form.append("code", $('input[name=discount_code]').val());
            form.append("description", $('textarea[name=discount_description]').val());
            form.append("free_delivery", free_delivery);
            form.append("discount_type", $('input[name=discount_type]:checked').val());
            form.append("discount_amount", $('input[name=discount_amount]').val());
            form.append("duration_start", duration_start);
            form.append("duration_end", duration_end);
            form.append("happy_hour", happy_hour);
            form.append("redemption_limit", $('input[name=discount_max_redemptions]').val());
            // form.append("limit_per_customer", $('input[name=discount_limit_per_customer]').val());
            form.append("limit_per_customer", 0);
            form.append("minimum_order_amount", $('input[name=discount_min_order_amount]').val());

            var settings = {
            "url": api_url + "vouchers",
            "method": "POST",
            "timeout": 0,
            "headers": {
                "Authorization": "Bearer " + token,
                "Accept": "application/json"
            },
            "processData": false,
            "mimeType": "multipart/form-data",
            "contentType": false,
            "data": form
            };

            $.ajax(settings).done(function (jqXHR, textStatus) {
                let response = JSON.parse(jqXHR);
                if(response.status) {
                    alert(response.message);
                    location.reload();
                }
            }).fail(function (jqXHR, textStatus) {
                let response = JSON.parse(jqXHR.responseText);
                alert(response.errors.code);
            });

        });

        let url_parts = api_url.split('/api',10);
        let base_url = url_parts[0];
        let verification_settings = {
            "url": base_url+"/verify/token",
            "method": "GET",
            "timeout": 0,
            "headers": {
            "Authorization": "Bearer " + token,
            "Accept": "application/json"
            },
        };

    $.ajax(verification_settings).done(function (response) {
        if(!response.status) {
            alert('API access token expired!, please login again.');
        }
    });

      // Get All Vouchers
      let vouchers_settings = {
        "url": api_url + "vouchers",
        "method": "GET",
        "timeout": 0,
        "headers": {
          "Authorization": "Bearer " + token,
          "Accept": "application/json"
        },
      };

      $.ajax(vouchers_settings).done(function (response) {
        $('#vouchers').DataTable({
            data: response.data,
            columns: [
                { data: "code" },
                { data: "description" },
                //{ data: "free_delivery" },
                { data: "discount_type", render: function(column, data, row) {
                      return row.discount_type == 1 ? `Amount` : `Percentage`;
                    }
                },
                { data: "discount_amount" },
                { data: "duration_start" },
                { data: "duration_end" },
                //{ data: "happy_hour" },
                { data: "redemption_limit" },
                //{ data: "limit_per_customer" },
                { data: "minimum_order_amount" },
                { data: "status", render: function(column, data, row) {
                  return row.status ? `<span class="badge badge-success">Active</span>` : `<span class="badge badge-secondary">Inactive</span>`;
                } },
                // { data: "created_at", render: function(column, data, row) {
                //   return moment(row.created_at).format('DD-MM-YYYY');
                // } },
                { data: "action", render: function(column, data, row) {
                    let avtivate_btn = (row.status) ? `<a href="javascript:void(0);" class="badge btn-primary btn-activate" data-vid="${row.id}" data-status="${row.status ? 0 : 1}">Deactivate</a>` : `<a href="javascript:void(0);" class="badge btn-primary btn-activate" data-vid="${row.id}" data-status="${row.status ? 0 : 1}">Activate</a>`;
                    let delete_btn = `
                              <a href="javascript:void(0);" class="badge btn-dark btn-delete" data-vid="${row.id}">Delete</a>`;
                    return avtivate_btn+delete_btn;
                } },
            ]
        });
      });
      // *** End *** //

      // Get Current User
      let user_settings = {
        "url": api_url + "user",
        "method": "GET",
        "timeout": 0,
        "headers": {
          "Authorization": "Bearer " + token,
          "Accept": "application/json"
        },
      };

      $.ajax(user_settings).done(function (response) {
        let data = response.data[0];
        $('#staticUsername').val(data.username);
        $('#staticEmail').val(data.email);
        $('#staticDomain').val(data.domain);
        $('#staticStatus').html(data.status == 1 ? 'Active' : 'Inactive');
      });
      // *** End *** //

      // Get App Settings
      let app_settings = {
        "url": api_url + "settings",
        "method": "GET",
        "timeout": 0,
        "headers": {
          "Authorization": "Bearer " + token,
          "Accept": "application/json"
        },
      };

      $.ajax(app_settings).done(function (response) {
        let data = response.data[0];
        if(data.enable_disable_system == 1) {
          $('#enableSwitch').prop("checked", true);
        } else {
          $('#enableSwitch').prop("checked", false);
        }
      });
      // *** End *** //

      // Enable/Disable Voucher System Settings
      $('#enableSwitch').change(function() {
        let status;
        if($(this).prop("checked") == true){
              status = 1;
          }
          else if($(this).prop("checked") == false){
              status = 0;
          }

          let voucher_settings = {
          "url": api_url + "settings/" + status,
          "method": "PUT",
          "timeout": 0,
          "headers": {
            "Authorization": "Bearer " + token,
            "Accept": "application/json"
          },
        };

        $.ajax(voucher_settings).done(function (response) {
          // console.log(response.status);
          alert('Voucher system updated');
        });

      });
      // *** End *** //

      // Activate/Deactivate Voucher
      $(document).on('click', '.btn-activate', function() {
        let vid = $(this).data('vid');
        let status = $(this).data('status');

          let enable_voucher_settings = {
          "url": api_url + "vouchers/"+vid+"/"+status,
          "method": "PUT",
          "timeout": 0,
          "headers": {
            "Authorization": "Bearer " + token,
            "Accept": "application/json"
          },
        };

        $.ajax(enable_voucher_settings).done(function (response) {
          if(response.status.success != null && response.status.success == 200) {
            alert('Voucher status updated');
            location.reload();
          }
        });

      });
      // *** End *** //

      // Delete Voucher
      $(document).on('click', '.btn-delete', function() {
        let vid = $(this).data('vid');

          let delete_voucher_settings = {
          "url": api_url + "vouchers/"+vid,
          "method": "DELETE",
          "timeout": 0,
          "headers": {
            "Authorization": "Bearer " + token,
            "Accept": "application/json"
          },
        };

        $.ajax(delete_voucher_settings).done(function (response) {
          if(response.status.success != null && response.status.success == 200) {
            alert('Voucher deleted');
            location.reload();
          }
        });

      });
      // *** End *** //

      // Logout
      $('#btn-logout').click(function() {
        localStorage.removeItem('xcsrf');
        alert('Logout successful!');
        window.location = "/login";
      });
      // *** End *** //

    });

</script>
@endsection
