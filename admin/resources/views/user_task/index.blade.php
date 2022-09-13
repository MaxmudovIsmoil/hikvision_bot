@extends('layouts.app')

@section('content')


    <div class="form-modal-ex position-relative">
        <!-- Button trigger modal -->
        <a href="javascript:void(0);" data-url="" class="btn btn-outline-primary add-user-btn js_add_btn">Hodimga vazifa <i class="fas fa-plus"></i></a>
        <h3 class="text-center text-primary position-absolute" style="z-index: 1; left: 40%; top: 12px;"> Hodimlar vazifalari</h3>
        <!-- Modal -->
    </div>

    <!-- list section start -->
    <div class="card">
        <table class="table table-striped w-100 table_hover" id="datatable">
            <thead class="table-light">
                <tr>
                    <th>№</th>
                    <th>Hodim</th>
                    <th class="text-right">Harakat</th>
                </tr>
            </thead>
            <tbody>
            @php $i = 1; @endphp
            @foreach($employees as $e)
                @if ($e->user_task->count() && $e->user_task[0]->month == date('m'))
                    <tr class="js_this_tr" data-id="{{ $e->id }}">
                        <td>{{ $i++ }}</td>
                        <td>{{ $e->full_name }}</td>
                        <td class="text-right">
                            <div class="d-flex justify-content-around">
                                <a href="javascript:void(0);" class="text-primary js_edit_btn"
                                   data-one_task_url="{{ route('user-task.one_user_tasks', [$e->id]) }}"
                                   data-update_url="{{ route('user-task.update', [$e->id]) }}"
                                   title="Edit">
                                    <i class="fas fa-pen mr-50"></i>
                                </a>
                                <a class="text-danger js_delete_btn" href="javascript:void(0);"
                                   data-toggle="modal"
                                   data-target="#deleteModal"
                                   data-name="{{ $e->full_name }}"
                                   data-url="{{ route('user-task.destroy', [$e->id]) }}" title="Delete">
                                    <i class="far fa-trash-alt mr-50"></i>
                                </a>
                            </div>
                        </td>
                    </tr>
                @endif
            @endforeach

            </tbody>
        </table>
    </div>
    <!-- list section end -->


    @include('user_task.add_edit_modal')

@endsection


@section('script')

    <script>

        function user_form_clear(form) {
            let user = form.find('.js_user_id option');
            $.each(user, function(i, one_option){
                $(one_option).removeAttr('selected');
            });
            form.find('.js_user_id').removeAttr('disabled');

            let div = $('.js_div')
            $.each(div, function(i, one_div){
                $(one_div).find('.js_checkbox_input').removeAttr('checked');
                $(one_div).find('.js_time_input').attr('disabled', 'true');
            });

            form.find(".js_day_off1").val('')
            form.find(".js_day_off2").val('')
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


            $(document).on('change', '.js_checkbox_input', function() {
                let parent_div = $(this).closest('.js_div');

                if($(this).is(":checked"))
                    parent_div.find('.js_time_input').removeAttr('disabled')
                else
                    parent_div.find('.js_time_input').attr('disabled', 'true')
            })


            $(document).on('input', '.js_time_input', function() {

                let parent_div = $(this).closest('.js_div');
                let time = $(this).val();
                let checkbox_input = parent_div.find('.js_checkbox_input');
                let array = checkbox_input.val().split(';');

                checkbox_input.val(array[0]+";"+time);
            });



            $(document).on('click', '.js_add_btn', function () {
                let url = $(this).data('url')

                form.attr('action', url)
                user_form_clear(form)
                modal.find('.modal-title').html('Hodimga vazifa +')
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

                            let user = form.find('.js_user_id option');
                            $.each(user, function(i, one_option){
                                if($(one_option).val() == response.user_tasks[0].user_id) {
                                    $(one_option).attr('selected', 'true');
                                }
                            });
                            form.find('.js_user_id').attr('disabled', true);

                            form.find('.js_day_off1').val(response.user_tasks[0].day_off1);
                            form.find('.js_day_off2').val(response.user_tasks[0].day_off2);

                            let div = $('.js_div')

                            $.each(div, function(i, one_div){
                                $.each(response.user_tasks, function(i2, one_user_task){
                                    if($(one_div).data('task_id') == one_user_task.task_id) {

                                        $(one_div).find('.js_checkbox_input').attr('checked', 'true');
                                        $(one_div).find('.js_checkbox_input').val(one_user_task.task_id + ';' + one_user_task.remind_time);

                                        $(one_div).find('.js_time_input').removeAttr('disabled');
                                        $(one_div).find('.js_time_input').val(one_user_task.remind_time);
                                    }
                                });
                            });

                        }
                        modal.find('.modal-title').html('Vazifa taxrirlash');
                        modal.modal('show');
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
                            if (response.errors.user_id)
                                form.find('.js_user_id').addClass('is-invalid')
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
