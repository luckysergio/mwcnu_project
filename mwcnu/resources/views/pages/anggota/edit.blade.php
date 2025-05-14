@extends('layouts.app')

@section('content')

<link href="https://cdn.jsdelivr.net/npm/sweetalert2@11.0.15/dist/sweetalert2.min.css" rel="stylesheet">

<div class="row justify-content-center">
    <div class="col-md-8">
        <form action="{{ url('/anggota/' . $anggota->id) }}" method="POST" class="needs-validation" novalidate>
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
                                    window.location.href = "/anggota";
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
                        <label for="name" class="form-label">Nama Lengkap</label>
                        <input type="text" name="name" id="name" class="form-control"
                            value="{{ old('name', $anggota->name) }}">
                    </div>
                    <div class="mb-3">
                        <label for="phone" class="form-label">Nomor Handphone</label>
                        <input type="text" name="phone" id="phone" class="form-control"
                            value="{{ old('phone', $anggota->phone) }}">
                    </div>
                    <div class="mb-3">
                        <label for="jabatan" class="form-label">Jabatan</label>
                        <select name="jabatan" id="jabatan" class="form-control @error('jabatan') is-invalid @enderror">
                            <option disabled value="">Pilih Jabatan</option>
                            @foreach ([
                                'mustasyar', 'syuriah', 'ross syuriah', 'katib', 'awan',
                                'tanfidiyah', 'wakil ketua', 'sekertaris', 'bendahara', 'anggota'
                            ] as $value)
                                <option value="{{ $value }}" {{ old('jabatan', $anggota->jabatan) == $value ? 'selected' : '' }}>
                                    {{ ucfirst($value) }}
                                </option>
                            @endforeach
                        </select>
                        @error('jabatan')
                            <div class="invalid-feedback">{{ $message }}</div>
                        @enderror
                    </div>
                    <div class="mb-3">
                        <label for="ranting" class="form-label">Ranting</label>
                        <select name="ranting" id="ranting" class="form-control @error('ranting') is-invalid @enderror">
                            <option disabled value="">Pilih Ranting</option>
                            @foreach ([
                                'karang tengah', 'karang mulya', 'karang timur',
                                'pedurenan', 'pondok bahar', 'pondok pucung', 'parung jaya'
                            ] as $value)
                                <option value="{{ $value }}" {{ old('ranting', $anggota->ranting) == $value ? 'selected' : '' }}>
                                    {{ ucwords($value) }}
                                </option>
                            @endforeach
                        </select>
                        @error('ranting')
                            <div class="invalid-feedback">{{ $message }}</div>
                        @enderror
                    </div>
                    <div class="mb-3">
                        <label for="status" class="form-label">Status</label>
                        <select name="status" id="status" class="form-control @error('status') is-invalid @enderror">
                            <option disabled value="">Pilih Status</option>
                            <option value="active" {{ old('status', $anggota->status) == 'active' ? 'selected' : '' }}>Active</option>
                            <option value="inactive" {{ old('status', $anggota->status) == 'inactive' ? 'selected' : '' }}>Inactive</option>
                        </select>
                        @error('status')
                            <div class="invalid-feedback">{{ $message }}</div>
                        @enderror
                    </div>
                    <hr>
                    <div class="mb-3">
                        <label for="user_name" class="form-label">Nama User</label>
                        <input type="text" name="user_name" id="user_name" class="form-control"
                            value="{{ old('user_name', $anggota->user->name) }}">
                    </div>
                    <div class="mb-3">
                        <label for="user_email" class="form-label">Email</label>
                        <input type="email" name="user_email" id="user_email" class="form-control"
                            value="{{ old('user_email', $anggota->user->email) }}">
                    </div>
                    <div class="mb-3">
                        <label for="user_password" class="form-label">Password (kosongkan jika tidak diubah)</label>
                        <div class="input-group">
                            <input type="password" name="user_password" id="user_password" class="form-control" autocomplete="off">
                            <span class="input-group-text bg-white border-start-0">
                                <i class="fa fa-eye" id="toggleIcon" style="cursor: pointer;" onclick="togglePassword()"></i>
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
