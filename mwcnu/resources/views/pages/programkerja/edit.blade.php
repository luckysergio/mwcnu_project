@extends('layouts.app')

@section('content')
    <link href="https://cdn.jsdelivr.net/npm/sweetalert2@11.0.15/dist/sweetalert2.min.css" rel="stylesheet" />

    <div class="max-w-3xl mx-auto px-4 sm:px-6 lg:px-8 py-10">
        <div class="bg-white p-6 rounded-2xl shadow-md space-y-6">

            {{-- Header --}}
            <div class="text-center">
                <h1 class="text-2xl font-bold text-gray-800">Edit Program Kerja</h1>
                <p class="text-gray-500 text-sm">Ubah data program kerja yang sudah diajukan</p>
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

            <form action="{{ route('proker.update', $proker->id) }}" method="POST" enctype="multipart/form-data"
                class="space-y-5">
                @csrf
                @method('PUT')

                <div>
                    <label for="judul" class="block text-sm font-medium text-gray-700 mb-1">Judul Program Kerja</label>
                    <input type="text" name="judul" id="judul" value="{{ old('judul', $proker->judul) }}"
                        class="w-full px-4 py-2 border rounded-lg shadow-sm focus:ring-green-500 focus:border-green-500 @error('judul') border-red-500 @enderror">
                    @error('judul')
                        <p class="text-red-500 text-sm mt-1">{{ $message }}</p>
                    @enderror
                </div>

                <div>
                    <label for="status" class="block text-sm font-medium text-gray-700 mb-1">Status Program Kerja</label>
                    <select name="status" id="status"
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

                @foreach ([['bidang_id', 'Bidang', $bidangs], ['jenis_id', 'Jenis Kegiatan', $jenisKegiatans], ['tujuan_id', 'Tujuan', $tujuans], ['sasaran_id', 'Sasaran', $sasarans]] as [$field, $label, $options])
                    <div>
                        <label for="{{ $field }}"
                            class="block text-sm font-medium text-gray-700 mb-1">{{ $label }}</label>
                        <select name="{{ $field }}" id="{{ $field }}"
                            class="w-full px-4 py-2 border rounded-lg shadow-sm focus:ring-green-500 focus:border-green-500 @error($field) border-red-500 @enderror">
                            <option value="">-- Pilih {{ $label }} --</option>
                            @foreach ($options as $opt)
                                <option value="{{ $opt->id }}"
                                    {{ old($field, $proker->$field) == $opt->id ? 'selected' : '' }}>
                                    {{ $opt->nama }}
                                </option>
                            @endforeach
                        </select>
                        @error($field)
                            <p class="text-red-500 text-sm mt-1">{{ $message }}</p>
                        @enderror
                    </div>
                @endforeach

                <div>
                    <label for="proposal" class="block text-sm font-medium text-gray-700 mb-1">Proposal (PDF)</label>
                    <input type="file" name="proposal" id="proposal"
                        class="w-full px-4 py-2 border rounded-lg shadow-sm bg-white @error('proposal') border-red-500 @enderror"
                        accept=".pdf">
                    @if ($proker->proposal)
                        <p class="text-sm text-gray-500 mt-1">File saat ini:
                            <a href="{{ asset('storage/' . $proker->proposal) }}" class="text-blue-600 underline"
                                target="_blank">Lihat Proposal</a>
                        </p>
                    @endif
                    @error('proposal')
                        <p class="text-red-500 text-sm mt-1">{{ $message }}</p>
                    @enderror
                </div>

                <div>
                    <label for="keterangan" class="block text-sm font-medium text-gray-700 mb-1">Keterangan Tambahan</label>
                    <textarea name="keterangan" id="keterangan" rows="4"
                        class="w-full px-4 py-2 border rounded-lg shadow-sm resize-y focus:ring-green-500 focus:border-green-500 @error('keterangan') border-red-500 @enderror">{{ old('keterangan', $proker->keterangan) }}</textarea>
                    @error('keterangan')
                        <p class="text-red-500 text-sm mt-1">{{ $message }}</p>
                    @enderror
                </div>

                <div class="flex justify-center">
                    <button type="submit"
                        class="inline-flex items-center gap-2 px-6 py-2 bg-gradient-to-r from-blue-500 to-blue-600 text-white text-sm font-semibold rounded-lg shadow hover:from-blue-600 hover:to-blue-700 focus:outline-none focus:ring-2 focus:ring-blue-300">
                        <i class="fas fa-save text-sm"></i> Simpan Perubahan
                    </button>
                </div>
            </form>
        </div>
    </div>
@endsection
