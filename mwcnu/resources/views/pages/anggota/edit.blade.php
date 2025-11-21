@extends('layouts.app')

@section('content')

    <link href="https://cdn.jsdelivr.net/npm/sweetalert2@11.0.15/dist/sweetalert2.min.css" rel="stylesheet">
    <script src="https://cdn.jsdelivr.net/npm/sweetalert2@11.0.15/dist/sweetalert2.all.min.js"></script>

    <div class="max-w-3xl mx-auto mt-10">
        <form action="{{ route('anggota.update', $anggota->id) }}" method="POST"
            class="bg-white shadow-xl border rounded-2xl px-8 pt-6 pb-10 mb-8">
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
                <label class="block text-sm font-semibold mb-2 text-gray-700">Nama Lengkap</label>
                <input type="text" name="name" value="{{ old('name', $anggota->name) }}"
                    class="w-full border rounded-xl py-2.5 px-4 text-gray-700 focus:outline-none focus:ring focus:ring-green-300">
            </div>

            <div class="mb-4">
                <label class="block text-sm font-semibold mb-2 text-gray-700">Nomor Handphone</label>
                <input type="text" name="phone" value="{{ old('phone', $anggota->phone) }}"
                    class="w-full border rounded-xl py-2.5 px-4 text-gray-700 focus:outline-none focus:ring focus:ring-green-300">
            </div>

            <div class="mb-4">
                <label class="block text-sm font-semibold mb-2 text-gray-700">Status Anggota</label>
                <select name="status_id" id="status_id"
                    class="w-full border rounded-xl py-2.5 px-4 text-gray-700 focus:outline-none focus:ring focus:ring-green-300">
                    <option disabled value="">Pilih Status</option>
                    @foreach ($statuses as $status)
                        <option value="{{ $status->id }}"
                            {{ old('status_id', $anggota->status_id) == $status->id ? 'selected' : '' }}>
                            {{ ucfirst($status->status) }}
                        </option>
                    @endforeach
                </select>
            </div>

            <div class="mb-4">
                <label class="block text-sm font-semibold mb-2 text-gray-700">Jabatan (Role)</label>
                <select name="role_id"
                    class="w-full border rounded-xl py-2.5 px-4 text-gray-700 focus:outline-none focus:ring focus:ring-green-300">
                    <option disabled value="">Pilih Jabatan</option>
                    @foreach ($roles as $role)
                        <option value="{{ $role->id }}"
                            {{ old('role_id', $anggota->role_id) == $role->id ? 'selected' : '' }}>
                            {{ ucfirst($role->jabatan) }}
                        </option>
                    @endforeach
                </select>
            </div>

            @php
                $user = auth()->user();
                $myRole = $user->anggota?->role?->jabatan;
                $isRestricted = in_array($myRole, ['Tanfidiyah ranting', 'Sekretaris']);
                $userRanting = $user->anggota?->ranting_id;
            @endphp

            <div class="mb-4" id="ranting_section">
                <label class="block text-sm font-semibold mb-2 text-gray-700">Ranting</label>

                @if ($isRestricted)
                    <select disabled class="w-full border bg-gray-100 rounded-xl py-2.5 px-4 text-gray-700">
                        @foreach ($rantings as $ranting)
                            @if ($ranting->id == $userRanting)
                                <option selected>{{ ucfirst($ranting->kelurahan) }}</option>
                            @endif
                        @endforeach
                    </select>
                    <input type="hidden" name="ranting_id" value="{{ $userRanting }}">
                @else
                    <select name="ranting_id" id="ranting_id"
                        class="w-full border rounded-xl py-2.5 px-4 text-gray-700 focus:outline-none focus:ring focus:ring-green-300">
                        <option disabled value="">Pilih Ranting</option>

                        @foreach ($rantings as $ranting)
                            <option value="{{ $ranting->id }}"
                                {{ old('ranting_id', $anggota->ranting_id) == $ranting->id ? 'selected' : '' }}>
                                {{ ucfirst($ranting->kelurahan) }}
                            </option>
                        @endforeach

                        <option value="new">+ Tambah Ranting Baru</option>
                    </select>

                    <input type="text" name="new_ranting" id="new_ranting" placeholder="Masukkan Nama Ranting Baru"
                        value="{{ old('new_ranting') }}"
                        class="w-full border rounded-xl py-2.5 px-4 mt-2 text-gray-700 focus:outline-none focus:ring focus:ring-green-300 hidden">
                @endif
            </div>

            @if ($anggota->user)
                <div class="mb-4">
                    <label class="block text-sm font-semibold mb-2 text-gray-700">Email</label>
                    <input type="email" name="user_email" value="{{ old('user_email', $anggota->user->email) }}"
                        class="w-full border rounded-xl py-2.5 px-4 text-gray-700 focus:outline-none focus:ring focus:ring-green-300">
                </div>

                <div class="mb-6">
                    <label class="block text-sm font-semibold mb-2 text-gray-700">
                        Password (kosongkan jika tidak diubah)
                    </label>
                    <div class="relative">
                        <input type="password" name="user_password" id="user_password"
                            class="w-full border rounded-xl py-2.5 px-4 text-gray-700 focus:outline-none focus:ring focus:ring-green-300">
                        <button type="button" id="togglePassword" class="absolute right-4 top-2.5 text-gray-500">
                            <i class="fas fa-eye" id="toggleIcon"></i>
                        </button>
                    </div>
                </div>
            @endif

            <div class="flex justify-center mb-8">
                <button type="submit"
                    class="bg-green-600 hover:bg-green-700 text-white font-bold py-2.5 px-6 rounded-xl shadow-md">
                    Simpan Perubahan
                </button>
            </div>

        </form>

    </div>

    <script>
        // Toggle password visibility
        document.getElementById('togglePassword')?.addEventListener('click', function() {
            const input = document.getElementById('user_password');
            const icon = this.querySelector('i');
            if (input.type === 'password') {
                input.type = 'text';
                icon.classList.replace('fa-eye', 'fa-eye-slash');
            } else {
                input.type = 'password';
                icon.classList.replace('fa-eye-slash', 'fa-eye');
            }
        });

        // Toggle input tambah ranting
        document.getElementById('ranting_id')?.addEventListener('change', function() {
            document.getElementById('new_ranting')
                .classList.toggle('hidden', this.value !== 'new');
        });

        // Show/hide ranting section if MWC selected
        const statusSelect = document.getElementById('status_id');
        const rantingSection = document.getElementById('ranting_section');
        const rantingInput = document.getElementById('ranting_id');

        function checkMWC() {
            const selected = statusSelect.options[statusSelect.selectedIndex].text.toLowerCase();

            if (selected.includes("mwc")) {
                rantingSection.classList.add('hidden');
                if (rantingInput) rantingInput.value = "";
            } else {
                rantingSection.classList.remove('hidden');
            }
        }

        statusSelect?.addEventListener('change', checkMWC);
        checkMWC(); // first load
    </script>

@endsection
