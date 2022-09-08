@extends('layouts.app')

@section('content')

    <div class="form-modal-ex position-relative">
        <!-- Button trigger modal -->
        <a href="javascript:void(0);" data-url="{{ route('user.store') }}"
           class="btn btn-outline-primary add-user-btn js_add_btn">
            <i class="fas fa-user-plus"></i></a>
        <h3 class="text-center text-primary position-absolute" style="z-index: 1; left: 45%; top: 12px;">Hodimlar</h3>
        <!-- Modal -->
    </div>


    <!-- list section start -->
    <div class="card">
        <table class="table table-striped w-100 table_hover" id="user_datatable">
            <thead class="table-light">
                <tr>
                    <th>№</th>
                    <th>Ism Familiya</th>
                    <th>Lavozim</th>
                    <th>Telefon raqam</th>
                    <th>Holat</th>
                    <th class="text-right">Harakat</th>
                </tr>
            </thead>
            <tbody>

            @foreach($users as $u)

                <tr class="js_this_tr" data-id="{{ $u->id }}">
                    <td>{{ 1 + $loop->index }}</td>
                    <td>{{ $u->full_name }}</td>
                    <td>{{ $u->job_title }}</td>
                    <td>{{ \Helper::phoneFormat($u->phone) }}</td>
                    <td>@if($u->status)
                            faol
                        @else
                            no faol
                        @endif</td>
                    <td class="text-right">
                        <div class="d-flex justify-content-around">
                            <a href="javascript:void(0);" class="text-primary js_edit_btn"
                               data-one_user_url="{{ route('user.oneUser', [$u->id]) }}"
                               data-update_url="{{ route('user.update', [$u->id]) }}"
                               title="Edit">
                                <i class="fas fa-pen mr-50"></i>
                            </a>
                            <a class="text-danger js_delete_btn" href="javascript:void(0);"
                               data-toggle="modal"
                               data-target="#deleteModal"
                               data-name="{{ $u->full_name }}"
                               data-url="{{ route('user.destroy', [$u->id]) }}" title="Delete">
                                <i class="far fa-trash-alt mr-50"></i>
                            </a>
                        </div>
                    </td>
                </tr>

            @endforeach

            </tbody>
        </table>
    </div>
    <!-- list section end -->


    @include('users.add_edit_modal')

@endsection


@section('script')

    <script>

        function user_form_clear(form) {
            form.find("input[type='text']").val('')
            form.remove('input[name="_method"]');
        }

        $(document).ready(function () {
            var modal = $('#add_edit_modal')

            $('#user_datatable').DataTable({
                scrollY: '70vh',
                scrollCollapse: true,
                paging: true,
                pageLength: 50,
                lengthChange: false,
                searching: true,
                ordering: true,
                info: true,
                autoWidth: true,
                language: {
                    search: "",
                    searchPlaceholder: " Search...",
                },
                // "columnDefs": [
                //     {"visible": false, "targets": 1}
                // ],
                // "order": [[1, 'asc']],
                // "drawCallback": function (settings) {
                //     let api = this.api();
                //     let rows = api.rows({page: 'current'}).nodes();
                //     let last = null;
                //     api.column(1, {page: 'current'}).data().each(function (group, i) {
                //         if (last !== group) {
                //             if (group) {
                //                 $(rows).eq(i).before(
                //                     '<tr class="js_this_group" style="background: white;">' +
                //                     '<td colspan="9" class="text-center"><b>' + group + '</b></td>' +
                //                     '</tr>'
                //                 );
                //             }
                //             last = group;
                //         }
                //     });
                // }
            });

            $(document).on('click', '.js_add_btn', function () {
                let url = $(this).data('url')
                let form = modal.find('.js_add_edit_form')

                form.attr('action', url)
                user_form_clear(form)
                modal.find('.modal-title').html('Hodim qo\'shish')
                modal.modal('show')
            })


            $(document).on('click', '.js_edit_btn', function () {

                let one_url = $(this).data('one_user_url')
                let update_url = $(this).data('update_url')
                let form = modal.find('.js_add_edit_form')
                user_form_clear(form)

                form.attr('action', update_url)
                form.append('<input type="hidden" name="_method" value="PUT">')
                $.ajax({
                    type: 'GET',
                    url: one_url,
                    dataType: 'JSON',
                    success: (response) => {
                        // console.log(response)
                        if (response.status) {
                            form.find('.js_full_name').val(response.user.full_name)
                            form.find('.js_job_title').val(response.user.job_title)
                            form.find('.js_phone').val(response.user.phone)

                            let status = form.find('.js_status option')
                            $.each(status, function (i, item) {
                                if ($(item).val() == response.user.status)
                                    $(item).attr('selected', true)
                            })
                        }
                        modal.find('.modal-title').html('Hodim tahrirlash')
                        modal.modal('show')
                    },
                    error: (response) => {
                        console.log('error: ', response)
                    }
                })
            })




            /** User submit store or update **/
            $('.js_add_edit_form').on('submit', function (e) {
                e.preventDefault()
                let form = $(this)
                let action = form.attr('action')

                $.ajax({
                    url: action,
                    type: "POST",
                    dataType: "json",
                    data: form.serialize(),
                    success: (response) => {

                        console.log('res: ', response)
                        if (response.status)
                            location.reload()

                        if (typeof response.errors !== 'undefined') {
                            if (response.errors.full_name)
                                form.find('.js_full_name').addClass('is-invalid')

                            if (response.errors.phone)
                                form.find('.js_phone').addClass('is-invalid')

                            if (response.errors.job_title)
                                form.find('.js_job_title').addClass('is-invalid')
                        }

                    },
                    error: (response) => {
                        console.log('error: ', response)
                    }
                })
            });
        });
    </script>
@endsection
