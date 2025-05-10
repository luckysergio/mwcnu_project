@extends('layouts.app')

@section('content')

    <div class="d-sm-flex align-items-center justify-content-between mb-4">
        <h1 class="h3 mb-0 text-gray-800">Data Anggota</h1>
        <a href="/anggota/create" class="btn btn-sm btn-success shadow-sm">
            <i class="fas fa-plus fa-sm text-white-50"></i> Tambah Anggota
        </a>
    </div>

    <div class="row">
        <div class="col">
            <div class="card shadow mb-4">
                {{-- <div class="card-header py-3">
                    <h6 class="m-0 font-weight-bold text-center">MWNCU KARANG TENGAH</h6>
                </div> --}}
                <div class="card-body">
                    <div class="table-responsive">
                        <table class="table table-bordered table-striped table-hover">
                            <thead class="thead-light">
                                <tr>
                                    <th>Nama</th>
                                    <th>Email</th>
                                    <th>HP</th>
                                    <th>Jabatan</th>
                                    <th>Ranting</th>
                                    <th>Status</th>
                                    <th>Aksi</th>
                                </tr>
                            </thead>

                            <tbody>
                                @forelse ($anggotas as $item)
                                    <tr>
                                        <td>{{ $item->name }}</td>
                                        <td>{{ $item->email }}</td>
                                        <td>{{ $item->phone }}</td>
                                        <td>{{ $item->jabatan }}</td>
                                        <td>{{ $item->ranting }}</td>
                                        <td>
                                            <span class="badge badge-{{ $item->status == 'aktif' ? 'success' : 'secondary' }}">
                                                {{ ucfirst($item->status) }}
                                            </span>
                                        </td>
                                        <td>
                                            <div class="btn-group" role="group">
                                                <a href="/anggota/{{ $item->id }}" class="btn btn-sm btn-warning" title="Edit">
                                                    <i class="fas fa-edit"></i>
                                                </a>
                                                <a href="/anggota/{{ $item->id }}" class="btn btn-sm btn-danger" title="Hapus">
                                                    <i class="fas fa-trash-alt"></i>
                                                </a>
                                            </div>
                                        </td>
                                    </tr>
                                @empty
                                    <tr>
                                        <td colspan="7" class="text-center py-3">Tidak ada data anggota.</td>
                                    </tr>
                                @endforelse
                            </tbody>
                        </table>
                    </div>
                </div>
            </div>
        </div>
    </div>

@endsection
