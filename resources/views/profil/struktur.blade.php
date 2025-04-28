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
                STRUKTUR ORGANISASI
            </h1>
            <div class="flex items-center justify-center text-white text-lg font-medium">
                <a href="{{ route('home') }}" class="hover:text-yellow-300 transition-colors">Beranda</a>
                <span class="mx-3 text-yellow-300">•</span>
                <a href="#" class="hover:text-yellow-300 transition-colors">Profil</a>
                <span class="mx-3 text-yellow-300">•</span>
                <span class="text-yellow-300">Struktur Organisasi</span>
            </div>
        </div>
    </div>

    <!-- Main Content -->
    <div class="py-12">
        <div class="max-w-7xl mx-auto sm:px-6 lg:px-8">
            <div class="bg-white overflow-hidden shadow-sm sm:rounded-lg">
                <div class="p-6">
                    <!-- Struktur Organisasi dengan Hierarki -->
                    <div class="flex flex-col items-center space-y-8">
                        <!-- Level 1: Kepala Dinas (Paling Atas) -->
                        <div class="flex justify-center">
                            <div class="border border-blue-300 rounded-lg overflow-hidden shadow-md relative bg-white text-center w-64 min-w-[16rem] max-w-[16rem]">
                                <div class="absolute top-0 right-0">
                                    <div class="bg-blue-500 w-7 h-7 flex items-center justify-center rounded-bl-lg">
                                        <img src="{{ asset('images/logo-mini.png') }}" alt="Logo" class="w-5 h-5 object-contain">
                                    </div>
                                </div>
                                <div class="p-3">
                                    <img src="{{ asset('images/default-profile.jpg') }}" alt="Profil" class="w-full h-48 object-cover object-center">
                                    
                                    <div class="mt-4 font-semibold text-blue-800 text-lg">NAMA LENGKAP</div>
                                    <div class="text-sm font-medium text-gray-600">Jabatan</div>
                                    <div class="text-xs text-gray-500 mt-1">NIP. 1234567890</div>
                                </div>
                            </div>
                        </div>

                        <!-- Connector Line -->
                        <div class="h-8 w-1 bg-blue-500"></div>

                        <!-- Level 2: Sekretaris di kanan -->
                        <div class="w-full">
                            <div class="grid grid-cols-1 md:grid-cols-5 gap-6 px-4">
                                <!-- Placeholder kosong di kiri pada tampilan desktop -->
                                <div class="hidden md:block md:col-span-3"></div>
                                
                                <!-- Sekretaris di kanan (tapi tidak terlalu pojok) -->
                                <div class="justify-self-center md:col-span-2 md:justify-self-start md:pl-4">
                                    <div class="border border-blue-300 rounded-lg overflow-hidden shadow-md relative bg-white text-center w-64 min-w-[16rem] max-w-[16rem]">
                                        <div class="absolute top-0 right-0">
                                            <div class="bg-blue-500 w-7 h-7 flex items-center justify-center rounded-bl-lg">
                                                <img src="{{ asset('images/logo-mini.png') }}" alt="Logo" class="w-5 h-5 object-contain">
                                            </div>
                                        </div>
                                        <div class="p-3">
                                            <img src="{{ asset('images/default-profile.jpg') }}" alt="Profil" class="w-full h-48 object-cover object-center">
                                            
                                            <div class="mt-4 font-semibold text-blue-800 text-lg">NAMA LENGKAP</div>
                                            <div class="text-sm font-medium text-gray-600">Jabatan</div>
                                            <div class="text-xs text-gray-500 mt-1">NIP. 1234567890</div>
                                        </div>
                                    </div>
                                </div>
                            </div>
                        </div>

                        <!-- Connector Line -->
                        <div class="h-8 w-1 bg-blue-500"></div>

                        <!-- Level 3: Kasubag (3 Posisi) -->
                        <div class="w-full">
                            <div class="text-center mb-4">
                                <h3 class="text-lg font-semibold text-gray-700">Kepala Jabatan</h3>
                            </div>
                            <div class="grid grid-cols-1 sm:grid-cols-2 lg:grid-cols-3 gap-6 justify-items-center">
                                <div class="border border-blue-300 rounded-lg overflow-hidden shadow-md relative bg-white text-center w-64 min-w-[16rem] max-w-[16rem]">
                                    <div class="absolute top-0 right-0">
                                        <div class="bg-blue-500 w-7 h-7 flex items-center justify-center rounded-bl-lg">
                                            <img src="{{ asset('images/logo-mini.png') }}" alt="Logo" class="w-5 h-5 object-contain">
                                        </div>
                                    </div>
                                    <div class="p-3">
                                        <img src="{{ asset('images/default-profile.jpg') }}" alt="Profil" class="w-full h-48 object-cover object-center">
                                        
                                        <div class="mt-4 font-semibold text-blue-800 text-lg">NAMA LENGKAP</div>
                                        <div class="text-sm font-medium text-gray-600">Jabatan</div>
                                        <div class="text-xs text-gray-500 mt-1">NIP. 1234567890</div>
                                    </div>
                                </div>

                                <div class="border border-blue-300 rounded-lg overflow-hidden shadow-md relative bg-white text-center w-64 min-w-[16rem] max-w-[16rem]">
                                    <div class="absolute top-0 right-0">
                                        <div class="bg-blue-500 w-7 h-7 flex items-center justify-center rounded-bl-lg">
                                            <img src="{{ asset('images/logo-mini.png') }}" alt="Logo" class="w-5 h-5 object-contain">
                                        </div>
                                    </div>
                                    <div class="p-3">
                                        <img src="{{ asset('images/default-profile.jpg') }}" alt="Profil" class="w-full h-48 object-cover object-center">
                                        
                                        <div class="mt-4 font-semibold text-blue-800 text-lg">NAMA LENGKAP</div>
                                        <div class="text-sm font-medium text-gray-600">Jabatan</div>
                                        <div class="text-xs text-gray-500 mt-1">NIP. 1234567890</div>
                                    </div>
                                </div>

                                <div class="border border-blue-300 rounded-lg overflow-hidden shadow-md relative bg-white text-center w-64 min-w-[16rem] max-w-[16rem]">
                                    <div class="absolute top-0 right-0">
                                        <div class="bg-blue-500 w-7 h-7 flex items-center justify-center rounded-bl-lg">
                                            <img src="{{ asset('images/logo-mini.png') }}" alt="Logo" class="w-5 h-5 object-contain">
                                        </div>
                                    </div>
                                    <div class="p-3">
                                        <img src="{{ asset('images/default-profile.jpg') }}" alt="Profil" class="w-full h-48 object-cover object-center">
                                        
                                        <div class="mt-4 font-semibold text-blue-800 text-lg">NAMA LENGKAP</div>
                                        <div class="text-sm font-medium text-gray-600">Jabatan</div>
                                        <div class="text-xs text-gray-500 mt-1">NIP. 1234567890</div>
                                    </div>
                                </div>
                            </div>
                        </div>

                        <!-- Connector Line -->
                        <div class="h-8 w-1 bg-blue-500"></div>

                        <!-- Level 4: Kabid (4 posisi) -->
                        <div class="w-full">
                            <div class="text-center mb-4">
                                <h3 class="text-lg font-semibold text-gray-700">Kepala Jabatan</h3>
                            </div>
                            <div class="grid grid-cols-1 sm:grid-cols-2 lg:grid-cols-3 xl:grid-cols-4 gap-8 justify-items-center px-4">
                                <div class="border border-blue-300 rounded-lg overflow-hidden shadow-md relative bg-white text-center w-64 min-w-[16rem] max-w-[16rem]">
                                    <div class="absolute top-0 right-0">
                                        <div class="bg-blue-500 w-7 h-7 flex items-center justify-center rounded-bl-lg">
                                            <img src="{{ asset('images/logo-mini.png') }}" alt="Logo" class="w-5 h-5 object-contain">
                                        </div>
                                    </div>
                                    <div class="p-3">
                                        <img src="{{ asset('images/default-profile.jpg') }}" alt="Profil" class="w-full h-48 object-cover object-center">
                                        
                                        <div class="mt-4 font-semibold text-blue-800 text-lg">NAMA LENGKAP</div>
                                        <div class="text-sm font-medium text-gray-600">Jabatan</div>
                                        <div class="text-xs text-gray-500 mt-1">NIP. 1234567890</div>
                                    </div>
                                </div>

                                <div class="border border-blue-300 rounded-lg overflow-hidden shadow-md relative bg-white text-center w-64 min-w-[16rem] max-w-[16rem]">
                                    <div class="absolute top-0 right-0">
                                        <div class="bg-blue-500 w-7 h-7 flex items-center justify-center rounded-bl-lg">
                                            <img src="{{ asset('images/logo-mini.png') }}" alt="Logo" class="w-5 h-5 object-contain">
                                        </div>
                                    </div>
                                    <div class="p-3">
                                        <img src="{{ asset('images/default-profile.jpg') }}" alt="Profil" class="w-full h-48 object-cover object-center">
                                        
                                        <div class="mt-4 font-semibold text-blue-800 text-lg">NAMA LENGKAP</div>
                                        <div class="text-sm font-medium text-gray-600">Jabatan</div>
                                        <div class="text-xs text-gray-500 mt-1">NIP. 1234567890</div>
                                    </div>
                                </div>

                                <div class="border border-blue-300 rounded-lg overflow-hidden shadow-md relative bg-white text-center w-64 min-w-[16rem] max-w-[16rem]">
                                    <div class="absolute top-0 right-0">
                                        <div class="bg-blue-500 w-7 h-7 flex items-center justify-center rounded-bl-lg">
                                            <img src="{{ asset('images/logo-mini.png') }}" alt="Logo" class="w-5 h-5 object-contain">
                                        </div>
                                    </div>
                                    <div class="p-3">
                                        <img src="{{ asset('images/default-profile.jpg') }}" alt="Profil" class="w-full h-48 object-cover object-center">
                                        
                                        <div class="mt-4 font-semibold text-blue-800 text-lg">NAMA LENGKAP</div>
                                        <div class="text-sm font-medium text-gray-600">Jabatan</div>
                                        <div class="text-xs text-gray-500 mt-1">NIP. 1234567890</div>
                                    </div>
                                </div>

                                <div class="border border-blue-300 rounded-lg overflow-hidden shadow-md relative bg-white text-center w-64 min-w-[16rem] max-w-[16rem]">
                                    <div class="absolute top-0 right-0">
                                        <div class="bg-blue-500 w-7 h-7 flex items-center justify-center rounded-bl-lg">
                                            <img src="{{ asset('images/logo-mini.png') }}" alt="Logo" class="w-5 h-5 object-contain">
                                        </div>
                                    </div>
                                    <div class="p-3">
                                        <img src="{{ asset('images/default-profile.jpg') }}" alt="Profil" class="w-full h-48 object-cover object-center">
                                        
                                        <div class="mt-4 font-semibold text-blue-800 text-lg">NAMA LENGKAP</div>
                                        <div class="text-sm font-medium text-gray-600">Jabatan</div>
                                        <div class="text-xs text-gray-500 mt-1">NIP. 1234567890</div>
                                    </div>
                                </div>
                            </div>
                        </div>

                        <!-- Connector Line -->
                        <div class="h-8 w-1 bg-blue-500"></div>

                        <!-- Level 5: Kasi -->
                        <div class="w-full">
                            <div class="text-center mb-4">
                                <h3 class="text-lg font-semibold text-gray-700">Kepala Jabatan</h3>
                            </div>
                            <div class="grid grid-cols-1 sm:grid-cols-2 lg:grid-cols-3 xl:grid-cols-4 gap-8 justify-items-center px-4">
                                <div class="border border-blue-300 rounded-lg overflow-hidden shadow-md relative bg-white text-center w-64 min-w-[16rem] max-w-[16rem]">
                                    <div class="absolute top-0 right-0">
                                        <div class="bg-blue-500 w-7 h-7 flex items-center justify-center rounded-bl-lg">
                                            <img src="{{ asset('images/logo-mini.png') }}" alt="Logo" class="w-5 h-5 object-contain">
                                        </div>
                                    </div>
                                    <div class="p-3">
                                        <img src="{{ asset('images/default-profile.jpg') }}" alt="Profil" class="w-full h-48 object-cover object-center">
                                        
                                        <div class="mt-4 font-semibold text-blue-800 text-lg">NAMA LENGKAP</div>
                                        <div class="text-sm font-medium text-gray-600">Jabatan</div>
                                        <div class="text-xs text-gray-500 mt-1">NIP. 1234567890</div>
                                    </div>
                                </div>

                                <div class="border border-blue-300 rounded-lg overflow-hidden shadow-md relative bg-white text-center w-64 min-w-[16rem] max-w-[16rem]">
                                    <div class="absolute top-0 right-0">
                                        <div class="bg-blue-500 w-7 h-7 flex items-center justify-center rounded-bl-lg">
                                            <img src="{{ asset('images/logo-mini.png') }}" alt="Logo" class="w-5 h-5 object-contain">
                                        </div>
                                    </div>
                                    <div class="p-3">
                                        <img src="{{ asset('images/default-profile.jpg') }}" alt="Profil" class="w-full h-48 object-cover object-center">
                                        
                                        <div class="mt-4 font-semibold text-blue-800 text-lg">NAMA LENGKAP</div>
                                        <div class="text-sm font-medium text-gray-600">Jabatan</div>
                                        <div class="text-xs text-gray-500 mt-1">NIP. 1234567890</div>
                                    </div>
                                </div>

                                <div class="border border-blue-300 rounded-lg overflow-hidden shadow-md relative bg-white text-center w-64 min-w-[16rem] max-w-[16rem]">
                                    <div class="absolute top-0 right-0">
                                        <div class="bg-blue-500 w-7 h-7 flex items-center justify-center rounded-bl-lg">
                                            <img src="{{ asset('images/logo-mini.png') }}" alt="Logo" class="w-5 h-5 object-contain">
                                        </div>
                                    </div>
                                    <div class="p-3">
                                        <img src="{{ asset('images/default-profile.jpg') }}" alt="Profil" class="w-full h-48 object-cover object-center">
                                        
                                        <div class="mt-4 font-semibold text-blue-800 text-lg">NAMA LENGKAP</div>
                                        <div class="text-sm font-medium text-gray-600">Jabatan</div>
                                        <div class="text-xs text-gray-500 mt-1">NIP. 1234567890</div>
                                    </div>
                                </div>

                                <div class="border border-blue-300 rounded-lg overflow-hidden shadow-md relative bg-white text-center w-64 min-w-[16rem] max-w-[16rem]">
                                    <div class="absolute top-0 right-0">
                                        <div class="bg-blue-500 w-7 h-7 flex items-center justify-center rounded-bl-lg">
                                            <img src="{{ asset('images/logo-mini.png') }}" alt="Logo" class="w-5 h-5 object-contain">
                                        </div>
                                    </div>
                                    <div class="p-3">
                                        <img src="{{ asset('images/default-profile.jpg') }}" alt="Profil" class="w-full h-48 object-cover object-center">
                                        
                                        <div class="mt-4 font-semibold text-blue-800 text-lg">NAMA LENGKAP</div>
                                        <div class="text-sm font-medium text-gray-600">Jabatan</div>
                                        <div class="text-xs text-gray-500 mt-1">NIP. 1234567890</div>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
</x-main-layout> 