@extends('layouts.app')

@section('content')

    @if (session('success'))
        <script>
            Swal.fire('Berhasil!', @json(session('success')), 'success');
        </script>
    @endif

    <div class="grid grid-cols-1 sm:grid-cols-2 lg:grid-cols-4 gap-6 mb-10">
        @foreach (['bidang' => 'Bidang', 'jenis' => 'Jenis Kegiatan', 'tujuan' => 'Tujuan', 'sasaran' => 'Sasaran'] as $key => $label)
            <form action="{{ route('data-proker.store') }}" method="POST"
                class="bg-white p-5 rounded-2xl shadow-md hover:shadow-lg transition-all duration-200">
                @csrf
                <input type="hidden" name="type" value="{{ $key }}">
                <h2 class="text-lg font-semibold mb-3 text-center text-gray-800">{{ $label }}</h2>
                <input type="text" name="nama" placeholder="Nama {{ $label }}" required
                    class="w-full px-4 py-2 border border-gray-300 rounded-lg focus:ring-2 focus:ring-green-400 focus:outline-none mb-3 text-sm">
                <button type="submit"
                    class="w-full py-2 bg-gradient-to-r from-green-500 to-green-600 text-white font-semibold rounded-lg hover:from-green-600 hover:to-green-700 transition">
                    Tambah
                </button>
            </form>
        @endforeach
    </div>

    <form method="GET" class="flex justify-center mb-8">
        <div class="relative w-full max-w-xs">
            <select name="filter" onchange="this.form.submit()"
                class="appearance-none w-full px-4 py-3 bg-white border border-gray-300 rounded-full shadow-md 
                focus:outline-none focus:ring-2 focus:ring-green-400 focus:border-transparent 
                text-gray-700 text-center cursor-pointer">
                <option value="">Pilih Jenis Data</option>
                <option value="bidang" {{ request('filter') === 'bidang' ? 'selected' : '' }}>Bidang</option>
                <option value="jenis" {{ request('filter') === 'jenis' ? 'selected' : '' }}>Jenis Kegiatan</option>
                <option value="tujuan" {{ request('filter') === 'tujuan' ? 'selected' : '' }}>Tujuan</option>
                <option value="sasaran" {{ request('filter') === 'sasaran' ? 'selected' : '' }}>Sasaran</option>
            </select>
            <div class="pointer-events-none absolute inset-y-0 right-4 flex items-center">
            </div>
        </div>
    </form>

    @php
        $filter = request('filter');
        $data = match ($filter) {
            'bidang' => $bidangs,
            'jenis' => $jenisKegiatans,
            'tujuan' => $tujuans,
            'sasaran' => $sasarans,
            default => collect(),
        };
    @endphp

    @if ($filter && $data->count())
        <div class="bg-white shadow-md rounded-2xl overflow-hidden mb-10">
            <div class="overflow-x-auto">
                <table class="min-w-full text-sm text-left">
                    <thead class="bg-gray-100 text-gray-700 uppercase tracking-wide text-xs">
                        <tr>
                            <th class="px-6 py-4 text-center">No</th>
                            <th class="px-6 py-4 text-center">Nama {{ ucfirst($filter) }}</th>
                        </tr>
                    </thead>
                    <tbody class="divide-y divide-gray-200 text-gray-700 bg-white">
                        @foreach ($data as $index => $item)
                            <tr class="hover:bg-gray-50 transition-colors duration-200">
                                <td class="px-6 py-4 text-center">{{ $index + 1 }}</td>
                                <td class="px-6 py-4 text-center">{{ $item->nama }}</td>
                            </tr>
                        @endforeach
                    </tbody>
                </table>
            </div>
        </div>
    @elseif ($filter)
        <div class="text-center text-gray-500 italic py-10">Data {{ ucfirst($filter) }} tidak ditemukan.</div>
    @else
        <div class="text-center text-gray-500 italic py-10">Silakan pilih jenis data untuk ditampilkan.</div>
    @endif
@endsection
