<x-app-layout>
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/notyf@3/notyf.min.css">
    <script src="https://cdn.jsdelivr.net/npm/notyf@3/notyf.min.js"></script>
    <!-- Tambahkan header -->
    <x-slot name="header">
        <div class="flex justify-between items-center">
            <h2 class="font-semibold text-xl text-gray-800 leading-tight">
                {{ __('Pesan Kontak') }}
            </h2>
        </div>
    </x-slot>

    <!-- Tambahkan loading indicator -->
    <div id="loadingIndicator" class="fixed inset-0 bg-black bg-opacity-50 flex items-center justify-center z-50 hidden">
        <div class="animate-spin rounded-full h-16 w-16 border-t-2 border-b-2 border-indigo-500"></div>
    </div>

    <!-- Hapus ml-60 -->
    <div class="py-12">
        <div class="max-w-7xl mx-auto sm:px-6 lg:px-8">
            <div class="bg-white overflow-hidden shadow-sm sm:rounded-lg">
                <div class="p-6">
                    <!-- Search dan Show Entries -->
                    <div class="flex justify-between items-center mb-4">
                        <div class="flex items-center space-x-2">
                            <span>Show</span>
                            <select id="entriesPerPage" class="border rounded px-2 py-1 w-20" onchange="loadContactData()">
                                <option value="10">10</option>
                                <option value="25">25</option>
                                <option value="50">50</option>
                                <option value="100">100</option>
                            </select>
                            <span>entries</span>
                        </div>
                        <div class="flex items-center">
                            <span class="mr-2">Search:</span>
                            <input type="text" id="searchInput" class="border rounded px-3 py-1" onkeyup="loadContactData()" placeholder="Search...">
                        </div>
                    </div>

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
                                    <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">No</th>
                                    <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Nama</th>
                                    <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Email</th>
                                    <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Subjek</th>
                                    <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Pesan</th>
                                    <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Tanggal</th>
                                    <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Aksi</th>
                                </tr>
                            </thead>
                            <tbody id="contactTableBody" class="bg-white divide-y divide-gray-200">
                                <!-- Data akan dirender melalui JavaScript -->
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

    <!-- View Message Modal -->
    <div x-data="{ open: false, message: '' }" 
         x-show="open" 
         @view-message.window="open = true; message = $event.detail"
         class="fixed inset-0 z-50 overflow-y-auto" 
         style="display: none;">
        <div class="flex items-center justify-center min-h-screen p-4">
            <div class="fixed inset-0 bg-gray-500 bg-opacity-75 transition-opacity"></div>

            <div class="relative bg-white rounded-lg w-full max-w-lg">
                <div class="p-6">
                    <h3 class="text-lg font-medium text-gray-900 mb-4">Detail Pesan</h3>
                    <div class="space-y-4 max-h-[60vh] overflow-y-auto pr-2">
                        <div x-html="message"></div>
                    </div>
                    <div class="mt-5 flex justify-end">
                        <button @click="open = false" 
                                class="px-4 py-2 text-sm font-medium text-gray-700 bg-white border border-gray-300 rounded-md hover:bg-gray-50">
                            Tutup
                        </button>
                    </div>
                </div>
            </div>
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
        const notyf = new Notyf({
            duration: 3000,
            position: {x: 'right', y: 'top'},
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

        let currentPage = 1;
        let totalPages = 1;
        let filteredData = [];

        // Fungsi untuk menampilkan/menyembunyikan loading
        function showLoading() {
            document.getElementById('loadingIndicator').classList.remove('hidden');
        }

        function hideLoading() {
            document.getElementById('loadingIndicator').classList.add('hidden');
        }

        function loadContactData() {
            showLoading();
            const perPage = document.getElementById('entriesPerPage').value;
            const searchQuery = document.getElementById('searchInput').value.toLowerCase();
            
            axios.get('/api/contact', {
                headers: {
                    'Authorization': 'Bearer ' + document.querySelector('meta[name="api-token"]').content
                }
            })
            .then(response => {
                const contacts = Array.isArray(response.data) ? response.data : response.data.data;

                // Filter data berdasarkan pencarian
                filteredData = contacts.filter(contact => 
                    contact.nama.toLowerCase().includes(searchQuery) ||
                    contact.email.toLowerCase().includes(searchQuery) ||
                    contact.subjek.toLowerCase().includes(searchQuery)
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
            })
            .catch(error => {
                console.error('Error:', error);
                notyf.error('Gagal memuat data kontak');
            })
            .finally(() => {
                hideLoading();
            });
        }

        function updateTable(contacts, startIndex) {
            const tbody = document.getElementById('contactTableBody');
            tbody.innerHTML = '';

            if (contacts.length === 0) {
                tbody.innerHTML = `
                    <tr>
                        <td colspan="7" class="px-6 py-4 text-center text-sm text-gray-500">
                            Tidak ada pesan kontak yang tersedia
                        </td>
                    </tr>
                `;
                return;
            }

            contacts.forEach((contact, index) => {
                const row = `
                    <tr>
                        <td class="px-6 py-4 whitespace-nowrap">
                            <div class="text-sm text-gray-900">${startIndex + index + 1}</div>
                        </td>
                        <td class="px-6 py-4">
                            <div class="text-sm font-medium text-gray-900">${contact.nama}</div>
                        </td>
                        <td class="px-6 py-4">
                            <div class="text-sm text-gray-900">${contact.email}</div>
                        </td>
                        <td class="px-6 py-4">
                            <div class="text-sm text-gray-900">${contact.subjek}</div>
                        </td>
                        <td class="px-6 py-4">
                            <div class="text-sm text-gray-900">${contact.isi.substring(0, 50)}${contact.isi.length > 50 ? '...' : ''}</div>
                        </td>
                        <td class="px-6 py-4 whitespace-nowrap">
                            <div class="text-sm text-gray-900">${formatDate(contact.created_at)}</div>
                        </td>
                        <td class="px-6 py-4 whitespace-nowrap text-sm font-medium flex space-x-3">
                            <button onclick="viewMessage(${contact.id})" class="text-blue-600 hover:text-blue-900">
                                <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 12a3 3 0 11-6 0 3 3 0 016 0z"/>
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M2.458 12C3.732 7.943 7.523 5 12 5c4.478 0 8.268 2.943 9.542 7-1.274 4.057-5.064 7-9.542 7-4.477 0-8.268-2.943-9.542-7z"/>
                                </svg>
                            </button>
                            <button onclick="deleteMessage(${contact.id})" class="text-red-600 hover:text-red-900">
                                <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 7l-.867 12.142A2 2 0 0116.138 21H7.862a2 2 0 01-1.995-1.858L5 7m5 4v6m4-6v6m1-10V4a1 1 0 00-1-1h-4a1 1 0 00-1 1v3M4 7h16"/>
                                </svg>
                            </button>
                        </td>
                    </tr>
                `;
                tbody.insertAdjacentHTML('beforeend', row);
            });
        }

        function formatDate(dateString) {
            try {
                const date = new Date(dateString);
                if (isNaN(date.getTime())) {
                    throw new Error('Invalid date');
                }
                return new Intl.DateTimeFormat('id-ID', {
                    day: '2-digit',
                    month: 'short',
                    year: 'numeric'
                }).format(date);
            } catch (error) {
                console.error('Error formatting date:', error);
                return '';
            }
        }

        function viewMessage(id) {
            showLoading(); // Tambahkan loading saat melihat pesan
            axios.get(`/api/contact/${id}`, {
                headers: {
                    'Authorization': 'Bearer ' + document.querySelector('meta[name="api-token"]').content
                }
            })
            .then(response => {
                const contact = response.data.data || response.data;
                
                console.log('Response data:', contact);

                if (!contact) {
                    throw new Error('Data kontak tidak ditemukan');
                }

                const messageHTML = `
                    <div class="bg-gray-50 p-4 rounded-lg">
                        <div class="flex justify-between items-start">
                            <div>
                                <h3 class="font-semibold text-indigo-600 text-lg">${contact.nama}</h3>
                                <p class="text-sm text-indigo-400">${contact.email}</p>
                            </div>
                            <span class="text-xs text-gray-400 bg-gray-100 px-2 py-1 rounded">${formatDate(contact.created_at)}</span>
                        </div>
                        <div class="mt-4 bg-white p-3 rounded-md shadow-sm">
                            <div class="mb-3">
                                <span class="text-xs font-medium text-gray-500 uppercase tracking-wider">Subjek:</span>
                                <p class="text-lg font-semibold text-indigo-800">${contact.subjek}</p>
                            </div>
                            <div>
                                <span class="text-xs font-medium text-gray-500 uppercase tracking-wider">Pesan:</span>
                                <p class="mt-2 text-gray-700 whitespace-pre-wrap leading-relaxed bg-gray-50 p-3 rounded">${contact.isi}</p>
                            </div>
                        </div>
                    </div>
                `;
                
                const event = new CustomEvent('view-message', {
                    detail: messageHTML
                });
                window.dispatchEvent(event);
            })
            .catch(error => {
                console.error('Error:', error);
                notyf.error('Gagal memuat detail pesan');
            })
            .finally(() => {
                hideLoading(); // Sembunyikan loading setelah selesai
            });
        }

        function deleteMessage(id) {
            Swal.fire({
                title: 'Apakah Anda yakin?',
                text: "Pesan yang dihapus tidak dapat dikembalikan!",
                icon: 'warning',
                showCancelButton: true,
                confirmButtonColor: '#3085d6',
                cancelButtonColor: '#d33',
                confirmButtonText: '<span style="color: white;">Ya, hapus!</span>',
                cancelButtonText: '<span style="color: white;">Batal</span>',
                reverseButtons: true
            }).then((result) => {
                if (result.isConfirmed) {
                    showLoading(); // Tambahkan loading saat menghapus
                    axios.delete(`/api/contact/${id}`, {
                        headers: {
                            'X-CSRF-TOKEN': document.querySelector('meta[name="csrf-token"]').content,
                            'Authorization': 'Bearer ' + document.querySelector('meta[name="api-token"]').content
                        }
                    })
                    .then(response => {
                        if (response.data.success) {
                            notyf.success('Pesan berhasil dihapus');
                            loadContactData();
                        }
                    })
                    .catch(() => {
                        notyf.error('Gagal menghapus pesan');
                    })
                    .finally(() => {
                        hideLoading(); // Sembunyikan loading setelah selesai
                    });
                }
            });
        }

        function previousPage() {
            if (currentPage > 1) {
                currentPage--;
                loadContactData();
            }
        }

        function nextPage() {
            if (currentPage < totalPages) {
                currentPage++;
                loadContactData();
            }
        }

        // Load data saat halaman dimuat
        document.addEventListener('DOMContentLoaded', loadContactData);

        // Tampilkan notifikasi jika ada session flash
        @if(session('success'))
            notyf.success("{{ session('success') }}");
        @endif

        @if(session('error'))
            notyf.error("{{ session('error') }}");
        @endif
    </script>
    @endpush
</x-app-layout> 