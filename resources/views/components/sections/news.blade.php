@props(['latestNews'])

<div class="w-full lg:w-2/3">
    <div class="bg-gradient-to-br from-blue-50 to-indigo-50 overflow-hidden shadow-lg sm:rounded-lg mt-8">
        <div class="p-6">
            <div class="flex justify-between items-center mb-8">
                <h2 class="text-2xl font-bold text-indigo-800 relative">
                    <span class="relative inline-block">
                        <span class="absolute -left-2 -right-2 bottom-2 h-3 bg-blue-200/60"></span>
                        <span class="relative">Berita Terbaru</span>
                    </span>
                </h2>
            </div>
            <div class="flex flex-col divide-y-4 divide-dashed divide-indigo-200 space-y-6">
                @foreach($latestNews as $item)
                <div class="flex flex-col md:flex-row gap-4 bg-gradient-to-r from-white via-blue-50 to-indigo-50 p-4 rounded-xl hover:shadow-lg transition-all duration-300 hover:from-blue-50 hover:via-indigo-50 hover:to-white {{ !$loop->first ? 'pt-10' : '' }}">
                    <div class="w-full md:w-1/3 relative overflow-hidden rounded-lg">
                        @if($item->image)
                            <div class="relative group">
                                <img src="{{ $item->image }}" 
                                    alt="{{ $item->title }}" 
                                    class="w-full h-48 object-cover rounded-lg shadow-md transition-transform duration-300 group-hover:scale-105">
                                <div class="absolute inset-0 bg-gradient-to-t from-indigo-900/30 to-blue-500/20 opacity-0 group-hover:opacity-100 transition-opacity duration-300"></div>
                            </div>
                        @else
                            <div class="w-full h-48 bg-gradient-to-br from-blue-200 to-indigo-200 rounded-lg"></div>
                        @endif
                    </div>
                    <div class="w-full md:w-2/3">
                        <h3 class="text-xl font-semibold text-indigo-700 hover:text-indigo-900">
                            <a href="{{ route('news.show', ['title' => Str::slug($item->title)]) }}">
                                {{ $item->title }}
                            </a>
                        </h3>
                        <p class="text-indigo-500 text-sm mt-2">
                            {{ \Carbon\Carbon::parse($item->created_at)->format('d F Y') }}
                        </p>
                        <p class="text-gray-600 mt-2 line-clamp-3">
                            {{ Str::limit(strip_tags($item->content), 150) }}
                        </p>
                        <a href="{{ route('news.show', ['title' => Str::slug($item->title)]) }}" 
                            class="inline-flex items-center mt-3 text-indigo-600 hover:text-indigo-800 transition-colors">
                            ... selengkapnya
                            <svg class="w-4 h-4 ml-1" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 5l7 7-7 7"></path>
                            </svg>
                        </a>
                    </div>
                </div>
                @endforeach
            </div>
        </div>
    </div>
</div> 