@extends('layouts.app')

@section('content')
    <link href="https://cdn.jsdelivr.net/npm/sweetalert2@11/dist/sweetalert2.min.css" rel="stylesheet">

    <div class="max-w-4xl mx-auto bg-white p-6 rounded-xl shadow-md">

        <h2 class="text-2xl font-bold text-center mb-6">Edit Jadwal Program Kerja</h2>

        <form action="{{ route('jadwal-proker.update', $jadwal->id) }}" method="POST" id="form-edit">
            @csrf
            @method('PUT')

            {{-- PROKER --}}
            <div class="mb-4">
                <label class="block font-medium mb-1">Program Kerja</label>
                <select name="proker_id" class="w-full border px-4 py-2 rounded">
                    @foreach ($prokers as $proker)
                        <option value="{{ $proker->id }}" @selected($jadwal->proker_id == $proker->id)>
                            {{ $proker->judul }}
                        </option>
                    @endforeach
                </select>
            </div>

            {{-- ESTIMASI --}}
            <div class="grid grid-cols-2 gap-4 mb-4">
                <div>
                    <label class="font-medium mb-1 block">Estimasi Mulai</label>
                    <input type="date" name="estimasi_mulai" value="{{ $jadwal->estimasi_mulai }}"
                        class="w-full border px-4 py-2 rounded" required>
                </div>

                <div>
                    <label class="font-medium mb-1 block">Estimasi Selesai</label>
                    <input type="date" name="estimasi_selesai" value="{{ $jadwal->estimasi_selesai }}"
                        class="w-full border px-4 py-2 rounded" required>
                </div>
            </div>

            {{-- STATUS --}}
            <div class="mb-4">
                <label class="block font-medium mb-1">Status</label>
                <select name="status" class="w-full border px-4 py-2 rounded">
                    @foreach (['penjadwalan', 'berjalan', 'selesai'] as $st)
                        <option value="{{ $st }}" @selected($jadwal->status == $st)>
                            {{ ucfirst($st) }}
                        </option>
                    @endforeach
                </select>
            </div>

            <hr class="my-6">

            {{-- DETAIL --}}
            <h3 class="font-semibold text-lg mb-2">Detail Kegiatan</h3>

            <div id="detail-container">
                @foreach ($jadwal->details as $detail)
                    <div class="detail-item bg-gray-100 p-4 mb-4 rounded relative">
                        <input type="hidden" name="detail_id[]" value="{{ $detail->id }}">

                        <button type="button" onclick="this.parentElement.remove()"
                            class="absolute top-2 right-2 text-red-600">
                            ×
                        </button>

                        <input type="text" name="kegiatan[]" value="{{ $detail->kegiatan }}"
                            class="w-full border px-4 py-2 rounded mb-2" required>

                        <div class="grid grid-cols-2 gap-4 mb-2">
                            <input type="date" name="tanggal_mulai[]" value="{{ $detail->tanggal_mulai }}"
                                class="w-full border px-4 py-2 rounded" required>
                            <input type="date" name="tanggal_selesai[]" value="{{ $detail->tanggal_selesai }}"
                                class="w-full border px-4 py-2 rounded" required>
                        </div>

                        <textarea name="catatan[]" class="w-full border px-4 py-2 rounded">{{ $detail->catatan }}</textarea>
                    </div>
                @endforeach
            </div>

            <button type="button" onclick="addDetail()" class="bg-green-600 text-white px-4 py-2 rounded">
                + Tambah Kegiatan
            </button>

            <div class="mt-6 text-center">
                <button type="submit" class="bg-blue-600 text-white px-6 py-2 rounded shadow">
                    Simpan Perubahan
                </button>
            </div>
        </form>
    </div>

    <template id="detail-template">
        <div class="detail-item bg-gray-100 p-4 mb-4 rounded relative">
            <input type="hidden" name="detail_id[]" value="">

            <button type="button" onclick="this.parentElement.remove()" class="absolute top-2 right-2 text-red-600">
                ×
            </button>

            <input type="text" name="kegiatan[]" class="w-full border px-4 py-2 rounded mb-2" required>

            <div class="grid grid-cols-2 gap-4 mb-2">
                <input type="date" name="tanggal_mulai[]" class="w-full border px-4 py-2 rounded" required>
                <input type="date" name="tanggal_selesai[]" class="w-full border px-4 py-2 rounded" required>
            </div>

            <textarea name="catatan[]" class="w-full border px-4 py-2 rounded"></textarea>
        </div>
    </template>

    <script src="https://cdn.jsdelivr.net/npm/sweetalert2@11"></script>

    <script>
        function addDetail() {
            const template = document.getElementById('detail-template').content.cloneNode(true)
            document.getElementById('detail-container').appendChild(template)
        }

        document.getElementById('form-edit').addEventListener('submit', function(e) {

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
