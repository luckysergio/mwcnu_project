@extends('layouts.app')

@section('content')
    <link href="https://cdn.jsdelivr.net/npm/sweetalert2@11.0.15/dist/sweetalert2.min.css" rel="stylesheet">

    <div class="row justify-content-center">
        <div class="col-md-8">
            <form action="{{ url('/anggaran/' . $anggaran->id) }}" method="POST" class="needs-validation" novalidate>
                @csrf
                @method('PUT')

                <div class="card shadow">
                    <div class="card-body">
                        <script src="https://cdn.jsdelivr.net/npm/sweetalert2@11.0.15/dist/sweetalert2.all.min.js"></script>

                        @if (session('success'))
                            <script>
                                Swal.fire({
                                    icon: 'success',
                                    title: 'Berhasil!',
                                    text: @json(session('success')),
                                    didClose: () => {
                                        window.location.href = "/anggaran";
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

                        <div class="mb-3">
    <label for="jadwal_proker_id" class="form-label">Program Kerja</label>
    <select name="jadwal_proker_id" id="jadwal_proker_id"
        class="form-control @error('jadwal_proker_id') is-invalid @enderror" disabled>
        <option disabled>Pilih Program Kerja</option>
        @foreach ($jadwalProkers as $jadwal)
            <option value="{{ $jadwal->id }}"
                {{ old('jadwal_proker_id', $anggaran->jadwal_proker_id) == $jadwal->id ? 'selected' : '' }}>
                {{ $jadwal->proker->program ?? 'Tanpa Nama Proker' }}
            </option>
        @endforeach
    </select>
    <input type="hidden" name="jadwal_proker_id" value="{{ old('jadwal_proker_id', $anggaran->jadwal_proker_id) }}">
    @error('jadwal_proker_id')
        <div class="invalid-feedback">{{ $message }}</div>
    @enderror
</div>


                        <div class="mb-3">
                            <label for="pendana" class="form-label">Pendana</label>
                            <input type="text" name="pendana" id="pendana"
                                class="form-control @error('pendana') is-invalid @enderror"
                                value="{{ old('pendana', $anggaran->pendana) }}">
                            @error('pendana')
                                <div class="invalid-feedback">{{ $message }}</div>
                            @enderror
                        </div>

                        <div class="mb-3">
                            <label for="jumlah" class="form-label">Jumlah</label>
                            <input type="number" name="jumlah" id="jumlah"
                                class="form-control @error('jumlah') is-invalid @enderror"
                                value="{{ old('jumlah', $anggaran->jumlah) }}">
                            @error('jumlah')
                                <div class="invalid-feedback">{{ $message }}</div>
                            @enderror
                        </div>

                        <div class="mb-3">
                            <label for="catatan" class="form-label">Catatan</label>
                            <textarea name="catatan" id="catatan" class="form-control @error('catatan') is-invalid @enderror" rows="3">{{ old('catatan', $anggaran->catatan) }}</textarea>
                            @error('catatan')
                                <div class="invalid-feedback">{{ $message }}</div>
                            @enderror
                        </div>
                    </div>
                    <div class="card-footer bg-white d-flex justify-content-end">
                        <button type="submit" class="btn btn-success">Simpan</button>
                    </div>
                </div>
            </form>
        </div>
    </div>
@endsection
