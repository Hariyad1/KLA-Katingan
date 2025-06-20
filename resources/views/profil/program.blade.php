<x-main-layout>
    <div class="relative h-[300px] flex items-center justify-center overflow-hidden">
        <div class="absolute inset-0">
            <img src="{{ asset('images/inner-head.png') }}" alt="Header Background" class="w-full h-full object-cover">
            <div class="absolute inset-0 bg-gradient-to-b from-purple-900/50 to-purple-900/70"></div>
        </div>
        
        <div class="absolute inset-0">
            <div class="absolute left-20 top-20">
                <svg width="80" height="80" viewBox="0 0 80 80" class="text-orange-500 opacity-80">
                    <circle cx="40" cy="40" r="40" fill="currentColor"/>
                </svg>
            </div>
            
            <div class="absolute right-32 top-16">
                <svg width="24" height="24" viewBox="0 0 24 24" class="text-yellow-300 opacity-80">
                    <path fill="currentColor" d="M12 2l3.09 6.26L22 9.27l-5 4.87 1.18 6.88L12 17.77l-6.18 3.25L7 14.14 2 9.27l6.91-1.01L12 2z"/>
                </svg>
            </div>
            <div class="absolute right-48 bottom-24">
                <svg width="16" height="16" viewBox="0 0 24 24" class="text-yellow-300 opacity-80">
                    <path fill="currentColor" d="M12 2l3.09 6.26L22 9.27l-5 4.87 1.18 6.88L12 17.77l-6.18 3.25L7 14.14 2 9.27l6.91-1.01L12 2z"/>
                </svg>
            </div>
            
            <div class="absolute right-16 top-12">
                <svg width="100" height="100" viewBox="0 0 100 100" class="text-yellow-300 opacity-80 transform -rotate-45">
                    <path fill="currentColor" d="M50 0 L52 98 L48 98 L50 0 Z"/>
                    <circle cx="50" cy="10" r="8" fill="currentColor"/>
                </svg>
            </div>
        </div>
        
        <div class="relative z-10 text-center px-4 py-8">
            <h1 class="text-4xl md:text-5xl font-extrabold text-white mb-6 tracking-wide mx-4">
                PROGRAM KERJA/KEGIATAN
            </h1>
            <div class="flex items-center justify-center text-white text-base md:text-lg font-medium space-x-2 md:space-x-3">
                <a href="{{ route('home') }}" class="hover:text-yellow-300 transition-colors px-1">Beranda</a>
                <span class="text-yellow-300">•</span>
                <a href="#" class="hover:text-yellow-300 transition-colors px-1">Profil</a>
                <span class="text-yellow-300">•</span>
                <span class="text-yellow-300 px-1">Program Kerja/Kegiatan</span>
            </div>
        </div>
    </div>

    @push('styles')
    <style>
        .program-content {
            max-width: none;
            font-family: -apple-system, BlinkMacSystemFont, "Segoe UI", Roboto, Helvetica, Arial, sans-serif;
            font-size: 14px;
            line-height: 1.6;
            color: #333;
        }
        
        .program-content p {
            margin-top: 1.5em !important;
            margin-bottom: 1.5em !important;
        }

        .program-content p:empty {
            min-height: 1em !important;
            display: block !important;
        }

        .program-content ul, 
        .program-content ol {
            margin-top: 1.25em !important;
            margin-bottom: 1.25em !important;
            padding-left: 2em !important;
            list-style-position: outside !important;
        }

        .program-content ul {
            list-style-type: disc !important;
        }
        
        .program-content ol {
            list-style-type: decimal !important;
        }

        .program-content h1, 
        .program-content h2, 
        .program-content h3, 
        .program-content h4, 
        .program-content h5, 
        .program-content h6 {
            margin-top: 1.5em !important;
            margin-bottom: 0.75em !important;
            font-weight: 600 !important;
        }
        
        .program-content h1 { font-size: 1.875rem !important; }
        .program-content h2 { font-size: 1.5rem !important; }
        .program-content h3 { font-size: 1.25rem !important; }
        .program-content h4 { font-size: 1.125rem !important; }
        
        .program-content blockquote,
        .program-content pre,
        .program-content figure,
        .program-content table {
            margin-top: 1.5em !important;
            margin-bottom: 1.5em !important;
        }

        .program-content * {
            line-height: 1.6 !important;
        }
        
        .program-content table {
            width: 100% !important;
            border-collapse: collapse !important;
        }
        
        .program-content table th,
        .program-content table td {
            border: 1px solid #e2e8f0 !important;
            padding: 0.5rem !important;
        }
        
        .program-content table th {
            background-color: #f8fafc !important;
            font-weight: 600 !important;
        }
        
        .program-content ul li,
        .program-content ol li {
            display: list-item !important;
            margin-bottom: 0.5em !important;
        }
        
        .program-content ul > li::marker,
        .program-content ol > li::marker {
            display: inline-block !important;
        }
        
        .program-content li ul,
        .program-content li ol {
            margin-top: 0.5em !important;
        }

        /* Loading Indicator */
        .search-loader-container {
            transition: all 0.3s ease;
        }
        
        .search-loader {
            width: 24px;
            height: 24px;
            border: 3px solid #f3f3f3;
            border-top: 3px solid #9333ea;
            border-radius: 50%;
            animation: spin 1s linear infinite;
        }
        
        @keyframes spin {
            0% { transform: rotate(0deg); }
            100% { transform: rotate(360deg); }
        }
    </style>
    @endpush

    @push('scripts')
    <script src="https://cdn.jsdelivr.net/npm/sweetalert2@11"></script>
    <script>
        document.addEventListener('DOMContentLoaded', function() {
            @if(session('success'))
                Swal.fire({
                    icon: 'success',
                    title: 'Berhasil!',
                    text: "{{ session('success') }}",
                    confirmButtonColor: '#9333ea',
                    timer: 3000,
                    timerProgressBar: true
                });
            @endif
        });
    </script>
    <script>
        document.addEventListener('DOMContentLoaded', function() {
            const searchInput = document.getElementById('search');
            const tahunSelect = document.getElementById('tahun');
            const opdSelect = document.getElementById('opd_id');
            const filterForm = document.getElementById('filterForm');
            const programContainer = document.getElementById('program-container');
            const paginationContainer = document.getElementById('pagination-container');
            const searchLoader = document.getElementById('searchLoader');
            
            let searchTimeout;
            const baseUrl = window.location.origin;
            
            function fetchData(url) {
                searchLoader.classList.remove('hidden');
                
                //console.log('Fetching data from:', url);
                
                fetch(url, {
                    method: 'GET',
                    headers: {
                        'Accept': 'text/html',
                        'X-Requested-With': 'XMLHttpRequest'
                    },
                    cache: 'no-store'
                })
                .then(response => {
                    if (!response.ok) {
                        throw new Error(`HTTP error! Status: ${response.status}`);
                    }
                    return response.text();
                })
                .then(html => {
                    // Update both containers with new content
                    const tempDiv = document.createElement('div');
                    tempDiv.innerHTML = html;
                    
                    const newProgramContainer = tempDiv.querySelector('#program-container');
                    const newPaginationContainer = tempDiv.querySelector('#pagination-container');
                    
                    if (newProgramContainer && programContainer) {
                        programContainer.innerHTML = newProgramContainer.innerHTML;
                    }
                    
                    if (newPaginationContainer && paginationContainer) {
                        paginationContainer.innerHTML = newPaginationContainer.innerHTML;
                    }
                    
                    // Re-initialize event handlers for new content
                    initializeEventHandlers();
                    
                    // Update URL without page reload
                    const browserUrl = new URL(url);
                    window.history.pushState({}, '', browserUrl.toString());
                })
                .catch(error => {
                    console.error('Fetch error:', error);
                    Swal.fire({
                        icon: 'error',
                        title: 'Error!',
                        text: 'Gagal memuat data. Silakan coba lagi.',
                        confirmButtonColor: '#9333ea'
                    });
                })
                .finally(() => {
                    searchLoader.classList.add('hidden');
                });
            }
            
            function updateUI(data) {
                if (programContainer) programContainer.innerHTML = '';
                if (paginationContainer) paginationContainer.innerHTML = '';
                
                if (!data.success) {
                    handleErrorResponse(data);
                    return;
                }
                
                if (data.html && programContainer) {
                    programContainer.innerHTML = data.html;
                }
                
                if (data.pagination && paginationContainer) {
                    paginationContainer.innerHTML = data.pagination;
                }
                
                initializeEventHandlers();
                
                if (searchInput.value.trim()) {
                    console.log(`Ditemukan ${data.total || 0} hasil untuk: "${searchInput.value.trim()}"`);
                }
            }
            
            function handleErrorResponse(data) {
                console.warn('API Error:', data.message || 'Unknown error');
                
                if (data.redirect_to) {
                    console.log('Redirecting to:', data.redirect_to);
                    fetchData(data.redirect_to);
                    return;
                }
                
                Swal.fire({
                    icon: 'error',
                    title: 'Error!',
                    text: data.message || 'Terjadi kesalahan saat memproses permintaan',
                    confirmButtonColor: '#9333ea'
                });
            }
            
            function buildApiUrl() {
                const params = new URLSearchParams();
                
                if (tahunSelect.value) params.append('tahun', tahunSelect.value);
                if (opdSelect.value) params.append('opd_id', opdSelect.value);
                if (searchInput.value.trim() && searchInput.value.trim().length >= 2) {
                    params.append('search', searchInput.value.trim());
                }
                
                return `${baseUrl}/profil/program-kerja?${params.toString()}`;
            }
            
            function initializeEventHandlers() {
                document.querySelectorAll('.pagination-link').forEach(link => {
                    link.addEventListener('click', handlePaginationClick);
                });
            }
            
            function handlePaginationClick(e) {
                e.preventDefault();
                
                const href = this.getAttribute('href');
                if (!href) return;
                
                try {
                    const url = new URL(href, window.location.origin);
                    
                    if (searchInput.value.trim()) {
                        url.searchParams.set('search', searchInput.value.trim());
                    }
                    
                    if (tahunSelect.value) {
                        url.searchParams.set('tahun', tahunSelect.value);
                    }
                    
                    if (opdSelect.value) {
                        url.searchParams.set('opd_id', opdSelect.value);
                    }
                    
                    const controllerUrl = `${baseUrl}/profil/program-kerja${url.search}`;
                    //console.log('Pagination URL:', controllerUrl);
                    
                    fetchData(controllerUrl);
                    
                    // Re-initialize event handlers for new content
                    initializeEventHandlers();
                    
                    programContainer.scrollIntoView({ behavior: 'smooth' });
                } catch (error) {
                    console.error('Pagination error:', error);
                    Swal.fire({
                        icon: 'error',
                        title: 'Error!',
                        text: 'Terjadi kesalahan saat navigasi. Silakan coba lagi.',
                        confirmButtonColor: '#9333ea'
                    });
                }
            }
            
            function showSuccessMessage(message) {
                Swal.fire({
                    icon: 'success',
                    title: 'Berhasil!',
                    text: message,
                    confirmButtonColor: '#9333ea',
                    timer: 3000,
                    timerProgressBar: true
                });
            }
            
            filterForm.addEventListener('submit', function(e) {
                e.preventDefault();
                fetchData(buildApiUrl());
            });
            
            // Add search input event listener
            searchInput.addEventListener('input', function() {
                clearTimeout(searchTimeout);
                searchTimeout = setTimeout(() => {
                    fetchData(buildApiUrl());
                }, 500); // Delay 500ms after user stops typing
            });
            
            tahunSelect.addEventListener('change', () => fetchData(buildApiUrl()));
            opdSelect.addEventListener('change', () => fetchData(buildApiUrl()));
            
            initializeEventHandlers();
        });
    </script>
    @endpush

    <div class="py-12">
        <div class="max-w-7xl mx-auto sm:px-6 lg:px-8">
            <div class="bg-white overflow-hidden shadow-sm sm:rounded-lg">
                <div class="p-6">
                    <div class="prose max-w-none">
                        <div class="mb-8 bg-gray-50 p-6 rounded-lg">
                            <div class="flex flex-col md:flex-row justify-between items-center gap-4 mb-6 px-4">
                                <h3 class="text-xl font-bold text-gray-800">Filter Program Kerja/Kegiatan</h3>
                            </div>
                            <form action="{{ route('profil.program') }}" method="GET" id="filterForm" class="flex flex-wrap items-end gap-4">
                                <div class="grid grid-cols-10 gap-4 w-full">
                                    <div class="col-span-10 md:col-span-2">
                                        <label for="tahun" class="block text-sm font-medium text-gray-700 mb-1">Tahun</label>
                                        <select id="tahun" name="tahun" class="w-full rounded-md border-gray-300 shadow-sm focus:border-purple-500 focus:ring-purple-500">
                                            <option value="">Semua Tahun</option>
                                            @foreach($tahunList as $t)
                                                <option value="{{ $t }}" {{ $tahun == $t ? 'selected' : '' }}>{{ $t }}</option>
                                            @endforeach
                                        </select>
                                    </div>
                                    
                                    <div class="col-span-10 md:col-span-3">
                                        <label for="opd_id" class="block text-sm font-medium text-gray-700 mb-1">Perangkat Daerah</label>
                                        <select id="opd_id" name="opd_id" class="w-full rounded-md border-gray-300 shadow-sm focus:border-purple-500 focus:ring-purple-500">
                                            <option value="">Semua Perangkat Daerah</option>
                                            @foreach($opds as $o)
                                                <option value="{{ $o->id }}" {{ $opd_id == $o->id ? 'selected' : '' }}>{{ $o->name }}</option>
                                            @endforeach
                                        </select>
                                    </div>
                                    
                                    <div class="col-span-10 md:col-span-5 flex gap-2 items-end">
                                        <div class="flex-grow">
                                            <label for="search" class="block text-sm font-medium text-gray-700 mb-1">Kata Kunci</label>
                                            <input type="text" id="search" name="search" value="{{ $search ?? request('search') }}" placeholder="Cari program kerja..." class="w-full rounded-md border-gray-300 shadow-sm focus:border-purple-500 focus:ring-purple-500 pl-3">
                                        </div>
                                        <button type="submit" class="px-4 py-2 bg-purple-600 text-white rounded-md hover:bg-purple-700 transition-colors flex items-center gap-2 h-[38px]">
                                            <svg xmlns="http://www.w3.org/2000/svg" class="h-5 w-5" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M21 21l-6-6m2-5a7 7 0 11-14 0 7 7 0 0114 0z" />
                                            </svg>
                                            Cari
                                        </button>
                                    </div>
                                </div>
                            </form>
                        </div>

                        <div class="mt-8">
                            <div class="flex items-center gap-4 mb-6">
                                <div class="w-12 h-12 bg-purple-100 rounded-lg flex items-center justify-center">
                                    <svg class="w-6 h-6 text-purple-600" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 5H7a2 2 0 00-2 2v12a2 2 0 002 2h10a2 2 0 002-2V7a2 2 0 00-2-2h-2M9 5a2 2 0 002 2h2a2 2 0 002-2M9 5a2 2 0 012-2h2a2 2 0 012 2m-3 7h3m-3 4h3m-6-4h.01M9 16h.01"/>
                                    </svg>
                                </div>
                                <h2 class="text-2xl font-bold text-gray-800">
                                    Program Kerja/Kegiatan Kabupaten Kota Layak Anak (KLA) 
                                </h2>
                            </div>
                            
                            <div id="program-container" class="space-y-6 mt-6">
                                <div id="searchLoader" class="search-loader-container py-10 hidden">
                                    <div class="flex justify-center items-center">
                                        <div class="search-loader"></div>
                                        <span class="ml-2 text-gray-600">Memuat data...</span>
                                    </div>
                                </div>
                                @include('profil.partials.program-cards', ['programKerjas' => $programKerjas])
                            </div>

                            <div id="pagination-container" class="mt-6">
                                @include('profil.partials.pagination', ['programKerjas' => $programKerjas])
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
</x-main-layout> 