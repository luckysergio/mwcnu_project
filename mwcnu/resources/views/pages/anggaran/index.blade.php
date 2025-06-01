@extends('layouts.app')

@section('content')
    <link href="https://cdn.jsdelivr.net/npm/sweetalert2@11.0.15/dist/sweetalert2.min.css" rel="stylesheet" />
    <script src="https://cdn.jsdelivr.net/npm/sweetalert2@11.0.15/dist/sweetalert2.all.min.js"></script>

    @if (session('success'))
        <script>
            Swal.fire({
                icon: 'success',
                title: 'Berhasil!',
                text: @json(session('success')),
                didClose: () => {
                    window.location.href = "{{ route('anggaran.index') }}";
                }
            });
        </script>
    @endif

    <div class="mb-10 space-y-6 px-4 md:px-8 lg:px-16">
        <div class="flex flex-col md:flex-row justify-between items-start md:items-center gap-4">
            <h1 class="text-3xl font-bold text-gray-900">Laporan Anggaran</h1>

            <div class="flex flex-col md:flex-row items-center gap-3 w-full md:w-auto">
                <form method="GET" class="w-full md:w-auto">
                    <div class="relative">
                        <select name="jadwal_proker_id" onchange="this.form.submit()"
                            class="appearance-none w-full md:w-64 px-4 py-2 pr-10 bg-white border border-gray-300 rounded-full shadow-sm focus:outline-none focus:ring-2 focus:ring-green-400 text-gray-700">
                            <option value="">Pilih Program Kerja</option>
                            @foreach ($jadwalProkers as $jadwal)
                                <option value="{{ $jadwal->id }}"
                                    {{ request('jadwal_proker_id') == $jadwal->id ? 'selected' : '' }}>
                                    {{ $jadwal->proker->program ?? 'Tanpa Nama Proker' }}
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

                @auth
                    @php
                        $user = auth()->user();
                        $isSekretaris = $user->anggota?->jabatan === 'sekertaris';
                    @endphp

                    @if ($user->role_id == 1 || $isSekretaris)
                        <a href="{{ route('anggaran.create') }}"
                            class="inline-flex items-center justify-center gap-2 px-5 py-2.5 bg-gradient-to-r from-green-500 to-green-600 hover:from-green-600 hover:to-green-700 text-white font-semibold text-sm rounded-full shadow-md transition duration-150 whitespace-nowrap">
                            <i class="fas fa-plus"></i>
                            <span>Tambah Anggaran</span>
                        </a>
                    @endif
                @endauth

            </div>
        </div>

        @if (!request('jadwal_proker_id'))
            <div class="bg-yellow-100 border-l-4 border-yellow-500 text-yellow-700 p-4 rounded-md mt-6" role="alert">
                <p class="font-bold">Perhatian</p>
                <p>Silakan pilih program kerja terlebih dahulu untuk melihat data anggaran.</p>
            </div>
        @else
            <div class="bg-white shadow-md rounded-xl overflow-hidden mt-6">
                <div class="overflow-x-auto">
                    <table class="w-full text-sm text-left min-w-[600px] md:min-w-full">
                        <thead class="bg-gray-100 text-gray-700 uppercase tracking-wide text-xs">
                            <tr>
                                <th class="px-4 py-3 text-center whitespace-nowrap">Pendana</th>
                                <th class="px-4 py-3 text-center whitespace-nowrap">Jumlah</th>
                                <th class="px-4 py-3 text-center whitespace-nowrap">Catatan</th>
                                @auth
                                    @php
                                        $user = auth()->user();
                                        $isSekretaris = $user->anggota?->jabatan === 'sekertaris';
                                    @endphp
                                    @if ($user->role_id == 1 || $isSekretaris)
                                        <th class="px-4 py-3 text-center whitespace-nowrap">Aksi</th>
                                    @endif
                                @endauth
                            </tr>
                        </thead>
                        <tbody class="divide-y divide-gray-200 text-gray-700">
                            @forelse ($anggarans as $item)
                                <tr class="hover:bg-gray-50 transition duration-150">
                                    <td class="px-4 py-3 text-center whitespace-nowrap">{{ $item->pendana }}</td>
                                    <td class="px-4 py-3 text-center whitespace-nowrap">
                                        Rp {{ number_format($item->jumlah, 0, ',', '.') }}
                                    </td>
                                    <td class="px-4 py-3 text-center whitespace-nowrap">{{ $item->catatan ?? '-' }}</td>
                                    @auth
                                    @php
                                        $user = auth()->user();
                                        $isSekretaris = $user->anggota?->jabatan === 'sekertaris';
                                    @endphp
                                    @if ($user->role_id == 1 || $isSekretaris)
                                        <td class="px-4 py-3 text-center whitespace-nowrap">
                                        <div class="flex justify-center gap-2">
                                            <a href="{{ route('anggaran.edit', $item->id) }}"
                                                class="inline-flex items-center justify-center w-9 h-9 rounded-full text-white shadow transition"
                                                style="background-color: #facc15;" aria-label="Edit" title="Edit">
                                                <i class="fas fa-edit"></i>
                                            </a>
                                            <button type="button"
                                                onclick="confirmDelete('{{ $item->id }}', '{{ $item->pendana }}')"
                                                aria-label="Delete"
                                                class="inline-flex items-center justify-center w-9 h-9 rounded-full text-white shadow transition"
                                                style="background-color: #dc2626; border-radius: 9999px;"
                                                onmouseover="this.style.backgroundColor='#b91c1c'"
                                                onmouseout="this.style.backgroundColor='#dc2626'" title="Hapus">
                                                <i class="fas fa-trash-alt"></i>
                                            </button>
                                        </div>
                                    </td>
                                    @endif
                                @endauth
                                </tr>
                            @empty
                                <tr>
                                    <td colspan="4" class="text-center py-4 text-gray-400 italic">
                                        Belum ada data anggaran.
                                    </td>
                                </tr>
                            @endforelse
                        </tbody>
                        <tfoot>
                            <tr class="bg-gray-100 font-semibold text-gray-700 text-center">
                                <td colspan="4" class="px-4 py-3 whitespace-nowrap">
                                    Total: Rp {{ number_format($anggarans->sum('jumlah'), 0, ',', '.') }}
                                </td>
                            </tr>
                        </tfoot>
                    </table>
                </div>
            </div>
           
            <div class="mt-4 flex justify-center px-2 md:px-0">
                @if ($anggarans->isNotEmpty())
                    <a href="{{ route('anggaran.downloadPdf', ['jadwal_proker_id' => request('jadwal_proker_id')]) }}"
                        class="inline-flex items-center justify-center gap-2 px-5 py-2.5 bg-gradient-to-r from-blue-500 to-blue-600 hover:from-blue-600 hover:to-blue-700 text-white font-semibold text-sm rounded-full shadow-md transition duration-150 whitespace-nowrap">
                        <i class="fas fa-file-pdf"></i>
                        <span>Download PDF</span>
                    </a>
                @endif
            </div>
        @endif

    </div>

    <script>
        function confirmDelete(id, pendana) {
            Swal.fire({
                title: 'Hapus Anggaran?',
                text: `Yakin ingin menghapus anggaran dari "${pendana}"?`,
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
                    form.action = `/anggaran/${id}`;

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
