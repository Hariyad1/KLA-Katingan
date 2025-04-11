<x-app-layout>
    <x-slot name="header">
        <h2 class="font-semibold text-xl text-gray-800 leading-tight">
            {{ __('Tambah Data Dukung') }}
        </h2>
    </x-slot>

    @push('scripts')
    <script src="https://cdn.jsdelivr.net/npm/axios/dist/axios.min.js"></script>
    <script src="https://cdn.jsdelivr.net/npm/sweetalert2@11"></script>
    @endpush

    <div class="py-12">
        <div class="max-w-7xl mx-auto sm:px-6 lg:px-8">
            <div class="bg-white overflow-hidden shadow-sm sm:rounded-lg">
                <div class="p-6">
                    <form action="{{ route('user.data-dukung.store') }}" method="POST" enctype="multipart/form-data" id="uploadForm">
                        @csrf
                        <div class="grid grid-cols-1 gap-6">
                            <div>
                                <label for="opd_id" class="block text-sm font-medium text-gray-700">OPD</label>
                                <select name="opd_id" id="opd_id" class="mt-1 block w-full rounded-md border-gray-300 shadow-sm focus:border-indigo-500 focus:ring-indigo-500" required>
                                    <option value="">Pilih OPD</option>
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

                                <p class="mt-4 text-sm text-gray-500">Format file: PDF, DOC, DOCX, XLS, XLSX, JPG, JPEG, PNG. Maksimal 25MB per file.</p>
                                
                                <div id="selectedFiles" class="mt-4 space-y-2"></div>
                                
                                @error('files')
                                    <p class="mt-1 text-sm text-red-600">{{ $message }}</p>
                                @enderror
                                @error('files.*')
                                    <p class="mt-1 text-sm text-red-600">{{ $message }}</p>
                                @enderror
                            </div>

                            <div>
                                <label for="description" class="block text-sm font-medium text-gray-700">Deskripsi</label>
                                <textarea name="description" id="description" rows="3" class="mt-1 block w-full rounded-md border-gray-300 shadow-sm focus:border-indigo-500 focus:ring-indigo-500">{{ old('description') }}</textarea>
                                @error('description')
                                    <p class="mt-2 text-sm text-red-600">{{ $message }}</p>
                                @enderror
                            </div>

                            <div class="flex justify-end">
                                <a href="{{ route('user.data-dukung.index') }}" class="bg-gray-500 hover:bg-gray-700 text-white font-bold py-2 px-4 rounded mr-2">
                                    Batal
                                </a>
                                <button type="submit" id="submitBtn" class="bg-indigo-600 hover:bg-indigo-700 text-white font-bold py-2 px-4 rounded">
                                    Simpan
                                </button>
                            </div>

                            <!-- Progress Bar Section -->
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
                        </div>
                    </form>
                </div>
            </div>
        </div>
    </div>

    @push('scripts')
    <script>
        document.addEventListener('DOMContentLoaded', function() {
            const klasterSelect = document.getElementById('klaster_id');
            const indikatorSelect = document.getElementById('indikator_id');
            const indikatorDesc = document.getElementById('indikator-description');

            if (klasterSelect) {
                klasterSelect.addEventListener('change', function() {
                    const klasterId = this.value;
                    
                    if (indikatorSelect) {
                        indikatorSelect.innerHTML = '<option value="">Pilih Indikator</option>';
                        
                        if (klasterId) {
                            fetch(`/api/klaster/${klasterId}/indikators`)
                                .then(response => response.json())
                                .then(data => {
                                    data.forEach(indikator => {
                                        const option = document.createElement('option');
                                        option.value = indikator.id;
                                        option.textContent = indikator.name;
                                        option.setAttribute('data-description', indikator.name);
                                        indikatorSelect.appendChild(option);
                                    });
                                })
                                .catch(error => {
                                    console.error('Error fetching indikators:', error);
                                });
                        }
                    }
                });
            }

            if (indikatorSelect) {
                indikatorSelect.addEventListener('change', function() {
                    if (indikatorDesc) {
                        const selectedOption = this.options[this.selectedIndex];
                        
                        if (selectedOption && selectedOption.value) {
                            const description = selectedOption.getAttribute('data-description');
                            indikatorDesc.innerHTML = `
                                <div class="text-gray-600">
                                    <p class="font-medium mb-1">Deskripsi Indikator:</p>
                                    <p>${description || 'Tidak ada deskripsi'}</p>
                                </div>
                            `;
                            indikatorDesc.classList.remove('hidden');
                        } else {
                            indikatorDesc.classList.add('hidden');
                        }
                    }
                });
            }

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
                    fileDiv.className = 'flex items-center p-2 bg-gray-50 rounded';
                    
                    const icon = document.createElement('span');
                    icon.className = 'mr-2';
                    icon.innerHTML = '📎';
                    
                    const fileName = document.createElement('span');
                    fileName.className = 'flex-1';
                    fileName.textContent = `${index + 1}. ${file.name} (${formatFileSize(file.size)})`;
                    
                    const status = document.createElement('span');
                    status.className = 'ml-2 text-sm text-green-600';
                    status.textContent = 'Siap diupload';
                    
                    fileDiv.appendChild(icon);
                    fileDiv.appendChild(fileName);
                    fileDiv.appendChild(status);
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

            document.querySelector('form').addEventListener('submit', function(e) {
                const selectedFiles = document.getElementById('selectedFiles');
                const fileDivs = selectedFiles.children;
                
                Array.from(fileDivs).forEach(fileDiv => {
                    const status = fileDiv.querySelector('span:last-child');
                    status.textContent = 'Mengupload...';
                    status.className = 'ml-2 text-sm text-blue-600';
                });
            });

            const form = document.getElementById('uploadForm');
            const submitBtn = document.getElementById('submitBtn');
            
            form.addEventListener('submit', async function(e) {
                e.preventDefault();
                
                const formData = new FormData(form);
                const files = formData.getAll('files[]').filter(file => file.size > 0);
                
                if (files.length === 0 && !confirm('Tidak ada file yang akan diupload. Lanjutkan menyimpan?')) {
                    return false;
                }

                try {
                    const result = await Swal.fire({
                        title: 'Konfirmasi',
                        text: 'Apakah Anda yakin ingin menyimpan data ini?',
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
                    }

                    const totalSize = files.reduce((acc, file) => acc + file.size, 0);
                    let uploadedSize = 0;
                    let startTime = Date.now();

                    const response = await axios.post(form.action, formData, {
                        headers: {
                            'Content-Type': 'multipart/form-data',
                            'X-CSRF-TOKEN': document.querySelector('meta[name="csrf-token"]').getAttribute('content')
                        },
                        onUploadProgress: (progressEvent) => {
                            if (files.length > 0) {
                                const progress = (progressEvent.loaded / progressEvent.total) * 100;
                                const progressBar = document.getElementById('progressBar');
                                const progressText = document.getElementById('progressText');
                                const uploadedSizeEl = document.getElementById('uploadedSize');
                                const uploadStatus = document.getElementById('uploadStatus');
                                const uploadSpeed = document.getElementById('uploadSpeed');

                                progressBar.style.width = progress + '%';
                                progressText.textContent = Math.round(progress) + '%';

                                uploadedSize = progressEvent.loaded;
                                uploadedSizeEl.textContent = `${formatSize(uploadedSize)} / ${formatSize(totalSize)}`;

                                const currentTime = Date.now();
                                const elapsedTime = (currentTime - startTime) / 1000;
                                const speed = uploadedSize / elapsedTime;
                                uploadSpeed.textContent = `(${formatSize(speed)}/s)`;

                                if (progress < 100) {
                                    uploadStatus.textContent = 'Mengupload...';
                                } else {
                                    uploadStatus.textContent = 'Selesai';
                                }
                            }
                        }
                    });

                    await Swal.fire({
                        icon: 'success',
                        title: 'Berhasil!',
                        text: 'Data dukung berhasil disimpan',
                        timer: 1500,
                        showConfirmButton: false
                    });

                    window.location.href = '{{ route("user.data-dukung.index") }}';

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
                }

                return false;
            });

            function formatSize(bytes) {
                if (bytes === 0) return '0 Bytes';
                const k = 1024;
                const sizes = ['Bytes', 'KB', 'MB', 'GB'];
                const i = Math.floor(Math.log(bytes) / Math.log(k));
                return parseFloat((bytes / Math.pow(k, i)).toFixed(2)) + ' ' + sizes[i];
            }
        });
    </script>
    @endpush
</x-app-layout> 