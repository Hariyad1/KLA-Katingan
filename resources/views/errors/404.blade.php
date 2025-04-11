<x-main-layout>
    <div class="min-h-screen flex items-center justify-center">
        <div class="max-w-2xl mx-auto text-center px-4">
            <div class="mb-8">
                <img src="{{ asset('images/404.svg') }}" 
                     alt="Page Not Found" 
                     class="w-full max-w-md mx-auto">
            </div>

            <h1 class="text-4xl md:text-6xl font-bold text-gray-800 mb-4">
                Oops! Halaman Tidak Ditemukan
            </h1>
            <p class="text-lg text-gray-600 mb-8">
                Maaf, halaman yang Anda cari tidak dapat ditemukan atau telah dipindahkan.
            </p>

            <div class="space-y-4">
                <a href="{{ route('home') }}" 
                   class="inline-flex items-center px-6 py-3 bg-blue-600 text-white font-semibold rounded-lg hover:bg-blue-700 transition-colors">
                    <svg class="w-5 h-5 mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M10 19l-7-7m0 0l7-7m-7 7h18"/>
                    </svg>
                    Kembali ke Beranda
                </a>
                <button onclick="window.history.back()" 
                        class="inline-flex items-center px-6 py-3 bg-gray-100 text-gray-700 font-semibold rounded-lg hover:bg-gray-200 transition-colors">
                    Kembali ke Halaman Sebelumnya
                </button>
            </div>

            <div class="mt-8 text-gray-600">
                <p>Butuh bantuan? 
                    <a href="{{ route('kontak') }}" class="text-blue-600 hover:underline">
                        Hubungi kami
                    </a>
                </p>
            </div>
        </div>
    </div>
</x-main-layout> 