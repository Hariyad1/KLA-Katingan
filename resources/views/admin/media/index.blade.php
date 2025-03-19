<x-app-layout>
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/notyf@3/notyf.min.css">
    <script src="https://cdn.jsdelivr.net/npm/notyf@3/notyf.min.js"></script>
    <!-- Tambahkan header -->
    <x-slot name="header">
        <div class="flex justify-between items-center">
            <h2 class="font-semibold text-xl text-gray-800 leading-tight">
                {{ __('Media Gambar') }}
            </h2>
            <a href="{{ route('admin.media.create') }}" class="bg-indigo-600 hover:bg-indigo-700 text-white font-bold py-2 px-4 rounded inline-flex items-center">
                <svg class="w-4 h-4 mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 6v6m0 0v6m0-6h6m-6 0H6"></path>
                </svg>
                <span>Upload Gambar</span>
            </a>
        </div>
    </x-slot>

    <!-- Tambahkan loading indicator di bagian atas konten -->
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
                            <select id="entriesPerPage" class="border rounded px-2 py-1 w-20" onchange="loadMediaData()">
                                <option value="10">10</option>
                                <option value="25">25</option>
                                <option value="50">50</option>
                                <option value="100">100</option>
                            </select>
                            <span>entries</span>
                        </div>
                        <div class="flex items-center">
                            <span class="mr-2">Search:</span>
                            <input type="text" id="searchInput" class="border rounded px-3 py-1" onkeyup="loadMediaData()" placeholder="Search...">
                        </div>
                    </div>

                    <!-- Alert Messages -->
                    <div id="alertSuccess" class="hidden mb-4 p-4 bg-green-100 border border-green-400 text-green-700 rounded">
                        <span id="alertSuccessMessage"></span>
                    </div>
                    <div id="alertError" class="hidden mb-4 p-4 bg-red-100 border border-red-400 text-red-700 rounded">
                        <span id="alertErrorMessage"></span>
                    </div>

                    <!-- Tabel Media -->
                    <div class="overflow-x-auto">
                        <table class="min-w-full divide-y divide-gray-200">
                            <thead class="bg-gray-50">
                                <tr>
                                    <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">No</th>
                                    <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Nama</th>
                                    <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">File</th>
                                    <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Path</th>
                                    <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Slideshow</th>
                                    <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Aksi</th>
                                </tr>
                            </thead>
                            <tbody id="mediaTableBody" class="bg-white divide-y divide-gray-200">
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

    <!-- Upload Modal -->
    <div x-data="{ open: false }" x-show="open" @open-upload-modal.window="open = true" class="fixed inset-0 z-50 overflow-y-auto" style="display: none;">
        <div class="flex items-center justify-center min-h-screen px-4">
            <div class="fixed inset-0 bg-gray-500 bg-opacity-75 transition-opacity"></div>

            <div class="relative bg-white rounded-lg max-w-lg w-full">
                <div class="p-6">
                    <h3 class="text-lg font-medium text-gray-900 mb-4">Upload Gambar</h3>
                    
                    <form id="uploadForm" class="space-y-4">
                        @csrf
                        <div>
                            <label class="block text-sm font-medium text-gray-700">Nama</label>
                            <input type="text" name="name" class="mt-1 block w-full rounded-md border-gray-300 shadow-sm focus:border-indigo-500 focus:ring-indigo-500" required>
                        </div>

                        <div>
                            <label class="block text-sm font-medium text-gray-700">File Gambar</label>
                            <input type="file" name="file" accept="image/*" class="mt-1 block w-full" required>
                            <p class="mt-1 text-sm text-gray-500">Format yang didukung: JPG, JPEG, PNG, GIF</p>
                        </div>

                        <div>
                            <label class="flex items-center">
                                <input type="checkbox" name="slide_show" value="1" class="rounded border-gray-300 text-indigo-600 shadow-sm focus:border-indigo-500 focus:ring-indigo-500">
                                <span class="ml-2 text-sm text-gray-600">Tampilkan di slideshow</span>
                            </label>
                            <p class="mt-1 text-xs text-gray-500">Jika dicentang, gambar akan ditampilkan di slideshow</p>
                        </div>

                        <div class="mt-5 flex justify-end space-x-3">
                            <button type="button" @click="open = false" class="px-4 py-2 text-sm font-medium text-gray-700 bg-white border border-gray-300 rounded-md hover:bg-gray-50">
                                Batal
                            </button>
                            <button type="submit" class="px-4 py-2 text-sm font-medium text-white bg-indigo-600 border border-transparent rounded-md hover:bg-indigo-700">
                                Upload
                            </button>
                        </div>
                    </form>
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

        // Fungsi untuk menampilkan/menyembunyikan loading
        function showLoading() {
            document.getElementById('loadingIndicator').classList.remove('hidden');
        }

        function hideLoading() {
            document.getElementById('loadingIndicator').classList.add('hidden');
        }

        function deleteMedia(id) {
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
                    axios.delete(`/api/media/${id}`, {
                        headers: {
                            'X-CSRF-TOKEN': document.querySelector('meta[name="csrf-token"]').content,
                            'Authorization': 'Bearer ' + document.querySelector('meta[name="api-token"]').content
                        }
                    })
                    .then(response => {
                        if (response.data.success) {
                            notyf.success('Media berhasil dihapus');
                            loadMediaData(); // Reload data setelah hapus
                        }
                    })
                    .catch(() => {
                        notyf.error('Gagal menghapus media');
                    })
                    .finally(() => {
                        hideLoading();
                    });
                }
            });
        }

        // Tampilkan notifikasi jika ada session flash
        @if(session('success'))
            notyf.success("{{ session('success') }}");
        @endif

        @if(session('error'))
            notyf.error("{{ session('error') }}");
        @endif

        // Update form upload dengan loading indicator
        document.getElementById('uploadForm')?.addEventListener('submit', function(e) {
            e.preventDefault();
            showLoading();
            
            const formData = new FormData(this);
            
            axios.post('/api/media', formData, {
                headers: {
                    'Content-Type': 'multipart/form-data',
                    'X-CSRF-TOKEN': document.querySelector('meta[name="csrf-token"]').content,
                    'Authorization': 'Bearer ' + document.querySelector('meta[name="api-token"]').content
                }
            })
            .then(response => {
                if (response.data.success) {
                    notyf.success('Media berhasil diupload');
                    this.reset();
                    loadMediaData();
                }
            })
            .catch(error => {
                console.error('Error:', error);
                notyf.error('Gagal mengupload media');
            })
            .finally(() => {
                hideLoading();
            });
        });

        let currentPage = 1;
        let totalPages = 1;
        let filteredData = [];

        function loadMediaData() {
            showLoading();
            const perPage = document.getElementById('entriesPerPage').value;
            const searchQuery = document.getElementById('searchInput').value.toLowerCase();
            
            axios.get('/api/media', {
                headers: {
                    'Authorization': 'Bearer ' + document.querySelector('meta[name="api-token"]').content
                }
            })
            .then(response => {
                const mediaItems = Array.isArray(response.data) ? response.data : response.data.data;

                // Filter hanya file gambar dan berdasarkan pencarian
                filteredData = mediaItems.filter(item => {
                    const extension = item.file.split('.').pop().toLowerCase();
                    const isImage = ['jpg', 'jpeg', 'png', 'gif'].includes(extension);
                    const matchesSearch = item.name.toLowerCase().includes(searchQuery);
                    return isImage && matchesSearch;
                });

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
                notyf.error('Gagal memuat data media');
            })
            .finally(() => {
                hideLoading();
            });
        }

        function updateTable(mediaItems, startIndex) {
            const tbody = document.getElementById('mediaTableBody');
            tbody.innerHTML = '';

            if (mediaItems.length === 0) {
                tbody.innerHTML = `
                    <tr>
                        <td colspan="6" class="px-6 py-4 text-center text-sm text-gray-500">
                            Tidak ada media yang tersedia
                        </td>
                    </tr>
                `;
                return;
            }

            mediaItems.forEach((item, index) => {
                const extension = item.file.split('.').pop().toLowerCase();
                const type = getFileType(extension);
                
                const row = `
                    <tr data-media-id="${item.id}" data-media-name="${item.name}">
                        <td class="px-6 py-4 whitespace-nowrap text-sm">${index + 1}</td>
                        <td class="px-6 py-4 whitespace-nowrap text-sm">${item.name}</td>
                        <td class="px-6 py-4 whitespace-nowrap text-sm">${type}</td>
                        <td class="px-6 py-4 whitespace-nowrap text-sm">${item.file}</td>
                        <td class="px-6 py-4 whitespace-nowrap text-sm">
                            <span class="px-2 inline-flex text-xs leading-5 font-semibold rounded-full ${item.slide_show ? 'bg-green-100 text-green-800' : 'bg-gray-100 text-gray-800'}">
                                ${item.slide_show ? 'Yes' : 'No'}
                            </span>
                        </td>
                        <td class="px-6 py-4 whitespace-nowrap text-sm font-medium flex space-x-3">
                            <a href="/manage/media/${item.id}/edit" class="text-blue-600 hover:text-blue-900">
                                <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M11 5H6a2 2 0 00-2 2v11a2 2 0 002 2h11a2 2 0 002-2v-5m-1.414-9.414a2 2 0 112.828 2.828L11.828 15H9v-2.828l8.586-8.586z"/>
                                </svg>
                            </a>
                            <button onclick="deleteMedia(${item.id})" class="text-red-600 hover:text-red-900">
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

        function getFileType(extension) {
            // Sederhanakan fungsi getFileType untuk hanya menangani gambar
            const types = {
                'jpg': 'Image/JPG',
                'jpeg': 'Image/JPEG',
                'png': 'Image/PNG',
                'gif': 'Image/GIF'
            };
            return types[extension] || 'Unknown';
        }

        function previousPage() {
            if (currentPage > 1) {
                currentPage--;
                loadMediaData();
            }
        }

        function nextPage() {
            if (currentPage < totalPages) {
                currentPage++;
                loadMediaData();
            }
        }

        // Load data saat halaman dimuat
        document.addEventListener('DOMContentLoaded', loadMediaData);
    </script>
    @endpush
</x-app-layout> 