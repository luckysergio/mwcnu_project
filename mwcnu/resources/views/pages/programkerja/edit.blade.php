@extends('layouts.app')

@section('content')

<link href="https://cdn.jsdelivr.net/npm/sweetalert2@11.0.15/dist/sweetalert2.min.css" rel="stylesheet">

<div class="row justify-content-center">
    <div class="col-md-8">
        <form action="{{ url('/proker/' . $proker->id) }}" method="POST" class="needs-validation" novalidate>
            @csrf
            @method('PUT')

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
                        <label for="program" class="form-label">Nama Program Kerja</label>
                        <input type="text" name="program" id="program" class="form-control"
                            value="{{ old('program', $proker->program) }}">
                    </div>

                    <div class="mb-3">
                        <label for="catatan" class="form-label">Catatan</label>
                        <textarea name="catatan" id="catatan" rows="3" class="form-control">{{ old('catatan', $proker->catatan) }}</textarea>
                    </div>

                </div>

                <div class="card-footer bg-white d-flex justify-content-end">
                    <button type="submit" class="btn btn-success">Perbarui</button>
                </div>
            </div>
        </form>
    </div>
</div>

@endsection
