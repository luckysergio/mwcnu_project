@extends('layouts.app')

@section('content')
    <link href="https://cdn.jsdelivr.net/npm/sweetalert2@11/dist/sweetalert2.min.css" rel="stylesheet">

    @php
        $jabatan = auth()->user()->anggota->status->status ?? null;
        $rantingUser = auth()->user()->anggota->ranting_id ?? null;
    @endphp

    <div class="container mt-4">

        {{-- Header --}}
        <div class="d-flex justify-content-between align-items-center mb-4">
            <h1 class="h3 text-gray-800">Program Kerja MWC</h1>

            @if ($jabatan === 'MWC')
                <a href="{{ route('proker-mwc.create') }}" class="btn btn-success rounded-3 shadow-sm">
                    + Buat Proker
                </a>
            @endif
        </div>

        <div class="row justify-content-center">
            @forelse ($prokers as $item)
                <div class="col-md-6 col-lg-4 mb-4">
                    <div class="card border-0 rounded-4 shadow-sm h-100">
                        <div class="card-body d-flex flex-column justify-content-between">

                            <div>
                                <h5 class="fw-bold mb-2 text-center">{{ $item->judul }}</h5>

                                <p class="mb-1 text-muted">
                                    <strong>Dibuat Oleh:</strong> {{ $item->anggota->name ?? '-' }}
                                </p>

                                <p class="mb-1"><strong>Bidang:</strong> {{ $item->bidang->nama ?? '-' }}</p>
                                <p class="mb-1"><strong>Jenis:</strong> {{ $item->jenis->nama ?? '-' }}</p>
                                <p class="mb-1"><strong>Tujuan:</strong> {{ $item->tujuan->nama ?? '-' }}</p>
                                <p class="mb-1"><strong>Sasaran:</strong> {{ $item->sasaran->nama ?? '-' }}</p>

                                <p class="mb-1">
                                    <strong>Proposal:</strong>
                                    @if($item->proposal)
                                        <a href="{{ asset('storage/' . $item->proposal) }}" target="_blank"
                                           class="text-primary text-decoration-underline">
                                            Lihat PDF
                                        </a>
                                    @else
                                        <span class="text-muted">Tidak ada file</span>
                                    @endif
                                </p>

                                @if ($item->ranting_id)
                                    <p class="mb-1 text-success fw-bold">
                                        Dipilih oleh Ranting: {{ $item->ranting->kelurahan ?? '-' }}
                                    </p>
                                @else
                                    <p class="mb-1 text-danger">
                                        Belum dipilih ranting mana pun
                                    </p>
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
                                    <strong>Status Proker:</strong>
                                    @if ($item->ranting_id !== null)
                                        {{ ucfirst($item->status) }}
                                    @else
                                        <span class="text-warning">Menunggu Dipilih Ranting</span>
                                    @endif
                                </p>
                            </div>

                            <div class="mt-4">

                                {{-- MWC --}}
                                @if ($jabatan === 'MWC')
                                    <a href="{{ route('proker-mwc.edit', $item->id) }}"
                                       class="btn btn-success btn-sm w-100 rounded-3">
                                        Edit Proker
                                    </a>
                                @endif

                                {{-- RANTING --}}
                                @if ($jabatan === 'Ranting')

                                    @if ($item->ranting_id === null)
                                        <button class="btn btn-success btn-sm w-100 rounded-3"
                                                onclick="openEstimasi('{{ $item->id }}')">
                                            Pilih Proker
                                        </button>

                                        <form id="formPilih{{ $item->id }}"
                                              action="{{ route('proker-mwc.pilih', $item->id) }}"
                                              method="POST"
                                              style="display:none;">
                                            @csrf
                                            <input type="hidden" name="estimasi_mulai"
                                                   id="estimasi_mulai_{{ $item->id }}">
                                            <input type="hidden" name="estimasi_selesai"
                                                   id="estimasi_selesai_{{ $item->id }}">
                                        </form>
                                    @else
                                        @if ($item->ranting_id === $rantingUser)
                                            <button class="btn btn-secondary btn-sm w-100 rounded-3" disabled>
                                                Anda Sudah Memilih Proker Ini
                                            </button>
                                        @else
                                            <button class="btn btn-secondary btn-sm w-100 rounded-3" disabled>
                                                Sudah Dipilih Ranting Lain
                                            </button>
                                        @endif
                                    @endif

                                @endif

                            </div>

                        </div>
                    </div>
                </div>
            @empty
                <div class="col-12 text-center mt-5">
                    <div class="alert alert-info">Belum ada program kerja MWC.</div>
                </div>
            @endforelse
        </div>

        {{-- ✅ PAGINATION --}}
        @if ($prokers->hasPages())
            <div class="d-flex justify-content-center mt-4">
                {{ $prokers->links('pagination::bootstrap-5') }}
            </div>
        @endif

    </div>

    <script src="https://cdn.jsdelivr.net/npm/sweetalert2@11"></script>

    <script>
        function openEstimasi(id) {
            Swal.fire({
                title: 'Estimasi Waktu Proker',
                html: `
                    <div class="text-start">
                        <label class="mb-1 fw-bold">Estimasi Mulai</label>
                        <input type="date" id="estimasi_mulai_input" class="form-control mb-2">

                        <label class="mb-1 fw-bold">Estimasi Selesai</label>
                        <input type="date" id="estimasi_selesai_input" class="form-control">
                    </div>
                `,
                focusConfirm: false,
                showCancelButton: true,
                confirmButtonText: 'Pilih Proker',
                confirmButtonColor: '#28a745',
                cancelButtonColor: '#d33',

                preConfirm: () => {
                    const mulai = document.getElementById('estimasi_mulai_input').value
                    const selesai = document.getElementById('estimasi_selesai_input').value

                    if (!mulai || !selesai) {
                        Swal.showValidationMessage('Estimasi mulai & selesai harus diisi')
                        return false
                    }

                    if (selesai < mulai) {
                        Swal.showValidationMessage('Tanggal selesai tidak boleh lebih awal dari tanggal mulai')
                        return false
                    }

                    return { mulai, selesai }
                }

            }).then((result) => {
                if (result.isConfirmed) {
                    document.getElementById('estimasi_mulai_' + id).value = result.value.mulai
                    document.getElementById('estimasi_selesai_' + id).value = result.value.selesai
                    document.getElementById('formPilih' + id).submit()
                }
            })
        }
    </script>
@endsection
