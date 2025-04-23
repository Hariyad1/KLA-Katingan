@if(auth()->check() && auth()->user()->status == 1)
<x-app-layout>
    <x-slot name="header">
        <div class="flex justify-between items-center">
            <h2 class="font-semibold text-xl text-gray-800 leading-tight">
                {{ __('Manajemen Data Dukung') }}
            </h2>
            <a href="{{ route('admin.data-dukung.create') }}" class="inline-flex items-center px-4 py-2 bg-indigo-600 border border-transparent rounded-md font-semibold text-xs text-white uppercase tracking-widest hover:bg-indigo-700 focus:bg-indigo-700 active:bg-indigo-900 focus:outline-none focus:ring-2 focus:ring-indigo-500 focus:ring-offset-2 transition ease-in-out duration-150">
                Tambah Data Dukung
            </a>
        </div>
    </x-slot>

    <div class="py-12" x-data="dataDukung()">
        <div class="max-w-7xl mx-auto sm:px-6 lg:px-8">
            <div class="bg-white overflow-hidden shadow-sm sm:rounded-lg">
                <div class="p-6 text-gray-900">
                    <div class="flex flex-col md:flex-row justify-between items-center mb-4 space-y-4 md:space-y-0">
                        <div class="flex items-center space-x-3">
                            <span class="text-gray-500 text-sm">Show</span>
                            <select x-model="perPage" 
                                    @change="fetchData()"
                                    class="rounded-md border-gray-300 shadow-sm focus:border-indigo-300 focus:ring focus:ring-indigo-200 focus:ring-opacity-50">
                                <option value="10">10</option>
                                <option value="25">25</option>
                                <option value="50">50</option>
                                <option value="100">100</option>
                            </select>
                            <span class="text-gray-500 text-sm">entries</span>
                        </div>
                        
                        <div class="flex items-center space-x-4">
                            <span class="text-gray-500 text-sm">Search:</span>
                            <input type="text" 
                                   x-model="searchTerm" 
                                   @input.debounce.300ms="fetchData()"
                                   placeholder="Cari..."
                                   class="rounded-md border-gray-300 shadow-sm focus:border-indigo-300 focus:ring focus:ring-indigo-200 focus:ring-opacity-50">
                        </div>
                    </div>

                    <div class="overflow-x-auto relative">
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
                            <tbody class="bg-white divide-y divide-gray-200">
                                <template x-for="(item, index) in items" :key="item.id">
                                    <tr>
                                        <td class="px-6 py-4 whitespace-nowrap" x-text="(currentPage - 1) * perPage + index + 1"></td>
                                        <td class="px-6 py-4 whitespace-nowrap" x-text="item.opd.name"></td>
                                        <td class="px-6 py-4 whitespace-nowrap" x-text="item.indikator.klaster.name"></td>
                                        <td class="px-6 py-4 whitespace-nowrap" x-text="item.indikator.name"></td>
                                        <td class="px-6 py-4">
                                            <template x-for="file in item.files" :key="file.id">
                                                <div class="mb-2 flex items-center justify-between p-3 bg-gray-50 rounded-lg hover:bg-gray-100 transition-colors duration-150">
                                                    <div class="flex items-center gap-3">
                                                        <i :class="{
                                                                'fas fa-file-pdf text-red-500 text-base': file.original_name.toLowerCase().endsWith('.pdf'),
                                                                'fas fa-file-word text-blue-500 text-base': file.original_name.toLowerCase().endsWith('.doc') || file.original_name.toLowerCase().endsWith('.docx'),
                                                                'fas fa-file-excel text-green-500 text-base': file.original_name.toLowerCase().endsWith('.xls') || file.original_name.toLowerCase().endsWith('.xlsx'),
                                                                'fas fa-file-image text-purple-500 text-base': file.original_name.toLowerCase().endsWith('.jpg') || file.original_name.toLowerCase().endsWith('.jpeg') || file.original_name.toLowerCase().endsWith('.png'),
                                                                'fas fa-file text-gray-500 text-base': !file.original_name.toLowerCase().match(/\.(pdf|doc|docx|xls|xlsx|jpg|jpeg|png)$/)
                                                        }" class="text-lg"></i>
                                                        <div class="flex flex-col">
                                                            <span class="text-sm font-medium text-gray-700" x-text="file.original_name"></span>
                                                            <span class="text-xs text-gray-500" x-text="file.size ? formatFileSize(file.size) : 'Ukuran tidak tersedia'"></span>
                                                        </div>
                                                    </div>
                                                    <div class="flex items-center gap-2">
                                                        <a :href="'/storage/' + file.file" download title="Unduh" class="text-indigo-600 hover:text-indigo-800">
                                                            <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M4 16v1a3 3 0 003 3h10a3 3 0 003-3v-1m-4-4l-4 4m0 0l-4-4m4 4V4"/>
                                                            </svg>
                                                        </a>
                                                        <button type="button" @click="confirmDeleteFile(file.id)" class="text-red-600 hover:text-red-900">
                                                            <i class="fas fa-trash"></i>
                                                        </button>
                                                    </div>
                                                </div>
                                            </template>
                                        </td>
                                        <td class="px-6 py-4 whitespace-nowrap text-sm font-medium">
                                            <a :href="'/manage/data-dukung/' + item.id + '/edit'" class="text-indigo-600 hover:text-indigo-900 mr-3 inline-flex items-center">
                                                <svg class="w-4 h-4 mr-1" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M11 5H6a2 2 0 00-2 2v11a2 2 0 002 2h11a2 2 0 002-2v-5m-1.414-9.414a2 2 0 112.828 2.828L11.828 15H9v-2.828l8.586-8.586z"/>
                                                </svg>
                                            </a>
                                            <button type="button" @click="confirmDelete(item.id)" class="text-red-600 hover:text-red-900 inline-flex items-center">
                                                <svg class="w-4 h-4 mr-1" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 7l-.867 12.142A2 2 0 0116.138 21H7.862a2 2 0 01-1.995-1.858L5 7m5 4v6m4-6v6m1-10V4a1 1 0 00-1-1h-4a1 1 0 00-1 1v3M4 7h16"/>
                                                </svg>
                                            </button>
                                        </td>
                                    </tr>
                                </template>
                                <tr x-show="!isLoading && items.length === 0">
                                    <td colspan="6" class="px-6 py-4 text-center text-gray-500">
                                        Tidak ada data dukung
                                    </td>
                                </tr>
                            </tbody>
                        </table>
                    </div>

                    <!-- Pagination -->
                    <div class="flex flex-col md:flex-row justify-between items-center mt-4 space-y-4 md:space-y-0">
                        <div class="text-sm text-gray-500">
                            Showing <span x-text="((currentPage - 1) * perPage) + 1"></span>
                            to <span x-text="Math.min(currentPage * perPage, totalItems)"></span>
                            of <span x-text="totalItems"></span> data
                        </div>
                        
                        <div class="flex items-center space-x-2">
                            <button 
                                @click="changePage(currentPage - 1)"
                                :disabled="currentPage === 1"
                                :class="{'opacity-50 cursor-not-allowed': currentPage === 1}"
                                class="px-3 py-1 bg-white border border-gray-300 rounded-md text-sm font-medium text-gray-700 hover:bg-gray-50">
                                Prev    
                            </button>
                            
                            <div class="flex items-center space-x-1">
                                <span class="text-sm text-gray-700">Page <span x-text="currentPage"></span> of <span x-text="lastPage"></span></span>
                            </div>
                            
                            <button 
                                @click="changePage(currentPage + 1)"
                                :disabled="currentPage === lastPage"
                                :class="{'opacity-50 cursor-not-allowed': currentPage === lastPage}"
                                class="px-3 py-1 bg-white border border-gray-300 rounded-md text-sm font-medium text-gray-700 hover:bg-gray-50">
                                Next
                            </button>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>

    @push('scripts')
    <script>
        function dataDukung() {
            return {
                items: [],
                currentPage: 1,
                lastPage: 1,
                searchTerm: '',
                perPage: 10,
                isLoading: true,
                totalItems: 0,

                formatFileSize(bytes) {
                    if (!bytes || bytes === 0) return 'Ukuran tidak tersedia';
                    const k = 1024;
                    const sizes = ['Bytes', 'KB', 'MB', 'GB'];
                    const i = Math.floor(Math.log(bytes) / Math.log(k));
                    return parseFloat((bytes / Math.pow(k, i)).toFixed(2)) + ' ' + sizes[i];
                },

                get pages() {
                    const pages = [];
                    const maxPages = 5;
                    const halfMaxPages = Math.floor(maxPages / 2);
                    
                    let startPage = Math.max(1, this.currentPage - halfMaxPages);
                    let endPage = Math.min(this.lastPage, startPage + maxPages - 1);
                    
                    if (endPage - startPage + 1 < maxPages) {
                        startPage = Math.max(1, endPage - maxPages + 1);
                    }
                    
                    for (let i = startPage; i <= endPage; i++) {
                        pages.push(i);
                    }
                    
                    return pages;
                },

                async fetchData() {
                    this.isLoading = true;
                    try {
                        const apiToken = document.querySelector('meta[name="api-token"]')?.getAttribute('content');
                        
                        const response = await fetch(`/api/data-dukung?page=${this.currentPage}&search=${this.searchTerm}&per_page=${this.perPage}`, {
                            headers: {
                                'Accept': 'application/json',
                                'Content-Type': 'application/json',
                                'Authorization': `Bearer ${apiToken}`,
                                'X-CSRF-TOKEN': document.querySelector('meta[name="csrf-token"]').getAttribute('content')
                            },
                            credentials: 'include'
                        });
                        
                        if (!response.ok) {
                            const errorText = await response.text();
                            console.error('Response not OK:', {
                                status: response.status,
                                statusText: response.statusText,
                                body: errorText
                            });
                            
                            if (response.status === 401) {
                                Swal.fire({
                                    icon: 'error',
                                    title: 'Sesi Berakhir',
                                    text: 'Sesi Anda telah berakhir. Silakan login kembali.',
                                    confirmButtonText: 'Login Ulang'
                                }).then((result) => {
                                    if (result.isConfirmed) {
                                        window.location.href = '/login';
                                    }
                                });
                                return;
                            }
                            
                            throw new Error(`Network response was not ok: ${response.status} ${response.statusText}`);
                        }
                        
                        const result = await response.json();
                        
                        if (!result.data) {
                            console.error('Invalid response format:', result);
                            throw new Error('Invalid response format from server');
                        }

                        this.items = Array.isArray(result.data) ? result.data : (result.data.data || []);
                        this.currentPage = result.meta?.current_page || result.current_page || 1;
                        this.lastPage = result.meta?.last_page || result.last_page || 1;
                        this.totalItems = result.meta?.total || result.total || this.items.length;
                    } catch (error) {
                        console.error('Detailed error:', error);
                        Swal.fire({
                            icon: 'error',
                            title: 'Oops...',
                            text: `Terjadi kesalahan saat mengambil data: ${error.message}`
                        });
                    } finally {
                        this.isLoading = false;
                    }
                },

                async changePage(page) {
                    if (page >= 1 && page <= this.lastPage) {
                        this.currentPage = page;
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
                                const apiToken = document.querySelector('meta[name="api-token"]')?.getAttribute('content');
                                const csrfToken = document.querySelector('meta[name="csrf-token"]')?.getAttribute('content');
                                
                                const formData = new FormData();
                                formData.append('_method', 'DELETE');
                                formData.append('_token', csrfToken);
                                
                                const response = await fetch(`/manage/data-dukung/${id}`, {
                                    method: 'POST',
                                    headers: {
                                        'Accept': 'application/json',
                                        'Authorization': `Bearer ${apiToken}`
                                    },
                                    body: formData,
                                    credentials: 'include'
                                });

                                if (!response.ok) {
                                    const errorText = await response.text();
                                    console.error('Delete response not OK:', {
                                        status: response.status,
                                        statusText: response.statusText,
                                        body: errorText
                                    });
                                    throw new Error('Gagal menghapus data');
                                }

                                await this.fetchData();
                                Swal.fire({
                                    icon: 'success',
                                    title: 'Terhapus!',
                                    text: 'Data dukung berhasil dihapus.',
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
                                const apiToken = document.querySelector('meta[name="api-token"]')?.getAttribute('content');
                                const csrfToken = document.querySelector('meta[name="csrf-token"]')?.getAttribute('content');
                                
                                console.log('Attempting to delete file:', {
                                    fileId: id,
                                    hasApiToken: !!apiToken,
                                    hasCsrfToken: !!csrfToken
                                });
                                
                                const formData = new FormData();
                                formData.append('_method', 'DELETE');
                                formData.append('_token', csrfToken);
                                
                                const response = await fetch(`/manage/data-dukung/file/${id}`, {
                                    method: 'POST',
                                    headers: {
                                        'Accept': 'application/json',
                                        'Authorization': `Bearer ${apiToken}`
                                    },
                                    body: formData,
                                    credentials: 'include'
                                });

                                const responseData = await response.text();
                                console.log('Delete file response:', {
                                    status: response.status,
                                    statusText: response.statusText,
                                    response: responseData
                                });

                                if (!response.ok) {
                                    throw new Error(responseData || 'Gagal menghapus file');
                                }

                                Swal.fire({
                                    icon: 'success',
                                    title: 'Berhasil!',
                                    text: 'File berhasil dihapus',
                                    timer: 1500
                                }).then(() => {
                                    this.fetchData();
                                });
                            } catch (error) {
                                console.error('Detailed error:', error);
                                let errorMessage = 'Terjadi kesalahan saat menghapus file!';
                                
                                try {
                                    const errorData = JSON.parse(error.message);
                                    errorMessage = errorData.message || errorMessage;
                                } catch (e) {
                                    errorMessage = error.message || errorMessage;
                                }
                                
                                Swal.fire({
                                    icon: 'error',
                                    title: 'Oops...',
                                    text: errorMessage
                                });
                            }
                        }
                    });
                },

                init() {
                    this.fetchData();
                }
            }
        }
    </script>
    @endpush
</x-app-layout>

<link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/sweetalert2@11/dist/sweetalert2.min.css">
<script src="https://cdn.jsdelivr.net/npm/sweetalert2@11"></script>
@else
<script>window.location = "{{ route('dashboard') }}";</script>
@endif 