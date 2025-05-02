<x-app-layout>
    <div class="pl-4 py-12">
        <div class="max-w-7xl mx-auto sm:px-6 lg:px-8">
            <div class="bg-white overflow-hidden shadow-sm sm:rounded-lg">
                <div class="p-6">
                    <div class="flex justify-between items-center mb-6">
                        <h2 class="text-2xl font-semibold text-gray-800">Edit Berita</h2>
                        <a href="{{ route('admin.news.index') }}" class="bg-gray-300 hover:bg-gray-400 text-gray-800 font-bold py-2 px-4 rounded inline-flex items-center">
                            <svg class="w-4 h-4 mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M10 19l-7-7m0 0l7-7m-7 7h18"></path>
                            </svg>
                            <span>Kembali</span>
                        </a>
                    </div>

                    <form id="editForm" class="space-y-6">
                        @csrf
                        @method('PUT')
                        <div>
                            <label class="block text-sm font-medium text-gray-700">Judul Berita</label>
                            <input type="text" name="title" value="{{ $news->title }}" class="mt-1 block w-full rounded-md border-gray-300 shadow-sm focus:border-indigo-500 focus:ring-indigo-500" required>
                            <p class="text-red-500 text-xs mt-1" id="titleError"></p>
                        </div>

                        <div>
                            <label class="block text-sm font-medium text-gray-700">Kategori</label>
                            <select name="kategori_id" class="mt-1 block w-full rounded-md border-gray-300 shadow-sm focus:border-indigo-500 focus:ring-indigo-500" required>
                                <option value="">Pilih Kategori</option>
                                @foreach($categories as $category)
                                    <option value="{{ $category->id }}" {{ $news->kategori_id == $category->id ? 'selected' : '' }}>
                                        {{ $category->name }}
                                    </option>
                                @endforeach
                            </select>
                            <p class="text-red-500 text-xs mt-1" id="kategoriError"></p>
                        </div>

                        <div>
                            <label class="block text-sm font-medium text-gray-700">Konten</label>
                            <textarea id="content" name="content" class="mt-1 block w-full rounded-md border-gray-300 shadow-sm focus:border-indigo-500 focus:ring-indigo-500" rows="10"></textarea>
                            <p class="text-red-500 text-xs mt-1" id="contentError"></p>
                        </div>

                        <div>
                            <label class="block text-sm font-medium text-gray-700">Gambar Saat Ini</label>
                            <img src="{{ asset($news->image) }}" alt="Current Image" class="mt-2 h-32 w-auto">
                        </div>

                        <div>
                            <label class="block text-sm font-medium text-gray-700">Ganti Gambar (Opsional)</label>
                            <input type="file" name="image" id="imageInput" accept="image/*" class="mt-1 block w-full" onchange="previewImage(this)">
                            
                            <!-- Image Preview -->
                            <div id="imagePreview" class="mt-3 hidden">
                                <img id="previewImg" src="#" alt="Preview" class="h-40 w-auto object-cover rounded-md">
                                <button type="button" onclick="resetImage()" class="mt-2 px-2 py-1 bg-red-500 text-white text-xs rounded hover:bg-red-600">
                                    Hapus
                                </button>
                            </div>
                            
                            <!-- Upload Progress Bar -->
                            <div id="uploadProgress" class="mt-3 hidden">
                                <div class="flex justify-between mb-1">
                                    <p class="text-xs text-gray-700" id="progressText">0%</p>
                                    <div class="flex space-x-2">
                                        <p class="text-xs text-gray-700"><span id="uploadedSize">0</span>/<span id="totalSize">0</span> MB</p>
                                        <p class="text-xs text-gray-700"><span id="uploadSpeed">0</span> KB/s</p>
                                    </div>
                                </div>
                                <div class="w-full bg-gray-200 rounded-full h-2.5">
                                    <div id="progressBar" class="bg-blue-600 h-2.5 rounded-full" style="width: 0%"></div>
                                </div>
                            </div>
                            
                            <p class="mt-1 text-sm text-gray-500">Format yang didukung: JPG, JPEG, PNG</p>
                            <p class="text-red-500 text-xs mt-1" id="imageError"></p>
                        </div>

                        <div>
                            <button type="submit" class="inline-flex justify-center py-2 px-4 border border-transparent shadow-sm text-sm font-medium rounded-md text-white bg-indigo-600 hover:bg-indigo-700 focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-indigo-500">
                                Simpan Perubahan
                            </button>
                        </div>
                    </form>

                    <div id="loadingIndicator" class="fixed inset-0 bg-gray-500 bg-opacity-75 flex items-center justify-center z-50">
                        <div class="bg-white p-4 rounded-lg flex items-center space-x-3">
                            <svg class="animate-spin h-8 w-8 text-indigo-600" xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24">
                                <circle class="opacity-25" cx="12" cy="12" r="10" stroke="currentColor" stroke-width="4"></circle>
                                <path class="opacity-75" fill="currentColor" d="M4 12a8 8 0 018-8V0C5.373 0 0 5.373 0 12h4zm2 5.291A7.962 7.962 0 014 12H0c0 3.042 1.135 5.824 3 7.938l3-2.647z"></path>
                            </svg>
                            <span class="text-gray-700 text-lg font-medium">Memuat data...</span>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>

    @push('scripts')
    <script src="https://cdn.jsdelivr.net/npm/axios/dist/axios.min.js"></script>
    <script src="{{ asset('ckeditor/ckeditor.js') }}"></script>
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/notyf@3/notyf.min.css">
    <script src="https://cdn.jsdelivr.net/npm/notyf@3/notyf.min.js"></script>
    <script>
        document.addEventListener('DOMContentLoaded', function() {
            const loadingIndicator = document.getElementById('loadingIndicator');
            loadingIndicator.classList.remove('hidden');

            var token = '{{ csrf_token() }}';
            
            var uploadUrl = '{{ route("upload.image") }}?_token=' + token;

            CKEDITOR.replace('content', {
                height: 400,
                filebrowserUploadUrl: uploadUrl,
                filebrowserImageUploadUrl: uploadUrl,
                filebrowserImageBrowseUrl: false,
                uploadUrl: uploadUrl,
                toolbar: [
                    { name: 'document', items: [ 'Source', '-', 'Save', 'NewPage', 'Preview', 'Print', '-', 'Templates' ] },
                    { name: 'clipboard', items: [ 'Cut', 'Copy', 'Paste', 'PasteText', '-', 'Undo', 'Redo' ] },
                    { name: 'editing', items: [ 'Find', 'Replace', '-', 'SelectAll', '-', 'Scayt' ] },
                    { name: 'basicstyles', items: [ 'Bold', 'Italic', 'Underline', 'Strike', 'Subscript', 'Superscript', '-', 'CopyFormatting', 'RemoveFormat' ] },
                    '/',
                    { name: 'paragraph', items: [ 'NumberedList', 'BulletedList', '-', 'Outdent', 'Indent', '-', 'Blockquote', 'CreateDiv', '-', 'JustifyLeft', 'JustifyCenter', 'JustifyRight', 'JustifyBlock', '-', 'BidiLtr', 'BidiRtl' ] },
                    { name: 'links', items: [ 'Link', 'Unlink', 'Anchor' ] },
                    { name: 'insert', items: [ 'Image', 'Table', 'HorizontalRule', 'SpecialChar' ] },
                    '/',
                    { name: 'styles', items: [ 'Styles', 'Format', 'Font', 'FontSize' ] },
                    { name: 'colors', items: [ 'TextColor', 'BGColor' ] },
                    { name: 'tools', items: [ 'Maximize', 'ShowBlocks' ] }
                ],
                allowedContent: true,
                extraPlugins: 'wysiwygarea',
                enterMode: CKEDITOR.ENTER_P,
                shiftEnterMode: CKEDITOR.ENTER_BR,
                autoParagraph: false,
                fillEmptyBlocks: false,
                entities: false,
                basicEntities: false,
                entities_latin: false,
                entities_greek: false,
                entities_additional: '',
                htmlEncodeOutput: false,
                forceSimpleAmpersand: true,
                removeDialogTabs: 'image:advanced;image:link;link:advanced;link:target'
            });
            
            CKEDITOR.on('dialogDefinition', function(ev) {
                var dialogName = ev.data.name;
                var dialogDefinition = ev.data.definition;
                
                if (dialogName == 'image') {
                    dialogDefinition.removeContents('advanced');
                    
                    var infoTab = dialogDefinition.getContents('info');
                    
                    var uploadTab = dialogDefinition.getContents('Upload');
                    if (uploadTab) {
                        var uploadField = uploadTab.get('upload');
                        if (uploadField) {
                            uploadField.action = uploadUrl;
                        }
                    }
                }
            });

            const newsId = '{{ $news->id }}';
            axios.get(`/api/news/${newsId}`, {
                headers: {
                    'Content-Type': 'multipart/form-data',
                    'Accept': 'application/json',
                    'X-CSRF-TOKEN': document.querySelector('meta[name="csrf-token"]').content,
                    'Authorization': 'Bearer ' + document.querySelector('meta[name="api-token"]').getAttribute('content')
                }
            })
            .then(function(response) {
                if (response.data.success) {
                    const news = response.data.data;
                    document.querySelector('input[name="title"]').value = news.title;
                    document.querySelector('select[name="kategori_id"]').value = news.kategori_id;
                    CKEDITOR.instances.content.setData(news.content);
                }
            })
            .catch(error => {
                console.error('Error:', error);
                showError('Gagal memuat data berita');
            })
            .finally(() => {
                loadingIndicator.classList.add('hidden');
            });

            const notyf = new Notyf({
                duration: 3000,
                position: {
                    x: 'right',
                    y: 'top',
                },
                types: [
                    {
                        type: 'success',
                        background: '#10B981',
                        icon: {
                            className: 'fas fa-check-circle',
                            tagName: 'span',
                            color: '#fff'
                        },
                        dismissible: true
                    },
                    {
                        type: 'error',
                        background: '#EF4444',
                        icon: {
                            className: 'fas fa-times-circle',
                            tagName: 'span',
                            color: '#fff'
                        },
                        dismissible: true
                    }
                ]
            });

            function showSuccess(message) {
                notyf.success({
                    message: message,
                    dismissible: true
                });
            }

            function showError(message) {
                notyf.error({
                    message: message,
                    dismissible: true
                });
            }

            document.getElementById('editForm').addEventListener('submit', function(e) {
                e.preventDefault();
                
                const formData = new FormData(this);
                formData.append('_method', 'PUT');
                formData.set('content', CKEDITOR.instances.content.getData());
                
                axios.post(`/api/news/${newsId}`, formData, {
                    headers: {
                        'Content-Type': 'multipart/form-data',
                        'X-CSRF-TOKEN': document.querySelector('meta[name="csrf-token"]').content,
                        'Accept': 'application/json',
                        'Authorization': 'Bearer ' + document.querySelector('meta[name="api-token"]').getAttribute('content')
                    },
                    onUploadProgress: function(progressEvent) {
                        const percentCompleted = Math.round((progressEvent.loaded * 100) / progressEvent.total);
                        const totalSize = (progressEvent.total / (1024 * 1024)).toFixed(2);
                        const uploadedSize = (progressEvent.loaded / (1024 * 1024)).toFixed(2);
                        
                        const currentTime = Date.now();
                        if (!window.lastUploadTime) {
                            window.lastUploadTime = currentTime;
                            window.lastLoaded = 0;
                        }
                        
                        const timeElapsed = (currentTime - window.lastUploadTime) / 1000;
                        if (timeElapsed > 0.5) {
                            const loadedSinceLastUpdate = progressEvent.loaded - window.lastLoaded;
                            const speedKBps = Math.round((loadedSinceLastUpdate / 1024) / timeElapsed);
                            
                            document.getElementById('uploadSpeed').textContent = speedKBps;
                            window.lastUploadTime = currentTime;
                            window.lastLoaded = progressEvent.loaded;
                        }
                        
                        document.getElementById('progressBar').style.width = percentCompleted + '%';
                        document.getElementById('progressText').textContent = percentCompleted + '%';
                        document.getElementById('totalSize').textContent = totalSize;
                        document.getElementById('uploadedSize').textContent = uploadedSize;
                        document.getElementById('uploadProgress').classList.remove('hidden');
                    }
                })
                .then(response => {
                    if (response.data.success) {
                        showSuccess('Berita berhasil diperbarui');
                        setTimeout(() => {
                            window.location.href = '{{ route("admin.news.index") }}';
                        }, 1000);
                    }
                })
                .catch(error => {
                    if (error.response?.data?.errors) {
                        const errors = error.response.data.errors;
                        Object.keys(errors).forEach(field => {
                            const errorElement = document.getElementById(`${field}Error`);
                            if (errorElement) {
                                errorElement.textContent = errors[field][0];
                            }
                        });
                    } else {
                        showError('Terjadi kesalahan saat memperbarui berita');
                    }
                });
            });

            function previewImage(input) {
                const preview = document.getElementById('previewImg');
                const previewContainer = document.getElementById('imagePreview');
                
                if (input.files && input.files[0]) {
                    const reader = new FileReader();
                    
                    reader.onload = function(e) {
                        preview.src = e.target.result;
                        previewContainer.classList.remove('hidden');
                    }
                    
                    reader.readAsDataURL(input.files[0]);
                }
            }
            
            function resetImage() {
                const input = document.getElementById('imageInput');
                const previewContainer = document.getElementById('imagePreview');
                
                input.value = '';
                previewContainer.classList.add('hidden');
            }
        });
    </script>
    @endpush
</x-app-layout> 