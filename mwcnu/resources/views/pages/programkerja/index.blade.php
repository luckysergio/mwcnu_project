@extends('layouts.app')

@section('content')
    <link href="https://cdn.jsdelivr.net/npm/sweetalert2@11.0.15/dist/sweetalert2.min.css" rel="stylesheet">

    {{-- Header dan tombol tambah --}}
    <div class="flex flex-col sm:flex-row justify-between items-center mb-6 gap-4">
        <h1 class="text-3xl font-bold text-gray-900">Data Program Kerja</h1>
        <a href="/proker/create"
            class="inline-flex items-center justify-center gap-3 px-6 py-3 bg-gradient-to-r from-green-500 to-green-600 hover:from-green-600 hover:to-green-700 active:from-green-700 active:to-green-800 text-white font-semibold text-sm rounded-xl shadow-lg hover:shadow-xl transform hover:-translate-y-0.5 transition-all duration-200 ease-in-out border border-green-400 hover:border-green-500 focus:outline-none focus:ring-4 focus:ring-green-200">
            <i class="fas fa-plus text-sm"></i>
            <span>Ajukan Program Kerja</span>
        </a>
    </div>

    <form method="GET" class="flex justify-center mb-8">
    <div class="relative w-full max-w-xs">
        <select name="status" onchange="this.form.submit()"
            class="appearance-none w-full px-4 py-3 bg-white border border-gray-300 rounded-full shadow-md 
                focus:outline-none focus:ring-2 focus:ring-green-400 focus:border-transparent 
                text-gray-700 text-center cursor-pointer">
            <option value="">Semua Program Kerja</option>
            @foreach (['pengajuan', 'di setujui', 'di tolak'] as $r)
                <option value="{{ $r }}" {{ request('status') == $r ? 'selected' : '' }}>
                    {{ ucfirst($r) }}
                </option>
            @endforeach
        </select>
        <div class="pointer-events-none absolute inset-y-0 right-4 flex items-center">
        </div>
    </div>
</form>

    <script src="https://cdn.jsdelivr.net/npm/sweetalert2@11.0.15/dist/sweetalert2.all.min.js"></script>

    @if (session('success'))
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

    <div class="bg-white shadow-md rounded-xl overflow-hidden mb-10">
        <div class="overflow-x-auto">
            <table class="table-auto w-full text-sm text-left">
                <thead class="bg-gray-100 text-gray-700 uppercase tracking-wide text-xs select-none">
                    <tr>
                        <th class="px-4 py-3 text-center">Mengajukan</th>
                        <th class="px-4 py-3 text-center">Program Kerja</th>
                        <th class="px-4 py-3 text-center">Status</th>
                        <th class="px-4 py-3 text-center">Catatan</th>
                        @auth
                            @if (auth()->user()->role_id == 1)
                                <th class="px-4 py-3 text-center">Aksi</th>
                            @endif
                        @endauth
                    </tr>
                </thead>
                <tbody class="divide-y divide-gray-200 text-gray-700">
                    @forelse ($prokers as $item)
                        <tr class="hover:bg-gray-50 transition-colors duration-200">
                            <td class="px-4 py-3 whitespace-nowrap text-center font-medium">{{ $item->user->name }}</td>
                            <td class="px-4 py-3 text-center break-words whitespace-normal max-w-xs">{{ $item->program }}
                            </td>
                            <td class="px-4 py-3 whitespace-nowrap text-center font-semibold text-green-600">
                                {{ ucfirst($item->status) }}</td>
                            <td class="px-4 py-3 text-center break-words whitespace-normal max-w-xs">
                                {{ $item->catatan ?? '-' }}</td>
                            @auth
                                @if (auth()->user()->role_id == 1)
                                    <td class="px-4 py-3 text-center whitespace-nowrap">
                                        <div class="flex justify-center gap-2">
                                            <a href="/proker/{{ $item->id }}"
                                                class="inline-flex items-center justify-center w-9 h-9 rounded-full text-white shadow transition"
                                                style="background-color: #facc15;">
                                                <i class="fas fa-edit"></i>
                                            </a>
                                            <button type="button"
                                                onclick="confirmDelete('{{ $item->id }}', '{{ $item->program }}')"
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
                            <td colspan="5" class="py-6 text-center text-gray-400 italic">Tidak ada data program kerja.
                            </td>
                        </tr>
                    @endforelse
                </tbody>
            </table>
        </div>
    </div>

    <script>
        function confirmDelete(id, program) {
            Swal.fire({
                title: 'Hapus Data?',
                text: `Yakin ingin menghapus program kerja "${program}"?`,
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
                    form.action = `/proker/${id}`;

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
