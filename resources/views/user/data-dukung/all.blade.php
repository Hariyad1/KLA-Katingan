<x-app-layout>
    <x-slot name="header">
        <h2 class="font-semibold text-xl text-gray-800 leading-tight">
            {{ __('Semua Data Dukung') }}
        </h2>
    </x-slot>

    <div class="py-12">
        <div class="max-w-7xl mx-auto sm:px-6 lg:px-8">
            <div class="bg-white overflow-hidden shadow-sm sm:rounded-lg">
                <div class="p-6 text-gray-900">
                    <!-- Show Entries & Filter Section -->
                    <div class="mb-6 grid grid-cols-1 md:grid-cols-4 gap-4">
                        <div>
                            <label for="entries" class="block text-sm font-medium text-gray-700 mb-1">Show Entries</label>
                            <select id="entries" class="w-full rounded-md border-gray-300 shadow-sm focus:border-indigo-500 focus:ring-indigo-500">
                                <option value="10">10</option>
                                <option value="25">25</option>
                                <option value="50">50</option>
                                <option value="100">100</option>
                            </select>
                        </div>
                        <div>
                            <label for="opd_filter" class="block text-sm font-medium text-gray-700 mb-1">OPD</label>
                            <select id="opd_filter" class="w-full rounded-md border-gray-300 shadow-sm focus:border-indigo-500 focus:ring-indigo-500">
                                <option value="">Semua OPD</option>
                                @foreach($opds as $opd)
                                    <option value="{{ $opd->id }}">{{ $opd->name }}</option>
                                @endforeach
                            </select>
                        </div>
                        <div>
                            <label for="klaster_filter" class="block text-sm font-medium text-gray-700 mb-1">Klaster</label>
                            <select id="klaster_filter" class="w-full rounded-md border-gray-300 shadow-sm focus:border-indigo-500 focus:ring-indigo-500">
                                <option value="">Semua Klaster</option>
                                @foreach($klasters as $klaster)
                                    <option value="{{ $klaster->id }}">{{ $klaster->name }}</option>
                                @endforeach
                            </select>
                        </div>
                        <div>
                            <label for="search" class="block text-sm font-medium text-gray-700 mb-1">Pencarian</label>
                            <input type="text" id="search" class="w-full rounded-md border-gray-300 shadow-sm focus:border-indigo-500 focus:ring-indigo-500" placeholder="Cari data dukung...">
                        </div>
                    </div>

                    <!-- Data Grid -->
                    <div class="overflow-x-auto bg-white rounded-lg shadow overflow-y-auto relative">
                        <table class="border-collapse table-auto w-full whitespace-no-wrap bg-white table-striped relative">
                            <thead>
                                <tr class="text-left">
                                    <th class="bg-gray-50 sticky top-0 border-b border-gray-200 px-6 py-3 text-gray-600 font-bold tracking-wider uppercase text-xs">No</th>
                                    <th class="bg-gray-50 sticky top-0 border-b border-gray-200 px-6 py-3 text-gray-600 font-bold tracking-wider uppercase text-xs">OPD</th>
                                    <th class="bg-gray-50 sticky top-0 border-b border-gray-200 px-6 py-3 text-gray-600 font-bold tracking-wider uppercase text-xs">Klaster</th>
                                    <th class="bg-gray-50 sticky top-0 border-b border-gray-200 px-6 py-3 text-gray-600 font-bold tracking-wider uppercase text-xs">Indikator</th>
                                    <th class="bg-gray-50 sticky top-0 border-b border-gray-200 px-6 py-3 text-gray-600 font-bold tracking-wider uppercase text-xs">File</th>
                                </tr>
                            </thead>
                            <tbody class="bg-white divide-y divide-gray-200">
                                @forelse($dataDukungs as $index => $dataDukung)
                                    <tr class="hover:bg-gray-50">
                                        <td class="border-t px-6 py-4">{{ $index + 1 }}</td>
                                        <td class="border-t px-6 py-4">{{ $dataDukung->opd->name }}</td>
                                        <td class="border-t px-6 py-4">{{ $dataDukung->indikator->klaster->name }}</td>
                                        <td class="border-t px-6 py-4">{{ $dataDukung->indikator->name }}</td>
                                        <td class="border-t px-6 py-4">
                                            <div class="space-y-2">
                                                @foreach($dataDukung->files as $file)
                                                    <div class="flex items-center justify-between bg-gray-50 p-2 rounded-lg">
                                                        <div class="flex items-center space-x-3">
                                                            @php
                                                                $extension = pathinfo($file->original_name, PATHINFO_EXTENSION);
                                                                $iconClass = match(strtolower($extension)) {
                                                                    'pdf' => 'fa-file-pdf text-red-500',
                                                                    'doc', 'docx' => 'fa-file-word text-blue-500',
                                                                    'xls', 'xlsx' => 'fa-file-excel text-green-500',
                                                                    'jpg', 'jpeg', 'png' => 'fa-file-image text-purple-500',
                                                                    default => 'fa-file text-gray-500'
                                                                };
                                                            @endphp
                                                            <i class="fas {{ $iconClass }} text-lg"></i>
                                                            <div class="flex flex-col">
                                                                <span class="text-sm text-gray-600">{{ $file->original_name }}</span>
                                                                <span class="text-xs text-gray-500">{{ number_format($file->size / 1024 / 1024, 2) }} MB</span>
                                                            </div>
                                                        </div>
                                                        <div>
                                                            <a href="{{ Storage::url($file->file) }}" 
                                                               target="_blank"
                                                               class="text-indigo-600 hover:text-indigo-900">
                                                                <i class="fas fa-eye"></i>
                                                            </a>
                                                        </div>
                                                    </div>
                                                @endforeach
                                            </div>
                                        </td>
                                    </tr>
                                @empty
                                    <tr>
                                        <td colspan="5" class="px-6 py-10 text-center">
                                            <div class="flex flex-col items-center justify-center">
                                                <i class="fas fa-folder-open text-gray-400 text-5xl mb-4"></i>
                                                <span class="text-gray-500 text-lg">Tidak ada data dukung yang tersedia</span>
                                            </div>
                                        </td>
                                    </tr>
                                @endforelse
                            </tbody>
                        </table>
                    </div>

                    <!-- Enhanced Pagination -->
                    <div class="mt-4 flex items-center justify-between">
                        <div class="text-sm text-gray-700">
                            Showing 
                            <span class="font-medium">{{ $dataDukungs->firstItem() }}</span>
                            to
                            <span class="font-medium">{{ $dataDukungs->lastItem() }}</span>
                            of
                            <span class="font-medium">{{ $dataDukungs->total() }}</span>
                            results
                        </div>
                        <div class="flex items-center space-x-4">
                            @if($dataDukungs->onFirstPage())
                                <span class="px-4 py-2 text-sm font-medium text-gray-400 bg-gray-100 border border-gray-300 rounded-md cursor-not-allowed">
                                    Prev
                                </span>
                            @else
                                <a href="{{ $dataDukungs->previousPageUrl() }}" class="px-4 py-2 text-sm font-medium text-gray-700 bg-white border border-gray-300 rounded-md hover:bg-gray-50">
                                    Prev
                                </a>
                            @endif

                            <div class="text-sm text-gray-700">
                                Page {{ $dataDukungs->currentPage() }} of {{ $dataDukungs->lastPage() }}
                            </div>

                            @if($dataDukungs->hasMorePages())
                                <a href="{{ $dataDukungs->nextPageUrl() }}" class="px-4 py-2 text-sm font-medium text-gray-700 bg-white border border-gray-300 rounded-md hover:bg-gray-50">
                                    Next
                                </a>
                            @else
                                <span class="px-4 py-2 text-sm font-medium text-gray-400 bg-gray-100 border border-gray-300 rounded-md cursor-not-allowed">
                                    Next
                                </span>
                            @endif
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>

    @push('scripts')
    <script>
        // Existing filter functionality
        const opdFilter = document.getElementById('opd_filter');
        const klasterFilter = document.getElementById('klaster_filter');
        const searchInput = document.getElementById('search');
        const entriesSelect = document.getElementById('entries');
        let timeoutId;

        function applyFilters() {
            const opd = opdFilter.value;
            const klaster = klasterFilter.value;
            const search = searchInput.value;
            const perPage = entriesSelect.value;

            const params = new URLSearchParams(window.location.search);
            if (opd) params.set('opd', opd);
            else params.delete('opd');
            if (klaster) params.set('klaster', klaster);
            else params.delete('klaster');
            if (search) params.set('search', search);
            else params.delete('search');
            if (perPage) params.set('per_page', perPage);
            else params.delete('per_page');

            window.location.search = params.toString();
        }

        opdFilter.addEventListener('change', applyFilters);
        klasterFilter.addEventListener('change', applyFilters);
        entriesSelect.addEventListener('change', applyFilters);
        
        searchInput.addEventListener('input', () => {
            clearTimeout(timeoutId);
            timeoutId = setTimeout(applyFilters, 500);
        });

        // Set filter values from URL params
        const params = new URLSearchParams(window.location.search);
        if (params.has('opd')) opdFilter.value = params.get('opd');
        if (params.has('klaster')) klasterFilter.value = params.get('klaster');
        if (params.has('search')) searchInput.value = params.get('search');
        if (params.has('per_page')) entriesSelect.value = params.get('per_page');
    </script>
    @endpush

    @push('styles')
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/5.15.4/css/all.min.css">
    @endpush
</x-app-layout> 