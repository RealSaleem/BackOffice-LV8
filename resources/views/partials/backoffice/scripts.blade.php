<script src="{{ App\Helpers\CustomUrl::asset('backoffice/lib/jquery/jquery.min.js')  }}"></script>
<script src="{{ App\Helpers\CustomUrl::asset('backoffice/lib/bootstrap/js/bootstrap.bundle.min.js')  }}"></script>
<!-- <script src="{{ App\Helpers\CustomUrl::asset('backoffice/assets/js/main.js')  }}"></script> -->
<script src="{{ App\Helpers\CustomUrl::asset('backoffice/assets/js/ui-nav.js')  }}"></script>
<script src="{{ App\Helpers\CustomUrl::asset('backoffice/assets/js/ui-toggle.js')  }}"></script>
<script src="{{ App\Helpers\CustomUrl::asset('backoffice/assets/js/charactercount/jquery.character-counter.js') }}"></script>
<script src="{{ App\Helpers\CustomUrl::asset('backoffice/assets/js/select2.min.js') }}"></script>
<script src="{{ App\Helpers\CustomUrl::asset('backoffice/assets/js/toastr.js') }}"></script>
<script src="{{ App\Helpers\CustomUrl::asset('backoffice/assets/js/intlTelInput.js')}} "></script>

<script type="text/javascript" src="https://cdn.jsdelivr.net/jquery.validation/1.16.0/jquery.validate.min.js"></script>
<script type="text/javascript" src="//cdn.datatables.net/1.10.20/js/jquery.dataTables.min.js"></script>
<script type="text/javascript" src="https://cdn.jsdelivr.net/momentjs/latest/moment.min.js"></script>
<script type="text/javascript" src="https://cdn.jsdelivr.net/npm/daterangepicker/daterangepicker.min.js"></script>

<script src="{{App\Helpers\CustomUrl::asset('backoffice/assets/js/cropper.min.js') }}"></script>
<script src="{{App\Helpers\CustomUrl::asset('backoffice/assets/js/dropzone.js') }}"></script>
<script src="{{App\Helpers\CustomUrl::asset('backoffice/assets/js/my-dropzone.js') }}"></script>

<script type="text/javascript" src="{{CustomUrl::asset('backoffice/assets/js/jquery.amsify.suggestags.js')}}"></script>




<script type="text/javascript">




const $loading = $('#LoaderDiv').hide();

$(document).ajaxStart(function() {
    $loading.show();
}).ajaxStop(function() {
    $loading.hide();
});

toastr.options = {
    "closeButton": true,
    "debug": false,
    "positionClass": "toast-bottom-left",
    "onclick": null,
    "showDuration": "1000",
    "hideDuration": "1000",
    "timeOut": "4000",
    "extendedTimeOut": "0",
    "showEasing": "swing",
    "hideEasing": "linear",
    "showMethod": "fadeIn",
    "hideMethod": "fadeOut",
    "progressBar": true,
}

</script>
<script>
    $(".menu-toogler").click(function(){
        $("#content").toggleClass("main-side");
    });
</script>



