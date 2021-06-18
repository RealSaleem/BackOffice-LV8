<script type="text/javascript">
    $(function () {
        $('#register').submit(function () {

            $.ajax({
                url: $(this).attr('action'),
                type: $(this).attr('method'),
                data: $(this).serialize(),
                success: function (response) {
                    // setTimeout(() => {
                    toastr.success(response.message, 'Success');
                    $('.outlet_register_name').val('');
                    // }, 3000);

                    // $('.outlet_register_name').val('');
                },
                error: function (error) {
                    toastr.success(error, 'error');
                }
            });
            return false;

        });
    });


</script>
