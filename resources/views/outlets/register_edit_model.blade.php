<div id="register_edit_modal" class="modal modal-danger fade" tabindex="-1" role="dialog" aria-labelledby="custom-width-modalLabel" aria-hidden="true" style="display: none;">
    <div class="modal-dialog">
        <div class="modal-content">
            <form id="brands_delete_form" method="POST" action="{{route('update.register')}}" class="remove-record-model">
                @csrf
                <div class="modal-header">
                    <h4 class="modal-title text-center" id="custom-width-modalLabel">{{ __('outlets.update_register') }}</h4>
                    <button type="button" class="close" data-dismiss="modal" aria-hidden="true">×</button>
                </div>
                <div class="modal-body">
                </div>
                <div class="modal-footer">
                    <button type="button" class="btn btn-sm waves-effect" data-dismiss="modal">{{ __('brand.close') }}</button>
                    <button type="submit" style="padding: 5PX 10px; display: block !important;" onclick="updateRegister()" class="btn-primary waves-effect update_register">{{ __('outlets.update') }}</button>
                </div>
            </form>
        </div>
    </div>
</div>
