@extends('layouts.app')

@section('content')
    <link href="https://cdn.jsdelivr.net/npm/sweetalert2@11/dist/sweetalert2.min.css" rel="stylesheet">
    <script src="https://cdn.jsdelivr.net/npm/chart.js"></script>

    <div class="max-w-7xl space-y-10">

        <div class="flex flex-col md:flex-row md:items-center md:justify-between gap-4">
            <div>
                <h1 class="text-3xl font-bold text-gray-800">Dashboard Program Kerja</h1>
                <p class="text-sm text-gray-500">
                    Pantau jadwal program kerja secara visual & terstruktur
                </p>
            </div>

            <div class="flex flex-wrap gap-3 text-sm">
                <div class="flex items-center gap-2">
                    <span class="w-4 h-4 bg-blue-600 rounded-full"></span> Penjadwalan
                </div>
                <div class="flex items-center gap-2">
                    <span class="w-4 h-4 bg-yellow-500 rounded-full"></span> Berjalan
                </div>
                <div class="flex items-center gap-2">
                    <span class="w-4 h-4 bg-green-600 rounded-full"></span> Selesai
                </div>
            </div>
        </div>

        <div class="bg-white p-6 rounded-2xl shadow-xl border border-gray-100 mb-10">
    <div id="calendar" class="min-h-[600px]"></div>
</div>

<div class="bg-white p-6 rounded-2xl shadow-xl border border-gray-200 mt-10">
    <h2 class="text-xl font-bold mb-4 text-gray-800 text-center">
        Grafik Status Program Kerja per Ranting
    </h2>
    <div class="w-full" style="min-height: 400px">
        <canvas id="rantingChart"></canvas>
    </div>
</div>

    </div>

    <script src="https://cdn.jsdelivr.net/npm/fullcalendar@6.1.8/index.global.min.js"></script>
    <script src="https://cdn.jsdelivr.net/npm/sweetalert2@11"></script>

    <script>
        document.addEventListener('DOMContentLoaded', function() {

            const calendarEl = document.getElementById('calendar');

            const calendar = new FullCalendar.Calendar(calendarEl, {
                initialView: 'dayGridMonth',
                locale: 'id',
                height: "auto",
                fixedWeekCount: false,
                showNonCurrentDates: false,
                selectable: false,
                editable: false,
                headerToolbar: {
                    left: 'prev',
                    center: 'title',
                    right: 'next'
                },
                events: @json($events),
                eventDidMount: function(info) {
                    const status = info.event.extendedProps.status;
                    let color = '#2563eb'; // default blue

                    if (status === 'penjadwalan') color = '#2563eb';
                    if (status === 'berjalan') color = '#f59e0b';
                    if (status === 'selesai') color = '#16a34a';

                    info.el.style.backgroundColor = color;
                    info.el.style.borderColor = color;
                    info.el.style.color = 'white';
                    info.el.style.borderRadius = '8px';
                    info.el.style.padding = '4px 6px';
                    info.el.style.fontSize = '0.8rem';
                    info.el.style.cursor = 'pointer';
                },
                eventClick: function(info) {
                    Swal.fire({
                        title: info.event.title,
                        html: `
                    <div class="text-left space-y-1">
                        <p><strong>Estimasi Mulai:</strong> ${info.event.start.toLocaleDateString()}</p>
                        <p><strong>Estimasi Selesai:</strong> ${info.event.end ? info.event.end.toLocaleDateString() : '-'}</p>
                        <p><strong>Status:</strong> ${info.event.extendedProps.status}</p>
                    </div>
                `,
                        icon: 'info',
                        confirmButtonText: 'Tutup'
                    });
                }
            });

            calendar.render();

            const ctx = document.getElementById('rantingChart').getContext('2d');

            new Chart(ctx, {
                type: 'bar',
                data: {
                    labels: @json($labels),
                    datasets: [{
                            label: 'Penjadwalan',
                            data: @json($penjadwalan),
                            backgroundColor: '#2563eb'
                        },
                        {
                            label: 'Berjalan',
                            data: @json($berjalan),
                            backgroundColor: '#f59e0b'
                        },
                        {
                            label: 'Selesai',
                            data: @json($selesai),
                            backgroundColor: '#16a34a'
                        }
                    ]
                },
                options: {
                    responsive: true,
                    maintainAspectRatio: false,
                    scales: {
                        x: {
                            stacked: true
                        },
                        y: {
                            stacked: true,
                            beginAtZero: true
                        }
                    },
                    plugins: {
                        legend: {
                            position: 'top'
                        },
                        tooltip: {
                            mode: 'index',
                            intersect: false
                        }
                    }
                }
            });

        });
    </script>
@endsection
