// show modals 
const showForm = function () {
    var btn = $(this);
    $.ajax({
        url: btn.attr('data-url'),
        type: 'get',
        dataType: 'json',
        beforeSend: function () {
            $('#modals').modal('show');
            $('#modals .modal-dialog').addClass('modal-' + btn.attr('data-size'));
            $('#modals .modal-content').html(`
            <center>
            <div class="fa-3x">
                <i class="fas fa-spinner fa-pulse"></i>
            </div>
            </center>
        `);
        },
        success: function (data) {
            $('#modals .modal-content').html(data);
        },
        error: function (jqXHR, exception) {
            var msg = '';
            if (jqXHR.status === 0) {
                msg = 'Not connect.\n Verify Network.';
            } else if (jqXHR.status == 404) {
                msg = 'Requested page not found. [404]';
            } else if (jqXHR.status == 500) {
                msg = 'Internal Server Error [500].';
            } else if (exception === 'parsererror') {
                msg = 'Requested JSON parse failed.';
            } else if (exception === 'timeout') {
                msg = 'Time out error.';
            } else if (exception === 'abort') {
                msg = 'Ajax request aborted.';
            } else {
                msg = 'Uncaught Error.\n' + jqXHR.responseText;
            }

            $('#modals .modal-content').html(`
            <div class="modal-header">
                <h4 class="modal-title" id="exampleModalLabel"> Terjadi Kesalahan</h4>
                <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
            </div>
            <center>
            <div class="alert alert-danger alert-dismissible fade show" role="alert">
            `+ msg + `
            <button type="button" class="btn-close p-0" data-bs-dismiss="alert" aria-label="Close"></button>
            </div>
               
            </center>
        `);
        }
    });
}

const showErrorsDetailModal = function (msg) {
    errors = `<div class="alert d-flex alert-danger alert-dismissible mb-2" role="alert">
                <ul>
                    <li class="text-bold">`+ msg + `</li>
                </ul>
                 <button type="button" class="btn-close p-0" data-bs-dismiss="alert" aria-label="Close"></button>   
            </div>`;
    $('#modals .errors').html(errors);
}

// loading button submit modal
const isLoadingSubmitButton = function (status) {
    if (status) {
        $('#modals .modal-footer .loading').show();
        $('#modals .modal-footer .button').hide();
    } else {
        $('#modals .modal-footer .loading').hide();
        $('#modals .modal-footer .button').show();
    }
}

const successAllInput = function () {
    var allInput = $(".forms :input");
    allInput.removeClass("is-invalid");
    allInput.addClass("is-valid");
    allInput.nextAll('small:first').remove();
}

// save form 
const saveForm = function () {

    var form = $(this);
    $.ajax({
        url: form.attr('data-url'),
        data: form.serialize(),
        type: form.attr('method'),
        dataType: 'json',
        beforeSend: function () {
            isLoadingSubmitButton(true);
        },
        success: function (response) {
            if (form.attr('data-type') == "reload") {
                location.reload();
            } else {
                $('#myTable').DataTable().ajax.reload();
            }

            $('#modals').modal('hide');
            Swal({
                title: 'Berhasil',
                text: response['msg'],
                type: 'success',
                showConfirmButton: false,
                timer: 2000,
                allowOutsideClick: false,
            });

        }, error: function (jqXHR, exception) {
            successAllInput()
            var msg = '';
            if (jqXHR.status === 0) {
                msg = 'Not connect.\n Verify Network.';
                showErrorsDetailModal(msg);
            } else if (jqXHR.status == 404) {
                msg = 'Requested page not found. [404]';
                showErrorsDetailModal(msg);
            } else if (jqXHR.status == 500) {
                msg = jqXHR.responseJSON.errors[0];
                showErrorsDetailModal(msg);
            } else if (exception === 'parsererror') {
                msg = 'Requested JSON parse failed.';
                showErrorsDetailModal(msg);
            } else if (exception === 'timeout') {
                msg = 'Time out error.';
                showErrorsDetailModal(msg);
            } else if (exception === 'abort') {
                msg = 'Ajax request aborted.';
                showErrorsDetailModal(msg);
            } else if (jqXHR.status == 422) {

                isLoadingSubmitButton(false)
                successAllInput()

                $.each(jqXHR.responseJSON.errors, function (i, error) {
                    var el = $(document).find('[name="' + i + '"]');
                    el.removeClass("is-valid");
                    el.nextAll('small:first').remove();
                    el.addClass("is-invalid");
                    el.after('<small style="color: red;">' + error[0] + '</small>');
                });
            } else {
                msg = 'Uncaught Error.\n' + jqXHR.responseText;
                showErrorsDetailModal(msg);
            }

            isLoadingSubmitButton(false);


        }
    });

    return false;
}
// save with image
// save with image
const saveWithImage = function () {

    var form = $(this);
    var img = document.getElementById('upload_preview');
    var image = img.getAttribute('src');
    var add_request = "&" + form.attr('data-reqimage') + "=" + image;

    $.ajax({
        url: form.attr('data-url'),
        data: form.serialize() + add_request,
        type: form.attr('method'),
        dataType: 'json',
        beforeSend: function () {
            isLoadingSubmitButton(true);
        },
        success: function (response) {
            if (form.attr('data-type') == "reload") {
                location.reload();
            } else {
                $('#myTable').DataTable().ajax.reload();
            }

            $('#modals').modal('hide');
            Swal({
                title: 'Berhasil',
                text: response['msg'],
                type: 'success',
                showConfirmButton: false,
                timer: 2000,
                allowOutsideClick: false,
            });

        }, error: function (jqXHR, exception) {
            successAllInput()
            var msg = '';
            if (jqXHR.status === 0) {
                msg = 'Not connect.\n Verify Network.';
                showErrorsDetailModal(msg);
            } else if (jqXHR.status == 404) {
                msg = 'Requested page not found. [404]';
                showErrorsDetailModal(msg);
            } else if (jqXHR.status == 500) {
                msg = jqXHR.responseJSON.errors[0];
                showErrorsDetailModal(msg);
            } else if (exception === 'parsererror') {
                msg = 'Requested JSON parse failed.';
                showErrorsDetailModal(msg);
            } else if (exception === 'timeout') {
                msg = 'Time out error.';
                showErrorsDetailModal(msg);
            } else if (exception === 'abort') {
                msg = 'Ajax request aborted.';
                showErrorsDetailModal(msg);
            } else if (jqXHR.status == 422) {

                isLoadingSubmitButton(false)
                successAllInput()

                $.each(jqXHR.responseJSON.errors, function (i, error) {
                    var el = $(document).find('[name="' + i + '"]');
                    el.removeClass("is-valid");
                    el.nextAll('small:first').remove();
                    el.addClass("is-invalid");
                    el.after('<small style="color: red;">' + error[0] + '</small>');
                });
            } else {
                msg = 'Uncaught Error.\n' + jqXHR.responseText;
                showErrorsDetailModal(msg);
            }

            isLoadingSubmitButton(false);


        }
    });

    return false;
}

// delete data
const deleteData = function (url, data, type) {

    $.ajax({
        url: url,
        data: data,
        type: "POST",
        dataType: 'json',
        success: function (response) {

            if (type == "reload") {
                location.reload();
            } else {
                $('#myTable').DataTable().ajax.reload();
            }

            Swal({
                title: 'Berhasil',
                text: response['msg'],
                type: 'success',
                showConfirmButton: false,
                timer: 2000,
                allowOutsideClick: false,
            })
        }, error: function (err, exception) {
            Swal({
                title: 'Perhatian',
                text: err.responseJSON.errors,
                type: 'error'
            })
        }
    });

    return false;
}
// show delete form
const deleteForm = function () {

    var btn = $(this);
    var url = btn.attr('data-url');
    var text = btn.attr('data-text');
    var type = btn.attr('data-type');
    var title = btn.attr("data-title");
    var data = {
        _method: 'DELETE',
        _token: $('meta[name="token"]').attr('content')
    };
    var textTitle = "";
    if(title == "data"){
        textTitle = "Menghapus Data dengan <br /> ";
    }

    Swal({
        title: 'Apakah Anda Yakin?',
        html: textTitle + text,
        type: 'question',
        showCancelButton: true,
        confirmButtonColor: '#d33',
        cancelButtonColor: '#3085d6',
        confirmButtonText: '<i class="fa fa-trash"></i> YA',
        cancelButtonText: '<i class="fa fa-reply-all"></i> Batal',
        showCancelButton: true,
        showLoaderOnConfirm: true,
        allowOutsideClick: () => !Swal.isLoading()
    }).then((result) => {
        if (result.value) {
            deleteData(url, data, type);
        }

    })

}


// convert to base64 =======================================
window.previewImage = function (idInputFile, idPriviewFile) {
    var oFReader = new FileReader();
    oFReader.readAsDataURL(document.getElementById(idInputFile).files[0]);

    oFReader.onload = function (oFREvent) {
        document.getElementById(idPriviewFile).src = oFREvent.target.result;
    };
};

window.getSizeBase64 = function (stringImage) {

    sub2 = stringImage.substring(stringImage.length - 2);
    if (sub2 = '==') {
        var y = 2;
    } else {
        var y = 1;
    }
    x = (stringImage.length * (3 / 4)) - y;
    kb = x / 1024;
    mb = kb / 1024;

    return mb;


}
// =========================================================

// Autonumeric  =====================================================
window.autoNumericGlobal = function (var1, var2) {

    $('.' + var1).bind('blur focusout keypress keyup', function () {
        if ($(this).autoNumeric('get') == '') {
            $('.' + var2).val('0');
        } else {
            $('.' + var2).val($(this).autoNumeric('get'));
        }
    });

    return $('.' + var1).autoNumeric('init', { mDec: '0' });
}
// Document Ready ============================================================
window.menuOpen = function (type, idMenu, idSubmenu = '') {
    if (type == 'menu') {

        $("#" + idMenu).addClass("active");

    } else if (type == 'submenu') {

        $("#" + idMenu).addClass("active");
        $("#" + idMenu).addClass("menu-open");
        $("#" + idSubmenu).addClass("active");

    }
}

window.numberWithCommas = function (num) {
    return num.toString().replace(/\B(?=(\d{3})+(?!\d))/g, ',');
}

const validationAlert = function (text) {
    swal({
        title: 'Perhatian',
        text: text,
        type: 'error'
    });
}

$(document).ready(function () {



    $('body').tooltip({
        selector: '[data-toggle=tooltip]'
    });



});


// ========================================================