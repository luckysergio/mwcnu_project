@extends('layouts.app')

@section('content')

    @auth
        @if (auth()->user()->role_id == 1)
            <div class="d-flex justify-content-center align-items-center mb-4">
                <h1 class="h3 text-gray-800">Data Pengajuan program kerja</h1>
            </div>
        @endif
    @endauth

    <div class="card shadow-sm border-0 mb-4 rounded-4">
        <div class="card-body">
            <div class="table-responsive">
                <table class="table table-hover align-middle text-center">
                    <thead class="table-light">
                        <tr>
                            <th>Mengajukan</th>
                            <th>Program kerja</th>
                            <th>Catatan</th>
                            @auth
                                @if (auth()->user()->role_id == 1)
                                    <th>Aksi</th>
                                @endif
                            @endauth
                        </tr>
                    </thead>
                    <tbody>
                        @forelse ($prokers as $item)
                            <tr>
                                <td>{{ $item->user->name }}</td>
                                <td>{{ $item->program}}</td>
                                <td>{{ $item->catatan }}</td>
                                <td>
                                    <div class="d-flex justify-content-center" style="gap: 10px;">
                                        <button type="button" class="btn btn-danger btn-sm rounded-pill shadow-sm" title="Tolak"
                                            data-bs-toggle="modal" data-bs-target="#confirmationDelete-{{ $item->id }}">
                                            Tolak
                                        </button>
                                        <button type="button" class="btn btn-success btn-sm rounded-pill shadow-sm"
                                            title="Setuju" data-bs-toggle="modal"
                                            data-bs-target="#confirmationApprove-{{ $item->id }}">
                                            Setuju
                                        </button>
                                    </div>
                                </td>
                            </tr>

                            <div class="modal fade" id="confirmationDelete-{{ $item->id }}" tabindex="-1"
                                aria-labelledby="deleteLabel{{ $item->id }}" aria-hidden="true">
                                <div class="modal-dialog modal-dialog-centered">
                                    <div class="modal-content border-0 rounded-3">
                                        <div class="modal-header bg-danger text-white">
                                            <h5 class="modal-title" id="deleteLabel{{ $item->id }}">Konfirmasi Tolak</h5>
                                            <button type="button" class="btn-close btn-close-white" data-bs-dismiss="modal" aria-label="Close"></button>
                                        </div>
                                        <form action="{{ url('/proker-request/approval/' . $item->id) }}" method="POST">
                                            @csrf
                                            <div class="modal-body text-center">
                                                <input type="hidden" name="for" value="reject">
                                                <p class="mb-0">Yakin ingin menolak <strong>{{ $item->program }}</strong>?</p>
                                            </div>
                                            <div class="modal-footer justify-content-center">
                                                <button type="submit" class="btn btn-danger">Ya, tolak</button>
                                                <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Batal</button>
                                            </div>
                                        </form>
                                    </div>
                                </div>
                            </div>                            

                            <div class="modal fade" id="confirmationApprove-{{ $item->id }}" tabindex="-1"
                                aria-labelledby="approveLabel{{ $item->id }}" aria-hidden="true">
                                <div class="modal-dialog modal-dialog-centered">
                                    <div class="modal-content border-0 rounded-3">
                                        <div class="modal-header bg-success text-white">
                                            <h5 class="modal-title" id="approveLabel{{ $item->id }}">Konfirmasi Setuju</h5>
                                            <button type="button" class="btn-close btn-close-white" data-bs-dismiss="modal"
                                                aria-label="Close"></button>
                                        </div>
                                        <form action="/proker-request/approval/{{ $item->id }}" method="POST">
                                            @csrf
                                            @method('POST')
                                            <div class="modal-body text-center">
                                                <input type="hidden" name="for" value="approve">
                                                <p class="mb-0">Yakin ingin menyetujui <strong>{{ $item->program }}</strong>?</p>
                                            </div>
                                            <div class="modal-footer justify-content-center">
                                                <button type="submit" class="btn btn-success">Ya, Setuju</button>
                                                <button type="button" class="btn btn-secondary"
                                                    data-bs-dismiss="modal">Batal</button>
                                            </div>
                                        </form>
                                    </div>
                                </div>
                            </div>
                        @empty
                            <tr>
                                <td colspan="7" class="text-center py-3 text-muted">Tidak ada data pengajuan</td>
                            </tr>
                        @endforelse
                    </tbody>
                </table>
            </div>
        </div>
    </div>

    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/js/bootstrap.bundle.min.js"></script>

@endsection