@extends('layouts.app')

@section('content')
    <link href="https://cdn.jsdelivr.net/npm/sweetalert2@11.0.15/dist/sweetalert2.min.css" rel="stylesheet">

    @auth
        @php
            $jabatan = auth()->user()->anggota->role->jabatan ?? null;
        @endphp

        @if (in_array($jabatan, ['Admin', 'Tanfidiyah']))
            <div class="container mt-4">

                <div class="d-flex justify-content-center align-items-center mb-4">
                    <h1 class="h3 text-gray-800">Data Pengajuan Program Kerja</h1>
                </div>

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

                <div class="row justify-content-center">

                    @forelse ($prokers as $item)
                        @php
                            $statusPengaju = $item->anggota->status->status ?? null;
                        @endphp

                        <div class="col-md-6 col-lg-4 mb-4">
                            <div class="card border-0 rounded-4 shadow-sm h-100">
                                <div class="card-body d-flex flex-column justify-content-between">

                                    <div>
                                        <h5 class="fw-bold mb-2 text-center">{{ $item->judul }}</h5>

                                        <p class="mb-1 text-muted">
                                            <strong>Pengaju:</strong> {{ $item->anggota->name ?? '-' }}
                                        </p>

                                        <p class="mb-1">
                                            <strong>Ranting:</strong> {{ $item->ranting->kelurahan ?? '-' }}
                                        </p>

                                        <p class="mb-1"><strong>Bidang:</strong> {{ $item->bidang->nama }}</p>
                                        <p class="mb-1"><strong>Jenis:</strong> {{ $item->jenis->nama }}</p>
                                        <p class="mb-1"><strong>Tujuan:</strong> {{ $item->tujuan->nama }}</p>
                                        <p class="mb-1"><strong>Sasaran:</strong> {{ $item->sasaran->nama }}</p>

                                        <p class="mb-1">
                                            <strong>Proposal:</strong>
                                            <a href="{{ asset('storage/' . $item->proposal) }}" target="_blank"
                                                class="text-primary text-decoration-underline">
                                                Lihat PDF
                                            </a>
                                        </p>

                                        <p class="mb-1">
                                            <strong>Keterangan:</strong>

                                            @if ($statusPengaju === 'MWC')
                                                Program Kerja MWC
                                            @elseif ($statusPengaju === 'Ranting')
                                                Program Kerja Ranting
                                            @else
                                                {{ $item->keterangan ?: '-' }}
                                            @endif
                                        </p>

                                        @if ($item->keterangan)
                                            <small class="text-muted">
                                                Catatan: {{ $item->keterangan }}
                                            </small>
                                        @endif

                                        <hr class="my-2">

                                        @if ($item->jadwalProker)
                                            <p class="mb-1">
                                                <strong>Estimasi Mulai:</strong>
                                                {{ \Carbon\Carbon::parse($item->jadwalProker->estimasi_mulai)->format('d M Y') }}
                                            </p>

                                            <p class="mb-1">
                                                <strong>Estimasi Selesai:</strong>
                                                {{ \Carbon\Carbon::parse($item->jadwalProker->estimasi_selesai)->format('d M Y') }}
                                            </p>
                                        @else
                                            <p class="mb-1 text-warning">
                                                <strong>Estimasi:</strong> Belum ditentukan
                                            </p>
                                        @endif

                                        <p class="mb-0">
                                            <strong>Status:</strong>
                                            <span class="text-warning text-capitalize">
                                                {{ $item->status }}
                                            </span>
                                        </p>
                                    </div>

                                    @if ($item->status === 'pengajuan')
                                        <div class="d-flex justify-content-between align-items-center gap-2 mt-4">
                                            <button type="button" class="btn btn-outline-danger btn-sm w-50 me-1"
                                                data-bs-toggle="modal"
                                                data-bs-target="#confirmationReject-{{ $item->id }}">
                                                Tolak
                                            </button>

                                            <button type="button" class="btn btn-success btn-sm w-50 ms-1"
                                                data-bs-toggle="modal"
                                                data-bs-target="#confirmationApprove-{{ $item->id }}">
                                                Setujui
                                            </button>
                                        </div>
                                    @endif

                                </div>
                            </div>
                        </div>

                        {{-- MODAL REJECT --}}
                        <div class="modal fade" id="confirmationReject-{{ $item->id }}" tabindex="-1">
                            <div class="modal-dialog modal-dialog-centered">
                                <div class="modal-content border-0 rounded-3">
                                    <div class="modal-header bg-danger text-white">
                                        <h5 class="modal-title">Konfirmasi Tolak</h5>
                                        <button type="button" class="btn-close btn-close-white"
                                            data-bs-dismiss="modal"></button>
                                    </div>
                                    <form action="{{ route('proker.approval', $item->id) }}" method="POST">
                                        @csrf
                                        <input type="hidden" name="for" value="reject">
                                        <div class="modal-body text-center">
                                            <p>
                                                Yakin ingin <strong>menolak</strong> program kerja
                                                <br><strong>{{ $item->judul }}</strong>?
                                            </p>
                                        </div>
                                        <div class="modal-footer justify-content-center">
                                            <button type="submit" class="btn btn-danger">Ya, Tolak</button>
                                            <button type="button" class="btn btn-secondary"
                                                data-bs-dismiss="modal">Batal</button>
                                        </div>
                                    </form>
                                </div>
                            </div>
                        </div>

                        {{-- MODAL APPROVE --}}
                        <div class="modal fade" id="confirmationApprove-{{ $item->id }}" tabindex="-1">
                            <div class="modal-dialog modal-dialog-centered">
                                <div class="modal-content border-0 rounded-3">
                                    <div class="modal-header bg-success text-white">
                                        <h5 class="modal-title">Konfirmasi Setujui</h5>
                                        <button type="button" class="btn-close btn-close-white"
                                            data-bs-dismiss="modal"></button>
                                    </div>
                                    <form action="{{ route('proker.approval', $item->id) }}" method="POST">
                                        @csrf
                                        <input type="hidden" name="for" value="approve">
                                        <div class="modal-body text-center">
                                            <p>
                                                Yakin ingin <strong>menyetujui</strong> program kerja
                                                <br><strong>{{ $item->judul }}</strong>?
                                            </p>
                                        </div>
                                        <div class="modal-footer justify-content-center">
                                            <button type="submit" class="btn btn-success">Ya, Setujui</button>
                                            <button type="button" class="btn btn-secondary"
                                                data-bs-dismiss="modal">Batal</button>
                                        </div>
                                    </form>
                                </div>
                            </div>
                        </div>

                    @empty
                        <div class="col-12 text-center mt-5">
                            <div class="alert alert-info">
                                Tidak ada data pengajuan program kerja.
                            </div>
                        </div>
                    @endforelse

                </div>

                {{-- PAGINATION --}}
                <div class="d-flex justify-content-center mt-4">
                    {{ $prokers->links() }}
                </div>

            </div>
        @endif
    @endauth
@endsection
