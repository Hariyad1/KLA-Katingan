<x-app-layout>
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/notyf@3/notyf.min.css">
    <script src="https://cdn.jsdelivr.net/npm/notyf@3/notyf.min.js"></script>
    <div class="pl-4 py-12">
        <div class="max-w-7xl mx-auto sm:px-6 lg:px-8">
            <div class="bg-white overflow-hidden shadow-sm sm:rounded-lg">
                <div class="p-6">
                    <div class="flex justify-between items-center mb-6">
                        <h2 class="text-2xl font-semibold text-gray-800">Tambah Berita Baru</h2>
                        <a href="{{ route('user.news.index') }}" class="bg-gray-300 hover:bg-gray-400 text-gray-800 font-bold py-2 px-4 rounded inline-flex items-center">
                            <svg class="w-4 h-4 mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M10 19l-7-7m0 0l7-7m-7 7h18"></path>
                            </svg>
                            <span>Kembali</span>
                        </a>
                    </div>

                    <form id="createForm" class="space-y-6">
                        @csrf
                        <div>
                            <label class="block text-sm font-medium text-gray-700">Judul Berita</label>
                            <input type="text" name="title" class="mt-1 block w-full rounded-md border-gray-300 shadow-sm focus:border-indigo-500 focus:ring-indigo-500" required>
                            <p class="text-red-500 text-xs mt-1" id="titleError"></p>
                        </div>

                        <div>
                            <label class="block text-sm font-medium text-gray-700">Kategori</label>
                            <select name="kategori_id" class="mt-1 block w-full rounded-md border-gray-300 shadow-sm focus:border-indigo-500 focus:ring-indigo-500" required>
                                <option value="">Pilih Kategori</option>
                                @foreach($categories as $category)
                                    <option value="{{ $category->id }}">{{ $category->name }}</option>
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
                            <label class="block text-sm font-medium text-gray-700">Gambar Berita</label>
                            <input type="file" name="image" id="imageInput" accept="image/*" class="mt-1 block w-full" required>
                            
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
                                Simpan Berita
                            </button>
                        </div>
                    </form>
                </div>
            </div>
        </div>
    </div>

    @push('scripts')
    <script src="https://cdn.jsdelivr.net/npm/axios/dist/axios.min.js"></script>
    <script src="{{ asset('ckeditor/ckeditor.js') }}"></script>
    <script>
        document.addEventListener('DOMContentLoaded', function() {
            CKEDITOR.replace('content', {
                height: 400,
                removeButtons: 'PasteFromWord',
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
                filebrowserUploadMethod: 'form',
                filebrowserUploadUrl: '{{ route("upload.image") }}',
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
                forceSimpleAmpersand: true
            });

            document.getElementById('createForm').addEventListener('submit', function(e) {
                e.preventDefault();
                
                document.querySelectorAll('.text-red-500').forEach(el => el.textContent = '');
                
                const formData = new FormData(this);
                
                formData.set('content', CKEDITOR.instances.content.getData());
                
                document.getElementById('loadingIndicator')?.classList.remove('hidden');
                
                axios.post('/api/news', formData, {
                    headers: {
                        'Content-Type': 'multipart/form-data',
                        'X-CSRF-TOKEN': document.querySelector('meta[name="csrf-token"]').content,
                        'Accept': 'application/json',
                        'Authorization': 'Bearer ' + document.querySelector('meta[name="api-token"]').content
                    },
                    onUploadProgress: function(progressEvent) {
                        const percentCompleted = Math.round((progressEvent.loaded * 100) / progressEvent.total);
                        const totalSize = (progressEvent.total / (1024 * 1024)).toFixed(2);
                        const uploadedSize = (progressEvent.loaded / (1024 * 1024)).toFixed(2);
                        
                        // Menghitung kecepatan upload
                        const currentTime = Date.now();
                        if (!window.lastUploadTime) {
                            window.lastUploadTime = currentTime;
                            window.lastLoaded = 0;
                        }
                        
                        const timeElapsed = (currentTime - window.lastUploadTime) / 1000; // dalam detik
                        if (timeElapsed > 0.5) { // update setiap 0.5 detik
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
                        showSuccess('Berita berhasil ditambahkan');
                        setTimeout(() => {
                            window.location.href = '{{ route("user.news.index") }}';
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
                        showError('Terjadi kesalahan saat menambahkan berita');
                    }
                })
                .finally(() => {
                    document.getElementById('loadingIndicator')?.classList.add('hidden');
                });
            });
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
    </script>
    @endpush
</x-app-layout> 