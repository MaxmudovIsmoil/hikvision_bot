@extends('layouts.app')

@section('content')

    <div class="form-modal-ex position-relative">
        <!-- Button trigger modal -->
        <form action="" class="report-form">
            <select name="user_id" class="form-control js_user_id mr-3 col-md-8">
                <option value="all">Barcha hodimlar</option>
                @foreach($employees as $employee)
                    <option value="{{ $employee->id }}">{{ $employee->full_name }}</option>
                @endforeach
            </select>
            <select name="interval" class="form-control col-md-4 js_interval">
                <option value="1">Kun</option>
                <option value="2">Hafta</option>
                <option value="3">Oy</option>
                <option value="4">Yil</option>
            </select>
        </form>

    </div>

    <!-- list section start -->
    <div class="card">
        <table class="table table-striped w-100 table_hover" id="datatable">
            <thead class="table-light">
                <tr>
                    <th>№</th>
                    <th>City</th>
                    <th>Name</th>
                    <th class="text-right">Actions</th>
                </tr>
            </thead>
            <tbody>

{{--            @foreach($outlet as $o)--}}
{{--                <tr class="js_this_tr" data-id="{{ $o->id }}">--}}
{{--                    <td>{{ 1 + $loop->index }}</td>--}}
{{--                    <td>{{ optional($o->city)->name }}</td>--}}
{{--                    <td>{{ $o->name }}</td>--}}
{{--                    <td class="text-right">--}}
{{--                        <div class="d-flex justify-content-around">--}}
{{--                            <a href="javascript:void(0);" class="text-primary js_edit_btn"--}}
{{--                               data-one_outlet_url="{{ route('report.oneOutlet', [$o->id]) }}"--}}
{{--                               data-update_url="{{ route('report.update', [$o->id]) }}"--}}
{{--                               title="Edit">--}}
{{--                                <i class="fas fa-pen mr-50"></i>--}}
{{--                            </a>--}}
{{--                            <a class="text-danger js_delete_btn" href="javascript:void(0);"--}}
{{--                               data-toggle="modal"--}}
{{--                               data-target="#deleteModal"--}}
{{--                               data-name="{{ $o->name }}"--}}
{{--                               data-url="{{ route('report.destroy', [$o->id]) }}" title="Delete">--}}
{{--                                <i class="far fa-trash-alt mr-50"></i>--}}
{{--                            </a>--}}
{{--                        </div>--}}
{{--                    </td>--}}
{{--                </tr>--}}
{{--            @endforeach--}}

            </tbody>
        </table>
    </div>
    <!-- list section end -->



@endsection


@section('script')

    <script>

        function user_form_clear(form) {
            form.find(".js_name").val('')
            form.find('.js_city option:selected').attr('selected', false)
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
                modal.find('.modal-title').html('Add Userdone')
                modal.modal('show')
            })


            $(document).on('click', '.js_edit_btn', function () {

                let one_url = $(this).data('one_outlet_url')
                let update_url = $(this).data('update_url')
                user_form_clear(form)

                form.attr('action', update_url)
                form.append('<input type="hidden" name="_method" value="PUT">')
                $.ajax({
                    type: 'GET',
                    url: one_url,
                    dataType: 'JSON',
                    success: (response) => {

                        if (response.status)
                            form.find('.js_name').val(response.outlet.name)

                        let city = form.find('.js_city option')
                        $.each(city, function(i, item) {
                            if ($(item).val() == response.outlet.city_id)
                                $(item).attr('selected', true)
                            else
                                $(item).attr('selected', false)
                        })

                        modal.find('.modal-title').html('Edit Userdone')
                        modal.modal('show')
                    },
                    error: (response) => {
                        console.log('error: ', response)
                    }
                })
            })


            /** Userdone submit store or update **/
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
