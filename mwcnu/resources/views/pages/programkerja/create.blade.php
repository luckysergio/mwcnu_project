@extends('layouts.app')

@section('content')

    <link href="https://cdn.jsdelivr.net/npm/sweetalert2@11.0.15/dist/sweetalert2.min.css" rel="stylesheet">

    {{-- <div class="text-center mb-4">
        <h1 class="h3 text-gray-800">Form tambah anggota</h1>
    </div> --}}

    <div class="row justify-content-center">
        <div class="col-md-8">
            <form action="/proker" method="POST" class="needs-validation" novalidate>
                @csrf

                <div class="card shadow">
                    <div class="card-body">

                        <script src="https://cdn.jsdelivr.net/npm/sweetalert2@11.0.15/dist/sweetalert2.all.min.js"></script>

                        @if(session('success'))
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

                        @if($errors->any())
                            <script>
                                Swal.fire({
                                    icon: 'error',
                                    title: 'Gagal!',
                                    html: `{!! implode('<br>', $errors->all()) !!}`
                                });
                            </script>
                        @endif


                        <div class="mb-3">
                            <label for="program" class="form-label">Program kerja</label>
                            <input type="text" name="program" id="program"
                                class="form-control @error('program') is-invalid @enderror">
                            @error('program')
                                <div class="invalid-feedback">{{ $message }}</div>
                            @enderror
                        </div>

                        <div class="mb-3">
                            <label for="catatan" class="form-label">Catatan</label>
                            <textarea name="catatan" id="catatan" rows="4"
                                class="form-control @error('catatan') is-invalid @enderror"
                                style="resize: vertical;">{{ old('catatan') }}</textarea>
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