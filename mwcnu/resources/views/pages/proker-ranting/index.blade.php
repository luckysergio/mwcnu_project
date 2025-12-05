@extends('layouts.app')

@section('content')
    <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8 py-6">

        @if (isset($listRanting))
            <div class="flex justify-center mb-6">
                <form method="GET" action="{{ route('proker-ranting.index') }}">
                    <select name="ranting_id" onchange="this.form.submit()"
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

        @php
            $penjadwalanGroup = $penjadwalan->groupBy(fn($item) => $item->jadwalProker->proker->id);
        @endphp

        <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-3 gap-6">
            @forelse ($penjadwalanGroup as $group)
                @php $firstItem = $group->first(); @endphp

                <div class="bg-gray-100 rounded-2xl shadow hover:shadow-md transition p-6">

                    <div class="text-center mb-4">
                        <h4 class="text-lg font-semibold text-gray-800">
                            {{ $firstItem->jadwalProker->proker->judul ?? '-' }}
                        </h4>

                        <p class="text-sm text-gray-600">
                            {{ $firstItem->jadwalProker->proker->ranting->kelurahan ?? 'MWC' }}
                        </p>
                    </div>

                    <div class="space-y-3 max-h-64 overflow-y-auto pr-1">
                        @foreach ($group as $detail)
                            <div class="p-3 bg-white border border-gray-200 rounded-lg text-center">
                                <p class="font-semibold text-gray-800">
                                    {{ $detail->kegiatan ?? '-' }}
                                </p>

                                <p class="text-xs text-gray-500">
                                    {{ \Carbon\Carbon::parse($detail->tanggal_mulai)->format('d M Y') }}
                                    -
                                    {{ \Carbon\Carbon::parse($detail->tanggal_selesai)->format('d M Y') }}
                                </p>

                                <p class="text-xs text-gray-500 mt-1">
                                    {{ $detail->catatan ?? '-' }}
                                </p>
                            </div>
                        @endforeach
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
                Program Kerja Sedang Berjalan
            </h3>
        </div>

        @php
            $berjalanGroup = $berjalan->groupBy(fn($item) => $item->jadwalProker->proker->id);
        @endphp

        <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-3 gap-6">
            @forelse ($berjalanGroup as $group)
                @php $firstItem = $group->first(); @endphp

                <div class="bg-gray-100 rounded-2xl shadow hover:shadow-md transition p-6">

                    <div class="text-center mb-4">
                        <h4 class="text-lg font-semibold text-gray-800">
                            {{ $firstItem->jadwalProker->proker->judul ?? '-' }}
                        </h4>

                        <p class="text-sm text-gray-600">
                            {{ $firstItem->jadwalProker->proker->ranting->kelurahan ?? 'MWC' }}
                        </p>
                    </div>

                    <div class="space-y-3 max-h-64 overflow-y-auto pr-1">
                        @foreach ($group as $detail)
                            @php
                                $detailFotos = $detail->foto ? json_decode($detail->foto, true) : [];
                                $detailFotoCount = count($detailFotos);
                            @endphp

                            <div class="p-3 bg-white border border-gray-200 rounded-lg" x-data="{
                                openGallery: false,
                                openUpload: false,
                                currentIndex: 0,
                                fotos: {{ json_encode($detailFotos) }}
                            }">
                                <p class="font-semibold text-gray-800 text-center">
                                    {{ $detail->kegiatan ?? '-' }}
                                </p>

                                <p class="text-xs text-gray-500 text-center">
                                    {{ \Carbon\Carbon::parse($detail->tanggal_mulai)->format('d M Y') }}
                                    -
                                    {{ \Carbon\Carbon::parse($detail->tanggal_selesai)->format('d M Y') }}
                                </p>

                                <p class="text-xs text-gray-500 mt-1 text-center">
                                    {{ $detail->catatan ?? '-' }}
                                </p>

                                @if ($detailFotoCount > 0)
                                    <div class="mt-1 text-center">
                                        <button @click="openGallery = true; currentIndex = 0"
                                            class="text-green-600 text-sm underline font-semibold">
                                            Lihat {{ $detailFotoCount }} Foto
                                        </button>
                                    </div>
                                @endif

                                <div x-cloak x-show="openGallery" x-transition.opacity
                                    class="fixed inset-0 z-50 flex items-center justify-center bg-black bg-opacity-75">

                                    <div class="relative w-full max-w-3xl p-4">
                                        <button @click="openGallery = false"
                                            class="absolute top-2 right-2 text-white text-2xl font-bold">
                                            &times;
                                        </button>

                                        <div class="bg-white rounded-lg overflow-hidden">

                                            <div class="px-4 py-2 text-center font-semibold text-gray-700 border-b">
                                                {{ $detail->kegiatan }}
                                            </div>

                                            <img :src="'{{ asset('storage') }}/' + fotos[currentIndex]"
                                                class="w-full h-96 object-contain">

                                            <div class="flex justify-between p-3 bg-gray-100">
                                                <button
                                                    @click="currentIndex = (currentIndex - 1 + fotos.length) % fotos.length"
                                                    class="px-4 py-2 bg-green-600 text-white rounded">
                                                    Prev
                                                </button>

                                                <button @click="currentIndex = (currentIndex + 1) % fotos.length"
                                                    class="px-4 py-2 bg-green-600 text-white rounded">
                                                    Next
                                                </button>
                                            </div>
                                        </div>
                                    </div>
                                </div>

                                <div class="text-center">
                                    <button @click="openUpload = true"
                                        class="mt-2 text-sm bg-green-500 hover:bg-green-600 text-white px-4 py-1.5 rounded">
                                        Upload Foto
                                    </button>
                                </div>

                                <div x-cloak x-show="openUpload"
                                    class="fixed inset-0 z-50 flex items-center justify-center bg-black bg-opacity-50">

                                    <div class="bg-white p-6 rounded-xl w-full max-w-md" @click.away="openUpload = false">
                                        <h3 class="text-lg font-semibold mb-4">Upload Foto</h3>

                                        <form method="POST"
                                            action="{{ route('proker-ranting.upload-foto', $detail->id) }}"
                                            enctype="multipart/form-data">

                                            @csrf
                                            <input type="file" name="foto[]" multiple class="mb-4 w-full">

                                            <div class="flex justify-end gap-2">
                                                <button type="button" @click="openUpload = false"
                                                    class="border px-4 py-2 rounded">
                                                    Batal
                                                </button>

                                                <button type="submit" class="bg-green-600 text-white px-4 py-2 rounded">
                                                    Upload
                                                </button>
                                            </div>
                                        </form>
                                    </div>
                                </div>

                            </div>
                        @endforeach
                    </div>

                </div>

            @empty
                <div class="col-span-full text-center text-gray-400 py-10">
                    Tidak ada Program kerja yang sedang berjalan
                </div>
            @endforelse
        </div>

        <div class="text-center mt-12 mb-6">
            <h3 class="text-xl md:text-2xl font-semibold text-emerald-600 uppercase">
                Program Kerja Sudah Selesai Dilaksanakan
            </h3>
        </div>

        <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-3 gap-6">
            @forelse ($selesai as $prokerGroup)
                @php
                    $firstItem = $prokerGroup->first();
                @endphp

                <div class="bg-gray-100 rounded-2xl shadow hover:shadow-md transition p-6">

                    <div class="text-center mb-4">
                        <h4 class="text-lg font-semibold text-gray-800">
                            {{ $firstItem->jadwalProker->proker->judul ?? '-' }}
                        </h4>

                        <p class="text-sm text-gray-600">
                            {{ $firstItem->jadwalProker->proker->ranting->kelurahan ?? 'MWC' }}
                        </p>
                    </div>

                    <div class="space-y-3 max-h-64 overflow-y-auto pr-1">

                        @foreach ($prokerGroup as $detail)
                            @php
                                $detailFotos = $detail->foto ? json_decode($detail->foto, true) : [];
                                $detailFotoCount = count($detailFotos);
                            @endphp

                            <div class="p-3 bg-white border border-gray-200 rounded-lg" x-data="{
                                openGallery: false,
                                currentIndex: 0,
                                fotos: {{ json_encode($detailFotos) }}
                            }">

                                <p class="font-semibold text-gray-800 text-center">
                                    {{ $detail->kegiatan ?? '-' }}
                                </p>

                                <p class="text-xs text-gray-500 text-center">
                                    {{ \Carbon\Carbon::parse($detail->tanggal_mulai)->format('d M Y') }}
                                    -
                                    {{ \Carbon\Carbon::parse($detail->tanggal_selesai)->format('d M Y') }}
                                </p>

                                @if ($detailFotoCount > 0)
                                    <div class="mt-1 text-center">
                                        <button @click="openGallery = true; currentIndex = 0"
                                            class="text-green-600 text-sm underline font-semibold">
                                            Lihat {{ $detailFotoCount }} Foto
                                        </button>
                                    </div>

                                    <div x-cloak x-show="openGallery" x-transition.opacity
                                        class="fixed inset-0 z-50 flex items-center justify-center bg-black bg-opacity-80">

                                        <div class="relative w-full max-w-3xl p-4">
                                            <button @click="openGallery = false"
                                                class="absolute top-2 right-2 text-white text-2xl font-bold">
                                                &times;
                                            </button>

                                            <div class="bg-white rounded-lg overflow-hidden">

                                                <div class="px-4 py-2 text-center font-semibold text-gray-700 border-b">
                                                    {{ $detail->kegiatan }}
                                                </div>

                                                <img :src="'{{ asset('storage') }}/' + fotos[currentIndex]"
                                                    class="w-full h-96 object-contain" alt="Foto Kegiatan">

                                                <div class="flex justify-between p-3 bg-gray-100">
                                                    <button
                                                        @click="currentIndex = (currentIndex - 1 + fotos.length) % fotos.length"
                                                        class="px-4 py-2 bg-green-500 text-white rounded">
                                                        Prev
                                                    </button>

                                                    <button @click="currentIndex = (currentIndex + 1) % fotos.length"
                                                        class="px-4 py-2 bg-green-500 text-white rounded">
                                                        Next
                                                    </button>
                                                </div>

                                                <div class="text-center py-2 text-sm text-gray-600">
                                                    Foto <span x-text="currentIndex + 1"></span> dari
                                                    {{ $detailFotoCount }}
                                                </div>
                                            </div>
                                        </div>

                                    </div>
                                @endif

                            </div>
                        @endforeach
                    </div>

                </div>

            @empty
                <div class="col-span-full text-center text-gray-400 py-10">
                    Tidak ada Program kerja yang sudah selesai
                </div>
            @endforelse
        </div>

    </div>
@endsection
