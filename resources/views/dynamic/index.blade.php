<x-main-layout>
    <div class="py-12">
        <div class="max-w-7xl mx-auto sm:px-6 lg:px-8">
            <div class="bg-white overflow-hidden shadow-sm sm:rounded-lg">
                <div class="p-6">
                    <h1 class="text-2xl font-bold mb-6">{{ $setting->name }}</h1>
                    <div class="prose max-w-none">
                        <h2 class="text-xl font-semibold mb-4">Content</h2>
                        <p>{{ $setting->content ?? 'Content not available' }}</p>
                    </div>
                </div>
            </div>
        </div>
    </div>
</x-main-layout> 