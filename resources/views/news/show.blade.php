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
                    <div class="prose max-w-none">
                        {!! $news->content !!}
                    </div>
                </div>
            </div>
        </div>
    </div>
</x-main-layout> 