@extends('layouts.app')

@section('content')

<link href="https://cdn.jsdelivr.net/npm/sweetalert2@11.0.15/dist/sweetalert2.min.css" rel="stylesheet">

<div class="d-flex justify-content-center align-items-center mb-4">
    <h1 class="h3 text-gray-800">Permintaan Akun</h1>
</div>

<style>
    .program-card {
        transition: transform 0.2s ease, box-shadow 0.2s ease;
    }

    .program-card:hover {
        transform: translateY(-4px);
        box-shadow: 0 8px 20px rgba(0, 0, 0, 0.1);
    }

    .btn-action {
        min-width: 80px;
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
                window.location.href = "/account-request";
            }
        });
    </script>
@endif

<div class="container mt-4">
    <div class="row justify-content-center">
        @forelse ($users as $item)
            <div class="col-md-6 col-lg-4 mb-4">
                <div class="card program-card border-0 rounded-4 shadow-sm h-100">
                    <div class="card-body d-flex flex-column justify-content-between">
                        <div>
                            <h5 class="fw-bold mb-2">{{ $item->name }}</h5>
                            <p class="mb-1 text-muted"><strong>Email:</strong> {{ $item->email }}</p>
                        </div>
                        <div class="d-flex justify-content-between align-items-center mt-4">
                            <button type="button" class="btn btn-outline-danger btn-sm btn-action"
                                data-bs-toggle="modal" data-bs-target="#confirmationDelete-{{ $item->id }}">
                                Tolak
                            </button>
                            <div class="flex-grow-1"></div>
                            <button type="button" class="btn btn-success btn-sm btn-action"
                                data-bs-toggle="modal" data-bs-target="#confirmationApprove-{{ $item->id }}">
                                Setuju
                            </button>
                        </div>
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
                        <form action="/account-request/approval/{{ $item->id }}" method="POST">
                            @csrf
                            <input type="hidden" name="for" value="reject">
                            <div class="modal-body text-center">
                                <p>Yakin ingin menolak <strong>{{ $item->name }}</strong>?</p>
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
                        <form action="/account-request/approval/{{ $item->id }}" method="POST">
                            @csrf
                            <input type="hidden" name="for" value="approve">
                            <div class="modal-body text-center">
                                <p>Yakin ingin menyetujui <strong>{{ $item->name }}</strong>?</p>
                                <div class="form-group mt-3">
                                    <label for="anggota_id_{{ $item->id }}">Pilih data anggota</label>
                                    <select name="anggota_id" id="anggota_id_{{ $item->id }}" class="form-control">
                                        <option value="">Tidak ada</option>
                                        @foreach ($anggotas as $anggota)
                                            <option value="{{ $anggota->id }}">{{ $anggota->name }}</option>
                                        @endforeach
                                    </select>
                                </div>
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
                <div class="alert alert-info">Tidak ada permintaan akun</div>
            </div>
        @endforelse
    </div>
</div>

<script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/js/bootstrap.bundle.min.js"></script>

@endsection