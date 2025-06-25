@extends('layouts.app')

@section('content')

    @if (session('success'))
        <script>
            Swal.fire('Berhasil!', @json(session('success')), 'success');
        </script>
    @endif

    <div class="grid grid-cols-1 sm:grid-cols-2 lg:grid-cols-4 gap-6 mb-10">
        @foreach (['bidang' => 'Bidang', 'jenis' => 'Jenis Kegiatan', 'tujuan' => 'Tujuan', 'sasaran' => 'Sasaran'] as $key => $label)
            <div class="bg-white p-5 rounded-2xl shadow-md hover:shadow-lg transition">
                <h2 class="text-lg font-semibold mb-3 text-center text-gray-800">{{ $label }}</h2>
                <button onclick="openTambahModal('{{ $key }}', '{{ $label }}')"
                    class="w-full py-2 bg-gradient-to-r from-green-500 to-green-600 text-white font-semibold rounded-lg hover:from-green-600 hover:to-green-700 transition">
                    Tambah
                </button>
            </div>
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
                            <th class="px-6 py-4 text-center">Aksi</th>
                        </tr>
                    </thead>
                    <tbody class="divide-y divide-gray-200 text-gray-700 bg-white">
                        @foreach ($data as $index => $item)
                            <tr class="hover:bg-gray-50 transition-colors duration-200">
                                <td class="px-6 py-4 text-center">{{ $index + 1 }}</td>
                                <td class="px-6 py-4 text-center">{{ $item->nama }}</td>
                                <td class="px-6 py-4 text-center whitespace-nowrap">
                                    <div class="flex justify-center gap-2">
                                        <button
                                            onclick="editData('{{ $filter }}', {{ $item->id }}, '{{ $item->nama }}')"
                                            aria-label="Edit"
                                            class="inline-flex items-center justify-center w-9 h-9 rounded-full text-white shadow transition"
                                            style="background-color: #facc15; border-radius: 9999px;">
                                            <i class="fas fa-edit"></i>
                                        </button>

                                        <form method="POST"
                                            action="{{ route('data-proker.destroy', ['type' => $filter, 'id' => $item->id]) }}"
                                            onsubmit="return confirm('Yakin ingin menghapus data ini?')"
                                            class="inline-block">
                                            @csrf
                                            @method('DELETE')
                                            <button type="button"
                                                onclick="confirmDelete('{{ route('data-proker.destroy', ['type' => $filter, 'id' => $item->id]) }}', '{{ $item->nama }}')"
                                                aria-label="Delete"
                                                class="inline-flex items-center justify-center w-9 h-9 rounded-full text-white shadow transition"
                                                style="background-color: #dc2626; border-radius: 9999px;"
                                                onmouseover="this.style.backgroundColor='#b91c1c'"
                                                onmouseout="this.style.backgroundColor='#dc2626'">
                                                <i class="fas fa-trash-alt"></i>
                                            </button>
                                        </form>
                                    </div>
                                </td>
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

    <div id="tambahModal" class="fixed inset-0 bg-black bg-opacity-50 z-50 hidden">
        <div class="flex items-center justify-center min-h-screen px-4">
            <div class="bg-white p-6 rounded-2xl w-full max-w-md shadow-lg">
                <div class="flex justify-between items-center mb-4">
                    <h2 id="tambahModalTitle" class="text-xl font-bold text-gray-800">Tambah Data</h2>
                    <button onclick="closeTambahModal()" class="text-gray-400 hover:text-gray-600 transition">
                        <i class="fas fa-times text-lg"></i>
                    </button>
                </div>
                <form method="POST" action="{{ route('data-proker.store') }}" class="space-y-4">
                    @csrf
                    <input type="hidden" name="type" id="tambahType">
                    <div>
                        <label for="tambahNama" class="block text-sm font-medium text-gray-700 mb-1">Nama</label>
                        <input type="text" name="nama" id="tambahNama"
                            class="w-full px-4 py-2 border border-gray-300 rounded-lg focus:ring-2 focus:ring-green-400 focus:outline-none text-sm"
                            placeholder="Masukkan nama" required>
                    </div>
                    <div class="flex justify-end gap-2">
                        <button type="button" onclick="closeTambahModal()"
                            class="px-4 py-2 text-sm text-gray-700 bg-gray-100 rounded-lg hover:bg-gray-200 transition">Batal</button>
                        <button type="submit"
                            class="px-4 py-2 text-sm text-white bg-green-500 rounded-lg hover:bg-green-600 transition">Simpan</button>
                    </div>
                </form>
            </div>
        </div>
    </div>

    <div id="editModal"
        class="fixed inset-0 z-50 bg-black bg-opacity-50 hidden transition-opacity duration-200 ease-in-out">
        <div class="flex items-center justify-center min-h-screen px-4">
            <div class="bg-white rounded-2xl shadow-lg p-6 w-full max-w-lg">
                <div class="flex justify-between items-center mb-4">
                    <h2 class="text-xl font-bold text-gray-800">Edit Data</h2>
                    <button onclick="closeEditModal()" class="text-gray-400 hover:text-gray-600 transition">
                        <svg xmlns="http://www.w3.org/2000/svg" class="h-6 w-6" fill="none" viewBox="0 0 24 24"
                            stroke="currentColor">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                d="M6 18L18 6M6 6l12 12" />
                        </svg>
                    </button>
                </div>
                <form id="editForm" method="POST" class="space-y-4">
                    @csrf
                    @method('PUT')
                    <input type="hidden" id="editType" name="type">
                    <div>
                        <label for="editNama" class="block text-sm font-medium text-gray-700 mb-1">Nama</label>
                        <input type="text" id="editNama" name="nama"
                            class="w-full px-4 py-2 border border-gray-300 rounded-lg focus:ring-2 focus:ring-green-400 focus:outline-none text-sm"
                            placeholder="Masukkan nama" required>
                    </div>
                    <div class="flex justify-end space-x-3">
                        <button type="button" onclick="closeEditModal()"
                            class="px-4 py-2 text-sm text-gray-700 bg-gray-100 rounded-lg hover:bg-gray-200 transition">
                            Batal
                        </button>
                        <button type="submit"
                            class="px-4 py-2 text-sm text-white bg-green-500 rounded-lg hover:bg-green-600 transition">
                            Simpan
                        </button>
                    </div>
                </form>
            </div>
        </div>
    </div>

    <script>
        function openTambahModal(type, label) {
            document.getElementById('tambahType').value = type;
            document.getElementById('tambahModalTitle').innerText = `Tambah ${label}`;
            document.getElementById('tambahNama').value = '';
            document.getElementById('tambahModal').classList.remove('hidden');
        }

        function closeTambahModal() {
            document.getElementById('tambahModal').classList.add('hidden');
        }

        function editData(type, id, nama) {
            document.getElementById('editForm').action = `/data-proker/${type}/${id}`;
            document.getElementById('editType').value = type;
            document.getElementById('editNama').value = nama;
            document.getElementById('editModal').classList.remove('hidden');
        }

        function closeEditModal() {
            document.getElementById('editModal').classList.add('hidden');
        }

        function confirmDelete(url, name) {
            Swal.fire({
                title: 'Hapus Data?',
                text: `Yakin ingin menghapus data ${name}?`,
                icon: 'warning',
                showCancelButton: true,
                confirmButtonColor: '#dc2626',
                cancelButtonColor: '#6c757d',
                confirmButtonText: 'Ya, hapus',
                cancelButtonText: 'Batal'
            }).then((result) => {
                if (result.isConfirmed) {
                    const form = document.createElement('form');
                    form.method = 'POST';
                    form.action = url;

                    const csrf = document.createElement('input');
                    csrf.type = 'hidden';
                    csrf.name = '_token';
                    csrf.value = '{{ csrf_token() }}';

                    const method = document.createElement('input');
                    method.type = 'hidden';
                    method.name = '_method';
                    method.value = 'DELETE';

                    form.appendChild(csrf);
                    form.appendChild(method);
                    document.body.appendChild(form);
                    form.submit();
                }
            });
        }
    </script>

@endsection
