<div class="container mx-auto px-4 py-8">
    <div class="flex justify-between items-start">
        <div class="w-2/3">
            <h2 class="text-2xl font-bold">BERITA TERKINI</h2>
            <x-news-item :news="$news" />
        </div>
        
        <div class="w-1/3 bg-white p-4 shadow-md rounded-lg">
            <h2 class="text-xl font-bold mb-4">AGENDA</h2>
            <div class="flex flex-col">
                @foreach($agenda as $item)
                    <div class="mb-4">
                        <div class="font-bold">{{ $item->title }}</div>
                        <div class="text-sm text-gray-600">{{ \Carbon\Carbon::parse($item->tanggal)->format('d M') }}</div>
                        <div class="text-sm">{{ $item->keterangan }}</div>
                    </div>
                @endforeach
            </div>
        </div>
    </div>
</div> 