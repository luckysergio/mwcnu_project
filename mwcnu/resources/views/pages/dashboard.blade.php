@extends('layouts.app')

@section('content')
<style>
    .dashboard-card {
        border-radius: 12px;
        backdrop-filter: blur(8px);
        background: rgba(255, 255, 255, 0.85);
        transition: transform 0.3s ease, box-shadow 0.3s ease;
    }

    .dashboard-card:hover {
        transform: translateY(-5px);
        box-shadow: 0 10px 20px rgba(0, 0, 0, 0.1);
    }

    .badge-status {
        font-size: 0.75rem;
        padding: 0.25em 0.6em;
        border-radius: 50px;
    }

    .badge-penjadwalan {
        background-color: #f0ad4e;
        color: white;
    }

    .badge-berjalan {
        background-color: #5cb85c;
        color: white;
    }

    .badge-selesai {
        background-color: #6c757d;
        color: white;
    }

    input.form-control[readonly] {
    pointer-events: none;
    box-shadow: none;
    }

    .dashboard-card {
    background-color: #fff;
    border-radius: 16px;
    transition: all 0.2s ease-in-out;
    }

    .dashboard-card:hover {
        transform: translateY(-3px);
        box-shadow: 0 0 10px rgba(0,0,0,0.05);
    }
</style>

<div class="container">
    <div class="text-center mb-5">
        <h2 class="fw-bold">Monitoring program kerja MWCNU Karang Tengah</h2>
    </div>

    <div class="text-center my-5">
        <input type="text" class="form-control text-center fs-5 fw-bold bg-light border-0" value="PROGRAM KERJA YANG TERJADWAL" readonly>
    </div>
    <div class="row">
        @forelse ($penjadwalan as $item)
            <div class="col-md-4 mb-4">
                <div class="card dashboard-card border-0 shadow-sm p-3 rounded-4">
                    <div class="card-body p-0">
                        <div class="d-flex justify-content-between align-items-start mb-3">
                            <h6 class="mb-0 fw-semibold text-dark">{{ $item->proker->program ?? '-' }}</h6>
                            <span class="badge rounded-pill text-white px-3 py-1" style="background-color: #f0ad4e;">
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
                                <strong>Selesai:</strong> {{ \Carbon\Carbon::parse($item->tanggal_selesai)->format('d-m-Y') }}
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
        <input type="text" class="form-control text-center fs-5 fw-bold bg-light border-0" value="PROGRAM KERJA YANG SEDANG BERJALAN" readonly>
    </div>
    <div class="row">
        @forelse ($berjalan as $item)
            <div class="col-md-4 mb-4">
                <div class="card dashboard-card border-0 shadow-sm p-3 rounded-4">
                    <div class="card-body p-0">
                        <div class="d-flex justify-content-between align-items-start mb-3">
                            <h6 class="mb-0 fw-semibold text-dark">{{ $item->proker->program ?? '-' }}</h6>
                            <span class="badge rounded-pill text-white px-3 py-1" style="background-color: #5cb85c;">
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
                                <strong>Selesai:</strong> {{ \Carbon\Carbon::parse($item->tanggal_selesai)->format('d-m-Y') }}
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
        <input type="text" class="form-control text-center fs-5 fw-bold bg-light border-0" value="PROGRAM KERJA YANG SUDAH SELESAI" readonly>
    </div>
    <div class="row">
        @forelse ($selesai as $item)
            <div class="col-md-4 mb-4">
                <div class="card dashboard-card border-0 shadow-sm p-3 rounded-4">
                    <div class="card-body p-0">
                        <div class="d-flex justify-content-between align-items-start mb-3">
                            <h6 class="mb-0 fw-semibold text-dark">{{ $item->proker->program ?? '-' }}</h6>
                            <span class="badge rounded-pill text-white px-3 py-1" style="background-color: #6c757d;">
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
                                <strong>Selesai:</strong> {{ \Carbon\Carbon::parse($item->tanggal_selesai)->format('d-m-Y') }}
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
