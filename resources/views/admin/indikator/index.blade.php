<x-app-layout>
    <x-slot name="header">
        <div class="flex justify-between items-center">
            <h2 class="font-semibold text-xl text-gray-800 leading-tight">
                {{ __('Kelola Indikator') }}
            </h2>
            <a href="{{ route('admin.indikator.create') }}" class="bg-indigo-600 hover:bg-indigo-700 text-white font-bold py-2 px-4 rounded inline-flex items-center">
                <i class="fas fa-plus mr-2"></i>
                <span>Tambah Indikator</span>
            </a>
        </div>
    </x-slot>

    <div class="py-12">
        <div class="max-w-7xl mx-auto sm:px-6 lg:px-8">
            <div class="bg-white overflow-hidden shadow-sm sm:rounded-lg">
                <div class="p-6">
                    <!-- Header dengan dropdown dan pencarian -->
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

                    <!-- Tabel -->
                    <div class="overflow-x-auto">
                        <table class="min-w-full divide-y divide-gray-200">
                            <thead class="bg-gray-50">
                                <tr>
                                    <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">No</th>
                                    <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Klaster</th>
                                    <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Indikator</th>
                                    <th class="px-6 py-3 text-right text-xs font-medium text-gray-500 uppercase tracking-wider">Aksi</th>
                                </tr>
                            </thead>
                            <tbody id="indikatorTableBody" class="bg-white divide-y divide-gray-200">
                                <!-- Data akan diisi melalui JavaScript -->
                            </tbody>
                        </table>
                        
                        <!-- Loading Spinner -->
                        <div id="loadingSpinner" class="hidden">
                            <div class="flex justify-center items-center py-4">
                                <div class="animate-spin rounded-full h-8 w-8 border-b-2 border-indigo-600"></div>
                            </div>
                        </div>

                        <!-- Pagination -->
                        <div class="flex items-center justify-between px-4 py-3 bg-white border-t border-gray-200 sm:px-6">
                            <div class="flex items-center">
                                <span id="paginationInfo" class="text-sm text-gray-700"></span>
                            </div>
                            <div class="flex items-center space-x-2">
                                <button id="prevPage" class="px-4 py-2 border rounded-md text-sm font-medium text-gray-700 bg-white hover:bg-gray-50 disabled:opacity-50 disabled:cursor-not-allowed">
                                    Prev
                                </button>
                                <span class="px-4 py-2 border rounded-md text-sm font-medium text-gray-700 bg-white">1</span>
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
        // Inisialisasi Notyf
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

        // Fungsi untuk memuat data indikator
        async function loadIndikator() {
            try {
                document.getElementById('loadingSpinner').classList.remove('hidden');
                
                const response = await fetch(`/api/indikator?page=${currentPage}&search=${searchQuery}&per_page=${document.getElementById('entries').value}`);
                const data = await response.json();
                
                const tableBody = document.getElementById('indikatorTableBody');
                tableBody.innerHTML = '';
                
                if (data.data.length === 0) {
                    tableBody.innerHTML = `
                        <tr>
                            <td colspan="3" class="px-6 py-4 text-center text-sm text-gray-500">
                                Tidak ada indikator yang tersedia
                            </td>
                        </tr>
                    `;
                    document.getElementById('paginationInfo').textContent = 'Showing 0 data';
                    return;
                }

                data.data.forEach((indikator, index) => {
                    const startingNumber = (currentPage - 1) * perPage + 1;
                    tableBody.innerHTML += `
                        <tr>
                            <td class="px-6 py-4 whitespace-nowrap">
                                <div class="text-sm text-gray-900">${startingNumber + index}</div>
                            </td>
                            <td class="px-6 py-4 whitespace-nowrap">
                                <div class="text-sm font-medium text-gray-900">${indikator.klaster_name || '-'}</div>
                            </td>
                            <td class="px-6 py-4">
                                <div class="text-sm text-gray-900">${indikator.name || '-'}</div>
                            </td>
                            <td class="px-6 py-4 whitespace-nowrap text-right text-sm font-medium space-x-3">
                                <a href="/manage/indikator/${indikator.id}/edit" class="text-indigo-600 hover:text-indigo-900 inline-flex items-center" title="Edit Indikator">
                                    <i class="fas fa-edit"></i>
                                </a>
                                <button onclick="confirmDelete(${indikator.id})" class="text-red-600 hover:text-red-900 inline-flex items-center" title="Hapus Indikator">
                                    <i class="fas fa-trash"></i>
                                </button>
                            </td>
                        </tr>
                    `;
                });

                // Update pagination
                totalPages = Math.ceil(data.total / perPage);
                document.getElementById('prevPage').disabled = currentPage === 1;
                document.getElementById('nextPage').disabled = currentPage === totalPages;
                
                // Update pagination info
                document.getElementById('paginationInfo').textContent = 
                    `Showing ${data.from || 0} to ${data.to || 0} of ${data.total} data`;

            } catch (error) {
                notyf.error('Gagal memuat data indikator');
                console.error('Error:', error);
            } finally {
                document.getElementById('loadingSpinner').classList.add('hidden');
            }
        }

        // Event listener untuk dropdown entries
        document.getElementById('entries').addEventListener('change', () => {
            currentPage = 1;
            loadIndikator();
        });

        // Event listener untuk pencarian
        let searchTimeout;
        document.getElementById('searchInput').addEventListener('input', (e) => {
            clearTimeout(searchTimeout);
            searchTimeout = setTimeout(() => {
                searchQuery = e.target.value;
                currentPage = 1;
                loadIndikator();
            }, 500);
        });

        // Event listener untuk pagination
        document.getElementById('prevPage').addEventListener('click', () => {
            if (currentPage > 1) {
                currentPage--;
                loadIndikator();
            }
        });

        document.getElementById('nextPage').addEventListener('click', () => {
            if (currentPage < totalPages) {
                currentPage++;
                loadIndikator();
            }
        });

        // Fungsi konfirmasi hapus indikator
        async function confirmDelete(id) {
            const result = await Swal.fire({
                title: 'Apakah Anda yakin?',
                text: "Indikator yang dihapus tidak dapat dikembalikan!",
                icon: 'warning',
                showCancelButton: true,
                confirmButtonColor: '#3085d6',
                cancelButtonColor: '#d33',
                confirmButtonText: 'Ya, hapus!',
                cancelButtonText: 'Batal'
            });

            if (result.isConfirmed) {
                try {
                    const response = await fetch(`/api/indikator/${id}`, {
                        method: 'DELETE',
                        headers: {
                            'X-CSRF-TOKEN': document.querySelector('meta[name="csrf-token"]').content
                        }
                    });

                    if (response.ok) {
                        notyf.success('Indikator berhasil dihapus');
                        loadIndikator();
                    } else {
                        throw new Error('Gagal menghapus indikator');
                    }
                } catch (error) {
                    notyf.error('Gagal menghapus indikator');
                    console.error('Error:', error);
                }
            }
        }

        // Load data saat halaman dimuat
        document.addEventListener('DOMContentLoaded', () => {
            loadIndikator();
        });

        // Tampilkan notifikasi sukses jika ada
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