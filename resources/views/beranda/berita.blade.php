<x-main-layout>
    <div class="relative h-[300px] flex items-center justify-center overflow-hidden">
        <div class="absolute inset-0">
            <img src="{{ asset('images/inner-head.png') }}" alt="Header Background" class="w-full h-full object-cover">
            <div class="absolute inset-0 bg-gradient-to-b from-purple-900/70 to-purple-900/90"></div>
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
        
        <!-- Content -->
        <div class="relative z-10 text-center">
            <h1 class="text-5xl font-extrabold text-white mb-4 tracking-wide">
                BERITA TERKINI
            </h1>
            <div class="flex items-center justify-center text-white text-lg font-medium">
                <a href="{{ route('home') }}" class="hover:text-yellow-300 transition-colors">Beranda</a>
                <span class="mx-3 text-yellow-300">•</span>
                <span class="text-yellow-300">Berita</span>
            </div>
        </div>
    </div>

    <!-- Filter Categories Section -->
    <div class="bg-white shadow-md">
        <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8 py-4">
            <div class="flex flex-col sm:flex-row items-center justify-between gap-4">
                <h2 class="text-lg font-semibold text-gray-700">Filter Kategori:</h2>
                <div class="w-full sm:w-auto">
                    <select id="kategoriSelect" class="w-full sm:w-64 px-4 py-2 bg-white border border-gray-300 rounded-lg focus:ring-2 focus:ring-blue-500 focus:border-blue-500 shadow-sm">
                        <option value="{{ route('berita') }}" {{ !isset($kategori) ? 'selected' : '' }}>Semua Kategori</option>
                        @foreach($categories as $category)
                            <option value="{{ route('berita.kategori', Str::slug($category->name)) }}"
                                    {{ isset($kategori) && $kategori == Str::slug($category->name) ? 'selected' : '' }}>
                                {{ $category->name }}
                            </option>
                        @endforeach
                    </select>
                </div>
            </div>
        </div>
    </div>

    <!-- Main Content -->
    <div class="py-12 bg-gradient-to-br from-blue-50 to-indigo-50">
        <div class="max-w-7xl mx-auto sm:px-6 lg:px-8">
            <!-- News Grid -->
            <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-3 gap-6">
                @forelse($news as $item)
                    <div class="bg-white rounded-lg shadow-md overflow-hidden hover:shadow-xl transition-all duration-300">
                        @if($item->image)
                            <div class="aspect-video overflow-hidden">
                                <img src="{{ asset($item->image) }}" 
                                     alt="{{ $item->title }}"
                                     class="w-full h-full object-cover transform hover:scale-105 transition-transform duration-300"
                                     onerror="this.onerror=null; this.src='{{ asset('images/default-image.jpg') }}';">
                            </div>
                        @endif
                        
                        <div class="p-6">
                            @if($item->kategori)
                                <span class="inline-block px-3 py-1 text-xs font-semibold text-blue-600 bg-blue-50 rounded-full">
                                    {{ $item->kategori->name }}
                                </span>
                            @endif
                            
                            <h3 class="mt-3 text-xl font-semibold text-gray-900 line-clamp-2 hover:text-blue-600 transition-colors">
                                <a href="{{ route('berita.detail', ['title' => Str::slug($item->title)]) }}">
                                    {{ $item->title }}
                                </a>
                            </h3>
                            
                            <p class="mt-3 text-gray-600 text-sm line-clamp-3">
                                {{ Str::limit(strip_tags($item->content), 150) }}
                            </p>
                            
                            <div class="mt-4 flex items-center justify-between text-sm">
                                <div class="flex items-center text-gray-500">
                                    <svg class="w-4 h-4 mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" 
                                              d="M16 7a4 4 0 11-8 0 4 4 0 018 0zM12 14a7 7 0 00-7 7h14a7 7 0 00-7-7z"/>
                                    </svg>
                                    {{ $item->creator->name }}
                                </div>
                                <div class="flex items-center text-gray-500">
                                    <svg class="w-4 h-4 mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" 
                                              d="M8 7V3m8 4V3m-9 8h10M5 21h14a2 2 0 002-2V7a2 2 0 00-2-2H5a2 2 0 00-2 2v12a2 2 0 002 2z"/>
                                    </svg>
                                    {{ $item->created_at->format('d M Y') }}
                                </div>
                            </div>
                        </div>
                    </div>
                @empty
                    <div class="col-span-full">
                        <div class="text-center py-12 bg-white rounded-lg shadow">
                            <svg class="mx-auto h-12 w-12 text-gray-400" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" 
                                      d="M19 20H5a2 2 0 01-2-2V6a2 2 0 012-2h10a2 2 0 012 2v1m2 13a2 2 0 01-2-2V7m2 13a2 2 0 002-2V9.5a2.5 2.5 0 00-2.5-2.5H14"/>
                            </svg>
                            <h3 class="mt-2 text-sm font-medium text-gray-900">Tidak ada berita</h3>
                            <p class="mt-1 text-sm text-gray-500">Belum ada berita yang ditambahkan</p>
                        </div>
                    </div>
                @endforelse
            </div>

            <!-- Pagination -->
            <div class="mt-4">
                @if ($news->hasPages())
                    <nav role="navigation" aria-label="Pagination Navigation" class="flex justify-center">
                        <div class="flex items-center gap-4">
                            {{-- Previous Page Link --}}
                            @if ($news->onFirstPage())
                                <span class="px-4 py-2 text-sm font-semibold text-gray-400 bg-gray-100 rounded-lg cursor-not-allowed">
                                    <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 19l-7-7 7-7"/>
                                    </svg>
                                </span>
                            @else
                                @if(isset($kategori))
                                    <a href="{{ $news->currentPage() == 2 ? route('berita.kategori', $kategori) : route('berita.kategori.page', ['kategori' => $kategori, 'page' => $news->currentPage() - 1]) }}" 
                                       class="px-4 py-2 text-sm font-semibold text-gray-700 bg-gray-100 rounded-lg hover:bg-blue-500 hover:text-white transition-colors duration-200">
                                        <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 19l-7-7 7-7"/>
                                        </svg>
                                    </a>
                                @else
                                    <a href="{{ $news->currentPage() == 2 ? route('berita') : route('berita.page', $news->currentPage() - 1) }}" 
                                       class="px-4 py-2 text-sm font-semibold text-gray-700 bg-gray-100 rounded-lg hover:bg-blue-500 hover:text-white transition-colors duration-200">
                                        <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 19l-7-7 7-7"/>
                                        </svg>
                                    </a>
                                @endif
                            @endif

                            {{-- Page Information --}}
                            <span class="text-sm text-gray-700">
                                Halaman {{ $news->currentPage() }} dari {{ $news->lastPage() }}
                            </span>

                            {{-- Next Page Link --}}
                            @if ($news->hasMorePages())
                                @if(isset($kategori))
                                    <a href="{{ route('berita.kategori.page', ['kategori' => $kategori, 'page' => $news->currentPage() + 1]) }}" 
                                       class="px-4 py-2 text-sm font-semibold text-gray-700 bg-gray-100 rounded-lg hover:bg-blue-500 hover:text-white transition-colors duration-200">
                                        <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 5l7 7-7 7"/>
                                        </svg>
                                    </a>
                                @else
                                    <a href="{{ route('berita.page', $news->currentPage() + 1) }}" 
                                       class="px-4 py-2 text-sm font-semibold text-gray-700 bg-gray-100 rounded-lg hover:bg-blue-500 hover:text-white transition-colors duration-200">
                                        <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 5l7 7-7 7"/>
                                        </svg>
                                    </a>
                                @endif
                            @else
                                <span class="px-4 py-2 text-sm font-semibold text-gray-400 bg-gray-100 rounded-lg cursor-not-allowed">
                                    <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 5l7 7-7 7"/>
                                    </svg>
                                </span>
                            @endif
                        </div>
                    </nav>
                @endif
            </div>
        </div>
    </div>

    @push('styles')
    <style>
        @keyframes float {
            0% { transform: translateY(0px); }
            50% { transform: translateY(-10px); }
            100% { transform: translateY(0px); }
        }

        .floating {
            animation: float 3s ease-in-out infinite;
        }
    </style>
    @endpush

    @push('scripts')
    <script>
    document.addEventListener('DOMContentLoaded', function() {
        const kategoriSelect = document.getElementById('kategoriSelect');
        
        if (kategoriSelect) {
            kategoriSelect.addEventListener('change', function() {
                window.location.href = this.value;
            });
        }
    });
    </script>
    @endpush
</x-main-layout> 