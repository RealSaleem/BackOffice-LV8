@extends('layouts.backoffice')
@section('content')
    @php
        $permission = '\App\Helpers\Helper';
    @endphp
<!-- content -->

@include('catalogue.product.delete-modal')

<div class="row">
   <div class="col-sm-12">
      <div class="greybg1 rounded p-4 mb-3">
         <div class="row">
            <div class="col-sm-12">
               <div class="common_title">
                  <h1>
                     {{ __('backoffice.products') }}

                  </h1>
                  <div class="filter-icons ">
                      @if( $permission::chekStatus('product_add','admin'))
                     <a href="{{ route('product.create')}}" class="m-b-xs w-auto btn-primary btn-sm pull-right">
                        {{ __('backoffice.add_item') }}
                     </a>
                      @endif
                          @if( $permission::chekStatus('import_catalogue','admin'))
                     <a href="{{ route('import.products')}}" class="m-b-xs w-auto btn-primary btn-sm pull-right">
                        {{ __('backoffice.import_product') }}
                     </a>
                          @endif
                     <a href="javascript:;" class="text-primary btn-link btn-lg pull-right d-inline-block d-md-none d-lg-none pt-0 pr-2">
                        <i class="fa fa-filter"></i>
                     </a>
                  </div>
               </div>
            </div>
         </div>
      </div>
      <div class="bg-light p-4 rounded">
         <div class=" rounded">
            <div class="row">
               <div class="col-md-12">
                  <div class="bg-light rounded">

                     <div class="greybg1 rounded date-filter">
                        <div class="bg-light p-4 rounded">
                           <form>
                              <div class="row">
                                 <div class="col-md-3">
                                    <label>{{ __('backoffice.name_sku') }}</label>
                                    <input type="text" class="form-control" id="filter_name" placeholder="Samsung or FK3001" >
                                 </div>
                                 <div class="col-md-3">
                                    <label>{{ __('backoffice.category') }}</label>
                                    <select id="filter_category" class="form-control rounded custom-select">
                                       <option value="">{{ __('backoffice.select')  }}</option>
                                       @foreach($categories as $category)
                                       <option value="{{ $category->id }}">{{ $category->name }}</option>
                                       @endforeach
                                    </select>
                                 </div>
                                 <div class="col-md-3">
                                    <label>{{ __('backoffice.brand') }}</label>
                                    <select id="filter_brand" class="form-control rounded custom-select">
                                    <option value="">{{ __('backoffice.select')  }}</option>
                                       @foreach($brands as $brand)
                                       <option value="{{ $brand->id }}">{{ $brand->name }}</option>
                                       @endforeach
                                    </select>
                                 </div>
                                 <div class="col-md-3">
                                    <label>{{ __('backoffice.supplier') }}</label>
                                    <select id="filter_supplier" class="form-control rounded custom-select">
                                    <option value="">{{ __('backoffice.select') }}</option>
                                       @foreach($suppliers as $supplier)
                                       <option value="{{ $supplier->id }}">{{ $supplier->name }}</option>
                                       @endforeach
                                    </select>
                                 </div>
                                 <div class="col-md-3 mt-3">
                                    <label>{{ __('backoffice.stock_range') }}</label>
                                    <input type="text" class="form-control" id="filter_stock_range" placeholder="0 - 100">
                                 </div>
                                 <div class="col-md-3 mt-3">
                                    <label>{{ __('backoffice.price_range') }}</label>
                                    <input type="text" class="form-control" id="filter_price_range" placeholder="0 - 500">
                                 </div>
                                 <div class="col-md-12 pt-3 align-right">
                                    <button type="button" onclick="applyFilters()" class="btn m-b-xs w-xs btn btn-primary rightfloat  maring-top1">{{ __('backoffice.apply_filters')  }}</button>
                                    <button type="reset" onclick="formReset()" class="btn m-b-xs w-xs btn btn-light  rightfloat maring-top1 margen-left">{{ __('backoffice.clear')  }}</button>
                                 </div>
                              </div>
                              <div class="clearfix"></div>
                           </form>
                        </div>
                     </div>
                     <hr>
                     <div class=" table-responsive table-responsive2">
                         @if( $permission::chekStatus('product_edit','admin'))
                        <div class="btn-toolbar" role="toolbar" aria-label="Toolbar with button groups">

                           <div class="btn-group mr-2" role="group" aria-label="First group">
                              <div class="custom-control custom-checkbox header-checkbox">
                                 <input type="checkbox" class="custom-control-input" id="product-all">
                                 <label class="custom-control-label" for="product-all">&nbsp;</label>
                              </div>
                              <select class="custom-select" id="bulk-action">
                                 <option value="">{{ __('backoffice.bulk_actions') }}</option>

                                 @if($has_pos)
                                 <option value="3">{{ __('backoffice.enable_pos') }}</option>
                                 <option value="4">{{ __('backoffice.disable_pos') }}</option>
                                 @endif

                                 @if($has_website)
                                 <option value="5">{{ __('backoffice.enable_website') }}</option>
                                 <option value="6">{{ __('backoffice.disable_website') }}</option>
                                 @endif

                                 @if($has_dinein_catalogue || $has_dinein_catalogue)
                                 <option value="1">{{ __('backoffice.enable_dinein') }}</option>
                                 <option value="2">{{ __('backoffice.disable_dinein') }}</option>
                                 @endif

                                 <option value="7">{{ __('backoffice.activate') }}</option>
                                 <option value="8">{{ __('backoffice.deactivate') }}</option>
                                 <option value="9">{{ __('backoffice.delete') }}</option>
                              </select>
                              <button type="button" class="btn btn-primary btn-xs btn-bulk ml-3" id="bulk-apply">Apply</button>
                           </div>
                        </div>
                         @endif
                        <hr />
                        <table class="table table-striped" id="product-table">
                           <thead>
                              <tr>
                                 <th style="width:5%">&nbsp;</th>
                                 <th style="width:20%">{{ __('backoffice.item') }} / {{ __('backoffice.sku') }} </th>
                                 <th style="width:10%">{{ __('backoffice.retail_price') }} </th>
                                 <th style="width:10%">{{ __('backoffice.stock') }} </th>

                                 @if($has_pos)
                                 <th style="width:10%">{{ __('backoffice.pos') }}</th>
                                 @endif

                                 @if($has_website)
                                 <th style="width:10%">{{ __('backoffice.website') }} </th>
                                 @endif

                                 @if($has_dinein_catalogue || $has_dinein_catalogue)
                                 <th style="width:10%">{{ __('backoffice.catalogue_app') }} </th>
                                 @endif

                                 <th style="width:10%">{{ __('backoffice.active') }} </th>
                                 <th style="width:15%">{{ __('backoffice.actions') }} </th>
                              </tr>
                           </thead>
                        </table>
                        <hr />
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
<script type="text/javascript">
   var productTable = null;

   function format(d) {

      let outlets = d.outlets.map(outlet => `<th>${outlet}</th>`);

      let variants = d.variants.map(variant => {
         let stocks = variant.stocks.map(stock => `<td>${stock}</td>`);
         return `<tr class="expended-row-inner"><td><span>${variant.name}<span><hr /><span class="badge badge-dark">{{ __('backoffice.sku_sm') }} : ${variant.sku}</span></td><td>${variant.price}</td><td><span class="badge badge-${ d.threshold < variant.stock ? "success" : "danger" }">${variant.stock}</span></td>${stocks.join('')}</tr>`;
      });

      return `
            <table cellpadding="5" cellspacing="0" border="0" style="padding-left:50px;" class="table">
               <tr class="expended-row-inner">
                  <th>{{ __('backoffice.item') }} / {{ __('backoffice.sku') }}</th>
                  <th>{{ __('backoffice.retail_price') }} </th>
                  <th>{{ __('backoffice.stock') }} </th>
                  ${outlets.join('')}
               </tr>
               ${variants.join('')}
            </table>
         `;

   }

   $(document).ready(function() {
      productTable = $('#product-table').DataTable({
         "processing": true,
         "serverSide": true,
         "deferLoading": 0,
         "ajax": {
            "url": "{{ route('api.fetch.products') }}",
            "dataType": "json",
            "type": "POST",
            "data": function(d) {
               let filter_name = $('#filter_name').val();
               let filter_stock_range = $('#filter_stock_range').val();
               let filter_price_range = $('#filter_price_range').val();
               let filter_category = $('#filter_category').find(":selected").val();
               let filter_brand = $('#filter_brand').find(":selected").val();
               let filter_supplier = $('#filter_supplier').find(":selected").val();

               if(filter_name != ''){
                  d['filter_name']  = filter_name;
               }

               if(filter_stock_range != ''){
                  d['filter_stock_range']  = filter_stock_range;
               }

               if(filter_price_range != ''){
                  d['filter_price_range']  = filter_price_range;
               }

               if(filter_category != ''){
                  d['filter_category']  = filter_category;
               }

               if(filter_brand != ''){
                  d['filter_brand']  = filter_brand;
               }

               if(filter_supplier != ''){
                  d['filter_supplier']  = filter_supplier;
               }

            }
         },
         "columns": [{
               sortable: false,
               data: 'id',
               'className': 'dt-body-center',
               'render': function(column, row, data, other) {
                  return `

                     <i class="details-control fa fa-chevron-right"></i>
                     <div class="custom-control custom-checkbox" style="float:right;margin-left:5px;">
                        <input type="checkbox" class="custom-control-input product-row " data-row="${other.row}" data-product-id="${data.id}" id="check-${data.id}">
                        <label class="custom-control-label" for="check-${data.id}">&nbsp;</label>
                     </div>
                     `
               }
            },
            {
               data: 'name',
               render: function(column, row, data) {

                  return `
                        <div class="row no-gutters">
                              <div class="col-auto">
                                 <img src="${data.image}" class="img-fluid" alt="" style="width:50px;height:50px;object-fit:cover;">
                              </div>
                              <div class="col">
                                 <div class="card-block px-2">
                                    <span class="card-title">${data.name}</span>
                                    ${ data.variants_count > 0 ? `` : `<hr /><span class="card-title badge badge-dark">{{ __('backoffice.sku_sm') }} : ${data.sku}</span>` }
                                 </div>
                              </div>
                        </div>
                  `;
               }
            },
            {
               data: 'price'
            },
            {
               data: 'stock',
               render: function(column, row, data) {
                  return data.variants_count > 0 ? `<em class="card-title">${data.variants_count} variants</em>` : `<span class="badge badge-${ data.threshold < data.stock ? "success" : "danger" }">${data.stock}</span>`;
               }
            },

            @if($has_pos) {
               data: 'is_featured',
               sortable: false,
               render: function(column, row, data) {
                  return `
@if( $permission::chekStatus('product_edit','admin'))
                  <div class="custom-control custom-switch center-align">
                     <input type="checkbox" class="custom-control-input" id="is_featured-${data.id}" ${ data.pos == 1 ? "checked" : "" } >
                     <label onclick="toggleProduct('is_featured',${data.id})" class="custom-control-label" for="is_featured-${data.id}">&nbsp;</label>
                  </div>
                  @endif
                  `;
               }
            },
            @endif

            @if($has_website) {
               data: 'website',
               sortable: false,
               render: function(column, row, data) {
                  return `
 @if( $permission::chekStatus('product_edit','admin'))
                  <div class="custom-control custom-switch center-align">
                     <input type="checkbox" class="custom-control-input" id="website-${data.id}" ${ data.website == 1 ? "checked" : "" } >
                     <label onclick="toggleProduct('website',${data.id})" class="custom-control-label" for="website-${data.id}">&nbsp;</label>
                  </div>
                  @endif
                  `;
               }
            },
            @endif

            @if($has_dinein_catalogue || $has_dinein_catalogue) {
               data: 'dinein',
               render: function(column, row, data) {
                  return `
@if( $permission::chekStatus('product_edit','admin'))
                  <div class="custom-control custom-switch center-align">
                     <input type="checkbox" class="custom-control-input" id="dine-${data.id}" ${ data.dinein == 1 ? "checked" : "" } >
                     <label onclick="toggleProduct('dinein',${data.id})" class="custom-control-label" for="dine-${data.id}">&nbsp;</label>
                  </div>
                  @endif
                  `;
               }
            },
            @endif {
               data: 'active',
               sortable: false,
               render: function(column, row, data, other) {
                  return `
@if( $permission::chekStatus('product_edit','admin'))
                  <div class="custom-control custom-switch center-align">
                     <input type="checkbox" class="custom-control-input" id="active-${data.id}" ${ data.active == 1 ? "checked" : "" } >
                     <label onclick="toggleProduct('active',${data.id},${other.row})" class="custom-control-label" for="active-${data.id}">&nbsp;</label>
                  </div>
                  @endif
                  `;
               }
            },
            {
               data: 'actions',
               sortable: false,
               render: function(column, row, data) {
                  return `
                  @if( $permission::chekStatus('product_edit','admin'))
                     <a href="{{ url('catalogue/product/${data.id}/edit') }}" class="badge btn-primary">
                        {{ __('backoffice.edit') }}
                     </a>
                     @endif
                  @if( $permission::chekStatus('product_delete','admin'))
                     <button type="button" onclick="openDeleteModal(${data.id})" class="badge btn-primary deleteproduct" data-productid="${data.id}">
                        {{ __('backoffice.delete') }}
                     </button>
                     @endif
                  `;
               }
            },
         ],
         "order": [[ 0, "desc" ]],
      });

      productTable.ajax.reload();
      $('#product-table').removeClass('dataTable');

      // Add event listener for opening and closing details
      $('#product-table tbody').on('click', '.details-control', function() {
         var tr = $(this).closest('tr');
         var row = productTable.row(tr);

         if (row.child.isShown()) {
            // This row is already open - close it
            row.child.hide();
            tr.removeClass('shown');
            tr.removeClass('expended-row');
            $(this).removeClass('fa-chevron-down').addClass('fa-chevron-right');
         } else { //
            // Open this row
            row.child(format(row.data())).show();
            $(this).removeClass('fa-chevron-right').addClass('fa-chevron-down');
            tr.addClass('expended-row');
            tr.addClass('shown');
            $(tr).next('tr').children('td').eq(0).css({
               'padding': '0px'
            });
         }
      });

      $('#category_filter').select2();
      $('#brand_filter').select2();
      $('#supplier_filter').select2();

      /*
      // Handle click on "Expand All" button
      $('#btn-show-all-children').on('click', function() {
         // Enumerate all rows
         productTable.rows().every(function() {
            // If row has details collapsed
            if (!this.child.isShown()) {
               // Open this row
               this.child(format(this.data())).show();
               $(this.node()).addClass('shown');
            }
         });
      });

      // Handle click on "Collapse All" button
      $('#btn-hide-all-children').on('click', function() {
         // Enumerate all rows
         productTable.rows().every(function() {
            // If row has details expanded
            if (this.child.isShown()) {
               // Collapse row details
               this.child.hide();
               $(this.node()).removeClass('shown');
            }
         });
      });
      */
   });

   function openDeleteModal(productId) {
      $('#product_id').val(productId);
      $('#product_delete_form').attr('action', "{{ route('api.delete.product') }}");
      $('#product_delete_modal').modal('show');
   }

   $(function() {
      $('#product_delete_form').submit(function(e) {
         e.preventDefault();
         $.ajax({
            url: $(this).attr('action'),
            type: $(this).attr('method'),
            data: $(this).serialize(),
            success: function(response) {
               if (response.IsValid) {
                  toastr.success(response.Message, 'Success');
                  $('#product_delete_modal').modal('hide');
                  productTable.ajax.reload();
               } else {
                  $('#product_delete_modal').modal('hide');
                  toastr.error(response.Errors[0], 'Error');
               }
            }
         })
         return false;
      });
   });

   $(function() {
      $('#product-all').change(function() {
         if ($(this).is(':checked')) {
            $('.product-row').prop('checked', true).trigger('change');
         } else {
            $('.product-row').prop('checked', false).trigger('change');
         }
      });
   });

   $(function() {
      $('#bulk-apply').click(function() {
         let count = 0;
         let product = [];
         let rows = [];

         $('.product-row').each(function() {
            if ($(this).is(':checked')) {
               product.push(parseInt($(this).data('product-id')));
               rows.push(parseInt($(this).data('row')));
               count++;
            }
         });

         if (count == 0) {
            toastr.error('No row(s) selected', 'Error');
            return;
         }

         let val = parseInt($('#bulk-action').val());

         if (product.length > 0 && val > 0) {
            let enables = [1, 3, 5, 7];
            let disables = [2, 4, 6, 8];
            let type = 'delete';
            let active = true;

            if ([1, 2].includes(val)) {
               type = 'dinein';
            } else if ([3, 4].includes(val)) {
               type = 'is_featured';
            } else if ([5, 6].includes(val)) {
               type = 'website';
            } else if ([7, 8].includes(val)) {
               type = 'active';
            } else {
               type = 'delete';
            }

            if (enables.includes(val)) {
               active = true;
            } else if (disables.includes(val)) {
               active = false;
            }

            toggleAllProduct(type, product, rows, active);
         }
      });

   });

   function toggleAllProduct(type, ids, rows, active) {
      $.ajax({
         url: "{{ route('api.toggle.product.all') }}",
         data: {
            product: ids,
            type: type,
            action: active
         },
         type: 'POST',
         success: function(response) {
            if (response.IsValid) {
               productTable.ajax.reload();
               toastr.success(response.Message, 'Success');
               $('#product-all').prop('checked', false).trigger('change');
            }
         }
      });
   }

   function toggleProduct(type, id, row) {
      $.ajax({
         url: "{{ route('api.toggle.product') }}",
         data: {
            id: id,
            type: type
         },
         type: 'POST',
         success: function(response) {
            if (response.IsValid) {
               if (row != null) {
                  productTable.row(row).data(response.Payload).draw();
               }
               toastr.success(response.Message, 'Success');
            }
         }
      });
   }

   function applyFilters() {
      productTable.ajax.reload();
   }

   function formReset(){
      productTable.ajax.reload();
   }
</script>

<script>
   $(document).ready(function(){
     $(".filter-icons a.btn-lg").click(function(){
       $(".date-filter").toggleClass('date');
     });
   });
</script>

@endsection
