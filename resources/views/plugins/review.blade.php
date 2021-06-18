@extends('layouts.backoffice')
@section('content')
<!-- content -->

@php
$access_token = \Session()->get('review_token');
$api_url = \Session()->get('review_api_url');
@endphp

@include('plugins.delete-modal')


<div class="row">
    <div class="col-sm-12">
        <div class="greybg1 rounded p-4 mb-3">
            <div class="row">
                <div class="col-sm-12">
                    <div class="common_title">
                        <h1>
                            {{ __('plugins.review') }}
                            <span class="pull-right">
                                <a href="{{ route('plugins.index')}}"
                                    class="m-b-xs w-auto btn-primary btn-sm pull-right">
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
<!--=================

  Main Wrapper Starts

  ==================-->

<div class="row">
    <div class="col-sm-6">
        <div class="card bg-light mt-3 pl-3 pr-3  rounded  border-0">
            <div class="card-body">
                <h2 class="mt-3 mb-3">User Detail</h2>

                <form>
                    <div class="form-group row">
                        <label for="staticUsername" class="col-sm-2 col-form-label">Username</label>
                        <div class="col-sm-10">
                            <input type="text" readonly class="form-control-plaintext" id="staticUsername">
                        </div>
                    </div>

                    <div class="form-group row">
                        <label for="staticEmail" class="col-sm-2 col-form-label">Email</label>
                        <div class="col-sm-10">
                            <input type="text" readonly class="form-control-plaintext" id="staticEmail">
                        </div>
                    </div>

                    <div class="form-group row">
                        <label for="staticDomain" class="col-sm-2 col-form-label">Domain</label>
                        <div class="col-sm-10">
                            <input type="text" readonly class="form-control-plaintext" id="staticDomain">
                        </div>
                    </div>

                    <div class="form-group row">
                        <label for="staticStatus" class="col-sm-2 col-form-label">Status</label>
                        <div class="col-sm-10">
                            <!-- <input type="text" readonly class="form-control-plaintext" id="staticStatus"> -->
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
                            <input type="checkbox" class="custom-control-input" id="approveSwitch" />
                            <label class="custom-control-label" for="approveSwitch">Auto approve reviews</label>
                        </div>
                    </div>

                    <!-- <button type="button" class="btn btn-primary">
                    Save
                  </button> -->
                </form>
            </div>
        </div>
    </div>
</div>

<div class="card bg-light mt-3 pl-3 pr-3  rounded  border-0">
    <div class="row">
        <div class="card-body">
            <div class="row">
                <div class="col-sm-12">
                    <h2 class="mt-3 mb-3">All Reviews</h2>

                    <div class=" table-responsive">
                        <table class="table table-hover" id="reviews">
                            <thead>
                                <tr>
                                    <th scope="col">product_id</th>
                                    <th scope="col">variant_id</th>
                                    <th scope="col">rating</th>
                                    <th scope="col">review</th>
                                    <th scope="col">status</th>
                                    <th scope="col">image</th>
                                    <th scope="col">name</th>
                                    <th scope="col">thumbs_up</th>
                                    <th scope="col">thumbs_down</th>
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
      let token = "{!! $access_token !!}";
      let api_url = "{!! $api_url !!}";

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

      // Get All Reviews
      let reviews_settings = {
          "url": api_url + "reviews",
          "method": "GET",
          "timeout": 0,
          "headers": {
            "Authorization": "Bearer " + token,
            "Accept": "application/json"
          },
        };

        $.ajax(reviews_settings).done(function (response) {
          $('#reviews').DataTable({
              data: response.data,
              columns: [
                  { data: "product_id" },
                  { data: "variant_id" },
                  { data: "rating" },
                  { data: "review" },
                  { data: "status", render: function(column, data, row) {
                    return row.status ? `<span class="badge badge-success">Approved</span>` : `<span class="badge badge-secondary">Unapproved</span>`;
                  } },
                  { data: "image" },
                  { data: "name" },
                  { data: "thumbs_up" },
                  { data: "thumbs_down" },
                //   { data: "created_at", render: function(column, data, row) {
                //     return moment(row.created_at).format('DD-MM-YYYY');
                //   } },
                  { data: "action", render: function(column, data, row) {
                      let approve_btn = (row.status) ? `` : `<a href="javascript:void(0);" class="badge btn-primary btn-approve" data-rid="${row.id}" data-pid="${row.product_id}" data-status="${row.status ? 0 : 1}">Approve</a>`;
                      let delete_btn = `
                                <a href="javascript:void(0);" class="badge btn-dark btn-delete" data-rid="${row.id}" data-pid="${row.product_id}">Delete</a>`;
                      return approve_btn+delete_btn;
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
          if(data.approve_reviews == 1) {
            $('#approveSwitch').prop("checked", true);
          } else {
            $('#approveSwitch').prop("checked", false);
          }
        });
        // *** End *** //

        // Approve Reviews Settings
        $('#approveSwitch').change(function() {
          let status;
          if($(this).prop("checked") == true){
                status = 1;
            }
            else if($(this).prop("checked") == false){
                status = 0;
            }

            let approve_settings = {
            "url": api_url + "settings/" + status,
            "method": "PUT",
            "timeout": 0,
            "headers": {
              "Authorization": "Bearer " + token,
              "Accept": "application/json"
            },
          };

          $.ajax(approve_settings).done(function (response) {
            // console.log(response.status);
            alert('Auto approve reviews setting updated');
          });

        });
        // *** End *** //

        // Approve Reviews
        $(document).on('click', '.btn-approve', function() {
          let rid = $(this).data('rid');
          let pid = $(this).data('pid');
          let status = $(this).data('status');

            let approve_review_settings = {
            "url": api_url + "reviews/product/"+pid+"/"+rid+"/"+status,
            "method": "PUT",
            "timeout": 0,
            "headers": {
              "Authorization": "Bearer " + token,
              "Accept": "application/json"
            },
          };

          $.ajax(approve_review_settings).done(function (response) {
            if(response.status.success != null && response.status.success == 200) {
              alert('Review approved');
              location.reload();
            }
          });

        });
        // *** End *** //

        // Delete Reviews
        $(document).on('click', '.btn-delete', function() {
          let rid = $(this).data('rid');
          let pid = $(this).data('pid');

            let delete_review_settings = {
            "url": api_url + "reviews/product/"+pid+"/"+rid,
            "method": "DELETE",
            "timeout": 0,
            "headers": {
              "Authorization": "Bearer " + token,
              "Accept": "application/json"
            },
          };

          $.ajax(delete_review_settings).done(function (response) {
            if(response.status.success != null && response.status.success == 200) {
              alert('Review deleted');
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
