<div id="customer_delete_modal" class="modal modal-danger fade" tabindex="-1" role="dialog" aria-labelledby="custom-width-modalLabel" aria-hidden="true" style="display: none;">
    <div class="modal-dialog">
        <div class="modal-content">
            <form id="customer_delete_form" method="POST" class="remove-record-model">
              {{ method_field('delete') }}
              {{ csrf_field() }}

               <div class="modal-header">
                   <button type="button" class="close" data-dismiss="modal" aria-hidden="true">×</button>
                   <h4 class="modal-title text-center" id="custom-width-modalLabel">{{ __('customer.delete_title') }}</h4>
               </div>
               <div class="modal-body">
                   <h4>{{ __('customer.delete_message') }}</h4>
                   <input type="hidden", name="customer_id" id="customer_id">
               </div>
               <div class="modal-footer">
                   <button type="submit" class="btn btn-danger waves-effect remove-data-from-delete-form">{{ __('customer.delete') }}</button>
                   <button type="button" class="btn btn-default waves-effect" data-dismiss="modal">{{ __('customer.close') }}</button>
               </div>

             </form>
        </div>
    </div>                        
</div>