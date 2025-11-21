@extends('layouts.app')

@section('content')
    <link href="https://cdn.jsdelivr.net/npm/sweetalert2@11.0.15/dist/sweetalert2.min.css" rel="stylesheet">

    <div class="flex flex-col sm:flex-row justify-between items-center mb-6 gap-4">
        <h1 class="text-3xl font-bold text-gray-900">Data Program Kerja</h1>
        <a href="/proker/create"
            class="inline-flex items-center justify-center gap-3 px-6 py-3 bg-gradient-to-r from-green-500 to-green-600 hover:from-green-600 hover:to-green-700 text-white font-semibold text-sm rounded-xl shadow-lg transition-all duration-200">
            <i class="fas fa-plus text-sm"></i>
            <span>Ajukan Program Kerja</span>
        </a>
    </div>

    <form method="GET" class="flex justify-center mb-8">
        <div class="relative w-full max-w-xs">
            <select name="status" onchange="this.form.submit()"
                class="appearance-none w-full px-4 py-3 bg-white border border-gray-300 rounded-full shadow-md 
            focus:outline-none focus:ring-2 focus:ring-green-400 text-gray-700 text-center cursor-pointer">
                <option value="">Semua Program Kerja</option>
                @foreach (['pengajuan', 'disetujui', 'ditolak'] as $r)
                    <option value="{{ $r }}" {{ request('status') == $r ? 'selected' : '' }}>
                        {{ ucfirst($r) }}
                    </option>
                @endforeach
            </select>
        </div>
    </form>

    @if (session('success'))
        <script src="https://cdn.jsdelivr.net/npm/sweetalert2@11.0.15/dist/sweetalert2.all.min.js"></script>
        <script>
            Swal.fire({
                icon: 'success',
                title: 'Berhasil!',
                text: @json(session('success')),
                didClose: () => window.location.href = "/proker"
            });
        </script>
    @endif

    <div class="grid grid-cols-1 md:grid-cols-2 xl:grid-cols-3 gap-6">
        @forelse ($prokers as $item)
            <div
                class="bg-white border border-gray-200 rounded-2xl shadow-md hover:shadow-lg p-6 flex flex-col justify-between">
                <div class="space-y-3">
                    <div class="flex items-start justify-between">
                        <h2 class="text-lg font-bold text-gray-900 leading-snug">{{ $item->judul }}</h2>
                        <span
                            class="inline-block px-3 py-1 text-xs font-semibold text-white rounded-full
                        {{ $item->status == 'pengajuan' ? 'bg-yellow-500' : ($item->status == 'disetujui' ? 'bg-green-600' : 'bg-red-600') }}">
                            {{ ucfirst($item->status) }}
                        </span>
                    </div>

                    <p class="text-sm text-gray-600">Diajukan oleh: <strong
                            class="text-gray-800">{{ $item->anggota->name }}</strong></p>

                    <div class="text-sm text-gray-700 space-y-1">
                        <p><strong>Ranting:</strong> {{ $item->ranting->kelurahan ?? '-' }}</p>
                        <p><strong>Bidang:</strong> {{ $item->bidang->nama }}</p>
                        <p><strong>Jenis Kegiatan:</strong> {{ $item->jenis->nama }}</p>
                        <p><strong>Tujuan:</strong> {{ $item->tujuan->nama }}</p>
                        <p><strong>Sasaran:</strong> {{ $item->sasaran->nama }}</p>
                        <p><strong>Proposal:</strong> <a href="{{ asset('storage/' . $item->proposal) }}" target="_blank"
                                class="text-blue-600 hover:underline">Lihat</a></p>
                        <p><strong>Keterangan:</strong> {{ $item->keterangan ?? '-' }}</p>
                    </div>
                </div>

                @auth
                    @php
                        $jabatan = auth()->user()->anggota->role->jabatan ?? null;
                    @endphp
                    @if (in_array($jabatan, ['Admin', 'Tanfidiyah']))
                        <div class="mt-5 flex flex-col gap-3">
                            <a href="{{ route('proker.edit', $item->id) }}"
                                class="inline-flex items-center justify-center gap-2 px-5 py-2 bg-gradient-to-r from-green-500 to-green-600 hover:from-green-600 hover:to-green-700 text-white font-semibold text-sm rounded-lg shadow transition">
                                <i class="fas fa-edit"></i> Edit
                            </a>
                            <button type="button" onclick="confirmDelete('{{ $item->id }}', '{{ $item->judul }}')"
                                class="inline-flex items-center justify-center gap-2 px-5 py-2 bg-red-600 hover:bg-red-700 text-white font-semibold text-sm rounded-lg shadow transition">
                                <i class="fas fa-trash-alt"></i> Hapus
                            </button>
                        </div>
                    @endif
                @endauth
            </div>
        @empty
            <div class="col-span-full text-center text-gray-400 italic">
                Tidak ada data program kerja.
            </div>
        @endforelse
    </div>

    <script>
        function confirmDelete(id, judul) {
            Swal.fire({
                title: 'Hapus Data?',
                text: `Yakin ingin menghapus program kerja "${judul}"?`,
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
