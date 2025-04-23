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
                PROGRAM KERJA
            </h1>
            <div class="flex items-center justify-center text-white text-lg font-medium">
                <a href="{{ route('home') }}" class="hover:text-yellow-300 transition-colors">Beranda</a>
                <span class="mx-3 text-yellow-300">•</span>
                <a href="#" class="hover:text-yellow-300 transition-colors">Profil</a>
                <span class="mx-3 text-yellow-300">•</span>
                <span class="text-yellow-300">Program Kerja</span>
            </div>
        </div>
    </div>

    <!-- Main Content -->
    <div class="py-12">
        <div class="max-w-7xl mx-auto sm:px-6 lg:px-8">
            <div class="bg-white overflow-hidden shadow-sm sm:rounded-lg">
                <div class="p-6">
                    <div class="prose max-w-none">
                        {{-- <h2 class="text-3xl font-bold text-blue-700 mb-6 text-center">Program Kerja Kabupaten/Kota Layak Anak (KLA)</h2>
                        
                        <div class="mb-10">
                            <h3 class="text-2xl font-bold text-gray-800 mb-4">A. Prinsip</h3>
                            <p class="mb-4">Prinsip Kebijakan KLA disusun dengan mengacu pada prinsip dasar hak Anak menurut KHA dan kaidah reformasi birokrasi sebagai berikut:</p>
                            
                            <div class="bg-blue-50 rounded-lg p-6 mb-4">
                                <ol class="list-decimal pl-6 space-y-3">
                                    <li class="text-gray-700"><span class="font-medium">Nondiskriminasi</span>, yaitu tidak membedakan suku, ras, agama, jenis kelamin, bahasa, paham politik, asal kebangsaan, status ekonomi, kondisi fisik maupun psikis Anak, atau faktor lainnya;</li>
                                    <li class="text-gray-700"><span class="font-medium">Kepentingan terbaik bagi Anak</span>, yaitu menjadikan Anak sebagai pertimbangan utama dalam setiap pengambilan kebijakan serta pengembangan program dan kegiatan;</li>
                                    <li class="text-gray-700"><span class="font-medium">Hak untuk hidup, kelangsungan hidup, dan perkembangan Anak</span>, yaitu menjamin hak untuk hidup, kelangsungan hidup, dan perkembangan Anak semaksimal mungkin;</li>
                                    <li class="text-gray-700"><span class="font-medium">Penghargaan terhadap pandangan Anak</span>, yaitu mengakui dan memastikan bahwa setiap Anak diberikan kesempatan untuk mengekspresikan pandangannya secara bebas, independen, dan santun terhadap segala sesuatu hal yang mempengaruhi dirinya, diberi bobot, dan dipertimbangkan dalam pengambilan keputusan; dan</li>
                                    <li class="text-gray-700"><span class="font-medium">Tata pemerintahan yang baik</span>, yaitu transparansi, akuntabilitas, partisipasi, keterbukaan informasi, dan supremasi hukum.</li>
                                </ol>
                            </div>
                        </div>
                        
                        <div class="mb-10">
                            <h3 class="text-2xl font-bold text-gray-800 mb-4">B. Arah Kebijakan</h3>
                            <p class="mb-4">Rumusan perencanaan komprehensif Kebijakan KLA termuat dalam 6 (enam) arah kebijakan yaitu:</p>
                            
                            <div class="grid grid-cols-1 md:grid-cols-2 gap-6 mb-4">
                                <div class="bg-gray-50 rounded-lg p-6">
                                    <div class="flex items-center gap-4 mb-4">
                                        <div class="w-12 h-12 bg-blue-100 rounded-lg flex items-center justify-center">
                                            <svg class="w-6 h-6 text-blue-600" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 21V5a2 2 0 00-2-2H7a2 2 0 00-2 2v16m14 0h2m-2 0h-5m-9 0H3m2 0h5M9 7h1m-1 4h1m4-4h1m-1 4h1m-5 10v-5a1 1 0 011-1h2a1 1 0 011 1v5m-4 0h4"></path>
                                            </svg>
                                        </div>
                                        <h4 class="text-xl font-semibold text-gray-800">Penguatan Kelembagaan</h4>
                                    </div>
                                    <p class="text-gray-700">Mengoptimalkan potensi dalam penguatan kelembagaan KLA untuk mendukung implementasi program.</p>
                                </div>
                                
                                <div class="bg-gray-50 rounded-lg p-6">
                                    <div class="flex items-center gap-4 mb-4">
                                        <div class="w-12 h-12 bg-blue-100 rounded-lg flex items-center justify-center">
                                            <svg class="w-6 h-6 text-blue-600" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M16 7a4 4 0 11-8 0 4 4 0 018 0zM12 14a7 7 0 00-7 7h14a7 7 0 00-7-7z"></path>
                                            </svg>
                                        </div>
                                        <h4 class="text-xl font-semibold text-gray-800">Hak Sipil dan Kebebasan</h4>
                                    </div>
                                    <p class="text-gray-700">Mewujudkan pemenuhan hak sipil dan kebebasan bagi setiap anak tanpa diskriminasi.</p>
                                </div>
                                
                                <div class="bg-gray-50 rounded-lg p-6">
                                    <div class="flex items-center gap-4 mb-4">
                                        <div class="w-12 h-12 bg-blue-100 rounded-lg flex items-center justify-center">
                                            <svg class="w-6 h-6 text-blue-600" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M3 12l2-2m0 0l7-7 7 7M5 10v10a1 1 0 001 1h3m10-11l2 2m-2-2v10a1 1 0 01-1 1h-3m-6 0a1 1 0 001-1v-4a1 1 0 011-1h2a1 1 0 011 1v4a1 1 0 001 1m-6 0h6"></path>
                                            </svg>
                                        </div>
                                        <h4 class="text-xl font-semibold text-gray-800">Lingkungan Keluarga</h4>
                                    </div>
                                    <p class="text-gray-700">Menguatkan lingkungan keluarga dan pengasuhan alternatif untuk memastikan tumbuh kembang optimal anak.</p>
                                </div>
                                
                                <div class="bg-gray-50 rounded-lg p-6">
                                    <div class="flex items-center gap-4 mb-4">
                                        <div class="w-12 h-12 bg-blue-100 rounded-lg flex items-center justify-center">
                                            <svg class="w-6 h-6 text-blue-600" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M4.318 6.318a4.5 4.5 0 000 6.364L12 20.364l7.682-7.682a4.5 4.5 0 00-6.364-6.364L12 7.636l-1.318-1.318a4.5 4.5 0 00-6.364 0z"></path>
                                            </svg>
                                        </div>
                                        <h4 class="text-xl font-semibold text-gray-800">Kesehatan dan Kesejahteraan</h4>
                                    </div>
                                    <p class="text-gray-700">Memastikan terpenuhinya hak kesehatan dasar dan kesejahteraan anak melalui layanan kesehatan yang berkualitas.</p>
                                </div>
                                
                                <div class="bg-gray-50 rounded-lg p-6">
                                    <div class="flex items-center gap-4 mb-4">
                                    <div class="w-12 h-12 bg-blue-100 rounded-lg flex items-center justify-center">
                                        <svg class="w-6 h-6 text-blue-600" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 6.253v13m0-13C10.832 5.477 9.246 5 7.5 5S4.168 5.477 3 6.253v13C4.168 18.477 5.754 18 7.5 18s3.332.477 4.5 1.253m0-13C13.168 5.477 14.754 5 16.5 5c1.747 0 3.332.477 4.5 1.253v13C19.832 18.477 18.247 18 16.5 18c-1.746 0-3.332.477-4.5 1.253"></path>
                                        </svg>
                                        </div>
                                        <h4 class="text-xl font-semibold text-gray-800">Pendidikan dan Kebudayaan</h4>
                                    </div>
                                    <p class="text-gray-700">Mengutamakan pemenuhan hak anak atas pendidikan, pemanfaatan waktu luang, dan kegiatan budaya.</p>
                                </div>
                                
                                <div class="bg-gray-50 rounded-lg p-6">
                                    <div class="flex items-center gap-4 mb-4">
                                        <div class="w-12 h-12 bg-blue-100 rounded-lg flex items-center justify-center">
                                            <svg class="w-6 h-6 text-blue-600" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 15v2m-6 4h12a2 2 0 002-2v-6a2 2 0 00-2-2H6a2 2 0 00-2 2v6a2 2 0 002 2zm10-10V7a4 4 0 00-8 0v4h8z"></path>
                                        </svg>
                                        </div>
                                        <h4 class="text-xl font-semibold text-gray-800">Perlindungan Khusus</h4>
                                    </div>
                                    <p class="text-gray-700">Memastikan pelayanan bagi anak yang memerlukan perlindungan khusus dengan pendekatan terintegrasi.</p>
                                </div>
                            </div>
                        </div>
                        
                        <div class="mb-6">
                            <h3 class="text-2xl font-bold text-gray-800 mb-4">C. Strategi</h3>
                            <p class="mb-4">Perwujudan Kebijakan KLA dilaksanakan berdasarkan 3 (tiga) strategi utama, yaitu:</p>
                            
                            <div class="bg-green-50 rounded-lg p-6">
                                <ol class="list-decimal pl-6 space-y-4">
                                    <li class="text-gray-700">
                                        <span class="font-medium">Peningkatan sumber daya manusia dan penguatan peran kelembagaan</span> pemerintah pusat dan pemerintah daerah dalam pencegahan dan penyediaan layanan.
                                    </li>
                                    <li class="text-gray-700">
                                        <span class="font-medium">Peningkatan peran:</span>
                                        <ul class="list-disc pl-6 mt-2 space-y-1">
                                            <li>Orang perseorangan</li>
                                            <li>Lembaga Perlindungan Anak</li>
                                            <li>Lembaga kesejahteraan sosial</li>
                                            <li>Organisasi kemasyarakatan</li>
                                            <li>Lembaga pendidikan</li>
                                            <li>Media massa</li>
                                            <li>Dunia usaha</li>
                                            <li>Anak</li>
                                        </ul>
                                        <p class="mt-2">melalui advokasi, fasilitasi, sosialisasi, dan edukasi.</p>
                                    </li>
                                    <li class="text-gray-700">
                                        <span class="font-medium">Peningkatan sarana dan prasarana</span> yang mendukung pemenuhan hak anak dan perlindungan khusus anak.
                                    </li>
                                </ol>
                            </div>
                        </div> --}}
                        
                        <div class="mt-12">
                            
                            <div class="bg-gray-50 rounded-lg p-8">
                                <div class="flex items-center gap-4 mb-6">
                                    <div class="w-12 h-12 bg-purple-100 rounded-lg flex items-center justify-center">
                                        <svg class="w-6 h-6 text-purple-600" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 5H7a2 2 0 00-2 2v12a2 2 0 002 2h10a2 2 0 002-2V7a2 2 0 00-2-2h-2M9 5a2 2 0 002 2h2a2 2 0 002-2M9 5a2 2 0 012-2h2a2 2 0 012 2m-3 7h3m-3 4h3m-6-4h.01M9 16h.01"/>
                                        </svg>
                                    </div>
                                    <h2 class="text-2xl font-bold text-gray-800">Program Kerja Kabupaten/Kota Layak Anak (KLA)</h2>
                                </div>
                                
                                <div class="grid grid-cols-1 md:grid-cols-2 gap-8">
                                    <ul class="space-y-4">
                                        <li class="flex items-start gap-3">
                                            <svg class="w-5 h-5 text-purple-600 mt-1" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 5l7 7-7 7"/>
                                            </svg>
                                            <span class="text-gray-700">Pembentukan Gugus Tugas KLA tingkat kabupaten/kota dan kecamatan</span>
                                        </li>
                                        <li class="flex items-start gap-3">
                                            <svg class="w-5 h-5 text-purple-600 mt-1" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 5l7 7-7 7"/>
                                            </svg>
                                            <span class="text-gray-700">Pelatihan peningkatan kapasitas SDM pengelola KLA</span>
                                        </li>
                                        <li class="flex items-start gap-3">
                                            <svg class="w-5 h-5 text-purple-600 mt-1" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 5l7 7-7 7"/>
                                            </svg>
                                            <span class="text-gray-700">Sosialisasi KLA kepada masyarakat dan pemangku kepentingan</span>
                                        </li>
                                        <li class="flex items-start gap-3">
                                            <svg class="w-5 h-5 text-purple-600 mt-1" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 5l7 7-7 7"/>
                                            </svg>
                                            <span class="text-gray-700">Pembentukan Forum Anak di tingkat desa/kelurahan</span>
                                        </li>
                                        <li class="flex items-start gap-3">
                                            <svg class="w-5 h-5 text-purple-600 mt-1" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 5l7 7-7 7"/>
                                            </svg>
                                            <span class="text-gray-700">Pendataan anak yang memerlukan perlindungan khusus</span>
                                        </li>
                                    </ul>
                                    
                                <ul class="space-y-4">
                                    <li class="flex items-start gap-3">
                                            <svg class="w-5 h-5 text-purple-600 mt-1" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 5l7 7-7 7"/>
                                            </svg>
                                            <span class="text-gray-700">Pengembangan Sekolah Ramah Anak di seluruh wilayah</span>
                                        </li>
                                        <li class="flex items-start gap-3">
                                            <svg class="w-5 h-5 text-purple-600 mt-1" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 5l7 7-7 7"/>
                                            </svg>
                                            <span class="text-gray-700">Pembangunan Ruang Publik Ramah Anak di setiap kecamatan</span>
                                        </li>
                                        <li class="flex items-start gap-3">
                                            <svg class="w-5 h-5 text-purple-600 mt-1" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 5l7 7-7 7"/>
                                        </svg>
                                            <span class="text-gray-700">Pengembangan Puskesmas Ramah Anak dan pelayanan kesehatan komprehensif</span>
                                    </li>
                                    <li class="flex items-start gap-3">
                                            <svg class="w-5 h-5 text-purple-600 mt-1" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 5l7 7-7 7"/>
                                        </svg>
                                            <span class="text-gray-700">Pengembangan sistem perlindungan anak terintegrasi</span>
                                    </li>
                                    <li class="flex items-start gap-3">
                                            <svg class="w-5 h-5 text-purple-600 mt-1" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 5l7 7-7 7"/>
                                        </svg>
                                            <span class="text-gray-700">Pembentukan kawasan ramah anak di seluruh wilayah desa/kelurahan</span>
                                    </li>
                                </ul>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
</x-main-layout> 