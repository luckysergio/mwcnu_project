@extends('layouts.app')

@section('content')
    <link href="https://cdn.jsdelivr.net/npm/sweetalert2@11/dist/sweetalert2.min.css" rel="stylesheet" />

    <div class="max-w-3xl mx-auto px-4 sm:px-6 lg:px-8 py-10">
        <div class="bg-white p-6 rounded-2xl shadow-md space-y-6">

            {{-- Header --}}
            <div class="text-center">
                <h1 class="text-2xl font-bold text-gray-800">Ajukan Program Kerja</h1>
                <p class="text-gray-500 text-sm">Silakan isi form berikut dengan benar</p>
            </div>

            {{-- SweetAlert feedback --}}
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

            <form action="{{ route('proker.store') }}" method="POST" enctype="multipart/form-data" class="space-y-5">
                @csrf

                {{-- Judul --}}
                <div>
                    <label for="judul" class="block text-sm font-medium text-gray-700 mb-1">Judul Program Kerja</label>
                    <input type="text" name="judul" id="judul" value="{{ old('judul') }}"
                        class="w-full px-4 py-2 border rounded-lg shadow-sm focus:ring-green-500 focus:border-green-500 @error('judul') border-red-500 @enderror">
                    @error('judul')
                        <p class="text-red-500 text-sm mt-1">{{ $message }}</p>
                    @enderror
                </div>

                {{-- Select Fields --}}
                @foreach ([['bidang_id', 'Bidang', $bidangs], ['jenis_id', 'Jenis Kegiatan', $jenisKegiatans], ['tujuan_id', 'Tujuan', $tujuans], ['sasaran_id', 'Sasaran', $sasarans]] as [$field, $label, $options])
                    <div>
                        <label for="{{ $field }}"
                            class="block text-sm font-medium text-gray-700 mb-1">{{ $label }}</label>
                        <select name="{{ $field }}" id="{{ $field }}"
                            class="w-full px-4 py-2 border rounded-lg shadow-sm focus:ring-green-500 focus:border-green-500 @error($field) border-red-500 @enderror">
                            <option value="">-- Pilih {{ $label }} --</option>
                            @foreach ($options as $opt)
                                <option value="{{ $opt->id }}" {{ old($field) == $opt->id ? 'selected' : '' }}>
                                    {{ $opt->nama }}
                                </option>
                            @endforeach
                        </select>
                        @error($field)
                            <p class="text-red-500 text-sm mt-1">{{ $message }}</p>
                        @enderror
                    </div>
                @endforeach

                {{-- Proposal --}}
                <div>
                    <label for="proposal" class="block text-sm font-medium text-gray-700 mb-1">Upload Proposal (PDF)</label>
                    <input type="file" name="proposal" id="proposal"
                        class="w-full px-4 py-2 border rounded-lg shadow-sm bg-white @error('proposal') border-red-500 @enderror"
                        accept=".pdf">
                    @error('proposal')
                        <p class="text-red-500 text-sm mt-1">{{ $message }}</p>
                    @enderror
                </div>

                {{-- Keterangan --}}
                <div>
                    <label for="keterangan" class="block text-sm font-medium text-gray-700 mb-1">Keterangan Tambahan</label>
                    <textarea name="keterangan" id="keterangan" rows="4"
                        class="w-full px-4 py-2 border rounded-lg shadow-sm resize-y focus:ring-green-500 focus:border-green-500 @error('keterangan') border-red-500 @enderror">{{ old('keterangan') }}</textarea>
                    @error('keterangan')
                        <p class="text-red-500 text-sm mt-1">{{ $message }}</p>
                    @enderror
                </div>

                {{-- Submit --}}
                <div class="flex justify-center">
                    <button type="submit"
                        class="inline-flex items-center gap-2 px-6 py-2 bg-gradient-to-r from-green-500 to-green-600 text-white text-sm font-semibold rounded-lg shadow hover:from-green-600 hover:to-green-700 focus:outline-none focus:ring-2 focus:ring-green-300">
                        <i class="fas fa-paper-plane text-sm"></i> Ajukan
                    </button>
                </div>
            </form>
        </div>
    </div>
@endsection
