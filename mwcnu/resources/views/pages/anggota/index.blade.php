@extends('layouts.app')

@section('content')
    <link href="https://cdn.jsdelivr.net/npm/sweetalert2@11.0.15/dist/sweetalert2.min.css" rel="stylesheet">

    @auth
        @php
            $user = auth()->user();
            $canManage = in_array($user->anggota?->role?->jabatan, ['Admin', 'Tanfidiyah']);
        @endphp
        @if ($canManage)
            <div class="flex flex-col sm:flex-row justify-between items-center mb-6 gap-4">
                <h1 class="text-3xl font-bold text-gray-900">Data Anggota</h1>
                <a href="/anggota/create"
                    class="inline-flex items-center justify-center gap-3 px-6 py-3 bg-gradient-to-r from-green-500 to-green-600 hover:from-green-600 hover:to-green-700 active:from-green-700 active:to-green-800 text-white font-semibold text-sm rounded-xl shadow-lg hover:shadow-xl transform hover:-translate-y-0.5 transition-all duration-200 ease-in-out border border-green-400 hover:border-green-500 focus:outline-none focus:ring-4 focus:ring-green-200">
                    <i class="fas fa-plus text-sm"></i>
                    <span>Tambah Anggota</span>
                </a>
            </div>
        @endif
    @endauth

    <script src="https://cdn.jsdelivr.net/npm/sweetalert2@11.0.15/dist/sweetalert2.all.min.js"></script>

    @if (session('success'))
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

    @if (session('error'))
        <script>
            Swal.fire({
                icon: 'error',
                title: 'Gagal!',
                text: @json(session('error'))
            });
        </script>
    @endif

    <form method="GET" class="flex justify-center mb-8">
        <select name="ranting" id="rantingSelect" onchange="this.form.submit()"
            class="w-full max-w-xs px-4 py-3 bg-white border border-gray-300 rounded-full shadow-md focus:outline-none focus:ring-2 focus:ring-green-400 focus:border-transparent transition text-gray-700">
            <option value="">Semua Ranting</option>
            @foreach ($rantings as $ranting)
                <option value="{{ $ranting->kelurahan }}" {{ request('ranting') == $ranting->kelurahan ? 'selected' : '' }}>
                    {{ ucfirst($ranting->kelurahan) }}
                </option>
            @endforeach
        </select>
    </form>

    <div class="bg-white shadow-md rounded-xl overflow-hidden mb-10">
        <div class="overflow-x-auto">
            <table class="min-w-full text-sm text-left">
                <thead class="bg-gray-100 text-gray-700 uppercase tracking-wide text-xs">
                    <tr>
                        <th class="px-6 py-4 text-center">Nama</th>
                        <th class="px-6 py-4 text-center">Email</th>
                        <th class="px-6 py-4 text-center">HP</th>
                        <th class="px-6 py-4 text-center">Jabatan</th>
                        <th class="px-6 py-4 text-center">Ranting</th>
                        <th class="px-6 py-4 text-center">Status</th>
                        @auth
                            @if ($canManage)
                                <th class="px-6 py-4 text-center">Aksi</th>
                            @endif
                        @endauth
                    </tr>
                </thead>
                <tbody class="divide-y divide-gray-200 text-gray-700">
                    @forelse ($anggotas as $item)
                        <tr class="hover:bg-gray-50 transition-colors duration-200">
                            <td class="px-6 py-4 font-medium whitespace-nowrap text-center">{{ $item->name }}</td>
                            <td class="px-6 py-4 whitespace-nowrap text-center">
                                {{ $item->user?->email ?? 'akun belum tertaut' }}
                            </td>
                            <td class="px-6 py-4 whitespace-nowrap text-center">{{ $item->phone }}</td>
                            <td class="px-6 py-4 whitespace-nowrap text-center">{{ $item->role->jabatan ?? '-' }}</td>
                            <td class="px-6 py-4 capitalize whitespace-nowrap text-center">
                                {{ ucfirst($item->ranting->kelurahan ?? '-') }}</td>
                            <td class="px-6 py-4 text-center">
                                <span
                                    class="inline-block px-3 py-1 rounded-full text-xs font-semibold text-white 
                                {{ $item->status === 'active' ? 'bg-green-600' : 'bg-red-600' }}">
                                    {{ ucfirst($item->status) }}
                                </span>
                            </td>
                            @auth
                                @if ($canManage)
                                    <td class="px-6 py-4 text-center whitespace-nowrap">
                                        <div class="flex justify-center gap-2">
                                            <a href="/anggota/{{ $item->id }}"
                                                class="inline-flex items-center justify-center w-9 h-9 rounded-full text-white shadow transition"
                                                style="background-color: #facc15;">
                                                <i class="fas fa-edit"></i>
                                            </a>
                                            <button onclick="confirmDelete('{{ $item->id }}', '{{ $item->name }}')"
                                                aria-label="Delete"
                                                class="inline-flex items-center justify-center w-9 h-9 rounded-full text-white shadow transition"
                                                style="background-color: #dc2626; border-radius: 9999px;"
                                                onmouseover="this.style.backgroundColor='#b91c1c'"
                                                onmouseout="this.style.backgroundColor='#dc2626'">
                                                <i class="fas fa-trash-alt"></i>
                                            </button>
                                        </div>
                                    </td>
                                @endif
                            @endauth
                        </tr>
                    @empty
                        <tr>
                            <td colspan="7" class="py-6 text-center text-gray-400 italic">
                                Tidak ada data anggota.
                            </td>
                        </tr>
                    @endforelse
                </tbody>
            </table>
        </div>
    </div>

    <div class="px-6 pb-6">
    {{ $anggotas->withQueryString()->links() }}
    </div>

    <script>
        function confirmDelete(id, name) {
            Swal.fire({
                title: 'Hapus Data?',
                text: `Yakin ingin menghapus data ${name}?`,
                icon: 'warning',
                showCancelButton: true,
                confirmButtonColor: '#d33',
                cancelButtonColor: '#6c757d',
                confirmButtonText: 'Ya, hapus',
                cancelButtonText: 'Batal'
            }).then((result) => {
                if (result.isConfirmed) {
                    const form = document.createElement('form');
                    form.method = 'POST';
                    form.action = `/anggota/${id}`;

                    const csrf = document.createElement('input');
                    csrf.type = 'hidden';
                    csrf.name = '_token';
                    csrf.value = '{{ csrf_token() }}';

                    const method = document.createElement('input');
                    method.type = 'hidden';
                    method.name = '_method';
                    method.value = 'DELETE';

                    form.appendChild(csrf);
                    form.appendChild(method);
                    document.body.appendChild(form);
                    form.submit();
                }
            });
        }
    </script>
@endsection
