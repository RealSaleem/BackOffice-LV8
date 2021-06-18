<div id="optionModal" class="modal fade" role="dialog">
    <form method="POST" action="{{ route('api.add.addon') }}" id="option_form">
        {{ csrf_field() }}
        <div class="modal-dialog">
            <div class="modal-content">
                <div class="modal-header">
                    <h4 class="modal-title">{{ __('backoffice.add_option') }}</h4>
                    <button type="button" class="close" data-dismiss="modal">&times;</button>
                </div>
                <div class="modal-body">

                    @include('catalogue.product.option-section', [ 'addon' => $model->addon, 'type' => 'option' ])

                </div>
                <div class="modal-footer">
                    <button type="submit"  class="btn-primary btn-sm">{{ __('backoffice.save') }}</button>
                </div>
            </div>
        </div>
    </form>
</div>
