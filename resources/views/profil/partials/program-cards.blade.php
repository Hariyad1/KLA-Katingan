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
                        </div>                    </div>
                </div>
                
                <div class="mt-4">
                    <div class="program-content">{!! $program->description !!}</div>
                </div>
            </div>
        </div>
    @endforeach
@endif 