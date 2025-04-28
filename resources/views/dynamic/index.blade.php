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
            <h1 class="text-5xl font-extrabold text-white mb-4 tracking-wide uppercase">
                {{ $setting->name }}
            </h1>
            <div class="flex items-center justify-center text-white text-lg font-medium">
                <a href="{{ route('home') }}" class="hover:text-yellow-300 transition-colors">Beranda</a>
                @foreach(explode('/', trim(request()->path(), '/')) as $segment)
                    <span class="mx-3 text-yellow-300">•</span>
                    <span class="capitalize {{ $loop->last ? 'text-yellow-300' : '' }}">
                        {{ str_replace('-', ' ', $segment) }}
                    </span>
                @endforeach
            </div>
        </div>
    </div>

    <div class="py-12">
        <div class="max-w-7xl mx-auto sm:px-6 lg:px-8">
            <div class="bg-white overflow-hidden shadow-sm sm:rounded-lg">
                <div class="p-6">
                    <div class="dynamic-content prose max-w-none">
                        @foreach($allSettings as $content)
                            @if($content['image'])
                                <div class="mb-6 flex justify-center">
                                    <img src="{{ asset($content['image']) }}" 
                                         alt="{{ $content['name'] }}" 
                                         class="max-w-2xl w-full h-auto rounded-lg shadow-md object-cover">
                                </div>
                            @endif
                            <div class="mb-8">
                                {!! $content['content'] !!}
                            </div>
                        @endforeach
                    </div>
                </div>
            </div>
        </div>
    </div>

    @push('styles')
    <style>
        .prose {
            max-width: none;
        }
        
        .prose img {
            margin: 0 auto;
        }

        .dynamic-content {
            --tw-prose-p-spacing: 1.5em;
        }
        
        .dynamic-content p {
            margin-top: 1.5em !important;
            margin-bottom: 1.5em !important;
        }

        .dynamic-content p:empty {
            min-height: 1em !important;
            display: block !important;
        }

        .dynamic-content ul, 
        .dynamic-content ol {
            margin-top: 1.25em !important;
            margin-bottom: 1.25em !important;
            padding-left: 2em !important;
            list-style-position: outside !important;
        }

        .dynamic-content ul {
            list-style-type: disc !important;
        }
        
        .dynamic-content ol {
            list-style-type: decimal !important;
        }

        .dynamic-content h1, 
        .dynamic-content h2, 
        .dynamic-content h3, 
        .dynamic-content h4, 
        .dynamic-content h5, 
        .dynamic-content h6 {
            margin-top: 1.5em !important;
            margin-bottom: 0.75em !important;
        }

        .dynamic-content blockquote,
        .dynamic-content pre,
        .dynamic-content figure,
        .dynamic-content table {
            margin-top: 1.5em !important;
            margin-bottom: 1.5em !important;
        }

        .dynamic-content * {
            line-height: 1.6 !important;
        }

        .dynamic-content img {
            margin-top: 1.5em !important;
            margin-bottom: 1.5em !important;
        }

        .dynamic-content iframe {
            margin-top: 1.5em !important;
            margin-bottom: 1.5em !important;
            max-width: 100% !important;
        }

        .dynamic-content p + p {
            margin-top: 1.5em !important;
        }

        .dynamic-content [style] {
            max-width: 100% !important;
            box-sizing: border-box !important;
        }
        
        .dynamic-content > *:first-child {
            margin-top: 0 !important;
        }
        
        .dynamic-content div {
            margin-top: 1.5em !important;
            margin-bottom: 1.5em !important;
        }
        
        .dynamic-content p span {
            display: inline-block !important;
            width: 100% !important;
        }
        
        .dynamic-content > div,
        .dynamic-content > p {
            margin-bottom: 1.5em !important;
        }
        
        .dynamic-content br + br {
            display: block !important;
            content: "" !important;
            margin-top: 1.5em !important;
        }
        
        .dynamic-content ul li,
        .dynamic-content ol li {
            display: list-item !important;
            margin-bottom: 0.5em !important;
        }
        
        .dynamic-content ul > li::marker,
        .dynamic-content ol > li::marker {
            display: inline-block !important;
        }
        
        .dynamic-content li ul,
        .dynamic-content li ol {
            margin-top: 0.5em !important;
        }

        .dynamic-content p[style*="text-indent"] {
            text-indent: 2em !important;
        }
    </style>
    @endpush
</x-main-layout>