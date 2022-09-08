@extends('layouts.app')

@section('content')

    <div class="form-modal-ex position-relative">
        <!-- Button trigger modal -->
        <a href="javascript:void(0);" data-url="" class="btn btn-outline-primary add-user-btn js_add_btn">add Task</a>
        <h3 class="text-center text-primary position-absolute" style="z-index: 1; left: 45%; top: 12px;"> Tasks</h3>
        <!-- Modal -->
    </div>

    <!-- list section start -->
    <div class="card">
        <table class="table table-striped w-100 table_hover" id="datatable">
            <thead class="table-light">
            <tr>
                <th>№</th>
                <th>Name</th>
                <th>Time</th>
                <th class="text-right">Actions</th>
            </tr>
            </thead>
            <tbody>

            @foreach($tasks as $t)

                <tr class="js_this_tr" data-id="{{ $t->id }}">
                    <td>{{ 1 + $loop->index }}</td>
                    <td>{{ $t->name }}</td>
                    <td>{{ date('H : i', strtotime($t->remind_time)) }}</td>
                    <td class="text-right">
                        <div class="d-flex justify-content-around">
                            <a href="javascript:void(0);" class="text-primary js_edit_btn"
                               data-one_task_url="{{ route('task.oneTask', [$t->id]) }}"
                               data-update_url="{{ route('task.update', [$t->id]) }}"
                               title="Edit">
                                <i class="fas fa-pen mr-50"></i>
                            </a>
                            <a class="text-danger js_delete_btn" href="javascript:void(0);"
                               data-toggle="modal"
                               data-target="#deleteModal"
                               data-name="{{ $t->name }}"
                               data-url="{{ route('task.destroy', [$t->id]) }}" title="Delete">
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


    @include('task.add_edit_modal')

@endsection


@section('script')

    <script>

        function user_form_clear(form) {
            form.find(".js_name").val('')
            form.remove('input[name="_method"]');
        }

        $(document).ready(function () {
            var modal = $('#add_edit_modal');
            var form = modal.find('.js_add_edit_form');

            $('#datatable').DataTable({
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
                }
            });

            $(document).on('click', '.js_add_btn', function () {
                let url = $(this).data('url')

                form.attr('action', url)
                user_form_clear(form)
                modal.find('.modal-title').html('add Task')
                modal.modal('show')
            })


            $(document).on('click', '.js_edit_btn', function () {

                let one_url = $(this).data('one_task_url')
                let update_url = $(this).data('update_url')
                user_form_clear(form)

                form.attr('action', update_url)
                form.append('<input type="hidden" name="_method" value="PUT">')
                $.ajax({
                    type: 'GET',
                    url: one_url,
                    dataType: 'JSON',
                    success: (response) => {
                        console.log(response)

                        if (response.status) {
                            form.find('.js_name').val(response.task.name)
                            form.find('.js_remind_time').val(response.task.remind_time)
                        }
                        modal.find('.modal-title').html('edit Task')
                        modal.modal('show')
                    },
                    error: (response) => {
                        console.log('error: ', response)
                    }
                })
            })


            /** TaskDone submit store or update **/
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
                            if (response.errors.name)
                                form.find('.js_name').addClass('is-invalid')

                            if (response.errors.remind_time)
                                form.find('.js_remind_time').addClass('is-invalid')
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
