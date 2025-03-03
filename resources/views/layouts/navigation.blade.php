<nav x-data="{ open: false }" class="bg-white border-r border-gray-200 w-60 fixed h-full">
    <div class="flex flex-col h-full">
        <!-- Logo -->
        <div class="flex items-center justify-center h-20 border-b border-gray-200">
            <a href="{{ route('dashboard') }}" class="flex items-center">
                <span class="text-xl font-bold text-indigo-700">Admin</span>
            </a>
        </div>

        <!-- Menu -->
        <div class="flex-1 px-4 py-6 space-y-1">
            <div class="text-gray-400 text-xs uppercase font-semibold mb-4">MENU</div>
            
            <!-- Dashboard -->
            <a href="{{ route('dashboard') }}" class="flex items-center px-4 py-3 text-gray-700 hover:bg-indigo-50 rounded-lg {{ request()->routeIs('dashboard') ? 'bg-indigo-50 text-indigo-700' : '' }}">
                <svg class="w-5 h-5 mr-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M4 6h16M4 12h16M4 18h16"></path>
                </svg>
                <span>Dashboard</span>
            </a>

            <!-- Media -->
            <a href="{{ route('media.index') }}" class="flex items-center px-4 py-3 text-gray-700 hover:bg-indigo-50 rounded-lg {{ request()->routeIs('media.*') ? 'bg-indigo-50 text-indigo-700' : '' }}">
                <svg class="w-5 h-5 mr-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M4 16l4.586-4.586a2 2 0 012.828 0L16 16m-2-2l1.586-1.586a2 2 0 012.828 0L20 14m-6-6h.01M6 20h12a2 2 0 002-2V6a2 2 0 00-2-2H6a2 2 0 00-2 2v12a2 2 0 002 2z"></path>
                </svg>
                <span>Media</span>
            </a>

            <!-- Berita -->
            <a href="{{ route('berita.index') }}" class="flex items-center px-4 py-3 text-gray-700 hover:bg-indigo-50 rounded-lg {{ request()->routeIs('berita.*') ? 'bg-indigo-50 text-indigo-700' : '' }}">
                <svg class="w-5 h-5 mr-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 20H5a2 2 0 01-2-2V6a2 2 0 012-2h10a2 2 0 012 2v1m2 13a2 2 0 01-2-2V7m2 13a2 2 0 002-2V9.5a2 2 0 00-.586-1.414l-3.5-3.5A2 2 0 0012.586 4H10"></path>
                </svg>
                <span>Berita</span>
            </a>

            <!-- Kategori -->
            <a href="{{ route('kategori.index') }}" class="flex items-center px-4 py-3 text-gray-700 hover:bg-indigo-50 rounded-lg {{ request()->routeIs('kategori.*') ? 'bg-indigo-50 text-indigo-700' : '' }}">
                <svg class="w-5 h-5 mr-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M7 7h.01M7 3h5c.512 0 1.024.195 1.414.586l7 7a2 2 0 010 2.828l-7 7a2 2 0 01-2.828 0l-7-7A1.994 1.994 0 013 12V7a4 4 0 014-4z"></path>
                </svg>
                <span>Kategori</span>
            </a>

            <!-- Dokumen -->
            <a href="{{ route('admin.dokumen.index') }}" class="flex items-center px-4 py-3 text-gray-700 hover:bg-indigo-50 rounded-lg {{ request()->routeIs('admin.dokumen.*') ? 'bg-indigo-50 text-indigo-700' : '' }}">
                <svg class="w-5 h-5 mr-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M7 21h10a2 2 0 002-2V9.414a1 1 0 00-.293-.707l-5.414-5.414A1 1 0 0012.586 3H7a2 2 0 00-2 2v14a2 2 0 002 2z"></path>
                </svg>
                <span>Dokumen</span>
            </a>

            <!-- Agenda -->
            <a href="{{ route('agenda.index') }}" class="flex items-center px-4 py-3 text-gray-700 hover:bg-indigo-50 rounded-lg {{ request()->routeIs('agenda.*') ? 'bg-indigo-50 text-indigo-700' : '' }}">
                <svg class="w-5 h-5 mr-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M8 7V3m8 4V3m-9 8h10M5 21h14a2 2 0 002-2V7a2 2 0 00-2-2H5a2 2 0 00-2 2v12a2 2 0 002 2z"></path>
                </svg>
                <span>Agenda</span>
            </a>

            <!-- Kontak -->
            <a href="{{ route('admin.kontak.index') }}" class="flex items-center px-4 py-3 text-gray-700 hover:bg-indigo-50 rounded-lg {{ request()->routeIs('admin.kontak.*') ? 'bg-indigo-50 text-indigo-700' : '' }}">
                <svg class="w-5 h-5 mr-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M3 8l7.89 5.26a2 2 0 002.22 0L21 8M5 19h14a2 2 0 002-2V7a2 2 0 00-2-2H5a2 2 0 00-2 2v10a2 2 0 002 2z"></path>
                </svg>
                <span>Kontak</span>
            </a>

            <!-- Setting -->
            <a href="{{ route('admin.setting.index') }}" class="flex items-center px-4 py-3 text-gray-700 hover:bg-indigo-50 rounded-lg {{ request()->routeIs('admin.setting.*') ? 'bg-indigo-50 text-indigo-700' : '' }}">
                <svg class="w-5 h-5 mr-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M10.325 4.317c.426-1.756 2.924-1.756 3.35 0a1.724 1.724 0 002.573 1.066c1.543-.94 3.31.826 2.37 2.37a1.724 1.724 0 001.065 2.572c1.756.426 1.756 2.924 0 3.35a1.724 1.724 0 00-1.066 2.573c.94 1.543-.826 3.31-2.37 2.37a1.724 1.724 0 00-2.572 1.065c-.426 1.756-2.924 1.756-3.35 0a1.724 1.724 0 00-2.573-1.066c-1.543.94-3.31-.826-2.37-2.37a1.724 1.724 0 00-1.065-2.572c-1.756-.426-1.756-2.924 0-3.35a1.724 1.724 0 001.066-2.573c-.94-1.543.826-3.31 2.37-2.37.996.608 2.296.07 2.572-1.065z"></path>
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 12a3 3 0 11-6 0 3 3 0 016 0z"></path>
                </svg>
                <span>Setting</span>
            </a>

            <!-- User Profile -->
            <a href="{{ route('profile.edit') }}" class="flex items-center px-4 py-3 text-gray-700 hover:bg-indigo-50 rounded-lg">
                <svg class="w-5 h-5 mr-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M16 7a4 4 0 11-8 0 4 4 0 018 0zM12 14a7 7 0 00-7 7h14a7 7 0 00-7-7z"></path>
                </svg>
                <span>User Profile</span>
            </a>
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
