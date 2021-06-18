<div class="stepwizard">
    <div class="stepwizard-row setup-panel row">
        <div class="stepwizard-step col-md-3">
            <a href="#step-1" type="button" class="d-block text-center" disabled="disabled">
                <span class="badge badge-pill badge-light badge-step">
                    1
                </span>
            </a>
            <p><small>Download Excel</small></p>
        </div>
        <div class="stepwizard-step col-md-3">
            <a href="#step-2" type="button" class="d-block text-center" disabled="disabled">
                <span class="badge badge-pill badge-light badge-step">
                    2
                </span>
            </a>
            <p><small>Upload Excel</small></p>
        </div>
        <div class="stepwizard-step col-md-3">
            <a href="#step-3" type="button" class="d-block text-center" disabled="disabled">
                <span class="badge badge-pill badge-light badge-step">
                    3
                </span>
            </a>
            <p><small>Upload Zip</small></p>
        </div>
        <div class="stepwizard-step col-md-3">
            <a href="#step-4" type="button" class="d-block text-center" disabled="disabled">
                <span class="badge badge-pill badge-light badge-step">
                    4
                </span>
            </a>
            <p><small>Complete</small></p>
        </div>
    </div>
</div>
<div class="row">

    <div class="col-sm-4 ">
        <div class="">
            <div class="card-body">
                <div class="text-center">
                    <h3 class="display-1">
                        <i class="fa fa-file-excel-o" aria-hidden="true"></i>
                    </h3>
                    <h6 class="display-4">
                        <i class="fa fa-arrow-circle-down" aria-hidden="true"></i>
                    </h6>
                </div>
                <ul class="list-group list-group-flush">
                    <li class="list-group-item text-center ">

                        <a required data-toggle="modal" data-target="#download_excel" class="m-b-xs w-auto btn-primary btn-sm">Download Excel</a>
                    </li>
                </ul>
            </div>
        </div>
    </div>

    <div class="col-sm-4 ">
        <div class="">
            <div class="card-body">
                <div class="text-center">
                    <h3 class="display-1">
                        <i class="fa fa-file-excel-o" aria-hidden="true"></i>
                    </h3>
                    <h6 class="display-4">
                        <i class="fa fa-arrow-circle-up" aria-hidden="true"></i>
                    </h6>
                </div>
                <form action="{{ route('import.products') }}" id="upload_excel" method="post" enctype="multipart/form-data">
                    {{csrf_field()}}
                    <ul class="list-group list-group-flush">
                        <li class="list-group-item text-center ">
                            <input type="hidden" form="upload_excel" class="custom-control-input"  name="language" value="{{ strtolower($language['short_name']) }}">

                            <input type="file" style="padding: 21px 17px;" class="broswebutton broswebutton2" name="imported-file" size="chars" required accept=".xlsx">
                        </li>
                        <li class="list-group-item text-center ">
                            <a required data-toggle="modal" data-target="#info_modal" class="m-b-xs w-auto btn-primary btn-sm">Upload Excel</a>
                        </li>
                    </ul>
                </form>
            </div>
        </div>
    </div>

    <div class="col-sm-4 ">
        <div class="">
            <div class="card-body">
                <div class="text-center">
                    <h3 class="display-1">
                        <i class="fa fa-file-archive-o" aria-hidden="true"></i>
                    </h3>
                    <h6 class="display-4">
                        <i class="fa fa-arrow-circle-up" aria-hidden="true"></i>
                    </h6>
                </div>
                <form action="{{ route('product.import.images') }}" method="post" enctype="multipart/form-data">
                    {{csrf_field()}}
                    <ul class="list-group list-group-flush">
                        <li class="list-group-item text-center ">
                        <input type="file" style="padding: 21px 17px;" class="broswebutton broswebutton2" name="imported-file" size="chars" required accept=".zip">
                        </li>
                        <li class="list-group-item text-center ">
                            <button type="submit" class="m-b-xs w-auto btn-primary btn-sm">Upload Zip</button>
                        </li>
                    </ul>
                </form>
            </div>
        </div>
    </div>
</div>
