@extends('layouts.app')

@section('content')

    <link href="https://cdn.jsdelivr.net/npm/sweetalert2@11.0.15/dist/sweetalert2.min.css" rel="stylesheet">

    @auth
        @if (auth()->user()->role_id == 1)
            <div class="d-flex justify-content-between align-items-center mb-4">
                <h1 class="h3 text-gray-800">Jadwal program kerja</h1>
                <a href="/jadwal/create" class="btn btn-success shadow-sm position-relative">
                    <i class="fas fa-plus fa-sm text-white-50"></i> Buat jadwal program kerja
                    @if($belumDijadwalCount > 0)
                        <span class="position-absolute top-0 start-100 translate-middle badge rounded-pill bg-warning text-dark">
                            {{ $belumDijadwalCount }}
                        </span>
                    @endif
                </a>                
            </div>
        @endif
    @endauth

    <script src="https://cdn.jsdelivr.net/npm/sweetalert2@11.0.15/dist/sweetalert2.all.min.js"></script>

    @if(session('success'))
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

    <form method="GET" class="d-flex justify-content-center mb-4">
        <select name="status" id="statusSelect" class="custom-dropdown" onchange="this.form.submit()">
            <option value="">-- Semua Status --</option>
            @foreach (['penjadwalan', 'berjalan', 'selesai'] as $r)
                <option value="{{ $r }}" {{ request('status') == $r ? 'selected' : '' }}>
                    {{ ucfirst($r) }}
                </option>
            @endforeach
        </select>
    </form>

    <div class="card shadow-sm border-0 mb-4 rounded-4">
        <div class="card-body">
            <div class="table-responsive">
                <table class="table table-hover align-middle text-center">
                    <thead class="table-light">
                        <tr>
                            <th>Penanggung jawab</th>
                            <th>Program Kerja</th>
                            <th>Mulai</th>
                            <th>Selesai</th>
                            <th>Status</th>
                            <th>Catatan</th>
                            @auth
                                @if (auth()->user()->role_id == 1)
                                    <th>Aksi</th>
                                @endif
                            @endauth
                        </tr>
                    </thead>
                    <tbody>
                        @forelse ($jadwals as $item)
                            <tr>
                                <td>{{ $item->penanggungJawab->name }}</td>
                                <td>{{ $item->proker->program }}</td>
                                <td>{{ \Carbon\Carbon::parse($item->tanggal_mulai)->format('d-m-Y') }}</td>
                                <td>{{ \Carbon\Carbon::parse($item->tanggal_selesai)->format('d-m-Y') }}</td>
                                <td>{{ $item->status }}</td>
                                <td>{{ $item->catatan }}</td>
                                @auth
                                    @if (auth()->user()->role_id == 1)
                                        <td>
                                            <div class="d-flex justify-content-center gap-2">
                                                <a href="/jadwal/{{ $item->id }}" class="btn btn-warning btn-sm rounded-pill shadow-sm"
                                                    title="Edit">
                                                    <i class="fas fa-edit"></i>
                                                </a>
                                                <button type="button" class="btn btn-danger btn-sm rounded-pill shadow-sm" title="Hapus"
                                                    data-bs-toggle="modal" data-bs-target="#confirmationDelete-{{ $item->id }}">
                                                    <i class="fas fa-trash-alt"></i>
                                                </button>
                                            </div>
                                        </td>
                                    @endif
                                @endauth
                            </tr>

                            <!-- Modal Konfirmasi -->
                            <div class="modal fade" id="confirmationDelete-{{ $item->id }}" tabindex="-1"
                                aria-labelledby="deleteLabel{{ $item->id }}" aria-hidden="true">
                                <div class="modal-dialog modal-dialog-centered">
                                    <div class="modal-content border-0 rounded-3">
                                        <div class="modal-header bg-danger text-white">
                                            <h5 class="modal-title" id="deleteLabel{{ $item->id }}">Konfirmasi Hapus</h5>
                                            <button type="button" class="btn-close btn-close-white" data-bs-dismiss="modal"
                                                aria-label="Close"></button>
                                        </div>
                                        <div class="modal-body text-center">
                                            <p class="mb-0">Yakin ingin menghapus data <strong>{{ $item->program }}</strong>?
                                            </p>
                                        </div>
                                        <div class="modal-footer justify-content-center">
                                            <form action="/jadwal/{{ $item->id }}" method="POST">
                                                @csrf
                                                @method('DELETE')
                                                <button type="submit" class="btn btn-danger">Ya, Hapus</button>
                                            </form>
                                            <button type="button" class="btn btn-secondary"
                                                data-bs-dismiss="modal">Batal</button>
                                        </div>
                                    </div>
                                </div>
                            </div>
                        @empty
                            <tr>
                                <td colspan="7" class="text-center py-3 text-muted">Tidak ada jadwal program kerja</td>
                            </tr>
                        @endforelse
                    </tbody>
                </table>
            </div>
        </div>
    </div>

    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/js/bootstrap.bundle.min.js"></script>

@endsection