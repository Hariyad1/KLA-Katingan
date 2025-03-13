<x-app-layout>
    <!-- Tambahkan header -->
    <x-slot name="header">
        <div class="flex justify-between items-center">
            <h2 class="font-semibold text-xl text-gray-800 leading-tight">
                {{ __('Manajemen Dokumen') }}
            </h2>
            <button onclick="openUploadModal()" class="bg-indigo-600 hover:bg-indigo-700 text-white font-bold py-2 px-4 rounded inline-flex items-center">
                <svg class="w-4 h-4 mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 6v6m0 0v6m0-6h6m-6 0H6"></path>
                </svg>
                <span>Upload Dokumen</span>
            </button>
        </div>
    </x-slot>

    <!-- Hapus ml-60 -->
    <div class="py-12">
        <div class="max-w-7xl mx-auto sm:px-6 lg:px-8">
            <div class="bg-white overflow-hidden shadow-sm sm:rounded-lg">
                <div class="p-6">
                    <!-- Alert Messages -->
                    <div id="alertSuccess" class="hidden mb-4 p-4 bg-green-100 border border-green-400 text-green-700 rounded">
                        <span id="alertSuccessMessage"></span>
                    </div>
                    <div id="alertError" class="hidden mb-4 p-4 bg-red-100 border border-red-400 text-red-700 rounded">
                        <span id="alertErrorMessage"></span>
                    </div>

                    <!-- Tabel Dokumen -->
                    <div class="overflow-x-auto">
                        <table class="min-w-full divide-y divide-gray-200">
                            <thead class="bg-gray-50">
                                <tr>
                                    <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">#</th>
                                    <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Nama</th>
                                    <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">File</th>
                                    <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Path</th>
                                    <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Diunduh</th>
                                    <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Aksi</th>
                                </tr>
                            </thead>
                            <tbody class="bg-white divide-y divide-gray-200">
                                @foreach($documents as $index => $item)
                                    <tr>
                                        <td class="px-6 py-4 whitespace-nowrap text-sm">{{ $index + 1 }}</td>
                                        <td class="px-6 py-4 whitespace-nowrap text-sm">{{ $item->name }}</td>
                                        <td class="px-6 py-4 whitespace-nowrap text-sm">
                                            @php
                                                $extension = strtolower(pathinfo($item->file, PATHINFO_EXTENSION));
                                                $type = match($extension) {
                                                    'pdf' => 'PDF Document',
                                                    'doc', 'docx' => 'Word Document',
                                                    'xls', 'xlsx' => 'Excel Document',
                                                    default => 'Unknown'
                                                };
                                            @endphp
                                            <span class="px-2 py-1 text-xs rounded-full
                                                {{ match($extension) {
                                                    'pdf' => 'bg-red-100 text-red-800',
                                                    'doc', 'docx' => 'bg-blue-100 text-blue-800',
                                                    'xls', 'xlsx' => 'bg-green-100 text-green-800',
                                                    default => 'bg-gray-100 text-gray-800'
                                                } }}">
                                                {{ $type }}
                                            </span>
                                        </td>
                                        <td class="px-6 py-4 whitespace-nowrap text-sm">{{ $item->file }}</td>
                                        <td class="px-6 py-4 whitespace-nowrap text-sm">{{ $item->hits }} kali</td>
                                        <td class="px-6 py-4 whitespace-nowrap text-sm font-medium">
                                            <a href="{{ $item->path }}" target="_blank" class="text-blue-600 hover:text-blue-900 mr-3">Unduh</a>
                                            <a href="{{ route('admin.dokumen.edit', $item->id) }}" class="text-indigo-600 hover:text-indigo-900 mr-3">Ubah</a>
                                            <button onclick="deleteDocument({{ $item->id }})" class="text-red-600 hover:text-red-900">Hapus</button>
                                        </td>
                                    </tr>
                                @endforeach
                            </tbody>
                        </table>
                    </div>
                </div>
            </div>
        </div>
    </div>

    @push('scripts')
    <script src="https://cdn.jsdelivr.net/npm/axios/dist/axios.min.js"></script>
    <script>
        function deleteDocument(id) {
            if (confirm('Apakah Anda yakin ingin menghapus dokumen ini?')) {
                axios.delete(`/api/media/${id}`, {
                    headers: {
                        'X-CSRF-TOKEN': document.querySelector('meta[name="csrf-token"]').content,
                        'Authorization': 'Bearer ' + document.querySelector('meta[name="api-token"]').content
                    }
                })
                .then(response => {
                    const data = response.data;
                    if (data.success) {
                        showSuccess('Dokumen berhasil dihapus');
                        setTimeout(() => {
                            window.location.reload();
                        }, 1000);
                    }
                })
                .catch(({ response }) => {
                    showError('Gagal menghapus dokumen');
                });
            }
        }

        function showSuccess(message) {
            const alert = document.getElementById('alertSuccess');
            const alertMessage = document.getElementById('alertSuccessMessage');
            alertMessage.textContent = message;
            alert.classList.remove('hidden');
            setTimeout(() => {
                alert.classList.add('hidden');
            }, 3000);
        }

        function showError(message) {
            const alert = document.getElementById('alertError');
            const alertMessage = document.getElementById('alertErrorMessage');
            alertMessage.textContent = message;
            alert.classList.remove('hidden');
            setTimeout(() => {
                alert.classList.add('hidden');
            }, 3000);
        }

        function openUploadModal() {
            window.location.href = "{{ route('admin.dokumen.create') }}";
        }
    </script>
    @endpush
</x-app-layout> 