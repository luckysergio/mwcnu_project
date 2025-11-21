@extends('layouts.app')

@section('content')
    <link href="https://cdn.jsdelivr.net/npm/sweetalert2@11.0.15/dist/sweetalert2.min.css" rel="stylesheet">
    <script src="https://cdn.jsdelivr.net/npm/sweetalert2@11.0.15/dist/sweetalert2.all.min.js"></script>

    ```
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
                <label class="block text-gray-700 font-bold mb-2">Email</label>
                <input type="email" name="email" value="{{ old('email') }}"
                    class="shadow appearance-none border w-full py-2 px-3 text-gray-700 leading-tight 
                focus:outline-none focus:shadow-outline {{ $errors->has('email') ? 'border-red-500' : 'border-gray-300' }}">
            </div>

            <div class="mb-4">
                <label class="block text-gray-700 font-bold mb-2">Password</label>
                <div class="relative">
                    <input type="password" name="password" id="password"
                        class="shadow appearance-none border w-full py-2 px-3 text-gray-700 
                    focus:outline-none focus:shadow-outline {{ $errors->has('password') ? 'border-red-500' : 'border-gray-300' }}">

                    <button type="button" id="togglePassword" class="absolute right-3 top-2.5 text-gray-500">
                        <i class="fas fa-eye"></i>
                    </button>
                </div>
            </div>

            <div class="mb-4">
                <label class="block text-gray-700 font-bold mb-2">Nama Lengkap</label>
                <input type="text" name="name" value="{{ old('name') }}"
                    class="shadow appearance-none border w-full py-2 px-3 text-gray-700 
                focus:outline-none focus:shadow-outline {{ $errors->has('name') ? 'border-red-500' : 'border-gray-300' }}">
            </div>

            <div class="mb-4">
                <label class="block text-gray-700 font-bold mb-2">Nomor Handphone</label>
                <input type="text" name="phone" value="{{ old('phone') }}"
                    class="shadow appearance-none border w-full py-2 px-3 text-gray-700 
                focus:outline-none focus:shadow-outline {{ $errors->has('phone') ? 'border-red-500' : 'border-gray-300' }}">
            </div>

            <div class="mb-4">
                <label class="block text-gray-700 font-bold mb-2">Status Anggota</label>
                <select name="status_id" id="status_id"
                    class="shadow appearance-none border w-full py-2 px-3 text-gray-700 
                focus:outline-none focus:shadow-outline {{ $errors->has('status_id') ? 'border-red-500' : 'border-gray-300' }}">
                    <option disabled selected>Pilih Status</option>
                    @foreach ($statuses as $status)
                        <option value="{{ $status->id }}" {{ old('status_id') == $status->id ? 'selected' : '' }}>
                            {{ ucfirst($status->status) }}
                        </option>
                    @endforeach
                </select>
            </div>

            <div class="mb-4">
                <label class="block text-gray-700 font-bold mb-2">Jabatan (Role)</label>
                <select name="role_id" id="role_id"
                    class="shadow appearance-none border w-full py-2 px-3 text-gray-700 
                focus:outline-none focus:shadow-outline {{ $errors->has('role_id') ? 'border-red-500' : 'border-gray-300' }}">
                    <option disabled selected>Pilih Jabatan</option>
                    @foreach ($roles as $role)
                        <option value="{{ $role->id }}" {{ old('role_id') == $role->id ? 'selected' : '' }}>
                            {{ ucfirst($role->jabatan) }}
                        </option>
                    @endforeach
                    <option value="new">+ Tambah Jabatan Baru</option>
                </select>

                <input type="text" name="new_role" id="new_role" placeholder="Masukkan Jabatan Baru"
                    value="{{ old('new_role') }}"
                    class="shadow appearance-none border w-full py-2 px-3 mt-2 text-gray-700 
                focus:outline-none focus:shadow-outline hidden">
            </div>

            @php
                $user = auth()->user();
                $role = $user->anggota?->role?->jabatan;
                $isRestricted = in_array($role, ['Tanfidiyah ranting', 'Sekretaris']);
                $userRantingId = $user->anggota?->ranting_id;
            @endphp

            <div class="mb-4" id="ranting_section">
                <label class="block text-gray-700 font-bold mb-2">Ranting</label>

                @if ($isRestricted)
                    <select disabled class="shadow appearance-none border bg-gray-100 w-full py-2 px-3 text-gray-700">
                        @foreach ($rantings as $ranting)
                            @if ($ranting->id == $userRantingId)
                                <option selected>{{ ucfirst($ranting->kelurahan) }}</option>
                            @endif
                        @endforeach
                    </select>
                    <input type="hidden" name="ranting_id" value="{{ $userRantingId }}">

                @else
                    <select name="ranting_id" id="ranting_id"
                        class="shadow appearance-none border w-full py-2 px-3 text-gray-700 focus:outline-none focus:shadow-outline 
                    {{ $errors->has('ranting_id') ? 'border-red-500' : 'border-gray-300' }}">
                        <option value="" selected>Pilih Ranting</option>
                        @foreach ($rantings as $ranting)
                            <option value="{{ $ranting->id }}" {{ old('ranting_id') == $ranting->id ? 'selected' : '' }}>
                                {{ ucfirst($ranting->kelurahan) }}
                            </option>
                        @endforeach
                        <option value="new">+ Tambah Ranting Baru</option>
                    </select>

                    <input type="text" name="new_ranting" id="new_ranting" placeholder="Masukkan Nama Ranting Baru"
                        value="{{ old('new_ranting') }}"
                        class="shadow appearance-none border w-full py-2 px-3 mt-2 text-gray-700 
                    focus:outline-none focus:shadow-outline hidden">
                @endif
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
            const input = document.getElementById('password');
            const icon = this.querySelector('i');
            const isPassword = input.type === 'password';
            input.type = isPassword ? 'text' : 'password';
            icon.classList.toggle('fa-eye', !isPassword);
            icon.classList.toggle('fa-eye-slash', isPassword);
        });

        document.getElementById('role_id').addEventListener('change', function() {
            document.getElementById('new_role').classList.toggle('hidden', this.value !== 'new');
        });

        const rantingSelect = document.getElementById('ranting_id');
        if (rantingSelect) {
            rantingSelect.addEventListener('change', function() {
                document.getElementById('new_ranting').classList.toggle('hidden', this.value !== 'new');
            });
        }

        document.getElementById('status_id').addEventListener('change', function() {
            const selectedStatus = this.options[this.selectedIndex].text.toLowerCase();
            const rantingSection = document.getElementById('ranting_section');

            if (selectedStatus.includes("mwc")) {
                rantingSection.classList.add("hidden");
                if (document.getElementById('ranting_id')) {
                    document.getElementById('ranting_id').value = "";
                }
            } else {
                rantingSection.classList.remove("hidden");
            }
        });
    </script>
    ```

@endsection
