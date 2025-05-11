@extends('layouts.app')

@section('content')

    <div class="d-flex justify-content-between align-items-center mb-4">
        <h1 class="h3 text-gray-800">Data Anggota</h1>
        <a href="/anggota/create" class="btn btn-success shadow-sm">
            <i class="fas fa-plus fa-sm text-white-50"></i> Tambah Anggota
        </a>
    </div>

    <div class="card shadow-sm border-0 mb-4 rounded-4">
        <div class="card-body">
            <div class="table-responsive">
                <table class="table table-hover align-middle text-center">
                    <thead class="table-light">
                        <tr>
                            <th>Nama</th>
                            <th>Email</th>
                            <th>HP</th>
                            <th>Jabatan</th>
                            <th>Ranting</th>
                            <th>Status</th>
                            <th>Aksi</th>
                        </tr>
                    </thead>
                    <tbody>
                        @forelse ($anggotas as $item)
                            <tr>
                                <td>{{ $item->name }}</td>
                                <td>{{ $item->email }}</td>
                                <td>{{ $item->phone }}</td>
                                <td>{{ $item->jabatan }}</td>
                                <td>{{ $item->ranting }}</td>
                                <td>
                                    <span class="badge text-capitalize px-3 py-2 {{ $item->status == 'active' ? 'bg-success' : 'bg-danger' }} text-white rounded-pill">
                                        {{ $item->status }}
                                    </span>
                                </td>
                                <td>
                                    <div class="d-flex justify-content-center gap-2">
                                        <a href="/anggota/{{ $item->id }}" class="btn btn-warning btn-sm rounded-pill shadow-sm" title="Edit">
                                            <i class="fas fa-edit"></i>
                                        </a>
                                        <button type="button" class="btn btn-danger btn-sm rounded-pill shadow-sm" title="Hapus"
                                            data-bs-toggle="modal" data-bs-target="#confirmationDelete-{{ $item->id }}">
                                            <i class="fas fa-trash-alt"></i>
                                        </button>
                                    </div>
                                </td>
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
                                            <p class="mb-0">Yakin ingin menghapus data <strong>{{ $item->name }}</strong>?</p>
                                        </div>
                                        <div class="modal-footer justify-content-center">
                                            <form action="/anggota/{{ $item->id }}" method="POST">
                                                @csrf
                                                @method('DELETE')
                                                <button type="submit" class="btn btn-danger">Ya, Hapus</button>
                                            </form>
                                            <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Batal</button>
                                        </div>
                                    </div>
                                </div>
                            </div>
                        @empty
                            <tr>
                                <td colspan="7" class="text-center py-3 text-muted">Tidak ada data anggota.</td>
                            </tr>
                        @endforelse
                    </tbody>
                </table>
            </div>
        </div>
    </div>

    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/js/bootstrap.bundle.min.js"></script>

@endsection
