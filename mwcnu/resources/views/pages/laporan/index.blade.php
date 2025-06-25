@extends('layouts.app')

@section('content')
<link href="https://cdn.jsdelivr.net/npm/sweetalert2@11.0.15/dist/sweetalert2.min.css" rel="stylesheet" />
<link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.5.0/css/all.min.css" crossorigin="anonymous" referrerpolicy="no-referrer" />

@auth
    @php
        $user = auth()->user();
        $canManage = in_array($user->anggota?->role?->jabatan, ['Admin', 'Tanfidiyah', 'Tanfidiyah ranting', 'Sekretaris']);
    @endphp
    @if ($canManage)
        <div class="flex flex-col sm:flex-row justify-between items-center mb-6 gap-4">
            <h1 class="text-3xl font-bold text-gray-900">Laporan Program Kerja</h1>
            <a href="{{ route('laporan.create') }}"
                class="inline-flex items-center justify-center gap-3 px-6 py-3 bg-gradient-to-r from-green-500 to-green-600 hover:from-green-600 hover:to-green-700 text-white font-semibold text-sm rounded-xl shadow-lg transition-all duration-200">
                <i class="fas fa-plus text-sm"></i>
                <span>Buat Laporan</span>
                @if ($unreportedCount > 0)
                    <span class="ml-2 px-2 py-1 text-xs font-bold text-white bg-red-600 rounded-full">
                        {{ $unreportedCount }}
                    </span>
                @endif
            </a>
        </div>
    @endif
@endauth

@if (session('success'))
    <script src="https://cdn.jsdelivr.net/npm/sweetalert2@11.0.15/dist/sweetalert2.all.min.js"></script>
    <script>
        Swal.fire({
            icon: 'success',
            title: 'Berhasil!',
            text: @json(session('success')),
            confirmButtonColor: '#22c55e'
        });
    </script>
@endif

<div class="grid grid-cols-1 md:grid-cols-2 xl:grid-cols-3 gap-6">
    @forelse ($laporans as $laporan)
        @php
            $fotos = is_array($laporan->foto) ? $laporan->foto : (json_decode($laporan->foto, true) ?? []);
        @endphp

        <div class="bg-white border border-gray-200 rounded-2xl shadow-md hover:shadow-lg p-6 flex flex-col justify-between">
            <div class="space-y-3">
                <div class="flex items-start justify-between">
                    <h2 class="text-lg font-bold text-gray-900 leading-snug">{{ $laporan->proker->judul }}</h2>
                    <span class="inline-block px-3 py-1 text-xs font-semibold text-white bg-green-600 rounded-full">Laporan</span>
                </div>

                <p class="text-sm text-gray-700"><strong>Catatan:</strong> {{ $laporan->catatan ?? '-' }}</p>

                <div class="text-sm text-gray-700">
                    <strong>Dokumentasi:</strong>
                    @if (is_array($fotos) && count($fotos) > 0)
                        <button onclick="showModal({{ $laporan->id }})" class="text-blue-600 hover:underline">
                            Lihat Foto
                        </button>

                        <!-- Modal Foto -->
                        <div id="modal-{{ $laporan->id }}" class="fixed inset-0 bg-black bg-opacity-60 z-50 hidden items-center justify-center">
                            <div class="bg-white rounded-lg overflow-auto max-h-[90%] p-6 w-full max-w-4xl relative flex flex-col">
                                <button onclick="closeModal({{ $laporan->id }})"
                                    class="absolute top-3 right-3 text-gray-600 hover:text-red-500 text-lg">
                                    <i class="fas fa-times"></i>
                                </button>
                                <div class="grid grid-cols-1 sm:grid-cols-2 md:grid-cols-3 gap-4 mt-6">
                                    @foreach ($fotos as $img)
                                        <a href="{{ asset('storage/laporan/' . $img) }}" target="_blank">
                                            <img src="{{ asset('storage/laporan/' . $img) }}"
                                                class="rounded shadow-md w-full object-cover max-h-48 hover:scale-105 transition"
                                                alt="Foto Laporan">
                                        </a>
                                    @endforeach
                                </div>
                            </div>
                        </div>
                    @else
                        <p class="text-gray-500 italic">Tidak ada foto</p>
                    @endif
                </div>

                @if ($laporan->proker->jadwalProker && $laporan->proker->jadwalProker->details)
                    <div class="text-sm text-gray-700 mt-4 space-y-2">
                        <strong>Jadwal:</strong>
                        @foreach ($laporan->proker->jadwalProker->details as $detail)
                            <div class="mt-2 border p-3 rounded-lg bg-gray-50">
                                <p><strong>Kegiatan:</strong> {{ $detail->kegiatan }}</p>
                                <p><strong>Tanggal:</strong>
                                    {{ \Carbon\Carbon::parse($detail->tanggal_mulai)->translatedFormat('d M Y') }} -
                                    {{ \Carbon\Carbon::parse($detail->tanggal_selesai)->translatedFormat('d M Y') }}
                                </p>
                                <p><strong>Catatan:</strong> {{ $detail->catatan ?? '-' }}</p>
                            </div>
                        @endforeach
                    </div>
                @endif
            </div>

            @auth
                @if ($canManage)
                    <div class="mt-5 flex flex-col gap-3">
                        <a href="{{ route('laporan.edit', $laporan->id) }}"
                            class="inline-flex items-center justify-center gap-2 px-5 py-2 bg-gradient-to-r from-yellow-500 to-yellow-600 hover:from-yellow-600 hover:to-yellow-700 text-white font-semibold text-sm rounded-lg shadow transition">
                            <i class="fas fa-edit"></i> Edit
                        </a>
                        <a href="{{ route('laporan.export.pdf', $laporan->proker_id) }}" target="_blank"
                            class="inline-flex items-center justify-center gap-2 px-5 py-2 bg-gradient-to-r from-red-600 to-red-700 hover:from-red-700 hover:to-red-800 text-white font-semibold text-sm rounded-lg shadow transition">
                            <i class="fas fa-file-pdf"></i> Cetak PDF
                        </a>
                    </div>
                @endif
            @endauth
        </div>
    @empty
        <div class="col-span-full text-center text-gray-400 italic">
            Tidak ada laporan program kerja.
        </div>
    @endforelse
</div>

<script>
    function showModal(id) {
        const modal = document.getElementById('modal-' + id);
        if (modal) {
            modal.classList.remove('hidden');
            modal.classList.add('flex');
        }
    }

    function closeModal(id) {
        const modal = document.getElementById('modal-' + id);
        if (modal) {
            modal.classList.remove('flex');
            modal.classList.add('hidden');
        }
    }
</script>
@endsection
