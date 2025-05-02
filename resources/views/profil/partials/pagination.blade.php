@if ($programKerjas->total() > 0)
    <div class="flex justify-between items-center">
        {{-- Informasi Jumlah Item - Pojok Kiri --}}
        <div class="text-sm text-gray-600">
            Menampilkan {{ $programKerjas->firstItem() }} hingga {{ $programKerjas->lastItem() }} dari {{ $programKerjas->total() }} program kerja
        </div>
        
        {{-- Tombol Pagination - Pojok Kanan --}}
        @if ($programKerjas->hasPages())
        <div class="pagination-container">
            <nav role="navigation" aria-label="Pagination Navigation" class="flex items-center">
                {{-- Mobile Pagination --}}
                <div class="flex sm:hidden">
                    @if ($programKerjas->onFirstPage())
                        <span class="relative inline-flex items-center px-4 py-2 text-sm font-medium text-gray-500 bg-white border border-gray-300 cursor-default rounded-md">
                            &laquo;
                        </span>
                    @else
                        <a href="{{ $programKerjas->previousPageUrl() }}" class="pagination-link prev-page-link relative inline-flex items-center px-4 py-2 text-sm font-medium text-gray-700 bg-white border border-gray-300 rounded-md hover:text-blue-600">
                            &laquo;
                        </a>
                    @endif

                    <span class="mx-2 text-sm text-gray-700">
                        {{ $programKerjas->currentPage() }} / {{ $programKerjas->lastPage() }}
                    </span>

                    @if ($programKerjas->hasMorePages())
                        <a href="{{ $programKerjas->nextPageUrl() }}" class="pagination-link next-page-link relative inline-flex items-center px-4 py-2 text-sm font-medium text-gray-700 bg-white border border-gray-300 rounded-md hover:text-blue-600">
                            &raquo;
                        </a>
                    @else
                        <span class="relative inline-flex items-center px-4 py-2 text-sm font-medium text-gray-500 bg-white border border-gray-300 cursor-default rounded-md">
                            &raquo;
                        </span>
                    @endif
                </div>

                {{-- Desktop Pagination --}}
                <div class="hidden sm:inline-flex">
                    <span class="relative z-0 inline-flex rounded-md shadow-sm">
                        {{-- Previous Page --}}
                        @if ($programKerjas->onFirstPage())
                            <span aria-disabled="true" aria-label="Sebelumnya">
                                <span class="relative inline-flex items-center px-2 py-2 text-sm font-medium text-gray-500 bg-white border border-gray-300 cursor-default rounded-l-md" aria-hidden="true">
                                    <svg class="w-5 h-5" fill="currentColor" viewBox="0 0 20 20">
                                        <path fill-rule="evenodd" d="M12.707 5.293a1 1 0 010 1.414L9.414 10l3.293 3.293a1 1 0 01-1.414 1.414l-4-4a1 1 0 010-1.414l4-4a1 1 0 011.414 0z" clip-rule="evenodd" />
                                    </svg>
                                </span>
                            </span>
                        @else
                            <a href="{{ $programKerjas->previousPageUrl() }}" rel="prev" class="pagination-link prev-page-link relative inline-flex items-center px-2 py-2 text-sm font-medium text-gray-500 bg-white border border-gray-300 rounded-l-md hover:text-blue-600" aria-label="Sebelumnya">
                                <svg class="w-5 h-5" fill="currentColor" viewBox="0 0 20 20">
                                    <path fill-rule="evenodd" d="M12.707 5.293a1 1 0 010 1.414L9.414 10l3.293 3.293a1 1 0 01-1.414 1.414l-4-4a1 1 0 010-1.414l4-4a1 1 0 011.414 0z" clip-rule="evenodd" />
                                </svg>
                            </a>
                        @endif

                        {{-- Pagination Elements --}}
                        @foreach ($programKerjas->getUrlRange(1, $programKerjas->lastPage()) as $page => $url)
                            @if ($page == $programKerjas->currentPage())
                                <span aria-current="page">
                                    <span class="relative inline-flex items-center px-4 py-2 -ml-px text-sm font-medium text-blue-600 bg-blue-50 border border-gray-300">
                                        {{ $page }}
                                    </span>
                                </span>
                            @else
                                <a href="{{ $url }}" class="pagination-link page-{{ $page }} relative inline-flex items-center px-4 py-2 -ml-px text-sm font-medium text-gray-700 bg-white border border-gray-300 hover:text-blue-600">
                                    {{ $page }}
                                </a>
                            @endif
                        @endforeach

                        {{-- Next Page --}}
                        @if ($programKerjas->hasMorePages())
                            <a href="{{ $programKerjas->nextPageUrl() }}" rel="next" class="pagination-link next-page-link relative inline-flex items-center px-2 py-2 -ml-px text-sm font-medium text-gray-500 bg-white border border-gray-300 rounded-r-md hover:text-blue-600" aria-label="Selanjutnya">
                                <svg class="w-5 h-5" fill="currentColor" viewBox="0 0 20 20">
                                    <path fill-rule="evenodd" d="M7.293 14.707a1 1 0 010-1.414L10.586 10 7.293 6.707a1 1 0 011.414-1.414l4 4a1 1 0 010 1.414l-4 4a1 1 0 01-1.414 0z" clip-rule="evenodd" />
                                </svg>
                            </a>
                        @else
                            <span aria-disabled="true" aria-label="Selanjutnya">
                                <span class="relative inline-flex items-center px-2 py-2 -ml-px text-sm font-medium text-gray-500 bg-white border border-gray-300 cursor-default rounded-r-md" aria-hidden="true">
                                    <svg class="w-5 h-5" fill="currentColor" viewBox="0 0 20 20">
                                        <path fill-rule="evenodd" d="M7.293 14.707a1 1 0 010-1.414L10.586 10 7.293 6.707a1 1 0 011.414-1.414l4 4a1 1 0 010 1.414l-4 4a1 1 0 01-1.414 0z" clip-rule="evenodd" />
                                    </svg>
                                </span>
                            </span>
                        @endif
                    </span>
                </div>
            </nav>
        </div>
        @endif
    </div>
@else
    <div class="text-sm text-gray-600">
        Tidak ada program kerja yang ditemukan
    </div>
@endif 