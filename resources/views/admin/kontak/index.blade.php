<x-app-layout>
    <!-- Tambahkan header -->
    <x-slot name="header">
        <div class="flex justify-between items-center">
            <h2 class="font-semibold text-xl text-gray-800 leading-tight">
                {{ __('Pesan Kontak') }}
            </h2>
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

                    <!-- Tabel Kontak -->
                    <div class="overflow-x-auto">
                        <table class="min-w-full divide-y divide-gray-200">
                            <thead class="bg-gray-50">
                                <tr>
                                    <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Nama</th>
                                    <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Email</th>
                                    <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Subjek</th>
                                    <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Pesan</th>
                                    <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Tanggal</th>
                                    <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Aksi</th>
                                </tr>
                            </thead>
                            <tbody class="bg-white divide-y divide-gray-200">
                                @foreach($contacts as $contact)
                                    <tr>
                                        <td class="px-6 py-4">
                                            <div class="text-sm font-medium text-gray-900">{{ $contact->nama }}</div>
                                        </td>
                                        <td class="px-6 py-4">
                                            <div class="text-sm text-gray-900">{{ $contact->email }}</div>
                                        </td>
                                        <td class="px-6 py-4">
                                            <div class="text-sm text-gray-900">{{ $contact->subjek }}</div>
                                        </td>
                                        <td class="px-6 py-4">
                                            <div class="text-sm text-gray-900">{{ Str::limit($contact->isi, 50) }}</div>
                                        </td>
                                        <td class="px-6 py-4 whitespace-nowrap">
                                            <div class="text-sm text-gray-900">{{ \Carbon\Carbon::parse($contact->created_at)->format('d M Y H:i') }}</div>
                                        </td>
                                        <td class="px-6 py-4 whitespace-nowrap text-sm font-medium">
                                            <button onclick="viewMessage({{ $contact->id }})" class="text-blue-600 hover:text-blue-900 mr-3">Lihat</button>
                                            <button onclick="deleteMessage({{ $contact->id }})" class="text-red-600 hover:text-red-900">Hapus</button>
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

    <!-- View Message Modal -->
    <div x-data="{ open: false, message: '' }" 
         x-show="open" 
         @view-message.window="open = true; message = $event.detail"
         class="fixed inset-0 z-50 overflow-y-auto" 
         style="display: none;">
        <div class="flex items-center justify-center min-h-screen px-4">
            <div class="fixed inset-0 bg-gray-500 bg-opacity-75 transition-opacity"></div>

            <div class="relative bg-white rounded-lg max-w-lg w-full">
                <div class="p-6">
                    <h3 class="text-lg font-medium text-gray-900 mb-4">Detail Pesan</h3>
                    <div class="space-y-4">
                        <div x-html="message"></div>
                    </div>
                    <div class="mt-5 flex justify-end">
                        <button @click="open = false" class="px-4 py-2 text-sm font-medium text-gray-700 bg-white border border-gray-300 rounded-md hover:bg-gray-50">
                            Tutup
                        </button>
                    </div>
                </div>
            </div>
        </div>
    </div>

    @push('scripts')
    <script src="https://cdn.jsdelivr.net/npm/axios/dist/axios.min.js"></script>
    <script>
        function viewMessage(id) {
            // Tampilkan loading jika ada
            document.getElementById('loadingIndicator')?.classList.remove('hidden');

            axios.get(`/api/contact/${id}`, {
                headers: {
                    'Accept': 'application/json',
                    'Authorization': 'Bearer ' + document.querySelector('meta[name="api-token"]').content
                }
            })
            .then(response => {
                const data = response.data;
                const messageHtml = `
                    <div class="space-y-3">
                        <div>
                            <span class="font-medium">Nama:</span>
                            <span>${data.nama}</span>
                        </div>
                        <div>
                            <span class="font-medium">Email:</span>
                            <span>${data.email}</span>
                        </div>
                        <div>
                            <span class="font-medium">Subjek:</span>
                            <span>${data.subjek}</span>
                        </div>
                        <div>
                            <span class="font-medium">Pesan:</span>
                            <div class="mt-1">${data.isi}</div>
                        </div>
                    </div>
                `;
                window.dispatchEvent(new CustomEvent('view-message', { detail: messageHtml }));
            })
            .catch(error => {
                showError('Terjadi kesalahan saat mengambil detail pesan');
                console.error('Error:', error);
            })
            .finally(() => {
                document.getElementById('loadingIndicator')?.classList.add('hidden');
            });
        }

        function deleteMessage(id) {
            if (confirm('Apakah Anda yakin ingin menghapus pesan ini?')) {
                // Tampilkan loading
                document.getElementById('loadingIndicator')?.classList.remove('hidden');

                axios.delete(`/api/contact/${id}`, {
                    headers: {
                        'X-CSRF-TOKEN': document.querySelector('meta[name="csrf-token"]').content,
                        'Accept': 'application/json',
                        'Authorization': 'Bearer ' + document.querySelector('meta[name="api-token"]').content
                    }
                })
                .then(response => {
                    const data = response.data;
                    if (data.success) {
                        showSuccess('Pesan berhasil dihapus');
                        setTimeout(() => window.location.reload(), 1000);
                    } else {
                        showError(data.message || 'Gagal menghapus pesan');
                    }
                })
                .catch(error => {
                    showError('Terjadi kesalahan saat menghapus pesan');
                    console.error('Error:', error);
                })
                .finally(() => {
                    document.getElementById('loadingIndicator')?.classList.add('hidden');
                });
            }
        }

        function showSuccess(message) {
            const alert = document.getElementById('alertSuccess');
            const alertMessage = document.getElementById('alertSuccessMessage');
            alertMessage.textContent = message;
            alert.classList.remove('hidden');
            setTimeout(() => alert.classList.add('hidden'), 3000);
        }

        function showError(message) {
            const alert = document.getElementById('alertError');
            const alertMessage = document.getElementById('alertErrorMessage');
            alertMessage.textContent = message;
            alert.classList.remove('hidden');
            setTimeout(() => alert.classList.add('hidden'), 3000);
        }
    </script>
    @endpush
</x-app-layout> 