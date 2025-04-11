<x-app-layout>
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/@sweetalert2/theme-material-ui/material-ui.css">
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/notyf@3/notyf.min.css">
    <script src="https://cdn.jsdelivr.net/npm/notyf@3/notyf.min.js"></script>
    <x-slot name="header">
        <div class="flex justify-between items-center">
            <h2 class="font-semibold text-xl text-gray-800 leading-tight">
                {{ __('Pengaturan Video') }}
            </h2>
            <a href="{{ route('admin.setting.video.create') }}" class="bg-indigo-600 hover:bg-indigo-700 text-white font-bold py-2 px-4 rounded inline-flex items-center">
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
                    <div class="flex flex-col md:flex-row md:justify-between md:items-center mb-4 space-y-4 md:space-y-0">
                        <div class="flex items-center space-x-2">
                            <span>Show</span>
                            <select id="entriesPerPage" class="border rounded px-2 py-1 w-20" onchange="loadSettingsData()">
                                <option value="10">10</option>
                                <option value="25">25</option>
                                <option value="50">50</option>
                                <option value="100">100</option>
                            </select>
                            <span>entries</span>
                        </div>
                        <div class="flex items-center w-full md:w-auto">
                            <span class="mr-2">Search:</span>
                            <input type="text" id="searchInput" class="border rounded px-3 py-1 w-full md:w-auto" placeholder="Search...">
                        </div>
                    </div>

                    <div class="overflow-x-auto">
                        <table class="min-w-full divide-y divide-gray-200">
                            <thead class="bg-gray-50">
                                <tr>
                                    <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">No</th>
                                    <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Nama</th>
                                    <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Halaman</th>
                                    <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">URL</th>
                                    <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Tipe</th>
                                    <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Deskripsi</th>
                                    {{-- <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Gambar</th> --}}
                                    <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Aksi</th>
                                </tr>
                            </thead>
                            <tbody id="settingsTableBody" class="bg-white divide-y divide-gray-200 relative">
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

        @if(session('success'))
            notyf.success("{{ session('success') }}");
        @endif

        @if(session('error'))
            notyf.error("{{ session('error') }}");
        @endif

        let currentPage = 1;
        let totalPages = 1;
        let filteredData = [];
        let allSettings = [];

        document.addEventListener('DOMContentLoaded', function() {
            loadSettingsData();
            
            const searchInput = document.getElementById('searchInput');
            searchInput.addEventListener('input', handleSearch);
        });

        function handleSearch() {
            const searchQuery = document.getElementById('searchInput').value.toLowerCase();
            
            filteredData = allSettings.filter(setting => 
                setting.name.toLowerCase().includes(searchQuery) ||
                setting.page.toLowerCase().includes(searchQuery) ||
                setting.type.toLowerCase().includes(searchQuery)
            );

            currentPage = 1;
            
            updateTableDisplay();
        }

        function loadSettingsData() {
            showLoading();
            
            axios.get('/api/setting', {
                headers: {
                    'X-CSRF-TOKEN': '{{ csrf_token() }}',
                    'Accept': 'application/json',
                    'Authorization': 'Bearer {{ session("api_token") }}'
                }
            })
            .then(function(response) {
                if (response.data.success) {
                    allSettings = response.data.data;
                    filteredData = allSettings;
                    updateTableDisplay();
                } else {
                    notyf.error('Gagal memuat data pengaturan');
                }
            })
            .catch(function(error) {
                console.error('Error:', error);
                notyf.error('Terjadi kesalahan saat memuat data pengaturan');
            })
            .finally(function() {
                hideLoading();
            });
        }

        function updateTableDisplay() {
            const perPage = parseInt(document.getElementById('entriesPerPage').value);
            
            filteredData = filteredData.filter(setting => setting.type === 'video');
            
            totalPages = Math.ceil(filteredData.length / perPage);
            
            if (currentPage > totalPages) {
                currentPage = totalPages || 1;
            }

            const startIndex = (currentPage - 1) * perPage;
            const endIndex = Math.min(startIndex + perPage, filteredData.length);
            const currentPageData = filteredData.slice(startIndex, endIndex);

            updateTable(currentPageData, startIndex);
            
            updateTableInfo(startIndex, endIndex, filteredData.length);
        }

        function updateTable(settings, startIndex) {
            const tableBody = document.getElementById('settingsTableBody');
            tableBody.innerHTML = '';

            if (settings.length === 0) {
                tableBody.innerHTML = `
                    <tr>
                        <td colspan="8" class="px-6 py-4 text-center text-sm text-gray-500">
                            Tidak ada pengaturan yang tersedia
                        </td>
                    </tr>
                `;
                return;
            }

            settings.forEach((setting, index) => {
                const row = document.createElement('tr');
                row.innerHTML = `
                    <td class="px-6 py-4 whitespace-nowrap">
                        <div class="text-sm text-gray-900">${startIndex + index + 1}</div>
                    </td>
                    <td class="px-6 py-4 whitespace-no-wrap border-b border-gray-200">${setting.name || '-'}</td>
                    <td class="px-6 py-4 whitespace-no-wrap border-b border-gray-200">${setting.page || '-'}</td>
                    <td class="px-6 py-4 whitespace-no-wrap border-b border-gray-200">${setting.url || '-'}</td>
                    <td class="px-6 py-4 whitespace-no-wrap border-b border-gray-200">${setting.type || '-'}</td>
                    <td class="px-6 py-4 whitespace-no-wrap border-b border-gray-200">
                        ${setting.content ? `<div class="truncate max-w-xs">${setting.content}</div>` : '-'}
                    </td>
                    <td class="px-6 py-4 whitespace-nowrap text-sm font-medium flex space-x-3">
                        <a href="/manage/setting/video/edit/${setting.id}" class="text-blue-600 hover:text-blue-900">
                            <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M11 5H6a2 2 0 00-2 2v11a2 2 0 002 2h11a2 2 0 002-2v-5m-1.414-9.414a2 2 0 112.828 2.828L11.828 15H9v-2.828l8.586-8.586z"/>
                            </svg>
                        </a>
                        <button onclick="deleteSetting(${setting.id})" class="text-red-600 hover:text-red-900">
                            <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 7l-.867 12.142A2 2 0 0116.138 21H7.862a2 2 0 01-1.995-1.858L5 7m5 4v6m4-6v6m1-10V4a1 1 0 00-1-1h-4a1 1 0 00-1 1v3M4 7h16"/>
                            </svg>
                        </button>
                    </td>
                `;
                tableBody.appendChild(row);
            });
        }

        function updateTableInfo(startIndex, endIndex, total) {
            document.getElementById('startEntry').textContent = total ? startIndex + 1 : 0;
            document.getElementById('endEntry').textContent = endIndex;
            document.getElementById('totalEntries').textContent = total;
            document.getElementById('currentPageDisplay').textContent = currentPage;
            document.getElementById('totalPagesDisplay').textContent = totalPages;
            
            document.getElementById('prevBtn').disabled = currentPage === 1;
            document.getElementById('nextBtn').disabled = currentPage === totalPages;
        }

        function showLoading() {
            document.getElementById('loadingIndicator').classList.remove('hidden');
        }

        function hideLoading() {
            document.getElementById('loadingIndicator').classList.add('hidden');
        }

        function previousPage() {
            if (currentPage > 1) {
                currentPage--;
                loadSettingsData();
            }
        }

        function nextPage() {
            if (currentPage < totalPages) {
                currentPage++;
                loadSettingsData();
            }
        }

        function deleteSetting(id) {
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
                    axios.delete(`/api/setting/${id}`, {
                        headers: {
                            'X-CSRF-TOKEN': document.querySelector('meta[name="csrf-token"]').content,
                            'Authorization': 'Bearer ' + document.querySelector('meta[name="api-token"]').content
                        }
                    })
                    .then(response => {
                        if (response.data.success) {
                            notyf.success('Pengaturan berhasil dihapus');
                            setTimeout(() => {
                                window.location.reload();
                            }, 1500);
                        }
                    })
                    .catch(() => {
                        notyf.error('Gagal menghapus pengaturan');
                    });
                }
            });
        }
    </script>
    @endpush
</x-app-layout> 