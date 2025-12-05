@extends('layouts.app')

@section('content')
    <div class="max-w-4xl mx-auto bg-white rounded-xl shadow-md p-6">
        <h2 class="text-2xl font-bold mb-6 text-gray-800">Edit Jadwal Program Kerja</h2>

        <form action="{{ route('jadwal-proker.update', $jadwal->id) }}" method="POST">
            @csrf
            @method('PUT')

            {{-- === Pilih Proker === --}}
            <div class="mb-4">
                <label for="proker_id" class="block text-sm font-medium text-gray-700 mb-1">Program Kerja</label>
                <select name="proker_id" id="proker_id"
                    class="w-full border px-4 py-2 rounded @error('proker_id') border-red-500 @enderror">
                    @foreach ($prokers as $proker)
                        <option value="{{ $proker->id }}" {{ $proker->id == $jadwal->proker_id ? 'selected' : '' }}>
                            {{ $proker->judul }}
                        </option>
                    @endforeach
                </select>
            </div>

            {{-- === Status Jadwal === --}}
            <div class="mb-4">
                <label class="block mb-1 font-medium text-gray-700">Status</label>
                <select name="status" class="w-full border px-4 py-2 rounded">
                    @foreach (['penjadwalan', 'berjalan', 'selesai'] as $status)
                        <option value="{{ $status }}" {{ $jadwal->status == $status ? 'selected' : '' }}>
                            {{ ucfirst($status) }}
                        </option>
                    @endforeach
                </select>
            </div>

            {{-- ✅ ESTIMASI --}}
            <div class="grid grid-cols-1 md:grid-cols-2 gap-4 mb-6">
                <div>
                    <label class="block mb-1 font-medium text-gray-700">Estimasi Mulai</label>
                    <input type="date" name="estimasi_mulai"
                        value="{{ old('estimasi_mulai', $jadwal->estimasi_mulai) }}"
                        class="w-full border px-4 py-2 rounded">
                </div>

                <div>
                    <label class="block mb-1 font-medium text-gray-700">Estimasi Selesai</label>
                    <input type="date" name="estimasi_selesai"
                        value="{{ old('estimasi_selesai', $jadwal->estimasi_selesai) }}"
                        class="w-full border px-4 py-2 rounded">
                </div>
            </div>

            <hr class="my-4">

            {{-- === Detail Kegiatan === --}}
            <div id="kegiatan-container">
                @foreach ($jadwal->details as $i => $detail)
                    <div class="bg-gray-100 p-4 mb-4 rounded-md kegiatan-item">

                        {{-- id detail (hidden) --}}
                        <input type="hidden" name="detail_id[]" value="{{ $detail->id }}">

                        <label class="block font-semibold">Kegiatan</label>
                        <input type="text" name="kegiatan[]" value="{{ $detail->kegiatan }}"
                            class="w-full border px-4 py-2 rounded mb-2" required>

                        <label class="block font-semibold">Tanggal Mulai</label>
                        <input type="date" name="tanggal_mulai[]" value="{{ $detail->tanggal_mulai }}"
                            class="w-full border px-4 py-2 rounded mb-2" required>

                        <label class="block font-semibold">Tanggal Selesai</label>
                        <input type="date" name="tanggal_selesai[]" value="{{ $detail->tanggal_selesai }}"
                            class="w-full border px-4 py-2 rounded mb-2" required>

                        <label class="block font-semibold">Catatan</label>
                        <textarea name="catatan[]" rows="2"
                            class="w-full border px-4 py-2 rounded">{{ $detail->catatan }}</textarea>

                        <button type="button" onclick="this.closest('.kegiatan-item').remove();"
                            class="mt-2 text-sm text-red-500 hover:underline">Hapus Kegiatan</button>
                    </div>
                @endforeach
            </div>

            <div class="flex justify-between items-center mt-6">
                <button type="button" onclick="tambahKegiatan()"
                    class="px-4 py-2 bg-green-600 hover:bg-green-700 text-white rounded shadow">
                    + Tambah Kegiatan
                </button>

                <button type="submit" class="px-6 py-2 bg-blue-600 hover:bg-blue-700 text-white rounded shadow">
                    Simpan Perubahan
                </button>
            </div>
        </form>
    </div>

    <template id="kegiatan-template">
        <div class="bg-gray-100 p-4 mb-4 rounded-md kegiatan-item">
            <input type="hidden" name="detail_id[]" value="">

            <label class="block font-semibold">Kegiatan</label>
            <input type="text" name="kegiatan[]" class="w-full border px-4 py-2 rounded mb-2" required>

            <label class="block font-semibold">Tanggal Mulai</label>
            <input type="date" name="tanggal_mulai[]" class="w-full border px-4 py-2 rounded mb-2" required>

            <label class="block font-semibold">Tanggal Selesai</label>
            <input type="date" name="tanggal_selesai[]" class="w-full border px-4 py-2 rounded mb-2" required>

            <label class="block font-semibold">Catatan</label>
            <textarea name="catatan[]" rows="2" class="w-full border px-4 py-2 rounded"></textarea>

            <button type="button" onclick="this.closest('.kegiatan-item').remove();"
                class="mt-2 text-sm text-red-500 hover:underline">Hapus Kegiatan</button>
        </div>
    </template>

    <script>
        function tambahKegiatan() {
            const template = document.getElementById('kegiatan-template').content.cloneNode(true);
            document.getElementById('kegiatan-container').appendChild(template);
        }
    </script>
@endsection
