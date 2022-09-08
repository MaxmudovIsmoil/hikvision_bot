$(document).ready(function() {

    /** btn user and task modal close inputs in clear **/
    $('#add_edit_modal button[data-dismiss="modal"]').click(function () {

        let form = $('.js_add_edit_form')

        let name = form.find('.js_name')
        name.val('')
        name.removeClass('is-invalid')

        let full_name = form.find('.js_full_name')
        full_name.val('')
        full_name.removeClass('is-invalid')

        let job_title = form.find('.js_job_title')
        job_title.val('')
        job_title.removeClass('is-invalid')

        let phone = form.find('.js_phone')
        phone.val('')
        phone.removeClass('is-invalid')

    })


    $('.js_name').on('input', function () {
        $(this).removeClass('is-invalid')
        $(this).siblings('.invalid-feedback').addClass('valid-feedback')
    })

    $('.js_full_name').on('input', function () {
        $(this).removeClass('is-invalid')
        $(this).siblings('.invalid-feedback').addClass('valid-feedback')
    })

    $('.js_phone').on('input', function () {
        $(this).removeClass('is-invalid')
        $(this).siblings('.invalid-feedback').addClass('valid-feedback')
    })

    $('.js_job_title').on('input', function () {
        $(this).removeClass('is-invalid')
        $(this).siblings('.invalid-feedback').addClass('valid-feedback')
    })

    $('.js_username').on('input', function () {
        $(this).removeClass('is-invalid')
        $(this).siblings('.invalid-feedback').addClass('valid-feedback')
    })

    $('.js_password').on('input', function () {
        $(this).removeClass('is-invalid')
        $(this).siblings('.invalid-feedback').addClass('valid-feedback')
    })


    // statistic
    $('.js_start_date').on('input', function () {
        $(this).removeClass('is-invalid')
        $(this).siblings('.invalid-feedback').addClass('valid-feedback')
    })

    $('.js_end_date').on('input', function () {
        $(this).removeClass('is-invalid')
        $(this).siblings('.invalid-feedback').addClass('valid-feedback')
    })
});
