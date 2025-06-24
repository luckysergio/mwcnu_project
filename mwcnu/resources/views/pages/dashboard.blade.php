@extends('layouts.app')

@section('content')
<div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8 py-6">


    {{-- === PROGRAM TERJADWAL === --}}
    <div class="text-center mb-6">
        <h3 class="text-xl md:text-2xl font-semibold text-emerald-600 uppercase">Program Kerja yang Terjadwal</h3>
    </div>

    <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-3 gap-6">
        @forelse ($penjadwalan as $item)
            <div class="bg-gray-50 rounded-2xl shadow hover:shadow-md transition p-6">
                <div class="text-center mb-4">
                    <h4 class="text-lg font-semibold text-gray-800">{{ $item->proker->program ?? '-' }}</h4>
                </div>
                <ul class="space-y-2 text-sm text-gray-600">
                    <li>
                        <i class="fas fa-calendar-alt text-emerald-500 mr-2"></i>
                        <strong>Mulai:</strong> {{ \Carbon\Carbon::parse($item->tanggal_mulai)->format('d-m-Y') }}
                    </li>
                    <li>
                        <i class="fas fa-calendar-check text-emerald-700 mr-2"></i>
                        <strong>Selesai:</strong> {{ \Carbon\Carbon::parse($item->tanggal_selesai)->format('d-m-Y') }}
                    </li>
                    <li>
                        <i class="fas fa-sticky-note text-gray-400 mr-2"></i>
                        <strong>Catatan:</strong> {{ $item->catatan ?? '-' }}
                    </li>
                </ul>
            </div>
        @empty
            <div class="col-span-full text-center text-gray-400 py-10">
                Tidak ada Program kerja
            </div>
        @endforelse
    </div>

    {{-- === PROGRAM BERJALAN === --}}
    <div class="text-center mt-12 mb-6">
        <h3 class="text-xl md:text-2xl font-semibold text-emerald-600 uppercase">Program Kerja yang Sedang Berjalan</h3>
    </div>

    <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-3 gap-6">
        @forelse ($berjalan as $item)
            <div class="bg-white rounded-2xl shadow hover:shadow-md transition p-6">
                <div class="text-center mb-4">
                    <h4 class="text-lg font-semibold text-gray-800">{{ $item->proker->program ?? '-' }}</h4>
                </div>
                <ul class="space-y-2 text-sm text-gray-600">
                    <li>
                        <i class="fas fa-calendar-alt text-emerald-500 mr-2"></i>
                        <strong>Mulai:</strong> {{ \Carbon\Carbon::parse($item->tanggal_mulai)->format('d-m-Y') }}
                    </li>
                    <li>
                        <i class="fas fa-calendar-check text-emerald-700 mr-2"></i>
                        <strong>Selesai:</strong> {{ \Carbon\Carbon::parse($item->tanggal_selesai)->format('d-m-Y') }}
                    </li>
                    <li>
                        <i class="fas fa-sticky-note text-gray-400 mr-2"></i>
                        <strong>Catatan:</strong> {{ $item->catatan ?? '-' }}
                    </li>
                </ul>
            </div>
        @empty
            <div class="col-span-full text-center text-gray-400 py-10">
                Tidak ada Program kerja
            </div>
        @endforelse
    </div>

    {{-- === PROGRAM SELESAI === --}}
    <div class="text-center mt-12 mb-6">
        <h3 class="text-xl md:text-2xl font-semibold text-emerald-600 uppercase">Program Kerja yang Sudah Selesai Dilaksanakan</h3>
    </div>

    <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-3 gap-6">
        @forelse ($selesai as $item)
            <div class="bg-gray-100 rounded-2xl shadow hover:shadow-md transition p-6">
                <div class="text-center mb-4">
                    <h4 class="text-lg font-semibold text-gray-800">{{ $item->proker->program ?? '-' }}</h4>
                </div>
                <ul class="space-y-2 text-sm text-gray-600">
                    <li>
                        <i class="fas fa-calendar-alt text-emerald-500 mr-2"></i>
                        <strong>Mulai:</strong> {{ \Carbon\Carbon::parse($item->tanggal_mulai)->format('d-m-Y') }}
                    </li>
                    <li>
                        <i class="fas fa-calendar-check text-emerald-700 mr-2"></i>
                        <strong>Selesai:</strong> {{ \Carbon\Carbon::parse($item->tanggal_selesai)->format('d-m-Y') }}
                    </li>
                    <li>
                        <i class="fas fa-sticky-note text-gray-400 mr-2"></i>
                        <strong>Catatan:</strong> {{ $item->catatan ?? '-' }}
                    </li>
                </ul>
            </div>
        @empty
            <div class="col-span-full text-center text-gray-400 py-10">
                Tidak ada Program kerja
            </div>
        @endforelse
    </div>
</div>
@endsection
