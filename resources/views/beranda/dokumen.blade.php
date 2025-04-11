<x-main-layout>
    <!-- Header Section dengan Background Image -->
    <div class="relative h-[200px] md:h-[300px] flex items-center justify-center overflow-hidden">
        <!-- Background Image dengan Overlay -->
        <div class="absolute inset-0">
            <img src="{{ asset('images/inner-head.png') }}" alt="Header Background" class="w-full h-full object-cover">
            <div class="absolute inset-0 bg-gradient-to-b from-purple-900/50 to-purple-900/70"></div>
        </div>
        
        <!-- Decorative Elements - Sembunyikan di mobile -->
        <div class="absolute inset-0 hidden md:block">
            <!-- Orange Circle -->
            <div class="absolute left-20 top-20">
                <svg width="80" height="80" viewBox="0 0 80 80" class="text-orange-500 opacity-80">
                    <circle cx="40" cy="40" r="40" fill="currentColor"/>
                </svg>
            </div>
            
            <!-- Stars -->
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
            
            <!-- Shooting Star -->
            <div class="absolute right-16 top-12">
                <svg width="100" height="100" viewBox="0 0 100 100" class="text-yellow-300 opacity-80 transform -rotate-45">
                    <path fill="currentColor" d="M50 0 L52 98 L48 98 L50 0 Z"/>
                    <circle cx="50" cy="10" r="8" fill="currentColor"/>
                </svg>
            </div>
        </div>
        
        <!-- Content -->
        <div class="relative z-10 text-center px-4">
            <h1 class="text-3xl md:text-5xl font-extrabold text-white mb-4 tracking-wide">
                DOKUMEN
            </h1>
            <div class="flex items-center justify-center text-white text-base md:text-lg font-medium">
                <a href="{{ route('home') }}" class="hover:text-yellow-300 transition-colors">Beranda</a>
                <span class="mx-2 md:mx-3 text-yellow-300">•</span>
                <span class="text-yellow-300">Dokumen</span>
            </div>
        </div>
    </div>

    <!-- Main Content -->
    <div class="py-6 md:py-12">
        <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8">
            <div class="bg-white overflow-hidden shadow-sm sm:rounded-lg">
                <div class="p-4 md:p-6">
                    <!-- Controls Section -->
                    <div class="flex flex-col md:flex-row md:justify-between md:items-center gap-4 mb-6">
                        <div class="flex items-center gap-2 md:gap-3">
                            <span class="text-gray-600 text-sm md:text-base whitespace-nowrap">Show</span>
                            <select id="entries" class="border rounded-md px-2 py-1 md:px-3 md:py-1.5 text-sm md:text-base w-16 md:w-20">
                                <option value="10" {{ request('show') == 10 ? 'selected' : '' }}>10</option>
                                <option value="25" {{ request('show') == 25 ? 'selected' : '' }}>25</option>
                                <option value="50" {{ request('show') == 50 ? 'selected' : '' }}>50</option>
                                <option value="100" {{ request('show') == 100 ? 'selected' : '' }}>100</option>
                            </select>
                            <span class="text-gray-600 text-sm md:text-base whitespace-nowrap">entries</span>
                        </div>
                        
                        <div class="flex items-center gap-2 md:gap-3">
                            <span class="text-gray-600 text-sm md:text-base">Search:</span>
                            <input type="search" 
                                   id="searchInput" 
                                   class="border rounded-md px-2 py-1 md:px-3 md:py-1.5 text-sm md:text-base w-full md:w-48"
                                   value="{{ request('q') }}"
                                   placeholder="Cari dokumen...">
                        </div>
                    </div>

                    <!-- Documents List -->
                    <div id="documentsList" class="overflow-x-auto">
                        @include('beranda.dokumen-list')
                    </div>
                </div>
            </div>
        </div>
    </div>

    <!-- Preview Modal -->
    <div id="previewModal" class="fixed inset-0 z-50 hidden overflow-y-auto bg-black bg-opacity-50">
        <div class="min-h-screen px-4 text-center">
            <div class="fixed inset-0" aria-hidden="true"></div>
            <div class="inline-block align-middle w-full max-w-4xl my-8 p-4 md:p-6">
                <div class="bg-white rounded-lg text-left overflow-hidden shadow-xl">
                    <!-- Modal Header -->
                    <div class="bg-gray-100 px-4 py-3 flex justify-between items-center">
                        <h3 id="previewTitle" class="text-lg font-semibold text-gray-900 truncate"></h3>
                        <button onclick="closePreviewModal()" class="text-gray-500 hover:text-gray-700">
                            <svg class="h-6 w-6" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M6 18L18 6M6 6l12 12"></path>
                            </svg>
                        </button>
                    </div>
                    <!-- Modal Body -->
                    <div class="relative" style="height: 80vh;">
                        <iframe id="documentPreview" class="w-full h-full"></iframe>
                    </div>
                </div>
            </div>
        </div>
    </div>

    @push('scripts')
    <script>
    document.addEventListener('DOMContentLoaded', function() {
        const entriesSelect = document.getElementById('entries');
        const searchInput = document.getElementById('searchInput');
        const documentsList = document.getElementById('documentsList');
        
        function updateContent(url = null) {
            const searchValue = searchInput.value;
            const perPage = entriesSelect.value;
            
            url = url || `{{ route('dokumen') }}`;
            
            documentsList.classList.add('opacity-50');
            
            fetch(`${url}?q=${searchValue}&show=${perPage}`, {
                headers: {
                    'X-Requested-With': 'XMLHttpRequest'
                }
            })
            .then(response => response.text())
            .then(html => {
                documentsList.innerHTML = html;
                documentsList.classList.remove('opacity-50');
                
                const newUrl = new URL(window.location.href);
                newUrl.searchParams.delete('q');
                newUrl.searchParams.delete('show');
                window.history.pushState({}, '', newUrl);
            });
        }
        
        entriesSelect.addEventListener('change', () => updateContent());
        
        let timeout = null;
        searchInput.addEventListener('input', function() {
            clearTimeout(timeout);
            timeout = setTimeout(() => updateContent(), 500);
        });
        
        document.addEventListener('click', function(e) {
            const link = e.target.closest('.pagination a');
            if (link) {
                e.preventDefault();
                updateContent(link.href);
            }
        });
    });

    function previewDocument(url, title) {
        const previewModal = document.getElementById('previewModal');
        const documentPreview = document.getElementById('documentPreview');
        const previewTitle = document.getElementById('previewTitle');
        
        previewTitle.textContent = title;
        
        documentPreview.src = url;
        
        previewModal.classList.remove('hidden');
        document.body.style.overflow = 'hidden';
    }

    function closePreviewModal() {
        const previewModal = document.getElementById('previewModal');
        const documentPreview = document.getElementById('documentPreview');
        
        documentPreview.src = '';
        previewModal.classList.add('hidden');
        document.body.style.overflow = 'auto';
    }

    document.getElementById('previewModal').addEventListener('click', function(e) {
        if (e.target === this) {
            closePreviewModal();
        }
    });
    </script>
    @endpush
</x-main-layout> 