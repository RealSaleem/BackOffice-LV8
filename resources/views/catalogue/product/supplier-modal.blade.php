<div class="modal fade" id="supplierModal" tabindex="-1" role="dialog" aria-labelledby="exampleModalLabel" aria-hidden="true">
    <div class="modal-dialog" role="document">
        <form method="POST" action="{{ route('api.add.supplier') }}" id="supplier-form">
            {{csrf_field()}}
            <div class="modal-content">
                <div class="modal-header">
                    <h4 class="modal-title">{{ __('backoffice.add') }}</h4>
                    <button type="button" class="close pull-right" data-dismiss="modal">&times;</button>
                </div>
                <div class="modal-body">
                    <div class="card-body">
                        <div class="row">
                            <div class="col-md-6">
                                <div class="form-group">
                                    <label>{{ __('backoffice.name')  }}<em style="color: red">*</em></label>
                                    <input type="text" name="name" class="form-control" form="supplier-form">
                                    <input type="hidden" name="address" class="form-control" form="supplier-form" value="No address provided yet">
                                    <input type="hidden" name="active" form="supplier-form" value="1" >
                                </div>
                            </div>
                            <div class="col-md-6">
                                <div class="form-group">
                                    <label>{{ __('backoffice.email')  }}<em style="color: red">*</em></label>
                                    <input type="text" name="email" class="form-control" form="supplier-form">
                                </div>
                            </div>
                        </div>
                        <div class="row">
                            <div class="col-md-6">
                                <div class="form-group">
                                    <label>{{ __('backoffice.phone')  }}<em style="color: red">*</em></label>
                                    <input type="text" name="phone" class="form-control" form="supplier-form">
                                </div>
                            </div>
                            <div class="col-md-6">
                                <div class="form-group">
                                    <label>{{ __('backoffice.mobile')  }}<em style="color: red">*</em></label>
                                    <input type="text" name="mobile" class="form-control" form="supplier-form">
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
                <div class="modal-footer">
                    <button type="submit" class="btn-primary btn-sm pull-left">{{ __('backoffice.save') }}</button>
                </div>
            </div>
        </form>
    </div>
</div>
