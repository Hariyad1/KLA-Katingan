<x-app-layout>
    <x-slot name="header">
        <h2 class="font-semibold text-xl text-gray-800 leading-tight">
            {{ __('Tambah Data Dukung') }}
        </h2>
    </x-slot>

    <div class="py-12">
        <div class="max-w-7xl mx-auto sm:px-6 lg:px-8">
            <div class="bg-white overflow-hidden shadow-sm sm:rounded-lg">
                <div class="p-6">
                    <form action="{{ route('admin.data-dukung.store') }}" method="POST" enctype="multipart/form-data" id="createForm">
                        @csrf
                        <div class="grid grid-cols-1 gap-6">
                            <div>
                                <label for="opd_id" class="block text-sm font-medium text-gray-700">Perangkat Daerah</label>
                                <select name="opd_id" id="opd_id" class="mt-1 block w-full rounded-md border-gray-300 shadow-sm focus:border-indigo-500 focus:ring-indigo-500" required>
                                    <option value="">Pilih Perangkat Daerah</option>
                                    @foreach($opds as $opd)
                                        <option value="{{ $opd->id }}" {{ old('opd_id') == $opd->id ? 'selected' : '' }}>{{ $opd->name }}</option>
                                    @endforeach
                                </select>
                                @error('opd_id')
                                    <p class="mt-2 text-sm text-red-600">{{ $message }}</p>
                                @enderror
                            </div>

                            <div>
                                <label for="klaster_id" class="block text-sm font-medium text-gray-700">Klaster</label>
                                <select name="klaster_id" id="klaster_id" class="mt-1 block w-full rounded-md border-gray-300 shadow-sm focus:border-indigo-500 focus:ring-indigo-500" required>
                                    <option value="">Pilih Klaster</option>
                                    @foreach($klasters as $klaster)
                                        <option value="{{ $klaster->id }}" {{ old('klaster_id') == $klaster->id ? 'selected' : '' }}>{{ $klaster->name }}</option>
                                    @endforeach
                                </select>
                                @error('klaster_id')
                                    <p class="mt-2 text-sm text-red-600">{{ $message }}</p>
                                @enderror
                            </div>

                            <div>
                                <label for="indikator_id" class="block text-sm font-medium text-gray-700">Indikator</label>
                                <select name="indikator_id" id="indikator_id" class="mt-1 block w-full rounded-md border-gray-300 shadow-sm focus:border-indigo-500 focus:ring-indigo-500" required>
                                    <option value="">Pilih Indikator</option>
                                </select>
                                <div id="indikator-description" class="mt-2 p-3 bg-gray-50 rounded-md text-sm text-gray-600 hidden">
                                </div>
                                @error('indikator_id')
                                    <p class="mt-2 text-sm text-red-600">{{ $message }}</p>
                                @enderror
                            </div>

                            <div class="mb-4">
                                <label class="block text-sm font-medium text-gray-700">File</label>
                                <div class="space-y-4">
                                    <div class="bg-gray-50 p-4 rounded-lg">
                                        <label class="block text-sm font-medium text-gray-700 mb-2">Upload Banyak File Sekaligus</label>
                                        <input type="file" name="files[]" multiple class="mt-1 block w-full" accept=".pdf,.doc,.docx,.xls,.xlsx,.jpg,.jpeg,.png">
                                        <p class="mt-1 text-xs text-gray-500">Pilih beberapa file sekaligus dengan CTRL + Klik atau drag and drop beberapa file</p>
                                    </div>

                                    <div class="bg-gray-50 p-4 rounded-lg">
                                        <label class="block text-sm font-medium text-gray-700 mb-2">Upload Satu Per Satu</label>
                                        <div id="fileInputs" class="space-y-4">
                                            <div class="file-input-group">
                                                <div class="flex items-center">
                                                    <input type="file" name="files[]" class="mt-1 block w-full" accept=".pdf,.doc,.docx,.xls,.xlsx,.jpg,.jpeg,.png">
                                                    <button type="button" onclick="addFileInput()" class="ml-2 text-indigo-600 hover:text-indigo-900">
                                                        <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 6v6m0 0v6m0-6h6m-6 0H6"/>
                                                        </svg>
                                                    </button>
                                                </div>
                                            </div>
                                        </div>
                                    </div>
                                </div>

                                <p class="mt-4 text-sm text-gray-500">Format file: PDF, DOC, DOCX, XLS, XLSX, JPG, JPEG, PNG. Maksimal 50MB per file.</p>
                                
                              <div id="selectedFiles" class="mt-4 space-y-2"></div>
                                
                                @error('files')
                                    <p class="mt-1 text-sm text-red-600">{{ $message }}</p>
                                @enderror
                                @error('files.*')
                                    <p class="mt-1 text-sm text-red-600">{{ $message }}</p>
                                @enderror
                            </div>

                            <div>
                                <label for="description" class="block text-sm font-medium text-gray-700">Deskripsi (Opsional)</label>
                                <textarea name="description" id="description" rows="3" class="mt-1 block w-full rounded-md border-gray-300 shadow-sm focus:border-indigo-500 focus:ring-indigo-500">{{ old('description') }}</textarea>
                                @error('description')
                                    <p class="mt-2 text-sm text-red-600">{{ $message }}</p>
                                @enderror
                            </div>

                            <div class="flex justify-end">
                                <a href="{{ route('admin.data-dukung.index') }}" class="bg-gray-500 hover:bg-gray-700 text-white font-bold py-2 px-4 rounded mr-2">
                                    Batal
                                </a>
                                <button type="submit" id="submitBtn" class="bg-indigo-600 hover:bg-indigo-700 text-white font-bold py-2 px-4 rounded">
                                    Simpan
                                </button>
                            </div>
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
    <script src="https://cdn.jsdelivr.net/npm/sweetalert2@11"></script>
    <script>
        const notyf = new Notyf({
            duration: 3000,
            position: {x: 'right', y: 'top'},
            types: [
                {
                    type: 'success',
                    background: '#10B981',
                    icon: {
                        className: 'fas fa-check-circle',
                        tagName: 'i'
                    }
                },
                {
                    type: 'error',
                    background: '#EF4444',
                    icon: {
                        className: 'fas fa-times-circle',
                        tagName: 'i'
                    }
                },
                {
                    type: 'warning',
                    background: '#F59E0B',
                    icon: {
                        className: 'fas fa-exclamation-circle',
                        tagName: 'i'
                    }
                }
            ]
        });

        document.getElementById('klaster_id').addEventListener('change', function() {
            const klasterId = this.value;
            const indikatorSelect = document.getElementById('indikator_id');
            const indikatorDesc = document.getElementById('indikator-description');
            
            indikatorSelect.innerHTML = '<option value="">Pilih Indikator</option>';
            
            if (klasterId) {
                axios.get(`/api/klaster/${klasterId}/indikators`)
                    .then(response => {
                        const data = response.data;
                        data.forEach(indikator => {
                            const option = document.createElement('option');
                            option.value = indikator.id;
                            option.textContent = indikator.name;
                            indikatorSelect.appendChild(option);
                        });
                    })
                    .catch(error => {
                        console.error('Error:', error);
                        notyf.error('Gagal memuat data indikator');
                    });
            }
        });

        let fileInputCount = 1;

        function addFileInput() {
            const fileInputs = document.getElementById('fileInputs');
            const newFileInput = document.createElement('div');
            newFileInput.className = 'file-input-group';
            newFileInput.innerHTML = `
                <div class="flex items-center mt-2">
                    <input type="file" name="files[]" class="mt-1 block w-full" accept=".pdf,.doc,.docx,.xls,.xlsx,.jpg,.jpeg,.png">
                    <button type="button" onclick="removeFileInput(this)" class="ml-2 text-red-600 hover:text-red-900">
                        <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 7l-.867 12.142A2 2 0 0116.138 21H7.862a2 2 0 01-1.995-1.858L5 7m5 4v6m4-6v6m1-10V4a1 1 0 00-1-1h-4a1 1 0 00-1 1v3M4 7h16" />
                        </svg>
                    </button>
                </div>
            `;
            fileInputs.appendChild(newFileInput);
            fileInputCount++;
        }

        function removeFileInput(button) {
            button.closest('.file-input-group').remove();
            fileInputCount--;
            updateFilePreview();
        }

        document.addEventListener('change', function(e) {
            if (e.target.type === 'file') {
                updateFilePreview();
            }
        });

        function updateFilePreview() {
            const selectedFiles = document.getElementById('selectedFiles');
            selectedFiles.innerHTML = '';
            
            const allFiles = [];
            const fileInputs = document.querySelectorAll('input[type="file"]');
            
            fileInputs.forEach(input => {
                if (input.files.length > 0) {
                    Array.from(input.files).forEach(file => {
                        allFiles.push(file);
                    });
                }
            });
            
            allFiles.forEach((file, index) => {
                const fileDiv = document.createElement('div');
                fileDiv.className = 'flex flex-col p-3 bg-gray-50 rounded mb-2';
                fileDiv.id = `file-${index}`;
                
                const fileInfo = document.createElement('div');
                fileInfo.className = 'flex items-center mb-2';
                
                const icon = document.createElement('span');
                icon.className = 'mr-2';
                icon.innerHTML = '📎';
                
                const fileName = document.createElement('span');
                fileName.className = 'flex-1';
                fileName.textContent = `${index + 1}. ${file.name} (${formatFileSize(file.size)})`;
                
                const status = document.createElement('span');
                status.className = 'ml-2 text-sm text-gray-600';
                status.id = `status-${index}`;
                status.textContent = 'Siap diupload';
                
                fileInfo.appendChild(icon);
                fileInfo.appendChild(fileName);
                fileInfo.appendChild(status);
                
                const progressContainer = document.createElement('div');
                progressContainer.className = 'hidden w-full bg-gray-200 rounded h-2 mt-2';
                progressContainer.id = `progress-container-${index}`;
                
                const progressBar = document.createElement('div');
                progressBar.className = 'bg-indigo-600 h-2 rounded transition-all duration-300';
                progressBar.id = `progress-${index}`;
                progressBar.style.width = '0%';
                
                progressContainer.appendChild(progressBar);
                
                fileDiv.appendChild(fileInfo);
                fileDiv.appendChild(progressContainer);
                selectedFiles.appendChild(fileDiv);
            });
        }

        function formatFileSize(bytes) {
            if (bytes === 0) return '0 Bytes';
            const k = 1024;
            const sizes = ['Bytes', 'KB', 'MB', 'GB'];
            const i = Math.floor(Math.log(bytes) / Math.log(k));
            return parseFloat((bytes / Math.pow(k, i)).toFixed(2)) + ' ' + sizes[i];
        }

        document.getElementById('createForm').addEventListener('submit', async function(e) {
            e.preventDefault();
            
            const submitBtn = document.getElementById('submitBtn');
            submitBtn.disabled = true;
            submitBtn.textContent = 'Mengupload...';

            const formData = new FormData(this);
            const files = formData.getAll('files[]').filter(file => file.size > 0);
            
            if (files.length === 0) {
                notyf.error('Silakan pilih file terlebih dahulu');
                submitBtn.disabled = false;
                submitBtn.textContent = 'Simpan';
                return;
            }

            try {
                const result = await Swal.fire({
                    title: 'Konfirmasi Upload',
                    html: `Anda akan mengupload ${files.length} file.<br>Pastikan data yang diisi sudah benar.`,
                    icon: 'question',
                    showCancelButton: true,
                    confirmButtonText: 'Ya, Upload',
                    cancelButtonText: 'Batal',
                    confirmButtonColor: '#4F46E5',
                    cancelButtonColor: '#6B7280',
                    reverseButtons: true
                });

                if (!result.isConfirmed) {
                    submitBtn.disabled = false;
                    submitBtn.textContent = 'Simpan';
                    return;
                }

                files.forEach((file, index) => {
                    const progressContainer = document.getElementById(`progress-container-${index}`);
                    const status = document.getElementById(`status-${index}`);
                    
                    if (progressContainer) {
                        progressContainer.classList.remove('hidden');
                    }
                    if (status) {
                        status.textContent = 'Menunggu...';
                        status.className = 'ml-2 text-sm text-gray-600';
                    }
                });

                const response = await axios.post(this.action, formData, {
                    headers: {
                        'Content-Type': 'multipart/form-data',
                        'X-CSRF-TOKEN': document.querySelector('meta[name="csrf-token"]').content,
                        'Accept': 'application/json'
                    },
                    onUploadProgress: function(progressEvent) {
                        if (progressEvent.total) {
                            const totalProgress = Math.round((progressEvent.loaded * 100) / progressEvent.total);
                            
                            let accumulatedSize = 0;
                            for (let i = 0; i < files.length; i++) {
                                const fileSize = files[i].size;
                                const nextAccumulatedSize = accumulatedSize + fileSize;
                                const uploadedBytes = progressEvent.loaded;
                                
                                const progressBar = document.getElementById(`progress-${i}`);
                                const status = document.getElementById(`status-${i}`);
                                
                                if (uploadedBytes > accumulatedSize && uploadedBytes <= nextAccumulatedSize) {
                                    if (status) {
                                        status.textContent = 'Mengupload...';
                                        status.className = 'ml-2 text-sm text-blue-600';
                                    }
                                    
                                    if (progressBar) {
                                        const fileProgress = Math.round(((uploadedBytes - accumulatedSize) * 100) / fileSize);
                                        progressBar.style.width = fileProgress + '%';
                                    }
                                } else if (uploadedBytes > nextAccumulatedSize) {
                                    if (status) {
                                        status.textContent = 'Selesai';
                                        status.className = 'ml-2 text-sm text-green-600';
                                    }
                                    if (progressBar) {
                                        progressBar.style.width = '100%';
                                    }
                                }
                                
                                accumulatedSize = nextAccumulatedSize;
                            }
                            
                            if (totalProgress === 100) {
                                const lastStatus = document.getElementById(`status-${files.length - 1}`);
                                if (lastStatus) {
                                    lastStatus.textContent = 'Memproses...';
                                }
                            }
                        }
                    }
                });

                if (response.data) {
                    files.forEach((file, index) => {
                        const status = document.getElementById(`status-${index}`);
                        if (status) {
                            status.textContent = 'Selesai';
                            status.className = 'ml-2 text-sm text-green-600';
                        }
                    });

                    await Swal.fire({
                        title: 'Berhasil!',
                        text: 'Semua file berhasil diupload',
                        icon: 'success',
                        confirmButtonColor: '#10B981'
                    });

                    window.location.href = '{{ route("admin.data-dukung.index") }}';
                }
            } catch (error) {
                console.error('Upload error:', error);

                files.forEach((file, index) => {
                    const status = document.getElementById(`status-${index}`);
                    const progressBar = document.getElementById(`progress-${index}`);
                    if (status) {
                        status.textContent = 'Gagal';
                        status.className = 'ml-2 text-sm text-red-600';
                    }
                    if (progressBar) {
                        progressBar.className = 'bg-red-600 h-2 rounded';
                    }
                });

                let errorMessage = 'Terjadi kesalahan saat mengupload file';
                if (error.response?.data?.message) {
                    errorMessage = error.response.data.message;
                } else if (error.response?.data?.errors) {
                    const errors = error.response.data.errors;
                    errorMessage = '<ul class="text-left">';
                    Object.keys(errors).forEach(key => {
                        errorMessage += `<li>- ${errors[key][0]}</li>`;
                    });
                    errorMessage += '</ul>';
                }

                await Swal.fire({
                    title: 'Gagal!',
                    html: errorMessage,
                    icon: 'error',
                    confirmButtonColor: '#EF4444'
                });
            } finally {
                submitBtn.disabled = false;
                submitBtn.textContent = 'Simpan';
            }
        });
    </script>
    @endpush
</x-app-layout>