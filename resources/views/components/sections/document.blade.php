@props(['documents' => []])

<div class="bg-gradient-to-br from-warmgray-50 to-stone-100 rounded-lg shadow-md overflow-hidden">
    <div class="p-6">
        <div class="flex justify-between items-center mb-6">
            <h2 class="text-2xl font-bold text-stone-800 relative">
                <span class="relative inline-block">
                    <span class="absolute -left-2 -right-2 bottom-2 h-3 bg-stone-200/60"></span>
                    <span class="relative">Dokumen</span>
                </span>
            </h2>
            <a href="{{ route('dokumen') }}" class="text-blue-500 hover:text-blue-600 text-sm font-medium">
                Lihat Semua →
            </a>
        </div>
        <div class="space-y-4">
            @forelse($documents as $document)
            <a href="{{ $document->path }}" 
               target="_blank"
               class="block p-4 bg-gradient-to-r from-white via-warmgray-50 to-stone-100 rounded-lg hover:from-warmgray-50 hover:via-stone-100 hover:to-white transition-all duration-300">
                <div class="flex items-center gap-4">
                    <div class="w-10 h-10 flex-shrink-0 bg-stone-100 rounded-lg flex items-center justify-center">
                        <svg class="w-6 h-6 text-stone-600" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" 
                                  d="M9 12h6m-6 4h6m2 5H7a2 2 0 01-2-2V5a2 2 0 012-2h5.586a1 1 0 01.707.293l5.414 5.414a1 1 0 01.293.707V19a2 2 0 01-2 2z"/>
                        </svg>
                    </div>
                    <div class="flex-1">
                        <h3 class="font-semibold text-gray-800 line-clamp-2">{{ $document->name }}</h3>
                        <p class="text-sm text-stone-600 mt-1">
                            {{ strtoupper(pathinfo($document->path, PATHINFO_EXTENSION)) }}
                        </p>
                    </div>
                </div>
            </a>
            @empty
            <div class="text-center py-8 text-stone-500">
                Tidak ada dokumen tersedia
            </div>
            @endforelse
        </div>
    </div>
</div>