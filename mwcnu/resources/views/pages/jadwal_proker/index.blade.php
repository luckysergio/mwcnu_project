@extends('layouts.app')

@section('content')
    <link href="https://cdn.jsdelivr.net/npm/sweetalert2@11.0.15/dist/sweetalert2.min.css" rel="stylesheet">
    <script src="https://cdn.jsdelivr.net/npm/sweetalert2@11.0.15/dist/sweetalert2.all.min.js"></script>

    @auth
        @if (auth()->user()->role_id == 1)
            <div class="flex flex-col sm:flex-row justify-between items-center mb-6 gap-4">
                <h1 class="text-3xl font-bold text-gray-900">Jadwal Program Kerja</h1>
                <div class="relative">
                    <a href="/jadwal/create"
                        class="inline-flex items-center justify-center gap-3 px-6 py-3 bg-gradient-to-r from-green-500 to-green-600 hover:from-green-600 hover:to-green-700 active:from-green-700 active:to-green-800 text-white font-semibold text-sm rounded-xl shadow-lg hover:shadow-xl transform hover:-translate-y-0.5 transition-all duration-200 ease-in-out border border-green-400 hover:border-green-500 focus:outline-none focus:ring-4 focus:ring-green-200">
                        <i class="fas fa-plus text-sm"></i>
                        <span>Buat Jadwal</span>
                    </a>
                    @if ($belumDijadwalCount > 0)
                        <span
                            class="absolute -top-2 -right-2 bg-gradient-to-r from-yellow-400 to-orange-400 text-white text-xs font-bold min-w-[24px] h-6 flex items-center justify-center rounded-full shadow-lg ring-2 ring-white animate-pulse">
                            {{ $belumDijadwalCount }}
                        </span>
                    @endif
                </div>
            </div>
        @endif
    @endauth

    @if (session('success'))
        <script>
            Swal.fire({
                icon: 'success',
                title: 'Berhasil!',
                text: @json(session('success')),
                didClose: () => {
                    window.location.href = "/jadwal";
                }
            });
        </script>
    @endif

    <form method="GET" class="flex justify-center mb-8">
        <div class="relative w-full max-w-xs">
            <select name="status" id="statusSelect" onchange="this.form.submit()"
                class="appearance-none w-full px-4 py-3 pr-10 bg-white border border-gray-300 rounded-full shadow-md focus:outline-none focus:ring-2 focus:ring-green-400 focus:border-transparent transition text-gray-700">
                <option value="">Semua Status</option>
                @foreach (['penjadwalan', 'berjalan', 'selesai'] as $r)
                    <option value="{{ $r }}" {{ request('status') == $r ? 'selected' : '' }}>
                        {{ ucfirst($r) }}
                    </option>
                @endforeach
            </select>
            <div class="absolute inset-y-0 right-0 flex items-center px-3 pointer-events-none text-gray-400">
                <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 9l-7 7-7-7" />
                </svg>
            </div>
        </div>
    </form>

    <div class="bg-white shadow-md rounded-xl overflow-hidden mb-10">
        <div class="overflow-x-auto">
            <table class="w-full text-sm text-left">
                <thead class="bg-gray-100 text-gray-700 uppercase tracking-wide text-xs">
                    <tr>
                        <th class="px-4 py-3 text-center">Penanggung Jawab</th>
                        <th class="px-4 py-3 text-center">Program Kerja</th>
                        <th class="px-4 py-3 text-center">Mulai</th>
                        <th class="px-4 py-3 text-center">Selesai</th>
                        @auth
                            @if (auth()->user()->role_id == 1)
                                <th class="px-4 py-3 text-center">Aksi</th>
                            @endif
                        @endauth
                    </tr>
                </thead>
                <tbody class="divide-y divide-gray-200 text-gray-700">
                    @forelse ($jadwals as $item)
                        <tr class="hover:bg-gray-50 transition-colors duration-200">
                            <td class="px-4 py-3 text-center">{{ $item->penanggungJawab->name }}</td>
                            <td class="px-4 py-3 text-center">{{ $item->proker->program }}</td>
                            <td class="px-4 py-3 text-center">
                                {{ \Carbon\Carbon::parse($item->tanggal_mulai)->format('d-m-Y') }}</td>
                            <td class="px-4 py-3 text-center">
                                {{ \Carbon\Carbon::parse($item->tanggal_selesai)->format('d-m-Y') }}</td>
                            @auth
                                @if (auth()->user()->role_id == 1)
                                    <td class="px-4 py-3 text-center">
                                        <div class="flex justify-center gap-2">
                                            <a href="/jadwal/{{ $item->id }}"
                                                class="inline-flex items-center justify-center w-9 h-9 rounded-full text-white shadow transition"
                                                style="background-color: #facc15;">
                                                <i class="fas fa-edit"></i>
                                            </a>
                                            <button type="button"
                                                onclick="confirmDelete('{{ $item->id }}', '{{ $item->proker->program }}')"
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
                            <td colspan="7" class="text-center py-4 text-gray-400 italic">
                                Tidak ada jadwal program kerja.
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
                title: 'Hapus Jadwal?',
                text: `Yakin ingin menghapus jadwal untuk program "${program}"?`,
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
                    form.action = `/jadwal/${id}`;

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
