@if($programKerjas->isEmpty())
    <div class="text-center py-10 bg-gray-50 rounded-lg">
        <svg class="w-16 h-16 text-gray-400 mx-auto mb-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12h6m-6 4h6m2 5H7a2 2 0 01-2-2V5a2 2 0 012-2h5.586a1 1 0 01.707.293l5.414 5.414a1 1 0 01.293.707V19a2 2 0 01-2 2z"></path>
        </svg>
        <h3 class="text-lg font-medium text-gray-600">Belum ada program kerja/kegiatan untuk kriteria yang dipilih</h3>
        <p class="text-gray-500 mt-2">Silahkan pilih filter lain</p>
    </div>
@else
    @foreach($programKerjas as $program)
        <div class="bg-white rounded-lg shadow-md overflow-hidden hover:shadow-lg transition-shadow duration-300 border border-gray-100">
            <div class="p-6">
                <div class="flex justify-between items-start">
                    <div class="flex items-center gap-3">
                        <div class="inline-flex items-center px-3 py-1.5 rounded-md bg-purple-100 text-purple-800 text-sm font-medium">
                            <span>Tahun {{ $program->tahun }}</span>
                        </div>
                        <div class="inline-flex items-center px-3 py-1.5 rounded-md bg-blue-50 text-blue-700 text-sm font-medium">
                            <svg class="h-4 w-4 mr-1 text-blue-500" xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 21V5a2 2 0 00-2-2H7a2 2 0 00-2 2v16m14 0h2m-2 0h-5m-9 0H3m2 0h5M9 7h1m-1 4h1m4-4h1m-1 4h1m-5 10v-5a1 1 0 011-1h2a1 1 0 011 1v5m-4 0h4" />
                            </svg>
                            <span>{{ $program->opd->name }}</span>
                        </div>
                    </div>
                    <div class="flex items-center space-x-2">
                        <a href="{{ route('profil.program.edit', $program->id) }}" class="p-2 text-blue-600 hover:text-white hover:bg-blue-600 focus:outline-none rounded-md transition-colors" title="Edit">
                            <svg class="h-5 w-5" xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15.232 5.232l3.536 3.536m-2.036-5.036a2.5 2.5 0 113.536 3.536L6.5 21.036H3v-3.572L16.732 3.732z" />
                            </svg>
                        </a>
                        {{-- <button type="button" 
                               class="p-2 text-red-600 hover:text-white hover:bg-red-600 focus:outline-none rounded-md transition-colors delete-program" 
                               title="Hapus" 
                               data-id="{{ $program->id }}">
                            <svg class="h-5 w-5" xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 7l-.867 12.142A2 2 0 0116.138 21H7.862a2 2 0 01-1.995-1.858L5 7m5 4v6m4-6v6m1-10V4a1 1 0 00-1-1h-4a1 1 0 00-1 1v3M4 7h16" />
                            </svg>
                        </button> --}}
                    </div>
                </div>
                
                <div class="mt-4">
                    <div class="program-content">{!! $program->description !!}</div>
                </div>
            </div>
        </div>
    @endforeach
@endif 