@extends('layouts.app')

@section('content')
    <link href="https://cdn.jsdelivr.net/npm/sweetalert2@11/dist/sweetalert2.min.css" rel="stylesheet" />

    <div class="max-w-3xl mx-auto px-4 sm:px-6 lg:px-8 py-10">
        <div class="bg-white p-6 rounded-2xl shadow-md space-y-6">

            <div class="text-center">
                <h1 class="text-2xl font-bold text-gray-800">Edit Program Kerja MWC</h1>
                <p class="text-gray-500 text-sm">Perbarui data program kerja Anda</p>
            </div>

            <script src="https://cdn.jsdelivr.net/npm/sweetalert2@11"></script>

            @if (session('success'))
                <script>
                    Swal.fire({
                        icon: 'success',
                        title: 'Berhasil!',
                        text: @json(session('success')),
                        didClose: () => window.location.href = "/proker-mwc"
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


            <form action="{{ route('proker-mwc.update', $proker->id) }}" method="POST" enctype="multipart/form-data"
                class="space-y-5">
                @csrf
                @method('PUT')

                {{-- Judul --}}
                <div>
                    <label class="block text-sm font-semibold text-gray-700 mb-1">
                        Judul Program Kerja
                    </label>
                    <input type="text" name="judul" value="{{ $proker->judul }}"
                        class="w-full px-4 py-2 border rounded-lg shadow-sm focus:ring-green-500 focus:border-green-500">
                </div>

                {{-- Dropdown dinamis --}}
                @foreach ([['bidang_id', 'Bidang', $bidangs, 'new_bidang'], ['jenis_id', 'Jenis Kegiatan', $jenisKegiatans, 'new_jenis'], ['tujuan_id', 'Tujuan', $tujuans, 'new_tujuan'], ['sasaran_id', 'Sasaran', $sasarans, 'new_sasaran']] as [$field, $label, $list, $newField])
                    <div>
                        <label class="block text-sm font-medium text-gray-700 mb-1">
                            {{ $label }}
                        </label>

                        <select name="{{ $field }}" id="{{ $field }}"
                            class="w-full px-4 py-2 border rounded-lg shadow-sm focus:ring-green-500 focus:border-green-500">

                            @foreach ($list as $item)
                                <option value="{{ $item->id }}" {{ $proker->$field == $item->id ? 'selected' : '' }}>
                                    {{ $item->nama }}
                                </option>
                            @endforeach

                            <option value="add_new">+ Tambah {{ $label }} Baru</option>
                        </select>

                        <input type="text" name="{{ $newField }}" id="{{ $newField }}"
                            placeholder="Masukkan {{ $label }} Baru"
                            class="w-full px-4 py-2 border rounded-lg shadow-sm mt-2 hidden focus:ring-green-500 focus:border-green-500">
                    </div>
                @endforeach

                {{-- Proposal --}}
                <div>
                    <label class="block text-sm font-medium text-gray-700 mb-1">
                        Proposal Saat Ini
                    </label>
                    <a href="{{ asset('storage/' . $proker->proposal) }}" target="_blank" class="text-blue-600 underline">
                        Lihat Proposal PDF
                    </a>

                    <label class="block text-sm font-medium text-gray-700 mt-3 mb-1">
                        Upload Proposal Baru (opsional)
                    </label>
                    <input type="file" name="proposal" accept="application/pdf"
                        class="w-full px-4 py-2 border rounded-lg shadow-sm">
                </div>

                {{-- Deskripsi --}}
                <div>
                    <label class="block text-sm font-medium text-gray-700 mb-1">
                        Deskripsi Tambahan
                    </label>
                    <textarea name="deskripsi" rows="4"
                        class="w-full px-4 py-2 border rounded-lg shadow-sm focus:ring-green-500 focus:border-green-500">{{ $proker->deskripsi }}</textarea>
                </div>

                {{-- Status otomatis --}}
                <input type="hidden" name="status" value="disetujui">

                <div class="flex justify-center">
                    <button type="submit"
                        class="inline-flex items-center gap-2 px-6 py-2 bg-green-600 text-white rounded-lg shadow hover:bg-green-700">
                        <i class="fas fa-save text-sm"></i>
                        Update Proker
                    </button>
                </div>
            </form>
        </div>
    </div>

    <script>
        const controls = [{
                selectId: 'bidang_id',
                inputId: 'new_bidang'
            },
            {
                selectId: 'jenis_id',
                inputId: 'new_jenis'
            },
            {
                selectId: 'tujuan_id',
                inputId: 'new_tujuan'
            },
            {
                selectId: 'sasaran_id',
                inputId: 'new_sasaran'
            },
        ];

        controls.forEach(({
            selectId,
            inputId
        }) => {
            document.getElementById(selectId).addEventListener('change', function() {
                document.getElementById(inputId).classList.toggle('hidden', this.value !== 'add_new');
            });
        });
    </script>
@endsection
