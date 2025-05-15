<div class="flex flex-col space-y-4">
    @foreach($news as $item)
    <div class="flex space-x-4">
        <div class="w-1/3">
            @if($item->image)
                <img src="{{ $item->image }}" alt="{{ $item->title }}" class="w-full h-48 object-cover rounded-lg">
            @else
                <div class="w-full h-48 bg-gray-200 rounded-lg"></div>
            @endif
        </div>
        <div class="w-2/3">
            <h3 class="text-xl font-semibold text-blue-600 hover:text-blue-800">
                <a href="{{ route('news.show', [$item->id, Str::slug($item->title)]) }}">{{ $item->title }}</a>
            </h3>
            <p class="text-gray-600 text-sm mt-2">{{ \Carbon\Carbon::parse($item->created_at)->format('d F Y') }}</p>
            <p class="text-gray-700 mt-2 line-clamp-3">{{ Str::limit(strip_tags($item->content), 150) }}</p>
            <a href="{{ route('news.show', $item->id) }}" class="text-blue-600 hover:text-blue-800 mt-2 inline-block">... selengkapnya</a>
        </div>
    </div>
    @endforeach
</div> 