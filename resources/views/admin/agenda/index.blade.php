<x-app-layout>
    <x-slot name="header">
        <div class="flex justify-between items-center">
            <h2 class="font-semibold text-xl text-gray-800 leading-tight">
                {{ __('Manajemen Agenda') }}
            </h2>
            <a href="{{ route('admin.agenda.create') }}" class="bg-indigo-600 hover:bg-indigo-700 text-white font-bold py-2 px-4 rounded inline-flex items-center">
                <svg class="w-4 h-4 mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 6v6m0 0v6m0-6h6m-6 0H6"></path>
                </svg>
                <span>Tambah Agenda</span>
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

                    <!-- Tabel Agenda -->
                    <div class="overflow-x-auto">
                        <table class="min-w-full divide-y divide-gray-200">
                            <thead class="bg-gray-50">
                                <tr>
                                    <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Judul</th>
                                    <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Tanggal</th>
                                    <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Keterangan</th>
                                    <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Aksi</th>
                                </tr>
                            </thead>
                            <tbody id="agendaTableBody">
                                <!-- Data akan diisi oleh JavaScript -->
                            </tbody>
                        </table>
                    </div>
                </div>
            </div>
        </div>
    </div>

    <!-- Modal untuk tambah agenda -->
    <div id="createModal" class="fixed inset-0 bg-gray-600 bg-opacity-50 flex items-center justify-center hidden z-50">
        <div class="bg-white rounded-lg shadow-lg p-6 w-full max-w-md">
            <div class="flex justify-between items-center mb-4">
                <h3 class="text-lg font-medium">Tambah Agenda Baru</h3>
                <button onclick="closeCreateModal()" class="text-gray-400 hover:text-gray-500">
                    <svg class="h-6 w-6" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M6 18L18 6M6 6l12 12"></path>
                    </svg>
                </button>
            </div>
            
            <form id="createForm">
                <div class="mb-4">
                    <label for="title" class="block text-sm font-medium text-gray-700">Judul</label>
                    <input type="text" id="title" name="title" class="mt-1 block w-full rounded-md border-gray-300 shadow-sm focus:border-indigo-500 focus:ring-indigo-500">
                    <p class="text-red-500 text-xs mt-1 hidden" id="titleError"></p>
                </div>
                
                <div class="mb-4">
                    <label for="tanggal" class="block text-sm font-medium text-gray-700">Tanggal</label>
                    <input type="date" id="tanggal" name="tanggal" class="mt-1 block w-full rounded-md border-gray-300 shadow-sm focus:border-indigo-500 focus:ring-indigo-500">
                    <p class="text-red-500 text-xs mt-1 hidden" id="tanggalError"></p>
                </div>
                
                <div class="mb-4">
                    <label for="keterangan" class="block text-sm font-medium text-gray-700">Keterangan</label>
                    <textarea id="keterangan" name="keterangan" rows="3" class="mt-1 block w-full rounded-md border-gray-300 shadow-sm focus:border-indigo-500 focus:ring-indigo-500"></textarea>
                    <p class="text-red-500 text-xs mt-1 hidden" id="keteranganError"></p>
                </div>
                
                <div class="flex justify-end">
                    <button type="button" onclick="closeCreateModal()" class="bg-gray-200 text-gray-700 px-4 py-2 rounded mr-2">Batal</button>
                    <button type="button" onclick="submitForm()" class="bg-indigo-600 text-white px-4 py-2 rounded">Simpan</button>
                </div>
            </form>
        </div>
    </div>

    <!-- Tambahkan modal edit setelah modal create -->
    <div id="editModal" class="fixed inset-0 bg-gray-600 bg-opacity-50 flex items-center justify-center hidden z-50">
        <div class="bg-white rounded-lg shadow-lg p-6 w-full max-w-md">
            <div class="flex justify-between items-center mb-4">
                <h3 class="text-lg font-medium">Edit Agenda</h3>
                <button onclick="closeEditModal()" class="text-gray-400 hover:text-gray-500">
                    <svg class="h-6 w-6" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M6 18L18 6M6 6l12 12"></path>
                    </svg>
                </button>
            </div>
            
            <form id="editForm">
                <input type="hidden" id="editId">
                <div class="mb-4">
                    <label for="editTitle" class="block text-sm font-medium text-gray-700">Judul</label>
                    <input type="text" id="editTitle" name="title" class="mt-1 block w-full rounded-md border-gray-300 shadow-sm focus:border-indigo-500 focus:ring-indigo-500">
                    <p class="text-red-500 text-xs mt-1 hidden" id="editTitleError"></p>
                </div>
                
                <div class="mb-4">
                    <label for="editTanggal" class="block text-sm font-medium text-gray-700">Tanggal</label>
                    <input type="date" id="editTanggal" name="tanggal" class="mt-1 block w-full rounded-md border-gray-300 shadow-sm focus:border-indigo-500 focus:ring-indigo-500">
                    <p class="text-red-500 text-xs mt-1 hidden" id="editTanggalError"></p>
                </div>
                
                <div class="mb-4">
                    <label for="editKeterangan" class="block text-sm font-medium text-gray-700">Keterangan</label>
                    <textarea id="editKeterangan" name="keterangan" rows="3" class="mt-1 block w-full rounded-md border-gray-300 shadow-sm focus:border-indigo-500 focus:ring-indigo-500"></textarea>
                    <p class="text-red-500 text-xs mt-1 hidden" id="editKeteranganError"></p>
                </div>
                
                <div class="flex justify-end">
                    <button type="button" onclick="closeEditModal()" class="bg-gray-200 text-gray-700 px-4 py-2 rounded mr-2">Batal</button>
                    <button type="button" onclick="updateAgenda()" class="bg-indigo-600 text-white px-4 py-2 rounded">Simpan</button>
                </div>
            </form>
        </div>
    </div>

    @push('scripts')
    <script src="https://cdn.jsdelivr.net/npm/axios/dist/axios.min.js"></script>
    <script>
        document.addEventListener('DOMContentLoaded', function() {
            loadAgendaData();
        });

        function loadAgendaData() {
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
            
            // Ambil data agenda dari API
            axios.get('/api/agenda', config)
                .then(function(response) {
                    // Sembunyikan loading
                    document.getElementById('loadingIndicator').classList.add('hidden');
                    
                    if (response.data.success) {
                        const agendas = response.data.data;
                        const tableBody = document.getElementById('agendaTableBody');
                        
                        // Kosongkan tabel
                        tableBody.innerHTML = '';
                        
                        // Isi tabel dengan data
                        agendas.forEach(function(agenda) {
                            const row = document.createElement('tr');
                            row.innerHTML = `
                                <td class="px-6 py-4 whitespace-no-wrap border-b border-gray-200">${agenda.title || '-'}</td>
                                <td class="px-6 py-4 whitespace-no-wrap border-b border-gray-200">${formatDate(agenda.tanggal) || '-'}</td>
                                <td class="px-6 py-4 whitespace-no-wrap border-b border-gray-200">${agenda.keterangan || '-'}</td>
                                <td class="px-6 py-4 whitespace-no-wrap border-b border-gray-200">
                                    <button onclick="editAgenda(${agenda.id})" class="text-indigo-600 hover:text-indigo-900 mr-3">Edit</button>
                                    <button onclick="deleteAgenda(${agenda.id})" class="text-red-600 hover:text-red-900">Hapus</button>
                                </td>
                            `;
                            tableBody.appendChild(row);
                        });
                    } else {
                        showError('Gagal memuat data agenda');
                    }
                })
                .catch(function(error) {
                    // Sembunyikan loading
                    document.getElementById('loadingIndicator').classList.add('hidden');
                    
                    console.error('Error:', error);
                    showError('Terjadi kesalahan saat memuat data agenda');
                });
        }

        function formatDate(dateString) {
            if (!dateString) return '-';
            const date = new Date(dateString);
            return date.toLocaleDateString('id-ID', { day: '2-digit', month: 'long', year: 'numeric' });
        }

        function openCreateModal() {
            document.getElementById('createModal').classList.remove('hidden');
            // Reset form
            document.getElementById('createForm').reset();
            // Reset error messages
            document.querySelectorAll('.text-red-500').forEach(el => el.classList.add('hidden'));
        }

        function closeCreateModal() {
            document.getElementById('createModal').classList.add('hidden');
        }

        function submitForm() {
            // Tampilkan loading
            document.getElementById('loadingIndicator').classList.remove('hidden');
            
            // Reset error messages
            document.querySelectorAll('.text-red-500').forEach(el => {
                el.textContent = '';
                el.classList.add('hidden');
            });
            
            // Ambil data form
            const formData = {
                title: document.getElementById('title').value,
                tanggal: document.getElementById('tanggal').value,
                keterangan: document.getElementById('keterangan').value
            };
            
            // Konfigurasi axios
            const config = {
                headers: {
                    'Content-Type': 'application/json',
                    'X-CSRF-TOKEN': '{{ csrf_token() }}',
                    'Accept': 'application/json',
                    'Authorization': 'Bearer {{ session("api_token") }}'
                }
            };
            
            // Kirim data ke API
            axios.post('/api/agenda', formData, config)
                .then(function(response) {
                    // Sembunyikan loading
                    document.getElementById('loadingIndicator').classList.add('hidden');
                    
                    if (response.data.success) {
                        // Tutup modal
                        closeCreateModal();
                        
                        // Tampilkan pesan sukses
                        showSuccess('Agenda berhasil ditambahkan');
                        
                        // Reload data
                        loadAgendaData();
                    } else {
                        showError('Gagal menambahkan agenda');
                    }
                })
                .catch(function(error) {
                    // Sembunyikan loading
                    document.getElementById('loadingIndicator').classList.add('hidden');
                    
                    console.error('Error:', error);
                    
                    // Tampilkan error validasi
                    if (error.response && error.response.data && error.response.data.errors) {
                        const errors = error.response.data.errors;
                        Object.keys(errors).forEach(field => {
                            const errorElement = document.getElementById(`${field}Error`);
                            if (errorElement) {
                                errorElement.textContent = errors[field][0];
                                errorElement.classList.remove('hidden');
                            }
                        });
                    } else {
                        showError('Terjadi kesalahan saat menambahkan agenda');
                    }
                });
        }

        function deleteAgenda(id) {
            if (confirm('Apakah Anda yakin ingin menghapus agenda ini?')) {
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
                
                // Kirim request delete
                axios.delete(`/api/agenda/${id}`, config)
                    .then(function(response) {
                        // Sembunyikan loading
                        document.getElementById('loadingIndicator').classList.add('hidden');
                        
                        if (response.data.success) {
                            // Tampilkan pesan sukses
                            showSuccess('Agenda berhasil dihapus');
                            
                            // Reload data
                            loadAgendaData();
                        } else {
                            showError('Gagal menghapus agenda');
                        }
                    })
                    .catch(function(error) {
                        // Sembunyikan loading
                        document.getElementById('loadingIndicator').classList.add('hidden');
                        
                        console.error('Error:', error);
                        showError('Terjadi kesalahan saat menghapus agenda');
                    });
            }
        }

        function editAgenda(id) {
            window.location.href = `/manage/agenda/${id}/edit`;
        }

        function updateAgenda() {
            // Tampilkan loading
            document.getElementById('loadingIndicator').classList.remove('hidden');
            
            // Reset error messages
            document.querySelectorAll('.text-red-500').forEach(el => {
                el.textContent = '';
                el.classList.add('hidden');
            });
            
            const id = document.getElementById('editId').value;
            const formData = {
                title: document.getElementById('editTitle').value,
                tanggal: document.getElementById('editTanggal').value,
                keterangan: document.getElementById('editKeterangan').value
            };
            
            // Konfigurasi axios
            const config = {
                headers: {
                    'Content-Type': 'application/json',
                    'Accept': 'application/json',
                    'Authorization': 'Bearer {{ session("api_token") }}'
                }
            };
            
            // Kirim request update
            axios.put(`/api/agenda/${id}`, formData, config)
                .then(function(response) {
                    if (response.data.success) {
                        // Tutup modal
                        closeEditModal();
                        
                        // Tampilkan pesan sukses
                        showSuccess('Agenda berhasil diperbarui');
                        
                        // Reload data
                        loadAgendaData();
                    } else {
                        showError('Gagal memperbarui agenda');
                    }
                })
                .catch(function(error) {
                    console.error('Error:', error);
                    
                    // Tampilkan error validasi
                    if (error.response && error.response.data && error.response.data.errors) {
                        const errors = error.response.data.errors;
                        Object.keys(errors).forEach(field => {
                            const errorElement = document.getElementById(`edit${field.charAt(0).toUpperCase() + field.slice(1)}Error`);
                            if (errorElement) {
                                errorElement.textContent = errors[field][0];
                                errorElement.classList.remove('hidden');
                            }
                        });
                    } else {
                        showError('Terjadi kesalahan saat memperbarui agenda');
                    }
                })
                .finally(function() {
                    document.getElementById('loadingIndicator').classList.add('hidden');
                });
        }

        function closeEditModal() {
            document.getElementById('editModal').classList.add('hidden');
            document.getElementById('editForm').reset();
        }

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
    </script>
    @endpush
</x-app-layout> 