<x-app-layout>
    <x-slot name="header">
        <div class="flex justify-between items-center">
            <h2 class="font-semibold text-xl text-gray-800 leading-tight">
                {{ __('Kelola Klaster') }}
            </h2>
            <a href="{{ route('admin.klaster.create') }}" class="bg-indigo-600 hover:bg-indigo-700 text-white font-bold py-2 px-4 rounded inline-flex items-center">
                <i class="fas fa-plus mr-2"></i>
                <span>Tambah Klaster</span>
            </a>
        </div>
    </x-slot>

    <div class="py-12">
        <div class="max-w-7xl mx-auto sm:px-6 lg:px-8">
            <div class="bg-white overflow-hidden shadow-sm sm:rounded-lg">
                <div class="p-6">
                    <div class="flex flex-col md:flex-row justify-between items-center gap-4 mb-6">
                        <div class="flex items-center gap-2">
                            <label for="entries" class="text-sm text-gray-700">Show</label>
                            <select id="entries" class="border-gray-300 rounded-md shadow-sm focus:ring-indigo-500 focus:border-indigo-500">
                                <option>10</option>
                                <option>25</option>
                                <option>50</option>
                                <option>100</option>
                            </select>
                            <span class="text-sm text-gray-700">entries</span>
                        </div>
                        <div class="relative w-full md:w-auto">
                            <div class="flex items-center gap-2">
                                <span class="text-gray-500 text-sm">Search:</span>
                                <div class="relative flex-1">
                                    <input type="text" 
                                        id="searchInput" 
                                        placeholder="Cari..." 
                                        class="w-full pl-10 pr-4 py-2 border rounded-lg focus:outline-none focus:ring-2 focus:ring-indigo-500">
                                    <div class="absolute inset-y-0 left-0 pl-3 flex items-center pointer-events-none">
                                        <svg class="h-5 w-5 text-gray-400" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M21 21l-6-6m2-5a7 7 0 11-14 0 7 7 0 0114 0z"/>
                                        </svg>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>

                    <div class="overflow-x-auto">
                        <table class="min-w-full divide-y divide-gray-200 table-fixed">
                            <thead class="bg-gray-50">
                                <tr>
                                    <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider w-12">No</th>
                                    <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider w-[50%]">KLASTER</th>
                                    <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider w-[20%]">JUMLAH INDIKATOR</th>
                                    <th class="px-6 py-3 text-right text-xs font-medium text-gray-500 uppercase tracking-wider w-[20%]">AKSI</th>
                                </tr>
                            </thead>
                            <tbody id="klasterTableBody" class="bg-white divide-y divide-gray-200">
                            </tbody>
                        </table>
                        
                        <div id="loadingSpinner" class="hidden">
                            <div class="flex justify-center items-center py-4">
                                <div class="animate-spin rounded-full h-8 w-8 border-b-2 border-indigo-600"></div>
                            </div>
                        </div>

                        <div class="flex items-center justify-between px-4 py-3 bg-white border-t border-gray-200 sm:px-6">
                            <div class="flex items-center">
                                <span id="paginationInfo" class="text-sm text-gray-700"></span>
                            </div>
                            <div class="flex items-center space-x-2">
                                <button id="prevPage" class="px-4 py-2 border rounded-md text-sm font-medium text-gray-700 bg-white hover:bg-gray-50 disabled:opacity-50 disabled:cursor-not-allowed">
                                    Prev
                                </button>
                                <span class="text-sm text-gray-700">Page <span id="currentPageSpan">1</span> of <span id="totalPagesSpan">1</span></span>
                                <button id="nextPage" class="px-4 py-2 border rounded-md text-sm font-medium text-gray-700 bg-white hover:bg-gray-50 disabled:opacity-50 disabled:cursor-not-allowed">
                                    Next
                                </button>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>

    @push('scripts')
    <script src="https://cdn.jsdelivr.net/npm/sweetalert2@11"></script>
    <script src="https://cdn.jsdelivr.net/npm/notyf@3/notyf.min.js"></script>
    <script>
        const notyf = new Notyf({
            duration: 3000,
            position: { x: 'right', y: 'top' },
            dismissible: true,
            types: [
                {
                    type: 'success',
                    background: '#4CAF50'
                },
                {
                    type: 'error',
                    background: '#f44336'
                }
            ]
        });

        let currentPage = 1;
        let totalPages = 1;
        let searchQuery = '';
        const perPage = 10;
        let allKlasterData = [];

        async function fetchAllKlaster() {
            try {
                document.getElementById('loadingSpinner').classList.remove('hidden');
                
                const csrfToken = document.querySelector('meta[name="csrf-token"]').getAttribute('content');
                const apiToken = document.querySelector('meta[name="api-token"]')?.getAttribute('content') || '';
                
                const response = await axios.get(`/api/klaster?per_page=1000`, {
                    headers: {
                        'Accept': 'application/json',
                        'X-Requested-With': 'XMLHttpRequest',
                        'X-CSRF-TOKEN': csrfToken,
                        'Authorization': `Bearer ${apiToken}`
                    }
                });

                const data = response.data;
                
                if (!data.data || data.data.length === 0) {
                    return [];
                }

                return data.data;
            } catch (error) {
                console.error('Error:', error);
                notyf.error(error.response?.data?.message || error.message || 'Gagal memuat data klaster');
                return [];
            } finally {
                document.getElementById('loadingSpinner').classList.add('hidden');
            }
        }

        function renderTable() {
            const tableBody = document.getElementById('klasterTableBody');
            tableBody.innerHTML = '';
            
            let filteredData = allKlasterData;
            if (searchQuery.trim() !== '') {
                const searchLower = searchQuery.toLowerCase();
                filteredData = allKlasterData.filter(klaster => 
                    klaster.name && klaster.name.toLowerCase().includes(searchLower)
                );
            }
            
            totalPages = Math.ceil(filteredData.length / perPage);
            
            const startIndex = (currentPage - 1) * perPage;
            const endIndex = startIndex + perPage;
            const currentPageData = filteredData.slice(startIndex, endIndex);
            
            if (currentPageData.length === 0) {
                tableBody.innerHTML = `
                    <tr>
                        <td colspan="4" class="px-6 py-4 text-center text-sm text-gray-500">
                            Tidak ada klaster yang tersedia
                        </td>
                    </tr>
                `;
                document.getElementById('paginationInfo').textContent = 'Showing 0 data';
                return;
            }

            currentPageData.forEach((klaster, index) => {
                const startingNumber = (currentPage - 1) * perPage + 1;
                tableBody.innerHTML += `
                    <tr>
                        <td class="px-6 py-4 w-12">
                            <div class="text-sm text-gray-900">${startingNumber + index}</div>
                        </td>
                        <td class="px-6 py-4 w-[50%]">
                            <div class="text-sm font-medium text-gray-900 break-words">${klaster.name || '-'}</div>
                        </td>
                        <td class="px-6 py-4 w-[20%]">
                            <div class="text-sm text-gray-500">${klaster.indikator_count || 0}</div>
                        </td>
                        <td class="px-6 py-4 w-[20%] text-right text-sm font-medium">
                            <div class="flex justify-end space-x-3">
                                <a href="/manage/klaster/${klaster.id}/edit" class="text-indigo-600 hover:text-indigo-900 inline-flex items-center" title="Edit Klaster">
                                    <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M11 5H6a2 2 0 00-2 2v11a2 2 0 002 2h11a2 2 0 002-2v-5m-1.414-9.414a2 2 0 112.828 2.828L11.828 15H9v-2.828l8.586-8.586z"/>
                                    </svg>
                                </a>
                                <button onclick="confirmDelete(${klaster.id})" class="text-red-600 hover:text-red-900 inline-flex items-center" title="Hapus Klaster">
                                    <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 7l-.867 12.142A2 2 0 0116.138 21H7.862a2 2 0 01-1.995-1.858L5 7m5 4v6m4-6v6m1-10V4a1 1 0 00-1-1h-4a1 1 0 00-1 1v3M4 7h16"/>
                                    </svg>
                                </button>
                            </div>
                        </td>
                    </tr>
                `;
            });

            document.getElementById('prevPage').disabled = currentPage === 1;
            document.getElementById('nextPage').disabled = currentPage === totalPages || totalPages === 0;
            
            document.getElementById('paginationInfo').textContent = 
                `Showing ${startIndex + 1} to ${Math.min(endIndex, filteredData.length)} of ${filteredData.length} data`;
            document.getElementById('currentPageSpan').textContent = currentPage;
            document.getElementById('totalPagesSpan').textContent = totalPages || 1;
        }

        async function loadKlaster() {
            try {
                if (allKlasterData.length === 0) {
                    allKlasterData = await fetchAllKlaster();
                }
                
                renderTable();
            } catch (error) {
                console.error('Error:', error);
                notyf.error(error.message || 'Gagal memuat data klaster');
                
                const tableBody = document.getElementById('klasterTableBody');
                tableBody.innerHTML = `
                    <tr>
                        <td colspan="4" class="px-6 py-4 text-center text-sm text-red-500">
                            ${error.message || 'Terjadi kesalahan saat memuat data. Silakan coba lagi.'}
                        </td>
                    </tr>
                `;
            }
        }

        document.getElementById('entries').addEventListener('change', () => {
            currentPage = 1;
            renderTable();
        });

        let searchTimeout;
        document.getElementById('searchInput').addEventListener('input', (e) => {
            clearTimeout(searchTimeout);
            searchTimeout = setTimeout(() => {
                searchQuery = e.target.value;
                currentPage = 1;
                renderTable();
            }, 300);
        });

        document.getElementById('prevPage').addEventListener('click', () => {
            if (currentPage > 1) {
                currentPage--;
                renderTable();
            }
        });

        document.getElementById('nextPage').addEventListener('click', () => {
            if (currentPage < totalPages) {
                currentPage++;
                renderTable();
            }
        });

        async function confirmDelete(id) {
            const result = await Swal.fire({
                title: 'Apakah Anda yakin?',
                text: "Klaster yang dihapus tidak dapat dikembalikan dan semua indikator di dalamnya juga akan dihapus!",
                icon: 'warning',
                showCancelButton: true,
                confirmButtonColor: '#3085d6',
                cancelButtonColor: '#d33',
                confirmButtonText: 'Ya, hapus!',
                cancelButtonText: 'Batal'
            });

            if (result.isConfirmed) {
                try {
                    const csrfToken = document.querySelector('meta[name="csrf-token"]').getAttribute('content');
                    const apiToken = document.querySelector('meta[name="api-token"]')?.getAttribute('content') || '';
                    
                    await axios.post(`/api/klaster/${id}`, {
                        _method: 'DELETE',
                        _token: csrfToken
                    }, {
                        headers: {
                            'Accept': 'application/json',
                            'X-Requested-With': 'XMLHttpRequest',
                            'X-CSRF-TOKEN': csrfToken,
                            'Authorization': `Bearer ${apiToken}`
                        }
                    });

                    notyf.success('Klaster berhasil dihapus');
                    
                    allKlasterData = await fetchAllKlaster();
                    renderTable();
                } catch (error) {
                    console.error('Error:', error);
                    
                    if (error.response) {
                        if (error.response.status === 401) {
                            notyf.error('Sesi telah berakhir. Silakan muat ulang halaman.');
                        } else if (error.response.status === 403) {
                            notyf.error('Anda tidak memiliki izin untuk menghapus klaster ini.');
                        } else if (error.response.status === 422) {
                            notyf.error('Klaster ini tidak dapat dihapus karena masih memiliki indikator di dalamnya.');
                        } else {
                            notyf.error(error.response.data?.message || 'Gagal menghapus klaster');
                        }
                    } else {
                        notyf.error(error.message || 'Gagal menghapus klaster');
                    }
                }
            }
        }

        document.addEventListener('DOMContentLoaded', () => {
            loadKlaster();
        });

        @if(session('success'))
            notyf.success("{{ session('success') }}");
        @endif

        @if(session('error'))
            notyf.error("{{ session('error') }}");
        @endif
    </script>
    @endpush

    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/notyf@3/notyf.min.css">
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/sweetalert2@11/dist/sweetalert2.min.css">
</x-app-layout> 