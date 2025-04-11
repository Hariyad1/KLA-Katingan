<x-main-layout>
    <!-- Header Section dengan Background Image -->
    <div class="relative h-[300px] flex items-center justify-center overflow-hidden">
        <!-- Background Image dengan Overlay -->
        <div class="absolute inset-0">
            <img src="{{ asset('images/inner-head.png') }}" alt="Header Background" class="w-full h-full object-cover">
            <div class="absolute inset-0 bg-gradient-to-b from-purple-900/70 to-purple-900/90"></div>
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
                GALERI
            </h1>
            <div class="flex items-center justify-center text-white text-lg font-medium">
                <a href="{{ route('home') }}" class="hover:text-yellow-300 transition-colors">Beranda</a>
                <span class="mx-3 text-yellow-300">•</span>
                <span class="text-yellow-300">Galeri</span>
            </div>
        </div>
    </div>

    <!-- Main Content -->
    <div 
        x-data="{
            isOpen: false,
            currentIndex: 0,
            images: {{ Js::from($media->items()) }},
            
            get currentImage() {
                return this.images[this.currentIndex];
            },
            
            openLightbox(index) {
                this.currentIndex = index;
                this.isOpen = true;
                document.body.style.overflow = 'hidden';
            },
            
            closeLightbox() {
                this.isOpen = false;
                document.body.style.overflow = '';
            },
            
            nextImage() {
                this.currentIndex = (this.currentIndex + 1) % this.images.length;
            },
            
            prevImage() {
                this.currentIndex = (this.currentIndex - 1 + this.images.length) % this.images.length;
            }
        }"
        class="py-12"
    >
        <div class="max-w-7xl mx-auto sm:px-6 lg:px-8">
            <div class="bg-white overflow-hidden shadow-sm sm:rounded-lg">
                <div class="p-6">
                    <!-- Grid Gallery -->
                    <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-3 gap-6">
                        @foreach($media->take(12) as $index => $item)
                            <div class="group relative overflow-hidden rounded-lg shadow-md hover:shadow-xl transition-shadow duration-300">
                                <img 
                                    src="{{ $item->path }}" 
                                    alt="{{ $item->name }}"
                                    class="w-full h-64 object-cover transform group-hover:scale-105 transition-transform duration-300"
                                >
                                
                                <div class="absolute bottom-0 left-0 right-0 bg-gradient-to-t from-black/80 to-transparent p-4">
                                    <h3 class="text-white font-semibold text-lg">{{ $item->name }}</h3>
                                </div>

                                <!-- Lightbox trigger -->
                                <button 
                                    @click="openLightbox({{ $index }})"
                                    class="absolute inset-0 w-full h-full cursor-pointer bg-black/0 hover:bg-black/10 transition-colors duration-200"
                                    aria-label="Buka gambar">
                                </button>
                            </div>
                        @endforeach
                    </div>

                    <!-- Pagination -->
                    <div class="mt-8">
                        <div class="flex items-center justify-center space-x-1">
                            {{ $media->links('components.sections.tailwind') }}
                        </div>
                    </div>
                </div>
            </div>
        </div>

        <!-- Lightbox Modal -->
        <div 
            x-show="isOpen" 
            x-transition:enter="transition ease-out duration-300"
            x-transition:enter-start="opacity-0"
            x-transition:enter-end="opacity-100"
            x-transition:leave="transition ease-in duration-300"
            x-transition:leave-start="opacity-100"
            x-transition:leave-end="opacity-0"
            class="fixed inset-0 z-50 flex items-center justify-center bg-black/90"
            @keydown.escape.window="closeLightbox"
            @keydown.arrow-left.window="prevImage"
            @keydown.arrow-right.window="nextImage"
            style="display: none;">
            
            <!-- Close Button -->
            <button @click="closeLightbox" class="absolute top-4 right-4 text-white hover:text-gray-300 z-50">
                <svg class="w-8 h-8" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M6 18L18 6M6 6l12 12"></path>
                </svg>
            </button>

            <!-- Previous Button -->
            <button @click="prevImage" class="absolute left-4 top-1/2 -translate-y-1/2 text-white hover:text-gray-300 z-50">
                <svg class="w-10 h-10" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 19l-7-7 7-7"></path>
                </svg>
            </button>

            <!-- Next Button -->
            <button @click="nextImage" class="absolute right-4 top-1/2 -translate-y-1/2 text-white hover:text-gray-300 z-50">
                <svg class="w-10 h-10" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 5l7 7-7 7"></path>
                </svg>
            </button>

            <!-- Image Container -->
            <div class="relative max-w-7xl mx-auto px-4 w-full">
                <template x-if="currentImage">
                    <img :src="currentImage.path" 
                         :alt="currentImage.name"
                         class="max-h-[80vh] mx-auto object-contain">
                </template>
                <div class="absolute bottom-4 left-0 right-0 text-center text-white">
                    <template x-if="currentImage">
                        <h3 x-text="currentImage.name" class="text-xl font-semibold"></h3>
                    </template>
                </div>
            </div>
        </div>
    </div>

    @push('scripts')
    <script>
        document.addEventListener('alpine:init', () => {
            Alpine.data('galleryData', () => ({
                isOpen: false,
                currentIndex: 0,
                images: @json($media->items()),

                get currentImage() {
                    return this.images[this.currentIndex];
                },

                openLightbox(index) {
                    this.currentIndex = index;
                    this.isOpen = true;
                    document.body.style.overflow = 'hidden';
                },

                closeLightbox() {
                    this.isOpen = false;
                    document.body.style.overflow = '';
                },

                nextImage() {
                    this.currentIndex = (this.currentIndex + 1) % this.images.length;
                },

                prevImage() {
                    this.currentIndex = (this.currentIndex - 1 + this.images.length) % this.images.length;
                }
            }));
        });
    </script>
    @endpush

    @push('styles')
    <style>
        @keyframes float {
            0% { transform: translateY(0px); }
            50% { transform: translateY(-10px); }
            100% { transform: translateY(0px); }
        }

        .floating {
            animation: float 3s ease-in-out infinite;
        }
    </style>
    @endpush
</x-main-layout> 