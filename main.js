$(document).on('submit', ' form.form-registration', function (e) {
    e.preventDefault();
    var data = new FormData(this);
    data.append('act', 'registration');
    $.ajax({
        url: 'ajax.php',
        type: "POST",
        data: data,
        processData: false,
        contentType: false,
        dataType: 'json',
        success: function (response) {
            if (response.error)
                $('.alert-danger').html(response.error).show();

            if (response.success) {
                $('.form__container').hide();
                $('.form__message').html(response.success);
            }
        }
    });
});