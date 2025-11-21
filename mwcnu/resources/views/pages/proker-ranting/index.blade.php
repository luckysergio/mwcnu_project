@extends('layouts.app')

@section('content')
    <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8 py-6">

        {{-- Filter Ranting --}}
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

        {{-- Program Kerja Terjadwal --}}
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
                        <li><strong>Kegiatan:</strong> {{ $item->kegiatan ?? '-' }}</li>
                        <li><strong>Mulai:</strong> {{ \Carbon\Carbon::parse($item->tanggal_mulai)->format('d-m-Y') }}</li>
                        <li><strong>Selesai:</strong> {{ \Carbon\Carbon::parse($item->tanggal_selesai)->format('d-m-Y') }}
                        </li>
                        <li><strong>Catatan:</strong> {{ $item->catatan ?? '-' }}</li>
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
                <div class="bg-white rounded-2xl shadow hover:shadow-md transition p-6 flex flex-col justify-between"
                    x-data="{ openUpload: false, openGallery: false, currentIndex: 0, fotos: {{ $item->foto ? json_encode(json_decode($item->foto, true)) : '[]' }} }">

                    <div>
                        <div class="text-center mb-4">
                            <h4 class="text-lg font-semibold text-gray-800">
                                {{ $item->jadwalProker->proker->judul ?? '-' }}
                            </h4>
                        </div>
                        <ul class="space-y-2 text-sm text-gray-600 mb-4">
                            <li><strong>Kegiatan:</strong> {{ $item->kegiatan ?? '-' }}</li>
                            <li><strong>Mulai:</strong> {{ \Carbon\Carbon::parse($item->tanggal_mulai)->format('d-m-Y') }}
                            </li>
                            <li><strong>Selesai:</strong>
                                {{ \Carbon\Carbon::parse($item->tanggal_selesai)->format('d-m-Y') }}</li>
                            <li><strong>Catatan:</strong> {{ $item->catatan ?? '-' }}</li>
                        </ul>
                    </div>

                    @if ($item->foto)
                        @php $fotoCount = count(json_decode($item->foto, true)) @endphp
                        <div class="mt-4 text-center">
                            <button @click="openGallery = true; currentIndex = 0"
                                class="text-green-600 underline font-semibold">
                                {{ $fotoCount }} Foto
                            </button>
                        </div>

                        {{-- Modal Gallery --}}
                        <div x-cloak x-show="openGallery" x-transition.opacity
                            class="fixed inset-0 z-50 flex items-center justify-center bg-black bg-opacity-75">
                            <div class="relative w-full max-w-3xl p-4">
                                <button @click="openGallery = false"
                                    class="absolute top-2 right-2 text-white text-2xl font-bold">&times;</button>

                                <div class="bg-white rounded-lg overflow-hidden">
                                    <img :src="'{{ asset('storage') }}/' + fotos[currentIndex]"
                                        class="w-full h-96 object-contain" alt="Foto Proker">

                                    <div class="flex justify-between p-2 bg-gray-100">
                                        <button @click="currentIndex = (currentIndex - 1 + fotos.length) % fotos.length"
                                            class="px-4 py-2 bg-green-500 text-white rounded hover:bg-green-600 transition">Prev</button>
                                        <button @click="currentIndex = (currentIndex + 1) % fotos.length"
                                            class="px-4 py-2 bg-green-500 text-white rounded hover:bg-green-600 transition">Next</button>
                                    </div>

                                    <div class="text-center py-2">
                                        Foto <span x-text="currentIndex + 1"></span> dari {{ $fotoCount }}
                                    </div>
                                </div>
                            </div>
                        </div>
                    @endif

                    <div class="mt-4 text-center">
                        <button @click.prevent="openUpload = true"
                            class="bg-green-500 hover:bg-green-600 text-white font-bold py-2 px-4 rounded focus:outline-none focus:shadow-outline transition">
                            Upload Foto Kegiatan
                        </button>
                    </div>

                    <div x-cloak x-show="openUpload" x-transition.opacity
                        class="fixed inset-0 z-50 flex items-center justify-center bg-black bg-opacity-50">
                        <div @click.away="openUpload = false" class="bg-white rounded-xl p-6 w-full max-w-md shadow-lg">
                            <h3 class="text-lg font-semibold mb-4">Upload Foto Kegiatan</h3>
                            <form method="POST" action="{{ route('proker-ranting.upload-foto', $item->id) }}"
                                enctype="multipart/form-data">
                                @csrf
                                <input type="file" name="foto[]" multiple required class="mb-4 w-full">
                                <div class="flex justify-end gap-2">
                                    <button type="button" @click="openUpload = false"
                                        class="px-4 py-2 rounded-full border border-gray-300 hover:bg-gray-100 transition">
                                        Batal
                                    </button>
                                    <button type="submit"
                                        class="bg-green-500 hover:bg-green-600 text-white font-bold py-2 px-4 rounded focus:outline-none focus:shadow-outline transition">
                                        Upload
                                    </button>
                                </div>
                            </form>
                        </div>
                    </div>

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
                        <li><strong>Kegiatan:</strong> {{ $item->kegiatan ?? '-' }}</li>
                        <li><strong>Mulai:</strong> {{ \Carbon\Carbon::parse($item->tanggal_mulai)->format('d-m-Y') }}</li>
                        <li><strong>Selesai:</strong> {{ \Carbon\Carbon::parse($item->tanggal_selesai)->format('d-m-Y') }}
                        </li>
                        <li><strong>Catatan:</strong> {{ $item->catatan ?? '-' }}</li>
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
