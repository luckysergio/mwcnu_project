@extends('layouts.app')

@section('content')

    <link href="https://cdn.jsdelivr.net/npm/sweetalert2@11.0.15/dist/sweetalert2.min.css" rel="stylesheet">


    <div class="row justify-content-center">
        <div class="col-md-8">
            <form action="/jadwal" method="POST" class="needs-validation" novalidate>
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
                                        window.location.href = "/jadwal";
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
                            <label for="proker_id" class="form-label">Program kerja</label>
                            <select name="proker_id" id="proker_id" class="form-control @error('proker_id') is-invalid @enderror">
                                <option disabled selected>Pilih Program Kerja</option>
                                @foreach($prokers as $proker)
                                    <option value="{{ $proker->id }}">{{ $proker->program }}</option>
                                @endforeach
                            </select>
                            @error('proker_id')
                                <div class="invalid-feedback">{{ $message }}</div>
                            @enderror
                        </div>
                    
                        <div class="mb-3">
                            <label for="tanggal_mulai" class="form-label">Tanggal mulai</label>
                            <input type="date" name="tanggal_mulai" id="tanggal_mulai" class="form-control @error('tanggal_mulai') is-invalid @enderror">
                            @error('tanggal_mulai')
                                <div class="invalid-feedback">{{ $message }}</div>
                            @enderror
                        </div>
                    
                        <div class="mb-3">
                            <label for="tanggal_selesai" class="form-label">Tanggal selesai</label>
                            <input type="date" name="tanggal_selesai" id="tanggal_selesai" class="form-control @error('tanggal_selesai') is-invalid @enderror">
                            @error('tanggal_selesai')
                                <div class="invalid-feedback">{{ $message }}</div>
                            @enderror
                        </div>
                    
                        <div class="mb-3">
                            <label for="catatan" class="form-label">Catatan</label>
                            <textarea name="catatan" id="catatan" class="form-control @error('catatan') is-invalid @enderror" rows="3"></textarea>
                            @error('catatan')
                                <div class="invalid-feedback">{{ $message }}</div>
                            @enderror
                        </div>
                    
                        <div class="mb-3">
                            <label for="status" class="form-label">Status</label>
                            <select name="status" id="status" class="form-control @error('status') is-invalid @enderror">
                                <option disabled selected>Pilih Status</option>
                                <option value="penjadwalan">Penjadwalan</option>
                                <option value="berjalan">Berjalan</option>
                                <option value="selesai">Selesai</option>
                            </select>
                            @error('status')
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