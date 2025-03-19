@if($media->count() > 0)
    @foreach($media as $item)
        <div class="flex items-center justify-between p-4 bg-gray-50 rounded-lg hover:bg-gray-100 transition-all duration-300">
            <div class="flex items-center gap-4">
                <div class="w-12 h-12 flex-shrink-0 bg-blue-100 rounded-lg flex items-center justify-center">
                    <svg class="w-6 h-6 text-blue-600" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12h6m-6 4h6m2 5H7a2 2 0 01-2-2V5a2 2 0 012-2h5.586a1 1 0 01.707.293l5.414 5.414a1 1 0 01.293.707V19a2 2 0 01-2 2z"/>
                    </svg>
                </div>
                <div>
                    <h3 class="font-semibold text-lg text-gray-800">{{ $item->name }}</h3>
                    <div class="flex items-center gap-2 mt-1">
                        <p class="text-sm text-gray-600">
                            {{ strtoupper(pathinfo($item->path, PATHINFO_EXTENSION)) }}
                        </p>
                        <span class="text-gray-400">•</span>
                        <p class="text-sm text-gray-600">
                            {{ \Carbon\Carbon::parse($item->created_at)->format('d M Y') }}
                        </p>
                    </div>
                </div>
            </div>
            <a href="{{ $item->path }}" 
               target="_blank"
               class="inline-flex items-center px-4 py-2 bg-blue-600 hover:bg-blue-700 text-white rounded-lg transition-colors">
                <svg class="w-4 h-4 mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" 
                          d="M4 16v1a3 3 0 003 3h10a3 3 0 003-3v-1m-4-4l-4 4m0 0l-4-4m4 4V4"/>
                </svg>
                Unduh
            </a>
        </div>
    @endforeach

    <!-- Pagination Info & Controls -->
    <div class="flex justify-between items-center mt-6">
        <div class="text-gray-600">
            Showing {{ $media->firstItem() }} to {{ $media->lastItem() }} of {{ $media->total() }} entries
        </div>
        <div class="flex items-center gap-2">
            @if($media->hasPages())
                <button class="px-3 py-1 border rounded-md {{ $media->onFirstPage() ? 'bg-gray-100 text-gray-400' : 'hover:bg-gray-50' }}"
                        {{ $media->onFirstPage() ? 'disabled' : '' }}
                        onclick="updateContent('{{ $media->previousPageUrl() }}')">
                    Previous
                </button>
                
                @foreach($media->getUrlRange(1, $media->lastPage()) as $page => $url)
                    <button class="w-8 h-8 flex items-center justify-center rounded-md {{ $media->currentPage() == $page ? 'bg-blue-600 text-white' : 'hover:bg-gray-50' }}"
                            onclick="updateContent('{{ $url }}')">
                        {{ $page }}
                    </button>
                @endforeach

                <button class="px-3 py-1 border rounded-md {{ !$media->hasMorePages() ? 'bg-gray-100 text-gray-400' : 'hover:bg-gray-50' }}"
                        {{ !$media->hasMorePages() ? 'disabled' : '' }}
                        onclick="updateContent('{{ $media->nextPageUrl() }}')">
                    Next
                </button>
            @endif
        </div>
    </div>
@else
    <div class="text-center py-8 text-gray-500">
        Tidak ada dokumen tersedia
    </div>
@endif 