@extends('layouts.app')

@section('content')

    <div class="d-flex justify-content-between align-items-center mb-4">
        <h1 class="h3 text-gray-800">Data Program kerja</h1>
        <a href="/anggota/create" class="btn btn-success shadow-sm">
            <i class="fas fa-plus fa-sm text-white-50"></i> Ajukan program kerja
        </a>
    </div>

    <form method="GET" class="d-flex justify-content-center mb-4">
        <select name="status" id="statusSelect" class="custom-dropdown" onchange="this.form.submit()">
            <option value="">-- Semua program kerja --</option>
            @foreach (['pengajuan', 'di setujui', 'di tolak'] as $r)
                <option value="{{ $r }}" {{ request('status') == $r ? 'selected' : '' }}>
                    {{ ucfirst($r) }}
                </option>
            @endforeach
        </select>
    </form>    

    <div class="card shadow-sm border-0 mb-4 rounded-4">
        <div class="card-body">
            <div class="table-responsive">
                <table class="table table-hover align-middle text-center">
                    <thead class="table-light">
                        <tr>
                            <th>Mengajukan</th>
                            <th>Program kerja</th>
                            <th>Tanggal pengajuan</th>
                            <th>Proses pengajuan</th>
                            <th>Catatan</th>
                    </thead>
                    <tbody>
                        @forelse ($prokers as $item)
                            <tr>
                                <td>{{ $item->user->name }}</td>
                                <td>{{ $item->program}}</td>
                                <td>{{ $item->created_at }}</td>
                                <td>{{ $item->updated_at }}</td>
                                <td>{{ $item->catatan }}</td>
                            </tr>
                        @empty
                            <tr>
                                <td colspan="7" class="text-center py-3 text-muted">Tidak ada data program kerja</td>
                            </tr>
                        @endforelse
                    </tbody>
                </table>
            </div>
        </div>
    </div>

    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/js/bootstrap.bundle.min.js"></script>

@endsection