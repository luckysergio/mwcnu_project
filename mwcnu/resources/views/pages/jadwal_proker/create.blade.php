@extends('layouts.app')

@section('content')
<link href="https://cdn.jsdelivr.net/npm/sweetalert2@11.0.15/dist/sweetalert2.min.css" rel="stylesheet" />

<div class="max-w-4xl mx-auto px-4 sm:px-6 lg:px-8 py-10">
    <div class="bg-white p-6 rounded-2xl shadow-md space-y-6">

        <div class="text-center">
            <h1 class="text-2xl font-bold text-gray-800">Buat Jadwal Program Kerja</h1>
            <p class="text-gray-500 text-sm">Isi jadwal berdasarkan program kerja yang telah disetujui</p>
        </div>

        @if (session('success'))
            <script src="https://cdn.jsdelivr.net/npm/sweetalert2@11"></script>
            <script>
                Swal.fire({
                    icon: 'success',
                    title: 'Berhasil!',
                    text: @json(session('success')),
                    didClose: () => {
                        window.location.href = "{{ route('jadwal-proker.index') }}";
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

        <form action="{{ route('jadwal-proker.store') }}" method="POST" class="space-y-5">
            @csrf

            {{-- PILIH PROKER --}}
            <div>
                <label for="proker_id" class="block text-sm font-medium text-gray-700 mb-1">Pilih Program Kerja</label>
                <select name="proker_id" id="proker_id"
                    class="w-full px-4 py-2 border rounded-lg shadow-sm focus:ring-green-500 focus:border-green-500">
                    <option value="">-- Pilih Program Kerja --</option>
                    @foreach ($prokers as $proker)
                        <option value="{{ $proker->id }}">{{ $proker->judul }}</option>
                    @endforeach
                </select>
            </div>

            {{-- ESTIMASI WAKTU --}}
            <div class="grid grid-cols-1 md:grid-cols-2 gap-4">
                <div>
                    <label for="estimasi_mulai" class="block text-sm font-medium text-gray-700 mb-1">Estimasi Mulai</label>
                    <input type="date" name="estimasi_mulai" id="estimasi_mulai"
                        class="w-full px-4 py-2 border rounded-lg shadow-sm focus:ring-green-500 focus:border-green-500" 
                        required>
                </div>

                <div>
                    <label for="estimasi_selesai" class="block text-sm font-medium text-gray-700 mb-1">Estimasi Selesai</label>
                    <input type="date" name="estimasi_selesai" id="estimasi_selesai"
                        class="w-full px-4 py-2 border rounded-lg shadow-sm focus:ring-green-500 focus:border-green-500" 
                        required>
                </div>
            </div>

            {{-- STATUS --}}
            <div>
                <label for="status" class="block text-sm font-medium text-gray-700 mb-1">Status Jadwal</label>
                <select name="status" id="status"
                    class="w-full px-4 py-2 border rounded-lg shadow-sm focus:ring-green-500 focus:border-green-500">
                    <option value="penjadwalan">Penjadwalan</option>
                    <option value="berjalan">Berjalan</option>
                    <option value="selesai">Selesai</option>
                </select>
            </div>

            <hr class="my-4">

            <div id="detailContainer">
                <h3 class="text-lg font-semibold text-gray-800 mb-2">Detail Jadwal</h3>

                <div class="detail-group space-y-3 mb-6">
                    <div class="grid grid-cols-1 md:grid-cols-2 gap-4">
                        <input type="text" name="kegiatan[]" placeholder="Kegiatan"
                            class="w-full px-4 py-2 border rounded-lg shadow-sm" required>

                        <input type="text" name="catatan[]" placeholder="Catatan (opsional)"
                            class="w-full px-4 py-2 border rounded-lg shadow-sm">
                    </div>
                    <div class="grid grid-cols-2 md:grid-cols-2 gap-4">
                        <input type="date" name="tanggal_mulai[]" class="w-full px-4 py-2 border rounded-lg shadow-sm" required>
                        <input type="date" name="tanggal_selesai[]" class="w-full px-4 py-2 border rounded-lg shadow-sm" required>
                    </div>
                    <hr class="my-2">
                </div>
            </div>

            <button type="button" onclick="addDetail()"
                class="bg-blue-500 hover:bg-blue-600 text-white text-sm font-semibold py-2 px-4 rounded-lg">
                + Tambah Kegiatan
            </button>

            <div class="flex justify-center">
                <button type="submit"
                    class="inline-flex items-center gap-2 px-6 py-2 bg-gradient-to-r from-green-500 to-green-600 text-white text-sm font-semibold rounded-lg shadow hover:from-green-600 hover:to-green-700 focus:ring-2 focus:ring-green-300">
                    <i class="fas fa-save text-sm"></i> Simpan Jadwal
                </button>
            </div>
        </form>
    </div>
</div>

<script>
    function addDetail() {
        const container = document.getElementById('detailContainer');
        const detail = document.createElement('div');
        detail.classList.add('detail-group', 'space-y-3', 'mb-6');
        detail.innerHTML = `
            <div class="grid grid-cols-1 md:grid-cols-2 gap-4">
                <input type="text" name="kegiatan[]" placeholder="Kegiatan"
                    class="w-full px-4 py-2 border rounded-lg shadow-sm" required>

                <input type="text" name="catatan[]" placeholder="Catatan (opsional)"
                    class="w-full px-4 py-2 border rounded-lg shadow-sm">
            </div>
            <div class="grid grid-cols-2 md:grid-cols-2 gap-4">
                <input type="date" name="tanggal_mulai[]" class="w-full px-4 py-2 border rounded-lg shadow-sm" required>
                <input type="date" name="tanggal_selesai[]" class="w-full px-4 py-2 border rounded-lg shadow-sm" required>
            </div>
            <hr class="my-2">
        `;
        container.appendChild(detail);
    }
</script>
@endsection
