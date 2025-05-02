<x-app-layout>
    <x-slot name="header">
        <div class="flex justify-between items-center">
            <h2 class="font-semibold text-xl text-gray-800 leading-tight">
                {{ __('Daftar Berita Saya') }}
            </h2>
            <a href="{{ route('user.news.create') }}" class="bg-indigo-600 hover:bg-indigo-700 text-white font-bold py-2 px-4 rounded inline-flex items-center">
                <svg class="w-4 h-4 mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 6v6m0 0v6m0-6h6m-6 0H6"></path>
                </svg>
                <span>Tambah Berita</span>
            </a>
        </div>
    </x-slot>

    <div class="py-12">
        <div class="max-w-7xl mx-auto sm:px-6 lg:px-8">
            <div class="bg-white overflow-hidden shadow-sm sm:rounded-lg">
                <div class="p-6">
                    <div class="flex flex-col md:flex-row md:justify-between md:items-center mb-4 space-y-4 md:space-y-0">
                        <div class="flex items-center space-x-2">
                            <span>Show</span>
                            <select id="entriesPerPage" class="border rounded px-2 py-1 w-20">
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

                    <div class="overflow-x-auto relative">
                        <div id="loadingIndicator" class="absolute inset-0 bg-white bg-opacity-80 z-10 hidden">
                            <div class="flex items-center justify-center h-full">
                                <div class="animate-spin rounded-full h-8 w-8 border-b-2 border-indigo-600"></div>
                            </div>
                        </div>

                        <table class="min-w-full divide-y divide-gray-200">
                            <thead class="bg-gray-50">
                                <tr>
                                    <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider border-b">No</th>
                                    <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider border-b">Judul</th>
                                    <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider border-b">Kategori</th>
                                    <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider border-b">Tanggal</th>
                                    <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider border-b">Status</th>
                                    <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider border-b">Aksi</th>
                                </tr>
                            </thead>
                            <tbody id="newsTableBody" class="bg-white divide-y divide-gray-200">
                            </tbody>
                        </table>
                    </div>

                    <div class="flex justify-between items-center mt-4">
                        <div id="tableInfo" class="text-sm text-gray-700">
                            Showing <span id="startEntry">1</span> to <span id="endEntry">10</span> of <span id="totalEntries">0</span> entries
                        </div>
                        <div class="flex items-center space-x-2">
                            <button id="prevBtn" class="px-3 py-1 border rounded hover:bg-gray-100 disabled:opacity-50 disabled:cursor-not-allowed">Prev</button>
                            <div class="flex items-center space-x-1">
                                <span>Page</span>
                                <span id="currentPageDisplay">1</span>
                                <span>of</span>
                                <span id="totalPagesDisplay">1</span>
                            </div>
                            <button id="nextBtn" class="px-3 py-1 border rounded hover:bg-gray-100 disabled:opacity-50 disabled:cursor-not-allowed">Next</button>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>

    @push('scripts')
    <script src="https://cdn.jsdelivr.net/npm/sweetalert2@11"></script>
    <script src="https://cdn.jsdelivr.net/npm/axios/dist/axios.min.js"></script>
    <script>
        let currentPage = 1;
        let totalPages = 1;
        let allNews = [];
        let filteredData = [];

        function showLoading() {
            document.getElementById('loadingIndicator').classList.remove('hidden');
        }

        function hideLoading() {
            document.getElementById('loadingIndicator').classList.add('hidden');
        }

        document.addEventListener('DOMContentLoaded', function() {
            loadNewsData();
            
            document.getElementById('searchInput').addEventListener('input', handleSearch);
            document.getElementById('entriesPerPage').addEventListener('change', handleEntriesChange);
            document.getElementById('prevBtn').addEventListener('click', previousPage);
            document.getElementById('nextBtn').addEventListener('click', nextPage);
        });

        function handleSearch() {
            const searchQuery = document.getElementById('searchInput').value.toLowerCase();
            
            filteredData = allNews.filter(news => 
                news.title.toLowerCase().includes(searchQuery) ||
                news.kategori.name.toLowerCase().includes(searchQuery)
            );

            currentPage = 1;
            updateTableDisplay();
        }

        function handleEntriesChange() {
            currentPage = 1;
            updateTableDisplay();
        }

        function loadNewsData() {
            showLoading();
            
            axios.get('/api/user/news', {
                headers: {
                    'Authorization': 'Bearer {{ session("api_token") }}',
                    'Accept': 'application/json'
                }
            })
            .then(response => {
                hideLoading();
                allNews = response.data.data || response.data;
                filteredData = allNews;
                updateTableDisplay();
            })
            .catch(error => {
                hideLoading();
                console.error('Error:', error);
                Swal.fire('Error!', error.response?.data?.message || 'Gagal memuat data berita', 'error');
            });
        }

        function updateTableDisplay() {
            const perPage = parseInt(document.getElementById('entriesPerPage').value);
            totalPages = Math.ceil(filteredData.length / perPage);
            
            if (currentPage > totalPages) {
                currentPage = totalPages || 1;
            }

            const startIndex = (currentPage - 1) * perPage;
            const endIndex = Math.min(startIndex + perPage, filteredData.length);
            const currentPageData = filteredData.slice(startIndex, endIndex);

            document.getElementById('currentPageDisplay').textContent = currentPage;
            document.getElementById('totalPagesDisplay').textContent = totalPages;
            document.getElementById('startEntry').textContent = filteredData.length ? startIndex + 1 : 0;
            document.getElementById('endEntry').textContent = endIndex;
            document.getElementById('totalEntries').textContent = filteredData.length;
            
            document.getElementById('prevBtn').disabled = currentPage === 1;
            document.getElementById('nextBtn').disabled = currentPage === totalPages;

            updateTable(currentPageData, startIndex);
        }

        function updateTable(news, startIndex) {
            const tbody = document.getElementById('newsTableBody');
            tbody.innerHTML = '';

            if (news.length === 0) {
                tbody.innerHTML = `
                    <tr>
                        <td colspan="6" class="px-6 py-4 text-center text-sm text-gray-500 border-b">
                            Tidak ada berita yang tersedia
                        </td>
                    </tr>
                `;
                return;
            }

            news.forEach((item, index) => {
                let statusBadge = '';
                if (item.status === 0) {
                    statusBadge = '<span class="px-2 py-1 text-xs font-semibold rounded-full bg-yellow-100 text-yellow-800">Menunggu Persetujuan</span>';
                } else if (item.status === 1) {
                    statusBadge = '<span class="px-2 py-1 text-xs font-semibold rounded-full bg-green-100 text-green-800">Disetujui</span>';
                } else if (item.status === 2) {
                    statusBadge = '<span class="px-2 py-1 text-xs font-semibold rounded-full bg-red-100 text-red-800">Ditolak</span>';
                } else {
                    statusBadge = '<span class="px-2 py-1 text-xs font-semibold rounded-full bg-gray-100 text-gray-800">Status Tidak Diketahui</span>';
                }

                const row = `
                    <tr class="hover:bg-gray-50">
                        <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-900 border-b">${startIndex + index + 1}</td>
                        <td class="px-6 py-4 text-sm text-gray-900 border-b">${item.title}</td>
                        <td class="px-6 py-4 text-sm text-gray-900 border-b">${item.kategori?.name || '-'}</td>
                        <td class="px-6 py-4 text-sm text-gray-900 border-b">${new Date(item.created_at).toLocaleDateString('id-ID', {
                            day: 'numeric',
                            month: 'short',
                            year: 'numeric'
                        })}</td>
                        <td class="px-6 py-4 border-b">${statusBadge}</td>
                        <td class="px-6 py-4 whitespace-nowrap text-sm font-medium border-b">
                            <div class="flex space-x-3">
                                <a href="/my/news/${item.id}/edit" class="text-blue-600 hover:text-blue-900">
                                    <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M11 5H6a2 2 0 00-2 2v11a2 2 0 002 2h11a2 2 0 002-2v-5m-1.414-9.414a2 2 0 112.828 2.828L11.828 15H9v-2.828l8.586-8.586z"/>
                                    </svg>
                                </a>
                                <button onclick="deleteNews(${item.id})" class="text-red-600 hover:text-red-900">
                                    <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 7l-.867 12.142A2 2 0 0116.138 21H7.862a2 2 0 01-1.995-1.858L5 7m5 4v6m4-6v6m1-10V4a1 1 0 00-1-1h-4a1 1 0 00-1 1v3M4 7h16"/>
                                    </svg>
                                </button>
                            </div>
                        </td>
                    </tr>
                `;
                tbody.insertAdjacentHTML('beforeend', row);
            });
        }

        function previousPage() {
            if (currentPage > 1) {
                currentPage--;
                updateTableDisplay();
            }
        }

        function nextPage() {
            if (currentPage < totalPages) {
                currentPage++;
                updateTableDisplay();
            }
        }

        function deleteNews(id) {
            Swal.fire({
                title: 'Apakah Anda yakin?',
                text: "Berita akan dihapus secara permanen!",
                icon: 'warning',
                showCancelButton: true,
                confirmButtonColor: '#3085d6',
                cancelButtonColor: '#d33',
                confirmButtonText: 'Ya, hapus!',
                cancelButtonText: 'Batal'
            }).then((result) => {
                if (result.isConfirmed) {
                    showLoading();
                    axios.post(`/api/news/${id}`, {
                        _method: 'DELETE',
                        _token: document.querySelector('meta[name="csrf-token"]').content
                    }, {
                        headers: {
                            'Accept': 'application/json',
                            'Authorization': 'Bearer {{ session("api_token") }}',
                            'X-CSRF-TOKEN': document.querySelector('meta[name="csrf-token"]').content
                        }
                    })
                    .then(response => {
                        hideLoading();
                        if (response.data.success) {
                            Swal.fire(
                                'Dihapus!',
                                'Berita berhasil dihapus.',
                                'success'
                            ).then(() => {
                                loadNewsData();
                            });
                        }
                    })
                    .catch(error => {
                        hideLoading();
                        console.error('Error:', error);
                        Swal.fire(
                            'Error!',
                            error.response?.data?.message || 'Terjadi kesalahan saat menghapus berita.',
                            'error'
                        );
                    });
                }
            });
        }
    </script>
    @endpush
</x-app-layout> 