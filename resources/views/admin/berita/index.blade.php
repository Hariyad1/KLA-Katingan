<x-app-layout>
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/notyf@3/notyf.min.css">
    <script src="https://cdn.jsdelivr.net/npm/notyf@3/notyf.min.js"></script>
    <x-slot name="header">
        <div class="flex justify-between items-center">
            <h2 class="font-semibold text-xl text-gray-800 leading-tight">
                {{ __('Manajemen Berita') }}
            </h2>
            {{-- <a href="{{ route('admin.news.create') }}" class="bg-indigo-600 hover:bg-indigo-700 text-white font-bold py-2 px-4 rounded inline-flex items-center">
                <svg class="w-4 h-4 mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 6v6m0 0v6m0-6h6m-6 0H6"></path>
                </svg>
                <span>Tambah Berita</span>
            </a> --}}
        </div>
    </x-slot>

    <div class="py-12">
        <div class="max-w-7xl mx-auto sm:px-6 lg:px-8">
            <div class="bg-white overflow-hidden shadow-sm sm:rounded-lg">
                <div class="p-6">
                    <div class="flex flex-col md:flex-row md:justify-between md:items-center mb-4 space-y-4 md:space-y-0">
                        <div class="flex items-center space-x-2">
                            <span>Show</span>
                            <select id="entriesPerPage" class="border rounded px-2 py-1 w-20" onchange="loadNewsData()">
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
                        <div id="loadingIndicator" class="absolute inset-0 bg-white z-10 hidden">
                            <div class="flex justify-center items-center py-4">
                                <div class="animate-spin rounded-full h-8 w-8 border-b-2 border-indigo-500"></div>
                            </div>
                        </div>

                        <table class="min-w-full divide-y divide-gray-200">
                            <thead class="bg-gray-50">
                                <tr>
                                    <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider border-b">No</th>
                                    <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider border-b">Judul</th>
                                    <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider border-b">Kategori</th>
                                    <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider border-b">Penulis</th>
                                    <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider border-b">Tanggal</th>
                                    <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider border-b">Status</th>
                                    <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider border-b">Aksi</th>
                                </tr>
                            </thead>
                            <tbody id="newsTableBody" class="bg-white divide-y divide-gray-200">
                                @foreach($news as $item)
                                    <tr class="hover:bg-gray-50">
                                        <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-900 border-b">{{ $loop->iteration }}</td>
                                        <td class="px-6 py-4 text-sm text-gray-900 border-b">{{ $item->title }}</td>
                                        <td class="px-6 py-4 text-sm text-gray-900 border-b">{{ $item->kategori->name }}</td>
                                        <td class="px-6 py-4 text-sm text-gray-900 border-b">{{ $item->creator->name }}</td>
                                        <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-900 border-b">{{ $item->created_at->format('d M Y') }}</td>
                                        <td class="px-6 py-4 border-b">
                                            @if($item->status == 0)
                                                <span class="px-2 py-1 text-xs font-semibold rounded-full bg-yellow-100 text-yellow-800">
                                                    Menunggu Persetujuan
                                                </span>
                                                <div class="mt-2 space-x-2">
                                                    <button onclick="approveNews({{ $item->id }})" class="px-3 py-1 text-xs font-semibold text-white bg-green-600 rounded hover:bg-green-700 focus:outline-none focus:ring-2 focus:ring-green-500 focus:ring-offset-2">
                                                        Setujui
                                                    </button>
                                                    <button onclick="rejectNews({{ $item->id }})" class="px-3 py-1 text-xs font-semibold text-white bg-red-600 rounded hover:bg-red-700 focus:outline-none focus:ring-2 focus:ring-red-500 focus:ring-offset-2">
                                                        Tolak
                                                    </button>
                                                </div>
                                            @elseif($item->status == 1)
                                                <span class="px-2 py-1 text-xs font-semibold rounded-full bg-green-100 text-green-800">
                                                    Disetujui
                                                </span>
                                            @elseif($item->status == 2)
                                                <span class="px-2 py-1 text-xs font-semibold rounded-full bg-red-100 text-red-800">
                                                    Ditolak
                                                </span>
                                            @else
                                                <span class="px-2 py-1 text-xs font-semibold rounded-full bg-gray-100 text-gray-800">
                                                    Status Tidak Diketahui
                                                </span>
                                            @endif
                                        </td>
                                        <td class="px-6 py-4 whitespace-nowrap text-sm font-medium border-b flex space-x-2">
                                            <a href="{{ route('admin.news.edit', $item->id) }}" class="text-blue-600 hover:text-blue-900">
                                                <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M11 5H6a2 2 0 00-2 2v11a2 2 0 002 2h11a2 2 0 002-2v-5m-1.414-9.414a2 2 0 112.828 2.828L11.828 15H9v-2.828l8.586-8.586z"></path>
                                                </svg>
                                            </a>
                                            <button onclick="deleteNews({{ $item->id }})" class="text-red-600 hover:text-red-900">
                                                <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 7l-.867 12.142A2 2 0 0116.138 21H7.862a2 2 0 01-1.995-1.858L5 7m5 4v6m4-6v6m1-10V4a1 1 0 00-1-1h-4a1 1 0 00-1 1v3M4 7h16"></path>
                                                </svg>
                                            </button>
                                        </td>
                                    </tr>
                                @endforeach
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

    <div id="imageModal" class="fixed inset-0 bg-black bg-opacity-50 hidden items-center justify-center z-50">
        <div class="bg-white p-4 rounded-lg max-w-2xl">
            <div class="flex justify-between items-center mb-4">
                <h3 class="text-lg font-semibold" id="modalTitle"></h3>
                <button onclick="closeImageModal()" class="text-gray-500 hover:text-gray-700">
                    <svg class="w-6 h-6" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M6 18L18 6M6 6l12 12"></path>
                    </svg>
                </button>
            </div>
            <img id="modalImage" src="" alt="" class="max-h-[70vh] w-auto mx-auto">
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

        function showLoading() {
            document.getElementById('loadingIndicator').classList.remove('hidden');
        }

        function hideLoading() {
            document.getElementById('loadingIndicator').classList.add('hidden');
        }

        document.addEventListener('DOMContentLoaded', function() {
            loadNewsData();
            
            const searchInput = document.getElementById('searchInput');
            searchInput.addEventListener('input', handleSearch);
        });

        function handleSearch() {
            const searchQuery = document.getElementById('searchInput').value.toLowerCase();
            const perPage = document.getElementById('entriesPerPage').value;
            
            filteredData = window.allNews.filter(news => 
                news.title.toLowerCase().includes(searchQuery)
            );

            totalPages = Math.ceil(filteredData.length / perPage);
            
            currentPage = 1;
            
            updateTableDisplay();
        }

        function loadNewsData() {
            showLoading();
            
            axios.get('/api/news', {
                headers: {
                    'Authorization': 'Bearer {{ session("api_token") }}'
                }
            })
            .then(function(response) {
                hideLoading();
                
                window.allNews = response.data.data || response.data;
                filteredData = window.allNews;
                updateTableDisplay();
            })
            .catch(function(error) {
                hideLoading();
                console.error('Error:', error);
                notyf.error('Gagal memuat data berita');
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

        function updateTable(news, startIndex) {
            const tbody = document.getElementById('newsTableBody');
            tbody.innerHTML = '';

            if (news.length === 0) {
                tbody.innerHTML = `
                    <tr>
                        <td colspan="7" class="px-6 py-4 text-center text-sm text-gray-500">
                            Tidak ada berita yang tersedia
                        </td>
                    </tr>
                `;
                return;
            }

            news.forEach((item, index) => {
                const statusBadge = item.status === 0 ? 
                    `<span class="px-2 py-1 text-xs font-semibold rounded-full bg-yellow-100 text-yellow-800">
                        Menunggu Persetujuan
                    </span>
                    <div class="mt-2 space-x-2">
                        <button onclick="approveNews(${item.id})" class="px-3 py-1 text-xs font-semibold text-white bg-green-600 rounded hover:bg-green-700 focus:outline-none focus:ring-2 focus:ring-green-500 focus:ring-offset-2">
                            Setujui
                        </button>
                        <button onclick="rejectNews(${item.id})" class="px-3 py-1 text-xs font-semibold text-white bg-red-600 rounded hover:bg-red-700 focus:outline-none focus:ring-2 focus:ring-red-500 focus:ring-offset-2">
                            Tolak
                        </button>
                    </div>` : 
                    item.status === 1 ? 
                    `<span class="px-2 py-1 text-xs font-semibold rounded-full bg-green-100 text-green-800">
                        Disetujui
                    </span>` :
                    item.status === 2 ?
                    `<span class="px-2 py-1 text-xs font-semibold rounded-full bg-red-100 text-red-800">
                        Ditolak
                    </span>` :
                    `<span class="px-2 py-1 text-xs font-semibold rounded-full bg-gray-100 text-gray-800">
                        Status Tidak Diketahui
                    </span>`;

                const row = `
                    <tr>
                        <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-900 border-b">${startIndex + index + 1}</td>
                        <td class="px-6 py-4 text-sm text-gray-900 border-b">${item.title}</td>
                        <td class="px-6 py-4 text-sm text-gray-900 border-b">${item.kategori?.name || '-'}</td>
                        <td class="px-6 py-4 text-sm text-gray-900 border-b">${item.creator?.name || '-'}</td>
                        <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-900 border-b">${new Date(item.created_at).toLocaleDateString('id-ID', {
                            day: 'numeric',
                            month: 'short',
                            year: 'numeric'
                        })}</td>
                        <td class="px-6 py-4 border-b">
                            ${statusBadge}
                        </td>
                        <td class="px-6 py-4 whitespace-nowrap text-sm font-medium border-b flex space-x-2">
                            <a href="/manage/news/${item.id}/edit" class="text-indigo-600 hover:text-indigo-900">
                                <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M11 5H6a2 2 0 00-2 2v11a2 2 0 002 2h11a2 2 0 002-2v-5m-1.414-9.414a2 2 0 112.828 2.828L11.828 15H9v-2.828l8.586-8.586z"></path>
                                </svg>
                            </a>
                            <button onclick="deleteNews(${item.id})" class="text-red-600 hover:text-red-900">
                                <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 7l-.867 12.142A2 2 0 0116.138 21H7.862a2 2 0 01-1.995-1.858L5 7m5 4v6m4-6v6m1-10V4a1 1 0 00-1-1h-4a1 1 0 00-1 1v3M4 7h16"></path>
                                </svg>
                            </button>
                        </td>
                    </tr>
                `;
                tbody.insertAdjacentHTML('beforeend', row);
            });
        }

        function previousPage() {
            if (currentPage > 1) {
                currentPage--;
                loadNewsData();
            }
        }

        function nextPage() {
            if (currentPage < totalPages) {
                currentPage++;
                loadNewsData();
            }
        }

        function deleteNews(id) {
            Swal.fire({
                title: 'Apakah Anda yakin?',
                text: "Berita yang dihapus tidak dapat dikembalikan!",
                icon: 'warning',
                showCancelButton: true,
                confirmButtonColor: '#3085d6',
                cancelButtonColor: '#d33',
                confirmButtonText: 'Ya, hapus!',
                cancelButtonText: 'Batal'
            }).then((result) => {
                if (result.isConfirmed) {
                    showLoading();
                    axios.delete(`/api/news/${id}`, {
                        headers: {
                            'X-CSRF-TOKEN': document.querySelector('meta[name="csrf-token"]').content,
                            'Authorization': 'Bearer ' + document.querySelector('meta[name="api-token"]').content
                        }
                    })
                    .then(response => {
                        if (response.data.success) {
                            notyf.success('Berita berhasil dihapus');
                            loadNewsData();
                        }
                    })
                    .catch(error => {
                        console.error('Error:', error);
                        notyf.error('Gagal menghapus berita');
                    })
                    .finally(() => {
                        hideLoading();
                    });
                }
            });
        }

        function showImage(imageSrc, title) {
            document.getElementById('modalImage').src = imageSrc;
            document.getElementById('modalTitle').textContent = title;
            document.getElementById('imageModal').classList.remove('hidden');
            document.getElementById('imageModal').classList.add('flex');
        }

        function closeImageModal() {
            document.getElementById('imageModal').classList.add('hidden');
            document.getElementById('imageModal').classList.remove('flex');
        }

        document.getElementById('imageModal').addEventListener('click', function(e) {
            if (e.target === this) {
                closeImageModal();
            }
        });

        @if(session('success'))
            notyf.success("{{ session('success') }}");
        @endif

        @if(session('error'))
            notyf.error("{{ session('error') }}");
        @endif

        function approveNews(id) {
            Swal.fire({
                title: 'Apakah Anda yakin?',
                text: "Berita akan disetujui dan ditampilkan di halaman publik",
                icon: 'warning',
                showCancelButton: true,
                confirmButtonColor: '#3085d6',
                cancelButtonColor: '#d33',
                confirmButtonText: 'Ya, setujui!',
                cancelButtonText: 'Batal'
            }).then((result) => {
                if (result.isConfirmed) {
                    showLoading();
                    axios.post(`/api/news/${id}/approve`, {}, {
                        headers: {
                            'X-CSRF-TOKEN': document.querySelector('meta[name="csrf-token"]').content,
                            'Authorization': 'Bearer ' + document.querySelector('meta[name="api-token"]').content
                        }
                    })
                    .then(response => {
                        if (response.data.success) {
                            Swal.fire(
                                'Berhasil!',
                                'Berita berhasil disetujui.',
                                'success'
                            ).then(() => {
                                window.location.reload();
                            });
                        } else {
                            Swal.fire(
                                'Gagal!',
                                'Gagal menyetujui berita.',
                                'error'
                            );
                        }
                    })
                    .catch(error => {
                        console.error('Error:', error);
                        Swal.fire(
                            'Error!',
                            'Terjadi kesalahan saat menyetujui berita.',
                            'error'
                        );
                    })
                    .finally(() => {
                        hideLoading();
                    });
                }
            });
        }

        function rejectNews(id) {
            Swal.fire({
                title: 'Apakah Anda yakin?',
                text: "Berita akan ditolak dan tidak akan ditampilkan di halaman publik",
                icon: 'warning',
                showCancelButton: true,
                confirmButtonColor: '#3085d6',
                cancelButtonColor: '#d33',
                confirmButtonText: 'Ya, tolak!',
                cancelButtonText: 'Batal'
            }).then((result) => {
                if (result.isConfirmed) {
                    showLoading();
                    axios.post(`/api/news/${id}/reject`, {}, {
                        headers: {
                            'X-CSRF-TOKEN': document.querySelector('meta[name="csrf-token"]').content,
                            'Authorization': 'Bearer ' + document.querySelector('meta[name="api-token"]').content
                        }
                    })
                    .then(response => {
                        if (response.data.success) {
                            Swal.fire(
                                'Berhasil!',
                                'Berita berhasil ditolak.',
                                'success'
                            ).then(() => {
                                window.location.reload();
                            });
                        } else {
                            Swal.fire(
                                'Gagal!',
                                'Gagal menolak berita.',
                                'error'
                            );
                        }
                    })
                    .catch(error => {
                        console.error('Error:', error);
                        Swal.fire(
                            'Error!',
                            'Terjadi kesalahan saat menolak berita.',
                            'error'
                        );
                    })
                    .finally(() => {
                        hideLoading();
                    });
                }
            });
        }
    </script>
    @endpush
</x-app-layout> 