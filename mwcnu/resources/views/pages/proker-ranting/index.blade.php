@extends('layouts.app')

@section('content')
    <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8 py-6">

        @if (isset($listRanting))
            <div class="flex justify-center mb-6">
                <form method="GET" action="{{ route('proker-ranting.index') }}">
                    <select name="ranting_id" id="filterRanting" onchange="this.form.submit()"
                        class="w-full max-w-xs px-4 py-3 border rounded-full shadow-md focus:ring-green-400">

                        <option value="">Semua Ranting</option>

                        @foreach ($listRanting as $ranting)
                            <option value="{{ $ranting->id }}"
                                {{ request('ranting_id') == $ranting->id ? 'selected' : '' }}>
                                {{ ucfirst($ranting->kelurahan) }}
                            </option>
                        @endforeach
                    </select>
                </form>
            </div>
        @endif

        <div class="text-center mb-6">
            <h3 class="text-xl md:text-2xl font-semibold text-emerald-600 uppercase">
                Program Kerja Terjadwal
            </h3>
        </div>

        <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-3 gap-6">
            @forelse ($penjadwalan as $item)
                <div class="bg-gray-50 rounded-2xl shadow hover:shadow-md transition p-6">
                    <div class="text-center mb-4">
                        <h4 class="text-lg font-semibold text-gray-800">
                            {{ $item->jadwalProker->proker->judul ?? '-' }}
                        </h4>
                    </div>
                    <ul class="space-y-2 text-sm text-gray-600">
                        <li>
                            <strong>Kegiatan:</strong> {{ $item->kegiatan ?? '-' }}
                        </li>
                        <li>
                            <strong>Mulai:</strong>
                            {{ \Carbon\Carbon::parse($item->tanggal_mulai)->format('d-m-Y') }}
                        </li>
                        <li>
                            <strong>Selesai:</strong>
                            {{ \Carbon\Carbon::parse($item->tanggal_selesai)->format('d-m-Y') }}
                        </li>
                        <li>
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


        <div class="text-center mt-12 mb-6">
            <h3 class="text-xl md:text-2xl font-semibold text-emerald-600 uppercase">
                Program Kerja Sedang Berjalan
            </h3>
        </div>

        <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-3 gap-6">
            @forelse ($berjalan as $item)
                <div class="bg-white rounded-2xl shadow hover:shadow-md transition p-6">
                    <div class="text-center mb-4">
                        <h4 class="text-lg font-semibold text-gray-800">
                            {{ $item->jadwalProker->proker->judul ?? '-' }}
                        </h4>
                    </div>
                    <ul class="space-y-2 text-sm text-gray-600">
                        <li>
                            <strong>Kegiatan:</strong> {{ $item->kegiatan ?? '-' }}
                        </li>
                        <li>
                            <strong>Mulai:</strong>
                            {{ \Carbon\Carbon::parse($item->tanggal_mulai)->format('d-m-Y') }}
                        </li>
                        <li>
                            <strong>Selesai:</strong>
                            {{ \Carbon\Carbon::parse($item->tanggal_selesai)->format('d-m-Y') }}
                        </li>
                        <li>
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


        <div class="text-center mt-12 mb-6">
            <h3 class="text-xl md:text-2xl font-semibold text-emerald-600 uppercase">
                Program Kerja Sudah Selesai Dilaksanakan
            </h3>
        </div>

        <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-3 gap-6">
            @forelse ($selesai as $item)
                <div class="bg-gray-100 rounded-2xl shadow hover:shadow-md transition p-6">
                    <div class="text-center mb-4">
                        <h4 class="text-lg font-semibold text-gray-800">
                            {{ $item->jadwalProker->proker->judul ?? '-' }}
                        </h4>
                    </div>
                    <ul class="space-y-2 text-sm text-gray-600">
                        <li>
                            <strong>Kegiatan:</strong> {{ $item->kegiatan ?? '-' }}
                        </li>
                        <li>
                            <strong>Mulai:</strong>
                            {{ \Carbon\Carbon::parse($item->tanggal_mulai)->format('d-m-Y') }}
                        </li>
                        <li>
                            <strong>Selesai:</strong>
                            {{ \Carbon\Carbon::parse($item->tanggal_selesai)->format('d-m-Y') }}
                        </li>
                        <li>
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
