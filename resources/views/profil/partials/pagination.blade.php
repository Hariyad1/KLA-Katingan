@if ($programKerjas->total() > 0)
    <div class="flex flex-col sm:flex-row justify-between items-center gap-4">
        {{-- Informasi Jumlah Item - Pojok Kiri --}}
        <div class="text-sm text-gray-600">
            Menampilkan {{ $programKerjas->firstItem() }} hingga {{ $programKerjas->lastItem() }} dari {{ $programKerjas->total() }} program kerja/kegiatan
        </div>
        
        {{-- Tombol Pagination - Pojok Kanan --}}
        @if ($programKerjas->hasPages())
        <div class="pagination-container">
            <nav role="navigation" aria-label="Pagination Navigation" class="flex items-center">
                <div class="flex items-center space-x-2">
                    {{-- Previous Page Button --}}
                    @if ($programKerjas->onFirstPage())
                        <span class="relative inline-flex items-center px-4 py-2 text-sm font-medium text-gray-400 bg-gray-100 border border-gray-300 cursor-not-allowed rounded-md">
                            <svg class="w-5 h-5 mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 19l-7-7 7-7" />
                            </svg>
                            Previous
                        </span>
                    @else
                        <a href="{{ $programKerjas->appends(request()->except('page'))->previousPageUrl() }}" class="pagination-link prev-page-link relative inline-flex items-center px-4 py-2 text-sm font-medium text-purple-600 bg-white border border-purple-300 rounded-md hover:bg-purple-50 transition-colors duration-150">
                            <svg class="w-5 h-5 mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 19l-7-7 7-7" />
                            </svg>
                            Previous
                        </a>
                    @endif

                    {{-- Current Page / Total Pages --}}
                    <div class="flex items-center">
                        <span class="px-3 py-2 text-sm text-gray-700 bg-gray-100 border border-gray-300 rounded-md">
                            Page {{ $programKerjas->currentPage() }} of {{ $programKerjas->lastPage() }}
                        </span>
                    </div>

                    {{-- Next Page Button --}}
                    @if ($programKerjas->hasMorePages())
                        <a href="{{ $programKerjas->appends(request()->except('page'))->nextPageUrl() }}" class="pagination-link next-page-link relative inline-flex items-center px-4 py-2 text-sm font-medium text-purple-600 bg-white border border-purple-300 rounded-md hover:bg-purple-50 transition-colors duration-150">
                            Next
                            <svg class="w-5 h-5 ml-2" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 5l7 7-7 7" />
                            </svg>
                        </a>
                    @else
                        <span class="relative inline-flex items-center px-4 py-2 text-sm font-medium text-gray-400 bg-gray-100 border border-gray-300 cursor-not-allowed rounded-md">
                            Next
                            <svg class="w-5 h-5 ml-2" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 5l7 7-7 7" />
                            </svg>
                        </span>
                    @endif
                </div>
            </nav>
        </div>
        @endif
    </div>
@else
    <div class="text-sm text-gray-600">
        Tidak ada program kerja/kegiatan yang ditemukan
    </div>
@endif 