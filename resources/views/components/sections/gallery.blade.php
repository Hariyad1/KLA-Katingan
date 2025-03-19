@props(['galleries' => []])

<div class="bg-gradient-to-br from-purple-50 to-fuchsia-50 rounded-lg shadow-md overflow-hidden">
    <div class="p-6">
        <div class="flex justify-between items-center mb-6">
            <h2 class="text-2xl font-bold text-purple-800 relative">
                <span class="relative inline-block">
                    <span class="absolute -left-2 -right-2 bottom-2 h-3 bg-purple-200/60"></span>
                    <span class="relative">Galeri Gambar</span>
                </span>
            </h2>
            <a href="{{ route('galeri') }}" class="text-blue-500 hover:text-blue-600 text-sm font-medium">
                Lihat Semua →
            </a>
        </div>
        
        <!-- Single container with slider -->
        <div class="aspect-video rounded-lg overflow-hidden bg-gray-100 relative">
            <div class="gallery-carousel h-full">
                @forelse($galleries->sortByDesc('created_at')->take(4) as $gallery)
                    <div class="slide-item h-full">
                        <a href="{{ route('gallery.show', $gallery->id) }}" class="block h-full">
                            <img src="{{ $gallery->path }}" 
                                 alt="{{ $gallery->name }}" 
                                 class="w-full h-full object-cover"
                                 onerror="this.onerror=null; this.src='{{ asset('images/default-image.jpg') }}';">
                        </a>
                    </div>
                @empty
                    <div class="flex items-center justify-center h-full">
                        <p class="text-gray-500">Tidak ada gambar tersedia</p>
                    </div>
                @endforelse
            </div>
            
            <!-- Custom Indicators -->
            <div class="absolute bottom-4 left-0 right-0 flex justify-center gap-2 z-10">
                @for($i = 0; $i < min(4, $galleries->count()); $i++)
                    <button class="w-12 h-1.5 rounded-full bg-white/50 hover:bg-white/75 transition-all duration-300 indicator-dot"
                            data-slide="{{ $i }}"></button>
                @endfor
            </div>
        </div>
    </div>
</div>

<script>
document.addEventListener('DOMContentLoaded', function() {
    if (typeof jQuery !== 'undefined' && typeof jQuery.fn.slick !== 'undefined') {
        var $carousel = $('.gallery-carousel');
        
        $carousel.slick({
            slidesToShow: 1,
            slidesToScroll: 1,
            autoplay: true,
            autoplaySpeed: 3000,
            arrows: false,
            dots: false,
            infinite: true,
            speed: 800,
            fade: false,
            cssEase: 'cubic-bezier(0.4, 0, 0.2, 1)',
            rtl: false
        });

        // Update indicator dots
        $carousel.on('beforeChange', function(event, slick, currentSlide, nextSlide) {
            $('.indicator-dot').removeClass('bg-white').addClass('bg-white/50');
            $(`.indicator-dot[data-slide="${nextSlide}"]`).removeClass('bg-white/50').addClass('bg-white');
        });

        // Initialize first dot as active
        $('.indicator-dot[data-slide="0"]').removeClass('bg-white/50').addClass('bg-white');

        // Click handler for indicator dots
        $('.indicator-dot').on('click', function() {
            const slideIndex = $(this).data('slide');
            $carousel.slick('slickGoTo', slideIndex);
        });
    } else {
        console.error('jQuery or Slick is not loaded');
    }
});
</script>

<style>
.single-image-container {
    position: relative;
}

.slide-item {
    outline: none;
}

.gallery-carousel {
    position: relative;
}

.gallery-carousel .slick-slide {
    transform: translateX(100%);
    transition: transform 0.8s cubic-bezier(0.4, 0, 0.2, 1);
}

.gallery-carousel .slick-current {
    transform: translateX(0);
}

.gallery-carousel .slick-slide.slick-active {
    z-index: 1;
}

/* Custom indicator styles */
.indicator-dot {
    cursor: pointer;
    transition: all 0.3s ease;
}

.indicator-dot:hover {
    background-color: rgba(255, 255, 255, 0.75);
}

/* Memastikan container slider tidak bertumpuk */
.gallery-carousel .slick-list,
.gallery-carousel .slick-track {
    height: 100%;
}

/* Menghilangkan efek fade */
.gallery-carousel {
    overflow: hidden;
}
</style> 