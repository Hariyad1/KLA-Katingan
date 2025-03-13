<x-app-layout>
    <x-slot name="header">
        <div class="flex justify-between items-center">
            <h2 class="font-semibold text-xl text-gray-800 leading-tight">
                {{ __('Pengaturan Aplikasi') }}
            </h2>
            <a href="{{ route('admin.setting.create') }}" class="bg-indigo-600 hover:bg-indigo-700 text-white font-bold py-2 px-4 rounded inline-flex items-center">
                <svg class="w-4 h-4 mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 6v6m0 0v6m0-6h6m-6 0H6"></path>
                </svg>
                <span>Tambah Pengaturan</span>
            </a>
        </div>
    </x-slot>

    <div class="py-12">
        <div class="max-w-7xl mx-auto sm:px-6 lg:px-8">
            <div class="bg-white overflow-hidden shadow-sm sm:rounded-lg">
                <div class="p-6 text-gray-900">
                    <!-- Alert untuk menampilkan pesan sukses/error -->
                    <div id="alertSuccess" class="hidden mb-4 p-4 bg-green-100 border border-green-400 text-green-700 rounded">
                        <span id="alertSuccessMessage"></span>
                    </div>
                    <div id="alertError" class="hidden mb-4 p-4 bg-red-100 border border-red-400 text-red-700 rounded">
                        <span id="alertErrorMessage"></span>
                    </div>

                    <!-- Loading indicator -->
                    <div id="loadingIndicator" class="flex justify-center items-center py-8 hidden">
                        <svg class="animate-spin h-8 w-8 text-indigo-600" xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24">
                            <circle class="opacity-25" cx="12" cy="12" r="10" stroke="currentColor" stroke-width="4"></circle>
                            <path class="opacity-75" fill="currentColor" d="M4 12a8 8 0 018-8V0C5.373 0 0 5.373 0 12h4zm2 5.291A7.962 7.962 0 014 12H0c0 3.042 1.135 5.824 3 7.938l3-2.647z"></path>
                        </svg>
                    </div>

                    <!-- Tabel Setting -->
                    <div class="overflow-x-auto">
                        <table class="min-w-full divide-y divide-gray-200">
                            <thead class="bg-gray-50">
                                <tr>
                                    <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Nama</th>
                                    <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Halaman</th>
                                    <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">URL</th>
                                    <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Tipe</th>
                                    <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Konten</th>
                                    <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Gambar</th>
                                    <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Aksi</th>
                                </tr>
                            </thead>
                            <tbody id="settingsTableBody" class="bg-white divide-y divide-gray-200">
                                <!-- Data akan diisi oleh JavaScript -->
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
        document.addEventListener('DOMContentLoaded', function() {
            // Tampilkan loading
            document.getElementById('loadingIndicator').classList.remove('hidden');
            
            // Konfigurasi axios
            const config = {
                headers: {
                    'Content-Type': 'application/json',
                    'X-CSRF-TOKEN': '{{ csrf_token() }}',
                    'Accept': 'application/json',
                    'Authorization': 'Bearer {{ session("api_token") }}'
                }
            };
            
            // Ambil data setting dari API
            axios.get('/api/setting', config)
                .then(function(response) {
                    // Sembunyikan loading
                    document.getElementById('loadingIndicator').classList.add('hidden');
                    
                    if (response.data.success) {
                        const settings = response.data.data;
                        const tableBody = document.getElementById('settingsTableBody');
                        
                        // Kosongkan tabel
                        tableBody.innerHTML = '';
                        
                        // Isi tabel dengan data
                        settings.forEach(function(setting) {
                            const row = document.createElement('tr');
                            row.innerHTML = `
                                <td class="px-6 py-4 whitespace-no-wrap border-b border-gray-200">${setting.name || '-'}</td>
                                <td class="px-6 py-4 whitespace-no-wrap border-b border-gray-200">${setting.page || '-'}</td>
                                <td class="px-6 py-4 whitespace-no-wrap border-b border-gray-200">${setting.url || '-'}</td>
                                <td class="px-6 py-4 whitespace-no-wrap border-b border-gray-200">${setting.type || '-'}</td>
                                <td class="px-6 py-4 whitespace-no-wrap border-b border-gray-200">
                                    ${setting.content ? `<div class="truncate max-w-xs">${setting.content}</div>` : '-'}
                                </td>
                                <td class="px-6 py-4 whitespace-no-wrap border-b border-gray-200">
                                    ${setting.image ? `<img src="${setting.image}" alt="${setting.name}" class="h-10 w-10 object-cover rounded">` : '-'}
                                </td>
                                <td class="px-6 py-4 whitespace-no-wrap border-b border-gray-200">
                                    <a href="/manage/setting/edit/${setting.id}" class="text-indigo-600 hover:text-indigo-900 mr-3">Edit</a>
                                    <button onclick="deleteSetting(${setting.id})" class="text-red-600 hover:text-red-900">Hapus</button>
                                </td>
                            `;
                            tableBody.appendChild(row);
                        });
                    } else {
                        showError('Gagal memuat data pengaturan');
                    }
                })
                .catch(function(error) {
                    // Sembunyikan loading
                    document.getElementById('loadingIndicator').classList.add('hidden');
                    
                    console.error('Error:', error);
                    showError('Terjadi kesalahan saat memuat data pengaturan');
                });
            
            // Fungsi untuk menampilkan pesan sukses
            function showSuccess(message) {
                const alert = document.getElementById('alertSuccess');
                const alertMessage = document.getElementById('alertSuccessMessage');
                alertMessage.textContent = message;
                alert.classList.remove('hidden');
                
                // Sembunyikan pesan setelah 3 detik
                setTimeout(function() {
                    alert.classList.add('hidden');
                }, 3000);
            }
            
            // Fungsi untuk menampilkan pesan error
            function showError(message) {
                const alert = document.getElementById('alertError');
                const alertMessage = document.getElementById('alertErrorMessage');
                alertMessage.textContent = message;
                alert.classList.remove('hidden');
                
                // Sembunyikan pesan setelah 3 detik
                setTimeout(function() {
                    alert.classList.add('hidden');
                }, 3000);
            }
            
            // Fungsi untuk menghapus setting
            window.deleteSetting = function(id) {
                if (confirm('Apakah Anda yakin ingin menghapus pengaturan ini?')) {
                    // Tampilkan loading
                    document.getElementById('loadingIndicator').classList.remove('hidden');
                    
                    axios.delete(`/api/setting/${id}`, config)
                        .then(function(response) {
                            // Sembunyikan loading
                            document.getElementById('loadingIndicator').classList.add('hidden');
                            
                            if (response.data.success) {
                                showSuccess('Pengaturan berhasil dihapus');
                                
                                // Reload halaman setelah 1 detik
                                setTimeout(function() {
                                    window.location.reload();
                                }, 1000);
                            } else {
                                showError('Gagal menghapus pengaturan');
                            }
                        })
                        .catch(function(error) {
                            // Sembunyikan loading
                            document.getElementById('loadingIndicator').classList.add('hidden');
                            
                            console.error('Error:', error);
                            showError('Terjadi kesalahan saat menghapus pengaturan');
                        });
                }
            };
        });
    </script>
    @endpush
</x-app-layout> 