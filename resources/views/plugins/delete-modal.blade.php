<div id="plugins_delete_modal" class="modal modal-danger fade" tabindex="-1" role="dialog" aria-labelledby="custom-width-modalLabel" aria-hidden="true" style="display: none;">
    <div class="modal-dialog">
        <div class="modal-content">
            <form id="plugins_delete_form" method="POST" class="remove-record-model">
                {{ method_field('delete') }} {{ csrf_field() }}

                <div class="modal-header">
                    <button type="button" class="close" data-dismiss="modal" aria-hidden="true">×</button>
                    <h4 class="modal-title text-center" id="custom-width-modalLabel">{{ __('plugins.delete_title') }}</h4>
                </div>
                <div class="modal-body">
                    <h4>{{ __('plugins.delete_message') }}</h4>
                    <input type="hidden" , name="plugins_id" id="plugins_id" />
                </div>
                <div class="modal-footer">
                    <button type="submit" class="btn btn-danger waves-effect remove-data-from-delete-form">{{ __('plugins.delete') }}</button>
                    <button type="button" class="btn btn-default waves-effect" data-dismiss="modal">{{ __('plugins.close') }}</button>
                </div>
            </form>
        </div>
    </div>
</div>
