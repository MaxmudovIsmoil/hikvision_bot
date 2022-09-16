@extends('layouts.app')

@section('content')

    <div class="form-modal-ex position-relative">
        <!-- Button trigger modal -->
        <form action="{{ route('reports') }}" class="report-form js_form_report">
            @csrf
            <input type="text" class="form-control js_start_date js_datepicker mr-3" name="start_date" value="{{ date('d.m.Y', strtotime(isset($start_date) ? $start_date : 'last week Monday')) }}">
            <input type="text" class="form-control js_end_date js_datepicker mr-3" name="end_date" value="{{ date('d.m.Y', strtotime(isset($end_date) ? $end_date : 'this week Monday')) }}">
            <input type="submit" class="btn btn-primary js_btn" name="btn" value="Ko'rish">
        </form>
    </div>

    <!-- list section start -->
    <div class="card">
        <table class="table table-striped w-100 table_hover" id="datatable">
            <thead class="table-light">
                <tr>
                    <th>№</th>
                    <th>Hodim</th>
                    <th>Lavorim</th>
                    <th class="text-center">Natija</th>
                </tr>
            </thead>
            <tbody>
            @php $i = 1; @endphp
            @foreach($result as $res)
                <tr class="js_this_tr" data-id="{{ $res['user_id'] }}">
                    <td>{{ $i++ }}</td>
                    <td>{{ $res['full_name'] }}</td>
                    <td>{{ $res['job_title'] }}</td>
                    <td class="text-center">
                        <span class="mr-1">
                            <span class="badge badge-success badge-pill">
                                {{ $res['success'] }} <i class="fas fa-check mr-1"></i>
                                <i class="fas fa-right-long mr-1"></i>
                                {{ number_format($res['success'] * 100 / ($res['success'] + $res['cancel'] + $res['wait']), 1, ".", " ") }} <i class="fa-solid fa-percent mr-1"></i>
                            </span>
                            Bajarilgan,
                        </span>
                        <span class="mr-1">
                            <span class="badge badge-danger badge-pill">
                                {{ $res['cancel'] }} <i class="fas fa-times mr-1"></i>
                                <i class="fas fa-right-long mr-1"></i>
                                {{ number_format($res['cancel'] * 100 / ($res['success'] + $res['cancel'] + $res['wait']), 1, ".", " ") }} <i class="fa-solid fa-percent mr-1"></i>
                            </span>
                            Bajarilmagan,
                        </span>
                        <span class="mr-1">
                            <span class="badge badge-pill badge-warning">
                                {{ $res['wait'] }} <i class="fa-solid fa-question mr-1"></i>
                                <i class="fas fa-right-long mr-1"></i>
                                {{ number_format($res['wait'] * 100 / ($res['success'] + $res['cancel'] + $res['wait']), 1, ".", " ") }} <i class="fa-solid fa-percent mr-1"></i>
                            </span>
                            E'tiborsiz qoldirilgan
                        </span>
                    </td>
                </tr>
            @endforeach

            </tbody>
        </table>
    </div>
    <!-- list section end -->



@endsection


@section('script')

    <script>

        $(document).ready(function () {

            $( ".js_datepicker" ).datepicker({ dateFormat: 'dd.mm.yy' })

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
                    searchPlaceholder: " Izlash...",
                    language: {
                        lengthMenu: "Har bir sahifada _MENU_ ta yozuvni ko'rsatish",
                        zeroRecords: "Hech narsa topilmadi - kechirasiz",
                        info: "_PAGES_ sahifadan _PAGE_ sahifa koʻrsatilmoqda",
                        infoEmpty: "Hech qanday yozuv mavjud emas",
                        infoFiltered: "(jami _MAX_ ta yozuvdan filtrlangan)",
                        paginate: {
                            'previous': 'Oldingi',
                            'next': 'Keyingi'
                        }
                    }
                }
            });


            $('.js_btn').on('click', function (e) {
                // e.preventDefault()
                // let form = $(this).closest('.js_form_report')
                // let action = form.attr('action')
                //
                // $.ajax({
                //     url: action,
                //     type: "POST",
                //     dataType: "json",
                //     data: form.serialize(),
                //     success: (response) => {
                //         console.log('res: ', response)
                //
                //     },
                //     error: (response) => {
                //         console.log('error: ', response)
                //     }
                // })
            });
        });
    </script>
@endsection
