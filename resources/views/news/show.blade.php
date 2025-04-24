<x-main-layout>
    <div class="py-12">
        <div class="max-w-7xl mx-auto sm:px-6 lg:px-8">
            <div class="bg-white overflow-hidden shadow-sm sm:rounded-lg">

                <div class="p-6">
                    <!-- Breadcrumb -->
                    <div class="flex items-center text-sm text-gray-600 mb-6">
                        <a href="/" class="hover:text-blue-600">Beranda</a>
                        <span class="mx-2">/</span>
                        <a href="#" class="hover:text-blue-600">{{ $news->kategori->name }}</a>
                        <span class="mx-2">/</span>
                        <span>{{ $news->title }}</span>
                    </div>

                    <!-- Title -->
                    <h1 class="text-3xl font-bold mb-4">{{ $news->title }}</h1>

                    <!-- Meta Info -->
                    <div class="flex items-center text-gray-600 text-sm mb-6">
                        <span>{{ \Carbon\Carbon::parse($news->created_at)->format('d F Y') }}</span>
                        <span class="mx-2">•</span>
                        <span>Oleh: {{ $news->creator->name }}</span>
                        {{-- <span class="mx-2">•</span>
                        <span>Dilihat: {{ $news->counter }} kali</span> --}}
                    </div>
                    
                    <!-- Featured Image -->
                    @if($news->image)
                    <div class="w-full flex justify-center p-6">
                        <div class="max-w-xl w-full">
                            <img src="{{ $news->image }}" 
                                alt="{{ $news->title }}" 
                                class="w-full h-auto rounded-lg shadow-lg">
                        </div>
                    </div>
                    @endif

                    <!-- Content -->
                    <div class="prose max-w-none news-content">
                        {!! $news->content !!}
                    </div>
                </div>
            </div>
        </div>
    </div>

    @push('styles')
    <style>
        .news-content {
            --tw-prose-p-spacing: 1.5em;
        }
        
        .news-content p {
            margin-top: 1.5em;
            margin-bottom: 1.5em;
        }

        .news-content p:empty {
            min-height: 1em;
            display: block;
        }

        .news-content ul, 
        .news-content ol {
            margin-top: 1.25em;
            margin-bottom: 1.25em;
        }

        .news-content h1, 
        .news-content h2, 
        .news-content h3, 
        .news-content h4, 
        .news-content h5, 
        .news-content h6 {
            margin-top: 1.5em;
            margin-bottom: 0.75em;
        }

        .news-content blockquote,
        .news-content pre,
        .news-content figure,
        .news-content table {
            margin-top: 1.5em;
            margin-bottom: 1.5em;
        }

        .news-content * {
            line-height: 1.6;
        }

        .news-content img {
            margin-top: 1.5em;
            margin-bottom: 1.5em;
        }

        .news-content iframe {
            margin-top: 1.5em;
            margin-bottom: 1.5em;
            max-width: 100%;
        }

        .news-content p + p {
            margin-top: 1.5em;
        }

        .news-content [style] {
            max-width: 100%;
            box-sizing: border-box;
        }
        
        .news-content > *:first-child {
            margin-top: 0 !important;
        }
        
        .news-content div {
            margin-top: 1.5em;
            margin-bottom: 1.5em;
        }
        
        .news-content p span {
            display: inline-block;
            width: 100%;
        }
        
        .news-content > div,
        .news-content > p {
            margin-bottom: 1.5em !important;
        }
        
        .news-content br + br {
            display: block;
            content: "";
            margin-top: 1.5em;
        }
        
        .news-content ul,
        .news-content ol {
            padding-left: 2em !important;
            list-style-position: outside !important;
        }
        
        .news-content ul {
            list-style-type: disc !important;
        }
        
        .news-content ol {
            list-style-type: decimal !important;
        }
        
        .news-content ul li,
        .news-content ol li {
            display: list-item !important;
            margin-bottom: 0.5em !important;
        }
        
        .news-content ul > li::marker,
        .news-content ol > li::marker {
            display: inline-block !important;
        }
        
        .news-content li ul,
        .news-content li ol {
            margin-top: 0.5em !important;
        }
    </style>
    @endpush
</x-main-layout> 