@extends('layouts.app')

@section('content')
    <link href="https://cdn.jsdelivr.net/npm/sweetalert2@11/dist/sweetalert2.min.css" rel="stylesheet" />

    <div class="max-w-3xl mx-auto px-4 sm:px-6 lg:px-8 py-10">
        <div class="bg-white p-6 rounded-2xl shadow-md space-y-6">

            <div class="text-center">
                <h1 class="text-2xl font-bold text-gray-800">Ajukan Program Kerja</h1>
                <p class="text-gray-500 text-sm">
                    Silahkan isi form berikut dengan benar
                </p>
            </div>

            <script src="https://cdn.jsdelivr.net/npm/sweetalert2@11"></script>

            @if (session('success'))
                <script>
                    Swal.fire({
                        icon: 'success',
                        title: 'Berhasil!',
                        text: @json(session('success')),
                        didClose: () => window.location.href = "/proker"
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

            <form action="{{ route('proker.store') }}" method="POST" enctype="multipart/form-data" class="space-y-5">
                @csrf

                <div>
                    <label class="block text-sm font-semibold text-gray-700 mb-1">
                        Judul Program Kerja
                    </label>
                    <input type="text" name="judul" value="{{ old('judul') }}"
                        class="w-full px-4 py-2 border rounded-lg shadow-sm
                              focus:ring-green-500 focus:border-green-500
                              @error('judul') border-red-500 @enderror">

                    @error('judul')
                        <p class="text-red-500 text-sm mt-1">{{ $message }}</p>
                    @enderror
                </div>


                @foreach ([['bidang_id', 'Bidang', $bidangs, 'new_bidang'], ['jenis_id', 'Jenis Kegiatan', $jenisKegiatans, 'new_jenis'], ['tujuan_id', 'Tujuan', $tujuans, 'new_tujuan'], ['sasaran_id', 'Sasaran', $sasarans, 'new_sasaran']] as [$field, $label, $list, $newField])
                    <div>
                        <label class="block text-sm font-medium text-gray-700 mb-1">
                            {{ $label }}
                        </label>

                        <select name="{{ $field }}" id="{{ $field }}"
                            class="w-full px-4 py-2 border rounded-lg shadow-sm
                               focus:ring-green-500 focus:border-green-500
                               @error($field) border-red-500 @enderror">

                            <option value="">-- Pilih {{ $label }} --</option>

                            @foreach ($list as $item)
                                <option value="{{ $item->id }}" {{ old($field) == $item->id ? 'selected' : '' }}>
                                    {{ $item->nama }}
                                </option>
                            @endforeach

                            <option value="add_new" {{ old($field) == 'add_new' ? 'selected' : '' }}>
                                + Tambah {{ $label }} Baru
                            </option>
                        </select>

                        <input type="text" name="{{ $newField }}" id="{{ $newField }}"
                            placeholder="Masukkan {{ $label }} Baru" value="{{ old($newField) }}"
                            class="w-full px-4 py-2 border rounded-lg shadow-sm mt-2
                              focus:ring-green-500 focus:border-green-500
                              {{ old($field) == 'add_new' ? '' : 'hidden' }}">

                        @error($field)
                            <p class="text-red-500 text-sm mt-1">{{ $message }}</p>
                        @enderror
                    </div>
                @endforeach


                <div>
                    <label class="block text-sm font-medium text-gray-700 mb-1">
                        Upload Proposal (PDF)
                    </label>
                    <input type="file" name="proposal" accept="application/pdf"
                        class="w-full px-4 py-2 border rounded-lg shadow-sm
                              @error('proposal') border-red-500 @enderror">

                    @error('proposal')
                        <p class="text-red-500 text-sm mt-1">{{ $message }}</p>
                    @enderror
                </div>


                <div>
                    <label class="block text-sm font-medium text-gray-700 mb-1">
                        Keterangan Tambahan
                    </label>
                    <textarea name="keterangan" rows="4"
                        class="w-full px-4 py-2 border rounded-lg shadow-sm resize-y
                                 focus:ring-green-500 focus:border-green-500
                                 @error('keterangan') border-red-500 @enderror">{{ old('keterangan') }}</textarea>

                    @error('keterangan')
                        <p class="text-red-500 text-sm mt-1">{{ $message }}</p>
                    @enderror
                </div>


                <div class="flex justify-center">
                    <button type="submit"
                        class="inline-flex items-center gap-2 px-6 py-2
                           bg-gradient-to-r from-green-500 to-green-600
                           text-white text-sm font-semibold rounded-lg shadow
                           hover:from-green-600 hover:to-green-700
                           focus:outline-none focus:ring-2 focus:ring-green-300">
                        <i class="fas fa-paper-plane text-sm"></i>
                        Ajukan
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
            const selectEl = document.getElementById(selectId);
            const inputEl = document.getElementById(inputId);

            selectEl.addEventListener('change', () => {
                inputEl.classList.toggle('hidden', selectEl.value !== 'add_new');
            });
        });
    </script>
@endsection
