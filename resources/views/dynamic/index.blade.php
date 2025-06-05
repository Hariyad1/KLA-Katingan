<x-main-layout>
    <div class="relative h-[300px] flex items-center justify-center overflow-hidden">
        <div class="absolute inset-0">
            <img src="{{ asset('images/inner-head.png') }}" alt="Header Background" class="w-full h-full object-cover">
            <div class="absolute inset-0 bg-gradient-to-b from-purple-900/70 to-purple-900/90"></div>
        </div>
        
        <div class="absolute inset-0">
            <div class="absolute left-20 top-20">
                <svg width="80" height="80" viewBox="0 0 80 80" class="text-orange-500 opacity-80">
                    <circle cx="40" cy="40" r="40" fill="currentColor"/>
                </svg>
            </div>
            
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
            
            <div class="absolute right-16 top-12">
                <svg width="100" height="100" viewBox="0 0 100 100" class="text-yellow-300 opacity-80 transform -rotate-45">
                    <path fill="currentColor" d="M50 0 L52 98 L48 98 L50 0 Z"/>
                    <circle cx="50" cy="10" r="8" fill="currentColor"/>
                </svg>
            </div>
        </div>
        
        <div class="relative z-10 text-center px-4 sm:px-6 md:px-8">
            <h1 class="text-3xl sm:text-4xl md:text-5xl font-extrabold text-white mb-4 md:mb-6 tracking-wide uppercase mx-auto max-w-5xl">
                {{ $setting->name }}
            </h1>
            <div class="flex flex-wrap items-center justify-center text-white text-base md:text-lg font-medium px-2">
                <a href="{{ route('home') }}" class="hover:text-yellow-300 transition-colors px-1">Beranda</a>
                @foreach(explode('/', trim(request()->path(), '/')) as $segment)
                    <span class="mx-2 md:mx-3 text-yellow-300">•</span>
                    <span class="capitalize px-1 {{ $loop->last ? 'text-yellow-300' : '' }}">
                        {{ str_replace('-', ' ', $segment) }}
                    </span>
                @endforeach
            </div>
        </div>
    </div>    <div class="py-12">
        <div class="max-w-7xl mx-auto sm:px-6 lg:px-8">
            @if(isset($allSettings) && count($allSettings) > 0)
                @foreach($allSettings as $content)
                    <div class="bg-white overflow-hidden shadow-sm sm:rounded-lg mb-8">
                        <div class="p-6">
                            <div class="prose max-w-none">
                                @if(isset($content['image']) && $content['image'])
                                    <div class="mb-6">
                                        <img src="{{ asset($content['image']) }}" 
                                             alt="{{ $content['name'] ?? '' }}" 
                                             class="w-full h-auto rounded-lg object-cover">
                                    </div>
                                @endif

                                <div class="space-y-4">
                                    {!! $content['content'] !!}
                                </div>
                            </div>
                        </div>
                    </div>
                @endforeach
            @endif
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
            font-family: Arial, sans-serif !important;
            font-size: 14px !important;
            line-height: 1.6 !important;
            color: #333 !important;
            display: block !important;
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
            font-weight: bold !important;
            font-family: Arial, sans-serif !important;
            color: #222 !important;
            line-height: 1.2 !important;
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
            font-family: Arial, sans-serif !important;
        }

        .dynamic-content img {
            margin-top: 1.5em !important;
            margin-bottom: 1.5em !important;
            max-width: 100% !important;
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
            display: inline !important;
            width: auto !important;
            font-family: inherit !important;
            font-size: inherit !important;
            line-height: inherit !important;
            color: inherit !important;
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
        
        .dynamic-content a {
            color: #0066cc !important;
            text-decoration: underline !important;
        }
        
        .dynamic-content strong, 
        .dynamic-content b {
            font-weight: bold !important;
        }
        
        .dynamic-content em, 
        .dynamic-content i {
            font-style: italic !important;
        }
        
        .dynamic-content table {
            border-collapse: collapse !important;
            width: auto !important;
            margin: 1.5em 0 !important;
        }
        
        .dynamic-content th, 
        .dynamic-content td {
            border: 1px solid #ddd !important;
            padding: 8px !important;
            text-align: left !important;
        }
        
        .dynamic-content th {
            background-color: #f2f2f2 !important;
            font-weight: bold !important;
        }
        
        .dynamic-content code, 
        .dynamic-content pre {
            font-family: monospace !important;
            background-color: #f5f5f5 !important;
            padding: 2px 4px !important;
            border-radius: 3px !important;
        }
        
        /* Mengganti penyajian teks untuk memastikan konsistensi dengan editor */
        .dynamic-content h1 { font-size: 2em !important; }
        .dynamic-content h2 { font-size: 1.5em !important; }
        .dynamic-content h3 { font-size: 1.17em !important; }
        .dynamic-content h4 { font-size: 1em !important; }
        .dynamic-content h5 { font-size: 0.83em !important; }
        .dynamic-content h6 { font-size: 0.67em !important; }
    </style>
    @endpush
</x-main-layout>