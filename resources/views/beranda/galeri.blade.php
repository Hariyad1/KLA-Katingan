<x-main-layout>
    <div class="py-12">
        <div class="max-w-7xl mx-auto sm:px-6 lg:px-8">
            <div class="bg-white overflow-hidden shadow-sm sm:rounded-lg">
                <div class="p-6">
                    <h1 class="text-2xl font-bold mb-6">Galeri</h1>
                    
                    <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-3 gap-6">
                        @foreach($media as $item)
                            <div class="group relative overflow-hidden rounded-lg shadow-md hover:shadow-xl transition-shadow duration-300">
                                <!-- Image -->
                                <img 
                                    src="{{ $item->path }}" 
                                    alt="{{ $item->name }}"
                                    class="w-full h-64 object-cover transform group-hover:scale-105 transition-transform duration-300"
                                >
                                
                                <!-- Overlay with title -->
                                <div class="absolute bottom-0 left-0 right-0 bg-gradient-to-t from-black/80 to-transparent p-4">
                                    <h3 class="text-white font-semibold text-lg">{{ $item->name }}</h3>
                                    <p class="text-white/70 text-sm">
                                        Dilihat: {{ $item->hits }} kali
                                    </p>
                                </div>

                                <!-- Lightbox trigger -->
                                <button 
                                    class="absolute inset-0 w-full h-full opacity-0 group-hover:opacity-100 transition-opacity duration-300"
                                    onclick="openLightbox('{{ $item->path }}', '{{ $item->name }}')"
                                >
                                </button>
                            </div>
                        @endforeach
                    </div>

                    <!-- Lightbox Modal -->
                    <div 
                        x-data="{ isOpen: false, imgSrc: '', imgTitle: '' }"
                        x-show="isOpen" 
                        x-on:open-lightbox.window="
                            isOpen = true;
                            imgSrc = $event.detail.src;
                            imgTitle = $event.detail.title;
                        "
                        class="fixed inset-0 bg-black/90 z-50 flex items-center justify-center"
                        x-cloak
                    >
                        <div class="relative max-w-4xl mx-auto p-4">
                            <button 
                                class="absolute top-4 right-4 text-white hover:text-gray-300"
                                @click="isOpen = false"
                            >
                                <svg class="w-6 h-6" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M6 18L18 6M6 6l12 12"></path>
                                </svg>
                            </button>
                            
                            <img :src="imgSrc" :alt="imgTitle" class="max-h-[80vh] mx-auto">
                            <p class="text-white text-center mt-4 text-lg" x-text="imgTitle"></p>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>

    @push('scripts')
    <script>
        function openLightbox(src, title) {
            window.dispatchEvent(new CustomEvent('open-lightbox', {
                detail: { src, title }
            }));
        }
    </script>
    @endpush
</x-main-layout> 