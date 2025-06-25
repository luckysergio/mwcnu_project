@extends('layouts.app')

@section('content')
    <link href="https://cdn.jsdelivr.net/npm/sweetalert2@11.0.15/dist/sweetalert2.min.css" rel="stylesheet" />

    @php
        $user = auth()->user();
        $role = $user->anggota?->role?->jabatan;
        $rantingUser = $user->anggota?->ranting_id;

        $canManageAll = in_array($role, ['Admin', 'Tanfidiyah']);
        $canManageOwnRanting = in_array($role, ['Tanfidiyah ranting', 'Sekretaris']);
    @endphp

    @if ($canManageAll || $canManageOwnRanting)
        <div class="flex flex-col sm:flex-row justify-between items-center mb-6 gap-4">
            <h1 class="text-3xl font-bold text-gray-900">Data Anggota</h1>
            <a href="/anggota/create"
                class="inline-flex items-center justify-center gap-3 px-6 py-3 bg-gradient-to-r from-green-500 to-green-600 hover:from-green-600 hover:to-green-700 text-white font-semibold text-sm rounded-xl shadow-lg transition">
                <i class="fas fa-plus text-sm"></i> Tambah Anggota
            </a>
        </div>
    @endif

    {{-- Alerts --}}
    <script src="https://cdn.jsdelivr.net/npm/sweetalert2@11.0.15/dist/sweetalert2.all.min.js"></script>
    @if (session('success'))
        <script>
            Swal.fire({
                icon: 'success',
                title: 'Berhasil!',
                text: @json(session('success'))
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

    {{-- Filter Ranting --}}
    <form method="GET" class="flex justify-center mb-8">
        <select name="ranting" onchange="this.form.submit()"
            class="w-full max-w-xs px-4 py-3 border rounded-full shadow-md focus:ring-green-400">
            <option value="">Semua Ranting</option>
            @foreach ($rantings as $ranting)
                <option value="{{ $ranting->kelurahan }}" {{ request('ranting') == $ranting->kelurahan ? 'selected' : '' }}>
                    {{ ucfirst($ranting->kelurahan) }}
                </option>
            @endforeach
        </select>
    </form>

    {{-- Table --}}
    <div class="bg-white shadow-md rounded-xl overflow-hidden mb-10">
        <div class="overflow-x-auto">
            <table class="min-w-full text-sm text-left">
                <thead class="bg-gray-100 text-gray-700 text-xs uppercase">
                    <tr>
                        <th class="px-6 py-4 text-center">Nama</th>
                        <th class="px-6 py-4 text-center">Email</th>
                        <th class="px-6 py-4 text-center">HP</th>
                        <th class="px-6 py-4 text-center">Jabatan</th>
                        <th class="px-6 py-4 text-center">Ranting</th>
                        <th class="px-6 py-4 text-center">Status</th>
                        <th class="px-6 py-4 text-center">Aksi</th>
                    </tr>
                </thead>
                <tbody class="divide-y divide-gray-200 text-gray-700">
                    @forelse ($anggotas as $item)
                        @php
                            $canEditDelete = false;
                            if ($canManageAll) {
                                $canEditDelete = true;
                            } elseif ($canManageOwnRanting && $item->ranting_id == $rantingUser) {
                                $canEditDelete = true;
                            }
                        @endphp

                        <tr class="hover:bg-gray-50">
                            <td class="px-6 py-4 text-center">{{ $item->name }}</td>
                            <td class="px-6 py-4 text-center">{{ $item->user?->email ?? 'Belum tertaut' }}</td>
                            <td class="px-6 py-4 text-center">{{ $item->phone }}</td>
                            <td class="px-6 py-4 text-center">{{ $item->role?->jabatan ?? '-' }}</td>
                            <td class="px-6 py-4 text-center capitalize">{{ $item->ranting?->kelurahan ?? '-' }}</td>
                            <td class="px-6 py-4 text-center">
                                <span
                                    class="px-3 py-1 text-xs font-semibold text-white rounded-full 
                                    {{ $item->status === 'active' ? 'bg-green-600' : 'bg-red-600' }}">
                                    {{ ucfirst($item->status) }}
                                </span>
                            </td>
                            <td class="px-6 py-4 text-center">
                                @if ($canEditDelete)
                                    <div class="flex justify-center gap-2">
                                        <a href="/anggota/{{ $item->id }}"
                                            class="inline-flex items-center justify-center w-9 h-9 text-white shadow transition"
                                            style="background-color: #facc15; border-radius: 9999px;">
                                            <i class="fas fa-edit"></i>
                                        </a>
                                        <button onclick="confirmDelete('{{ $item->id }}', '{{ $item->name }}')"
                                            class="inline-flex items-center justify-center w-9 h-9 text-white shadow transition"
                                            style="background-color: #dc2626; border-radius: 9999px;"
                                            onmouseover="this.style.backgroundColor='#b91c1c'"
                                            onmouseout="this.style.backgroundColor='#dc2626'">
                                            <i class="fas fa-trash-alt"></i>
                                        </button>
                                    </div>
                                @else
                                    <span class="text-gray-400 italic">Tidak Diizinkan</span>
                                @endif
                            </td>
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
                text: `Yakin ingin menghapus ${name}?`,
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
                    form.innerHTML = `
                        @csrf
                        <input type="hidden" name="_method" value="DELETE">
                    `;
                    document.body.appendChild(form);
                    form.submit();
                }
            });
        }
    </script>
@endsection
