<div id="addonModal" class="modal fade" role="dialog">
    <form method="POST" action="{{ route('api.add.addon') }}" id="add_on_form">
        {{ csrf_field() }}
        <div class="modal-dialog">
            <div class="modal-content">
                <div class="modal-header">
                    <h4 class="modal-title">{{ __('backoffice.add_addon') }}</h4>
                    <button type="button" class="close" data-dismiss="modal">&times;</button>
                </div>
                <div class="modal-body">

                    @include('catalogue.product.addon-section', [ 'addon' => $model->addon, 'type' => 'add_on' ])

                </div>
                <div class="modal-footer">
                    <button type="submit"  class="btn-primary btn-sm">{{ __('backoffice.save') }}</button>
                </div>
            </div>
        </div>
    </form>
</div>
