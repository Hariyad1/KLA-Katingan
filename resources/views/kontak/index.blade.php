<x-main-layout>
    <div class="relative bg-gradient-to-br from-blue-400 to-indigo-600">
        <div class="absolute bottom-0 left-0 w-full overflow-hidden rotate-180">
            <svg class="relative block w-full h-[50px]" viewBox="0 0 1200 120" preserveAspectRatio="none">
                <path d="M321.39,56.44c58-10.79,114.16-30.13,172-41.86,82.39-16.72,168.19-17.73,250.45-.39C823.78,31,906.67,72,985.66,92.83c70.05,18.48,146.53,26.09,214.34,3V0H0V27.35A600.21,600.21,0,0,0,321.39,56.44Z" 
                      class="fill-gray-50">
                </path>
            </svg>
        </div>
        
        <div class="relative z-10 max-w-7xl mx-auto px-4 sm:px-6 lg:px-8 py-12 text-white">
            <h1 class="text-3xl font-bold mb-2">Kontak Kami</h1>
            <p class="text-lg opacity-90">Hubungi kami secara online dengan mengisi form di bawah ini</p>
        </div>
    </div>

    <div class="py-12">
        <div class="max-w-7xl mx-auto sm:px-6 lg:px-8">
            <div class="bg-white overflow-hidden shadow-sm sm:rounded-lg">
                <div class="p-6">
                    @if(session('success'))
                        <div class="bg-green-100 border border-green-400 text-green-700 px-4 py-3 rounded relative mb-4" role="alert">
                            <span class="block sm:inline">{{ session('success') }}</span>
                        </div>
                    @endif

                    <form action="{{ route('kontak') }}" method="POST" class="space-y-4">
                        @csrf
                        <div>
                            <label for="nama" class="block text-sm font-medium text-gray-700">Nama</label>
                            <input type="text" name="nama" id="nama" value="{{ old('nama') }}" required 
                                class="mt-1 block w-full border-gray-300 rounded-md shadow-sm focus:border-blue-500 focus:ring focus:ring-blue-200 @error('nama') border-red-500 @enderror">
                            @error('nama')
                                <p class="text-red-500 text-xs mt-1">{{ $message }}</p>
                            @enderror
                        </div>

                        <div>
                            <label for="email" class="block text-sm font-medium text-gray-700">Email</label>
                            <input type="email" name="email" id="email" value="{{ old('email') }}" required 
                                class="mt-1 block w-full border-gray-300 rounded-md shadow-sm focus:border-blue-500 focus:ring focus:ring-blue-200 @error('email') border-red-500 @enderror">
                            @error('email')
                                <p class="text-red-500 text-xs mt-1">{{ $message }}</p>
                            @enderror
                        </div>

                        <div>
                            <label for="subjek" class="block text-sm font-medium text-gray-700">Subjek</label>
                            <input type="text" name="subjek" id="subjek" value="{{ old('subjek') }}" required 
                                class="mt-1 block w-full border-gray-300 rounded-md shadow-sm focus:border-blue-500 focus:ring focus:ring-blue-200 @error('subjek') border-red-500 @enderror">
                            @error('subjek')
                                <p class="text-red-500 text-xs mt-1">{{ $message }}</p>
                            @enderror
                        </div>

                        <div>
                            <label for="isi" class="block text-sm font-medium text-gray-700">Isi Pesan</label>
                            <textarea name="isi" id="isi" rows="4" required 
                                class="mt-1 block w-full border-gray-300 rounded-md shadow-sm focus:border-blue-500 focus:ring focus:ring-blue-200 @error('isi') border-red-500 @enderror">{{ old('isi') }}</textarea>
                            @error('isi')
                                <p class="text-red-500 text-xs mt-1">{{ $message }}</p>
                            @enderror
                        </div>

                        <button type="submit" 
                            class="w-full sm:w-auto px-6 py-3 bg-blue-600 text-white font-medium rounded-md hover:bg-blue-700 focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-blue-500">
                            Kirim Pesan
                        </button>
                    </form>

                    {{-- @if(count($contacts) > 0)
                        <div class="mt-12">
                            <h2 class="text-xl font-semibold mb-4">Pesan Terbaru</h2>
                            <div class="space-y-4">
                                @foreach($contacts as $contact)
                                    <div class="bg-gray-50 p-4 rounded-lg">
                                        <div class="flex justify-between items-start">
                                            <div>
                                                <h3 class="font-medium">{{ $contact->nama }}</h3>
                                                <p class="text-sm text-gray-600">{{ $contact->email }}</p>
                                            </div>
                                            <span class="text-sm text-gray-500">{{ $contact->created_at->diffForHumans() }}</span>
                                        </div>
                                        <p class="mt-2 font-medium">{{ $contact->subjek }}</p>
                                        <p class="mt-1 text-gray-600">{{ $contact->isi }}</p>
                                    </div>
                                @endforeach
                            </div>
                        </div>
                    @endif --}}
                </div>
            </div>
        </div>
    </div>
</x-main-layout> 