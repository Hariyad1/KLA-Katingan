<x-main-layout>
    <!-- Header Section dengan Background Image -->
    <div class="relative h-[300px] flex items-center justify-center overflow-hidden">
        <!-- Background Image dengan Overlay -->
        <div class="absolute inset-0">
            <img src="{{ asset('images/inner-head.png') }}" alt="Header Background" class="w-full h-full object-cover">
            <div class="absolute inset-0 bg-gradient-to-b from-purple-900/50 to-purple-900/70"></div>
        </div>
        
        <!-- Decorative Elements -->
        <div class="absolute inset-0">
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
        <div class="relative z-10 text-center">
            <h1 class="text-5xl font-extrabold text-white mb-4 tracking-wide">
                KESEHATAN DASAR DAN KESEJAHTERAAN
            </h1>
            <div class="flex items-center justify-center text-white text-lg font-medium">
                <a href="{{ route('home') }}" class="hover:text-yellow-300 transition-colors">Beranda</a>
                <span class="mx-3 text-yellow-300">•</span>
                <a href="#" class="hover:text-yellow-300 transition-colors">Pemenuhan Hak Anak</a>
                <span class="mx-3 text-yellow-300">•</span>
                <span class="text-yellow-300">Klaster 3</span>
            </div>
        </div>
    </div>

    <!-- Main Content -->
    <div class="py-12">
        <div class="max-w-7xl mx-auto sm:px-6 lg:px-8">
            <!-- Konten Statis -->
            <div class="bg-white overflow-hidden shadow-sm sm:rounded-lg mb-8">
                <div class="p-6">
                    <div class="prose max-w-none">
                        <!-- Overview Section -->
                        <div class="bg-gray-50 rounded-lg p-8">
                            <div class="flex items-center gap-4 mb-6">
                                <div class="w-12 h-12 bg-purple-100 rounded-lg flex items-center justify-center">
                                    <svg class="w-6 h-6 text-purple-600" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" 
                                              d="M13 16h-1v-4h-1m1-4h.01M21 12a9 9 0 11-18 0 9 9 0 0118 0z"/>
                                    </svg>
                                </div>
                                <h2 class="text-2xl font-bold text-gray-800">Kesehatan Dasar dan Kesejahteraan</h2>
                            </div>
                            <p class="text-gray-700">Lorem ipsum dolor sit amet, consectetur adipiscing elit. Sed do eiusmod tempor incididunt ut labore et dolore magna aliqua. Ut enim ad minim veniam, quis nostrud exercitation ullamco laboris nisi ut aliquip ex ea commodo consequat.</p>
                        </div>
                    </div>
                </div>
            </div>

            <!-- Konten Dinamis -->
            @if(isset($settings) && $settings->isNotEmpty())
                @foreach($settings as $setting)
                    <div class="bg-white overflow-hidden shadow-sm sm:rounded-lg mb-8">
                        <div class="p-6">
                            <div class="prose max-w-none">
                                <div class="bg-gray-50 rounded-lg p-8">
                                    @if($setting->image)
                                        <div class="mb-6">
                                            <img src="{{ $setting->image }}" 
                                                 alt="{{ $setting->name }}" 
                                                 class="w-full h-auto rounded-lg object-cover">
                                        </div>
                                    @endif

                                    <div class="bg-white rounded-lg p-6 shadow-sm">
                                        <div class="space-y-4">
                                            {!! nl2br($setting->content) !!}
                                        </div>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>
                @endforeach
            @endif
        </div>
    </div>
</x-main-layout> 