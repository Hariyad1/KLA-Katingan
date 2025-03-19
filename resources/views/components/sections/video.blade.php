@props(['videoSetting' => null])

<div class="bg-gradient-to-br from-amber-50 to-orange-50 rounded-lg shadow-md overflow-hidden">
    <div class="p-6">
        <div class="flex justify-between items-center mb-6">
            <h2 class="text-2xl font-bold text-amber-800 relative">
                <span class="relative inline-block">
                    <span class="absolute -left-2 -right-2 bottom-2 h-3 bg-amber-200/60"></span>
                    <span class="relative">Video</span>
                </span>
            </h2>
            <a href="{{ route('video') }}" class="text-blue-500 hover:text-blue-600 text-sm font-medium">
                Lihat Semua →
            </a>
        </div>
        <div class="aspect-video rounded-lg overflow-hidden bg-black">
            @if($videoSetting && $videoSetting->url)
                <iframe 
                    class="w-full h-full"
                    src="{{ $videoSetting->embed_url }}"
                    title="{{ $videoSetting->name ?? 'Video Galeri' }}"
                    frameborder="0"
                    allow="accelerometer; autoplay; clipboard-write; encrypted-media; gyroscope; picture-in-picture"
                    allowfullscreen>
                </iframe>
            @else
                <div class="flex items-center justify-center h-full bg-gradient-to-br from-amber-100 to-orange-100 text-amber-500">
                    <div class="text-center p-6">
                        <svg class="mx-auto h-12 w-12 text-gray-400" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 10l4.553-2.276A1 1 0 0121 8.618v6.764a1 1 0 01-1.447.894L15 14M5 18h8a2 2 0 002-2V8a2 2 0 00-2-2H5a2 2 0 00-2 2v8a2 2 0 002 2z"></path>
                        </svg>
                        <h3 class="mt-2 text-sm font-medium text-gray-900">Tidak ada video</h3>
                        <p class="mt-1 text-sm text-gray-500">Video belum tersedia</p>
                    </div>
                </div>
            @endif
        </div>
    </div>
</div>