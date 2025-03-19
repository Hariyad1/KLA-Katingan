<x-app-layout>
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/notyf@3/notyf.min.css">
    <script src="https://cdn.jsdelivr.net/npm/notyf@3/notyf.min.js"></script>
    <div class="pl-4 py-12">
        <div class="max-w-7xl mx-auto sm:px-6 lg:px-8">
            <div class="bg-white overflow-hidden shadow-sm sm:rounded-lg">
                <div class="p-6">
                    <div class="flex justify-between items-center mb-6">
                        <h2 class="text-2xl font-semibold text-gray-800">Edit Dokumen</h2>
                        <a href="{{ route('admin.dokumen.index') }}" class="bg-gray-300 hover:bg-gray-400 text-gray-800 font-bold py-2 px-4 rounded inline-flex items-center">
                            <svg class="w-4 h-4 mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M10 19l-7-7m0 0l7-7m-7 7h18"></path>
                            </svg>
                            <span>Kembali</span>
                        </a>
                    </div>

                    <!-- Loading Indicator -->
                    <div id="loadingIndicator" class="hidden flex justify-center items-center py-4">
                        <svg class="animate-spin h-8 w-8 text-indigo-600" xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24">
                            <circle class="opacity-25" cx="12" cy="12" r="10" stroke="currentColor" stroke-width="4"></circle>
                            <path class="opacity-75" fill="currentColor" d="M4 12a8 8 0 018-8V0C5.373 0 0 5.373 0 12h4zm2 5.291A7.962 7.962 0 014 12H0c0 3.042 1.135 5.824 3 7.938l3-2.647z"></path>
                        </svg>
                    </div>

                    <!-- Edit Form -->
                    <form id="editForm" class="space-y-6">
                        @csrf
                        @method('PUT')
                        <div>
                            <label class="block text-sm font-medium text-gray-700">Nama Dokumen</label>
                            <input type="text" name="name" value="{{ $document->name }}" class="mt-1 block w-full rounded-md border-gray-300 shadow-sm focus:border-indigo-500 focus:ring-indigo-500" required>
                            <p class="text-red-500 text-xs mt-1" id="nameError"></p>
                        </div>

                        <div>
                            <label class="block text-sm font-medium text-gray-700">File Dokumen Saat Ini</label>
                            <p class="mt-1 text-sm text-gray-500">{{ $document->file }}</p>
                        </div>

                        <div>
                            <label class="block text-sm font-medium text-gray-700">Ganti File Dokumen (Opsional)</label>
                            <input type="file" name="file" 
                                   accept=".pdf,.doc,.docx,.xls,.xlsx" 
                                   class="mt-1 block w-full"
                                   onchange="validateFileType(this)">
                            <p class="mt-1 text-sm text-gray-500">Format yang didukung: PDF, DOC, DOCX, XLS, XLSX</p>
                            <p class="text-red-500 text-xs mt-1" id="fileError"></p>
                        </div>

                        <div>
                            <button type="submit" class="inline-flex justify-center py-2 px-4 border border-transparent shadow-sm text-sm font-medium rounded-md text-white bg-indigo-600 hover:bg-indigo-700 focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-indigo-500">
                                Simpan Perubahan
                            </button>
                        </div>
                    </form>
                </div>
            </div>
        </div>
    </div>

    @push('styles')
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/notyf@3/notyf.min.css">
    @endpush

    @push('scripts')
    <script src="https://cdn.jsdelivr.net/npm/axios/dist/axios.min.js"></script>
    <script src="https://cdn.jsdelivr.net/npm/notyf@3/notyf.min.js"></script>
    <script>
        const notyf = new Notyf({
            duration: 3000,
            position: {x: 'right', y: 'top'},
            types: [
                {
                    type: 'success',
                    background: '#10B981',
                    icon: false
                },
                {
                    type: 'error',
                    background: '#EF4444',
                    icon: false
                }
            ]
        });

        function validateFileType(input) {
            const allowedTypes = ['application/pdf', 
                                'application/msword', 
                                'application/vnd.openxmlformats-officedocument.wordprocessingml.document',
                                'application/vnd.ms-excel',
                                'application/vnd.openxmlformats-officedocument.spreadsheetml.sheet'];
            
            const file = input.files[0];
            if (file) {
                if (!allowedTypes.includes(file.type)) {
                    input.value = '';
                    document.getElementById('fileError').textContent = 'Hanya file dokumen (PDF, DOC, DOCX, XLS, XLSX) yang diperbolehkan';
                    return false;
                } else {
                    document.getElementById('fileError').textContent = '';
                }
            }
            return true;
        }
        
        document.getElementById('editForm').addEventListener('submit', function(e) {
            e.preventDefault();
            
            const fileInput = this.querySelector('input[type="file"]');
            if (fileInput.files.length > 0 && !validateFileType(fileInput)) {
                return;
            }
            
            // Reset error messages
            document.querySelectorAll('.text-red-500').forEach(el => el.textContent = '');
            
            const formData = new FormData(this);
            formData.append('_method', 'PUT');
            
            axios.post('/api/media/{{ $document->id }}', formData, {
                headers: {
                    'Content-Type': 'multipart/form-data',
                    'X-CSRF-TOKEN': document.querySelector('meta[name="csrf-token"]').content,
                    'Authorization': 'Bearer ' + document.querySelector('meta[name="api-token"]').content
                }
            })
            .then(response => {
                const data = response.data;
                if (data.success) {
                    notyf.success('Dokumen berhasil diperbarui');
                    setTimeout(() => {
                        window.location.href = '{{ route("admin.dokumen.index") }}';
                    }, 1500);
                } else {
                    notyf.error(data.message || 'Gagal mengupload/memperbarui dokumen');
                }
            })
            .catch(error => {
                if (error.response?.data?.errors) {
                    const errors = error.response.data.errors;
                    Object.keys(errors).forEach(key => {
                        notyf.error(errors[key][0]);
                    });
                } else {
                    notyf.error('Terjadi kesalahan saat mengupload/memperbarui dokumen');
                }
            });
        });
    </script>
    @endpush
</x-app-layout> 