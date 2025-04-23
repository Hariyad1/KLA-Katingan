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
                            <input type="text" 
                                id="searchInput" 
                                placeholder="Cari..." 
                                class="rounded-md border-gray-300 shadow-sm focus:border-indigo-500 focus:ring-indigo-500">
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
                                    <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">No</th>
                                    <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">OPD</th>
                                    <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Klaster</th>
                                    <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Indikator</th>
                                    <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">File</th>
                                    <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Aksi</th>
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
                                        <td class="px-6 py-4 whitespace-nowrap" x-text="startNumber + index"></td>
                                        <td class="px-6 py-4 whitespace-nowrap" x-text="item.opd?.name"></td>
                                        <td class="px-6 py-4 whitespace-nowrap" x-text="item.indikator?.klaster?.name"></td>
                                        <td class="px-6 py-4 whitespace-nowrap" x-text="item.indikator?.name"></td>
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
                                                        <div class="flex-1">
                                                            <a :href="'/storage/' + file.file.replace('public/', '')" 
                                                               :download="file.original_name" 
                                                               class="text-indigo-600 hover:text-indigo-900 text-sm block"
                                                               x-text="file.original_name">
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
                                        <td class="px-6 py-4 whitespace-nowrap text-sm font-medium">
                                            <a :href="`/user/data-dukung/${item.id}/edit`" 
                                               class="text-indigo-600 hover:text-indigo-900 mr-3 inline-flex items-center" 
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
                searchTimeout: null,
                isLoading: true,

                formatFileSize(bytes) {
                    if (!bytes || bytes === 0) return '0 Bytes';
                    const k = 1024;
                    const sizes = ['Bytes', 'KB', 'MB', 'GB'];
                    const i = Math.floor(Math.log(bytes) / Math.log(k));
                    return parseFloat((bytes / Math.pow(k, i)).toFixed(2)) + ' ' + sizes[i];
                },

                async init() {
                    this.perPage = document.getElementById('perPage').value;
                    await this.fetchData();
                    this.setupListeners();
                },

                setupListeners() {
                    const searchInput = document.getElementById('searchInput');
                    const perPageSelect = document.getElementById('perPage');

                    searchInput.addEventListener('input', (e) => {
                        clearTimeout(this.searchTimeout);
                        this.searchTimeout = setTimeout(() => {
                            this.searchQuery = e.target.value;
                            this.currentPage = 1;
                            this.fetchData();
                        }, 300);
                    });

                    perPageSelect.addEventListener('change', (e) => {
                        this.perPage = e.target.value;
                        this.currentPage = 1;
                        this.fetchData();
                    });
                },

                async fetchData() {
                    this.isLoading = true;
                    try {
                        const csrfToken = document.querySelector('meta[name="csrf-token"]')?.getAttribute('content');
                        
                        const response = await fetch(`/user/data-dukung/list?page=${this.currentPage}&per_page=${this.perPage}&search=${this.searchQuery}`, {
                            method: 'GET',
                            headers: {
                                'Accept': 'application/json',
                                'Content-Type': 'application/json',
                                'X-CSRF-TOKEN': csrfToken
                            },
                            credentials: 'same-origin'
                        });

                        if (!response.ok) {
                            throw new Error('Gagal mengambil data');
                        }

                        const result = await response.json();
                        
                        if (result.data) {
                            this.items = result.data.data || [];
                            this.currentPage = result.data.current_page || 1;
                            this.lastPage = result.data.last_page || 1;
                            this.totalItems = result.data.total || 0;
                            this.startNumber = this.items.length > 0 ? 
                                (this.currentPage - 1) * this.perPage + 1 : 0;
                            this.endNumber = Math.min(this.startNumber + this.items.length - 1, this.totalItems);
                        } else {
                            throw new Error('Format data tidak sesuai');
                        }
                    } catch (error) {
                        console.error('Error fetching data:', error);
                        Swal.fire({
                            icon: 'error',
                            title: 'Error!',
                            text: 'Gagal mengambil data: ' + error.message,
                            confirmButtonColor: '#3085d6'
                        });
                    } finally {
                        this.isLoading = false;
                    }
                },

                async nextPage() {
                    if (this.currentPage < this.lastPage) {
                        this.currentPage++;
                        await this.fetchData();
                    }
                },

                async previousPage() {
                    if (this.currentPage > 1) {
                        this.currentPage--;
                        await this.fetchData();
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
                                
                                const formData = new FormData();
                                formData.append('_method', 'DELETE');
                                formData.append('_token', csrfToken);

                                const response = await fetch(`/user/data-dukung/${id}`, {
                                    method: 'POST',
                                    headers: {
                                        'Accept': 'application/json',
                                        'X-CSRF-TOKEN': csrfToken
                                    },
                                    body: formData,
                                    credentials: 'include'
                                });

                                if (!response.ok) {
                                    const errorData = await response.json();
                                    throw new Error(errorData.message || 'Gagal menghapus data');
                                }

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
                                    text: error.message || 'Terjadi kesalahan saat menghapus data!'
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
                                
                                const formData = new FormData();
                                formData.append('_method', 'DELETE');
                                formData.append('_token', csrfToken);

                                const response = await fetch(`/user/data-dukung/file/${id}`, {
                                    method: 'POST',
                                    headers: {
                                        'Accept': 'application/json',
                                        'X-CSRF-TOKEN': csrfToken
                                    },
                                    body: formData,
                                    credentials: 'include'
                                });

                                if (!response.ok) {
                                    const errorData = await response.json();
                                    throw new Error(errorData.message || 'Gagal menghapus file');
                                }

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
                                    text: error.message || 'Terjadi kesalahan saat menghapus file!'
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