<x-app-layout>
    <x-slot name="header">
        <div class="flex justify-between items-center">
            <h2 class="font-semibold text-xl text-gray-800 leading-tight">
                {{ __('Data Dukung Saya') }}
            </h2>
            <a href="{{ route('user.data-dukung.create') }}" class="inline-flex items-center px-4 py-2 bg-indigo-600 border border-transparent rounded-md font-semibold text-xs text-white uppercase tracking-widest hover:bg-indigo-700 focus:bg-indigo-700 active:bg-indigo-900 focus:outline-none focus:ring-2 focus:ring-indigo-500 focus:ring-offset-2 transition ease-in-out duration-150">
                Tambah Data Dukung
            </a>
        </div>
    </x-slot>

    <div class="py-12">
        <div class="max-w-7xl mx-auto sm:px-6 lg:px-8">
            <div class="bg-white overflow-hidden shadow-sm sm:rounded-lg">
                <div class="p-6 text-gray-900">
                    <!-- Search dan Filter -->
                    <div class="mb-4 flex justify-between items-center">
                        <div class="flex items-center gap-2">
                            <span>Show</span>
                            <select id="perPage" class="rounded-md border-gray-300 shadow-sm focus:border-indigo-500 focus:ring-indigo-500">
                                <option value="10">10</option>
                                <option value="25">25</option>
                                <option value="50">50</option>
                                <option value="100">100</option>
                            </select>
                            <span>entries</span>
                        </div>
                        <div class="flex items-center gap-2">
                            <span>Search:</span>
                            <div class="relative">
                                <input type="text" 
                                    id="searchInput" 
                                    placeholder="Cari..." 
                                    class="rounded-md border-gray-300 shadow-sm focus:border-indigo-500 focus:ring-indigo-500 pr-8">
                                <div x-show="isLoading" class="absolute right-2 top-1/2 transform -translate-y-1/2">
                                    <div class="animate-spin rounded-full h-4 w-4 border-b-2 border-indigo-600"></div>
                                </div>
                            </div>
                        </div>
                    </div>
                    
                    <div class="overflow-x-auto relative" x-data="dataDukungList()">
                        <!-- Loading Spinner -->
                        <div x-show="isLoading" class="absolute inset-0 bg-white bg-opacity-80 z-10 flex items-center justify-center">
                            <div class="animate-spin rounded-full h-8 w-8 border-b-2 border-indigo-500"></div>
                        </div>
                        
                        <table class="min-w-full divide-y divide-gray-200">
                            <thead class="bg-gray-50">
                                <tr>
                                    <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider w-16">No</th>
                                    <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider w-1/6">OPD</th>
                                    <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider w-1/6">Klaster</th>
                                    <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider w-1/4">Indikator</th>
                                    <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">File</th>
                                    <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider w-24">Aksi</th>
                                </tr>
                            </thead>
                            <tbody class="bg-white divide-y divide-gray-200" id="dataDukungTable">
                                <template x-if="!isLoading && items.length === 0">
                                    <tr>
                                        <td colspan="6" class="px-6 py-4 text-center text-gray-500">
                                            Tidak ada data dukung yang tersedia
                                        </td>
                                    </tr>
                                </template>
                                <template x-for="(item, index) in items" :key="item.id">
                                    <tr>
                                        <td class="px-6 py-4 w-16" x-text="startNumber + index"></td>
                                        <td class="px-6 py-4 w-1/6 truncate" x-text="item.opd?.name"></td>
                                        <td class="px-6 py-4 w-1/6 truncate" x-text="item.indikator?.klaster?.name"></td>
                                        <td class="px-6 py-4 w-1/4 break-words" x-text="item.indikator?.name"></td>
                                        <td class="px-6 py-4">
                                            <template x-for="file in item.files" :key="file.id">
                                                <div class="mb-2 p-2 border rounded-lg bg-gray-50 hover:bg-gray-100 transition-colors">
                                                    <div class="flex items-start gap-2">
                                                        <div class="flex-shrink-0">
                                                            <i :class="{
                                                                'fas fa-file-pdf text-red-500 text-base': file.original_name.toLowerCase().endsWith('.pdf'),
                                                                'fas fa-file-word text-blue-500 text-base': file.original_name.toLowerCase().endsWith('.doc') || file.original_name.toLowerCase().endsWith('.docx'),
                                                                'fas fa-file-excel text-green-500 text-base': file.original_name.toLowerCase().endsWith('.xls') || file.original_name.toLowerCase().endsWith('.xlsx'),
                                                                'fas fa-file-image text-purple-500 text-base': file.original_name.toLowerCase().endsWith('.jpg') || file.original_name.toLowerCase().endsWith('.jpeg') || file.original_name.toLowerCase().endsWith('.png'),
                                                                'fas fa-file text-gray-500 text-base': !file.original_name.toLowerCase().match(/\.(pdf|doc|docx|xls|xlsx|jpg|jpeg|png)$/)
                                                            }"></i>
                                                        </div>
                                                        <div class="flex-1 min-w-0">
                                                            <a :href="'/storage/' + file.file.replace('public/', '')" 
                                                               :download="file.original_name" 
                                                               class="text-indigo-600 hover:text-indigo-900 text-sm block truncate"
                                                               :title="file.original_name"
                                                               x-text="file.original_name.length > 25 ? file.original_name.substring(0, 22) + '...' : file.original_name">
                                                            </a>
                                                            <span class="text-xs text-gray-500 block mt-0.5" x-text="formatFileSize(file.size || 0)"></span>
                                                        </div>
                                                        <div class="flex-shrink-0">
                                                            <button type="button" 
                                                                    @click="confirmDeleteFile(file.id)"
                                                                    class="text-red-600 hover:text-red-800 p-1">
                                                                <i class="fas fa-trash text-sm"></i>
                                                            </button>
                                                        </div>
                                                    </div>
                                                </div>
                                            </template>
                                        </td>
                                        <td class="px-6 py-4 text-sm font-medium w-24">
                                            <div class="flex items-center space-x-2">
                                                <a :href="`/user/data-dukung/${item.id}/edit`" 
                                                   class="text-indigo-600 hover:text-indigo-900 inline-flex items-center" 
                                                   title="Edit">
                                                    <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M11 5H6a2 2 0 00-2 2v11a2 2 0 002 2h11a2 2 0 002-2v-5m-1.414-9.414a2 2 0 112.828 2.828L11.828 15H9v-2.828l8.586-8.586z"/>
                                                    </svg>
                                                </a>
                                                <button type="button" 
                                                        @click="confirmDelete(item.id)"
                                                        class="text-red-600 hover:text-red-900 inline-flex items-center" 
                                                        title="Hapus">
                                                    <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 7l-.867 12.142A2 2 0 0116.138 21H7.862a2 2 0 01-1.995-1.858L5 7m5 4v6m4-6v6m1-10V4a1 1 0 00-1-1h-4a1 1 0 00-1 1v3M4 7h16"/>
                                                    </svg>
                                                </button>
                                            </div>
                                        </td>
                                    </tr>
                                </template>
                            </tbody>
                        </table>

                        <!-- Pagination -->
                        <div class="mt-4 flex items-center justify-between">
                            <div class="text-sm text-gray-700">
                                Showing 
                                <span x-text="totalItems === 0 ? '0' : startNumber"></span> 
                                to 
                                <span x-text="totalItems === 0 ? '0' : endNumber"></span> 
                                of 
                                <span x-text="totalItems"></span> 
                                entries
                            </div>
                            <div class="flex items-center gap-4">
                                <button @click="previousPage" 
                                        :disabled="currentPage === 1 || totalItems === 0"
                                        class="px-3 py-1" 
                                        :class="currentPage === 1 || totalItems === 0 ? 'text-gray-400 cursor-not-allowed' : 'text-indigo-600 hover:text-indigo-800'">
                                    Prev
                                </button>
                                <span>Page <span x-text="totalItems === 0 ? '0' : currentPage"></span> of <span x-text="totalItems === 0 ? '0' : lastPage"></span></span>
                                <button @click="nextPage" 
                                        :disabled="currentPage === lastPage || totalItems === 0"
                                        class="px-3 py-1" 
                                        :class="currentPage === lastPage || totalItems === 0 ? 'text-gray-400 cursor-not-allowed' : 'text-indigo-600 hover:text-indigo-800'">
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
    <script>
        function dataDukungList() {
            return {
                items: [],
                currentPage: 1,
                lastPage: 1,
                totalItems: 0,
                startNumber: 0,
                endNumber: 0,
                searchQuery: '',
                perPage: 10,
                isLoading: true,
                cachedData: null,

                formatFileSize(bytes) {
                    if (!bytes || bytes === 0) return '0 Bytes';
                    const k = 1024;
                    const sizes = ['Bytes', 'KB', 'MB', 'GB'];
                    const i = Math.floor(Math.log(bytes) / Math.log(k));
                    return parseFloat((bytes / Math.pow(k, i)).toFixed(2)) + ' ' + sizes[i];
                },

                async init() {
                    const urlParams = new URLSearchParams(window.location.search);
                    if (urlParams.has('per_page')) {
                        this.perPage = parseInt(urlParams.get('per_page'));
                        document.getElementById('perPage').value = this.perPage;
                    } else {
                        this.perPage = parseInt(document.getElementById('perPage').value);
                    }
                    
                    if (urlParams.has('search')) {
                        this.searchQuery = urlParams.get('search');
                        document.getElementById('searchInput').value = this.searchQuery;
                    }
                    
                    await this.fetchAllData();
                    this.setupListeners();
                },

                setupListeners() {
                    const searchInput = document.getElementById('searchInput');
                    const perPageSelect = document.getElementById('perPage');
                    
                    let searchTimeout;
                    searchInput.addEventListener('input', (e) => {
                        clearTimeout(searchTimeout);
                        searchTimeout = setTimeout(() => {
                            this.searchQuery = e.target.value;
                            this.currentPage = 1;
                            this.filterData();
                            this.updateUrl();
                        }, 300);
                    });
                    
                    searchInput.addEventListener('keypress', (e) => {
                        if (e.key === 'Enter') {
                            e.preventDefault();
                            clearTimeout(searchTimeout);
                            this.searchQuery = e.target.value;
                            this.currentPage = 1;
                            this.filterData();
                            this.updateUrl();
                        }
                    });
                    
                    perPageSelect.addEventListener('change', (e) => {
                        this.perPage = parseInt(e.target.value);
                        this.currentPage = 1;
                        this.filterData();
                        this.updateUrl();
                    });
                },
                
                updateUrl() {
                    const url = new URL(window.location);
                    
                    if (this.searchQuery && this.searchQuery.trim() !== '') {
                        url.searchParams.set('search', this.searchQuery.trim());
                    } else {
                        url.searchParams.delete('search');
                    }
                    
                    if (this.perPage !== 10) {
                        url.searchParams.set('per_page', this.perPage);
                    } else {
                        url.searchParams.delete('per_page');
                    }
                    
                    if (this.currentPage > 1) {
                        url.searchParams.set('page', this.currentPage);
                    } else {
                        url.searchParams.delete('page');
                    }
                    
                    window.history.pushState({}, '', url);
                },
                
                filterData() {
                    if (!this.cachedData) {
                        this.fetchData();
                        return;
                    }
                    
                    this.isLoading = true;
                    
                    try {
                        if (this.searchQuery.trim() === '') {
                            const startIndex = (this.currentPage - 1) * this.perPage;
                            const endIndex = startIndex + this.perPage;
                            
                            this.items = this.cachedData.slice(startIndex, endIndex);
                            this.totalItems = this.cachedData.length;
                            this.lastPage = Math.ceil(this.totalItems / this.perPage);
                            
                            this.updatePaginationInfo();
                        } else {
                            const searchQuery = this.searchQuery.toLowerCase().trim();
                            const filteredData = this.cachedData.filter(item => {
                                if (item.opd && item.opd.name && item.opd.name.toLowerCase().includes(searchQuery)) {
                                    return true;
                                }
                                
                                if (item.indikator && item.indikator.name && item.indikator.name.toLowerCase().includes(searchQuery)) {
                                    return true;
                                }
                                
                                if (item.indikator && item.indikator.klaster && 
                                    item.indikator.klaster.name && 
                                    item.indikator.klaster.name.toLowerCase().includes(searchQuery)) {
                                    return true;
                                }
                                
                                if (item.files && item.files.length > 0) {
                                    return item.files.some(file => 
                                        file.original_name && file.original_name.toLowerCase().includes(searchQuery)
                                    );
                                }
                                
                                if (item.description && item.description.toLowerCase().includes(searchQuery)) {
                                    return true;
                                }
                                
                                return false;
                            });
                            
                            const startIndex = (this.currentPage - 1) * this.perPage;
                            const endIndex = startIndex + this.perPage;
                            
                            this.items = filteredData.slice(startIndex, endIndex);
                            this.totalItems = filteredData.length;
                            this.lastPage = Math.ceil(this.totalItems / this.perPage) || 1;
                            
                            if (this.currentPage > this.lastPage) {
                                this.currentPage = 1;
                                this.items = filteredData.slice(0, this.perPage);
                            }
                            
                            this.updatePaginationInfo();
                        }
                        
                        console.log(`Ditemukan ${this.totalItems} data dari ${this.cachedData.length} total data`);
                    } catch (error) {
                        console.error('Error filtering data:', error);
                    } finally {
                        this.isLoading = false;
                    }
                },
                
                updatePaginationInfo() {
                    this.startNumber = this.items.length > 0 ? 
                        (this.currentPage - 1) * this.perPage + 1 : 0;
                    this.endNumber = Math.min(this.startNumber + this.items.length - 1, this.totalItems);
                },

                async fetchAllData() {
                    this.isLoading = true;
                    try {
                        const csrfToken = document.querySelector('meta[name="csrf-token"]')?.getAttribute('content');
                        
                        const response = await axios.get(`/user/data-dukung/list?per_page=1000`, {
                            headers: {
                                'Accept': 'application/json',
                                'X-CSRF-TOKEN': csrfToken,
                                'Cache-Control': 'no-cache'
                            }
                        });

                        this.cachedData = response.data.data?.data || [];
                        console.log(`Cached ${this.cachedData.length} data for searching`);
                        
                        this.filterData();
                    } catch (error) {
                        console.error('Error fetching all data:', error);
                        this.fetchData();
                    }
                },

                async fetchData() {
                    this.isLoading = true;
                    try {
                        const csrfToken = document.querySelector('meta[name="csrf-token"]')?.getAttribute('content');
                        
                        let apiUrl = `/user/data-dukung/list?page=${this.currentPage}&per_page=${this.perPage}`;
                        
                        if (this.searchQuery && this.searchQuery.trim() !== '') {
                            apiUrl += `&search=${encodeURIComponent(this.searchQuery.trim())}`;
                        }
                        
                        apiUrl += `&_=${new Date().getTime()}`;
                        
                        const response = await axios.get(apiUrl, {
                            headers: {
                                'Accept': 'application/json',
                                'X-CSRF-TOKEN': csrfToken,
                                'Cache-Control': 'no-cache'
                            }
                        });

                        const result = response.data;
                        
                        if (result.data) {
                            this.items = result.data.data || [];
                            this.currentPage = result.data.current_page || 1;
                            this.lastPage = result.data.last_page || 1;
                            this.totalItems = result.data.total || 0;
                            this.updatePaginationInfo();
                        } else {
                            throw new Error('Format data tidak sesuai');
                        }
                    } catch (error) {
                        console.error('Error fetching data:', error);
                        Swal.fire({
                            icon: 'error',
                            title: 'Error!',
                            text: 'Gagal mengambil data: ' + (error.response?.data?.message || error.message),
                            confirmButtonColor: '#3085d6'
                        });
                        this.items = [];
                        this.totalItems = 0;
                    } finally {
                        this.isLoading = false;
                    }
                },

                async nextPage() {
                    if (this.currentPage < this.lastPage) {
                        this.currentPage++;
                        this.filterData();
                        this.updateUrl();
                    }
                },

                async previousPage() {
                    if (this.currentPage > 1) {
                        this.currentPage--;
                        this.filterData();
                        this.updateUrl();
                    }
                },

                confirmDelete(id) {
                    Swal.fire({
                        title: 'Apakah Anda yakin?',
                        text: "Data dukung dan semua file terkait akan dihapus permanen!",
                        icon: 'warning',
                        showCancelButton: true,
                        confirmButtonColor: '#3085d6',
                        cancelButtonColor: '#d33',
                        confirmButtonText: 'Ya, hapus!',
                        cancelButtonText: 'Batal'
                    }).then(async (result) => {
                        if (result.isConfirmed) {
                            try {
                                const csrfToken = document.querySelector('meta[name="csrf-token"]')?.getAttribute('content');
                                
                                await axios.post(`/user/data-dukung/${id}`, {
                                    _method: 'DELETE',
                                    _token: csrfToken
                                }, {
                                    headers: {
                                        'Accept': 'application/json',
                                        'X-CSRF-TOKEN': csrfToken
                                    }
                                });

                                await this.fetchData();

                                Swal.fire({
                                    icon: 'success',
                                    title: 'Berhasil!',
                                    text: 'Data dukung berhasil dihapus',
                                    timer: 1500
                                });
                            } catch (error) {
                                console.error('Error:', error);
                                Swal.fire({
                                    icon: 'error',
                                    title: 'Oops...',
                                    text: error.response?.data?.message || error.message || 'Terjadi kesalahan saat menghapus data!'
                                });
                            }
                        }
                    });
                },

                confirmDeleteFile(id) {
                    Swal.fire({
                        title: 'Apakah Anda yakin?',
                        text: "File ini akan dihapus permanen!",
                        icon: 'warning',
                        showCancelButton: true,
                        confirmButtonColor: '#3085d6',
                        cancelButtonColor: '#d33',
                        confirmButtonText: 'Ya, hapus!',
                        cancelButtonText: 'Batal'
                    }).then(async (result) => {
                        if (result.isConfirmed) {
                            try {
                                const csrfToken = document.querySelector('meta[name="csrf-token"]')?.getAttribute('content');
                                
                                await axios.post(`/user/data-dukung/file/${id}`, {
                                    _method: 'DELETE',
                                    _token: csrfToken
                                }, {
                                    headers: {
                                        'Accept': 'application/json',
                                        'X-CSRF-TOKEN': csrfToken
                                    }
                                });

                                await this.fetchData();

                                Swal.fire({
                                    icon: 'success',
                                    title: 'Berhasil!',
                                    text: 'File berhasil dihapus',
                                    timer: 1500
                                });
                            } catch (error) {
                                console.error('Error:', error);
                                Swal.fire({
                                    icon: 'error',
                                    title: 'Oops...',
                                    text: error.response?.data?.message || error.message || 'Terjadi kesalahan saat menghapus file!'
                                });
                            }
                        }
                    });
                }
            }
        }
    </script>
    @endpush

    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/sweetalert2@11/dist/sweetalert2.min.css">
    <script src="https://cdn.jsdelivr.net/npm/sweetalert2@11"></script>
    <script defer src="https://unpkg.com/alpinejs@3.x.x/dist/cdn.min.js"></script>
</x-app-layout> 