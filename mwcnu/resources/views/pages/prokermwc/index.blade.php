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

                            {{-- Isi card --}}
                            <div>
                                <h5 class="fw-bold mb-2">{{ $item->judul }}</h5>

                                <p class="mb-1 text-muted"><strong>Dibuat Oleh:</strong> {{ $item->anggota->name }}</p>
                                <p class="mb-1"><strong>Bidang:</strong> {{ $item->bidang->nama }}</p>
                                <p class="mb-1"><strong>Jenis:</strong> {{ $item->jenis->nama }}</p>
                                <p class="mb-1"><strong>Tujuan:</strong> {{ $item->tujuan->nama }}</p>
                                <p class="mb-1"><strong>Sasaran:</strong> {{ $item->sasaran->nama }}</p>

                                {{-- Info ranting yang memilih --}}
                                @if ($item->ranting_id)
                                    <p class="mb-1 text-success fw-bold">
                                        Dipilih oleh Ranting: {{ $item->ranting->kelurahan }}
                                    </p>
                                @else
                                    <p class="mb-1 text-danger">
                                        Belum dipilih ranting mana pun
                                    </p>
                                @endif

                                <p class="mb-1"><strong>Proposal:</strong>
                                    <a href="{{ asset('storage/' . $item->proposal) }}" target="_blank"
                                        class="text-primary text-decoration-underline">
                                        Lihat PDF
                                    </a>
                                </p>
                                <p class="mb-0"><strong>Status:</strong> {{ ucfirst($item->status) }}</p>
                            </div>

                            <div class="mt-4">

                                @if ($jabatan === 'MWC')
                                    <a href="{{ route('proker-mwc.edit', $item->id) }}"
                                        class="btn btn-success btn-sm w-100 rounded-3">
                                        Edit Proker
                                    </a>
                                @endif

                                @if ($jabatan === 'Ranting')
                                    @if ($item->ranting_id === null)
                                        <button class="btn btn-success btn-sm w-100 rounded-3"
                                            onclick="konfirmasiPilih('{{ $item->id }}')">
                                            Pilih Proker
                                        </button>

                                        <form id="formPilih{{ $item->id }}"
                                            action="{{ route('proker-mwc.pilih', $item->id) }}" method="POST"
                                            style="display:none;">
                                            @csrf
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
    </div>

    <script src="https://cdn.jsdelivr.net/npm/sweetalert2@11"></script>

    <script>
        function konfirmasiPilih(id) {
            Swal.fire({
                title: 'Pilih Proker?',
                text: "Apakah Anda yakin memilih proker ini?",
                icon: 'question',
                showCancelButton: true,
                confirmButtonColor: '#28a745',
                cancelButtonColor: '#d33',
                confirmButtonText: 'Ya, Pilih'
            }).then((result) => {
                if (result.isConfirmed) {
                    document.getElementById('formPilih' + id).submit();
                }
            })
        }
    </script>

@endsection
