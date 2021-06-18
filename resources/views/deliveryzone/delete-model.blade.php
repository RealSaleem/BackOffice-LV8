<div id="delivery_zone_delete_modal" class="modal modal-danger fade" tabindex="-1" role="dialog" aria-labelledby="custom-width-modalLabel" aria-hidden="true" style="display: none;">
    <div class="modal-dialog">
        <div class="modal-content">
            <form id="delivery_zone_delete_form" method="GET" class="remove-record-model">
                <div class="modal-header">
                    <h4 class="modal-title text-center" id="custom-width-modalLabel">{{ __('brand.delete_title') }}</h4>
                    <button type="button" class="close" data-dismiss="modal" aria-hidden="true">×</button>
                </div>
                <div class="modal-body">
                    <p>{{ __('brand.delete_message') }}</p>
                    <input type="hidden" name="outlet_id" id="outlet_id">
                    <input type="hidden" name="zone_id" id="zone_id">
                </div>
                <div class="modal-footer">
                    <button type="button" class="btn btn-sm waves-effect" data-dismiss="modal">{{ __('brand.close') }}</button>
                    <button type="submit" class="btn-primary waves-effect remove-data-from-delete-form">{{ __('brand.delete') }}</button>
                </div>
            </form>
        </div>
    </div>
</div>
