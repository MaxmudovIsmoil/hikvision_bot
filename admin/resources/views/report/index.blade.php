@extends('layouts.app')

@section('content')

    <div class="form-modal-ex position-relative">
        <!-- Button trigger modal -->
        <form action="{{ route('report.get_report') }}" class="report-form js_form_report">
            @csrf
{{--            <select name="user_id" class="form-control js_user_id mr-3 col-md-8">--}}
{{--                <option value="all">Barcha hodimlar</option>--}}
{{--                @foreach($users as $user)--}}
{{--                    <option value="{{ $user->id }}">{{ $user->full_name }}</option>--}}
{{--                @endforeach--}}
{{--            </select>--}}
            <select name="interval" class="form-control js_interval">
                <option value="1">Kun (Kechagi)</option>
                <option value="2">Hafta (o'tgan)</option>
                <option value="3" selected>Oy ({{ date('M') }})</option>
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
                    <th>Hodim</th>
                    <th class="text-center">Natija</th>
{{--                    <th class="text-right">Harakat</th>--}}
                </tr>
            </thead>
            <tbody>
            @php $i = 1; $done = 0; $failed = 0; $no_click = 0; @endphp
            @foreach($users as $user)
                @if ($user->user_task_done->count())
                    <tr class="js_this_tr" data-id="{{ $user->id }}">
                        <td>{{ $i++ }}</td>
                        <td>{{ $user->full_name }}</td>
                        <td class="text-center">
                            @foreach($user->user_task_done as $task_done)
                                @if(date('m', strtotime($task_done->created_at)) == date('m'))
                                    @php
                                        if ($task_done->status == 1)
                                            $done++;
                                        if ($task_done->status == -1)
                                            $failed++;
                                        if ($task_done->status == 0)
                                            $no_click++;
                                    @endphp
                                @endif
                            @endforeach
                            <span class="mr-1">
                                <span class="badge badge-success badge-pill">
                                    {{ $done }} <i class="fas fa-check mr-1"></i>
                                    <i class="fas fa-right-long mr-1"></i>
                                    {{ number_format($done * 100 / ($done + $failed + $no_click), 1, ".", " ") }} <i class="fa-solid fa-percent mr-1"></i>
                                </span>
                                Bajarilgan,
                            </span>
                            <span class="mr-1">
                                <span class="badge badge-danger badge-pill">
                                    {{ $failed }} <i class="fas fa-times mr-1"></i>
                                    <i class="fas fa-right-long mr-1"></i>
                                    {{ number_format($failed * 100 / ($done + $failed + $no_click), 1, ".", " ") }} <i class="fa-solid fa-percent mr-1"></i>
                                </span>
                                Bajarilmagan,
                            </span>
                            <span class="mr-1">
                                <span class="badge badge-pill badge-warning">
                                    {{ $no_click }} <i class="fa-solid fa-question mr-1"></i>
                                    <i class="fas fa-right-long mr-1"></i>
                                    {{ number_format($no_click * 100 / ($done + $failed + $no_click), 1, ".", " ") }} <i class="fa-solid fa-percent mr-1"></i>
                                </span>
                                E'tiborsiz qoldirilgan
                            </span>
                        </td>
{{--                        <td class="text-right">--}}
{{--                            <div class="d-flex justify-content-around">--}}
{{--                                <a href="javascript:void(0);" class="text-info" title="Ko'rish">--}}
{{--                                    <i class="fas fa-eye mr-50"></i>--}}
{{--                                </a>--}}
{{--                            </div>--}}
{{--                        </td>--}}
                    </tr>
                @endif
            @endforeach

            </tbody>
        </table>
    </div>
    <!-- list section end -->



@endsection


@section('script')

    <script>

        $(document).ready(function () {

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


            $('.js_interval').on('change', function (e) {
                e.preventDefault()
                let form = $(this).closest('.js_form_report')
                let action = form.attr('action')

                $.ajax({
                    url: action,
                    type: "POST",
                    dataType: "json",
                    data: form.serialize(),
                    success: (response) => {
                        console.log('res: ', response)

                        if (response.status) {
                            $('#datatable tbody').html(response.result)
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
