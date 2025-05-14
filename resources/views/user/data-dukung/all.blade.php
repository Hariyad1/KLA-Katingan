<x-app-layout>
    <x-slot name="header">
        <h2 class="font-semibold text-xl text-gray-800 leading-tight">
            {{ __('Semua Data Dukung') }}
        </h2>
    </x-slot>

    <div id="dataDukungContainer" 
         data-items="{{ json_encode($dataDukungs->items()) }}" 
         data-total="{{ $dataDukungs->total() }}" 
         data-last-page="{{ $dataDukungs->lastPage() }}" 
         data-per-page="{{ $dataDukungs->perPage() }}" 
         style="display: none;"></div>

    @if(request()->ajax())
        <script>
            window.dataDukungItems = @json($dataDukungs->items());
        </script>
    @endif

    <div class="py-12">
        <div class="max-w-7xl mx-auto sm:px-6 lg:px-8">
            <div class="bg-white overflow-hidden shadow-sm sm:rounded-lg">
                <div class="p-6 text-gray-900">
                    <!-- Show Entries & Filter Section -->
                    <div class="mb-6 grid grid-cols-1 md:grid-cols-4 gap-4">
                        <div>
                            <label for="entries" class="block text-sm font-medium text-gray-700 mb-1">Show Entries</label>
                            <select id="entries" class="w-full rounded-md border-gray-300 shadow-sm focus:border-indigo-500 focus:ring-indigo-500">
                                <option value="10">10</option>
                                <option value="25">25</option>
                                <option value="50">50</option>
                                <option value="100">100</option>
                            </select>
                        </div>
                        <div>
                            <label for="opd_filter" class="block text-sm font-medium text-gray-700 mb-1">Perangkat Daerah</label>
                            <select id="opd_filter" class="w-full rounded-md border-gray-300 shadow-sm focus:border-indigo-500 focus:ring-indigo-500">
                                <option value="">Semua Perangkat Daerah</option>
                                @foreach($opds as $opd)
                                    <option value="{{ $opd->id }}">{{ $opd->name }}</option>
                                @endforeach
                            </select>
                        </div>
                        <div>
                            <label for="klaster_filter" class="block text-sm font-medium text-gray-700 mb-1">Klaster</label>
                            <select id="klaster_filter" class="w-full rounded-md border-gray-300 shadow-sm focus:border-indigo-500 focus:ring-indigo-500">
                                <option value="">Semua Klaster</option>
                                @foreach($klasters as $klaster)
                                    <option value="{{ $klaster->id }}">{{ $klaster->name }}</option>
                                @endforeach
                            </select>
                        </div>
                        <div>
                            <label for="search" class="block text-sm font-medium text-gray-700 mb-1">Pencarian</label>
                            <input type="text" id="search" class="w-full rounded-md border-gray-300 shadow-sm focus:border-indigo-500 focus:ring-indigo-500" placeholder="Cari data dukung...">
                        </div>
                    </div>

                    <!-- Data Grid -->
                    <div class="overflow-x-auto bg-white rounded-lg shadow overflow-y-auto relative">
                        <table class="border-collapse table-auto w-full bg-white table-striped relative">
                            <thead>
                                <tr class="text-left">
                                    <th class="bg-gray-50 sticky top-0 border-b border-gray-200 px-6 py-3 text-gray-600 font-bold tracking-wider uppercase text-xs w-16">No</th>
                                    <th class="bg-gray-50 sticky top-0 border-b border-gray-200 px-6 py-3 text-gray-600 font-bold tracking-wider uppercase text-xs w-1/6">Perangkat Daerah</th>
                                    <th class="bg-gray-50 sticky top-0 border-b border-gray-200 px-6 py-3 text-gray-600 font-bold tracking-wider uppercase text-xs w-1/6">Klaster</th>
                                    <th class="bg-gray-50 sticky top-0 border-b border-gray-200 px-6 py-3 text-gray-600 font-bold tracking-wider uppercase text-xs w-1/4">Indikator</th>
                                    <th class="bg-gray-50 sticky top-0 border-b border-gray-200 px-6 py-3 text-gray-600 font-bold tracking-wider uppercase text-xs">File</th>
                                </tr>
                            </thead>
                            <tbody id="dataDukungTableBody" class="bg-white divide-y divide-gray-200">
                            </tbody>
                        </table>
                        
                        <!-- Loading Spinner -->
                        <div id="loadingSpinner" class="py-8">
                            <div class="flex justify-center items-center">
                                <div class="animate-spin rounded-full h-8 w-8 border-b-2 border-indigo-600"></div>
                                                            </div>
                                                        </div>
                        
                        <!-- No data message -->
                        <div id="noDataMessage" class="py-10 text-center hidden">
                                            <div class="flex flex-col items-center justify-center">
                                                <i class="fas fa-folder-open text-gray-400 text-5xl mb-4"></i>
                                                <span class="text-gray-500 text-lg">Tidak ada data dukung yang tersedia</span>
                                            </div>
                        </div>
                    </div>

                    <!-- Enhanced Pagination -->
                    <div class="mt-4 flex items-center justify-between">
                        <div id="paginationInfo" class="text-sm text-gray-700">
                            Showing 0 to 0 of 0 results
                        </div>
                        <div class="flex items-center space-x-4">
                            <button id="prevPageBtn" disabled class="px-4 py-2 text-sm font-medium text-gray-400 bg-gray-100 border border-gray-300 rounded-md cursor-not-allowed">
                                    Prev
                            </button>

                            <div class="text-sm text-gray-700">
                                Page <span id="currentPage">1</span> of <span id="totalPages">1</span>
                            </div>

                            <button id="nextPageBtn" disabled class="px-4 py-2 text-sm font-medium text-gray-400 bg-gray-100 border border-gray-300 rounded-md cursor-not-allowed">
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
        let allDataDukung = [];
        let filteredData = [];
        let currentPage = 1;
        let perPage = 10;

        const opdFilter = document.getElementById('opd_filter');
        const klasterFilter = document.getElementById('klaster_filter');
        const searchInput = document.getElementById('search');
        const entriesSelect = document.getElementById('entries');
        const tableBody = document.getElementById('dataDukungTableBody');
        const loadingSpinner = document.getElementById('loadingSpinner');
        const noDataMessage = document.getElementById('noDataMessage');
        const paginationInfo = document.getElementById('paginationInfo');
        const prevPageBtn = document.getElementById('prevPageBtn');
        const nextPageBtn = document.getElementById('nextPageBtn');
        const currentPageSpan = document.getElementById('currentPage');
        const totalPagesSpan = document.getElementById('totalPages');
        
        document.addEventListener('DOMContentLoaded', function() {
            fetchAllDataDukung();
                
            opdFilter.addEventListener('change', filterData);
            klasterFilter.addEventListener('change', filterData);
            entriesSelect.addEventListener('change', function() {
                perPage = parseInt(this.value);
                currentPage = 1;
                renderTable();
                
                const url = new URL(window.location);
                if (perPage === 10) {
                    url.searchParams.delete('per_page');
                } else {
                    url.searchParams.set('per_page', perPage);
                }
                window.history.pushState({}, '', url);
            });
            
        let timeoutId;
            searchInput.addEventListener('input', () => {
                clearTimeout(timeoutId);
                timeoutId = setTimeout(filterData, 300);
            });
            
            prevPageBtn.addEventListener('click', function() {
                if (currentPage > 1) {
                    currentPage--;
                    renderTable();
                }
            });
            
            nextPageBtn.addEventListener('click', function() {
                const totalPages = Math.ceil(filteredData.length / perPage);
                if (currentPage < totalPages) {
                    currentPage++;
                    renderTable();
                }
            });

            const params = new URLSearchParams(window.location.search);
            if (params.has('opd')) opdFilter.value = params.get('opd');
            if (params.has('klaster')) klasterFilter.value = params.get('klaster');
            if (params.has('search')) searchInput.value = params.get('search');
            if (params.has('per_page')) {
                entriesSelect.value = params.get('per_page');
                perPage = parseInt(params.get('per_page'));
            }
        });
        
        async function fetchAllDataDukung() {
            try {
                showLoading(true);
                
                const dataDukungContainer = document.getElementById('dataDukungContainer');
                if (dataDukungContainer) {
                    try {
                        allDataDukung = JSON.parse(dataDukungContainer.dataset.items);

                        const totalItems = parseInt(dataDukungContainer.dataset.total);
                        const lastPage = parseInt(dataDukungContainer.dataset.lastPage);
                        const serverPerPage = parseInt(dataDukungContainer.dataset.perPage);
                        
                        if (serverPerPage && serverPerPage !== perPage) {
                            entriesSelect.value = serverPerPage.toString();
                            perPage = serverPerPage;
                        }
                        
                        if (totalItems > allDataDukung.length) {
                            fetchAllPages(lastPage);
                        } else {
                            filterData();
                        }
                    } catch (parseError) {
                        console.error('Error parsing data:', parseError);
                        throw new Error('Gagal memuat data');
                    }
                } else {
                    throw new Error('Tidak ada data yang tersedia');
                }
            } catch (error) {
                console.error('Error fetching data:', error);
                showError('Gagal memuat data. Silakan coba lagi nanti.');
            } finally {
                showLoading(false);
            }
        }
        
        async function fetchAllPages(lastPage) {
            try {
                showLoading(true);
                
                const baseUrl = window.location.pathname;
                const currentParams = new URLSearchParams(window.location.search);
                let allResults = [...allDataDukung];
                
                for (let page = 2; page <= lastPage; page++) {
                    const pageParams = new URLSearchParams(currentParams);
                    pageParams.set('page', page);
                    
                    const response = await fetch(`${baseUrl}?${pageParams.toString()}`, {
                        headers: {
                            'X-Requested-With': 'XMLHttpRequest'
                        }
                    });
                    
                    if (!response.ok) {
                        throw new Error(`Error fetching page ${page}`);
                    }
                    
                    const html = await response.text();
                    const parser = new DOMParser();
                    const doc = parser.parseFromString(html, 'text/html');
                    const container = doc.getElementById('dataDukungContainer');
                    
                    if (container) {
                        const pageItems = JSON.parse(container.dataset.items);
                        allResults = [...allResults, ...pageItems];
                    }
                }
                
                allDataDukung = allResults;
                filterData();
            } catch (error) {
                console.error('Error fetching all pages:', error);
                filterData();
            } finally {
                showLoading(false);
            }
        }
        
        function filterData() {
            const opdId = opdFilter.value;
            const klasterId = klasterFilter.value;
            const searchTerm = searchInput.value.toLowerCase().trim();
            
            filteredData = allDataDukung.filter(item => {
                if (opdId && item.opd.id != opdId) return false;
                
                if (klasterId && item.indikator.klaster.id != klasterId) return false;
                
                if (searchTerm) {
                    const matchesOpd = item.opd.name.toLowerCase().includes(searchTerm);
                    const matchesKlaster = item.indikator.klaster.name.toLowerCase().includes(searchTerm);
                    const matchesIndikator = item.indikator.name.toLowerCase().includes(searchTerm);
 
                    const matchesFile = item.files.some(file => 
                        file.original_name.toLowerCase().includes(searchTerm)
                    );
                    
                    if (!matchesOpd && !matchesKlaster && !matchesIndikator && !matchesFile) {
                        return false;
                    }
                }
                
                return true;
            });
            
            currentPage = 1;
            renderTable();
            
            const url = new URL(window.location);
            
            if (opdId) url.searchParams.set('opd', opdId);
            else url.searchParams.delete('opd');
            
            if (klasterId) url.searchParams.set('klaster', klasterId);
            else url.searchParams.delete('klaster');
            
            if (searchTerm) url.searchParams.set('search', searchTerm);
            else url.searchParams.delete('search');
            
            if (perPage !== 10) {
                url.searchParams.set('per_page', perPage);
            } else {
                url.searchParams.delete('per_page');
            }
            
            const cleanedUrl = url.toString().replace(/\?$/, '');
            window.history.pushState({}, '', cleanedUrl);
        }
        
        function renderTable() {
            tableBody.innerHTML = '';
            
            if (filteredData.length === 0) {
                noDataMessage.classList.remove('hidden');
                updatePagination();
                return;
            }
            
            noDataMessage.classList.add('hidden');

            const start = (currentPage - 1) * perPage;
            const end = Math.min(start + perPage, filteredData.length);
            const pageData = filteredData.slice(start, end);
            
            pageData.forEach((dataDukung, index) => {
                const row = document.createElement('tr');
                row.className = 'hover:bg-gray-50';
                
                row.innerHTML = `
                    <td class="border-t px-6 py-4 w-16">${start + index + 1}</td>
                    <td class="border-t px-6 py-4 w-1/6">${dataDukung.opd.name}</td>
                    <td class="border-t px-6 py-4 w-1/6">${dataDukung.indikator.klaster.name}</td>
                    <td class="border-t px-6 py-4 w-1/4 break-words">${dataDukung.indikator.name}</td>
                    <td class="border-t px-6 py-4">
                        <div class="space-y-2 max-w-lg">
                            ${dataDukung.files.map(file => {
                                const extension = file.original_name.split('.').pop().toLowerCase();
                                let iconClass = 'fa-file text-gray-500';
                                
                                switch(extension) {
                                    case 'pdf': iconClass = 'fa-file-pdf text-red-500'; break;
                                    case 'doc': case 'docx': iconClass = 'fa-file-word text-blue-500'; break;
                                    case 'xls': case 'xlsx': iconClass = 'fa-file-excel text-green-500'; break;
                                    case 'jpg': case 'jpeg': case 'png': iconClass = 'fa-file-image text-purple-500'; break;
                                }
                                
                                const fileSizeMB = (file.size / 1024 / 1024).toFixed(2);
                                const truncatedName = file.original_name.length > 30 ? 
                                    file.original_name.substring(0, 27) + '...' : 
                                    file.original_name;
                                
                                return `
                                    <div class="flex items-center justify-between bg-gray-50 p-2 rounded-lg">
                                        <div class="flex items-center space-x-3 overflow-hidden">
                                            <i class="fas ${iconClass} text-lg flex-shrink-0"></i>
                                            <div class="flex flex-col min-w-0">
                                                <span class="text-sm text-gray-600 truncate" title="${file.original_name}">${truncatedName}</span>
                                                <span class="text-xs text-gray-500">${fileSizeMB} MB</span>
                                            </div>
                                        </div>
                                        <div class="flex-shrink-0 ml-2">
                                            <a href="/storage/${file.file}" 
                                               download="${file.original_name}"
                                               class="text-blue-600 hover:text-blue-900">
                                                <i class="fas fa-download"></i>
                                            </a>
                                        </div>
                                    </div>
                                `;
                            }).join('')}
                        </div>
                    </td>
                `;
                
                tableBody.appendChild(row);
            });
            
            updatePagination();
        }
        
        function updatePagination() {
            const totalItems = filteredData.length;
            const totalPages = Math.ceil(totalItems / perPage);
            const start = totalItems === 0 ? 0 : (currentPage - 1) * perPage + 1;
            const end = Math.min(start + perPage - 1, totalItems);
            
            paginationInfo.textContent = `Showing ${start} to ${end} of ${totalItems} results`;
            
            currentPageSpan.textContent = totalItems === 0 ? '0' : currentPage;
            totalPagesSpan.textContent = totalPages || 1;
            
            prevPageBtn.disabled = currentPage <= 1;
            nextPageBtn.disabled = currentPage >= totalPages || totalPages === 0;
            
            if (prevPageBtn.disabled) {
                prevPageBtn.className = 'px-4 py-2 text-sm font-medium text-gray-400 bg-gray-100 border border-gray-300 rounded-md cursor-not-allowed';
            } else {
                prevPageBtn.className = 'px-4 py-2 text-sm font-medium text-gray-700 bg-white border border-gray-300 rounded-md hover:bg-gray-50';
            }
            
            if (nextPageBtn.disabled) {
                nextPageBtn.className = 'px-4 py-2 text-sm font-medium text-gray-400 bg-gray-100 border border-gray-300 rounded-md cursor-not-allowed';
            } else {
                nextPageBtn.className = 'px-4 py-2 text-sm font-medium text-gray-700 bg-white border border-gray-300 rounded-md hover:bg-gray-50';
            }
        }
        
        function showLoading(isLoading) {
            if (isLoading) {
                loadingSpinner.classList.remove('hidden');
                tableBody.classList.add('hidden');
                noDataMessage.classList.add('hidden');
            } else {
                loadingSpinner.classList.add('hidden');
                tableBody.classList.remove('hidden');
            }
        }
        
        function showError(message) {
            tableBody.innerHTML = `
                <tr>
                    <td colspan="5" class="px-6 py-10 text-center">
                        <div class="flex flex-col items-center justify-center">
                            <i class="fas fa-exclamation-circle text-red-500 text-5xl mb-4"></i>
                            <span class="text-red-500 text-lg">${message}</span>
                        </div>
                    </td>
                </tr>
            `;
        }
    </script>
    @endpush

    @push('styles')
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/5.15.4/css/all.min.css">
    @endpush
</x-app-layout> 