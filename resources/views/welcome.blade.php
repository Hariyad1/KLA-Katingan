<x-main-layout>
    <!-- Carousel Section -->
    <div class="relative bg-gradient-to-r from-blue-100 to-indigo-50">
        <div class="w-full">
            <!-- Main Slider Container -->
            <div class="flex flex-col lg:flex-row">
                <!-- Static Content - Responsive -->
                <div class="w-full lg:w-[320px] relative overflow-hidden lg:h-[500px]">
                    <div class="absolute inset-0 flex items-center justify-center">
                        <img src="{{ asset('images/bg-slider-tp1.png') }}" alt="Children Playing" 
                             class="w-full h-full object-contain">
                    </div>
                    <div class="absolute inset-0 bg-gradient-to-b from-transparent via-black/30 to-black/70 lg:via-transparent lg:to-black/60"></div>
                    <div class="absolute inset-x-0 p-8 text-center h-full flex flex-col items-center justify-center lg:justify-start lg:top-[5%]">
                        <h2 class="text-3xl font-bold text-white lg:text-black mb-4 drop-shadow-lg">Kabupaten Layak Anak</h2>
                        <p class="text-white/90 lg:text-black/90 mb-6 drop-shadow-md max-w-xs">Mewujudkan Katingan yang ramah dan layak bagi tumbuh kembang anak</p>
                    </div>
                </div>

                <!-- Dynamic Slider -->
                <div class="w-full lg:flex-1 h-[400px] lg:h-[500px] relative overflow-hidden" 
                    x-data="{ 
                        currentIndex: 0,
                        slides: {{ json_encode($slides) }},
                        timer: null,
                        canSlide: true,
                        
                        init() {
                            this.startTimer();
                        },
                        
                        startTimer() {
                            this.stopTimer();
                            this.timer = setInterval(() => {
                                if (this.canSlide) {
                                    this.next();
                                }
                            }, 7000);
                        },
                        
                        stopTimer() {
                            if (this.timer) {
                                clearInterval(this.timer);
                                this.timer = null;
                            }
                        },
                        
                        next() {
                            if (!this.canSlide) return;
                            this.canSlide = false;
                            this.currentIndex = (this.currentIndex + 1) % this.slides.length;
                            this.resetSlideState();
                        },
                        
                        prev() {
                            if (!this.canSlide) return;
                            this.canSlide = false;
                            this.currentIndex = (this.currentIndex - 1 + this.slides.length) % this.slides.length;
                            this.resetSlideState();
                        },
                        
                        goto(index) {
                            if (!this.canSlide || index === this.currentIndex) return;
                            this.canSlide = false;
                            this.currentIndex = index;
                            this.resetSlideState();
                        },
                        
                        resetSlideState() {
                            this.stopTimer();
                            setTimeout(() => {
                                this.canSlide = true;
                                this.startTimer();
                            }, 1000);
                        }
                    }" 
                    x-init="init()"
                    @mouseenter="stopTimer()"
                    @mouseleave="if (canSlide) startTimer()">
                    
                    <!-- Slides -->
                    @foreach($slides as $slide)
                        <div class="absolute inset-0 w-full h-full transition-all duration-1000"
                        x-show="currentIndex === {{ $loop->index }}"
                        x-transition:enter="transition ease-in-out duration-1000"
                        x-transition:enter-start="opacity-0 transform translate-x-full"
                        x-transition:enter-end="opacity-100 transform translate-x-0"
                        x-transition:leave="transition ease-in-out duration-1000"
                        x-transition:leave-start="opacity-100 transform translate-x-0"
                        x-transition:leave-end="opacity-0 transform -translate-x-full">
                            <div class="absolute inset-0">
                                <img src="{{ $slide->path }}" 
                                     alt="{{ $slide->name }}" 
                                     class="w-full h-full object-contain animate-kenburns">
                            </div>
                            <div class="absolute inset-0 bg-gradient-to-b from-black/40 via-transparent to-black/60"></div>
                            <div class="absolute bottom-0 left-0 right-0 p-6 lg:p-12 text-center">
                                <h3 class="text-2xl lg:text-4xl font-bold text-white mb-2 lg:mb-4 drop-shadow-lg slide-up">
                                    {{ $slide->name }}
                                </h3>
                                @if($slide->description)
                                    <p class="text-white/90 text-sm lg:text-xl max-w-3xl mx-auto lg:leading-relaxed drop-shadow-md slide-up-delayed">
                                        {{ $slide->description }}
                                    </p>
                                @endif
                            </div>
                        </div>
                    @endforeach
                    
                    <!-- Controls -->
                    <div class="hidden lg:block">
                        <button @click="prev()" 
                                class="absolute left-4 top-1/2 -translate-y-1/2 bg-black/20 hover:bg-black/40 text-white p-4 rounded-full backdrop-blur-sm transition-all z-20">
                            <svg class="w-6 h-6" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 19l-7-7 7-7"/>
                            </svg>
                        </button>
                        <button @click="next()" 
                                class="absolute right-4 top-1/2 -translate-y-1/2 bg-black/20 hover:bg-black/40 text-white p-4 rounded-full backdrop-blur-sm transition-all z-20">
                            <svg class="w-6 h-6" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 5l7 7-7 7"/>
                            </svg>
                        </button>
                    </div>
                    
                    <!-- Indicators -->
                    <div class="absolute bottom-4 lg:bottom-8 left-0 right-0 flex justify-center space-x-2 z-20">
                        <template x-for="(slide, index) in slides" :key="index">
                            <button @click="goto(index)" 
                                    :class="{'bg-white w-6': currentIndex === index, 'bg-white/50 w-2': currentIndex !== index}"
                                    class="h-2 rounded-full transition-all duration-500">
                            </button>
                        </template>
                    </div>
                </div>
            </div>
        </div>
    </div>

    <!-- Styles -->
    <style>
        @keyframes kenburns {
            0% {
                transform: scale(1.1);
            }
            100% {
                transform: scale(1);
            }
        }

        .animate-kenburns {
            animation: kenburns 7s ease-out;
            animation-fill-mode: both;
            transform-origin: center center;
        }

        .slide-up {
            animation: slide-up 1s ease-out forwards;
        }

        .slide-up-delayed {
            animation: slide-up 1s ease-out 0.3s forwards;
            opacity: 0;
        }

        @keyframes slide-up {
            0% {
                opacity: 0;
                transform: translateY(20px);
            }
            100% {
                opacity: 1;
                transform: translateY(0);
            }
        }

        .klaster-slider {
            padding: 20px;
        }

        .klaster-slide {
            margin: 0 20px;
            transition: transform 0.3s ease;
            height: 100%;
        }

        .klaster-slide > a {
            display: block;
            height: 100%;
        }

        .klaster-slide:hover {
            transform: scale(1.05);
        }

        .klaster-slide img {
            width: 100%;
            height: 200px;
            object-fit: cover;
            border-radius: 8px;
        }

        .klaster-title {
            text-align: center;
            margin-top: 10px;
            font-weight: bold;
            color: #14213D;
        }

        .klaster-description {
            text-align: center;
            color: #666;
            font-size: 0.9rem;
            line-height: 1.2;
            overflow: hidden;
            display: -webkit-box;
            -webkit-line-clamp: 2;
            -webkit-box-orient: vertical;
        }
    </style>

    <!-- Section 1: Klaster -->
    <section id="klaster-section" class="relative py-16 bg-gradient-to-br from-blue-200 via-white to-purple-100">
        <div class="absolute inset-0 bg-gradient-to-br from-blue-200/80 via-white/60 to-purple-100/80 backdrop-blur-sm"></div>
        
        <div class="absolute left-0 bottom-0 w-80 h-112 z-10">
            <img src="{{ asset('images/1kids.svg') }}" alt="Kids Decoration Left" class="w-full h-full object-contain">
        </div>
        <div class="absolute right-0 bottom-0 w-80 h-112 z-10">
            <img src="{{ asset('images/2kids.svg') }}" alt="Kids Decoration Right" class="w-full h-full object-contain">
        </div>
        
        <div class="relative z-30">
            <div class="max-w-7xl mx-auto sm:px-6 lg:px-8">
                <div class="bg-white/80 backdrop-blur-sm overflow-hidden shadow-sm sm:rounded-lg mt-8 relative">
                    
                    <div class="p-6 relative z-10">
                        <h2 class="text-4xl font-extrabold font-poppins mb-8 text-center">
                            <span class="relative inline-block">
                                <span class="absolute -left-4 -right-4 bottom-2 h-4 bg-pink-200/80"></span>
                                <span class="relative text-[#14213D] tracking-wide">Klaster</span>
                            </span>
                        </h2>
                        
                        <div class="klaster-slider">
                            <div class="klaster-slide">
                                <a href="{{ route('pemenuhan-hak-anak.klaster1') }}">
                                    <div class="relative overflow-hidden shadow-md hover:shadow-xl transition-all duration-300 h-full bg-gradient-to-br from-orange-200 via-orange-100 to-orange-300 rounded-lg">
                                        <div class="aspect-w-16 aspect-h-9">
                                            <img src="{{ asset('images/klaster-1.jpg') }}" alt="Klaster 1" class="w-full h-[200px] object-cover">
                                        </div>
                                        <div class="p-4">
                                            <div class="klaster-title">Klaster 1</div>
                                            <div class="klaster-description h-12 flex items-center justify-center">Hak Sipil dan Kebebasan</div>
                                        </div>
                                    </div>
                                </a>
                            </div>

                            <div class="klaster-slide">
                                <a href="{{ route('pemenuhan-hak-anak.klaster2') }}">
                                    <div class="relative overflow-hidden shadow-md hover:shadow-xl transition-all duration-300 h-full bg-gradient-to-br from-yellow-200 via-yellow-100 to-yellow-300 rounded-lg">
                                        <div class="aspect-w-16 aspect-h-9">
                                            <img src="{{ asset('images/klaster-2.jpg') }}" alt="Klaster 2" class="w-full h-[200px] object-cover">
                                        </div>
                                        <div class="p-4">
                                            <div class="klaster-title">Klaster 2</div>
                                            <div class="klaster-description h-12 flex items-center justify-center">Lingkungan Keluarga dan Pengasuhan Alternatif</div>
                                        </div>
                                    </div>
                                </a>
                            </div>

                            <div class="klaster-slide">
                                <a href="{{ route('pemenuhan-hak-anak.klaster3') }}">
                                    <div class="relative overflow-hidden shadow-md hover:shadow-xl transition-all duration-300 h-full bg-gradient-to-br from-green-200 via-green-100 to-green-300 rounded-lg">
                                        <div class="aspect-w-16 aspect-h-9">
                                            <img src="{{ asset('images/klaster-3.jpg') }}" alt="Klaster 3" class="w-full h-[200px] object-cover">
                                        </div>
                                        <div class="p-4">
                                            <div class="klaster-title">Klaster 3</div>
                                            <div class="klaster-description h-12 flex items-center justify-center">Kesehatan Dasar dan Kesejahteraan</div>
                                        </div>
                                    </div>
                                </a>
                            </div>

                            <div class="klaster-slide">
                                <a href="{{ route('pemenuhan-hak-anak.klaster4') }}">
                                    <div class="relative overflow-hidden shadow-md hover:shadow-xl transition-all duration-300 h-full bg-gradient-to-br from-sky-200 via-sky-100 to-sky-300 rounded-lg">
                                        <div class="aspect-w-16 aspect-h-9">
                                            <img src="{{ asset('images/klaster-4.jpg') }}" alt="Klaster 4" class="w-full h-[200px] object-cover">
                                        </div>
                                        <div class="p-4">
                                            <div class="klaster-title">Klaster 4</div>
                                            <div class="klaster-description h-12 flex items-center justify-center">Pendidikan, Pemanfaatan Waktu Luang, dan Kegiatan Budaya</div>
                                        </div>
                                    </div>
                                </a>
                            </div>

                            <div class="klaster-slide">
                                <a href="{{ route('perlindungan-khusus-anak.klaster5') }}">
                                    <div class="relative overflow-hidden shadow-md hover:shadow-xl transition-all duration-300 h-full bg-gradient-to-br from-red-200 via-red-100 to-red-300 rounded-lg">
                                        <div class="aspect-w-16 aspect-h-9">
                                            <img src="{{ asset('images/klaster-5.jpg') }}" alt="Klaster 5" class="w-full h-[200px] object-cover">
                                        </div>
                                        <div class="p-4">
                                            <div class="klaster-title">Klaster 5</div>
                                            <div class="klaster-description h-12 flex items-center justify-center">Perlindungan Khusus Anak</div>
                                        </div>
                                    </div>
                                </a>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
        
        <div class="absolute bottom-0 left-0 right-0 w-full h-16 overflow-hidden z-20">
            <svg class="absolute bottom-0 w-full h-full" xmlns="http://www.w3.org/2000/svg" viewBox="0 0 1440 320" preserveAspectRatio="none">
                <path fill="rgb(224 231 255)" d="M0,224L48,213.3C96,203,192,181,288,181.3C384,181,480,203,576,213.3C672,224,768,224,864,213.3C960,203,1056,181,1152,186.7C1248,192,1344,224,1392,240L1440,256L1440,320L1392,320C1344,320,1248,320,1152,320C1056,320,960,320,864,320C768,320,672,320,576,320C480,320,384,320,288,320C192,320,96,320,48,320L0,320Z"></path>
            </svg>
        </div>
    </section>

    <!-- Section 2: Berita & Agenda -->
    <section id="berita-agenda-section" class="relative py-16 bg-gradient-to-br from-indigo-100 via-white to-blue-100">
        <div class="absolute inset-0 bg-gradient-to-br from-indigo-100/80 via-white/60 to-blue-100/80 backdrop-blur-sm"></div>
        
        <div class="absolute left-0 bottom-0 w-80 h-112 z-10">
            <img src="{{ asset('images/3kids.svg') }}" alt="Kids Decoration Left" class="w-full h-full object-contain">
        </div>
        <div class="absolute right-0 bottom-0 w-80 h-112 z-10">
            <img src="{{ asset('images/4kids.svg') }}" alt="Kids Decoration Right" class="w-full h-full object-contain">
        </div>
        
        <div class="relative z-10">
            <div class="max-w-7xl mx-auto sm:px-6 lg:px-8">
                <div class="flex flex-col lg:flex-row lg:justify-between gap-4 mt-8">
                    <div class="w-full lg:w-2/3">
                        <div class="bg-gradient-to-br from-blue-50 to-indigo-50 overflow-hidden shadow-lg sm:rounded-lg mt-8">
                            <div class="p-6">
                                <div class="flex justify-between items-center mb-8">
                                    <h2 class="text-2xl font-bold text-indigo-800 relative">
                                        <span class="relative inline-block">
                                            <span class="absolute -left-2 -right-2 bottom-2 h-3 bg-blue-200/60"></span>
                                            <span class="relative">Berita Terbaru</span>
                                        </span>
                                    </h2>
                                </div>
                                <div class="flex flex-col divide-y-4 divide-dashed divide-indigo-200 space-y-6">
                                    @forelse($latestNews as $item)
                                    <div class="flex flex-col md:flex-row gap-4 bg-gradient-to-r from-white via-blue-50 to-indigo-50 p-4 rounded-xl hover:shadow-lg transition-all duration-300 hover:from-blue-50 hover:via-indigo-50 hover:to-white {{ !$loop->first ? 'pt-10' : '' }}">
                                        <div class="w-full md:w-1/3 relative overflow-hidden rounded-lg">
                                            @if($item->image)
                                                <div class="relative group">
                                                    <img src="{{ $item->image }}" 
                                                        alt="{{ $item->title }}" 
                                                        class="w-full h-48 object-cover rounded-lg shadow-md transition-transform duration-300 group-hover:scale-105">
                                                    <div class="absolute inset-0 bg-gradient-to-t from-indigo-900/30 to-blue-500/20 opacity-0 group-hover:opacity-100 transition-opacity duration-300"></div>
                                                </div>
                                            @else
                                                <div class="w-full h-48 bg-gradient-to-br from-blue-200 to-indigo-200 rounded-lg"></div>
                                            @endif
                                        </div>
                                        <div class="w-full md:w-2/3">
                                            <h3 class="text-xl font-semibold text-indigo-700 hover:text-indigo-900">
                                                <a href="{{ route('berita.detail', ['title' => Str::slug($item->title)]) }}">
                                                    {{ $item->title }}
                                                </a>
                                            </h3>
                                            <p class="text-indigo-500 text-sm mt-2">
                                                {{ \Carbon\Carbon::parse($item->created_at)->format('d F Y') }}
                                            </p>
                                            <p class="text-gray-600 mt-2 line-clamp-3">
                                                {{ Str::limit(strip_tags($item->content), 150) }}
                                            </p>
                                            <a href="{{ route('berita.detail', ['title' => Str::slug($item->title)]) }}" 
                                                class="inline-flex items-center mt-3 text-indigo-600 hover:text-indigo-800 transition-colors">
                                                ... selengkapnya
                                                <svg class="w-4 h-4 ml-1" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 5l7 7-7 7"></path>
                                                </svg>
                                            </a>
                                        </div>
                                    </div>
                                    @empty
                                    <div class="text-center py-12 bg-white/60 rounded-xl">
                                        <div class="mb-4">
                                            <svg class="mx-auto h-16 w-16 text-indigo-300" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 20H5a2 2 0 01-2-2V6a2 2 0 012-2h10a2 2 0 012 2v1m2 13a2 2 0 01-2-2V7m2 13a2 2 0 002-2V9.5a2 2 0 00-2-2h-2m-4-3H9M7 16h6M7 8h6v4H7V8z"></path>
                                            </svg>
                                        </div>
                                        <h3 class="text-xl font-semibold text-indigo-900 mb-2">Tidak Ada Berita</h3>
                                        <p class="text-indigo-600">Belum ada berita yang dipublikasikan saat ini</p>
                                    </div>
                                    @endforelse
                                </div>
                            </div>
                        </div>
                    </div>

                    <!-- Agenda Section -->
                    <div class="w-full lg:w-1/3">
                        <div class="bg-gradient-to-br from-emerald-50 to-teal-100 p-6 shadow-lg rounded-lg mt-8">
                            <h2 class="text-2xl font-bold text-emerald-800 mb-6 relative">
                                <span class="relative inline-block">
                                    <span class="absolute -left-2 -right-2 bottom-2 h-3 bg-emerald-200/60"></span>
                                    <span class="relative">Agenda</span>
                                </span>
                            </h2>
                            <div class="grid grid-cols-1 gap-4">
                                @forelse($upcomingAgendas as $item)
                                    <div class="flex gap-4 p-4 bg-white/80 rounded-xl hover:shadow-lg transition-all duration-300">
                                        <div class="w-20 flex-shrink-0">
                                            <div class="bg-gradient-to-br from-emerald-100 to-teal-200 rounded-lg p-3 text-center shadow-md">
                                                <span class="text-xl font-bold block text-emerald-700">
                                                    {{ \Carbon\Carbon::parse($item->tanggal)->format('d') }}
                                                </span>
                                                <span class="text-sm text-emerald-600 block">
                                                    {{ \Carbon\Carbon::parse($item->tanggal)->isoFormat('MMM') }}
                                                </span>
                                            </div>
                                        </div>
                                        <div class="flex-1">
                                            <h3 class="text-lg font-semibold text-emerald-700">{{ $item->title }}</h3>
                                            <p class="text-sm text-emerald-600">
                                                {{ \Carbon\Carbon::parse($item->tanggal)->isoFormat('dddd, D MMMM Y') }}
                                            </p>
                                            <p class="text-sm mt-1 text-gray-600">{{ $item->keterangan }}</p>
                                        </div>
                                    </div>
                                @empty
                                    <div class="text-center py-8">
                                        <div class="mb-4">
                                            <svg class="mx-auto h-12 w-12 text-gray-400" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M8 7V3m8 4V3m-9 8h10M5 21h14a2 2 0 002-2V7a2 2 0 00-2-2H5a2 2 0 00-2 2v12a2 2 0 002 2z"/>
                                            </svg>
                                        </div>
                                        <h3 class="text-lg font-medium text-gray-900 mb-1">Tidak ada agenda</h3>
                                        <p class="text-gray-500">Belum ada agenda yang akan datang saat ini</p>
                                    </div>
                                @endforelse
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
        
        <div class="absolute bottom-0 left-0 right-0 w-full h-16 overflow-hidden">
            <svg class="absolute bottom-0 w-full h-full" xmlns="http://www.w3.org/2000/svg" viewBox="0 0 1440 320" preserveAspectRatio="none">
                <path fill="rgb(243 232 255)" d="M0,288L60,261.3C120,235,240,181,360,181.3C480,181,600,235,720,245.3C840,256,960,224,1080,213.3C1200,203,1320,213,1380,218.7L1440,224L1440,320L1380,320C1320,320,1200,320,1080,320C960,320,840,320,720,320C600,320,480,320,360,320C240,320,120,320,60,320L0,320Z"></path>
            </svg>
        </div>
    </section>

    <!-- Section 3: Galeri, Video & Dokumen -->
    <section id="galeri-video-dokumen-section" class="relative py-16 bg-gradient-to-br from-purple-100 via-white to-pink-100">
        <div class="absolute inset-0 bg-gradient-to-br from-purple-100/80 via-white/60 to-pink-100/80 backdrop-blur-sm"></div>
        
        <div class="absolute left-0 bottom-0 w-80 h-112 z-10">
            <img src="{{ asset('images/5kids.svg') }}" alt="Kids Decoration Left" class="w-full h-full object-contain">
        </div>
        <div class="absolute right-0 bottom-0 w-80 h-112 z-10">
            <img src="{{ asset('images/6kids.svg') }}" alt="Kids Decoration Right" class="w-full h-full object-contain">
        </div>
        
        <div class="relative z-10">
            <div class="max-w-7xl mx-auto sm:px-6 lg:px-8">
                <div class="py-12 bg-white/50 backdrop-blur-sm rounded-lg shadow-md">
                    <div class="max-w-7xl mx-auto sm:px-6 lg:px-8">
                        <div class="grid grid-cols-1 lg:grid-cols-3 gap-8">
                            <x-sections.gallery :galleries="$galleries" />

                            <x-sections.video :videoSetting="$videoSetting" />

                            <x-sections.document :documents="$documents" />
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </section>
</x-main-layout>

<script>
$(document).ready(function(){
    var $slider = $('.klaster-slider');
    
    $slider.slick({
        slidesToShow: 3,
        slidesToScroll: 1,
        autoplay: true,
        autoplaySpeed: 2000,
        arrows: true,
        dots: true,
        pauseOnHover: false,
        infinite: true,
        draggable: true,
        touchThreshold: 10,
        swipe: true,
        swipeToSlide: true,
        responsive: [{
            breakpoint: 1024,
            settings: {
                slidesToShow: 2
            }
        }, {
            breakpoint: 600,
            settings: {
                slidesToShow: 1
            }
        }]
    });

    var autoplayEnabled = true;

    $slider.on('beforeChange', function() {
        autoplayEnabled = true;
        restartAutoplay();
    });

    $slider.on('swipe', function() {
        autoplayEnabled = true;
        restartAutoplay();
    });

    $slider.on('edge', function() {
        autoplayEnabled = true;
        restartAutoplay();
    });

    $slider.on('mousedown touchstart', function() {
        autoplayEnabled = true;
    });

    function restartAutoplay() {
        if (autoplayEnabled) {
            $slider.slick('slickPlay');
        }
    }

    setInterval(function() {
        if (autoplayEnabled) {
            restartAutoplay();
        }
    }, 3000);

    $('.slick-next, .slick-prev').on('click', function() {
        autoplayEnabled = true;
        restartAutoplay();
    });

    $('.slick-dots button').on('click', function() {
        autoplayEnabled = true;
        restartAutoplay();
    });
});
</script>
