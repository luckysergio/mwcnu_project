@extends('layouts.app')

@section('content')

    <link href="https://cdn.jsdelivr.net/npm/sweetalert2@11.0.15/dist/sweetalert2.min.css" rel="stylesheet">

    {{-- <div class="text-center mb-4">
        <h1 class="h3 text-gray-800">Form tambah anggota</h1>
    </div> --}}

    <div class="row justify-content-center">
        <div class="col-md-8">
            <form action="/anggota" method="POST" class="needs-validation" novalidate>
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
                            <input type="text" name="name" id="name"
                                class="form-control @error('name') is-invalid @enderror">
                            @error('name')
                                <div class="invalid-feedback">{{ $message }}</div>
                            @enderror
                        </div>

                        <div class="mb-3">
                            <label for="email" class="form-label">Email</label>
                            <input type="text" name="email" id="email"
                                class="form-control @error('email') is-invalid @enderror">
                            @error('email')
                                <div class="invalid-feedback">{{ $message }}</div>
                            @enderror
                        </div>

                        <div class="mb-3">
                            <label for="phone" class="form-label">Nomor Handphone</label>
                            <input type="text" name="phone" id="phone"
                                class="form-control @error('phone') is-invalid @enderror">
                            @error('phone')
                                <div class="invalid-feedback">{{ $message }}</div>
                            @enderror
                        </div>

                        <div class="mb-3">
                            <label for="jabatan" class="form-label">Jabatan</label>
                            <select name="jabatan" id="jabatan" class="form-control @error('jabatan') is-invalid @enderror">
                                <option disabled selected>Pilih Jabatan</option>
                                <option value="mustasyar">Mustasyar</option>
                                <option value="syuriah">Syuriah</option>
                                <option value="ross syuriah">Ross Syuriah</option>
                                <option value="katib">Katib</option>
                                <option value="awan">A'wan</option>
                                <option value="tanfidiyah">Tanfidiyah</option>
                                <option value="wakil ketua">Wakil Ketua</option>
                                <option value="sekertaris">Sekretaris</option>
                                <option value="bendahara">Bendahara</option>
                                <option value="anggota">Anggota</option>
                            </select>
                            @error('jabatan')
                                <div class="invalid-feedback">{{ $message }}</div>
                            @enderror
                        </div>

                        <div class="mb-3">
                            <label for="ranting" class="form-label">Ranting</label>
                            <select name="ranting" id="ranting" class="form-control @error('ranting') is-invalid @enderror">
                                <option disabled selected>Pilih Ranting</option>
                                <option value="karang tengah">Karang Tengah</option>
                                <option value="karang mulya">Karang Mulya</option>
                                <option value="karang timur">Karang Timur</option>
                                <option value="pedurenan">Pedurenan</option>
                                <option value="pondok bahar">Pondok Bahar</option>
                                <option value="pondok pucung">Pondok Pucung</option>
                                <option value="parung jaya">Parung Jaya</option>
                            </select>
                            @error('ranting')
                                <div class="invalid-feedback">{{ $message }}</div>
                            @enderror
                        </div>

                        <div class="mb-3">
                            <label for="status" class="form-label">Status</label>
                            <select name="status" id="status" class="form-control @error('status') is-invalid @enderror">
                                <option disabled selected>Pilih Status</option>
                                <option value="active">Active</option>
                                <option value="inactive">In active</option>
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