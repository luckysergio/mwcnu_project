@extends('layouts.app')

@section('content')
    <style>
        .dashboard-card {
            border-radius: 16px;
            background: linear-gradient(135deg, #ffffff, #f9f9f9);
            box-shadow: 0 4px 20px rgba(0, 0, 0, 0.04);
            transition: all 0.3s ease-in-out;
            padding: 20px;
        }

        .dashboard-card:hover {
            transform: translateY(-5px);
            box-shadow: 0 12px 24px rgba(0, 0, 0, 0.08);
        }

        .badge-status {
            font-size: 0.75rem;
            padding: 0.4em 0.8em;
            border-radius: 30px;
            font-weight: 500;
        }

        .badge-penjadwalan {
            background-color: #ffb74d;
        }

        .badge-berjalan {
            background-color: #4caf50;
        }

        .badge-selesai {
            background-color: #9e9e9e;
        }

        input.form-control[readonly] {
            pointer-events: none;
            box-shadow: none;
            font-size: 1.25rem;
            color: #333;
            background: #f1f3f5;
            border-radius: 8px;
        }

        .section-title {
            font-weight: 700;
            color: #0d6efd;
            font-size: 1.5rem;
            margin-bottom: 1.5rem;
        }

        .list-unstyled i {
            width: 20px;
        }

        h6 {
            font-size: 1.1rem;
        }
    </style>

    <div class="container">
        <div class="text-center mb-5">
            <h2 class="fw-bold">Monitoring program kerja MWCNU Karang Tengah</h2>
        </div>

        <div class="text-center my-5">
            <input type="text" class="form-control text-center section-title" value="PROGRAM KERJA YANG TERJADWAL" readonly>
        </div>
        <div class="row">
            @forelse ($penjadwalan as $item)
                <div class="col-md-4 mb-4">
                    <div class="card dashboard-card border-0 shadow-sm p-3 rounded-4">
                        <div class="card-body p-0">
                            <div class="d-flex justify-content-between align-items-start mb-3">
                                <h6 class="mb-0 fw-semibold text-dark">{{ $item->proker->program ?? '-' }}</h6>
                                <span class="badge badge-status badge-penjadwalan">
                                    {{ ucfirst($item->status) }}
                                </span>
                            </div>
                            <ul class="list-unstyled small text-secondary mb-0">
                                <li class="mb-1">
                                    <i class="fas fa-calendar-alt me-2 text-primary"></i>
                                    <strong>Mulai:</strong> {{ \Carbon\Carbon::parse($item->tanggal_mulai)->format('d-m-Y') }}
                                </li>
                                <li class="mb-1">
                                    <i class="fas fa-calendar-check me-2 text-success"></i>
                                    <strong>Selesai:</strong>
                                    {{ \Carbon\Carbon::parse($item->tanggal_selesai)->format('d-m-Y') }}
                                </li>
                                <li>
                                    <i class="fas fa-sticky-note me-2 text-muted"></i>
                                    <strong>Catatan:</strong> {{ $item->catatan ?? '-' }}
                                </li>
                            </ul>
                        </div>
                    </div>
                </div>
            @empty
                <p class="text-muted text-center w-100">Tidak ada Program kerja</p>
            @endforelse
        </div>

        <div class="text-center my-5">
            <input type="text" class="form-control text-center section-title" value="PROGRAM KERJA YANG SEDANG BERJALAN"
                readonly>
        </div>
        <div class="row">
            @forelse ($berjalan as $item)
                <div class="col-md-4 mb-4">
                    <div class="card dashboard-card border-0 shadow-sm p-3 rounded-4">
                        <div class="card-body p-0">
                            <div class="d-flex justify-content-between align-items-start mb-3">
                                <h6 class="mb-0 fw-semibold text-dark">{{ $item->proker->program ?? '-' }}</h6>
                                <span class="badge badge-status badge-penjadwalan">
                                    {{ ucfirst($item->status) }}
                                </span>
                            </div>
                            <ul class="list-unstyled small text-secondary mb-0">
                                <li class="mb-1">
                                    <i class="fas fa-calendar-alt me-2 text-primary"></i>
                                    <strong>Mulai:</strong> {{ \Carbon\Carbon::parse($item->tanggal_mulai)->format('d-m-Y') }}
                                </li>
                                <li class="mb-1">
                                    <i class="fas fa-calendar-check me-2 text-success"></i>
                                    <strong>Selesai:</strong>
                                    {{ \Carbon\Carbon::parse($item->tanggal_selesai)->format('d-m-Y') }}
                                </li>
                                <li>
                                    <i class="fas fa-sticky-note me-2 text-muted"></i>
                                    <strong>Catatan:</strong> {{ $item->catatan ?? '-' }}
                                </li>
                            </ul>
                        </div>
                    </div>
                </div>
            @empty
                <p class="text-muted text-center w-100">Tidak ada Program kerja</p>
            @endforelse
        </div>

        <div class="text-center my-5">
            <input type="text" class="form-control text-center section-title"
                value="PROGRAM KERJA YANG SUDAH SELESAI DILAKSANAKAN" readonly>
        </div>
        <div class="row">
            @forelse ($selesai as $item)
                <div class="col-md-4 mb-4">
                    <div class="card dashboard-card border-0 shadow-sm p-3 rounded-4">
                        <div class="card-body p-0">
                            <div class="d-flex justify-content-between align-items-start mb-3">
                                <h6 class="mb-0 fw-semibold text-dark">{{ $item->proker->program ?? '-' }}</h6>
                                <span class="badge badge-status badge-penjadwalan">
                                    {{ ucfirst($item->status) }}
                                </span>
                            </div>
                            <ul class="list-unstyled small text-secondary mb-0">
                                <li class="mb-1">
                                    <i class="fas fa-calendar-alt me-2 text-primary"></i>
                                    <strong>Mulai:</strong> {{ \Carbon\Carbon::parse($item->tanggal_mulai)->format('d-m-Y') }}
                                </li>
                                <li class="mb-1">
                                    <i class="fas fa-calendar-check me-2 text-success"></i>
                                    <strong>Selesai:</strong>
                                    {{ \Carbon\Carbon::parse($item->tanggal_selesai)->format('d-m-Y') }}
                                </li>
                                <li>
                                    <i class="fas fa-sticky-note me-2 text-muted"></i>
                                    <strong>Catatan:</strong> {{ $item->catatan ?? '-' }}
                                </li>
                            </ul>
                        </div>
                    </div>
                </div>
            @empty
                <p class="text-muted text-center w-100">Tidak ada Program kerja</p>
            @endforelse
        </div>

    </div>
@endsection