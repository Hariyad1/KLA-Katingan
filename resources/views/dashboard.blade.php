<x-app-layout>
    <x-slot name="header">
        <div class="flex justify-between items-center">
            <h2 class="font-semibold text-xl text-gray-800 leading-tight">
                {{ __('Dashboard') }}
            </h2>
            <div class="text-sm text-gray-600">
                {{ now()->format('l, d F Y') }}
            </div>
        </div>
    </x-slot>

    <div class="py-6">
        <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8">
            <!-- Welcome Section -->
            <div class="bg-gradient-to-r from-green-600 to-green-700 rounded-2xl shadow-lg mb-6 p-6">
                <div class="flex items-center justify-between">
                    <div class="text-white">
                        <h3 class="text-2xl font-bold">Selamat Datang, {{ Auth::user()->name }}!</h3>
                        <p class="mt-1 text-green-100">Selamat datang kembali di Dashboard KLA Katingan</p>
                    </div>
                    <div class="hidden md:block">
                        <img src="{{ asset('images/logo_kla.png') }}" alt="Logo" class="h-16 w-auto">
                    </div>
                </div>
            </div>

            <!-- Statistics Cards -->
            <div class="grid grid-cols-1 md:grid-cols-3 gap-6 mb-6">
                <!-- Berita Card -->
                <div class="bg-white rounded-2xl shadow-md p-6 hover:shadow-lg transition-shadow duration-300">
                    <div class="flex items-center justify-between mb-4">
                        <div class="bg-green-100 rounded-lg p-3">
                            <svg xmlns="http://www.w3.org/2000/svg" class="h-6 w-6 text-green-600" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 20H5a2 2 0 01-2-2V6a2 2 0 012-2h10a2 2 0 012 2v1m2 13a2 2 0 01-2-2V7m2 13a2 2 0 002-2V9.5a2.5 2.5 0 00-2.5-2.5H14" />
                            </svg>
                        </div>
                        <span class="text-green-500 flex items-center">
                            <svg xmlns="http://www.w3.org/2000/svg" class="h-5 w-5 mr-1" viewBox="0 0 20 20" fill="currentColor">
                                <path fill-rule="evenodd" d="M5.293 9.707a1 1 0 010-1.414l4-4a1 1 0 011.414 0l4 4a1 1 0 01-1.414 1.414L11 7.414V15a1 1 0 11-2 0V7.414L6.707 9.707a1 1 0 01-1.414 0z" clip-rule="evenodd" />
                            </svg>
                            +{{ \App\Models\News::whereDate('created_at', today())->count() }} hari ini
                        </span>
                    </div>
                    <h3 class="text-gray-600 font-medium mb-2">Total Berita</h3>
                    <div class="flex items-baseline">
                        <p class="text-3xl font-bold text-gray-900">{{ \App\Models\News::count() }}</p>
                        <span class="ml-2 text-sm text-gray-500">artikel</span>
                    </div>
                </div>

                <!-- Agenda Card -->
                <div class="bg-white rounded-2xl shadow-md p-6 hover:shadow-lg transition-shadow duration-300">
                    <div class="flex items-center justify-between mb-4">
                        <div class="bg-blue-100 rounded-lg p-3">
                            <svg xmlns="http://www.w3.org/2000/svg" class="h-6 w-6 text-blue-600" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M8 7V3m8 4V3m-9 8h10M5 21h14a2 2 0 002-2V7a2 2 0 00-2-2H5a2 2 0 00-2 2v12a2 2 0 002 2z" />
                            </svg>
                        </div>
                        <span class="text-{{ \App\Models\Agenda::whereDate('created_at', today())->count() > 0 ? 'blue' : 'gray' }}-500 flex items-center">
                            @if(\App\Models\Agenda::whereDate('created_at', today())->count() > 0)
                                <svg xmlns="http://www.w3.org/2000/svg" class="h-5 w-5 mr-1" viewBox="0 0 20 20" fill="currentColor">
                                    <path fill-rule="evenodd" d="M5.293 9.707a1 1 0 010-1.414l4-4a1 1 0 011.414 0l4 4a1 1 0 01-1.414 1.414L11 7.414V15a1 1 0 11-2 0V7.414L6.707 9.707a1 1 0 01-1.414 0z" clip-rule="evenodd" />
                                </svg>
                            @endif
                            {{ \App\Models\Agenda::whereDate('created_at', today())->count() }} hari ini
                        </span>
                    </div>
                    <h3 class="text-gray-600 font-medium mb-2">Total Agenda</h3>
                    <div class="flex items-baseline">
                        <p class="text-3xl font-bold text-gray-900">{{ \App\Models\Agenda::count() }}</p>
                        <span class="ml-2 text-sm text-gray-500">kegiatan</span>
                    </div>
                </div>

                <!-- Pengunjung Card -->
                <div class="bg-white rounded-2xl shadow-md p-6 hover:shadow-lg transition-shadow duration-300">
                    <div class="flex items-center justify-between mb-4">
                        <div class="bg-purple-100 rounded-lg p-3">
                            <svg xmlns="http://www.w3.org/2000/svg" class="h-6 w-6 text-purple-600" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M17 20h5v-2a3 3 0 00-5.356-1.857M17 20H7m10 0v-2c0-.656-.126-1.283-.356-1.857M7 20H2v-2a3 3 0 015.356-1.857M7 20v-2c0-.656.126-1.283.356-1.857m0 0a5.002 5.002 0 019.288 0M15 7a3 3 0 11-6 0 3 3 0 016 0zm6 3a2 2 0 11-4 0 2 2 0 014 0zM7 10a2 2 0 11-4 0 2 2 0 014 0z" />
                            </svg>
                        </div>
                        <span class="text-purple-500 flex items-center">
                            <svg xmlns="http://www.w3.org/2000/svg" class="h-5 w-5 mr-1" viewBox="0 0 20 20" fill="currentColor">
                                <path fill-rule="evenodd" d="M5.293 9.707a1 1 0 010-1.414l4-4a1 1 0 011.414 0l4 4a1 1 0 01-1.414 1.414L11 7.414V15a1 1 0 11-2 0V7.414L6.707 9.707a1 1 0 01-1.414 0z" clip-rule="evenodd" />
                            </svg>
                            +{{ \App\Models\Statistic::whereDate('created_at', today())->count() }} hari ini
                        </span>
                    </div>
                    <h3 class="text-gray-600 font-medium mb-2">Total Pengunjung</h3>
                    <div class="flex items-baseline">
                        <p class="text-3xl font-bold text-gray-900">{{ \App\Models\Statistic::count() }}</p>
                        <span class="ml-2 text-sm text-gray-500">pengunjung</span>
                    </div>
                </div>

                <!-- Gambar Card -->
                <div class="bg-white rounded-2xl shadow-md p-6 hover:shadow-lg transition-shadow duration-300">
                    <div class="flex items-center justify-between mb-4">
                        <div class="bg-pink-100 rounded-lg p-3">
                            <svg xmlns="http://www.w3.org/2000/svg" class="h-6 w-6 text-pink-600" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M4 16l4.586-4.586a2 2 0 012.828 0L16 16m-2-2l1.586-1.586a2 2 0 012.828 0L20 14m-6-6h.01M6 20h12a2 2 0 002-2V6a2 2 0 00-2-2H6a2 2 0 00-2 2v12a2 2 0 002 2z" />
                            </svg>
                        </div>
                        <span class="text-pink-500 flex items-center">
                            <svg xmlns="http://www.w3.org/2000/svg" class="h-5 w-5 mr-1" viewBox="0 0 20 20" fill="currentColor">
                                <path fill-rule="evenodd" d="M5.293 9.707a1 1 0 010-1.414l4-4a1 1 0 011.414 0l4 4a1 1 0 01-1.414 1.414L11 7.414V15a1 1 0 11-2 0V7.414L6.707 9.707a1 1 0 01-1.414 0z" clip-rule="evenodd" />
                            </svg>
                            +{{ \App\Models\Media::where('file', 'like', '%.jpg')
                                ->orWhere('file', 'like', '%.jpeg')
                                ->orWhere('file', 'like', '%.png')
                                ->orWhere('file', 'like', '%.gif')
                                ->whereDate('created_at', today())
                                ->count() }} hari ini
                        </span>
                    </div>
                    <h3 class="text-gray-600 font-medium mb-2">Total Gambar</h3>
                    <div class="flex items-baseline">
                        <p class="text-3xl font-bold text-gray-900">{{ \App\Models\Media::where('file', 'like', '%.jpg')
                            ->orWhere('file', 'like', '%.jpeg')
                            ->orWhere('file', 'like', '%.png')
                            ->orWhere('file', 'like', '%.gif')
                            ->count() }}</p>
                        <span class="ml-2 text-sm text-gray-500">file</span>
                    </div>
                </div>

                <!-- Video Card -->
                <div class="bg-white rounded-2xl shadow-md p-6 hover:shadow-lg transition-shadow duration-300">
                    <div class="flex items-center justify-between mb-4">
                        <div class="bg-red-100 rounded-lg p-3">
                            <svg xmlns="http://www.w3.org/2000/svg" class="h-6 w-6 text-red-600" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 10l4.553-2.276A1 1 0 0121 8.618v6.764a1 1 0 01-1.447.894L15 14M5 18h8a2 2 0 002-2V8a2 2 0 00-2-2H5a2 2 0 00-2 2v8a2 2 0 002 2z" />
                            </svg>
                        </div>
                        <span class="text-red-500 flex items-center">
                            <svg xmlns="http://www.w3.org/2000/svg" class="h-5 w-5 mr-1" viewBox="0 0 20 20" fill="currentColor">
                                <path fill-rule="evenodd" d="M5.293 9.707a1 1 0 010-1.414l4-4a1 1 0 011.414 0l4 4a1 1 0 01-1.414 1.414L11 7.414V15a1 1 0 11-2 0V7.414L6.707 9.707a1 1 0 01-1.414 0z" clip-rule="evenodd" />
                            </svg>
                            +{{ \App\Models\Media::where('file', 'like', '%.mp4')
                                ->orWhere('file', 'like', '%.avi')
                                ->orWhere('file', 'like', '%.mov')
                                ->orWhere('file', 'like', '%.wmv')
                                ->whereDate('created_at', today())
                                ->count() }} hari ini
                        </span>
                    </div>
                    <h3 class="text-gray-600 font-medium mb-2">Total Video</h3>
                    <div class="flex items-baseline">
                        <p class="text-3xl font-bold text-gray-900">{{ \App\Models\Media::where('file', 'like', '%.mp4')
                            ->orWhere('file', 'like', '%.avi')
                            ->orWhere('file', 'like', '%.mov')
                            ->orWhere('file', 'like', '%.wmv')
                            ->count() }}</p>
                        <span class="ml-2 text-sm text-gray-500">file</span>
                    </div>
                </div>

                <!-- Dokumen Card -->
                <div class="bg-white rounded-2xl shadow-md p-6 hover:shadow-lg transition-shadow duration-300">
                    <div class="flex items-center justify-between mb-4">
                        <div class="bg-yellow-100 rounded-lg p-3">
                            <svg xmlns="http://www.w3.org/2000/svg" class="h-6 w-6 text-yellow-600" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12h6m-6 4h6m2 5H7a2 2 0 01-2-2V5a2 2 0 012-2h5.586a1 1 0 01.707.293l5.414 5.414a1 1 0 01.293.707V19a2 2 0 01-2 2z" />
                            </svg>
                        </div>
                        <span class="text-yellow-500 flex items-center">
                            <svg xmlns="http://www.w3.org/2000/svg" class="h-5 w-5 mr-1" viewBox="0 0 20 20" fill="currentColor">
                                <path fill-rule="evenodd" d="M5.293 9.707a1 1 0 010-1.414l4-4a1 1 0 011.414 0l4 4a1 1 0 01-1.414 1.414L11 7.414V15a1 1 0 11-2 0V7.414L6.707 9.707a1 1 0 01-1.414 0z" clip-rule="evenodd" />
                            </svg>
                            +{{ \App\Models\Media::where('file', 'like', '%.pdf')
                                ->orWhere('file', 'like', '%.doc')
                                ->orWhere('file', 'like', '%.docx')
                                ->orWhere('file', 'like', '%.xls')
                                ->orWhere('file', 'like', '%.xlsx')
                                ->orWhere('file', 'like', '%.ppt')
                                ->orWhere('file', 'like', '%.pptx')
                                ->whereDate('created_at', today())
                                ->count() }} hari ini
                        </span>
                    </div>
                    <h3 class="text-gray-600 font-medium mb-2">Total Dokumen</h3>
                    <div class="flex items-baseline">
                        <p class="text-3xl font-bold text-gray-900">{{ \App\Models\Media::where('file', 'like', '%.pdf')
                            ->orWhere('file', 'like', '%.doc')
                            ->orWhere('file', 'like', '%.docx')
                            ->orWhere('file', 'like', '%.xls')
                            ->orWhere('file', 'like', '%.xlsx')
                            ->orWhere('file', 'like', '%.ppt')
                            ->orWhere('file', 'like', '%.pptx')
                            ->count() }}</p>
                        <span class="ml-2 text-sm text-gray-500">file</span>
                    </div>
                </div>

                <!-- Kontak Card -->
                <div class="bg-white rounded-2xl shadow-md p-6 hover:shadow-lg transition-shadow duration-300">
                    <div class="flex items-center justify-between mb-4">
                        <div class="bg-indigo-100 rounded-lg p-3">
                            <svg xmlns="http://www.w3.org/2000/svg" class="h-6 w-6 text-indigo-600" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M3 5a2 2 0 012-2h3.28a1 1 0 01.948.684l1.498 4.493a1 1 0 01-.502 1.21l-2.257 1.13a11.042 11.042 0 005.516 5.516l1.13-2.257a1 1 0 011.21-.502l4.493 1.498a1 1 0 01.684.949V19a2 2 0 01-2 2h-1C9.716 21 3 14.284 3 6V5z" />
                            </svg>
                        </div>
                        <span class="text-indigo-500 flex items-center">
                            <svg xmlns="http://www.w3.org/2000/svg" class="h-5 w-5 mr-1" viewBox="0 0 20 20" fill="currentColor">
                                <path fill-rule="evenodd" d="M5.293 9.707a1 1 0 010-1.414l4-4a1 1 0 011.414 0l4 4a1 1 0 01-1.414 1.414L11 7.414V15a1 1 0 11-2 0V7.414L6.707 9.707a1 1 0 01-1.414 0z" clip-rule="evenodd" />
                            </svg>
                            +{{ \App\Models\Contact::whereDate('created_at', today())->count() }} hari ini
                        </span>
                    </div>
                    <h3 class="text-gray-600 font-medium mb-2">Total Kontak</h3>
                    <div class="flex items-baseline">
                        <p class="text-3xl font-bold text-gray-900">{{ \App\Models\Contact::count() }}</p>
                        <span class="ml-2 text-sm text-gray-500">kontak</span>
                    </div>
                </div>

                <!-- Data Dukung Card -->
                <div class="bg-white rounded-2xl shadow-md p-6 hover:shadow-lg transition-shadow duration-300">
                    <div class="flex items-center justify-between mb-4">
                        <div class="bg-orange-100 rounded-lg p-3">
                            <svg xmlns="http://www.w3.org/2000/svg" class="h-6 w-6 text-orange-600" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12h6m-6 4h6m2 5H7a2 2 0 01-2-2V5a2 2 0 012-2h5.586a1 1 0 01.707.293l5.414 5.414a1 1 0 01.293.707V19a2 2 0 01-2 2z" />
                            </svg>
                        </div>
                        <span class="text-orange-500 flex items-center">
                            <svg xmlns="http://www.w3.org/2000/svg" class="h-5 w-5 mr-1" viewBox="0 0 20 20" fill="currentColor">
                                <path fill-rule="evenodd" d="M5.293 9.707a1 1 0 010-1.414l4-4a1 1 0 011.414 0l4 4a1 1 0 01-1.414 1.414L11 7.414V15a1 1 0 11-2 0V7.414L6.707 9.707a1 1 0 01-1.414 0z" clip-rule="evenodd" />
                            </svg>
                            +{{ \App\Models\DataDukung::whereDate('created_at', today())->count() }} hari ini
                        </span>
                    </div>
                    <h3 class="text-gray-600 font-medium mb-2">Total Data Dukung</h3>
                    <div class="flex items-baseline">
                        <p class="text-3xl font-bold text-gray-900">{{ \App\Models\DataDukung::count() }}</p>
                        <span class="ml-2 text-sm text-gray-500">data</span>
                    </div>
                </div>

                <!-- Data Dukung Files Card -->
                <div class="bg-white rounded-2xl shadow-md p-6 hover:shadow-lg transition-shadow duration-300">
                    <div class="flex items-center justify-between mb-4">
                        <div class="bg-teal-100 rounded-lg p-3">
                            <svg xmlns="http://www.w3.org/2000/svg" class="h-6 w-6 text-teal-600" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M8 7v8a2 2 0 002 2h6M8 7V5a2 2 0 012-2h4.586a1 1 0 01.707.293l4.414 4.414a1 1 0 01.293.707V15a2 2 0 01-2 2h-2M8 7H6a2 2 0 00-2 2v10a2 2 0 002 2h8a2 2 0 002-2v-2" />
                            </svg>
                        </div>
                        <span class="text-teal-500 flex items-center">
                            <svg xmlns="http://www.w3.org/2000/svg" class="h-5 w-5 mr-1" viewBox="0 0 20 20" fill="currentColor">
                                <path fill-rule="evenodd" d="M5.293 9.707a1 1 0 010-1.414l4-4a1 1 0 011.414 0l4 4a1 1 0 01-1.414 1.414L11 7.414V15a1 1 0 11-2 0V7.414L6.707 9.707a1 1 0 01-1.414 0z" clip-rule="evenodd" />
                            </svg>
                            +{{ \App\Models\DataDukungFile::whereDate('created_at', today())->count() }} hari ini
                        </span>
                    </div>
                    <h3 class="text-gray-600 font-medium mb-2">Total File Data Dukung</h3>
                    <div class="flex items-baseline">
                        <p class="text-3xl font-bold text-gray-900">{{ \App\Models\DataDukungFile::count() }}</p>
                        <span class="ml-2 text-sm text-gray-500">file</span>
                    </div>
                </div>
            </div>

            {{-- <!-- Recent Activities -->
            <div class="bg-white rounded-2xl shadow-md p-6">
                <div class="flex items-center justify-between mb-6">
                    <h3 class="text-lg font-semibold text-gray-900">Aktivitas Terbaru</h3>
                    <a href="#" class="text-green-600 hover:text-green-700 text-sm font-medium">Lihat Semua</a>
                </div>
                <div class="space-y-4">
                    @php
                        $activities = collect();
                        
                        // Mengumpulkan berita
                        $news = \App\Models\News::with('creator')
                            ->latest()
                            ->take(5)
                            ->get()
                            ->map(function($item) {
                                return [
                                    'type' => 'berita',
                                    'title' => $item->title,
                                    'creator' => $item->creator->name,
                                    'date' => $item->created_at,
                                    'icon' => 'newspaper',
                                    'color' => 'green'
                                ];
                            });
                        $activities = $activities->concat($news);

                        // Mengumpulkan agenda
                        $agendas = \App\Models\Agenda::with('creator')
                            ->latest()
                            ->take(5)
                            ->get()
                            ->map(function($item) {
                                return [
                                    'type' => 'agenda',
                                    'title' => $item->title,
                                    'creator' => $item->creator->name,
                                    'date' => $item->created_at,
                                    'icon' => 'calendar',
                                    'color' => 'blue'
                                ];
                            });
                        $activities = $activities->concat($agendas);

                        // Mengumpulkan kategori
                        $categories = \App\Models\Kategori::latest()
                            ->take(5)
                            ->get()
                            ->map(function($item) {
                                return [
                                    'type' => 'kategori',
                                    'title' => $item->name,
                                    'creator' => 'Admin',
                                    'date' => $item->created_at,
                                    'icon' => 'tag',
                                    'color' => 'purple'
                                ];
                            });
                        $activities = $activities->concat($categories);

                        // Mengumpulkan media
                        $media = \App\Models\Media::latest()
                            ->take(5)
                            ->get()
                            ->map(function($item) {
                                return [
                                    'type' => 'media',
                                    'title' => $item->title ?? $item->file_name,
                                    'creator' => 'Admin',
                                    'date' => $item->created_at,
                                    'icon' => 'photograph',
                                    'color' => 'pink'
                                ];
                            });
                        $activities = $activities->concat($media);

                        // Mengumpulkan data dukung
                        $dataDukung = \App\Models\DataDukung::with('creator')
                            ->latest()
                            ->take(5)
                            ->get()
                            ->map(function($item) {
                                return [
                                    'type' => 'data dukung',
                                    'title' => $item->title ?? $item->file_name,
                                    'creator' => $item->creator->name ?? 'Admin',
                                    'date' => $item->created_at,
                                    'icon' => 'document',
                                    'color' => 'yellow'
                                ];
                            });
                        $activities = $activities->concat($dataDukung);

                        // Mengumpulkan kontak
                        $contacts = \App\Models\Contact::latest()
                            ->take(5)
                            ->get()
                            ->map(function($item) {
                                return [
                                    'type' => 'kontak',
                                    'title' => $item->name,
                                    'creator' => 'Admin',
                                    'date' => $item->created_at,
                                    'icon' => 'phone',
                                    'color' => 'indigo'
                                ];
                            });
                        $activities = $activities->concat($contacts);

                        // Mengumpulkan setting
                        $settings = \App\Models\Setting::latest()
                            ->take(5)
                            ->get()
                            ->map(function($item) {
                                return [
                                    'type' => 'pengaturan',
                                    'title' => 'Update ' . $item->key,
                                    'creator' => 'Admin',
                                    'date' => $item->created_at,
                                    'icon' => 'cog',
                                    'color' => 'gray'
                                ];
                            });
                        $activities = $activities->concat($settings);

                        // Mengurutkan semua aktivitas berdasarkan tanggal terbaru
                        $activities = $activities->sortByDesc('date')->take(5);
                    @endphp

                    @foreach($activities as $activity)
                        <div class="flex items-start space-x-4">
                            <div class="flex-shrink-0">
                                <div class="w-10 h-10 rounded-full bg-{{ $activity['color'] }}-100 flex items-center justify-center">
                                    @if($activity['icon'] === 'newspaper')
                                        <svg xmlns="http://www.w3.org/2000/svg" class="h-5 w-5 text-{{ $activity['color'] }}-600" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 20H5a2 2 0 01-2-2V6a2 2 0 012-2h10a2 2 0 012 2v1m2 13a2 2 0 01-2-2V7m2 13a2 2 0 002-2V9.5a2.5 2.5 0 00-2.5-2.5H14" />
                                        </svg>
                                    @elseif($activity['icon'] === 'calendar')
                                        <svg xmlns="http://www.w3.org/2000/svg" class="h-5 w-5 text-{{ $activity['color'] }}-600" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M8 7V3m8 4V3m-9 8h10M5 21h14a2 2 0 002-2V7a2 2 0 00-2-2H5a2 2 0 00-2 2v12a2 2 0 002 2z" />
                                        </svg>
                                    @elseif($activity['icon'] === 'photograph')
                                        <svg xmlns="http://www.w3.org/2000/svg" class="h-5 w-5 text-{{ $activity['color'] }}-600" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M4 16l4.586-4.586a2 2 0 012.828 0L16 16m-2-2l1.586-1.586a2 2 0 012.828 0L20 14m-6-6h.01M6 20h12a2 2 0 002-2V6a2 2 0 00-2-2H6a2 2 0 00-2 2v12a2 2 0 002 2z" />
                                        </svg>
                                    @elseif($activity['icon'] === 'document')
                                        <svg xmlns="http://www.w3.org/2000/svg" class="h-5 w-5 text-{{ $activity['color'] }}-600" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12h6m-6 4h6m2 5H7a2 2 0 01-2-2V5a2 2 0 012-2h5.586a1 1 0 01.707.293l5.414 5.414a1 1 0 01.293.707V19a2 2 0 01-2 2z" />
                                        </svg>
                                    @elseif($activity['icon'] === 'phone')
                                        <svg xmlns="http://www.w3.org/2000/svg" class="h-5 w-5 text-{{ $activity['color'] }}-600" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M3 5a2 2 0 012-2h3.28a1 1 0 01.948.684l1.498 4.493a1 1 0 01-.502 1.21l-2.257 1.13a11.042 11.042 0 005.516 5.516l1.13-2.257a1 1 0 011.21-.502l4.493 1.498a1 1 0 01.684.949V19a2 2 0 01-2 2h-1C9.716 21 3 14.284 3 6V5z" />
                                        </svg>
                                    @elseif($activity['icon'] === 'cog')
                                        <svg xmlns="http://www.w3.org/2000/svg" class="h-5 w-5 text-{{ $activity['color'] }}-600" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M10.325 4.317c.426-1.756 2.924-1.756 3.35 0a1.724 1.724 0 002.573 1.066c1.543-.94 3.31.826 2.37 2.37a1.724 1.724 0 001.065 2.572c1.756.426 1.756 2.924 0 3.35a1.724 1.724 0 00-1.066 2.573c.94 1.543-.826 3.31-2.37 2.37a1.724 1.724 0 00-2.572 1.065c-.426 1.756-2.924 1.756-3.35 0a1.724 1.724 0 00-2.573-1.066c-1.543.94-3.31-.826-2.37-2.37a1.724 1.724 0 00-1.065-2.572c-1.756-.426-1.756-2.924 0-3.35a1.724 1.724 0 001.066-2.573c-.94-1.543.826-3.31 2.37-2.37.996.608 2.296.07 2.572-1.065z" />
                                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 12a3 3 0 11-6 0 3 3 0 016 0z" />
                                        </svg>
                                    @else
                                        <svg xmlns="http://www.w3.org/2000/svg" class="h-5 w-5 text-{{ $activity['color'] }}-600" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M7 7h.01M7 3h5c.512 0 1.024.195 1.414.586l7 7a2 2 0 010 2.828l-7 7a2 2 0 01-2.828 0l-7-7A1.994 1.994 0 013 12V7a4 4 0 014-4z" />
                                        </svg>
                                    @endif
                                </div>
                            </div>
                            <div class="flex-1">
                                <div class="flex items-center justify-between">
                                    <p class="text-sm font-medium text-gray-900">
                                        {{ $activity['title'] }}
                                        <span class="text-{{ $activity['color'] }}-600 text-xs ml-2">
                                            {{ ucfirst($activity['type']) }}
                                        </span>
                                    </p>
                                    <span class="text-xs text-gray-500">
                                        {{ \Carbon\Carbon::parse($activity['date'])->diffForHumans() }}
                                    </span>
                                </div>
                                <p class="text-xs text-gray-500 mt-1">
                                    Dibuat oleh {{ $activity['creator'] }}
                                </p>
                            </div>
                        </div>
                    @endforeach
                </div>
            </div> --}}
        </div>
    </div>
</x-app-layout>
