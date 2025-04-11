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

                    <form id="editForm" class="space-y-4">
                        @csrf
                        <input type="hidden" name="_method" value="PUT">
                        <meta name="csrf-token" content="{{ csrf_token() }}">
                        <meta name="api-token" content="{{ auth()->user()->createToken('api_token')->plainTextToken }}">
                        
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
                            <p class="mt-1 text-sm text-red-600 hidden" id="opd_id_error"></p>
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
                            <p class="mt-1 text-sm text-red-600 hidden" id="klaster_id_error"></p>
                        </div>

                        <div class="mb-4">
                            <label for="indikator_id" class="block text-sm font-medium text-gray-700">Indikator</label>
                            <select name="indikator_id" id="indikator_id" class="mt-1 block w-full rounded-md border-gray-300 shadow-sm focus:border-indigo-500 focus:ring-indigo-500" required>
                                <option value="">Pilih Indikator</option>
                                @foreach($indikators as $indikator)
                                    <option value="{{ $indikator->id }}" 
                                            data-klaster="{{ $indikator->klaster_id }}"
                                            {{ old('indikator_id', $dataDukung->indikator_id) == $indikator->id ? 'selected' : '' }}>
                                        {{ $indikator->name }}
                                    </option>
                                @endforeach
                            </select>
                            <p class="mt-1 text-sm text-red-600 hidden" id="indikator_id_error"></p>
                        </div>

                        <div class="mb-4">
                            <label class="block text-sm font-medium text-gray-700">File yang Sudah Ada</label>
                            <div class="mt-2 space-y-2">
                                @foreach($dataDukung->files as $file)
                                    <div id="file-row-{{ $file->id }}" class="flex items-center justify-between p-2 bg-gray-50 rounded">
                                        <a href="{{ Storage::url($file->file) }}" target="_blank" class="text-indigo-600 hover:text-indigo-900">
                                            {{ $file->original_name }}
                                        </a>
                                        <button type="button" onclick="deleteFile({{ $file->id }})" class="text-red-600 hover:text-red-900">
                                            <i class="fas fa-trash"></i>
                                        </button>
                                    </div>
                                @endforeach
                            </div>
                        </div>

                        <div class="mb-4">
                            <div class="flex justify-between items-center mb-2">
                                <label class="block text-sm font-medium text-gray-700">Tambah File Baru</label>
                                <button type="button" onclick="addFileInput()" class="inline-flex items-center px-3 py-1 border border-transparent rounded-md text-sm font-medium text-white bg-indigo-600 hover:bg-indigo-700 focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-indigo-500">
                                    <i class="fas fa-plus mr-2"></i>
                                    Tambah File
                                </button>
                            </div>
                            
                            <div id="fileInputsContainer" class="space-y-4">
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
                                                        PDF, DOC, DOCX, XLS, XLSX, JPG, JPEG, PNG hingga 25MB
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
                                            <i class="fas fa-times"></i>
                                        </button>
                                    </div>
                                    <p class="mt-1 text-xs text-red-600 file-error hidden"></p>
                                </div>
                            </div>
                        </div>

                        <div class="mb-4">
                            <label for="description" class="block text-sm font-medium text-gray-700">Keterangan</label>
                            <textarea name="description" id="description" rows="3" class="mt-1 block w-full rounded-md border-gray-300 shadow-sm focus:border-indigo-500 focus:ring-indigo-500">{{ old('description', $dataDukung->description) }}</textarea>
                            <p class="mt-1 text-sm text-red-600 hidden" id="description_error"></p>
                        </div>

                        <div class="flex items-center justify-end">
                            <a href="{{ route('user.data-dukung.index') }}" class="mr-4 text-sm text-gray-600 hover:text-gray-900">Batal</a>
                            <button type="submit" id="submitBtn" class="inline-flex items-center px-4 py-2 bg-indigo-600 border border-transparent rounded-md font-semibold text-xs text-white uppercase tracking-widest hover:bg-indigo-700 focus:bg-indigo-700 active:bg-indigo-900 focus:outline-none focus:ring-2 focus:ring-indigo-500 focus:ring-offset-2 transition ease-in-out duration-150">
                                Simpan
                            </button>
                        </div>

                        <div id="uploadProgress" class="mt-4 hidden">
                            <div class="mb-2 flex justify-between text-sm text-gray-600">
                                <div>
                                    <span id="uploadStatus">Mempersiapkan...</span>
                                    <span id="uploadSpeed"></span>
                                </div>
                                <span id="uploadedSize">0 KB / 0 KB</span>
                            </div>
                            <div class="w-full bg-gray-200 rounded-full h-2.5">
                                <div id="progressBar" class="bg-indigo-600 h-2.5 rounded-full transition-all duration-150" style="width: 0%"></div>
                            </div>
                            <div class="mt-1 text-right text-sm text-gray-600">
                                <span id="progressText">0%</span>
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
        document.addEventListener('DOMContentLoaded', function() {
            const klasterSelect = document.getElementById('klaster_id');
            const indikatorSelect = document.getElementById('indikator_id');
            const form = document.getElementById('editForm');
            const submitBtn = document.getElementById('submitBtn');
            const dataDukungId = {{ $dataDukung->id }};
            const apiToken = document.querySelector('meta[name="api-token"]')?.getAttribute('content');

            function filterIndikator() {
                const selectedKlasterId = klasterSelect.value;
                const indikatorOptions = indikatorSelect.querySelectorAll('option');

                indikatorOptions.forEach(option => {
                    if (option.value === '') return;
                    
                    const klasterId = option.getAttribute('data-klaster');
                    if (klasterId === selectedKlasterId) {
                        option.style.display = '';
                    } else {
                        option.style.display = 'none';
                        if (option.selected) {
                            indikatorSelect.value = '';
                        }
                    }
                });
            }

            form.addEventListener('submit', async function(e) {
                e.preventDefault();
                
                document.querySelectorAll('.text-red-600').forEach(el => el.classList.add('hidden'));
                
                const formData = new FormData(form);
                formData.append('_method', 'PUT');

                const files = Array.from(formData.getAll('files[]')).filter(file => file.size > 0);

                try {
                    const result = await Swal.fire({
                        title: 'Konfirmasi',
                        text: 'Apakah Anda yakin ingin menyimpan perubahan?',
                        icon: 'question',
                        showCancelButton: true,
                        confirmButtonColor: '#3085d6',
                        cancelButtonColor: '#d33',
                        confirmButtonText: 'Ya, Simpan!',
                        cancelButtonText: 'Batal'
                    });

                    if (!result.isConfirmed) {
                        return false;
                    }

                    submitBtn.disabled = true;
                    submitBtn.textContent = 'Mengupload...';

                    if (files.length > 0) {
                        const progressSection = document.getElementById('uploadProgress');
                        progressSection.classList.remove('hidden');
                        document.getElementById('progressBar').style.width = '0%';
                        document.getElementById('progressText').textContent = '0%';
                        document.getElementById('uploadStatus').textContent = 'Memulai upload...';
                        document.getElementById('uploadedSize').textContent = '0 KB / 0 KB';
                        document.getElementById('uploadSpeed').textContent = '';

                        const totalSize = files.reduce((acc, file) => acc + file.size, 0);
                        let uploadedSize = 0;
                        let startTime = Date.now();

                        const response = await axios.post(`/api/data-dukung/${dataDukungId}`, formData, {
                            headers: {
                                'Content-Type': 'multipart/form-data',
                                'Authorization': `Bearer ${apiToken}`,
                                'X-CSRF-TOKEN': document.querySelector('meta[name="csrf-token"]').getAttribute('content')
                            },
                            onUploadProgress: (progressEvent) => {
                                const progress = (progressEvent.loaded / progressEvent.total) * 100;
                                const progressBar = document.getElementById('progressBar');
                                const progressText = document.getElementById('progressText');
                                const uploadedSizeEl = document.getElementById('uploadedSize');
                                const uploadStatus = document.getElementById('uploadStatus');
                                const uploadSpeed = document.getElementById('uploadSpeed');

                                progressBar.style.width = progress + '%';
                                progressText.textContent = Math.round(progress) + '%';

                                uploadedSize = progressEvent.loaded;
                                uploadedSizeEl.textContent = `${formatFileSize(uploadedSize)} / ${formatFileSize(totalSize)}`;

                                const currentTime = Date.now();
                                const elapsedTime = (currentTime - startTime) / 1000;
                                const speed = uploadedSize / elapsedTime;
                                uploadSpeed.textContent = `(${formatFileSize(speed)}/s)`;

                                if (progress < 100) {
                                    uploadStatus.textContent = 'Mengupload...';
                                } else {
                                    uploadStatus.textContent = 'Selesai';
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

                            window.location.href = '{{ route("user.data-dukung.index") }}';
                        }
                    } else {
                        const response = await fetch(`/api/data-dukung/${dataDukungId}`, {
                            method: 'POST',
                            headers: {
                                'Accept': 'application/json',
                                'Authorization': `Bearer ${apiToken}`,
                                'X-CSRF-TOKEN': document.querySelector('meta[name="csrf-token"]').getAttribute('content')
                            },
                            body: formData
                        });

                        if (!response.ok) {
                            const result = await response.json();
                            if (response.status === 422 && result.errors) {
                                Object.keys(result.errors).forEach(key => {
                                    const errorElement = document.getElementById(`${key}_error`);
                                    if (errorElement) {
                                        errorElement.textContent = result.errors[key][0];
                                        errorElement.classList.remove('hidden');
                                    }
                                });
                                throw new Error('Validasi gagal');
                            }
                            throw new Error(result.message || 'Terjadi kesalahan saat menyimpan data');
                        }

                        await Swal.fire({
                            icon: 'success',
                            title: 'Berhasil!',
                            text: 'Data dukung berhasil diperbarui',
                            timer: 1500,
                            showConfirmButton: false
                        });

                        window.location.href = '{{ route("user.data-dukung.index") }}';
                    }

                } catch (error) {
                    console.error('Error:', error);
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

                    Swal.fire({
                        icon: 'error',
                        title: 'Error!',
                        html: errorMessage
                    });
                } finally {
                    submitBtn.disabled = false;
                    submitBtn.textContent = 'Simpan';
                    document.getElementById('uploadProgress').classList.add('hidden');
                }
            });

            window.deleteFile = async function(fileId) {
                try {
                    const result = await Swal.fire({
                        title: 'Apakah Anda yakin?',
                        text: "File ini akan dihapus permanen!",
                        icon: 'warning',
                        showCancelButton: true,
                        confirmButtonColor: '#3085d6',
                        cancelButtonColor: '#d33',
                        confirmButtonText: 'Ya, hapus!',
                        cancelButtonText: 'Batal'
                    });

                    if (result.isConfirmed) {
                        const response = await fetch(`/api/data-dukung/file/${fileId}`, {
                            method: 'DELETE',
                            headers: {
                                'Accept': 'application/json',
                                'Content-Type': 'application/json',
                                'Authorization': `Bearer ${apiToken}`,
                                'X-CSRF-TOKEN': '{{ csrf_token() }}'
                            }
                        });

                        if (!response.ok) {
                            throw new Error('Gagal menghapus file');
                        }

                        document.getElementById(`file-row-${fileId}`).remove();

                        Swal.fire({
                            icon: 'success',
                            title: 'Berhasil!',
                            text: 'File berhasil dihapus',
                            timer: 1500
                        });
                    }
                } catch (error) {
                    console.error('Error:', error);
                    Swal.fire({
                        icon: 'error',
                        title: 'Oops...',
                        text: error.message
                    });
                }
            };

            klasterSelect.addEventListener('change', filterIndikator);
            filterIndikator();
        });

        function addFileInput() {
            const container = document.getElementById('fileInputsContainer');
            const template = container.querySelector('.file-input-group').cloneNode(true);

            const fileInput = template.querySelector('.file-input');
            fileInput.value = '';
            const preview = template.querySelector('.file-preview');
            preview.classList.add('hidden');
            const error = template.querySelector('.file-error');
            error.classList.add('hidden');
            error.textContent = '';
            
            container.appendChild(template);
        }

        function removeFileInput(button) {
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
        }

        function handleFileSelect(input) {
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

            if (file.size > 10 * 1024 * 1024) {
                error.textContent = 'Ukuran file maksimal 25MB';
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
        }

        function formatFileSize(bytes) {
            if (bytes === 0) return '0 Bytes';
            const k = 1024;
            const sizes = ['Bytes', 'KB', 'MB', 'GB'];
            const i = Math.floor(Math.log(bytes) / Math.log(k));
            return parseFloat((bytes / Math.pow(k, i)).toFixed(2)) + ' ' + sizes[i];
        }

        function setupDragAndDrop() {
            document.querySelectorAll('.file-input-group').forEach(group => {
                const dropZone = group.querySelector('.border-dashed');
                const input = group.querySelector('.file-input');

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
            });
        }

        document.addEventListener('DOMContentLoaded', setupDragAndDrop);
        const originalAddFileInput = addFileInput;
        addFileInput = function() {
            originalAddFileInput();
            setupDragAndDrop();
        };
    </script>
    @endpush

    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/sweetalert2@11/dist/sweetalert2.min.css">
    <script src="https://cdn.jsdelivr.net/npm/sweetalert2@11"></script>
    @push('styles')
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/5.15.4/css/all.min.css">
    @endpush
</x-app-layout> 