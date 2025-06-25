@extends('layouts.app')

@section('content')
<link href="https://cdn.jsdelivr.net/npm/sweetalert2@11.0.15/dist/sweetalert2.min.css" rel="stylesheet" />

<div class="max-w-4xl mx-auto px-4 sm:px-6 lg:px-8 py-10">
    <div class="bg-white p-6 rounded-2xl shadow-md space-y-6">

        <div class="text-center">
            <h1 class="text-2xl font-bold text-gray-800">Buat Laporan Program Kerja</h1>
            <p class="text-gray-500 text-sm">Isi laporan berdasarkan program kerja yang telah selesai</p>
        </div>

        @if (session('success'))
            <script src="https://cdn.jsdelivr.net/npm/sweetalert2@11"></script>
            <script>
                Swal.fire({
                    icon: 'success',
                    title: 'Berhasil!',
                    text: @json(session('success')),
                    didClose: () => {
                        window.location.href = "{{ route('laporan.index') }}";
                    }
                });
            </script>
        @endif

        @if ($errors->any())
            <script>
                Swal.fire({
                    icon: 'error',
                    title: 'Gagal!',
                    html: `{!! implode('<br>', $errors->all()) !!}`
                });
            </script>
        @endif

        <form action="{{ route('laporan.store') }}" method="POST" enctype="multipart/form-data" class="space-y-6">
            @csrf

            <div>
                <label for="proker_id" class="block text-sm font-medium text-gray-700 mb-1">Pilih Program Kerja</label>
                <select name="proker_id" id="proker_id" required
                    class="w-full px-4 py-2 border rounded-lg shadow-sm focus:ring-green-500 focus:border-green-500">
                    <option value="">-- Pilih Program Kerja --</option>
                    @foreach ($prokers as $proker)
                        <option value="{{ $proker->id }}" {{ old('proker_id') == $proker->id ? 'selected' : '' }}>
                            {{ $proker->judul }}
                        </option>
                    @endforeach
                </select>
            </div>

            <div>
                <label for="catatan" class="block text-sm font-medium text-gray-700 mb-1">Catatan Laporan</label>
                <textarea name="catatan" id="catatan" rows="4"
                    class="w-full px-4 py-2 border rounded-lg shadow-sm focus:ring-green-500 focus:border-green-500"
                    placeholder="Tuliskan catatan kegiatan...">{{ old('catatan') }}</textarea>
            </div>

            <div>
                <label for="foto[]" class="block text-sm font-medium text-gray-700 mb-1">Upload Foto Dokumentasi</label>
                <input type="file" name="foto[]" multiple accept="image/*"
                    class="w-full px-4 py-2 border rounded-lg shadow-sm focus:ring-green-500 focus:border-green-500">
                <p class="text-xs text-gray-500 mt-1">Bisa unggah lebih dari satu file (maks. 2MB per file).</p>
            </div>

            <div class="flex justify-center">
                <button type="submit"
                    class="inline-flex items-center gap-2 px-6 py-2 bg-gradient-to-r from-green-500 to-green-600 text-white text-sm font-semibold rounded-lg shadow hover:from-green-600 hover:to-green-700 focus:ring-2 focus:ring-green-300">
                    <i class="fas fa-save text-sm"></i> Simpan Laporan
                </button>
            </div>
        </form>
    </div>
</div>
@endsection
