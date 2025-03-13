<x-app-layout>
    <div class="pl-4 py-12">
        <div class="max-w-7xl mx-auto sm:px-6 lg:px-8">
            <div class="bg-white overflow-hidden shadow-sm sm:rounded-lg">
                <div class="p-6">
                    <div class="flex justify-between items-center mb-6">
                        <h2 class="text-2xl font-semibold text-gray-800">Upload Media Baru</h2>
                        <a href="{{ route('admin.media.index') }}" class="bg-gray-300 hover:bg-gray-400 text-gray-800 font-bold py-2 px-4 rounded inline-flex items-center">
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

                    <!-- Form Upload -->
                    <form id="uploadForm" class="space-y-6">
                        @csrf
                        <div>
                            <label class="block text-sm font-medium text-gray-700">Nama</label>
                            <input type="text" name="name" class="mt-1 block w-full rounded-md border-gray-300 shadow-sm focus:border-indigo-500 focus:ring-indigo-500" required>
                            <p class="text-red-500 text-xs mt-1" id="nameError"></p>
                        </div>

                        <div>
                            <label class="block text-sm font-medium text-gray-700">File Media</label>
                            <input type="file" name="file" accept="image/*" class="mt-1 block w-full" required>
                            <p class="mt-1 text-sm text-gray-500">Format yang didukung: JPG, JPEG, PNG, GIF</p>
                            <p class="text-red-500 text-xs mt-1" id="fileError"></p>
                        </div>

                        <div>
                            <label class="flex items-center">
                                <input type="checkbox" name="slide_show" value="1" class="rounded border-gray-300 text-indigo-600 shadow-sm focus:border-indigo-500 focus:ring-indigo-500">
                                <span class="ml-2 text-sm text-gray-600">Tampilkan di slideshow</span>
                            </label>
                        </div>

                        <div class="flex justify-end">
                            <a href="{{ route('admin.media.index') }}" class="bg-gray-200 text-gray-700 px-4 py-2 rounded mr-2">Batal</a>
                            <button type="submit" class="bg-indigo-600 text-white px-4 py-2 rounded">Upload</button>
                        </div>
                    </form>

                    <div id="loadingIndicator" class="hidden">
                        <!-- Loading spinner atau teks loading -->
                    </div>
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
            
            // Tampilkan loading
            document.getElementById('loadingIndicator')?.classList.remove('hidden');
            
            const formData = new FormData(this);
            
            axios.post('/api/media', formData, {
                headers: {
                    'Content-Type': 'multipart/form-data',
                    'X-CSRF-TOKEN': document.querySelector('meta[name="csrf-token"]').content,
                    'Accept': 'application/json',
                    'Authorization': 'Bearer ' + document.querySelector('meta[name="api-token"]').content
                }
            })
            .then(response => {
                const data = response.data;
                if (data.success) {
                    showSuccess('Media berhasil diupload');
                    setTimeout(() => {
                        window.location.href = '{{ route("admin.media.index") }}';
                    }, 1000);
                } else {
                    showError(data.message || 'Gagal mengupload media');
                }
            })
            .catch(error => {
                if (error.response?.data?.errors) {
                    const errors = error.response.data.errors;
                    if (errors.name) document.getElementById('nameError').textContent = errors.name[0];
                    if (errors.file) document.getElementById('fileError').textContent = errors.file[0];
                } else {
                    showError('Terjadi kesalahan saat mengupload media');
                }
            })
            .finally(() => {
                document.getElementById('loadingIndicator')?.classList.add('hidden');
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