@extends('layouts.app')

@section('content')
    <link href="https://cdn.jsdelivr.net/npm/sweetalert2@11.0.15/dist/sweetalert2.min.css" rel="stylesheet">

    <div class="row justify-content-center">
        <div class="col-md-8">
            <form action="{{ url('/profile/' . auth()->user()->id) }}" method="POST" class="needs-validation" novalidate>
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
                                        window.location.href = "/dashboard";
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

                            @if (auth()->user()->anggota)
                            <hr>
                            <h5 class="mb-3">Jika data anggota salah silahkan hubungi admin / seketaris</h5>

                            <div class="mb-3">
                                <label class="form-label">Nama Anggota</label>
                                <input type="text" class="form-control" value="{{ auth()->user()->anggota->name }}"
                                    readonly>
                            </div>
                            <div class="mb-3">
                                <label class="form-label">No HP</label>
                                <input type="text" class="form-control" value="{{ auth()->user()->anggota->phone }}"
                                    readonly>
                            </div>
                            <div class="mb-3">
                                <label class="form-label">Jabatan</label>
                                <input type="text" class="form-control"
                                    value="{{ ucwords(auth()->user()->anggota->jabatan) }}" readonly>
                            </div>
                            <div class="mb-3">
                                <label class="form-label">Ranting</label>
                                <input type="text" class="form-control"
                                    value="{{ ucwords(auth()->user()->anggota->ranting) }}" readonly>
                            </div>
                            <div class="mb-3">
                                <label class="form-label">Status</label>
                                <input type="text" class="form-control"
                                    value="{{ ucfirst(auth()->user()->anggota->status) }}" readonly>
                            </div>
                        @endif

                        <div class="mb-3">
                            <label for="user_name" class="form-label">Nama User</label>
                            <input type="text" name="user_name" id="user_name" class="form-control"
                                value="{{ old('user_name', auth()->user()->name) }}">
                        </div>

                        <div class="mb-3">
                            <label for="user_email" class="form-label">Email</label>
                            <input type="email" name="user_email" id="user_email" class="form-control"
                                value="{{ old('user_email', auth()->user()->email) }}">
                        </div>

                        <div class="mb-3">
                            <label for="user_password" class="form-label">Password (kosongkan jika tidak diubah)</label>
                            <div class="input-group">
                                <input type="password" name="user_password" id="user_password" class="form-control"
                                    autocomplete="off">
                                <span class="input-group-text bg-white border-start-0">
                                    <i class="fa fa-eye" id="toggleIcon" style="cursor: pointer;"
                                        onclick="togglePassword()"></i>
                                </span>
                            </div>
                        </div>
                    </div>
                    <div class="card-footer bg-white d-flex justify-content-end">
                        <button type="submit" class="btn btn-success">Simpan</button>
                    </div>
                </div>
            </form>
        </div>
    </div>

    <script>
        function togglePassword() {
            const input = document.getElementById('user_password');
            const icon = document.getElementById('toggleIcon');
            if (input.type === 'password') {
                input.type = 'text';
                icon.classList.remove('fa-eye');
                icon.classList.add('fa-eye-slash');
            } else {
                input.type = 'password';
                icon.classList.remove('fa-eye-slash');
                icon.classList.add('fa-eye');
            }
        }
    </script>

    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.0.0-beta3/css/all.min.css" />
@endsection
