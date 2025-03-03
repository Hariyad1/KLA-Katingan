<x-main-layout>
    <div class="py-12">
        <div class="max-w-7xl mx-auto sm:px-6 lg:px-8">
            <div class="bg-white overflow-hidden shadow-sm sm:rounded-lg">
                <div class="p-6">
                    <h1 class="text-2xl font-bold mb-6">Pemenuhan Hak Anak</h1>
                    
                    <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
                        <!-- Klaster 1 -->
                        <a href="{{ route('pemenuhan-hak-anak.klaster1') }}" 
                           class="block p-6 bg-white border rounded-lg shadow hover:bg-gray-50 transition">
                            <h2 class="text-xl font-semibold mb-2">Klaster 1</h2>
                            <p class="text-gray-600">Hak Sipil dan Kebebasan</p>
                        </a>

                        <!-- Klaster 2 -->
                        <a href="{{ route('pemenuhan-hak-anak.klaster2') }}"
                           class="block p-6 bg-white border rounded-lg shadow hover:bg-gray-50 transition">
                            <h2 class="text-xl font-semibold mb-2">Klaster 2</h2>
                            <p class="text-gray-600">Lingkungan Keluarga dan Pengasuhan Alternatif</p>
                        </a>

                        <!-- Klaster 3 -->
                        <a href="{{ route('pemenuhan-hak-anak.klaster3') }}"
                           class="block p-6 bg-white border rounded-lg shadow hover:bg-gray-50 transition">
                            <h2 class="text-xl font-semibold mb-2">Klaster 3</h2>
                            <p class="text-gray-600">Kesehatan Dasar dan Kesejahteraan</p>
                        </a>

                        <!-- Klaster 4 -->
                        <a href="{{ route('pemenuhan-hak-anak.klaster4') }}"
                           class="block p-6 bg-white border rounded-lg shadow hover:bg-gray-50 transition">
                            <h2 class="text-xl font-semibold mb-2">Klaster 4</h2>
                            <p class="text-gray-600">Pendidikan, Pemanfaatan Waktu Luang, dan Kegiatan Budaya</p>
                        </a>
                    </div>
                </div>
            </div>
        </div>
    </div>
</x-main-layout> 