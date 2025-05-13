@extends('layouts.app')

@section('content')

    <link href="https://cdn.jsdelivr.net/npm/sweetalert2@11.0.15/dist/sweetalert2.min.css" rel="stylesheet">

    @auth
        @if (auth()->user()->role_id == 1)
            <div class="d-flex justify-content-center align-items-center mb-4">
                <h1 class="h3 text-gray-800">Data Pengajuan program kerja</h1>
            </div>
        @endif
    @endauth

    <style>
        .program-card {
            transition: transform 0.2s ease, box-shadow 0.2s ease;
        }

        .program-card:hover {
            transform: translateY(-4px);
            box-shadow: 0 8px 20px rgba(0, 0, 0, 0.1);
        }

        .badge-status {
            font-size: 0.75rem;
            padding: 0.35em 0.6em;
            border-radius: 0.5rem;
        }

        .btn-action {
            min-width: 80px;
        }

        .card-action-column {
            position: absolute;
            right: 1rem;
            bottom: 1rem;
            display: flex;
            flex-direction: column;
            gap: 0.5rem;
        }

        .card-body-relative {
            position: relative;
        }
    </style>

    <script src="https://cdn.jsdelivr.net/npm/sweetalert2@11.0.15/dist/sweetalert2.all.min.js"></script>

    @if(session('success'))
        <script>
            Swal.fire({
                icon: 'success',
                title: 'Berhasil!',
                text: @json(session('success')),
                didClose: () => {
                    window.location.href = "/proker-request";
                }
            });
        </script>
    @endif

    <div class="container mt-4">
        <div class="row justify-content-center">
            @forelse ($prokers as $item)
                <div class="col-md-6 col-lg-4 mb-4">
                    <div class="card program-card border-0 rounded-4 shadow-sm h-100">
                        <div class="card-body d-flex flex-column justify-content-between">
                            <div>
                                <h5 class="fw-bold mb-2">{{ $item->program }}</h5>
                                <div class="mt-3">
                                    <p class="mb-1 text-muted"><strong>Mengajukan:</strong> {{ $item->user->name }}</p>
                                    <p class="mb-0"><strong>Catatan:</strong> {{ $item->catatan ?: '-' }}</p>
                                </div>
                            </div>
                            @auth
                                @if (auth()->user()->role_id == 1 && $item->status === 'pengajuan')
                                    <div class="d-flex justify-content-between align-items-center mt-4">
                                        <button type="button" class="btn btn-outline-danger btn-sm btn-action" data-bs-toggle="modal"
                                            data-bs-target="#confirmationDelete-{{ $item->id }}">
                                            Tolak
                                        </button>
                                        <div class="flex-grow-1"></div>
                                        <button type="button" class="btn btn-success btn-sm btn-action" data-bs-toggle="modal"
                                            data-bs-target="#confirmationApprove-{{ $item->id }}">
                                            Setuju
                                        </button>
                                    </div>
                                @endif
                            @endauth
                        </div>
                    </div>
                </div>
                <div class="modal fade" id="confirmationDelete-{{ $item->id }}" tabindex="-1"
                    aria-labelledby="deleteLabel{{ $item->id }}" aria-hidden="true">
                    <div class="modal-dialog modal-dialog-centered">
                        <div class="modal-content border-0 rounded-3">
                            <div class="modal-header bg-danger text-white">
                                <h5 class="modal-title">Konfirmasi Tolak</h5>
                                <button type="button" class="btn-close btn-close-white" data-bs-dismiss="modal"
                                    aria-label="Close"></button>
                            </div>
                            <form action="{{ url('/proker-request/approval/' . $item->id) }}" method="POST">
                                @csrf
                                <input type="hidden" name="for" value="reject">
                                <div class="modal-body text-center">
                                    <p>Yakin ingin menolak program kerja <strong>{{ $item->program }}</strong>?</p>
                                </div>
                                <div class="modal-footer justify-content-center">
                                    <button type="submit" class="btn btn-danger">Ya, Tolak</button>
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
                                <h5 class="modal-title">Konfirmasi Setuju</h5>
                                <button type="button" class="btn-close btn-close-white" data-bs-dismiss="modal"
                                    aria-label="Close"></button>
                            </div>
                            <form action="{{ url('/proker-request/approval/' . $item->id) }}" method="POST">
                                @csrf
                                <input type="hidden" name="for" value="approve">
                                <div class="modal-body text-center">
                                    <p>Yakin ingin menyetujui Program kerja <strong>{{ $item->program }}</strong>?</p>
                                </div>
                                <div class="modal-footer justify-content-center">
                                    <button type="submit" class="btn btn-success">Ya, Setuju</button>
                                    <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Batal</button>
                                </div>
                            </form>
                        </div>
                    </div>
                </div>
            @empty
                <div class="col-12 text-center mt-5">
                    <div class="alert alert-info">Tidak ada data pengajuan</div>
                </div>
            @endforelse
        </div>
    </div>

    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/js/bootstrap.bundle.min.js"></script>

@endsection