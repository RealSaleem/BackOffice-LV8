<div id="role_delete_modal" class="modal modal-danger fade" tabindex="-1" role="dialog" aria-labelledby="custom-width-modalLabel" aria-hidden="true" style="display: none;">
    <div class="modal-dialog">
        <div class="modal-content">
            <form id="role_delete_form" method="GET" class="remove-record-model">
               <div class="modal-header">
                   <h4 class="modal-title text-center" id="custom-width-modalLabel">{{ __('backoffice.role_delete_title') }}</h4>
                   <button type="button" class="close" data-dismiss="modal" aria-hidden="true">×</button>
               </div>
               <div class="modal-body">
                   {{ __('backoffice.role_delete_message') }}
                   <input type="hidden", name="role_id" id="role_id">
               </div>
               <div class="modal-footer">
                   <button type="submit" class="btn btn-primary remove-data-from-delete-form">{{ __('backoffice.delete') }}</button>
                   <button type="button" class="btn btn-sm waves-effect " data-dismiss="modal">{{ __('backoffice.close') }}</button>
               </div>

             </form>
        </div>
    </div>
</div>
