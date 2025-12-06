@extends('layouts.app')

@section('content')
    <link href="https://cdn.jsdelivr.net/npm/sweetalert2@11/dist/sweetalert2.min.css" rel="stylesheet">

    <div class="max-w-4xl mx-auto px-4 py-10">

        <div class="bg-white p-6 rounded-2xl shadow-md space-y-6">

            <div class="text-center">
                <h1 class="text-2xl font-bold text-gray-800">Buat Jadwal Program Kerja</h1>
                <p class="text-gray-500 text-sm">Isi jadwal berdasarkan program kerja yang telah disetujui</p>
            </div>

            {{-- ALERT SUCCESS --}}
            @if (session('success'))
                <script src="https://cdn.jsdelivr.net/npm/sweetalert2@11"></script>
                <script>
                    Swal.fire({
                        icon: 'success',
                        title: 'Berhasil',
                        text: @json(session('success')),
                        didClose: () => {
                            window.location.href = "{{ route('jadwal-proker.index') }}"
                        }
                    });
                </script>
            @endif

            {{-- ALERT ERROR --}}
            @if ($errors->any())
                <script>
                    Swal.fire({
                        icon: 'error',
                        title: 'Terjadi Kesalahan',
                        html: `{!! implode('<br>', $errors->all()) !!}`
                    })
                </script>
            @endif

            <form action="{{ route('jadwal-proker.store') }}" method="POST" id="form-jadwal">
                @csrf

                {{-- PILIH PROKER --}}
                <div>
                    <label class="block mb-1 font-medium">Program Kerja</label>
                    <select name="proker_id" class="w-full border px-4 py-2 rounded" required>
                        <option value="">-- Pilih Proker --</option>
                        @foreach ($prokers as $proker)
                            <option value="{{ $proker->id }}">{{ $proker->judul }}</option>
                        @endforeach
                    </select>
                </div>

                {{-- ESTIMASI --}}
                <div class="grid grid-cols-1 md:grid-cols-2 gap-4 mt-4">
                    <div>
                        <label class="block mb-1 font-medium">Estimasi Mulai</label>
                        <input type="date" name="estimasi_mulai" class="w-full border px-4 py-2 rounded" required>
                    </div>

                    <div>
                        <label class="block mb-1 font-medium">Estimasi Selesai</label>
                        <input type="date" name="estimasi_selesai" class="w-full border px-4 py-2 rounded" required>
                    </div>
                </div>

                {{-- STATUS --}}
                <div class="mt-4">
                    <label class="block mb-1 font-medium">Status</label>
                    <select name="status" class="w-full border px-4 py-2 rounded">
                        <option value="penjadwalan">Penjadwalan</option>
                        <option value="berjalan">Berjalan</option>
                        <option value="selesai">Selesai</option>
                    </select>
                </div>

                <hr class="my-6">

                {{-- DETAIL --}}
                <h3 class="font-semibold text-lg mb-2">Detail Kegiatan</h3>

                <div id="detail-container">
                    <div class="detail-item bg-gray-100 p-4 mb-4 rounded">
                        <input type="text" name="kegiatan[]" class="w-full border px-4 py-2 rounded mb-2"
                            placeholder="Nama kegiatan" required>

                        <div class="grid grid-cols-2 gap-4 mb-2">
                            <input type="date" name="tanggal_mulai[]" class="w-full border px-4 py-2 rounded" required>
                            <input type="date" name="tanggal_selesai[]" class="w-full border px-4 py-2 rounded" required>
                        </div>

                        <textarea name="catatan[]" class="w-full border px-4 py-2 rounded" placeholder="Catatan (opsional)"></textarea>
                    </div>
                </div>

                <button type="button" onclick="addDetail()" class="px-4 py-2 bg-blue-600 text-white rounded">
                    + Tambah Kegiatan
                </button>

                <div class="mt-6 flex justify-center">
                    <button type="submit" class="px-6 py-2 bg-green-600 text-white rounded shadow">
                        Simpan Jadwal
                    </button>
                </div>
            </form>

        </div>
    </div>

    <template id="detail-template">
        <div class="detail-item bg-gray-100 p-4 mb-4 rounded relative">
            <button type="button" onclick="this.parentElement.remove()" class="absolute top-2 right-2 text-red-600">
                ×
            </button>

            <input type="text" name="kegiatan[]" class="w-full border px-4 py-2 rounded mb-2" placeholder="Nama kegiatan"
                required>

            <div class="grid grid-cols-2 gap-4 mb-2">
                <input type="date" name="tanggal_mulai[]" class="w-full border px-4 py-2 rounded" required>
                <input type="date" name="tanggal_selesai[]" class="w-full border px-4 py-2 rounded" required>
            </div>

            <textarea name="catatan[]" class="w-full border px-4 py-2 rounded" placeholder="Catatan (opsional)"></textarea>
        </div>
    </template>

    <script src="https://cdn.jsdelivr.net/npm/sweetalert2@11"></script>

    <script>
        function addDetail() {
            const template = document.getElementById('detail-template').content.cloneNode(true)
            document.getElementById('detail-container').appendChild(template)
        }

        // VALIDASI
        document.getElementById('form-jadwal').addEventListener('submit', function(e) {

            const mulai = document.querySelector("input[name='estimasi_mulai']").value
            const selesai = document.querySelector("input[name='estimasi_selesai']").value

            if (mulai > selesai) {
                e.preventDefault()
                Swal.fire("Error", "Estimasi tidak valid", "error")
                return
            }

            const mulaiDetail = document.querySelectorAll("input[name='tanggal_mulai[]']")
            const selesaiDetail = document.querySelectorAll("input[name='tanggal_selesai[]']")

            for (let i = 0; i < mulaiDetail.length; i++) {
                if (mulaiDetail[i].value > selesaiDetail[i].value) {
                    e.preventDefault()
                    Swal.fire("Error", "Tanggal detail tidak valid", "error")
                    return
                }

                if (mulaiDetail[i].value < mulai || selesaiDetail[i].value > selesai) {
                    e.preventDefault()
                    Swal.fire("Error", "Tanggal detail harus di dalam estimasi", "error")
                    return
                }
            }
        })
    </script>
@endsection
