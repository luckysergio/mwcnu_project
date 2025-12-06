@extends('layouts.app')

@section('content')
    <link href="https://cdn.jsdelivr.net/npm/sweetalert2@11.0.15/dist/sweetalert2.min.css" rel="stylesheet">

    <div class="flex flex-col sm:flex-row justify-between items-center mb-6 gap-4">
        <h1 class="text-3xl font-bold text-gray-900">Jadwal Program Kerja</h1>

        <a href="{{ route('jadwal-proker.create') }}"
            class="inline-flex items-center justify-center gap-3 px-6 py-3 bg-gradient-to-r from-green-500 to-green-600 hover:from-green-600 hover:to-green-700 text-white font-semibold text-sm rounded-xl shadow-lg transition-all duration-200">
            <i class="fas fa-plus text-sm"></i>
            <span>Buat Jadwal</span>
            @if ($unassignedCount > 0)
                <span
                    class="ml-2 inline-flex items-center justify-center px-2 py-1 text-xs font-bold leading-none text-red-100 bg-red-600 rounded-full">
                    {{ $unassignedCount }}
                </span>
            @endif
        </a>
    </div>

    {{-- FILTER STATUS --}}
    <form method="GET" class="flex justify-center mb-8">
        <div class="relative w-full max-w-xs">
            <select name="status" onchange="this.form.submit()"
                class="appearance-none w-full px-4 py-3 bg-white border border-gray-300 rounded-full shadow-md 
                focus:outline-none focus:ring-2 focus:ring-green-400 text-gray-700 text-center cursor-pointer">
                <option value="">Semua Jadwal</option>
                @foreach (['penjadwalan', 'berjalan', 'selesai'] as $r)
                    <option value="{{ $r }}" {{ request('status') == $r ? 'selected' : '' }}>
                        {{ ucfirst($r) }}
                    </option>
                @endforeach
            </select>
        </div>
    </form>

    {{-- ALERT SUCCESS --}}
    @if (session('success'))
        <script src="https://cdn.jsdelivr.net/npm/sweetalert2@11.0.15/dist/sweetalert2.all.min.js"></script>
        <script>
            Swal.fire({
                icon: 'success',
                title: 'Berhasil!',
                text: @json(session('success')),
                didClose: () => window.location.href = "{{ route('jadwal-proker.index') }}"
            });
        </script>
    @endif

    {{-- LIST CARD --}}
    <div class="grid grid-cols-1 md:grid-cols-2 xl:grid-cols-3 gap-6">

        @forelse ($jadwals as $item)
            @php
                $estimasiMulai = $item->estimasi_mulai ? \Carbon\Carbon::parse($item->estimasi_mulai) : null;
                $estimasiSelesai = $item->estimasi_selesai ? \Carbon\Carbon::parse($item->estimasi_selesai) : null;

                $durasi = $estimasiMulai && $estimasiSelesai ? $estimasiMulai->diffInDays($estimasiSelesai) + 1 : null;
            @endphp

            <div
                class="bg-white border border-gray-200 rounded-2xl shadow-md hover:shadow-lg p-6 flex flex-col justify-between">

                <div class="space-y-4">

                    {{-- HEADER --}}
                    <div class="flex flex-col items-center text-center space-y-2">
                        <h2 class="text-lg font-bold text-gray-900 leading-snug">
                            {{ $item->proker->judul }}
                        </h2>

                        <span
                            class="inline-block px-4 py-1 text-xs font-semibold text-white rounded-full
        {{ $item->status == 'penjadwalan' ? 'bg-yellow-500' : ($item->status == 'berjalan' ? 'bg-blue-600' : 'bg-green-600') }}">
                            {{ ucfirst($item->status) }}
                        </span>
                    </div>

                    {{-- RANTING PROKER --}}
                    <p class="text-sm text-gray-600 text-center">
                        <strong class="text-gray-800">
                            {{ $item->proker->ranting->kelurahan ?? '-' }}
                        </strong>
                    </p>

                    {{-- ESTIMASI --}}
                    <div class="bg-gray-50 border border-gray-200 rounded-xl p-3">
                        <p class="text-sm text-gray-700">
                            <strong>Estimasi Mulai:</strong>
                            {{ $estimasiMulai ? $estimasiMulai->translatedFormat('d M Y') : '-' }}
                        </p>

                        <p class="text-sm text-gray-700">
                            <strong>Estimasi Selesai:</strong>
                            {{ $estimasiSelesai ? $estimasiSelesai->translatedFormat('d M Y') : '-' }}
                        </p>

                        @if ($durasi)
                            <p class="text-sm text-gray-800 font-semibold mt-1">
                                Durasi: {{ $durasi }} hari
                            </p>
                        @endif
                    </div>

                    {{-- DETAIL KEGIATAN --}}
                    <div class="text-sm text-gray-700 space-y-3">
                        @foreach ($item->details as $detail)
                            <div class="mb-2">
                                <p><strong>Kegiatan:</strong> {{ $detail->kegiatan }}</p>
                                <p><strong>Tanggal:</strong>
                                    {{ \Carbon\Carbon::parse($detail->tanggal_mulai)->translatedFormat('d M Y') }} -
                                    {{ \Carbon\Carbon::parse($detail->tanggal_selesai)->translatedFormat('d M Y') }}
                                </p>
                                <p><strong>Catatan:</strong> {{ $detail->catatan ?? '-' }}</p>
                                <hr class="my-2">
                            </div>
                        @endforeach
                    </div>

                </div>

                {{-- BUTTON --}}
                <div class="mt-5 flex flex-col gap-3">
                    <a href="{{ route('jadwal-proker.edit', $item->id) }}"
                        class="inline-flex items-center justify-center gap-2 px-5 py-2 bg-gradient-to-r from-yellow-500 to-yellow-600 hover:from-yellow-600 hover:to-yellow-700 text-white font-semibold text-sm rounded-lg shadow transition">
                        <i class="fas fa-edit"></i> Edit
                    </a>

                    <button type="button"
                        onclick="confirmDeleteJadwal({{ $item->id }}, '{{ $item->proker->judul }}')"
                        class="w-full inline-flex items-center justify-center gap-2 px-5 py-2 bg-red-600 hover:bg-red-700 text-white font-semibold text-sm rounded-lg shadow transition">
                        <i class="fas fa-trash-alt"></i> Hapus
                    </button>
                </div>

            </div>

        @empty
            <div class="col-span-full text-center text-gray-400 italic">
                Tidak ada data jadwal program kerja.
            </div>
        @endforelse

    </div>

    {{-- DELETE CONFIRM --}}
    <script>
        function confirmDeleteJadwal(id, judul) {
            Swal.fire({
                title: 'Hapus Jadwal?',
                text: `Yakin ingin menghapus jadwal dari program "${judul}"?`,
                icon: 'warning',
                showCancelButton: true,
                confirmButtonColor: '#d33',
                cancelButtonColor: '#6c757d',
                confirmButtonText: 'Ya, hapus',
                cancelButtonText: 'Batal'
            }).then((result) => {
                if (result.isConfirmed) {
                    const form = document.createElement('form');
                    form.method = 'POST';
                    form.action = `/jadwal-proker/${id}`;

                    const csrf = document.createElement('input');
                    csrf.type = 'hidden';
                    csrf.name = '_token';
                    csrf.value = '{{ csrf_token() }}';

                    const method = document.createElement('input');
                    method.type = 'hidden';
                    method.name = '_method';
                    method.value = 'DELETE';

                    form.appendChild(csrf);
                    form.appendChild(method);

                    document.body.appendChild(form);
                    form.submit();
                }
            });
        }
    </script>
@endsection
