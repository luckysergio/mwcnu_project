@extends('layouts.app')

@section('content')
    <link href="https://cdn.jsdelivr.net/npm/sweetalert2@11.0.15/dist/sweetalert2.min.css" rel="stylesheet" />

    <div class="max-w-4xl mx-auto px-4 sm:px-6 lg:px-8 py-10">
        <div class="bg-white p-6 rounded-2xl shadow-md space-y-6">

            <div class="text-center">
                <h1 class="text-2xl font-bold text-gray-800">Edit Laporan Program Kerja</h1>
                <p class="text-gray-500 text-sm">Perbarui informasi laporan program kerja</p>
            </div>

            @if ($errors->any())
                <script src="https://cdn.jsdelivr.net/npm/sweetalert2@11.0.15/dist/sweetalert2.all.min.js"></script>
                <script>
                    document.addEventListener('DOMContentLoaded', function () {
                        Swal.fire({
                            icon: 'error',
                            title: 'Gagal!',
                            html: `{!! implode('<br>', $errors->all()) !!}`
                        });
                    });
                </script>
            @endif

            @if (session('success'))
                <script>
                    Swal.fire({
                        icon: 'success',
                        title: 'Berhasil!',
                        text: @json(session('success')),
                        didClose: () => window.location.href = "/laporan"
                    });
                </script>
            @endif

            <form action="{{ route('laporan.update', $laporan->id) }}" method="POST" enctype="multipart/form-data" class="space-y-6">
                @csrf
                @method('PUT')

                <div>
                    <label for="proker_id" class="block text-sm font-medium text-gray-700 mb-1">Pilih Program Kerja</label>
                    <select name="proker_id" id="proker_id" required
                        class="w-full px-4 py-2 border rounded-lg shadow-sm focus:ring-green-500 focus:border-green-500">
                        @foreach ($prokers as $proker)
                            <option value="{{ $proker->id }}" {{ $laporan->proker_id == $proker->id ? 'selected' : '' }}>
                                {{ $proker->judul }}
                            </option>
                        @endforeach
                    </select>
                </div>

                <div>
                    <label for="catatan" class="block text-sm font-medium text-gray-700 mb-1">Catatan Laporan</label>
                    <textarea name="catatan" id="catatan" rows="4"
                        class="w-full px-4 py-2 border rounded-lg shadow-sm focus:ring-green-500 focus:border-green-500"
                        placeholder="Tuliskan catatan kegiatan...">{{ old('catatan', $laporan->catatan) }}</textarea>
                </div>

                <div>
                    <label class="block text-sm font-medium text-gray-700 mb-1">Foto Dokumentasi (Tambahan)</label>
                    <input type="file" name="foto[]" multiple accept="image/*"
                        class="w-full px-4 py-2 border rounded-lg shadow-sm focus:ring-green-500 focus:border-green-500">
                    <p class="text-xs text-gray-500 mt-1">Bisa unggah lebih dari satu file (maks. 2MB per file).</p>
                </div>

                @php
                    $fotos = is_array($laporan->foto) ? $laporan->foto : (json_decode($laporan->foto, true) ?? []);
                @endphp

                @if (is_array($fotos) && count($fotos))
                    <div class="pt-4">
                        <label class="block text-sm font-medium text-gray-700 mb-2">Foto yang Sudah Ada</label>
                        <div class="grid grid-cols-2 md:grid-cols-3 gap-4">
                            @foreach ($fotos as $i => $img)
                                <div class="relative group cursor-pointer">
                                    <img src="{{ asset('storage/laporan/' . $img) }}"
                                        class="w-full h-36 object-cover rounded-lg shadow-md hover:scale-105 transition"
                                        onclick="previewImage('{{ asset('storage/laporan/' . $img) }}')"
                                        alt="Foto {{ $i + 1 }}">
                                </div>
                            @endforeach
                        </div>
                    </div>
                @endif

                <div class="flex justify-center pt-6">
                    <button type="submit"
                        class="inline-flex items-center gap-2 px-6 py-2 bg-gradient-to-r from-green-500 to-green-600 text-white text-sm font-semibold rounded-lg shadow hover:from-green-600 hover:to-green-700 focus:ring-2 focus:ring-green-300">
                        <i class="fas fa-save text-sm"></i> Update Laporan
                    </button>
                </div>
            </form>
        </div>
    </div>

    <!-- Modal Preview -->
    <div id="previewModal" class="fixed inset-0 bg-black bg-opacity-60 z-50 hidden items-center justify-center">
        <div class="relative bg-white p-4 rounded-lg shadow-lg max-w-3xl w-full flex flex-col items-center">
            <button onclick="closePreview()"
                class="absolute top-2 right-2 text-gray-700 hover:text-red-500 text-2xl font-bold focus:outline-none z-50">
                &times;
            </button>
            <img id="previewImage" src="" class="max-h-[75vh] object-contain rounded-md" />
        </div>
    </div>

    <script>
        function previewImage(src) {
            const modal = document.getElementById('previewModal');
            const image = document.getElementById('previewImage');
            if (modal && image) {
                image.src = src;
                modal.classList.remove('hidden');
                modal.classList.add('flex');
            }
        }

        function closePreview() {
            const modal = document.getElementById('previewModal');
            if (modal) {
                modal.classList.remove('flex');
                modal.classList.add('hidden');
            }
        }
    </script>
@endsection
