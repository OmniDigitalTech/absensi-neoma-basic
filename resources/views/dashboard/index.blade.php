@php
function formatEventDate($date) {
return \Carbon\Carbon::createFromFormat('Y-m-d', $date)->addDay()->format('Y-m-d');
}
@endphp
@extends('templates.dashboard')
@section('isi')
<div class="row">
    <div class="col-xl-12">
        <div class="items-slider">
            @foreach([
            ['icon' => 'users', 'label' => 'Total Pegawai', 'value' => $jumlah_user],
            ['icon' => 'database', 'label' => 'Masuk', 'value' => $jumlah_masuk + $jumlah_izin_telat +
            $jumlah_izin_pulang_cepat],
            ['icon' => 'user', 'label' => 'Alfa', 'value' => $jumlah_user - ($jumlah_masuk + $jumlah_izin_telat +
            $jumlah_izin_pulang_cepat + $jumlah_libur + $jumlah_cuti + $jumlah_izin_masuk)],
            ['icon' => 'clipboard', 'label' => 'Libur', 'value' => $jumlah_libur],
            ['icon' => 'file-text', 'label' => 'Lembur', 'value' => $jumlah_karyawan_lembur],
            ['icon' => 'credit-card', 'label' => 'Cuti', 'value' => $jumlah_cuti],
            ['icon' => 'umbrella', 'label' => 'Izin', 'value' => $jumlah_cuti],
            ['icon' => 'droplet', 'label' => 'Izin Telat', 'value' => $jumlah_izin_telat],
            ['icon' => 'navigation', 'label' => 'Izin Pulang Cepat', 'value' => $jumlah_izin_pulang_cepat]
            ] as $item)
            <div class="col-xl-2 col-lg-4 col-sm-4 des-xsm-50 box-col-33">
                <div class="card investment-sec">
                    <div class="animated-bg"><i></i><i></i><i></i></div>
                    <div class="card-body">
                        <div class="icon"><i data-feather="{{ $item['icon'] }}"></i></div>
                        <p>{{ $item['label'] }}</p>
                        <h3>{{ $item['value'] }}</h3>
                    </div>
                </div>
            </div>
            @endforeach
        </div>
    </div>
    <div class="col-xl-12 container-fluid calendar-basic">
        <div class="card">
            <div class="card-body">
                <div class="row" id="wrap">
                    <div class="col-xxl-12 col-xl-12 box-col-70">
                        <div id="external-events mb-4">
                            <div id="external-events-list"></div>
                        </div>
                        <div class="calendar-default" id="calendar-container">
                            <div id="calendar"></div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>
@push('script')
<script>
    document.addEventListener("DOMContentLoaded", function() {
        let date = new Date();
        let y = date.getFullYear();

        let containerEl = document.getElementById("external-events-list");
        new FullCalendar.Draggable(containerEl, {
            itemSelector: ".fc-event",
            eventData: function(eventEl) {
                return {
                    title: eventEl.innerText.trim(),
                };
            },
        });

        let calendarEl = document.getElementById("calendar");
        let calendar = new FullCalendar.Calendar(calendarEl, {
            headerToolbar: {
                left: "prev,next today",
                center: "title",
                right: "dayGridMonth,timeGridWeek,timeGridDay,listWeek",
            },
            initialView: "dayGridMonth",
            navLinks: true, // can click day/week names to navigate views
            editable: true,
            selectable: true,
            nowIndicator: true,
            // dayMaxEvents: true, // allow "more" link when too many events
            events: [
                @foreach($data_user as $du)
                    @php
                        $pecah = explode("-", $du->tgl_lahir)
                    @endphp
                    {
                        title: 'Ulang Tahun: {{ $du->name }}',
                        start: new Date(y, {{ $pecah[1]-1 }}, {{ $pecah[2] }}),
                        allDay: true
                    },
                @endforeach
                @foreach($data_cuti as $dc)
                    @php
                        $pecah2 = explode("-", $dc->tanggal_mulai)
                    @endphp
                    {
                        title: 'Izin: {{ $dc->User->name }}',
                        start: new Date({{ $pecah2[0] }}, {{ $pecah2[1]-1 }}, {{ $pecah2[2] }}),
                        allDay: true
                    },
                @endforeach
            ],
            droppable: true,
            drop: function(arg) {
                if (document.getElementById("drop-remove").checked) {
                    arg.draggedEl.parentNode.removeChild(arg.draggedEl);
                }
            },
        });
        calendar.render();
    });
</script>
@endpush
@endsection
