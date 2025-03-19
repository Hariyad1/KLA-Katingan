<x-app-layout>
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/notyf@3/notyf.min.css">
    <script src="https://cdn.jsdelivr.net/npm/notyf@3/notyf.min.js"></script>
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

        <!-- Tambahkan loading indicator di bagian atas konten -->
        <div id="loadingIndicator" class="fixed inset-0 bg-black bg-opacity-50 flex items-center justify-center z-50 hidden">
            <div class="animate-spin rounded-full h-16 w-16 border-t-2 border-b-2 border-indigo-500"></div>
        </div>

    <div class="py-12">
        <div class="max-w-7xl mx-auto sm:px-6 lg:px-8">
            <div class="bg-white overflow-hidden shadow-sm sm:rounded-lg">
                <div class="p-6 text-gray-900">
                    <!-- Search dan Show Entries -->
                    <div class="flex justify-between items-center mb-4">
                        <div class="flex items-center space-x-2">
                            <span>Show</span>
                            <select id="entriesPerPage" class="border rounded px-2 py-1 w-20" onchange="loadAgendaData()">
                                <option value="10">10</option>
                                <option value="25">25</option>
                                <option value="50">50</option>
                                <option value="100">100</option>
                            </select>
                            <span>entries</span>
                        </div>
                        <div class="flex items-center">
                            <span class="mr-2">Search:</span>
                            <input type="text" id="searchInput" class="border rounded px-3 py-1" onkeyup="loadAgendaData()" placeholder="Search...">
                        </div>
                    </div>

                    <!-- Tabel Agenda -->
                    <div class="overflow-x-auto">
                        <table class="min-w-full divide-y divide-gray-200">
                            <thead class="bg-gray-50">
                                <tr>
                                    <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">No</th>
                                    <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Judul</th>
                                    <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Tanggal</th>
                                    <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Keterangan</th>
                                    <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Status</th>
                                    <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Aksi</th>
                                </tr>
                            </thead>
                            <tbody id="agendaTableBody">
                            </tbody>
                        </table>
                    </div>

                    <!-- Pagination -->
                    <div class="flex justify-between items-center mt-4">
                        <div id="tableInfo" class="text-sm text-gray-700">
                            Showing <span id="startEntry">1</span> to <span id="endEntry">10</span> of <span id="totalEntries">0</span> entries
                        </div>
                        <div class="flex items-center space-x-2">
                            <button id="prevBtn" onclick="previousPage()" class="px-3 py-1 border rounded hover:bg-gray-100 disabled:opacity-50 disabled:cursor-not-allowed">Previous</button>
                            <div class="flex items-center space-x-1">
                                <span>Page</span>
                                <span id="currentPageDisplay">1</span>
                                <span>of</span>
                                <span id="totalPagesDisplay">1</span>
                            </div>
                            <button id="nextBtn" onclick="nextPage()" class="px-3 py-1 border rounded hover:bg-gray-100 disabled:opacity-50 disabled:cursor-not-allowed">Next</button>
                        </div>
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

    @push('styles')
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/@sweetalert2/theme-material-ui/material-ui.css">
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/notyf@3/notyf.min.css">
    @endpush

    @push('scripts')
    <script src="https://cdn.jsdelivr.net/npm/sweetalert2@11"></script>
    <script src="https://cdn.jsdelivr.net/npm/axios/dist/axios.min.js"></script>
    <script src="https://cdn.jsdelivr.net/npm/notyf@3/notyf.min.js"></script>
    <script>
        document.addEventListener('DOMContentLoaded', function() {
            loadAgendaData();
        });

        function showLoading() {
            document.getElementById('loadingIndicator').classList.remove('hidden');
        }

        function hideLoading() {
            document.getElementById('loadingIndicator').classList.add('hidden');
        }

        let currentPage = 1;
        let totalPages = 1;
        let filteredData = [];

        function loadAgendaData() {
            showLoading();
            const perPage = document.getElementById('entriesPerPage').value;
            const searchQuery = document.getElementById('searchInput').value.toLowerCase();
            
            axios.get('/api/agenda', {
                headers: {
                    'Authorization': 'Bearer {{ session("api_token") }}'
                }
            })
            .then(function(response) {
                hideLoading();
                
                if (response.data.success) {
                    const allAgendas = response.data.data;
                    
                    // Filter data hanya berdasarkan judul
                    filteredData = allAgendas.filter(agenda => 
                        agenda.title.toLowerCase().includes(searchQuery)
                    );

                    // Hitung total halaman
                    totalPages = Math.ceil(filteredData.length / perPage);
                    
                    // Pastikan halaman saat ini valid
                    if (currentPage > totalPages) {
                        currentPage = totalPages || 1;
                    }

                    // Update tampilan nomor halaman
                    document.getElementById('currentPageDisplay').textContent = currentPage;
                    document.getElementById('totalPagesDisplay').textContent = totalPages;

                    // Hitung data untuk halaman saat ini
                    const startIndex = (currentPage - 1) * perPage;
                    const endIndex = Math.min(startIndex + parseInt(perPage), filteredData.length);
                    const currentPageData = filteredData.slice(startIndex, endIndex);

                    // Update tampilan tabel
                    updateTable(currentPageData, startIndex);
                    
                    // Update informasi tabel
                    document.getElementById('startEntry').textContent = filteredData.length ? startIndex + 1 : 0;
                    document.getElementById('endEntry').textContent = endIndex;
                    document.getElementById('totalEntries').textContent = filteredData.length;
                    
                    // Update status tombol pagination
                    document.getElementById('prevBtn').disabled = currentPage === 1;
                    document.getElementById('nextBtn').disabled = currentPage === totalPages;
                } else {
                    showError('Gagal memuat data agenda');
                }
            })
            .catch(function(error) {
                hideLoading();
                console.error('Error:', error);
                showError('Terjadi kesalahan saat memuat data agenda');
            });
        }

        function updateTable(agendas, startIndex) {
            const tableBody = document.getElementById('agendaTableBody');
            tableBody.innerHTML = '';
            
            agendas.forEach(function(agenda, index) {
                const agendaDate = new Date(agenda.tanggal);
                const today = new Date();
                today.setHours(0, 0, 0, 0);
                const isActive = agendaDate >= today;
                
                const row = document.createElement('tr');
                row.className = index % 2 === 0 ? 'bg-white' : 'bg-gray-50';
                row.innerHTML = `
                    <td class="px-6 py-4">${startIndex + index + 1}</td>
                    <td class="px-6 py-4">${agenda.title || '-'}</td>
                    <td class="px-6 py-4">${formatDate(agenda.tanggal) || '-'}</td>
                    <td class="px-6 py-4">${agenda.keterangan || '-'}</td>
                    <td class="px-6 py-4">
                        <span class="px-2 inline-flex text-xs leading-5 font-semibold rounded-full ${
                            isActive 
                            ? 'bg-green-100 text-green-800' 
                            : 'bg-red-100 text-red-800'
                        }">
                            ${isActive ? 'Aktif' : 'Nonaktif'}
                        </span>
                    </td>
                    <td class="px-6 py-4 whitespace-nowrap text-sm font-medium flex space-x-3">
                        <a href="/manage/agenda/${agenda.id}/edit" class="text-blue-600 hover:text-blue-900">
                            <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M11 5H6a2 2 0 00-2 2v11a2 2 0 002 2h11a2 2 0 002-2v-5m-1.414-9.414a2 2 0 112.828 2.828L11.828 15H9v-2.828l8.586-8.586z"/>
                            </svg>
                        </a>
                        <button onclick="deleteAgenda(${agenda.id})" class="text-red-600 hover:text-red-900">
                            <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 7l-.867 12.142A2 2 0 0116.138 21H7.862a2 2 0 01-1.995-1.858L5 7m5 4v6m4-6v6m1-10V4a1 1 0 00-1-1h-4a1 1 0 00-1 1v3M4 7h16"/>
                            </svg>
                        </button>
                    </td>
                `;
                tableBody.appendChild(row);
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
            showLoading();
            // Reset error messages
            document.querySelectorAll('.text-red-500').forEach(el => {
                el.textContent = '';
                el.classList.add('hidden');
            });
            
            const formData = {
                title: document.getElementById('title').value,
                tanggal: document.getElementById('tanggal').value,
                keterangan: document.getElementById('keterangan').value
            };
            
            axios.post('/api/agenda', formData, {
                headers: {
                    'Content-Type': 'application/json',
                    'Accept': 'application/json',
                    'Authorization': 'Bearer {{ session("api_token") }}'
                }
            })
            .then(function(response) {
                hideLoading();
                
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
                hideLoading();
                
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
            Swal.fire({
                title: 'Apakah Anda yakin?',
                text: "Data pengaturan yang dihapus tidak dapat dikembalikan!",
                icon: 'warning',
                showCancelButton: true,
                confirmButtonColor: '#3085d6',
                cancelButtonColor: '#d33',
                confirmButtonText: '<span style="color: white;">Ya, hapus!</span>',
                cancelButtonText: '<span style="color: white;">Batal</span>',
                reverseButtons: true
            }).then((result) => {
                if (result.isConfirmed) {
                    showLoading();
                    axios.delete(`/api/agenda/${id}`, {
                        headers: {
                            'X-CSRF-TOKEN': document.querySelector('meta[name="csrf-token"]').content,
                            'Authorization': 'Bearer ' + document.querySelector('meta[name="api-token"]').content
                        }
                    })
                    .then(response => {
                        hideLoading();
                        if (response.data.success) {
                            notyf.success({
                                message: 'Agenda berhasil dihapus',
                                duration: 2000,
                                dismissible: true
                            });
                            loadAgendaData();
                        }
                    })
                    .catch(error => {
                        hideLoading();
                        console.error('Error:', error);
                        notyf.error({
                            message: 'Gagal menghapus agenda',
                            duration: 2000,
                            dismissible: true
                        });
                    });
                }
            });
        }

        function editAgenda(id) {
            window.location.href = `/manage/agenda/${id}/edit`;
        }

        function updateAgenda() {
            showLoading();
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
            
            axios.put(`/api/agenda/${id}`, formData, {
                headers: {
                    'Content-Type': 'application/json',
                    'Accept': 'application/json',
                    'Authorization': 'Bearer {{ session("api_token") }}'
                }
            })
            .then(function(response) {
                hideLoading();
                
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
                hideLoading();
                
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
            });
        }

        function closeEditModal() {
            document.getElementById('editModal').classList.add('hidden');
            document.getElementById('editForm').reset();
        }

        // Inisialisasi Notyf
        const notyf = new Notyf({
            duration: 3000,
            position: {
                x: 'right',
                y: 'top',
            },
            types: [
                {
                    type: 'success',
                    background: '#10B981',
                    icon: false
                },
                {
                    type: 'error',
                    background: '#EF4444',
                    icon: false
                }
            ]
        });

        function showSuccess(message) {
            notyf.success({
                message: message,
                dismissible: true
            });
        }

        function showError(message) {
            notyf.error({
                message: message,
                dismissible: true
            });
        }

        // Tampilkan notifikasi jika ada session flash
        @if(session('success'))
            notyf.success("{{ session('success') }}");
        @endif

        @if(session('error'))
            notyf.error("{{ session('error') }}");
        @endif

        function previousPage() {
            if (currentPage > 1) {
                currentPage--;
                loadAgendaData();
            }
        }

        function nextPage() {
            if (currentPage < totalPages) {
                currentPage++;
                loadAgendaData();
            }
        }
    </script>
    @endpush
</x-app-layout> 