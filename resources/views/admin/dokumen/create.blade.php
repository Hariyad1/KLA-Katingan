<x-app-layout>
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/notyf@3/notyf.min.css">
    <script src="https://cdn.jsdelivr.net/npm/notyf@3/notyf.min.js"></script>
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

                    <form id="uploadForm" class="space-y-6">
                        @csrf
                        <div>
                            <label class="block text-sm font-medium text-gray-700">Nama Dokumen</label>
                            <input type="text" name="name" class="mt-1 block w-full rounded-md border-gray-300 shadow-sm focus:border-indigo-500 focus:ring-indigo-500" required>
                            <p class="text-red-500 text-xs mt-1" id="nameError"></p>
                        </div>

                        <div class="mt-1 flex justify-center px-6 pt-5 pb-6 border-2 border-gray-300 border-dashed rounded-md hover:border-indigo-500 transition-colors duration-200">
                            <div class="space-y-1 text-center">
                                <svg class="mx-auto h-12 w-12 text-gray-400" stroke="currentColor" fill="none" viewBox="0 0 48 48">
                                    <path d="M28 8H12a4 4 0 00-4 4v20m32-12v8m0 0v8a4 4 0 01-4 4H12a4 4 0 01-4-4v-4m32-4l-3.172-3.172a4 4 0 00-5.656 0L28 28M8 32l9.172-9.172a4 4 0 015.656 0L28 28m0 0l4 4m4-24h8m-4-4v8m-12 4h.02" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"/>
                                </svg>
                                <div class="flex text-sm text-gray-600">
                                    <label class="relative cursor-pointer bg-white rounded-md font-medium text-indigo-600 hover:text-indigo-500 focus-within:outline-none focus-within:ring-2 focus-within:ring-offset-2 focus-within:ring-indigo-500">
                                        <span>Upload file</span>
                                        <input type="file" name="file" class="sr-only" accept=".pdf,.doc,.docx,.xls,.xlsx" required onchange="validateFileType(this)">
                                    </label>
                                    <p class="pl-1">atau drag and drop</p>
                                </div>
                                <p class="text-xs text-gray-500">Format yang didukung: PDF, DOC, DOCX, XLS, XLSX</p>
                                <div id="filePreview" class="hidden mt-4 p-2 bg-gray-50 rounded-md">
                                    <div class="flex items-center justify-between">
                                        <div class="flex items-center space-x-2">
                                            <i id="fileIcon" class="fas fa-file text-gray-400 text-lg"></i>
                                            <span id="fileName" class="text-sm text-gray-600"></span>
                                            <span id="fileSize" class="text-xs text-gray-500"></span>
                                        </div>
                                        <button type="button" onclick="removeFile()" class="text-red-600 hover:text-red-900">
                                            <i class="fas fa-times"></i>
                                        </button>
                                    </div>
                                </div>
                            </div>
                        </div>
                        <p class="text-red-500 text-xs mt-1" id="fileError"></p>

                        <div>
                            <button type="submit" class="inline-flex justify-center py-2 px-4 border border-transparent shadow-sm text-sm font-medium rounded-md text-white bg-indigo-600 hover:bg-indigo-700 focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-indigo-500">
                                Upload Dokumen
                            </button>
                        </div>
                    </form>

                    <div id="loadingIndicator" class="hidden mt-8">
                        <div class="bg-white p-6 rounded-lg shadow-sm border border-gray-200">
                            <div class="flex flex-col space-y-4">
                                <div class="flex items-center justify-between text-sm font-medium text-gray-700">
                                    <span id="uploadStatus">Mempersiapkan upload...</span>
                                    <span id="uploadSpeed" class="text-indigo-600"></span>
                                </div>
                                
                                <div class="relative pt-1">
                                    <div class="overflow-hidden h-2 text-xs flex rounded bg-indigo-100">
                                        <div id="progressBar" class="shadow-none flex flex-col text-center whitespace-nowrap text-white justify-center bg-indigo-600 transition-all duration-300" style="width: 0%"></div>
                                    </div>
                                </div>
                                
                                <div class="flex justify-between items-center text-xs text-gray-500">
                                    <span id="progressText">0%</span>
                                    <span id="uploadedSize">0 KB / 0 KB</span>
                                </div>

                                <div class="flex items-center justify-center space-x-2 text-indigo-600 animate-pulse">
                                    <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M7 16a4 4 0 01-.88-7.903A5 5 0 1115.9 6L16 6a5 5 0 011 9.9M15 13l-3-3m0 0l-3 3m3-3v12"></path>
                                    </svg>
                                    <span class="text-sm">Sedang mengupload...</span>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>

    @push('styles')
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/notyf@3/notyf.min.css">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/5.15.4/css/all.min.css">
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

        const dropZone = document.querySelector('.border-dashed');
        const fileInput = document.querySelector('input[type="file"]');

        ['dragenter', 'dragover', 'dragleave', 'drop'].forEach(eventName => {
            dropZone.addEventListener(eventName, preventDefaults, false);
        });

        function preventDefaults(e) {
            e.preventDefault();
            e.stopPropagation();
        }

        ['dragenter', 'dragover'].forEach(eventName => {
            dropZone.addEventListener(eventName, highlight, false);
        });

        ['dragleave', 'drop'].forEach(eventName => {
            dropZone.addEventListener(eventName, unhighlight, false);
        });

        function highlight(e) {
            dropZone.classList.add('border-indigo-500', 'bg-indigo-50');
        }

        function unhighlight(e) {
            dropZone.classList.remove('border-indigo-500', 'bg-indigo-50');
        }

        dropZone.addEventListener('drop', handleDrop, false);

        function handleDrop(e) {
            const dt = e.dataTransfer;
            fileInput.files = dt.files;
            validateFileType(fileInput);
        }

        function formatFileSize(bytes) {
            if (bytes === 0) return '0 Bytes';
            const k = 1024;
            const sizes = ['Bytes', 'KB', 'MB', 'GB'];
            const i = Math.floor(Math.log(bytes) / Math.log(k));
            return parseFloat((bytes / Math.pow(k, i)).toFixed(2)) + ' ' + sizes[i];
        }

        function removeFile() {
            fileInput.value = '';
            document.getElementById('filePreview').classList.add('hidden');
            document.getElementById('fileError').textContent = '';
        }

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
                    document.getElementById('filePreview').classList.add('hidden');
                    return false;
                } else {
                    document.getElementById('fileError').textContent = '';
                    
                    const preview = document.getElementById('filePreview');
                    const fileName = document.getElementById('fileName');
                    const fileSize = document.getElementById('fileSize');
                    const fileIcon = document.getElementById('fileIcon');
                    
                    preview.classList.remove('hidden');
                    fileName.textContent = file.name;
                    fileSize.textContent = `(${formatFileSize(file.size)})`;
                    
                    if (file.type.includes('pdf')) {
                        fileIcon.className = 'fas fa-file-pdf text-red-500 text-lg';
                    } else if (file.type.includes('word')) {
                        fileIcon.className = 'fas fa-file-word text-blue-500 text-lg';
                    } else if (file.type.includes('excel')) {
                        fileIcon.className = 'fas fa-file-excel text-green-500 text-lg';
                    }
                }
            }
            return true;
        }

        let uploadStartTime;
        let uploadedBytes = 0;
        
        document.getElementById('uploadForm').addEventListener('submit', function(e) {
            e.preventDefault();
            
            const fileInput = this.querySelector('input[type="file"]');
            if (!validateFileType(fileInput)) {
                return;
            }
            
            document.querySelectorAll('.text-red-500').forEach(el => el.textContent = '');
            
            document.getElementById('loadingIndicator').classList.remove('hidden');
            document.getElementById('progressBar').style.width = '0%';
            document.getElementById('progressText').textContent = '0%';
            document.getElementById('uploadStatus').textContent = 'Memulai upload...';
            
            const formData = new FormData(this);
            uploadStartTime = Date.now();
            uploadedBytes = 0;
            
            axios.post('/api/media', formData, {
                headers: {
                    'Content-Type': 'multipart/form-data',
                    'X-CSRF-TOKEN': document.querySelector('meta[name="csrf-token"]').content,
                    'Authorization': 'Bearer ' + document.querySelector('meta[name="api-token"]').content
                },
                onUploadProgress: function(progressEvent) {
                    const percentCompleted = Math.round((progressEvent.loaded * 100) / progressEvent.total);
                    document.getElementById('progressBar').style.width = percentCompleted + '%';
                    document.getElementById('progressText').textContent = percentCompleted + '%';
                    
                    const currentTime = Date.now();
                    const elapsedTime = (currentTime - uploadStartTime) / 1000;
                    const bytesPerSecond = progressEvent.loaded / elapsedTime;
                    const speed = formatFileSize(bytesPerSecond) + '/s';
                    document.getElementById('uploadSpeed').textContent = speed;
                    
                    document.getElementById('uploadedSize').textContent = 
                        `${formatFileSize(progressEvent.loaded)} / ${formatFileSize(progressEvent.total)}`;
                    
                    if (percentCompleted < 100) {
                        document.getElementById('uploadStatus').textContent = 'Mengupload dokumen...';
                    } else {
                        document.getElementById('uploadStatus').textContent = 'Memproses...';
                    }
                }
            })
            .then(response => {
                const data = response.data;
                document.getElementById('loadingIndicator').classList.add('hidden');
                
                if (data.success) {
                    notyf.success('Dokumen berhasil diupload');
                    setTimeout(() => {
                        window.location.href = '{{ route("admin.dokumen.index") }}';
                    }, 1500);
                } else {
                    notyf.error('Gagal mengupload dokumen');
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
                    notyf.error('Terjadi kesalahan saat mengupload dokumen');
                }
            });
        });
    </script>
    @endpush
</x-app-layout> 