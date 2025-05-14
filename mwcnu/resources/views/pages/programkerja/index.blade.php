@extends('layouts.app')

@section('content')

    <link href="https://cdn.jsdelivr.net/npm/sweetalert2@11.0.15/dist/sweetalert2.min.css" rel="stylesheet">

    <div class="d-flex justify-content-between align-items-center mb-4">
        <h1 class="h3 text-gray-800">Data Program kerja</h1>
        <a href="/proker/create" class="btn btn-success shadow-sm">
            <i class="fas fa-plus fa-sm text-white-50"></i> Ajukan program kerja
        </a>
    </div>

    <form method="GET" class="d-flex justify-content-center mb-4">
        <select name="status" id="statusSelect" class="custom-dropdown" onchange="this.form.submit()">
            <option value="">-- Semua program kerja --</option>
            @foreach (['pengajuan', 'di setujui', 'di tolak'] as $r)
                <option value="{{ $r }}" {{ request('status') == $r ? 'selected' : '' }}>
                    {{ ucfirst($r) }}
                </option>
            @endforeach
        </select>
    </form>

    <script src="https://cdn.jsdelivr.net/npm/sweetalert2@11.0.15/dist/sweetalert2.all.min.js"></script>

    @if(session('success'))
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

    <div class="card shadow-sm border-0 mb-4 rounded-4">
        <div class="card-body">
            <div class="table-responsive">
                <table class="table table-hover align-middle text-center">
                    <thead class="table-light">
                        <tr>
                            <th>Mengajukan</th>
                            <th>Program kerja</th>
                            <th>Status</th>
                            <th>Catatan</th>
                            @auth
                                @if (auth()->user()->role_id == 1)
                                    <th>Aksi</th>
                                @endif
                            @endauth
                    </thead>
                    <tbody>
                        @forelse ($prokers as $item)
                            <tr>
                                <td>{{ $item->user->name }}</td>
                                <td>{{ $item->program}}</td>
                                <td>{{ $item->status }}</td>
                                <td>{{ $item->catatan }}</td>
                                @auth
                                    @if (auth()->user()->role_id == 1)
                                        <td>
                                            <div class="d-flex justify-content-center gap-2">
                                                <a href="/proker/{{ $item->id }}" class="btn btn-warning btn-sm rounded-pill shadow-sm"
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
                                            <form action="/proker/{{ $item->id }}" method="POST">
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
                                <td colspan="7" class="text-center py-3 text-muted">Tidak ada data program kerja</td>
                            </tr>
                        @endforelse
                    </tbody>
                </table>
            </div>
        </div>
    </div>

    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/js/bootstrap.bundle.min.js"></script>

@endsection