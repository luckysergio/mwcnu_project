@extends('layouts.app')

@section('content')
    <link href="https://cdn.jsdelivr.net/npm/sweetalert2@11/dist/sweetalert2.min.css" rel="stylesheet" />

    <div class="max-w-3xl mx-auto px-4 sm:px-6 lg:px-8 py-10">
        <div class="bg-white p-6 rounded-2xl shadow-md space-y-6">

            <div class="text-center">
                <h1 class="text-2xl font-bold text-gray-800">Edit Program Kerja</h1>
                <p class="text-gray-500 text-sm">Ubah data program kerja yang sudah diajukan</p>
            </div>

            {{-- SweetAlert --}}
            <script src="https://cdn.jsdelivr.net/npm/sweetalert2@11"></script>

            @if (session('success'))
                <script>
                    Swal.fire({
                        icon: 'success',
                        title: 'Berhasil!',
                        text: @json(session('success')),
                        didClose: () => {
                            window.location.href = "/proker";
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

            <form action="{{ route('proker.update', $proker->id) }}" method="POST" enctype="multipart/form-data"
                class="space-y-5">
                @csrf
                @method('PUT')

                {{-- JUDUL --}}
                <div>
                    <label class="block text-sm font-medium text-gray-700 mb-1">Judul Program Kerja</label>
                    <input type="text" name="judul" value="{{ old('judul', $proker->judul) }}"
                        class="w-full px-4 py-2 border rounded-lg shadow-sm focus:ring-green-500 focus:border-green-500 @error('judul') border-red-500 @enderror">
                    @error('judul')
                        <p class="text-red-500 text-sm mt-1">{{ $message }}</p>
                    @enderror
                </div>

                {{-- STATUS --}}
                <div>
                    <label class="block text-sm font-medium text-gray-700 mb-1">Status Program Kerja</label>
                    <select name="status"
                        class="w-full px-4 py-2 border rounded-lg shadow-sm focus:ring-green-500 focus:border-green-500 @error('status') border-red-500 @enderror">
                        <option value="">-- Pilih Status --</option>
                        @foreach (['pengajuan' => 'Pengajuan', 'disetujui' => 'Disetujui', 'ditolak' => 'Ditolak'] as $key => $label)
                            <option value="{{ $key }}"
                                {{ old('status', $proker->status) == $key ? 'selected' : '' }}>
                                {{ $label }}
                            </option>
                        @endforeach
                    </select>
                    @error('status')
                        <p class="text-red-500 text-sm mt-1">{{ $message }}</p>
                    @enderror
                </div>

                {{-- LOOP DROPDOWN DINAMIS --}}
                @foreach ([['bidang_id', 'Bidang', $bidangs, 'new_bidang'], ['jenis_id', 'Jenis Kegiatan', $jenisKegiatans, 'new_jenis'], ['tujuan_id', 'Tujuan', $tujuans, 'new_tujuan'], ['sasaran_id', 'Sasaran', $sasarans, 'new_sasaran']] as [$field, $label, $list, $newField])
                    <div>
                        <label class="block text-sm font-medium text-gray-700 mb-1">{{ $label }}</label>

                        <select name="{{ $field }}" id="{{ $field }}"
                            class="w-full px-4 py-2 border rounded-lg shadow-sm focus:ring-green-500 focus:border-green-500 @error($field) border-red-500 @enderror">
                            <option value="">-- Pilih {{ $label }} --</option>

                            @foreach ($list as $item)
                                <option value="{{ $item->id }}"
                                    {{ old($field, $proker->$field) == $item->id ? 'selected' : '' }}>
                                    {{ $item->nama }}
                                </option>
                            @endforeach

                            {{-- Tambah opsi baru --}}
                            <option value="add_new" {{ old($field) === 'add_new' ? 'selected' : '' }}>
                                + Tambah {{ $label }} Baru
                            </option>
                        </select>

                        {{-- Input tambah baru --}}
                        <input type="text" name="{{ $newField }}" id="{{ $newField }}"
                            placeholder="Masukkan {{ $label }} Baru" value="{{ old($newField) }}"
                            class="w-full px-4 py-2 border rounded-lg shadow-sm mt-2
                focus:ring-green-500 focus:border-green-500
                {{ old($field) === 'add_new' ? '' : 'hidden' }}">

                        @error($field)
                            <p class="text-red-500 text-sm mt-1">{{ $message }}</p>
                        @enderror
                    </div>
                @endforeach

                {{-- PROPOSAL --}}
                <div>
                    <label class="block text-sm font-medium text-gray-700 mb-1">Proposal (PDF)</label>
                    <input type="file" name="proposal" accept="application/pdf"
                        class="w-full px-4 py-2 border rounded-lg shadow-sm bg-white @error('proposal') border-red-500 @enderror">

                    @if ($proker->proposal)
                        <p class="text-sm text-gray-500 mt-1">
                            File saat ini:
                            <a href="{{ asset('storage/' . $proker->proposal) }}" target="_blank"
                                class="text-blue-600 underline">
                                Lihat Proposal
                            </a>
                        </p>
                    @endif

                    @error('proposal')
                        <p class="text-red-500 text-sm mt-1">{{ $message }}</p>
                    @enderror
                </div>

                {{-- KETERANGAN --}}
                <div>
                    <label class="block text-sm font-medium text-gray-700 mb-1">Keterangan Tambahan</label>
                    <textarea name="keterangan" rows="4"
                        class="w-full px-4 py-2 border rounded-lg shadow-sm resize-y focus:ring-green-500 focus:border-green-500 @error('keterangan') border-red-500 @enderror">{{ old('keterangan', $proker->keterangan) }}</textarea>
                    @error('keterangan')
                        <p class="text-red-500 text-sm mt-1">{{ $message }}</p>
                    @enderror
                </div>

                {{-- SUBMIT --}}
                <div class="flex justify-center">
                    <button type="submit"
                        class="inline-flex items-center gap-2 px-6 py-2 bg-blue-600 hover:bg-blue-700 text-white text-sm font-semibold rounded-lg shadow focus:outline-none focus:ring-2 focus:ring-blue-300">
                        <i class="fas fa-save text-sm"></i>
                        Simpan Perubahan
                    </button>
                </div>
            </form>
        </div>
        ```

    </div>

    <script>
        const selects = [{
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

        selects.forEach(({
            selectId,
            inputId
        }) => {
            const select = document.getElementById(selectId);
            const inputField = document.getElementById(inputId);

            select.addEventListener('change', () => {
                inputField.classList.toggle('hidden', select.value !== 'add_new');
            });
        });
    </script>
@endsection
