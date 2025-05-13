<nav x-data="{ open: false }" class="fixed top-0 left-0 right-0 z-50 bg-white/95 backdrop-blur-sm border-b border-gray-100 transition-all duration-200">
    <div class="max-w-7xl mx-auto px-6 sm:px-8 lg:px-12">
        <div class="flex justify-between items-center h-14">
            <div class="flex items-center">
                <!-- Logo -->
                <div class="flex items-center shrink-0">
                    <a href="/" class="flex items-center space-x-3">
                        <img src="{{ asset('images/logo_katingan.png') }}" alt="Logo Katingan" class="h-8 w-auto">
                        <div>
                            <div class="text-sm font-semibold bg-gradient-to-r from-blue-600 to-indigo-600 bg-clip-text text-transparent">Website Resmi KLA</div>
                            <div class="text-xs text-gray-600">Kabupaten Katingan</div>
                        </div>
                    </a>
                    <div class="ml-2">
                        <img src="{{ asset('images/logo_kla.png') }}" alt="Logo KLA" class="h-14 w-14 object-contain">
                    </div>
                </div>
            </div>

            <!-- Right side -->
            <div class="hidden xl:flex items-center space-x-4">
                <a href="/" 
                   class="group relative px-4 py-2 text-xs font-medium text-gray-800 hover:text-blue-600 transition-all duration-200">
                    <span class="relative z-10">{{ __('Beranda') }}</span>
                    <div class="absolute inset-0 h-full w-full bg-blue-100/80 rounded-xl origin-left scale-x-0 group-hover:scale-x-100 transition-transform duration-300 ease-out"></div>
                </a>

                <div class="relative" 
                     x-data="{ open: false }" 
                     @mouseenter="open = true" 
                     @mouseleave="open = false">
                    <button 
                        class="group relative px-4 py-2 inline-flex items-center text-xs font-medium text-gray-800 hover:text-blue-600 transition-all duration-200">
                        <span class="relative z-10">Profil</span>
                        <svg class="ml-1 w-3 h-3 transition-transform duration-200 group-hover:rotate-180" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 9l-7 7-7-7" />
                        </svg>
                        <div class="absolute inset-0 h-full w-full bg-blue-100/80 rounded-xl origin-left scale-x-0 group-hover:scale-x-100 transition-transform duration-300 ease-out"></div>
                    </button>

                    <div x-show="open" 
                         x-transition:enter="transition ease-out duration-200"
                         x-transition:enter-start="opacity-0 transform -translate-y-2"
                         x-transition:enter-end="opacity-100 transform translate-y-0"
                         class="absolute z-50 w-40 rounded-xl bg-white shadow-lg ring-1 ring-black ring-opacity-5 focus:outline-none overflow-hidden"
                         style="margin-top: -2px;">
                        <div class="py-1">
                            {{-- <a href="{{ route('profil.struktur') }}" 
                               class="group relative block px-3 py-1.5 text-xs text-gray-800 hover:text-blue-600 transition-colors duration-200">
                                <span class="relative z-10">Struktur Organisasi</span>
                                <div class="absolute inset-0 h-full w-full bg-blue-100/80 origin-left scale-x-0 group-hover:scale-x-100 transition-transform duration-300 ease-out"></div>
                            </a> --}}
                            <a href="{{ route('profil.visi-misi') }}" 
                               class="group relative block px-3 py-1.5 text-xs text-gray-800 hover:text-blue-600 transition-colors duration-200">
                                <span class="relative z-10">Visi dan Misi</span>
                                <div class="absolute inset-0 h-full w-full bg-blue-100/80 origin-left scale-x-0 group-hover:scale-x-100 transition-transform duration-300 ease-out"></div>
                            </a>
                            <a href="{{ route('profil.program') }}" 
                               class="group relative block px-3 py-1.5 text-xs text-gray-800 hover:text-blue-600 transition-colors duration-200">
                                <span class="relative z-10">Program Kerja</span>
                                <div class="absolute inset-0 h-full w-full bg-blue-100/80 origin-left scale-x-0 group-hover:scale-x-100 transition-transform duration-300 ease-out"></div>
                            </a>
                        </div>
                    </div>
                </div>

                <a href="{{ route('galeri') }}" 
                   class="group relative px-4 py-2 text-xs font-medium text-gray-800 hover:text-blue-600 transition-all duration-200">
                    <span class="relative z-10">Galeri</span>
                    <div class="absolute inset-0 h-full w-full bg-blue-100/80 rounded-xl origin-left scale-x-0 group-hover:scale-x-100 transition-transform duration-300 ease-out"></div>
                </a>

                <a href="{{ route('dokumen') }}" 
                   class="group relative px-4 py-2 text-xs font-medium text-gray-800 hover:text-blue-600 transition-all duration-200">
                    <span class="relative z-10">Dokumen</span>
                    <div class="absolute inset-0 h-full w-full bg-blue-100/80 rounded-xl origin-left scale-x-0 group-hover:scale-x-100 transition-transform duration-300 ease-out"></div>
                </a>

                <div class="relative" 
                     x-data="{ open: false }" 
                     @mouseenter="open = true" 
                     @mouseleave="open = false">
                    <button 
                        class="group relative px-4 py-2 inline-flex items-center text-xs font-medium text-gray-800 hover:text-blue-600 transition-all duration-200">
                        <span class="relative z-10">Pemenuhan Hak Anak</span>
                        <svg class="ml-1 w-3 h-3 transition-transform duration-200 group-hover:rotate-180" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 9l-7 7-7-7" />
                        </svg>
                        <div class="absolute inset-0 h-full w-full bg-blue-100/80 rounded-xl origin-left scale-x-0 group-hover:scale-x-100 transition-transform duration-300 ease-out"></div>
                    </button>

                    <div x-show="open"
                         x-transition:enter="transition ease-out duration-200"
                         x-transition:enter-start="opacity-0 transform -translate-y-2"
                         x-transition:enter-end="opacity-100 transform translate-y-0"
                         class="absolute z-50 w-40 rounded-xl bg-white shadow-lg ring-1 ring-black ring-opacity-5 focus:outline-none overflow-hidden"
                         style="margin-top: -2px;">
                        <div class="py-1">
                            <a href="{{ route('pemenuhan-hak-anak.klaster1') }}" 
                               class="group relative block px-3 py-1.5 text-xs text-gray-800 hover:text-blue-600 transition-colors duration-200">
                                <span class="relative z-10">Klaster 1</span>
                                <div class="absolute inset-0 h-full w-full bg-blue-100/80 origin-left scale-x-0 group-hover:scale-x-100 transition-transform duration-300 ease-out"></div>
                            </a>
                            <a href="{{ route('pemenuhan-hak-anak.klaster2') }}" 
                               class="group relative block px-3 py-1.5 text-xs text-gray-800 hover:text-blue-600 transition-colors duration-200">
                                <span class="relative z-10">Klaster 2</span>
                                <div class="absolute inset-0 h-full w-full bg-blue-100/80 origin-left scale-x-0 group-hover:scale-x-100 transition-transform duration-300 ease-out"></div>
                            </a>
                            <a href="{{ route('pemenuhan-hak-anak.klaster3') }}" 
                               class="group relative block px-3 py-1.5 text-xs text-gray-800 hover:text-blue-600 transition-colors duration-200">
                                <span class="relative z-10">Klaster 3</span>
                                <div class="absolute inset-0 h-full w-full bg-blue-100/80 origin-left scale-x-0 group-hover:scale-x-100 transition-transform duration-300 ease-out"></div>
                            </a>
                            <a href="{{ route('pemenuhan-hak-anak.klaster4') }}" 
                               class="group relative block px-3 py-1.5 text-xs text-gray-800 hover:text-blue-600 transition-colors duration-200">
                                <span class="relative z-10">Klaster 4</span>
                                <div class="absolute inset-0 h-full w-full bg-blue-100/80 origin-left scale-x-0 group-hover:scale-x-100 transition-transform duration-300 ease-out"></div>
                            </a>
                        </div>
                    </div>
                </div>
                <div class="relative" 
                     x-data="{ open: false }" 
                     @mouseenter="open = true" 
                     @mouseleave="open = false">
                    <button 
                        class="group relative px-4 py-2 inline-flex items-center text-xs font-medium text-gray-800 hover:text-blue-600 transition-all duration-200">
                        <span class="relative z-10">Perlindungan Khusus Anak</span>
                        <svg class="ml-1 w-3 h-3 transition-transform duration-200 group-hover:rotate-180" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 9l-7 7-7-7" />
                        </svg>
                        <div class="absolute inset-0 h-full w-full bg-blue-100/80 rounded-xl origin-left scale-x-0 group-hover:scale-x-100 transition-transform duration-300 ease-out"></div>
                    </button>

                    <div x-show="open"
                         x-transition:enter="transition ease-out duration-200"
                         x-transition:enter-start="opacity-0 transform -translate-y-2"
                         x-transition:enter-end="opacity-100 transform translate-y-0"
                         class="absolute z-50 w-40 rounded-xl bg-white shadow-lg ring-1 ring-black ring-opacity-5 focus:outline-none overflow-hidden"
                         style="margin-top: -2px;">
                        <div class="py-1">
                            <a href="{{ route('perlindungan-khusus-anak.klaster5') }}" 
                               class="group relative block px-3 py-1.5 text-xs text-gray-800 hover:text-blue-600 transition-colors duration-200">
                                <span class="relative z-10">Klaster 5</span>
                                <div class="absolute inset-0 h-full w-full bg-blue-100/80 origin-left scale-x-0 group-hover:scale-x-100 transition-transform duration-300 ease-out"></div>
                            </a>
                        </div>
                    </div>
                </div>
                <a href="/kontak" 
                   class="group relative px-4 py-2 text-xs font-medium text-gray-800 hover:text-blue-600 transition-all duration-200">
                    <span class="relative z-10">Kontak Kami</span>
                    <div class="absolute inset-0 h-full w-full bg-blue-100/80 rounded-xl origin-left scale-x-0 group-hover:scale-x-100 transition-transform duration-300 ease-out"></div>
                </a>
            </div>

            <!-- Hamburger -->
            <div class="flex items-center xl:hidden">
                <button @click="open = ! open" class="inline-flex items-center justify-center p-2 rounded-md text-gray-400 hover:text-gray-500 hover:bg-gray-100 focus:outline-none focus:bg-gray-100 focus:text-gray-500 transition duration-150 ease-in-out">
                    <svg class="h-6 w-6" stroke="currentColor" fill="none" viewBox="0 0 24 24">
                        <path :class="{'hidden': open, 'inline-flex': ! open }" class="inline-flex" stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M4 6h16M4 12h16M4 18h16" />
                        <path :class="{'hidden': ! open, 'inline-flex': open }" class="hidden" stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M6 18L18 6M6 6l12 12" />
                    </svg>
                </button>
            </div>
        </div>
    </div>

    <!-- Mobile menu -->
    <div :class="{'block': open, 'hidden': ! open}" class="hidden xl:hidden">
        <div class="pt-2 pb-3 space-y-1 bg-white border-b border-gray-200">
            <x-responsive-nav-link href="/" :active="request()->is('/')">
                Beranda
            </x-responsive-nav-link>

            <div x-data="{ subOpen: false }" class="relative">
                <button @click="subOpen = !subOpen" class="w-full flex items-center justify-between px-4 py-2 text-base font-medium text-gray-600 hover:text-gray-800 hover:bg-gray-50">
                    <span>Profil</span>
                    <svg class="ml-2 h-5 w-5 transform transition-transform duration-200" 
                         :class="{'rotate-180': subOpen}"
                         xmlns="http://www.w3.org/2000/svg" 
                         viewBox="0 0 20 20" 
                         fill="currentColor">
                        <path fill-rule="evenodd" d="M5.293 7.293a1 1 0 011.414 0L10 10.586l3.293-3.293a1 1 0 111.414 1.414l-4 4a1 1 0 01-1.414 0l-4-4a1 1 0 010-1.414z" clip-rule="evenodd" />
                    </svg>
                </button>
                <div x-show="subOpen" class="px-4 py-2 bg-gray-50">
                    {{-- <a href="{{ route('profil.struktur') }}" class="block px-4 py-2 text-sm text-gray-600 hover:text-gray-800">Struktur Organisasi</a> --}}
                    <a href="{{ route('profil.visi-misi') }}" class="block px-4 py-2 text-sm text-gray-600 hover:text-gray-800">Visi dan Misi</a>
                    <a href="{{ route('profil.program') }}" class="block px-4 py-2 text-sm text-gray-600 hover:text-gray-800">Program Kerja</a>
                </div>
            </div>

            <x-responsive-nav-link href="{{ route('galeri') }}" :active="request()->routeIs('galeri')">
                Galeri
            </x-responsive-nav-link>

            <x-responsive-nav-link href="{{ route('dokumen') }}" :active="request()->routeIs('dokumen')">
                Dokumen
            </x-responsive-nav-link>

            <div x-data="{ subOpen: false }" class="relative">
                <button @click="subOpen = !subOpen" class="w-full flex items-center justify-between px-4 py-2 text-base font-medium text-gray-600 hover:text-gray-800 hover:bg-gray-50">
                    <span>Pemenuhan Hak Anak</span>
                    <svg class="ml-2 h-5 w-5 transform transition-transform duration-200" 
                         :class="{'rotate-180': subOpen}"
                         xmlns="http://www.w3.org/2000/svg" 
                         viewBox="0 0 20 20" 
                         fill="currentColor">
                        <path fill-rule="evenodd" d="M5.293 7.293a1 1 0 011.414 0L10 10.586l3.293-3.293a1 1 0 111.414 1.414l-4 4a1 1 0 01-1.414 0l-4-4a1 1 0 010-1.414z" clip-rule="evenodd" />
                    </svg>
                </button>
                <div x-show="subOpen" class="px-4 py-2 bg-gray-50">
                    <a href="{{ route('pemenuhan-hak-anak.klaster1') }}" class="block px-4 py-2 text-sm text-gray-600 hover:text-gray-800">Klaster 1</a>
                    <a href="{{ route('pemenuhan-hak-anak.klaster2') }}" class="block px-4 py-2 text-sm text-gray-600 hover:text-gray-800">Klaster 2</a>
                    <a href="{{ route('pemenuhan-hak-anak.klaster3') }}" class="block px-4 py-2 text-sm text-gray-600 hover:text-gray-800">Klaster 3</a>
                    <a href="{{ route('pemenuhan-hak-anak.klaster4') }}" class="block px-4 py-2 text-sm text-gray-600 hover:text-gray-800">Klaster 4</a>
                </div>
            </div>

            <div x-data="{ subOpen: false }" class="relative">
                <button @click="subOpen = !subOpen" class="w-full flex items-center justify-between px-4 py-2 text-base font-medium text-gray-600 hover:text-gray-800 hover:bg-gray-50">
                    <span>Perlindungan Khusus Anak</span>
                    <svg class="ml-2 h-5 w-5 transform transition-transform duration-200" 
                         :class="{'rotate-180': subOpen}"
                         xmlns="http://www.w3.org/2000/svg" 
                         viewBox="0 0 20 20" 
                         fill="currentColor">
                        <path fill-rule="evenodd" d="M5.293 7.293a1 1 0 011.414 0L10 10.586l3.293-3.293a1 1 0 111.414 1.414l-4 4a1 1 0 01-1.414 0l-4-4a1 1 0 010-1.414z" clip-rule="evenodd" />
                    </svg>
                </button>
                <div x-show="subOpen" class="px-4 py-2 bg-gray-50">
                    <a href="{{ route('perlindungan-khusus-anak.klaster5') }}" class="block px-4 py-2 text-sm text-gray-600 hover:text-gray-800">Klaster 5</a>
                </div>
            </div>

            <x-responsive-nav-link href="/kontak" :active="request()->is('kontak')">
                Kontak Kami
            </x-responsive-nav-link>
        </div>
    </div>
</nav>

<div class="h-14"></div> 