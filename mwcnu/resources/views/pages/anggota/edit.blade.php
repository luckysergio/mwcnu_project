@extends('layouts.app')

@section('content')
    <link href="https://cdn.jsdelivr.net/npm/sweetalert2@11.0.15/dist/sweetalert2.min.css" rel="stylesheet">
    <script src="https://cdn.jsdelivr.net/npm/sweetalert2@11.0.15/dist/sweetalert2.all.min.js"></script>

    <div class="max-w-3xl mx-auto mt-10">
        <form action="{{ route('anggota.update', $anggota->id) }}" method="POST"
            class="bg-white shadow-md rounded-lg px-8 pt-6 pb-8 mb-4">
            @csrf
            @method('PUT')

            @if (session('success'))
                <script>
                    Swal.fire({
                        icon: 'success',
                        title: 'Berhasil!',
                        text: @json(session('success')),
                        didClose: () => window.location.href = "/anggota"
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

            <div class="mb-4">
                <label class="block text-gray-700 text-sm font-bold mb-2">Nama Lengkap</label>
                <input type="text" name="name" value="{{ old('name', $anggota->name) }}"
                    class="shadow appearance-none border w-full py-2 px-3 text-gray-700 leading-tight focus:outline-none focus:shadow-outline @error('name')  @else border-gray-300 @enderror">
            </div>

            <div class="mb-4">
                <label class="block text-gray-700 text-sm font-bold mb-2">Nomor Handphone</label>
                <input type="text" name="phone" value="{{ old('phone', $anggota->phone) }}"
                    class="shadow appearance-none border w-full py-2 px-3 text-gray-700 leading-tight focus:outline-none focus:shadow-outline @error('phone')  @else border-gray-300 @enderror">
            </div>

            <div class="mb-4">
                <label class="block text-gray-700 text-sm font-bold mb-2">Jabatan (Role)</label>
                <select name="role_id"
                    class="shadow appearance-none border w-full py-2 px-3 text-gray-700 leading-tight focus:outline-none focus:shadow-outline @error('role_id')  @else border-gray-300 @enderror">
                    <option disabled value="">Pilih Jabatan</option>
                    @foreach ($roles as $role)
                        <option value="{{ $role->id }}"
                            {{ old('role_id', $anggota->role_id) == $role->id ? 'selected' : '' }}>
                            {{ ucfirst($role->jabatan) }}
                        </option>
                    @endforeach
                </select>
            </div>

            <div class="mb-4">
                <label class="block text-gray-700 text-sm font-bold mb-2">Ranting</label>
                <select name="ranting_id"
                    class="shadow appearance-none border w-full py-2 px-3 text-gray-700 leading-tight focus:outline-none focus:shadow-outline @error('ranting_id')  @else border-gray-300 @enderror">
                    <option disabled value="">Pilih Ranting</option>
                    @foreach ($rantings as $ranting)
                        <option value="{{ $ranting->id }}"
                            {{ old('ranting_id', $anggota->ranting_id) == $ranting->id ? 'selected' : '' }}>
                            {{ ucwords($ranting->kelurahan) }}
                        </option>
                    @endforeach
                </select>
            </div>

            <div class="mb-6">
                <label class="block text-gray-700 text-sm font-bold mb-2">Status</label>
                <select name="status"
                    class="shadow appearance-none border w-full py-2 px-3 text-gray-700 leading-tight focus:outline-none focus:shadow-outline @error('status')  @else border-gray-300 @enderror">
                    <option disabled value="">Pilih Status</option>
                    <option value="active" {{ old('status', $anggota->status) == 'active' ? 'selected' : '' }}>Active
                    </option>
                    <option value="inactive" {{ old('status', $anggota->status) == 'inactive' ? 'selected' : '' }}>Inactive
                    </option>
                </select>
            </div>

            @if ($anggota->user)
                <div class="mb-4">
                    <label class="block text-gray-700 text-sm font-bold mb-2">Email</label>
                    <input type="email" name="user_email" value="{{ old('user_email', $anggota->user->email) }}"
                        class="shadow appearance-none border w-full py-2 px-3 text-gray-700 leading-tight focus:outline-none focus:shadow-outline">
                </div>

                <div class="mb-4">
                    <label class="block text-gray-700 text-sm font-bold mb-2">Password (kosongkan jika tidak diubah)</label>
                    <div class="relative">
                        <input type="password" name="user_password" id="user_password"
                            class="shadow appearance-none border w-full py-2 px-3 text-gray-700 leading-tight focus:outline-none focus:shadow-outline">
                        <button type="button" id="togglePassword" class="absolute right-3 top-2.5 text-gray-500">
                            <i class="fas fa-eye" id="toggleIcon"></i>
                        </button>
                    </div>
                </div>
            @else
                <div class="mb-4">
                    <label class="block text-gray-700 text-sm font-bold mb-2">Tautkan Akun User</label>
                    <select name="user_id"
                        class="shadow appearance-none border w-full py-2 px-3 text-gray-700 leading-tight focus:outline-none focus:shadow-outline">
                        <option value="">-- Pilih User --</option>
                        @foreach ($users as $user)
                            <option value="{{ $user->id }}" {{ old('user_id') == $user->id ? 'selected' : '' }}>
                                {{ $user->name }} ({{ $user->email }})
                            </option>
                        @endforeach
                    </select>
                </div>
            @endif

            <div class="flex items-center justify-center">
                <button type="submit"
                    class="bg-green-500 hover:bg-green-600 text-white font-bold py-2 px-4 rounded focus:outline-none focus:shadow-outline">
                    Simpan
                </button>
            </div>
        </form>
    </div>

    <script>
        document.getElementById('togglePassword').addEventListener('click', function() {
            const input = document.getElementById('user_password');
            const icon = this.querySelector('i');
            if (input.type === 'password') {
                input.type = 'text';
                icon.classList.remove('fa-eye');
                icon.classList.add('fa-eye-slash');
            } else {
                input.type = 'password';
                icon.classList.remove('fa-eye-slash');
                icon.classList.add('fa-eye');
            }
        });
    </script>
@endsection
