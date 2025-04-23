<x-app-layout>
    <x-slot name="header">
        <h2 class="font-semibold text-xl text-gray-800 leading-tight">
            {{ __('Edit Data Dukung') }}
        </h2>
    </x-slot>

    <div class="py-12">
        <div class="max-w-7xl mx-auto sm:px-6 lg:px-8">
            <div class="bg-white overflow-hidden shadow-sm sm:rounded-lg">
                <div class="p-6 text-gray-900">
                    @if(session('error'))
                        <div class="mb-4 p-4 bg-red-100 text-red-700 rounded">
                            {{ session('error') }}
                        </div>
                    @endif

                    <form id="editForm" class="space-y-4" onsubmit="return handleSubmit(event)">
                        @csrf
                        <input type="hidden" name="_method" value="PUT">
                        
                        <div class="mb-4">
                            <label for="opd_id" class="block text-sm font-medium text-gray-700">OPD</label>
                            <select name="opd_id" id="opd_id" class="mt-1 block w-full rounded-md border-gray-300 shadow-sm focus:border-indigo-500 focus:ring-indigo-500" required>
                                <option value="">Pilih OPD</option>
                                @foreach($opds as $opd)
                                    <option value="{{ $opd->id }}" {{ old('opd_id', $dataDukung->opd_id) == $opd->id ? 'selected' : '' }}>
                                        {{ $opd->name }}
                                    </option>
                                @endforeach
                            </select>
                            @error('opd_id')
                                <p class="mt-1 text-sm text-red-600">{{ $message }}</p>
                            @enderror
                        </div>

                        <div class="mb-4">
                            <label for="klaster_id" class="block text-sm font-medium text-gray-700">Klaster</label>
                            <select name="klaster_id" id="klaster_id" class="mt-1 block w-full rounded-md border-gray-300 shadow-sm focus:border-indigo-500 focus:ring-indigo-500" required>
                                <option value="">Pilih Klaster</option>
                                @foreach($klasters as $klaster)
                                    <option value="{{ $klaster->id }}" {{ old('klaster_id', $dataDukung->indikator->klaster_id) == $klaster->id ? 'selected' : '' }}>
                                        {{ $klaster->name }}
                                    </option>
                                @endforeach
                            </select>
                            @error('klaster_id')
                                <p class="mt-1 text-sm text-red-600">{{ $message }}</p>
                            @enderror
                        </div>

                        <div class="mb-4">
                            <label for="indikator_id" class="block text-sm font-medium text-gray-700">Indikator</label>
                            <select name="indikator_id" id="indikator_id" class="mt-1 block w-full rounded-md border-gray-300 shadow-sm focus:border-indigo-500 focus:ring-indigo-500" required>
                                <option value="">Pilih Indikator</option>
                            </select>
                            @error('indikator_id')
                                <p class="mt-1 text-sm text-red-600">{{ $message }}</p>
                            @enderror
                        </div>

                        <!-- File Upload Section -->
                        <div class="mb-4">
                            <div class="flex justify-between items-center mb-2">
                                <label class="block text-sm font-medium text-gray-700">Upload File</label>
                                <button type="button" onclick="addFileInput()" class="inline-flex items-center px-3 py-1 border border-transparent rounded-md text-sm font-medium text-white bg-indigo-600 hover:bg-indigo-700 focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-indigo-500">
                                    <svg class="w-5 h-5 mr-1" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 6v6m0 0v6m0-6h6m-6 0H6"></path>
                                    </svg>
                                    Tambah File
                                </button>
                            </div>
                            
                            <div id="fileUploads" class="space-y-4">
                                <div class="file-input-group">
                                    <div class="relative flex items-center gap-4">
                                        <div class="flex-1">
                                            <div class="mt-1 flex justify-center px-6 pt-5 pb-6 border-2 border-gray-300 border-dashed rounded-md hover:border-indigo-500 transition-colors duration-200">
                                                <div class="space-y-1 text-center">
                                                    <svg class="mx-auto h-12 w-12 text-gray-400" stroke="currentColor" fill="none" viewBox="0 0 48 48">
                                                        <path d="M28 8H12a4 4 0 00-4 4v20m32-12v8m0 0v8a4 4 0 01-4 4H12a4 4 0 01-4-4v-4m32-4l-3.172-3.172a4 4 0 00-5.656 0L28 28M8 32l9.172-9.172a4 4 0 015.656 0L28 28m0 0l4 4m4-24h8m-4-4v8m-12 4h.02" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"/>
                                                    </svg>
                                                    <div class="flex text-sm text-gray-600">
                                                        <label class="relative cursor-pointer bg-white rounded-md font-medium text-indigo-600 hover:text-indigo-500 focus-within:outline-none focus-within:ring-2 focus-within:ring-offset-2 focus-within:ring-indigo-500">
                                                            <span>Upload file</span>
                                                            <input type="file" name="files[]" class="sr-only file-input" accept=".pdf,.doc,.docx,.xls,.xlsx,.jpg,.jpeg,.png" onchange="handleFileSelect(this)">
                                                        </label>
                                                        <p class="pl-1">atau drag and drop</p>
                                                    </div>
                                                    <p class="text-xs text-gray-500">
                                                        PDF, DOC, DOCX, XLS, XLSX, JPG, JPEG, PNG.
                                                    </p>
                                                </div>
                                            </div>
                                            <div class="file-preview hidden mt-2 p-2 bg-gray-50 rounded-md">
                                                <div class="flex items-center justify-between">
                                                    <div class="flex items-center space-x-2">
                                                        <i class="file-icon fas fa-file text-gray-400"></i>
                                                        <span class="file-name text-sm text-gray-600"></span>
                                                        <span class="file-size text-xs text-gray-500"></span>
                                                    </div>
                                                </div>
                                            </div>
                                        </div>
                                        <button type="button" onclick="removeFileInput(this)" class="text-red-600 hover:text-red-900">
                                            <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M6 18L18 6M6 6l12 12"></path>
                                            </svg>
                                        </button>
                                    </div>
                                    <p class="mt-1 text-xs text-red-600 file-error hidden"></p>
                                </div>
                            </div>
                        </div>

                        <!-- Existing Files Section -->
                        <div class="mb-4">
                            <label class="block text-sm font-medium text-gray-700 mb-2">File yang Sudah Diupload</label>
                            <div class="space-y-2">
                                @foreach($dataDukung->files as $file)
                                <div id="file-row-{{ $file->id }}" class="flex items-center justify-between p-3 bg-gray-50 rounded-lg hover:bg-gray-100 transition-colors duration-150">
                                    <div class="flex items-center gap-3">
                                        @php
                                            $extension = pathinfo($file->original_name, PATHINFO_EXTENSION);
                                            $iconClass = match(strtolower($extension)) {
                                                'pdf' => 'fa-file-pdf text-red-500',
                                                'doc', 'docx' => 'fa-file-word text-blue-500',
                                                'xls', 'xlsx' => 'fa-file-excel text-green-500',
                                                'jpg', 'jpeg', 'png' => 'fa-file-image text-purple-500',
                                                default => 'fa-file text-gray-500'
                                            };

                                            try {
                                                $fileSize = 0;
                                                $filePath = 'public/' . $file->file;
                                                if (Storage::exists($filePath)) {
                                                    $fileSize = Storage::size($filePath);
                                                } else {
                                                    $altPath = 'public/data-dukung-files/' . basename($file->file);
                                                    if (Storage::exists($altPath)) {
                                                        $fileSize = Storage::size($altPath);
                                                    }
                                                }
                                            } catch (\Exception $e) {
                                                $fileSize = $file->size ?? 0;
                                            }
                                        @endphp
                                        <i class="fas {{ $iconClass }} text-lg"></i>
                                        <div class="flex flex-col">
                                            <span class="text-sm font-medium text-gray-700">{{ $file->original_name }}</span>
                                            <span class="text-xs text-gray-500">{{ $fileSize > 0 ? number_format($fileSize / 1024, 2) . ' KB' : 'Ukuran tidak tersedia' }}</span>
                                        </div>
                                    </div>
                                    <div class="flex items-center gap-2">
                                        <a href="{{ url('storage/' . $file->file) }}" target="_blank" class="text-indigo-600 hover:text-indigo-800">
                                            <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 12a3 3 0 11-6 0 3 3 0 016 0z"/>
                                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M2.458 12C3.732 7.943 7.523 5 12 5c4.478 0 8.268 2.943 9.542 7-1.274 4.057-5.064 7-9.542 7-4.477 0-8.268-2.943-9.542-7z"/>
                                            </svg>
                                        </a>
                                        <button type="button" onclick="deleteFile({{ $file->id }})" class="text-red-600 hover:text-red-800">
                                            <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 7l-.867 12.142A2 2 0 0116.138 21H7.862a2 2 0 01-1.995-1.858L5 7m5 4v6m4-6v6m1-10V4a1 1 0 00-1-1h-4a1 1 0 00-1 1v3M4 7h16"></path>
                                            </svg>
                                        </button>
                                    </div>
                                </div>
                                @endforeach
                            </div>
                        </div>

                        <div class="mb-4">
                            <label for="description" class="block text-sm font-medium text-gray-700">Keterangan</label>
                            <textarea name="description" id="description" rows="3" class="mt-1 block w-full rounded-md border-gray-300 shadow-sm focus:border-indigo-500 focus:ring-indigo-500">{{ old('description', $dataDukung->description) }}</textarea>
                            @error('description')
                                <p class="mt-1 text-sm text-red-600">{{ $message }}</p>
                            @enderror
                        </div>

                        <div class="flex items-center justify-end">
                            <a href="{{ route('admin.data-dukung.index') }}" class="mr-4 text-sm text-gray-600 hover:text-gray-900">Batal</a>
                            <button type="submit" id="submitBtn" class="inline-flex items-center px-4 py-2 bg-indigo-600 border border-transparent rounded-md font-semibold text-xs text-white uppercase tracking-widest hover:bg-indigo-700 focus:bg-indigo-700 active:bg-indigo-900 focus:outline-none focus:ring-2 focus:ring-indigo-500 focus:ring-offset-2 transition ease-in-out duration-150">
                                Simpan
                            </button>
                        </div>

                        <!-- Progress Bar Section -->
                        <div id="uploadProgress" class="hidden mt-6">
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
                                </div>
                            </div>
                        </div>
                    </form>
                </div>
            </div>
        </div>
    </div>

    @push('scripts')
    <script src="https://cdn.jsdelivr.net/npm/axios/dist/axios.min.js"></script>
    <script>
        window.addFileInput = function() {
            const container = document.getElementById('fileUploads');
            const template = container.querySelector('.file-input-group').cloneNode(true);
            
            const fileInput = template.querySelector('.file-input');
            fileInput.value = '';
            const preview = template.querySelector('.file-preview');
            preview.classList.add('hidden');
            const error = template.querySelector('.file-error');
            error.classList.add('hidden');
            error.textContent = '';
            
            container.appendChild(template);
            setupDragAndDrop(template);
        };

        window.removeFileInput = function(button) {
            const group = button.closest('.file-input-group');
            if (document.querySelectorAll('.file-input-group').length > 1) {
                group.remove();
            } else {
                const fileInput = group.querySelector('.file-input');
                fileInput.value = '';
                const preview = group.querySelector('.file-preview');
                preview.classList.add('hidden');
                const error = group.querySelector('.file-error');
                error.classList.add('hidden');
                error.textContent = '';
            }
        };

        window.handleFileSelect = function(input) {
            const file = input.files[0];
            if (!file) return;

            const group = input.closest('.file-input-group');
            const preview = group.querySelector('.file-preview');
            const fileIcon = preview.querySelector('.file-icon');
            const fileName = preview.querySelector('.file-name');
            const fileSize = preview.querySelector('.file-size');
            const error = group.querySelector('.file-error');

            error.classList.add('hidden');
            error.textContent = '';

            const allowedTypes = [
                'application/pdf',
                'application/msword',
                'application/vnd.openxmlformats-officedocument.wordprocessingml.document',
                'application/vnd.ms-excel',
                'application/vnd.openxmlformats-officedocument.spreadsheetml.sheet',
                'image/jpeg',
                'image/png'
            ];

            if (!allowedTypes.includes(file.type)) {
                error.textContent = 'Format file tidak didukung';
                error.classList.remove('hidden');
                input.value = '';
                preview.classList.add('hidden');
                return;
            }

            if (file.size > 50 * 1024 * 1024) {
                error.textContent = 'Ukuran file maksimal 50MB';
                error.classList.remove('hidden');
                input.value = '';
                preview.classList.add('hidden');
                return;
            }

            preview.classList.remove('hidden');
            fileName.textContent = file.name;
            fileSize.textContent = `(${formatFileSize(file.size)})`;

            if (file.type.includes('pdf')) {
                fileIcon.className = 'fas fa-file-pdf text-red-500 text-lg';
            } else if (file.type.includes('word')) {
                fileIcon.className = 'fas fa-file-word text-blue-500 text-lg';
            } else if (file.type.includes('excel')) {
                fileIcon.className = 'fas fa-file-excel text-green-500 text-lg';
            } else if (file.type.includes('image')) {
                fileIcon.className = 'fas fa-file-image text-purple-500 text-lg';
            }
        };

        function formatFileSize(bytes) {
            if (bytes === 0) return '0 Bytes';
            const k = 1024;
            const sizes = ['Bytes', 'KB', 'MB', 'GB'];
            const i = Math.floor(Math.log(bytes) / Math.log(k));
            return parseFloat((bytes / Math.pow(k, i)).toFixed(2)) + ' ' + sizes[i];
        }

        function setupDragAndDrop(container) {
            const dropZone = container.querySelector('.border-dashed');
            const input = container.querySelector('.file-input');

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
                input.files = dt.files;
                handleFileSelect(input);
            }
        }

        document.querySelectorAll('.file-input-group').forEach(setupDragAndDrop);

        document.addEventListener('DOMContentLoaded', function() {
            const klasterSelect = document.getElementById('klaster_id');
            const indikatorSelect = document.getElementById('indikator_id');
            const dataDukungId = {{ $dataDukung->id }};

            window.handleSubmit = async function(e) {
                e.preventDefault();
                
                try {
                    const confirmResult = await Swal.fire({
                        title: 'Konfirmasi',
                        text: 'Apakah Anda yakin ingin menyimpan perubahan?',
                        icon: 'question',
                        showCancelButton: true,
                        confirmButtonColor: '#3085d6',
                        cancelButtonColor: '#d33',
                        confirmButtonText: 'Ya, Simpan!',
                        cancelButtonText: 'Batal'
                    });

                    if (!confirmResult.isConfirmed) {
                        return false;
                    }

                    const submitBtn = document.getElementById('submitBtn');
                    submitBtn.disabled = true;
                    submitBtn.textContent = 'Mengupload...';

                    const formData = new FormData(e.target);
                    formData.append('_method', 'PUT');
                    
                    const files = formData.getAll('files[]').filter(file => file.size > 0);

                    if (files.length > 0) {
                        const progressSection = document.getElementById('uploadProgress');
                        progressSection.classList.remove('hidden');
                        document.getElementById('progressBar').style.width = '0%';
                        document.getElementById('progressText').textContent = '0%';
                        document.getElementById('uploadStatus').textContent = 'Memulai upload...';
                        document.getElementById('uploadedSize').textContent = '0 KB / 0 KB';
                        document.getElementById('uploadSpeed').textContent = '';
                    }
                    
                    const apiToken = document.querySelector('meta[name="api-token"]')?.getAttribute('content');
                    const csrfToken = document.querySelector('meta[name="csrf-token"]')?.getAttribute('content');

                    const response = await axios.post(`/api/data-dukung/${dataDukungId}`, formData, {
                        headers: {
                            'Content-Type': 'multipart/form-data',
                            'Accept': 'application/json',
                            'Authorization': `Bearer ${apiToken}`,
                            'X-CSRF-TOKEN': csrfToken
                        },
                        onUploadProgress: function(progressEvent) {
                            if (progressEvent.total && files.length > 0) {
                                const totalProgress = Math.round((progressEvent.loaded * 100) / progressEvent.total);
                                
                                const totalSize = files.reduce((acc, file) => acc + file.size, 0);
                                const uploadedBytes = progressEvent.loaded;
                                
                                let accumulatedSize = 0;
                                let currentFileIndex = -1;
                                
                                for (let i = 0; i < files.length; i++) {
                                    const fileSize = files[i].size;
                                    const nextAccumulatedSize = accumulatedSize + fileSize;
                                    
                                    if (uploadedBytes > accumulatedSize && uploadedBytes <= nextAccumulatedSize) {
                                        const fileProgress = Math.round(((uploadedBytes - accumulatedSize) * 100) / fileSize);
                                        
                                        document.getElementById('progressBar').style.width = `${fileProgress}%`;
                                        document.getElementById('progressText').textContent = `${fileProgress}%`;
                                        document.getElementById('uploadStatus').textContent = `Mengupload file ${i + 1} dari ${files.length}`;
                                        document.getElementById('uploadedSize').textContent = 
                                            `${formatFileSize(uploadedBytes)} / ${formatFileSize(totalSize)}`;
                                        
                                        const speed = formatFileSize(progressEvent.loaded / ((Date.now() - window.uploadStartTime) / 1000)) + '/s';
                                        document.getElementById('uploadSpeed').textContent = speed;
                                    }
                                    
                                    accumulatedSize = nextAccumulatedSize;
                                }

                                if (totalProgress === 100) {
                                    document.getElementById('uploadStatus').textContent = 'Memproses...';
                                }
                            }
                        }
                    });

                    if (response.data.success) {
                        await Swal.fire({
                            icon: 'success',
                            title: 'Berhasil!',
                            text: 'Data dukung berhasil diperbarui',
                            timer: 1500,
                            showConfirmButton: false
                        });

                        window.location.href = '{{ route("admin.data-dukung.index") }}';
                    }
                } catch (error) {
                    console.error('Upload error:', error);
                    
                    let errorMessage = 'Terjadi kesalahan saat menyimpan data';
                    
                    if (error.response?.data?.errors) {
                        const errors = error.response.data.errors;
                        errorMessage = '<ul class="text-left">';
                        Object.keys(errors).forEach(key => {
                            errorMessage += `<li>- ${errors[key][0]}</li>`;
                        });
                        errorMessage += '</ul>';
                    } else if (error.response?.data?.message) {
                        errorMessage = error.response.data.message;
                    }

                    await Swal.fire({
                        icon: 'error',
                        title: 'Gagal!',
                        html: errorMessage,
                        confirmButtonColor: '#EF4444'
                    });
                } finally {
                    submitBtn.disabled = false;
                    submitBtn.textContent = 'Simpan';
                    if (files.length > 0) {
                        document.getElementById('uploadProgress').classList.add('hidden');
                        Swal.close();
                    }
                }

                return false;
            };

            function filterIndikator() {
                const selectedKlasterId = klasterSelect.value;
                
                if (selectedKlasterId) {
                    fetch(`/api/klaster/${selectedKlasterId}/indikators`)
                        .then(response => response.json())
                        .then(data => {
                            indikatorSelect.innerHTML = '<option value="">Pilih Indikator</option>';
                            
                            data.forEach(indikator => {
                                const option = document.createElement('option');
                                option.value = indikator.id;
                                option.textContent = indikator.name;
                                if (indikator.id == {{ $dataDukung->indikator_id }}) {
                                    option.selected = true;
                                }
                                indikatorSelect.appendChild(option);
                            });
                        });
                }
            }

            klasterSelect.addEventListener('change', filterIndikator);

            window.deleteFile = async function(id) {
                try {
                    const result = await Swal.fire({
                        title: 'Apakah Anda yakin?',
                        text: "File akan dihapus secara permanen!",
                        icon: 'warning',
                        showCancelButton: true,
                        confirmButtonColor: '#3085d6',
                        cancelButtonColor: '#d33',
                        confirmButtonText: 'Ya, hapus!',
                        cancelButtonText: 'Batal'
                    });

                    if (!result.isConfirmed) {
                        return;
                    }

                    const apiToken = document.querySelector('meta[name="api-token"]')?.getAttribute('content');
                    const csrfToken = document.querySelector('meta[name="csrf-token"]')?.getAttribute('content');

                    const formData = new FormData();
                    formData.append('_method', 'DELETE');
                    formData.append('_token', csrfToken);

                    const response = await fetch(`/api/data-dukung/file/${id}`, {
                        method: 'POST',
                        headers: {
                            'Accept': 'application/json',
                            'Authorization': `Bearer ${apiToken}`
                        },
                        body: formData,
                        credentials: 'include'
                    });

                    if (!response.ok) {
                        const errorData = await response.json();
                        throw new Error(errorData.message || 'Gagal menghapus file');
                    }

                    const data = await response.json();
                    
                    const fileRow = document.getElementById(`file-row-${id}`);
                    if (fileRow) {
                        fileRow.remove();
                    }

                    await Swal.fire({
                        icon: 'success',
                        title: 'Berhasil!',
                        text: data.message || 'File berhasil dihapus',
                        timer: 1500
                    });

                } catch (error) {
                    console.error('Delete error:', error);
                    await Swal.fire({
                        icon: 'error',
                        title: 'Error!',
                        text: error.message || 'Gagal menghapus file. Silakan coba lagi.'
                    });
                }
            }

            filterIndikator();

            window.uploadStartTime = Date.now();
        });
    </script>
    @endpush

    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/sweetalert2@11/dist/sweetalert2.min.css">
    <script src="https://cdn.jsdelivr.net/npm/sweetalert2@11"></script>
    @push('styles')
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/5.15.4/css/all.min.css">
    @endpush
</x-app-layout>