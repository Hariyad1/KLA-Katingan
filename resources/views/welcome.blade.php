<x-main-layout>
    <!-- Carousel Section -->
    <div class="relative" x-data="{
            currentIndex: 0,
            slides: {{ json_encode($slides) }},
            
            next() {
                this.currentIndex = (this.currentIndex + 1) % this.slides.length;
            },
            
            prev() {
                this.currentIndex = (this.currentIndex - 1 + this.slides.length) % this.slides.length;
            },
            
            startAutoplay() {
                setInterval(() => {
                    this.next();
                }, 5000);
            }
        }" 
        x-init="startAutoplay">
        
        <div class="overflow-hidden relative h-[500px]">
            <div class="flex transition-transform duration-500 ease-out" 
                 :style="{ transform: `translateX(-${currentIndex * 100}%)` }">
                @foreach($slides as $slide)
                    <div class="min-w-full relative">
                        <img src="{{ $slide->path }}" 
                             alt="{{ $slide->name }}"
                             class="w-full h-[500px] object-cover">
                        <!-- Caption Container dengan gradient background -->
                        <div class="absolute bottom-0 left-0 right-0 bg-gradient-to-t from-black/80 to-transparent pt-16 pb-8 px-6">
                            <h3 class="text-2xl font-bold text-white mb-2">{{ $slide->name }}</h3>
                            @if($slide->description)
                                <p class="text-white/90 text-lg">{{ $slide->description }}</p>
                            @endif
                        </div>
                    </div>
                @endforeach
            </div>
        </div>
        
        <!-- Carousel Controls -->
        <button @click="prev" class="absolute left-0 top-1/2 -translate-y-1/2 bg-black/50 hover:bg-black/70 text-white p-3 rounded-r transition-colors">
            <svg class="w-6 h-6" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 19l-7-7 7-7"/>
            </svg>
        </button>
        <button @click="next" class="absolute right-0 top-1/2 -translate-y-1/2 bg-black/50 hover:bg-black/70 text-white p-3 rounded-l transition-colors">
            <svg class="w-6 h-6" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 5l7 7-7 7"/>
            </svg>
        </button>

        <!-- Indicators -->
        <div class="absolute bottom-20 left-0 right-0 flex justify-center space-x-3 z-10">
            <template x-for="(slide, index) in slides" :key="index">
                <button @click="currentIndex = index" 
                        :class="{'bg-white': currentIndex === index, 'bg-white/50': currentIndex !== index}"
                        class="w-3 h-3 rounded-full transition-all duration-300 hover:bg-white"></button>
            </template>
        </div>
    </div>

    <!-- Content Section -->
    <div class="py-12">
        <div class="max-w-7xl mx-auto sm:px-6 lg:px-8">
            <!-- Klaster Grid -->
            <div class="bg-white overflow-hidden shadow-sm sm:rounded-lg mt-8">
                <div class="p-6">
                    <h2 class="text-2xl font-bold mb-8 text-center">KLASTER</h2>
                    <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-3 gap-8">
                        <!-- Klaster 1 -->
                        <a href="{{ route('pemenuhan-hak-anak.klaster1') }}" class="group">
                            <div class="relative overflow-hidden rounded-lg shadow-md hover:shadow-xl transition-all duration-300">
                                <img src="{{ asset('images/klaster1.jpg') }}" alt="Klaster 1" class="w-full h-48 object-cover group-hover:scale-105 transition-transform duration-300">
                                <div class="absolute bottom-0 left-0 right-0 bg-gradient-to-t from-black/80 to-transparent p-4">
                                    <h3 class="text-white font-semibold text-lg">Hak Sipil dan Kebebasan</h3>
                                </div>
                            </div>
                        </a>

                        <!-- Klaster 2 -->
                        <a href="{{ route('pemenuhan-hak-anak.klaster2') }}" class="group">
                            <div class="relative overflow-hidden rounded-lg shadow-md hover:shadow-xl transition-all duration-300">
                                <img src="{{ asset('images/klaster2.jpg') }}" alt="Klaster 2" class="w-full h-48 object-cover group-hover:scale-105 transition-transform duration-300">
                                <div class="absolute bottom-0 left-0 right-0 bg-gradient-to-t from-black/80 to-transparent p-4">
                                    <h3 class="text-white font-semibold text-lg">Lingkungan Keluarga dan Pengasuhan Alternatif</h3>
                                </div>
                            </div>
                        </a>

                        <!-- Klaster 3 -->
                        <a href="{{ route('pemenuhan-hak-anak.klaster3') }}" class="group">
                            <div class="relative overflow-hidden rounded-lg shadow-md hover:shadow-xl transition-all duration-300">
                                <img src="{{ asset('images/klaster3.jpg') }}" alt="Klaster 3" class="w-full h-48 object-cover group-hover:scale-105 transition-transform duration-300">
                                <div class="absolute bottom-0 left-0 right-0 bg-gradient-to-t from-black/80 to-transparent p-4">
                                    <h3 class="text-white font-semibold text-lg">Kesehatan Dasar dan Kesejahteraan</h3>
                                </div>
                            </div>
                        </a>

                        <!-- Klaster 4 -->
                        <a href="{{ route('pemenuhan-hak-anak.klaster4') }}" class="group">
                            <div class="relative overflow-hidden rounded-lg shadow-md hover:shadow-xl transition-all duration-300">
                                <img src="{{ asset('images/klaster4.jpg') }}" alt="Klaster 4" class="w-full h-48 object-cover group-hover:scale-105 transition-transform duration-300">
                                <div class="absolute bottom-0 left-0 right-0 bg-gradient-to-t from-black/80 to-transparent p-4">
                                    <h3 class="text-white font-semibold text-lg">Pendidikan, Pemanfaatan Waktu Luang, dan Kegiatan Budaya</h3>
                                </div>
                            </div>
                        </a>

                        <!-- Klaster 5 -->
                        <a href="{{ route('perlindungan-khusus-anak.klaster5') }}" class="group">
                            <div class="relative overflow-hidden rounded-lg shadow-md hover:shadow-xl transition-all duration-300">
                                <img src="{{ asset('images/klaster5.jpg') }}" alt="Klaster 5" class="w-full h-48 object-cover group-hover:scale-105 transition-transform duration-300">
                                <div class="absolute bottom-0 left-0 right-0 bg-gradient-to-t from-black/80 to-transparent p-4">
                                    <h3 class="text-white font-semibold text-lg">Perlindungan Khusus</h3>
                                </div>
                            </div>
                        </a>

                    </div>
                </div>
            </div>

            <div class="flex justify-between">
                <!-- Berita Terkini Section -->
                <div class="w-2/3">
                    <div class="bg-white overflow-hidden shadow-sm sm:rounded-lg mt-8">
                        <div class="p-6">
                            <div class="flex justify-between items-center mb-8">
                                <h2 class="text-2xl font-bold">BERITA TERKINI</h2>
                            </div>
                            <div class="flex flex-col space-y-4">
                                @foreach($latestNews as $item)
                                <div class="flex space-x-4">
                                    <div class="w-1/3">
                                        @if($item->image)
                                            <img src="{{ $item->image }}" alt="{{ $item->title }}" 
                                                 class="w-full h-48 object-cover rounded-lg">
                                        @else
                                            <div class="w-full h-48 bg-gray-200 rounded-lg"></div>
                                        @endif
                                    </div>
                                    <div class="w-2/3">
                                        <h3 class="text-xl font-semibold text-blue-600 hover:text-blue-800">
                                            <a href="{{ route('news.show', [$item->id, Str::slug($item->title)]) }}">{{ $item->title }}</a>
                                        </h3>
                                        <p class="text-gray-600 text-sm mt-2">
                                            {{ \Carbon\Carbon::parse($item->created_at)->format('d F Y') }}
                                        </p>
                                        <p class="text-gray-700 mt-2 line-clamp-3">
                                            {{ Str::limit(strip_tags($item->content), 150) }}
                                        </p>
                                        <a href="{{ route('news.show', [$item->id, Str::slug($item->title)]) }}" 
                                           class="text-blue-600 hover:text-blue-800 mt-2 inline-block">
                                            ... selanjutnya
                                        </a>
                                    </div>
                                </div>
                                @endforeach
                            </div>
                        </div>
                    </div>
                </div>

                <!-- Agenda Section -->
                <div class="w-1/3 bg-white p-4 shadow-md rounded-lg mt-8 ml-4">
                    <h2 class="text-xl font-bold mb-4">AGENDA</h2>
                    <div class="flex flex-col space-y-4">
                        @foreach($agenda as $item)
                        <div class="flex space-x-4">
                            <div class="w-1/4">
                                <div class="bg-gray-200 rounded-lg p-4 text-center">
                                    <span class="text-xl font-bold">{{ \Carbon\Carbon::parse($item->tanggal)->format('d') }}</span>
                                    <span class="block text-sm">{{ \Carbon\Carbon::parse($item->tanggal)->format('M') }}</span>
                                </div>
                            </div>
                            <div class="w-3/4">
                                <h3 class="text-lg font-semibold">{{ $item->title }}</h3>
                                <p class="text-sm text-gray-600">{{ \Carbon\Carbon::parse($item->tanggal)->format('l') }}</p>
                                <p class="text-sm">{{ $item->keterangan }}</p>
                            </div>
                        </div>
                        @endforeach
                    </div>
                </div>
            </div>
        </div>
    </div>
</x-main-layout>
