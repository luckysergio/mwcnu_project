@extends('layouts.app')

@section('content')
    <link href="https://cdn.jsdelivr.net/npm/sweetalert2@11.0.15/dist/sweetalert2.min.css" rel="stylesheet">

    @auth
        @php
            $jabatan = auth()->user()->anggota->role->jabatan ?? null;
        @endphp
        @if (in_array($jabatan, ['Admin', 'Tanfidiyah']))
            <div class="d-flex justify-content-center align-items-center mb-4">
                <h1 class="h3 text-gray-800">Data Pengajuan Program Kerja</h1>
            </div>
        @endif
    @endauth

    <script src="https://cdn.jsdelivr.net/npm/sweetalert2@11.0.15/dist/sweetalert2.all.min.js"></script>
    @if (session('success'))
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
                        <div class="card-body d-flex flex-column justify-content-between card-body-relative">
                            <div>
                                <h5 class="fw-bold mb-2">{{ $item->judul }}</h5>
                                <div class="mt-3">
                                    <p class="mb-1 text-muted">
                                        <strong>Mengajukan:</strong> {{ $item->anggota->name }}
                                    </p>
                                    <p class="mb-1"><strong>Ranting:</strong> {{ $item->ranting->kelurahan ?? '-' }}</p>
                                    <p class="mb-1"><strong>Bidang:</strong> {{ $item->bidang->nama }}</p>
                                    <p class="mb-1"><strong>Jenis Kegiatan:</strong> {{ $item->jenis->nama }}</p>
                                    <p class="mb-1"><strong>Tujuan:</strong> {{ $item->tujuan->nama }}</p>
                                    <p class="mb-1"><strong>Sasaran:</strong> {{ $item->sasaran->nama }}</p>
                                    <p class="mb-1"><strong>Proposal:</strong>
                                        <a href="{{ asset('storage/' . $item->proposal) }}" target="_blank"
                                            class="text-primary text-decoration-underline">
                                            Lihat PDF
                                        </a>
                                    </p>
                                    <p class="mb-0"><strong>Catatan:</strong> {{ $item->keterangan ?: '-' }}</p>
                                </div>
                            </div>

                            @if ($item->status === 'pengajuan')
                                <div class="d-flex justify-content-between align-items-center mt-4">
                                    <button type="button" class="btn btn-outline-danger btn-sm" data-bs-toggle="modal"
                                        data-bs-target="#confirmationReject-{{ $item->id }}">
                                        Tolak
                                    </button>
                                    <div class="flex-grow-1"></div>
                                    <button type="button" class="btn btn-success btn-sm" data-bs-toggle="modal"
                                        data-bs-target="#confirmationApprove-{{ $item->id }}">
                                        Setujui
                                    </button>
                                </div>
                            @endif
                        </div>
                    </div>
                </div>

                {{-- Modal Tolak --}}
                <div class="modal fade" id="confirmationReject-{{ $item->id }}" tabindex="-1" aria-hidden="true">
                    <div class="modal-dialog modal-dialog-centered">
                        <div class="modal-content border-0 rounded-3">
                            <div class="modal-header bg-danger text-white">
                                <h5 class="modal-title">Konfirmasi Tolak</h5>
                                <button type="button" class="btn-close btn-close-white" data-bs-dismiss="modal"
                                    aria-label="Close"></button>
                            </div>
                            <form action="{{ route('proker.approval', $item->id) }}" method="POST">
                                @csrf
                                <input type="hidden" name="for" value="reject">
                                <div class="modal-body text-center">
                                    <p>Yakin ingin <strong>menolak</strong> program kerja
                                        <br><strong>{{ $item->judul }}</strong>?
                                    </p>
                                </div>
                                <div class="modal-footer justify-content-center">
                                    <button type="submit" class="btn btn-danger">Ya, Tolak</button>
                                    <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Batal</button>
                                </div>
                            </form>
                        </div>
                    </div>
                </div>

                {{-- Modal Setujui --}}
                <div class="modal fade" id="confirmationApprove-{{ $item->id }}" tabindex="-1" aria-hidden="true">
                    <div class="modal-dialog modal-dialog-centered">
                        <div class="modal-content border-0 rounded-3">
                            <div class="modal-header bg-success text-white">
                                <h5 class="modal-title">Konfirmasi Setujui</h5>
                                <button type="button" class="btn-close btn-close-white" data-bs-dismiss="modal"
                                    aria-label="Close"></button>
                            </div>
                            <form action="{{ route('proker.approval', $item->id) }}" method="POST">
                                @csrf
                                <input type="hidden" name="for" value="approve">
                                <div class="modal-body text-center">
                                    <p>Yakin ingin <strong>menyetujui</strong> program kerja
                                        <br><strong>{{ $item->judul }}</strong>?
                                    </p>
                                </div>
                                <div class="modal-footer justify-content-center">
                                    <button type="submit" class="btn btn-success">Ya, Setujui</button>
                                    <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Batal</button>
                                </div>
                            </form>
                        </div>
                    </div>
                </div>
            @empty
                <div class="col-12 text-center mt-5">
                    <div class="alert alert-info">Tidak ada data pengajuan program kerja.</div>
                </div>
            @endforelse
        </div>
    </div>

    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/js/bootstrap.bundle.min.js"></script>
@endsection
