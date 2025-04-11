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
                VISI & MISI
            </h1>
            <div class="flex items-center justify-center text-white text-lg font-medium">
                <a href="{{ route('home') }}" class="hover:text-yellow-300 transition-colors">Beranda</a>
                <span class="mx-3 text-yellow-300">•</span>
                <a href="#" class="hover:text-yellow-300 transition-colors">Profil</a>
                <span class="mx-3 text-yellow-300">•</span>
                <span class="text-yellow-300">Visi & Misi</span>
            </div>
        </div>
    </div>

    <!-- Main Content -->
    <div class="py-12">
        <div class="max-w-7xl mx-auto sm:px-6 lg:px-8">
            <div class="bg-white overflow-hidden shadow-sm sm:rounded-lg">
                <div class="p-6">
                    <div class="prose max-w-none">
                        <div class="mb-12">
                            <h2 class="text-3xl font-bold text-gray-800 mb-6">Visi</h2>
                            <div class="bg-gray-50 p-6 rounded-lg">
                                <p class="text-lg text-gray-700">
                                    "Terwujudnya pemenuhan hak, partisipasi, dan pengembangan diri anak, dalam rangka mensejahterakan anak serta mewujudkan Kabupaten Katingan sebagai Kabupaten Layak Anak."
                                </p>
                            </div>
                        </div>

                        <div>
                            <h2 class="text-3xl font-bold text-gray-800 mb-6">Misi</h2>
                            <div class="space-y-4">
                                <div class="flex items-start gap-4 bg-gray-50 p-6 rounded-lg">
                                    <span class="flex-shrink-0 w-8 h-8 bg-blue-600 text-white rounded-full flex items-center justify-center font-semibold">1</span>
                                    <p class="text-lg text-gray-700">Mengembangkan Anak menjadi pribadi yang berkarakter, aktif, kreatif, serta produktif.</p>
                                </div>
                                <div class="flex items-start gap-4 bg-gray-50 p-6 rounded-lg">
                                    <span class="flex-shrink-0 w-8 h-8 bg-blue-600 text-white rounded-full flex items-center justify-center font-semibold">2</span>
                                    <p class="text-lg text-gray-700">Mempererat hubungan antar anak-anak di Kabupaten Katingan.</p>
                                </div>
                                <div class="flex items-start gap-4 bg-gray-50 p-6 rounded-lg">
                                    <span class="flex-shrink-0 w-8 h-8 bg-blue-600 text-white rounded-full flex items-center justify-center font-semibold">3</span>
                                    <p class="text-lg text-gray-700">Menjadikan Forum Anak sebagai wadah kegiatan dan sumber informasi anak-anak Kabupaten Katingan.</p>
                                </div>
                                <div class="flex items-start gap-4 bg-gray-50 p-6 rounded-lg">
                                    <span class="flex-shrink-0 w-8 h-8 bg-blue-600 text-white rounded-full flex items-center justify-center font-semibold">4</span>
                                    <p class="text-lg text-gray-700">Meminimalisir diskriminasi dan kejahatan terhadap anak di Kabupaten Katingan.</p>
                                </div>
                                <div class="flex items-start gap-4 bg-gray-50 p-6 rounded-lg">
                                    <span class="flex-shrink-0 w-8 h-8 bg-blue-600 text-white rounded-full flex items-center justify-center font-semibold">5</span>
                                    <p class="text-lg text-gray-700">Meningkatkan kesadaran anak akan kebersihan dan rasa cinta terhadap lingkungan alam dan sosial.</p>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
</x-main-layout> 