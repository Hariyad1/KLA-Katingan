<x-app-layout>
    <x-slot name="header">
        <div class="flex justify-between items-center">
            <h2 class="font-semibold text-xl text-gray-800 leading-tight">
                {{ __('Program Kerja') }}
            </h2>
            <a href="{{ route('admin.program-kerja.create') }}" class="px-4 py-2 bg-blue-600 text-white rounded-md hover:bg-blue-700 transition-colors">
                Tambah Program Kerja
            </a>
        </div>
    </x-slot>

    @push('styles')
    <style>
        .line-clamp-2 {
            display: -webkit-box;
            -webkit-line-clamp: 2;
            -webkit-box-orient: vertical;
            overflow: hidden;
            text-overflow: ellipsis;
        }
    </style>
    @endpush

    <div class="py-12">
        <div class="max-w-7xl mx-auto sm:px-6 lg:px-8 space-y-6">
            <!-- Statistics Cards -->
            <div class="grid grid-cols-1 md:grid-cols-3 gap-6">
                <div class="bg-gradient-to-r from-blue-500 to-blue-600 rounded-lg shadow p-6">
                    <div class="flex items-center">
                        <div class="flex-shrink-0">
                            <svg class="h-8 w-8 text-white" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12h6m-6 4h6m2 5H7a2 2 0 01-2-2V5a2 2 0 012-2h5.586a1 1 0 01.707.293l5.414 5.414a1 1 0 01.293.707V19a2 2 0 01-2 2z"></path>
                            </svg>
                        </div>
                        <div class="ml-5 w-0 flex-1">
                            <dl>
                                <dt class="text-sm font-medium text-blue-100 truncate">Total Program Kerja</dt>
                                <dd class="text-lg font-semibold text-white">{{ $totalPrograms }}</dd>
                            </dl>
                        </div>
                    </div>
                </div>

                <div class="bg-gradient-to-r from-green-500 to-green-600 rounded-lg shadow p-6">
                    <div class="flex items-center">
                        <div class="flex-shrink-0">
                            <svg class="h-8 w-8 text-white" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 21V5a2 2 0 00-2-2H7a2 2 0 00-2 2v16m14 0h2m-2 0h-5m-9 0H3m2 0h5M9 7h1m-1 4h1m4-4h1m-1 4h1m-5 10v-5a1 1 0 011-1h2a1 1 0 011 1v5m-4 0h4"></path>
                            </svg>
                        </div>
                        <div class="ml-5 w-0 flex-1">
                            <dl>
                                <dt class="text-sm font-medium text-green-100 truncate">OPD Terlibat</dt>
                                <dd class="text-lg font-semibold text-white">{{ $totalOpds }}</dd>
                            </dl>
                        </div>
                    </div>
                </div>

                <div class="bg-gradient-to-r from-purple-500 to-purple-600 rounded-lg shadow p-6">
                    <div class="flex items-center">
                        <div class="flex-shrink-0">
                            <svg class="h-8 w-8 text-white" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M8 7V3m8 4V3m-9 8h10M5 21h14a2 2 0 002-2V7a2 2 0 00-2-2H5a2 2 0 00-2 2v12a2 2 0 002 2z"></path>
                            </svg>
                        </div>
                        <div class="ml-5 w-0 flex-1">
                            <dl>
                                <dt class="text-sm font-medium text-purple-100 truncate">Program Tahun Ini</dt>
                                <dd class="text-lg font-semibold text-white">{{ $programsThisYear }}</dd>
                            </dl>
                        </div>
                    </div>
                </div>
            </div>

            <div class="bg-white overflow-hidden shadow-sm sm:rounded-lg">
                <div class="p-6 bg-white border-b border-gray-200">
                    
                    <!-- Search and Filter Section -->
                    <div class="mb-6 flex flex-col sm:flex-row gap-4">
                        <div class="flex-1">
                            <input type="text" id="searchInput" placeholder="Cari program kerja..." 
                                   class="w-full px-4 py-2 border border-gray-300 rounded-md focus:ring-indigo-500 focus:border-indigo-500">
                        </div>
                        <div class="sm:w-48">
                            <select id="filterTahun" class="w-full px-4 py-2 border border-gray-300 rounded-md focus:ring-indigo-500 focus:border-indigo-500">
                                <option value="">Semua Tahun</option>
                                @foreach($programKerjas->pluck('tahun')->unique()->sort()->reverse() as $tahun)
                                    <option value="{{ $tahun }}">{{ $tahun }}</option>
                                @endforeach
                            </select>
                        </div>
                        <div class="sm:w-64">
                            <select id="filterOpd" class="w-full px-4 py-2 border border-gray-300 rounded-md focus:ring-indigo-500 focus:border-indigo-500">
                                <option value="">Semua OPD</option>
                                @foreach($programKerjas->pluck('opd')->unique('id')->sortBy('name') as $opd)
                                    <option value="{{ $opd->id }}">{{ $opd->name }}</option>
                                @endforeach
                            </select>
                        </div>
                    </div>                    <!-- Loading Indicator -->
                    <div id="loadingIndicator" class="hidden">
                        <div class="flex justify-center items-center py-4">
                            <div class="animate-spin rounded-full h-8 w-8 border-b-2 border-indigo-600"></div>
                            <span class="ml-2 text-gray-600">Memuat data...</span>
                        </div>
                    </div>                    <div class="overflow-x-auto bg-white rounded-lg shadow">                        <table class="w-full divide-y divide-gray-200" id="programKerjaTable">
                            <thead class="bg-gray-50">
                                <tr>
                                    <th scope="col" class="w-16 px-4 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">No</th>
                                    <th scope="col" class="w-20 px-4 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Tahun</th>
                                    <th scope="col" class="w-72 px-4 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Perangkat Daerah</th>
                                    <th scope="col" class="px-4 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Deskripsi Program Kerja</th>
                                    <th scope="col" class="w-32 px-4 py-3 text-center text-xs font-medium text-gray-500 uppercase tracking-wider">Aksi</th>
                                </tr>
                            </thead>
                            <tbody class="bg-white divide-y divide-gray-200" id="programKerjaTableBody">
                                @foreach($programKerjas as $index => $program)
                                    <tr data-tahun="{{ $program->tahun }}" data-opd="{{ $program->opd_id }}" data-description="{{ strtolower(strip_tags($program->description)) }}" data-opd-name="{{ strtolower($program->opd->name) }}">                                        <td class="px-4 py-4 whitespace-nowrap text-sm text-gray-500 text-center">{{ $index + 1 }}</td>
                                        <td class="px-4 py-4 whitespace-nowrap text-sm text-gray-500 text-center">
                                            <span class="px-2 py-1 text-xs font-semibold rounded-full bg-blue-100 text-blue-800">{{ $program->tahun }}</span>
                                        </td>
                                        <td class="px-4 py-4 text-sm font-medium text-gray-900">
                                            <div class="truncate" title="{{ $program->opd->name }}">{{ $program->opd->name }}</div>
                                        </td>                                        <td class="px-4 py-4 text-sm text-gray-900">
                                            <div class="line-clamp-2 leading-relaxed" title="{{ strip_tags($program->description) }}">
                                                {!! Str::limit(strip_tags($program->description), 120) ?? 'Tidak ada deskripsi' !!}
                                            </div>
                                        </td>
                                        <td class="px-4 py-4 whitespace-nowrap text-center text-sm font-medium">
                                            <div class="flex justify-center space-x-2">                                                <a href="{{ route('admin.program-kerja.edit', $program) }}" 
                                                   class="inline-flex items-center p-2 border border-gray-300 shadow-sm text-sm leading-4 font-medium rounded-md text-gray-700 bg-white hover:bg-gray-50 focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-indigo-500" title="Edit">
                                                    <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M11 5H6a2 2 0 00-2 2v11a2 2 0 002 2h11a2 2 0 002-2v-5m-1.414-9.414a2 2 0 112.828 2.828L11.828 15H9v-2.828l8.586-8.586z"></path>
                                                    </svg>
                                                </a>
                                                
                                                <button onclick="deleteProgramKerja({{ $program->id }})" 
                                                        class="inline-flex items-center p-2 border border-red-300 shadow-sm text-sm leading-4 font-medium rounded-md text-red-700 bg-white hover:bg-red-50 focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-red-500" title="Hapus">
                                                    <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 7l-.867 12.142A2 2 0 0116.138 21H7.862a2 2 0 01-1.995-1.858L5 7m5 4v6m4-6v6m1-10V4a1 1 0 00-1-1h-4a1 1 0 00-1 1v3M4 7h16"></path>
                                                    </svg>
                                                </button>
                                            </div>
                                        </td>
                                    </tr>
                                @endforeach
                                
                                @if($programKerjas->isEmpty())
                                    <tr id="emptyRow">
                                        <td colspan="6" class="px-6 py-8 text-center text-gray-500">
                                            <div class="flex flex-col items-center">
                                                <svg class="w-12 h-12 text-gray-400 mb-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12h6m-6 4h6m2 5H7a2 2 0 01-2-2V5a2 2 0 012-2h5.586a1 1 0 01.707.293l5.414 5.414a1 1 0 01.293.707V19a2 2 0 01-2 2z"></path>
                                                </svg>
                                                <p class="text-lg font-medium">Tidak ada data program kerja</p>
                                                <p class="text-sm text-gray-400 mt-1">Mulai dengan menambahkan program kerja baru</p>
                                            </div>
                                        </td>
                                    </tr>
                                @endif
                            </tbody>
                        </table>
                        
                        <!-- No Results Message -->
                        <div id="noResults" class="hidden text-center py-8">
                            <div class="flex flex-col items-center">
                                <svg class="w-12 h-12 text-gray-400 mb-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M21 21l-6-6m2-5a7 7 0 11-14 0 7 7 0 0114 0z"></path>
                                </svg>
                                <p class="text-lg font-medium text-gray-500">Tidak ada hasil yang ditemukan</p>
                                <p class="text-sm text-gray-400 mt-1">Coba ubah kriteria pencarian atau filter</p>                        </div>
                    </div>
                      <!-- Pagination -->
                    @if($programKerjas->hasPages())
                        <div class="bg-white px-4 py-3 border-t border-gray-200 sm:px-6">
                            {{ $programKerjas->links() }}
                        </div>
                    @endif
                </div>
            </div>
        </div>
    </div>

    @push('scripts')
    <script src="https://cdn.jsdelivr.net/npm/sweetalert2@11"></script>
    <script src="https://cdn.jsdelivr.net/npm/notyf@3/notyf.min.js"></script>
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/notyf@3/notyf.min.css">
    
    <script>
        const notyf = new Notyf({
            duration: 4000,
            position: {
                x: 'right',
                y: 'top',
            }
        });

        // Search and Filter functionality
        const searchInput = document.getElementById('searchInput');
        const filterTahun = document.getElementById('filterTahun');
        const filterOpd = document.getElementById('filterOpd');
        const tableBody = document.getElementById('programKerjaTableBody');
        const noResults = document.getElementById('noResults');
        
        function filterTable() {
            const searchTerm = searchInput.value.toLowerCase();
            const selectedTahun = filterTahun.value;
            const selectedOpd = filterOpd.value;
            const rows = tableBody.querySelectorAll('tr');
            let visibleRows = 0;
            
            rows.forEach((row, index) => {
                if (row.id === 'emptyRow') return;
                
                const tahun = row.dataset.tahun;
                const opdId = row.dataset.opd;
                const description = row.dataset.description;
                const opdName = row.dataset.opdName;
                
                const matchesSearch = description.includes(searchTerm) || opdName.includes(searchTerm);
                const matchesTahun = !selectedTahun || tahun === selectedTahun;
                const matchesOpd = !selectedOpd || opdId === selectedOpd;
                
                if (matchesSearch && matchesTahun && matchesOpd) {
                    row.style.display = '';
                    visibleRows++;
                    // Update row number
                    row.querySelector('td:first-child').textContent = visibleRows;
                } else {
                    row.style.display = 'none';
                }
            });
            
            // Show/hide no results message
            if (visibleRows === 0 && rows.length > 1) { // > 1 because of emptyRow
                noResults.classList.remove('hidden');
                document.getElementById('programKerjaTable').classList.add('hidden');
            } else {
                noResults.classList.add('hidden');
                document.getElementById('programKerjaTable').classList.remove('hidden');
            }
        }
        
        // Add event listeners
        searchInput.addEventListener('input', filterTable);
        filterTahun.addEventListener('change', filterTable);
        filterOpd.addEventListener('change', filterTable);
        
        function deleteProgramKerja(id) {
            Swal.fire({
                title: 'Apakah Anda yakin?',
                text: "Program kerja yang dihapus tidak dapat dikembalikan!",
                icon: 'warning',
                showCancelButton: true,
                confirmButtonColor: '#ef4444',
                cancelButtonColor: '#6b7280',
                confirmButtonText: 'Ya, hapus!',
                cancelButtonText: 'Batal'
            }).then((result) => {
                if (result.isConfirmed) {
                    // Create form and submit
                    const form = document.createElement('form');
                    form.method = 'POST';
                    form.action = `/manage/program-kerja/${id}`;
                    form.style.display = 'none';
                    
                    // Add CSRF token
                    const csrfInput = document.createElement('input');
                    csrfInput.type = 'hidden';
                    csrfInput.name = '_token';
                    csrfInput.value = '{{ csrf_token() }}';
                    form.appendChild(csrfInput);
                    
                    // Add method override
                    const methodInput = document.createElement('input');
                    methodInput.type = 'hidden';
                    methodInput.name = '_method';
                    methodInput.value = 'DELETE';
                    form.appendChild(methodInput);
                    
                    document.body.appendChild(form);
                    form.submit();
                }
            });
        }

        // Show success/error messages
        @if(session('success'))
            notyf.success("{{ session('success') }}");
        @endif

        @if(session('error'))
            notyf.error("{{ session('error') }}");
        @endif
    </script>
    @endpush
</x-app-layout>