<nav class="bg-white border-r border-gray-200 fixed h-full w-60 lg:block transform transition-transform duration-200 ease-in-out z-50" 
     :class="{'translate-x-0': open, '-translate-x-full lg:translate-x-0': !open}">
    
    <div class="flex flex-col h-full">
        <!-- Logo -->
        <div class="flex items-center justify-between h-20 border-b border-gray-200 px-4">
            <a href="{{ route('dashboard') }}" class="flex items-center">
                <span class="text-xl font-bold text-indigo-700">Admin</span>
            </a>
            <button @click="open = false" class="lg:hidden p-2 rounded-md text-gray-400 hover:text-gray-500">
                <svg class="h-6 w-6" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M6 18L18 6M6 6l12 12"></path>
                </svg>
            </button>
        </div>

        <!-- Menu -->
        <div class="flex-1 px-4 py-6 space-y-1 overflow-y-auto">
            <div class="text-gray-400 text-xs uppercase font-semibold mb-4">MENU</div>

            <!-- Dashboard -->
            <a href="{{ route('dashboard') }}" class="flex items-center px-4 py-3 text-gray-700 hover:bg-indigo-50 rounded-lg {{ request()->routeIs('dashboard') ? 'bg-indigo-50 text-indigo-700' : '' }}">
                <svg class="w-5 h-5 mr-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M4 6h16M4 12h16M4 18h16"></path>
                </svg>
                <span>Dashboard</span>
            </a>    

            <div class="space-y-1">
                <p class="px-4 py-2 text-xs font-semibold text-gray-500">BERITA</p>
            
            <!-- Berita Admin -->
            @if(auth()->check() && auth()->user()->status == 1)
            <a href="{{ route('berita.index') }}" class="flex items-center px-4 py-3 text-gray-700 hover:bg-indigo-50 rounded-lg {{ request()->routeIs('berita.*') ? 'bg-indigo-50 text-indigo-700' : '' }}">
                <svg class="w-5 h-5 mr-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 20H5a2 2 0 01-2-2V6a2 2 0 012-2h10a2 2 0 012 2v1m2 13a2 2 0 01-2-2V7m2 13a2 2 0 002-2V9.5a2 2 0 00-.586-1.414l-3.5-3.5A2 2 0 0012.586 4H10"></path>
                </svg>
                <span>Berita</span>
            </a>
            @endif

            <a href="{{ route('user.news.index') }}" class="flex items-center px-4 py-3 text-gray-700 hover:bg-indigo-50 rounded-lg {{ request()->routeIs('user.news.*') ? 'bg-indigo-50 text-indigo-700' : '' }}">
                <svg class="w-5 h-5 mr-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 20H5a2 2 0 01-2-2V6a2 2 0 012-2h10a2 2 0 012 2v1m2 13a2 2 0 01-2-2V7m2 13a2 2 0 002-2V9.5a2 2 0 00-.586-1.414l-3.5-3.5A2 2 0 0012.586 4H10"></path>
                </svg>
                <span>Berita Saya</span>
            </a>

            <!-- Semua Berita - Hanya untuk User Biasa -->
            @if(auth()->check() && auth()->user()->status == 0)
                <a href="{{ route('user.all.news') }}" class="flex items-center px-4 py-3 text-gray-700 hover:bg-indigo-50 rounded-lg {{ request()->routeIs('user.all.news') ? 'bg-indigo-50 text-indigo-700' : '' }}">
                    <svg class="w-5 h-5 mr-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 20H5a2 2 0 01-2-2V6a2 2 0 012-2h10a2 2 0 012 2v1m2 13a2 2 0 01-2-2V7m2 13a2 2 0 002-2V9.5a2 2 0 00-.586-1.414l-3.5-3.5A2 2 0 0012.586 4H10"></path>
                    </svg>
                    <span>Semua Berita</span>
                </a>
            @endif

            <!-- Kategori - Hanya untuk Admin -->
            @if(auth()->check() && auth()->user()->status == 1)
            <a href="{{ route('admin.kategori.index') }}" class="flex items-center px-4 py-3 text-gray-700 hover:bg-indigo-50 rounded-lg {{ request()->routeIs('admin.kategori.*') ? 'bg-indigo-50 text-indigo-700' : '' }}">
                <svg class="w-5 h-5 mr-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M7 7h.01M7 3h5c.512 0 1.024.195 1.414.586l7 7a2 2 0 010 2.828l-7 7a2 2 0 01-2.828 0l-7-7A1.994 1.994 0 013 12V7a4 4 0 014-4z"></path>
                </svg>
                <span>Kategori</span>
            </a>
            @endif
            </div>

            <div class="space-y-1">
                <p class="px-4 py-2 text-xs font-semibold text-gray-500">MEDIA</p>
            <!-- Media -->
            <a href="{{ route('media.index') }}" class="flex items-center px-4 py-3 text-gray-700 hover:bg-indigo-50 rounded-lg {{ request()->routeIs('media.*') ? 'bg-indigo-50 text-indigo-700' : '' }}">
                <svg class="w-5 h-5 mr-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M4 16l4.586-4.586a2 2 0 012.828 0L16 16m-2-2l1.586-1.586a2 2 0 012.828 0L20 14m-6-6h.01M6 20h12a2 2 0 002-2V6a2 2 0 00-2-2H6a2 2 0 00-2 2v12a2 2 0 002 2z"></path>
                </svg>
                <span>Media Gambar</span>
            </a>

            <!-- Dokumen -->
            <a href="{{ route('admin.dokumen.index') }}" class="flex items-center px-4 py-3 text-gray-700 hover:bg-indigo-50 rounded-lg {{ request()->routeIs('admin.dokumen.*') ? 'bg-indigo-50 text-indigo-700' : '' }}">
                <svg class="w-5 h-5 mr-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M7 21h10a2 2 0 002-2V9.414a1 1 0 00-.293-.707l-5.414-5.414A1 1 0 0012.586 3H7a2 2 0 00-2 2v14a2 2 0 002 2z"></path>
                </svg>
                <span>Dokumen</span>
            </a>

            <!-- Video -->
            @if(auth()->check() && auth()->user()->status == 1)
            <a href="{{ route('admin.setting.video.index') }}" class="flex items-center px-4 py-3 text-gray-700 hover:bg-indigo-50 rounded-lg {{ request()->routeIs('admin.setting.video.*') ? 'bg-indigo-50 text-indigo-700' : '' }}">
                <svg class="w-5 h-5 mr-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 12a3 3 0 11-6 0 3 3 0 016 0z"></path>
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M2.458 12C3.732 7.943 7.523 5 12 5c4.478 0 8.268 2.943 9.542 7-1.274 4.057-5.064 7-9.542 7-4.477 0-8.268-2.943-9.542-7z"></path>
                </svg>
                <span>Video</span>
            </a>
            @endif
            </div>

            <div class="space-y-1">
                <p class="px-4 py-2 text-xs font-semibold text-gray-500">AGENDA</p>
            <!-- Agenda -->
            <a href="{{ route('admin.agenda.index') }}" class="flex items-center px-4 py-3 text-gray-700 hover:bg-indigo-50 rounded-lg {{ request()->routeIs('admin.agenda.*') ? 'bg-indigo-50 text-indigo-700' : '' }}">
                <svg class="w-5 h-5 mr-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M8 7V3m8 4V3m-9 8h10M5 21h14a2 2 0 002-2V7a2 2 0 00-2-2H5a2 2 0 00-2 2v12a2 2 0 002 2z"></path>
                </svg>
                <span>Agenda</span>
            </a>
            </div>

            <div class="space-y-1">
                <p class="px-4 py-2 text-xs font-semibold text-gray-500">KONTAK</p>
            <!-- Kontak -->
            <a href="{{ route('admin.kontak.index') }}" class="flex items-center px-4 py-3 text-gray-700 hover:bg-indigo-50 rounded-lg {{ request()->routeIs('admin.kontak.*') ? 'bg-indigo-50 text-indigo-700' : '' }}">
                <svg class="w-5 h-5 mr-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M3 8l7.89 5.26a2 2 0 002.22 0L21 8M5 19h14a2 2 0 002-2V7a2 2 0 00-2-2H5a2 2 0 00-2 2v10a2 2 0 002 2z"></path>
                </svg>
                <span>Kontak</span>
            </a>
            </div>
            
            <div class="space-y-1">
                <p class="px-4 py-2 text-xs font-semibold text-gray-500">DATA DUKUNG</p>
            <!-- Data Dukung -->
            @if(auth()->check() && auth()->user()->status == 1)
                <a href="{{ route('admin.data-dukung.index') }}" class="flex items-center px-4 py-3 text-gray-700 hover:bg-indigo-50 rounded-lg {{ request()->routeIs('admin.data-dukung.index') || request()->routeIs('admin.data-dukung.create') || request()->routeIs('admin.data-dukung.edit') || request()->routeIs('admin.data-dukung.show') ? 'bg-indigo-50 text-indigo-700' : '' }}">
                    <svg class="w-5 h-5 mr-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12h6m-6 4h6m2 5H7a2 2 0 01-2-2V5a2 2 0 012-2h5.586a1 1 0 01.707.293l5.414 5.414a1 1 0 01.293.707V19a2 2 0 01-2 2z"></path>
                    </svg>
                    <span>Data Dukung</span>
                </a>

                <a href="{{ route('admin.data-dukung.all') }}" class="flex items-center px-4 py-3 text-gray-700 hover:bg-indigo-50 rounded-lg {{ request()->routeIs('admin.data-dukung.all') ? 'bg-indigo-50 text-indigo-700' : '' }}">
                    <svg class="w-5 h-5 mr-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 11H5m14 0a2 2 0 012 2v6a2 2 0 01-2 2H5a2 2 0 01-2-2v-6a2 2 0 012-2m14 0V9a2 2 0 00-2-2M5 11V9a2 2 0 012-2m0 0V5a2 2 0 012-2h6a2 2 0 012 2v3M7 7h10"></path>
                    </svg>
                    <span>Semua Data Dukung</span>
                </a>
            @else
                <a href="{{ route('user.data-dukung.index') }}" class="flex items-center px-4 py-3 text-gray-700 hover:bg-indigo-50 rounded-lg {{ request()->routeIs('user.data-dukung.index') ? 'bg-indigo-50 text-indigo-700' : '' }}">
                    <svg class="w-5 h-5 mr-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12h6m-6 4h6m2 5H7a2 2 0 01-2-2V5a2 2 0 012-2h5.586a1 1 0 01.707.293l5.414 5.414a1 1 0 01.293.707V19a2 2 0 01-2 2z"></path>
                    </svg>
                    <span>Data Dukung Saya</span>
                </a>

                <a href="{{ route('user.data-dukung.all') }}" class="flex items-center px-4 py-3 text-gray-700 hover:bg-indigo-50 rounded-lg {{ request()->routeIs('user.data-dukung.all') ? 'bg-indigo-50 text-indigo-700' : '' }}">
                    <svg class="w-5 h-5 mr-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 11H5m14 0a2 2 0 012 2v6a2 2 0 01-2 2H5a2 2 0 01-2-2v-6a2 2 0 012-2m14 0V9a2 2 0 00-2-2M5 11V9a2 2 0 012-2m0 0V5a2 2 0 012-2h6a2 2 0 012 2v3M7 7h10"></path>
                    </svg>
                    <span>Semua Data Dukung</span>
                </a>
            @endif

            <!-- Klaster -->
            @if(auth()->check() && auth()->user()->status == 1)
            <a href="{{ route('admin.klaster.index') }}" class="flex items-center px-4 py-3 text-gray-700 hover:bg-indigo-50 rounded-lg {{ request()->routeIs('admin.klaster.*') ? 'bg-indigo-50' : '' }}">
                <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M4 5a1 1 0 011-1h4a1 1 0 011 1v4a1 1 0 01-1 1H5a1 1 0 01-1-1V5zm11 0a1 1 0 011-1h4a1 1 0 011 1v4a1 1 0 01-1 1h-4a1 1 0 01-1-1V5zM4 15a1 1 0 011-1h4a1 1 0 011 1v4a1 1 0 01-1 1H5a1 1 0 01-1-1v-4zm11 0a1 1 0 011-1h4a1 1 0 011 1v4a1 1 0 01-1 1h-4a1 1 0 01-1-1v-4z"></path>
                </svg>
                <span class="ml-3">Klaster</span>
            </a>
            @endif

            <!-- Indikator -->
            @if(auth()->check() && auth()->user()->status == 1)
            <a href="{{ route('admin.indikator.index') }}" class="flex items-center px-4 py-3 text-gray-700 hover:bg-indigo-50 rounded-lg {{ request()->routeIs('admin.indikator.*') ? 'bg-indigo-50' : '' }}">
                <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 5H7a2 2 0 00-2 2v12a2 2 0 002 2h10a2 2 0 002-2V7a2 2 0 00-2-2h-2M9 5a2 2 0 002 2h2a2 2 0 002-2M9 5a2 2 0 012-2h2a2 2 0 012 2"></path>
                </svg>
                <span class="ml-3">Indikator</span>
            </a>
            @endif

            <!-- OPD -->
            @if(auth()->check() && auth()->user()->status == 1)
            <a href="{{ route('admin.opd.index') }}" class="flex items-center px-4 py-3 text-gray-700 hover:bg-indigo-50 rounded-lg {{ request()->routeIs('admin.opd.*') ? 'bg-indigo-50 text-indigo-700' : '' }}">
                <svg class="w-5 h-5 mr-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 21V5a2 2 0 00-2-2H7a2 2 0 00-2 2v16m14 0h2m-2 0h-5m-9 0H3m2 0h5M9 7h1m-1 4h1m4-4h1m-1 4h1m-5 10v-5a1 1 0 011-1h2a1 1 0 011 1v5m-4 0h4"></path>
                </svg>
                <span>Perangkat Daerah</span>
            </a>
            @endif

            <!-- Program Kerja -->
            @if(auth()->check())
                <a href="{{ route('admin.program-kerja.index') }}" class="flex items-center px-4 py-3 text-gray-700 hover:bg-indigo-50 rounded-lg {{ request()->routeIs('admin.program-kerja.*') ? 'bg-indigo-50 text-indigo-700' : '' }}">
                    <svg class="w-5 h-5 mr-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 5H7a2 2 0 00-2 2v12a2 2 0 002 2h10a2 2 0 002-2V7a2 2 0 00-2-2h-2M9 5a2 2 0 002 2h2a2 2 0 002-2M9 5a2 2 0 012-2h2a2 2 0 012 2m-6 9l2 2 4-4"></path>
                    </svg>
                    <span>Program Kerja</span>
                </a>
            @endif
            </div>

            @if(auth()->check() && auth()->user()->status == 1)
                <div class="space-y-1">
                    <p class="px-4 py-2 text-xs font-semibold text-gray-500">PENGATURAN</p>
                    
                    <!-- Setting Dinamis -->
                    <a href="{{ route('admin.setting.statis.index') }}" class="flex items-center px-4 py-3 text-gray-700 hover:bg-indigo-50 rounded-lg {{ request()->routeIs('admin.setting.statis.*') ? 'bg-indigo-50 text-indigo-700' : '' }}">
                        <svg class="w-5 h-5 mr-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 11H5m14 0a2 2 0 012 2v6a2 2 0 01-2 2H5a2 2 0 01-2-2v-6a2 2 0 012-2m14 0V9a2 2 0 00-2-2M5 11V9a2 2 0 012-2m0 0V5a2 2 0 012-2h6a2 2 0 012 2M7 7h10"></path>
                        </svg>
                        <span>Setting Dinamis</span>
                    </a>
                </div>
            @endif

            @if(auth()->check() && auth()->user()->status == 1)
            <a href="{{ route('admin.users.index') }}" class="flex items-center px-4 py-3 text-gray-700 hover:bg-indigo-50 rounded-lg {{ request()->routeIs('admin.users.*') ? 'bg-indigo-50 text-indigo-700' : '' }}">
                <svg class="w-5 h-5 mr-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 4.354a4 4 0 110 5.292M15 21H3v-1a6 6 0 0112 0v1zm0 0h6v-1a6 6 0 00-9-5.197M13 7a4 4 0 11-8 0 4 4 0 018 0z"></path>
                </svg>
                <span>Manajemen User</span>
            </a>
            @endif

            {{-- <!-- User Profile -->
            <a href="{{ route('profile.edit') }}" class="flex items-center px-4 py-3 text-gray-700 hover:bg-indigo-50 rounded-lg">
                <svg class="w-5 h-5 mr-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M16 7a4 4 0 11-8 0 4 4 0 018 0zM12 14a7 7 0 00-7 7h14a7 7 0 00-7-7z"></path>
                </svg>
                <span>User Profile</span>
            </a> --}}
        </div>

        <!-- Support Section -->
        <div class="px-4 py-6 border-t border-gray-200">
            <div class="text-gray-400 text-xs uppercase font-semibold mb-4">SUPPORT</div>
            
            <!-- Logout -->
            <form method="POST" action="{{ route('logout') }}">
                @csrf
                <button type="submit" class="w-full flex items-center px-4 py-3 text-gray-700 hover:bg-indigo-50 rounded-lg">
                    <svg class="w-5 h-5 mr-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M17 16l4-4m0 0l-4-4m4 4H7m6 4v1a3 3 0 01-3 3H6a3 3 0 01-3-3V7a3 3 0 013-3h4a3 3 0 013 3v1"></path>
                    </svg>
                    <span>Logout</span>
                </button>
            </form>
        </div>
    </div>
</nav>

<!-- Overlay untuk Mobile -->
<div x-show="open" 
     class="fixed inset-0 z-40 bg-black bg-opacity-50 lg:hidden"
     @click="open = false"
     x-transition:enter="transition-opacity ease-in-out duration-300"
     x-transition:enter-start="opacity-0"
     x-transition:enter-end="opacity-100"
     x-transition:leave="transition-opacity ease-in-out duration-300"
     x-transition:leave-start="opacity-100"
     x-transition:leave-end="opacity-0">
</div>
