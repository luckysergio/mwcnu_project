@extends('layouts.app')

@section('content')
    @if (session('success'))
        <script>
            Swal.fire('Berhasil!', @json(session('success')), 'success');
        </script>
    @endif

    <div class="grid grid-cols-1 sm:grid-cols-2 gap-6 mb-10">
        @foreach ([['jabatan', 'Jabatan', $roles], ['ranting', 'Ranting', $rantings]] as [$type, $label, $data])
            <div class="bg-white p-5 rounded-2xl shadow-md hover:shadow-lg transition-all">
                <div class="flex justify-between items-center mb-4">
                    <h2 class="text-lg font-semibold text-gray-800">{{ $label }}</h2>
                    <button onclick="openTambahModal('{{ $type }}', '{{ $label }}')"
                        class="text-sm bg-green-500 hover:bg-green-600 text-white px-3 py-1 rounded-lg transition">Tambah</button>
                </div>

                <table class="min-w-full text-sm">
                    <thead class="bg-gray-100 text-gray-700 uppercase text-xs">
                        <tr>
                            <th class="px-4 py-2 text-center">No</th>
                            <th class="px-4 py-2 text-center">Nama {{ $label }}</th>
                            <th class="px-4 py-2 text-center">Aksi</th>
                        </tr>
                    </thead>
                    <tbody class="divide-y divide-gray-200">
                        @foreach ($data as $i => $item)
                            @php
                                $lockedNames = $type === 'jabatan'
                                    ? ['Admin', 'Tanfidiyah', 'Tanfidiyah ranting', 'Sekretaris']
                                    : ['karang tengah', 'karang mulya', 'karang timur', 'pedurenan', 'pondok bahar', 'pondok pucung', 'parung jaya'];
                                $nama = $type === 'jabatan' ? $item->jabatan : $item->kelurahan;
                            @endphp
                            <tr>
                                <td class="px-4 py-2 text-center">{{ $i + 1 }}</td>
                                <td class="px-4 py-2 text-center">{{ $nama }}</td>
                                <td class="px-4 py-2 text-center space-x-2">
                                    @if (!in_array(strtolower($nama), array_map('strtolower', $lockedNames)))
                                        <button
                                            onclick="openEditModal('{{ $type }}', {{ $item->id }}, '{{ $nama }}')"
                                            aria-label="Edit"
                                            class="inline-flex items-center justify-center w-9 h-9 text-white shadow transition"
                                            style="background-color: #facc15; border-radius: 9999px;">
                                            <i class="fas fa-edit"></i>
                                        </button>

                                        <button type="button"
                                            onclick="confirmDelete('{{ route('jdr.destroy', [$type, $item->id]) }}', '{{ $nama }}')"
                                            aria-label="Delete"
                                            class="inline-flex items-center justify-center w-9 h-9 text-white shadow transition"
                                            style="background-color: #dc2626; border-radius: 9999px;"
                                            onmouseover="this.style.backgroundColor='#b91c1c'"
                                            onmouseout="this.style.backgroundColor='#dc2626'">
                                            <i class="fas fa-trash-alt"></i>
                                        </button>
                                    @else
                                        <span class="text-gray-400 italic">Terkunci</span>
                                    @endif
                                </td>
                            </tr>
                        @endforeach
                        @if ($data->isEmpty())
                            <tr>
                                <td colspan="3" class="text-center text-gray-500 italic py-4">Belum ada data.</td>
                            </tr>
                        @endif
                    </tbody>
                </table>
            </div>
        @endforeach
    </div>

    <div id="dataModal" class="fixed inset-0 bg-black bg-opacity-50 z-50 hidden">
        <div class="flex items-center justify-center min-h-screen px-4">
            <div class="bg-white p-6 rounded-2xl w-full max-w-md shadow-lg">
                <div class="flex justify-between items-center mb-4">
                    <h2 id="modalTitle" class="text-xl font-bold text-gray-800">Modal</h2>
                    <button onclick="closeModal()" class="text-gray-400 hover:text-gray-600">
                        <i class="fas fa-times text-lg"></i>
                    </button>
                </div>
                <form id="dataForm" method="POST">
                    @csrf
                    <input type="hidden" name="_method" id="formMethod" value="POST">
                    <input type="hidden" name="type" id="formType">
                    <div class="mb-4">
                        <label for="formNama" class="block text-sm font-medium text-gray-700 mb-1">Nama</label>
                        <input type="text" name="nama" id="formNama"
                            class="w-full px-4 py-2 border border-gray-300 rounded-lg focus:ring-2 focus:ring-green-400 focus:outline-none text-sm"
                            required>
                    </div>
                    <div class="flex justify-end gap-2">
                        <button type="button" onclick="closeModal()"
                            class="px-4 py-2 bg-gray-100 text-gray-700 rounded-lg hover:bg-gray-200 transition">Batal</button>
                        <button type="submit"
                            class="px-4 py-2 bg-green-500 text-white rounded-lg hover:bg-green-600 transition">Simpan</button>
                    </div>
                </form>
            </div>
        </div>
    </div>

    <script>
        function openTambahModal(type, label) {
            document.getElementById('dataForm').action = "{{ route('jdr.store') }}";
            document.getElementById('formMethod').value = 'POST';
            document.getElementById('formType').value = type;
            document.getElementById('formNama').value = '';
            document.getElementById('modalTitle').innerText = `Tambah ${label}`;
            document.getElementById('dataModal').classList.remove('hidden');
        }

        function openEditModal(type, id, nama) {
            const url = `/jdr/${type}/${id}`;
            document.getElementById('dataForm').action = url;
            document.getElementById('formMethod').value = 'PUT';
            document.getElementById('formType').value = type;
            document.getElementById('formNama').value = nama;
            document.getElementById('modalTitle').innerText = `Edit ${type.charAt(0).toUpperCase() + type.slice(1)}`;
            document.getElementById('dataModal').classList.remove('hidden');
        }

        function closeModal() {
            document.getElementById('dataModal').classList.add('hidden');
        }

        function confirmDelete(url, name) {
            Swal.fire({
                title: 'Hapus Data?',
                text: `Yakin ingin menghapus ${name}?`,
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
