<x-app-layout>
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/notyf@3/notyf.min.css">
    <script src="https://cdn.jsdelivr.net/npm/notyf@3/notyf.min.js"></script>
    <div class="pl-4 py-12">
        <div class="max-w-7xl mx-auto sm:px-6 lg:px-8">
            <div class="bg-white overflow-hidden shadow-sm sm:rounded-lg">
                <div class="p-6">
                    <div class="flex justify-between items-center mb-6">
                        <h2 class="text-2xl font-semibold text-gray-800">Edit Media</h2>
                        <a href="{{ route('media.index') }}" class="bg-gray-300 hover:bg-gray-400 text-gray-800 font-bold py-2 px-4 rounded inline-flex items-center">
                            <svg class="w-4 h-4 mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M10 19l-7-7m0 0l7-7m-7 7h18"></path>
                            </svg>
                            <span>Kembali</span>
                        </a>
                    </div>

                    <div id="alertSuccess" class="hidden mb-4 p-4 bg-green-100 border border-green-400 text-green-700 rounded">
                        <span id="alertSuccessMessage"></span>
                    </div>
                    <div id="alertError" class="hidden mb-4 p-4 bg-red-100 border border-red-400 text-red-700 rounded">
                        <span id="alertErrorMessage"></span>
                    </div>

                    <form id="editForm" class="space-y-6" action="{{ route('media.update', $media->id) }}" method="POST" enctype="multipart/form-data">
                        @csrf
                        @method('PUT')
                        <div>
                            <label class="block text-sm font-medium text-gray-700">Nama Media</label>
                            <input type="text" name="name" value="{{ $media->name }}" class="mt-1 block w-full rounded-md border-gray-300 shadow-sm focus:border-indigo-500 focus:ring-indigo-500" required>
                            <p class="text-red-500 text-xs mt-1" id="nameError"></p>
                        </div>

                        <div>
                            <label class="block text-sm font-medium text-gray-700">Gambar Saat Ini</label>
                            <img src="{{ $media->path }}" alt="{{ $media->name }}" class="mt-2 h-32 w-auto">
                        </div>

                        <div>
                            <label class="block text-sm font-medium text-gray-700">Ganti Gambar (Opsional)</label>
                            <input type="file" name="file" 
                                   accept="image/jpeg,image/png,image/gif" 
                                   class="mt-1 block w-full"
                                   onchange="validateImageType(this)">
                            <p class="mt-1 text-sm text-gray-500">Format yang didukung: JPG, JPEG, PNG, GIF</p>
                            <p class="text-red-500 text-xs mt-1" id="fileError"></p>
                        </div>

                        <div>
                            <label class="flex items-center">
                                <input type="checkbox" id="slide_show" name="slide_show" {{ $media->slide_show ? 'checked' : '' }}
                                    class="rounded border-gray-300 text-indigo-600 shadow-sm focus:border-indigo-500 focus:ring-indigo-500">
                                <span class="ml-2 text-sm text-gray-600">Tampilkan di slideshow</span>
                            </label>
                        </div>

                        <div>
                            <button type="submit" class="inline-flex justify-center py-2 px-4 border border-transparent shadow-sm text-sm font-medium rounded-md text-white bg-indigo-600 hover:bg-indigo-700 focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-indigo-500">
                                Simpan Perubahan
                            </button>
                        </div>
                    </form>

                    <div id="loadingIndicator" class="hidden">
                        <div class="mt-4">
                            <div class="flex items-center justify-between mb-1">
                                <div class="text-sm font-medium text-gray-700">Progress Upload</div>
                                <div class="text-sm font-medium text-gray-700" id="uploadProgress">0%</div>
                            </div>
                            <div class="w-full bg-gray-200 rounded-full h-2.5">
                                <div class="bg-blue-600 h-2.5 rounded-full" id="progressBar" style="width: 0%"></div>
                            </div>
                            <div class="mt-2 text-sm text-gray-600">
                                <span id="uploadedSize">0 KB</span> / <span id="totalSize">0 KB</span>
                                <span id="uploadSpeed" class="ml-2">(0 KB/s)</span>
                            </div>
                        </div>
                    </div>
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

        function formatFileSize(bytes) {
            if (bytes === 0) return '0 Bytes';
            const k = 1024;
            const sizes = ['Bytes', 'KB', 'MB', 'GB'];
            const i = Math.floor(Math.log(bytes) / Math.log(k));
            return parseFloat((bytes / Math.pow(k, i)).toFixed(2)) + ' ' + sizes[i];
        }

        document.addEventListener('DOMContentLoaded', function() {
            const slideShowCheckbox = document.getElementById('slide_show');
            const editForm = document.getElementById('editForm');
            
            const ORIGINAL_SLIDE_SHOW_STATE = {{ $media->slide_show ? 'true' : 'false' }};
            slideShowCheckbox.checked = ORIGINAL_SLIDE_SHOW_STATE;
            
            editForm && editForm.addEventListener('submit', function(e) {
                e.preventDefault();
                
                const formData = new FormData(this);
                formData.append('_method', 'PUT');
                
                const slideShowValue = slideShowCheckbox.checked === ORIGINAL_SLIDE_SHOW_STATE 
                    ? (ORIGINAL_SLIDE_SHOW_STATE ? '1' : '0')
                    : (slideShowCheckbox.checked ? '1' : '0');
                formData.append('slide_show', slideShowValue);
                
                const fileInput = this.querySelector('input[type="file"]');
                if (fileInput.files.length > 0) {
                    if (!validateImageType(fileInput)) {
                        return;
                    }
                    document.getElementById('loadingIndicator').classList.remove('hidden');
                    document.getElementById('totalSize').textContent = formatFileSize(fileInput.files[0].size);
                }

                window.uploadStartTime = Date.now();
                
                axios.post('/api/media/{{ $media->id }}', formData, {
                    headers: {
                        'Content-Type': 'multipart/form-data',
                        'X-CSRF-TOKEN': document.querySelector('meta[name="csrf-token"]').content,
                        'Authorization': 'Bearer ' + document.querySelector('meta[name="api-token"]').content
                    },
                    onUploadProgress: function(progressEvent) {
                        const percentCompleted = Math.round((progressEvent.loaded * 100) / progressEvent.total);
                        document.getElementById('progressBar').style.width = percentCompleted + '%';
                        document.getElementById('uploadProgress').textContent = percentCompleted + '%';
                        document.getElementById('uploadedSize').textContent = formatFileSize(progressEvent.loaded);
                        
                        const timeElapsed = (Date.now() - window.uploadStartTime) / 1000;
                        const uploadSpeed = progressEvent.loaded / timeElapsed;
                        document.getElementById('uploadSpeed').textContent = `(${formatFileSize(uploadSpeed)}/s)`;
                    }
                })
                .then(response => {
                    const data = response.data;
                    if (data.success) {
                        notyf.success('Media berhasil diperbarui');
                        setTimeout(() => {
                            window.location.href = '{{ route("media.index") }}';
                        }, 1500);
                    }
                })
                .catch(error => {
                    if (error.response?.data?.errors) {
                        const errors = error.response.data.errors;
                        Object.keys(errors).forEach(key => {
                            notyf.error(errors[key][0]);
                        });
                    } else {
                        notyf.error('Terjadi kesalahan saat memperbarui media');
                    }
                    slideShowCheckbox.checked = ORIGINAL_SLIDE_SHOW_STATE;
                });
            });
        });

        function validateImageType(input) {
            const allowedTypes = ['image/jpeg', 'image/png', 'image/gif'];
            
            const file = input.files[0];
            if (file) {
                if (!allowedTypes.includes(file.type)) {
                    input.value = '';
                    notyf.error('Hanya file gambar (JPG, JPEG, PNG, GIF) yang diperbolehkan');
                    return false;
                }
                document.getElementById('totalSize').textContent = formatFileSize(file.size);
            }
            return true;
        }
    </script>
    @endpush
</x-app-layout> 