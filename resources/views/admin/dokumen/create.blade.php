<x-app-layout>
    <div class="pl-4 py-12">
        <div class="max-w-7xl mx-auto sm:px-6 lg:px-8">
            <div class="bg-white overflow-hidden shadow-sm sm:rounded-lg">
                <div class="p-6">
                    <div class="flex justify-between items-center mb-6">
                        <h2 class="text-2xl font-semibold text-gray-800">Upload Dokumen Baru</h2>
                        <a href="{{ route('admin.dokumen.index') }}" class="bg-gray-300 hover:bg-gray-400 text-gray-800 font-bold py-2 px-4 rounded inline-flex items-center">
                            <svg class="w-4 h-4 mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M10 19l-7-7m0 0l7-7m-7 7h18"></path>
                            </svg>
                            <span>Kembali</span>
                        </a>
                    </div>

                    <!-- Alert Messages -->
                    <div id="alertSuccess" class="hidden mb-4 p-4 bg-green-100 border border-green-400 text-green-700 rounded">
                        <span id="alertSuccessMessage"></span>
                    </div>
                    <div id="alertError" class="hidden mb-4 p-4 bg-red-100 border border-red-400 text-red-700 rounded">
                        <span id="alertErrorMessage"></span>
                    </div>

                    <!-- Loading Indicator -->
                    <div id="loadingIndicator" class="hidden flex justify-center items-center py-4">
                        <svg class="animate-spin h-8 w-8 text-indigo-600" xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24">
                            <circle class="opacity-25" cx="12" cy="12" r="10" stroke="currentColor" stroke-width="4"></circle>
                            <path class="opacity-75" fill="currentColor" d="M4 12a8 8 0 018-8V0C5.373 0 0 5.373 0 12h4zm2 5.291A7.962 7.962 0 014 12H0c0 3.042 1.135 5.824 3 7.938l3-2.647z"></path>
                        </svg>
                    </div>

                    <!-- Upload Form -->
                    <form id="uploadForm" class="space-y-6">
                        @csrf
                        <div>
                            <label class="block text-sm font-medium text-gray-700">Nama Dokumen</label>
                            <input type="text" name="name" class="mt-1 block w-full rounded-md border-gray-300 shadow-sm focus:border-indigo-500 focus:ring-indigo-500" required>
                            <p class="text-red-500 text-xs mt-1" id="nameError"></p>
                        </div>

                        <div>
                            <label class="block text-sm font-medium text-gray-700">File Dokumen</label>
                            <input type="file" name="file" accept=".pdf,.doc,.docx,.xls,.xlsx" class="mt-1 block w-full" required>
                            <p class="mt-1 text-sm text-gray-500">Format yang didukung: PDF, DOC, DOCX, XLS, XLSX</p>
                            <p class="text-red-500 text-xs mt-1" id="fileError"></p>
                        </div>

                        <div>
                            <button type="submit" class="inline-flex justify-center py-2 px-4 border border-transparent shadow-sm text-sm font-medium rounded-md text-white bg-indigo-600 hover:bg-indigo-700 focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-indigo-500">
                                Upload Dokumen
                            </button>
                        </div>
                    </form>
                </div>
            </div>
        </div>
    </div>

    @push('scripts')
    <script src="https://cdn.jsdelivr.net/npm/axios/dist/axios.min.js"></script>
    <script>
        document.getElementById('uploadForm').addEventListener('submit', function(e) {
            e.preventDefault();
            
            // Reset error messages
            document.querySelectorAll('.text-red-500').forEach(el => el.textContent = '');
            
            // Show loading
            document.getElementById('loadingIndicator').classList.remove('hidden');
            
            const formData = new FormData(this);
            
            axios.post('/api/media', formData, {
                headers: {
                    'Content-Type': 'multipart/form-data',
                    'X-CSRF-TOKEN': document.querySelector('meta[name="csrf-token"]').content,
                    'Authorization': 'Bearer ' + document.querySelector('meta[name="api-token"]').content
                }
            })
            .then(response => {
                const data = response.data;
                document.getElementById('loadingIndicator').classList.add('hidden');
                
                if (data.success) {
                    showSuccess('Dokumen berhasil diupload');
                    setTimeout(() => {
                        window.location.href = '{{ route("admin.dokumen.index") }}';
                    }, 1000);
                } else {
                    showError('Gagal mengupload dokumen');
                }
            })
            .catch(({ response }) => {
                document.getElementById('loadingIndicator').classList.add('hidden');
                
                if (response && response.data && response.data.errors) {
                    const errors = response.data.errors;
                    if (errors.name) {
                        document.getElementById('nameError').textContent = errors.name[0];
                    }
                    if (errors.file) {
                        document.getElementById('fileError').textContent = errors.file[0];
                    }
                } else {
                    showError('Terjadi kesalahan saat mengupload dokumen');
                }
            });
        });

        function showSuccess(message) {
            const alert = document.getElementById('alertSuccess');
            const alertMessage = document.getElementById('alertSuccessMessage');
            alertMessage.textContent = message;
            alert.classList.remove('hidden');
            setTimeout(() => alert.classList.add('hidden'), 3000);
        }

        function showError(message) {
            const alert = document.getElementById('alertError');
            const alertMessage = document.getElementById('alertErrorMessage');
            alertMessage.textContent = message;
            alert.classList.remove('hidden');
            setTimeout(() => alert.classList.add('hidden'), 3000);
        }
    </script>
    @endpush
</x-app-layout> 