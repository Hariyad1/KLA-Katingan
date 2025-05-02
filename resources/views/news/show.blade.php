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
            margin-top: 1.5em !important;
            margin-bottom: 1.5em !important;
            font-family: Arial, sans-serif !important;
            font-size: 14px !important;
            line-height: 1.6 !important;
            color: #333 !important;
            display: block !important;
        }

        .news-content p:empty {
            min-height: 1em !important;
            display: block !important;
        }

        .news-content ul, 
        .news-content ol {
            margin-top: 1.25em !important;
            margin-bottom: 1.25em !important;
            padding-left: 2em !important;
            list-style-position: outside !important;
        }

        .news-content h1, 
        .news-content h2, 
        .news-content h3, 
        .news-content h4, 
        .news-content h5, 
        .news-content h6 {
            margin-top: 1.5em !important;
            margin-bottom: 0.75em !important;
            font-weight: bold !important;
            font-family: Arial, sans-serif !important;
            color: #222 !important;
            line-height: 1.2 !important;
        }

        .news-content blockquote,
        .news-content pre,
        .news-content figure,
        .news-content table {
            margin-top: 1.5em !important;
            margin-bottom: 1.5em !important;
        }

        .news-content * {
            line-height: 1.6 !important;
            font-family: Arial, sans-serif !important;
        }

        .news-content img {
            margin-top: 1.5em !important;
            margin-bottom: 1.5em !important;
            max-width: 100% !important;
        }

        .news-content iframe {
            margin-top: 1.5em !important;
            margin-bottom: 1.5em !important;
            max-width: 100% !important;
        }

        .news-content p + p {
            margin-top: 1.5em !important;
        }

        .news-content [style] {
            max-width: 100% !important;
            box-sizing: border-box !important;
        }
        
        .news-content > *:first-child {
            margin-top: 0 !important;
        }
        
        .news-content div {
            margin-top: 1.5em !important;
            margin-bottom: 1.5em !important;
        }
        
        .news-content p span {
            display: inline !important;
            width: auto !important;
            font-family: inherit !important;
            font-size: inherit !important;
            line-height: inherit !important;
            color: inherit !important;
        }
        
        .news-content > div,
        .news-content > p {
            margin-bottom: 1.5em !important;
        }
        
        .news-content br + br {
            display: block !important;
            content: "" !important;
            margin-top: 1.5em !important;
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
        
        .news-content a {
            color: #0066cc !important;
            text-decoration: underline !important;
        }
        
        .news-content strong, 
        .news-content b {
            font-weight: bold !important;
        }
        
        .news-content em, 
        .news-content i {
            font-style: italic !important;
        }
        
        .news-content table {
            border-collapse: collapse !important;
            width: auto !important;
            margin: 1.5em 0 !important;
        }
        
        .news-content th, 
        .news-content td {
            border: 1px solid #ddd !important;
            padding: 8px !important;
            text-align: left !important;
        }
        
        .news-content th {
            background-color: #f2f2f2 !important;
            font-weight: bold !important;
        }
        
        .news-content code, 
        .news-content pre {
            font-family: monospace !important;
            background-color: #f5f5f5 !important;
            padding: 2px 4px !important;
            border-radius: 3px !important;
        }
        
        /* Mengganti penyajian teks untuk memastikan konsistensi dengan editor */
        .news-content h1 { font-size: 2em !important; }
        .news-content h2 { font-size: 1.5em !important; }
        .news-content h3 { font-size: 1.17em !important; }
        .news-content h4 { font-size: 1em !important; }
        .news-content h5 { font-size: 0.83em !important; }
        .news-content h6 { font-size: 0.67em !important; }
    </style>
    @endpush
</x-main-layout> 