<x-app-layout>
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/notyf@3/notyf.min.css">
    <script src="https://cdn.jsdelivr.net/npm/notyf@3/notyf.min.js"></script>
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

    <div class="py-12">
        <div class="max-w-7xl mx-auto sm:px-6 lg:px-8">
            <div class="bg-white overflow-hidden shadow-sm sm:rounded-lg">
                <div class="p-6">
                    <div class="flex flex-col md:flex-row md:justify-between md:items-center mb-4 space-y-4 md:space-y-0">
                        <div class="flex items-center space-x-2">
                            <span>Show</span>
                            <select id="entriesPerPage" class="border rounded px-2 py-1 w-20" onchange="loadDokumenData()">
                                <option value="10">10</option>
                                <option value="25">25</option>
                                <option value="50">50</option>
                                <option value="100">100</option>
                            </select>
                            <span>entries</span>
                        </div>
                        <div class="flex items-center w-full md:w-auto">
                            <span class="mr-2">Search:</span>
                            <input type="text" id="searchInput" class="border rounded px-3 py-1 w-full md:w-auto" placeholder="Cari...">
                        </div>
                    </div>

                    <div class="overflow-x-auto">
                        <table class="min-w-full divide-y divide-gray-200">
                            <thead class="bg-gray-50">
                                <tr>
                                    <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">No</th>
                                    <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Nama</th>
                                    <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">File</th>
                                    <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Path</th>
                                    <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Aksi</th>
                                </tr>
                            </thead>
                            <tbody id="dokumenTableBody" class="bg-white divide-y divide-gray-200 relative">
                                <!-- Loading Spinner -->
                                <div id="loadingIndicator" class="absolute inset-0 bg-white bg-opacity-80 z-10 hidden">
                                    <div class="flex justify-center items-center h-full">
                                        <div class="animate-spin rounded-full h-8 w-8 border-b-2 border-indigo-500"></div>
                                    </div>
                                </div>
                            </tbody>
                        </table>
                    </div>

                    <div class="flex justify-between items-center mt-4">
                        <div id="tableInfo" class="text-sm text-gray-700">
                            Showing <span id="startEntry">1</span> to <span id="endEntry">10</span> of <span id="totalEntries">0</span> entries
                        </div>
                        <div class="flex items-center space-x-2">
                            <button id="prevBtn" onclick="previousPage()" class="px-3 py-1 border rounded hover:bg-gray-100 disabled:opacity-50 disabled:cursor-not-allowed">Prev</button>
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

        document.addEventListener('DOMContentLoaded', function() {
            loadDokumenData();
            
            const searchInput = document.getElementById('searchInput');
            searchInput.addEventListener('input', handleSearch);
        });

        function handleSearch() {
            const searchQuery = document.getElementById('searchInput').value.toLowerCase();
            const perPage = document.getElementById('entriesPerPage').value;
            
            filteredData = window.allDokumen.filter(dokumen => {
                const extension = dokumen.file ? dokumen.file.split('.').pop().toLowerCase() : '';
                const isDocument = ['pdf', 'doc', 'docx', 'xls', 'xlsx'].includes(extension);
                const matchesSearch = dokumen.name.toLowerCase().includes(searchQuery);
                return isDocument && matchesSearch;
            });

            totalPages = Math.ceil(filteredData.length / perPage);
            
            currentPage = 1;
            
            updateTableDisplay();
        }

        function loadDokumenData() {
            showLoading();
            
            axios.get('/api/media', {
                headers: {
                    'Authorization': 'Bearer {{ session("api_token") }}'
                }
            })
            .then(function(response) {
                hideLoading();
                
                window.allDokumen = response.data.data || response.data;
                filteredData = window.allDokumen.filter(item => {
                    const extension = item.file ? item.file.split('.').pop().toLowerCase() : '';
                    return ['pdf', 'doc', 'docx', 'xls', 'xlsx'].includes(extension);
                });
                updateTableDisplay();
            })
            .catch(function(error) {
                hideLoading();
                console.error('Error:', error);
                notyf.error('Gagal memuat data dokumen');
            });
        }

        function updateTableDisplay() {
            const perPage = document.getElementById('entriesPerPage').value;
            
            totalPages = Math.ceil(filteredData.length / perPage);
                
            if (currentPage > totalPages) {
                currentPage = totalPages || 1;
            }

            document.getElementById('currentPageDisplay').textContent = currentPage;
            document.getElementById('totalPagesDisplay').textContent = totalPages;

            const startIndex = (currentPage - 1) * perPage;
            const endIndex = Math.min(startIndex + parseInt(perPage), filteredData.length);
            const currentPageData = filteredData.slice(startIndex, endIndex);

            updateTable(currentPageData, startIndex);
            
            document.getElementById('startEntry').textContent = filteredData.length ? startIndex + 1 : 0;
            document.getElementById('endEntry').textContent = endIndex;
            document.getElementById('totalEntries').textContent = filteredData.length;
            
            document.getElementById('prevBtn').disabled = currentPage === 1;
            document.getElementById('nextBtn').disabled = currentPage === totalPages;
        }

        function updateTable(documents, startIndex) {
            const dokumenTableBody = document.getElementById('dokumenTableBody');
            dokumenTableBody.innerHTML = '';
            
            if (documents.length === 0) {
                dokumenTableBody.innerHTML = `
                    <tr>
                        <td colspan="6" class="px-6 py-4 text-center text-sm text-gray-500">
                            Tidak ada dokumen yang tersedia
                        </td>
                    </tr>
                `;
                return;
            }

            documents.forEach((item, index) => {
                const extension = item.file ? item.file.split('.').pop().toLowerCase() : '';
                const type = (() => {
                    switch(extension) {
                        case 'pdf': return 'PDF Document';
                        case 'doc':
                        case 'docx': return 'Word Document';
                        case 'xls':
                        case 'xlsx': return 'Excel Document';
                        default: return 'Unknown';
                    }
                })();

                const typeClass = (() => {
                    switch(extension) {
                        case 'pdf': return 'bg-red-100 text-red-800';
                        case 'doc':
                        case 'docx': return 'bg-blue-100 text-blue-800';
                        case 'xls':
                        case 'xlsx': return 'bg-green-100 text-green-800';
                        default: return 'bg-gray-100 text-gray-800';
                    }
                })();

                const row = `
                    <tr>
                        <td class="px-6 py-4 whitespace-nowrap text-sm">${index + 1}</td>
                        <td class="px-6 py-4 whitespace-nowrap text-sm">${item.name || '-'}</td>
                        <td class="px-6 py-4 whitespace-nowrap text-sm">
                            <span class="px-2 py-1 text-xs rounded-full ${typeClass}">
                                ${type}
                            </span>
                        </td>
                        <td class="px-6 py-4 whitespace-nowrap text-sm">${item.file || '-'}</td>
                        <td class="px-6 py-4 whitespace-nowrap text-sm font-medium flex space-x-3">
                            <a href="${item.path}" target="_blank" class="text-blue-600 hover:text-blue-900">
                                <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M4 16v1a3 3 0 003 3h10a3 3 0 003-3v-1m-4-4l-4 4m0 0l-4-4m4 4V4"/>
                                </svg>
                            </a>
                            <a href="{{ route('admin.dokumen.index') }}/${item.id}/edit" class="text-indigo-600 hover:text-indigo-900">
                                <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M11 5H6a2 2 0 00-2 2v11a2 2 0 002 2h11a2 2 0 002-2v-5m-1.414-9.414a2 2 0 112.828 2.828L11.828 15H9v-2.828l8.586-8.586z"/>
                                </svg>
                            </a>
                            <button onclick="deleteDokumen(${item.id})" class="text-red-600 hover:text-red-900">
                                <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 7l-.867 12.142A2 2 0 0116.138 21H7.862a2 2 0 01-1.995-1.858L5 7m5 4v6m4-6v6m1-10V4a1 1 0 00-1-1h-4a1 1 0 00-1 1v3M4 7h16"/>
                                </svg>
                            </button>
                        </td>
                    </tr>
                `;
                dokumenTableBody.innerHTML += row;
            });
        }

        function previousPage() {
            if (currentPage > 1) {
                currentPage--;
                loadDokumenData();
            }
        }

        function nextPage() {
            if (currentPage < totalPages) {
                currentPage++;
                loadDokumenData();
            }
        }

        function deleteDokumen(id) {
            Swal.fire({
                title: 'Apakah Anda yakin?',
                text: "Dokumen yang dihapus tidak dapat dikembalikan!",
                icon: 'warning',
                showCancelButton: true,
                confirmButtonColor: '#3085d6',
                cancelButtonColor: '#d33',
                confirmButtonText: 'Ya, hapus!',
                cancelButtonText: 'Batal'
            }).then((result) => {
                if (result.isConfirmed) {
                    showLoading();
                    axios.delete(`/api/media/${id}`, {
                        headers: {
                            'X-CSRF-TOKEN': document.querySelector('meta[name="csrf-token"]').content,
                            'Authorization': 'Bearer ' + document.querySelector('meta[name="api-token"]').content
                        }
                    })
                    .then(response => {
                        if (response.data.success) {
                            notyf.success('Dokumen berhasil dihapus');
                            loadDokumenData();
                        }
                    })
                    .catch(error => {
                        console.error('Error:', error);
                        notyf.error('Gagal menghapus dokumen');
                    })
                    .finally(() => {
                        hideLoading();
                    });
                }
            });
        }

        function showLoading() {
            document.getElementById('loadingIndicator').classList.remove('hidden');
        }

        function hideLoading() {
            document.getElementById('loadingIndicator').classList.add('hidden');
        }

        @if(session('success'))
            notyf.success("{{ session('success') }}");
        @endif

        @if(session('error'))
            notyf.error("{{ session('error') }}");
        @endif

        function openUploadModal() {
            window.location.href = "{{ route('admin.dokumen.create') }}";
        }
    </script>
    @endpush
</x-app-layout> 