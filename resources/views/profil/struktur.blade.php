<x-main-layout>
    <div class="py-12">
        <div class="max-w-7xl mx-auto sm:px-6 lg:px-8">
            <div class="bg-white overflow-hidden shadow-sm sm:rounded-lg">
                <div class="p-6">
                    <h1 class="text-3xl font-bold text-gray-800 mb-6">Struktur Organisasi</h1>
                    
                    <div class="prose max-w-none">
                        <img src="{{ asset('images/struktur-organisasi.png') }}" alt="Struktur Organisasi" class="w-full mb-6">
                        
                        <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
                            <!-- Daftar jabatan dan nama pejabat -->
                            <div class="border rounded-lg p-4">
                                <h3 class="text-xl font-semibold mb-4">Pimpinan</h3>
                                <ul class="space-y-2">
                                    <li class="flex justify-between">
                                        <span class="font-medium">Kepala Dinas</span>
                                        <span>Nama Pejabat</span>
                                    </li>
                                    <!-- Tambahkan daftar pejabat lainnya -->
                                </ul>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
</x-main-layout> 