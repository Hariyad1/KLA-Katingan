<div class="overflow-x-auto">
    <div class="inline-block min-w-full">
        <div id="dokumen-list-container">
            @if($media->count() > 0)
                @foreach($media as $item)
                    <div class="flex flex-col sm:flex-row items-start sm:items-center justify-between p-4 bg-gray-50 rounded-lg hover:bg-gray-100 transition-all duration-300 gap-4">
                        <div class="flex items-center gap-4">
                            <div class="w-12 h-12 flex-shrink-0 rounded-lg flex items-center justify-center
                                @php
                                    $extension = strtolower(pathinfo($item->path, PATHINFO_EXTENSION));
                                    $bgColor = 'bg-blue-100';
                                    $textColor = 'text-blue-600';
                                    
                                    switch($extension) {
                                        case 'pdf':
                                            $bgColor = 'bg-red-100';
                                            $textColor = 'text-red-600';
                                            break;
                                        case 'doc':
                                        case 'docx':
                                            $bgColor = 'bg-blue-100';
                                            $textColor = 'text-blue-600';
                                            break;
                                        case 'xls':
                                        case 'xlsx':
                                            $bgColor = 'bg-green-100';
                                            $textColor = 'text-green-600';
                                            break;
                                        case 'ppt':
                                        case 'pptx':
                                            $bgColor = 'bg-orange-100';
                                            $textColor = 'text-orange-600';
                                            break;
                                        case 'jpg':
                                        case 'jpeg':
                                        case 'png':
                                        case 'gif':
                                            $bgColor = 'bg-purple-100';
                                            $textColor = 'text-purple-600';
                                            break;
                                        case 'zip':
                                        case 'rar':
                                            $bgColor = 'bg-yellow-100';
                                            $textColor = 'text-yellow-600';
                                            break;
                                    }
                                @endphp
                                {{ $bgColor }} {{ $textColor }}">
                                @php
                                    $extension = strtolower(pathinfo($item->path, PATHINFO_EXTENSION));
                                    $icon = '<path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12h6m-6 4h6m2 5H7a2 2 0 01-2-2V5a2 2 0 012-2h5.586a1 1 0 01.707.293l5.414 5.414a1 1 0 01.293.707V19a2 2 0 01-2 2z"/>';
                                    
                                    switch($extension) {
                                        case 'pdf':
                                            $icon = '<path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M7 21h10a2 2 0 002-2V9.414a1 1 0 00-.293-.707l-5.414-5.414A1 1 0 0012.586 3H7a2 2 0 00-2 2v14a2 2 0 002 2z"/><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M10 9h4m-4 3h2m-2 3h4"/>';
                                            break;
                                        case 'doc':
                                        case 'docx':
                                            $icon = '<path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12h6m-6 4h6m2 5H7a2 2 0 01-2-2V5a2 2 0 012-2h5.586a1 1 0 01.707.293l5.414 5.414a1 1 0 01.293.707V19a2 2 0 01-2 2z"/>';
                                            break;
                                        case 'xls':
                                        case 'xlsx':
                                            $icon = '<path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M7 21h10a2 2 0 002-2V9.414a1 1 0 00-.293-.707l-5.414-5.414A1 1 0 0012.586 3H7a2 2 0 00-2 2v14a2 2 0 002 2z"/><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M10 9h1m-1 3h4m-4 3h2"/>';
                                            break;
                                        case 'ppt':
                                        case 'pptx':
                                            $icon = '<path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M7 21h10a2 2 0 002-2V9.414a1 1 0 00-.293-.707l-5.414-5.414A1 1 0 0012.586 3H7a2 2 0 00-2 2v14a2 2 0 002 2z"/><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 8h2m0 0v8"/>';
                                            break;
                                        case 'jpg':
                                        case 'jpeg':
                                        case 'png':
                                        case 'gif':
                                            $icon = '<path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M4 16l4.586-4.586a2 2 0 012.828 0L16 16m-2-2l1.586-1.586a2 2 0 012.828 0L20 14m-6-6h.01M6 20h12a2 2 0 002-2V6a2 2 0 00-2-2H6a2 2 0 00-2 2v12a2 2 0 002 2z"/>';
                                            break;
                                        case 'zip':
                                        case 'rar':
                                            $icon = '<path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M5 8h14M5 8a2 2 0 110-4h14a2 2 0 110 4M5 8v10a2 2 0 002 2h10a2 2 0 002-2V8m-9 4h4"/>';
                                            break;
                                    }
                                @endphp
                                <svg class="w-6 h-6" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                    {!! $icon !!}
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
                        <div class="flex flex-col sm:flex-row items-start sm:items-center gap-2 w-full sm:w-auto">
                            <!-- Preview Button -->
                            <button type="button"
                                    onclick="window.previewDocument('{{ route('dokumen.preview', $item->id) }}', '{{ $item->name }}')" 
                                    class="inline-flex items-center justify-center px-4 py-2 bg-gray-600 hover:bg-gray-700 text-white rounded-lg transition-colors w-full sm:w-auto">
                                <svg class="w-4 h-4 mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" 
                                          d="M15 12a3 3 0 11-6 0 3 3 0 016 0z"/>
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" 
                                          d="M2.458 12C3.732 7.943 7.523 5 12 5c4.478 0 8.268 2.943 9.542 7-1.274 4.057-5.064 7-9.542 7-4.477 0-8.268-2.943-9.542-7z"/>
                                </svg>
                                Preview
                            </button>
                            <!-- Download Button -->
                            <a href="{{ $item->path }}" 
                               download="{{ $item->name }}.{{ pathinfo($item->file, PATHINFO_EXTENSION) }}"
                               class="inline-flex items-center justify-center px-4 py-2 bg-blue-600 hover:bg-blue-700 text-white rounded-lg transition-colors w-full sm:w-auto">
                                <svg class="w-4 h-4 mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" 
                                          d="M4 16v1a3 3 0 003 3h10a3 3 0 003-3v-1m-4-4l-4 4m0 0l-4-4m4 4V4"/>
                                </svg>
                                Unduh
                            </a>
                        </div>
                    </div>
                @endforeach

                <!-- Pagination Info & Controls -->
                <div class="flex justify-between items-center mt-6">
                    <div class="text-gray-600">
                        Showing {{ $media->firstItem() }} to {{ $media->lastItem() }} of {{ $media->total() }} entries
                    </div>
                    <div class="flex items-center gap-2">
                        @if($media->hasPages())
                            <!-- Previous Button -->
                            <button type="button"
                                    class="px-3 py-1 border rounded-md {{ $media->onFirstPage() ? 'bg-gray-100 text-gray-400' : 'hover:bg-gray-50' }}"
                                    onclick="fetchPage('{{ $media->previousPageUrl() }}')"
                                    {{ $media->onFirstPage() ? 'disabled' : '' }}>
                                Previous
                            </button>
                            
                            <!-- Page Numbers -->
                            @for ($i = 1; $i <= $media->lastPage(); $i++)
                                <button type="button"
                                        class="w-8 h-8 flex items-center justify-center rounded-md {{ $media->currentPage() == $i ? 'bg-blue-600 text-white' : 'hover:bg-gray-50' }}"
                                        onclick="fetchPage('{{ $media->url($i) }}')">
                                    {{ $i }}
                                </button>
                            @endfor

                            <!-- Next Button -->
                            <button type="button"
                                    class="px-3 py-1 border rounded-md {{ !$media->hasMorePages() ? 'bg-gray-100 text-gray-400' : 'hover:bg-gray-50' }}"
                                    onclick="fetchPage('{{ $media->nextPageUrl() }}')"
                                    {{ !$media->hasMorePages() ? 'disabled' : '' }}>
                                Next
                            </button>
                        @endif
                    </div>
                </div>

                <!-- Preview Modal -->
                <div id="previewModal" class="fixed inset-0 bg-black bg-opacity-50 z-50 hidden flex items-center justify-center">
                    <div class="bg-white w-full max-w-5xl h-[80vh] rounded-lg shadow-xl flex flex-col">
                        <div class="p-4 border-b flex justify-between items-center">
                            <h3 id="previewTitle" class="text-xl font-semibold text-gray-800">Preview Dokumen</h3>
                            <button onclick="closePreviewModal()" class="text-gray-500 hover:text-gray-700">
                                <svg class="w-6 h-6" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M6 18L18 6M6 6l12 12"/>
                                </svg>
                            </button>
                        </div>
                        <div class="flex-1 p-4 relative">
                            <div id="previewLoading" class="absolute inset-0 flex items-center justify-center bg-white">
                                <div class="animate-spin rounded-full h-12 w-12 border-4 border-blue-500 border-t-transparent"></div>
                            </div>
                            <iframe id="documentPreview" 
                                    class="w-full h-full border-0" 
                                    src=""
                                    onload="document.getElementById('previewLoading').style.display = 'none'">
                            </iframe>
                        </div>
                    </div>
                </div>
            @else
                <div class="text-center py-8 text-gray-500">
                    Tidak ada dokumen tersedia
                </div>
            @endif
        </div>
    </div>
</div>

<script>
function hidePreviewLoading() {
    document.getElementById('previewLoading').style.display = 'none';
}

function fetchPage(url) {
    // console.log('Fetching URL:', url);
    
    fetch(url, {
        headers: {
            'X-Requested-With': 'XMLHttpRequest'
        }
    })
    .then(response => {
        // console.log('Response status:', response.status);
        return response.text();
    })
    .then(html => {
        // console.log('Received HTML');
        const container = document.getElementById('dokumen-list-container');
        if (container) {
            container.innerHTML = html;
        } else {
            console.error('Container not found: dokumen-list-container');
        }
    })
    .catch(error => {
        console.error('Error fetching page:', error);
    });
}

function previewDocument(url, title) {
    const modal = document.getElementById('previewModal');
    const previewFrame = document.getElementById('documentPreview');
    const previewTitle = document.getElementById('previewTitle');
    const previewLoading = document.getElementById('previewLoading');
    
    if (modal && previewFrame && previewTitle && previewLoading) {
        previewTitle.textContent = title;
        previewLoading.style.display = 'flex';
        previewFrame.src = url;
        modal.classList.remove('hidden');
    }
}

function closePreviewModal() {
    const modal = document.getElementById('previewModal');
    const previewFrame = document.getElementById('documentPreview');
    
    if (modal && previewFrame) {
        modal.classList.add('hidden');
        previewFrame.src = '';
    }
}

document.addEventListener('DOMContentLoaded', function() {
    // console.log('DOM fully loaded');
});
</script> 