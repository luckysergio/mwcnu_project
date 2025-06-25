@extends('layouts.app')

@section('content')
    <link href="https://cdn.jsdelivr.net/npm/sweetalert2@11.0.15/dist/sweetalert2.min.css" rel="stylesheet">
    <script src="https://cdn.jsdelivr.net/npm/sweetalert2@11.0.15/dist/sweetalert2.all.min.js"></script>

    <div class="max-w-3xl mx-auto mt-10">
        <form action="/anggota" method="POST" class="bg-white shadow-md rounded-lg px-8 pt-6 pb-8 mb-4">
            @csrf

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
                <label for="email" class="block text-gray-700 text-sm font-bold mb-2">Email</label>
                <input type="email" name="email" id="email" value="{{ old('email') }}"
                    class="shadow appearance-none border w-full py-2 px-3 text-gray-700 leading-tight focus:outline-none focus:shadow-outline @error('email')  @else border-gray-300 @enderror">
            </div>

            <div class="mb-4">
                <label for="password" class="block text-gray-700 text-sm font-bold mb-2">Password</label>
                <div class="relative">
                    <input type="password" name="password" id="password"
                        class="shadow appearance-none border w-full py-2 px-3 text-gray-700 leading-tight focus:outline-none focus:shadow-outline @error('password')  @else border-gray-300 @enderror">
                    <button type="button" id="togglePassword" class="absolute right-3 top-2.5 text-gray-500">
                        <i class="fas fa-eye"></i>
                    </button>
                </div>
            </div>

            <div class="mb-4">
                <label for="name" class="block text-gray-700 text-sm font-bold mb-2">Nama Lengkap</label>
                <input type="text" name="name" id="name" value="{{ old('name') }}"
                    class="shadow appearance-none border w-full py-2 px-3 text-gray-700 leading-tight focus:outline-none focus:shadow-outline @error('name')  @else border-gray-300 @enderror">
            </div>

            <div class="mb-4">
                <label for="phone" class="block text-gray-700 text-sm font-bold mb-2">Nomor Handphone</label>
                <input type="text" name="phone" id="phone" value="{{ old('phone') }}"
                    class="shadow appearance-none border w-full py-2 px-3 text-gray-700 leading-tight focus:outline-none focus:shadow-outline @error('phone')  @else border-gray-300 @enderror">
            </div>

            <div class="mb-4">
                <label for="role_id" class="block text-gray-700 text-sm font-bold mb-2">Jabatan (Role)</label>
                <select name="role_id" id="role_id"
                    class="shadow appearance-none border w-full py-2 px-3 text-gray-700 leading-tight focus:outline-none focus:shadow-outline @error('role_id')  @else border-gray-300 @enderror">
                    <option disabled selected>Pilih Jabatan</option>
                    @foreach ($roles as $role)
                        <option value="{{ $role->id }}" {{ old('role_id') == $role->id ? 'selected' : '' }}>
                            {{ ucfirst($role->jabatan) }}</option>
                    @endforeach
                    <option value="new">+ Tambah Jabatan Baru</option>
                </select>
                <input type="text" name="new_role" id="new_role" placeholder="Masukkan Jabatan Baru"
                    value="{{ old('new_role') }}"
                    class="shadow appearance-none border mt-2 w-full py-2 px-3 text-gray-700 leading-tight focus:outline-none focus:shadow-outline hidden">
            </div>

            @php
                $user = auth()->user();
                $role = $user->anggota?->role?->jabatan;
                $isRestricted = in_array($role, ['Tanfidiyah ranting', 'Sekretaris']);
                $userRantingId = $user->anggota?->ranting_id;
            @endphp

            <div class="mb-4">
                <label for="ranting_id" class="block text-gray-700 text-sm font-bold mb-2">Ranting</label>

                @if ($isRestricted)
                    <select name="ranting_id" id="ranting_id" disabled
                        class="shadow appearance-none border w-full py-2 px-3 bg-gray-100 text-gray-700 leading-tight focus:outline-none focus:shadow-outline">
                        @foreach ($rantings as $ranting)
                            @if ($ranting->id == $userRantingId)
                                <option value="{{ $ranting->id }}" selected>{{ ucfirst($ranting->kelurahan) }}</option>
                            @endif
                        @endforeach
                    </select>
                    <input type="hidden" name="ranting_id" value="{{ $userRantingId }}">
                @else
                    <select name="ranting_id" id="ranting_id"
                        class="shadow appearance-none border w-full py-2 px-3 text-gray-700 leading-tight focus:outline-none focus:shadow-outline @error('ranting_id') border-red-500 @else @enderror">
                        <option disabled selected>Pilih Ranting</option>
                        @foreach ($rantings as $ranting)
                            <option value="{{ $ranting->id }}" {{ old('ranting_id') == $ranting->id ? 'selected' : '' }}>
                                {{ ucfirst($ranting->kelurahan) }}</option>
                        @endforeach
                        <option value="new">+ Tambah Ranting Baru</option>
                    </select>
                    <input type="text" name="new_ranting" id="new_ranting" placeholder="Masukkan Nama Ranting Baru"
                        value="{{ old('new_ranting') }}"
                        class="shadow appearance-none border mt-2 w-full py-2 px-3 text-gray-700 leading-tight focus:outline-none focus:shadow-outline hidden">
                @endif
            </div>

            <div class="mb-6">
                <label for="status" class="block text-gray-700 text-sm font-bold mb-2">Status</label>
                <select name="status" id="status"
                    class="shadow appearance-none border w-full py-2 px-3 text-gray-700 leading-tight focus:outline-none focus:shadow-outline @error('status')  @else @enderror">
                    <option disabled selected>Pilih Status</option>
                    <option value="active" {{ old('status') == 'active' ? 'selected' : '' }}>Active</option>
                    <option value="inactive" {{ old('status') == 'inactive' ? 'selected' : '' }}>Inactive</option>
                </select>
            </div>

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
            const passwordInput = document.getElementById('password');
            const icon = this.querySelector('i');
            if (passwordInput.type === 'password') {
                passwordInput.type = 'text';
                icon.classList.remove('fa-eye');
                icon.classList.add('fa-eye-slash');
            } else {
                passwordInput.type = 'password';
                icon.classList.remove('fa-eye-slash');
                icon.classList.add('fa-eye');
            }
        });

        document.getElementById('role_id').addEventListener('change', function() {
            document.getElementById('new_role').classList.toggle('hidden', this.value !== 'new');
        });

        document.getElementById('ranting_id').addEventListener('change', function() {
            document.getElementById('new_ranting').classList.toggle('hidden', this.value !== 'new');
        });
    </script>
@endsection
