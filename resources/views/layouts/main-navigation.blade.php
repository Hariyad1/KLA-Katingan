<nav x-data="{ open: false }" class="fixed top-0 left-0 right-0 z-50 bg-white/80 backdrop-blur-sm border-b border-gray-100 transition-all duration-200">
    <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8">
        <div class="flex justify-between h-20">
            <div class="flex">
                <!-- Logo -->
                <div class="flex items-center">
                    <a href="/" class="flex items-center">
                        <img src="{{ asset('images/logo-katingan.png') }}" alt="Logo Katingan" class="h-16 w-auto">
                        <div class="ml-3">
                            <div class="text-lg font-semibold text-gray-800">Website Resmi Kabupaten Layak Anak</div>
                            <div class="text-sm text-gray-600">Kabupaten Katingan</div>
                        </div>
                    </a>
                </div>

                <!-- Navigation Links -->
                <div class="hidden space-x-8 sm:ml-10 sm:flex items-center">
                    <x-nav-link href="/" :active="request()->is('/')">
                        Home
                    </x-nav-link>

                    <x-nav-link href="{{ route('galeri') }}" :active="request()->routeIs('galeri')">
                        Galeri
                    </x-nav-link>

                    <x-nav-link href="{{ route('dokumen') }}" :active="request()->routeIs('dokumen')">
                        Dokumen
                    </x-nav-link>

                    <div class="relative" x-data="{ open: false }">
                        <button @click="open = !open" @click.away="open = false" class="inline-flex items-center px-1 pt-1 border-b-2 border-transparent text-sm font-medium leading-5 text-gray-500 hover:text-gray-700 hover:border-gray-300 focus:outline-none transition duration-150 ease-in-out">
                            Profil
                            <svg class="ml-2 -mr-0.5 h-4 w-4 transform transition-transform duration-200" 
                                 :class="{'rotate-180': open}"
                                 xmlns="http://www.w3.org/2000/svg" 
                                 viewBox="0 0 20 20" 
                                 fill="currentColor">
                                <path fill-rule="evenodd" d="M5.293 7.293a1 1 0 011.414 0L10 10.586l3.293-3.293a1 1 0 111.414 1.414l-4 4a1 1 0 01-1.414 0l-4-4a1 1 0 010-1.414z" clip-rule="evenodd" />
                            </svg>
                        </button>

                        <div x-show="open" 
                             x-transition:enter="transition ease-out duration-200"
                             x-transition:enter-start="transform opacity-0 scale-95"
                             x-transition:enter-end="transform opacity-100 scale-100"
                             x-transition:leave="transition ease-in duration-75"
                             x-transition:leave-start="transform opacity-100 scale-100"
                             x-transition:leave-end="transform opacity-0 scale-95"
                             class="absolute z-50 mt-2 w-48 rounded-md shadow-lg">
                            <div class="rounded-md ring-1 ring-black ring-opacity-5 py-1 bg-white">
                                <a href="{{ route('profil.struktur') }}" class="block px-4 py-2 text-sm text-gray-700 hover:bg-gray-100">Struktur Organisasi</a>
                                <a href="{{ route('profil.visi-misi') }}" class="block px-4 py-2 text-sm text-gray-700 hover:bg-gray-100">Visi dan Misi</a>
                                <a href="{{ route('profil.program') }}" class="block px-4 py-2 text-sm text-gray-700 hover:bg-gray-100">Program Kerja</a>
                            </div>
                        </div>
                    </div>

                    <div class="relative" x-data="{ open: false }">
                        <button @click="open = !open" @click.away="open = false" class="inline-flex items-center px-1 pt-1 border-b-2 border-transparent text-sm font-medium leading-5 text-gray-500 hover:text-gray-700 hover:border-gray-300 focus:outline-none transition duration-150 ease-in-out">
                            Pemenuhan Hak Anak
                            <svg class="ml-2 -mr-0.5 h-4 w-4 transform transition-transform duration-200" 
                                 :class="{'rotate-180': open}"
                                 xmlns="http://www.w3.org/2000/svg" 
                                 viewBox="0 0 20 20" 
                                 fill="currentColor">
                                <path fill-rule="evenodd" d="M5.293 7.293a1 1 0 011.414 0L10 10.586l3.293-3.293a1 1 0 111.414 1.414l-4 4a1 1 0 01-1.414 0l-4-4a1 1 0 010-1.414z" clip-rule="evenodd" />
                            </svg>
                        </button>

                        <div x-show="open" 
                             x-transition:enter="transition ease-out duration-200"
                             x-transition:enter-start="transform opacity-0 scale-95"
                             x-transition:enter-end="transform opacity-100 scale-100"
                             x-transition:leave="transition ease-in duration-75"
                             x-transition:leave-start="transform opacity-100 scale-100"
                             x-transition:leave-end="transform opacity-0 scale-95"
                             class="absolute z-50 mt-2 w-48 rounded-md shadow-lg">
                            <div class="rounded-md ring-1 ring-black ring-opacity-5 py-1 bg-white">
                                <a href="{{ route('pemenuhan-hak-anak.klaster1') }}" class="block px-4 py-2 text-sm text-gray-700 hover:bg-gray-100">Klaster 1</a>
                                <a href="{{ route('pemenuhan-hak-anak.klaster2') }}" class="block px-4 py-2 text-sm text-gray-700 hover:bg-gray-100">Klaster 2</a>
                                <a href="{{ route('pemenuhan-hak-anak.klaster3') }}" class="block px-4 py-2 text-sm text-gray-700 hover:bg-gray-100">Klaster 3</a>
                                <a href="{{ route('pemenuhan-hak-anak.klaster4') }}" class="block px-4 py-2 text-sm text-gray-700 hover:bg-gray-100">Klaster 4</a>
                            </div>
                        </div>
                    </div>
                    <div class="relative" x-data="{ open: false }">
                        <button @click="open = !open" @click.away="open = false" class="inline-flex items-center px-1 pt-1 border-b-2 border-transparent text-sm font-medium leading-5 text-gray-500 hover:text-gray-700 hover:border-gray-300 focus:outline-none transition duration-150 ease-in-out">
                            Perlindungan Khusus Anak
                            <svg class="ml-2 -mr-0.5 h-4 w-4 transform transition-transform duration-200" 
                                 :class="{'rotate-180': open}"
                                 xmlns="http://www.w3.org/2000/svg" 
                                 viewBox="0 0 20 20" 
                                 fill="currentColor">
                                <path fill-rule="evenodd" d="M5.293 7.293a1 1 0 011.414 0L10 10.586l3.293-3.293a1 1 0 111.414 1.414l-4 4a1 1 0 01-1.414 0l-4-4a1 1 0 010-1.414z" clip-rule="evenodd" />
                            </svg>
                        </button>

                        <div x-show="open" 
                             x-transition:enter="transition ease-out duration-200"
                             x-transition:enter-start="transform opacity-0 scale-95"
                             x-transition:enter-end="transform opacity-100 scale-100"
                             x-transition:leave="transition ease-in duration-75"
                             x-transition:leave-start="transform opacity-100 scale-100"
                             x-transition:leave-end="transform opacity-0 scale-95"
                             class="absolute z-50 mt-2 w-48 rounded-md shadow-lg">
                            <div class="rounded-md ring-1 ring-black ring-opacity-5 py-1 bg-white">
                                <a href="{{ route('perlindungan-khusus-anak.klaster5') }}" class="block px-4 py-2 text-sm text-gray-700 hover:bg-gray-100">Klaster 5</a>
                            </div>
                        </div>
                    </div>
                    <x-nav-link href="/kontak" :active="request()->is('kontak')">
                        Kontak Kami
                    </x-nav-link>
                </div>
            </div>

            <!-- Hamburger -->
            <div class="-mr-2 flex items-center sm:hidden">
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
    <div :class="{'block': open, 'hidden': ! open}" class="hidden sm:hidden">
        <div class="pt-2 pb-3 space-y-1 bg-white border-b border-gray-200">
            <x-responsive-nav-link href="/" :active="request()->is('/')">
                Home
            </x-responsive-nav-link>

            <x-responsive-nav-link href="{{ route('galeri') }}" :active="request()->routeIs('galeri')">
                Galeri
            </x-responsive-nav-link>

            <x-responsive-nav-link href="{{ route('dokumen') }}" :active="request()->routeIs('dokumen')">
                Dokumen
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
                    <a href="{{ route('profil.struktur') }}" class="block px-4 py-2 text-sm text-gray-600 hover:text-gray-800">Struktur Organisasi</a>
                    <a href="{{ route('profil.visi-misi') }}" class="block px-4 py-2 text-sm text-gray-600 hover:text-gray-800">Visi dan Misi</a>
                    <a href="{{ route('profil.program') }}" class="block px-4 py-2 text-sm text-gray-600 hover:text-gray-800">Program Kerja</a>
                </div>
            </div>

            <!-- Dropdown Pemenuhan Hak Anak -->
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

            <!-- Dropdown Perlindungan Khusus Anak -->
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

<!-- Spacer to prevent content from hiding behind fixed navbar -->
<div class="h-20"></div> 