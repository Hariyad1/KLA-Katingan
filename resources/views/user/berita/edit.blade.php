<x-app-layout>
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/notyf@3/notyf.min.css">
    <script src="https://cdn.jsdelivr.net/npm/notyf@3/notyf.min.js"></script>
    <div class="pl-4 py-12">
        <form id="ckeditor-form" action="{{ route('upload.image') }}" method="post" enctype="multipart/form-data" style="display: none;">
            @csrf
            <input type="file" name="upload">
        </form>
        
        <div class="max-w-7xl mx-auto sm:px-6 lg:px-8">
            <div class="bg-white overflow-hidden shadow-sm sm:rounded-lg">
                <div class="p-6">
                    <div class="flex justify-between items-center mb-6">
                        <h2 class="text-2xl font-semibold text-gray-800">Edit Berita</h2>
                        <a href="{{ route('user.news.index') }}" class="bg-gray-300 hover:bg-gray-400 text-gray-800 font-bold py-2 px-4 rounded inline-flex items-center">
                            <svg class="w-4 h-4 mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M10 19l-7-7m0 0l7-7m-7 7h18"></path>
                            </svg>
                            <span>Kembali</span>
                        </a>
                    </div>

                    <form id="editForm" class="space-y-6">
                        @csrf
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
                                    <option value="{{ $category->id }}" {{ $news->kategori_id == $category->id ? 'selected' : '' }}>{{ $category->name }}</option>
                                @endforeach
                            </select>
                            <p class="text-red-500 text-xs mt-1" id="kategoriError"></p>
                        </div>

                        <div>
                            <label class="block text-sm font-medium text-gray-700">Konten</label>
                            <textarea id="content" name="content" class="mt-1 block w-full rounded-md border-gray-300 shadow-sm focus:border-indigo-500 focus:ring-indigo-500" rows="10">{{ $news->content }}</textarea>
                            <p class="text-red-500 text-xs mt-1" id="contentError"></p>
                        </div>

                        <div>
                            <label class="block text-sm font-medium text-gray-700">Gambar Berita</label>
                            @if($news->image)
                                <div class="mt-2">
                                    @php
                                        $imagePath = str_replace('storage/', '', $news->image);
                                    @endphp
                                    
                                    <img src="{{ asset('storage/' . $imagePath) }}" 
                                         alt="Gambar Berita" 
                                         class="h-32 w-auto"
                                         onerror="this.style.display='none'; document.getElementById('image-error').style.display='block';">
                                    
                                    <p id="image-error" style="display:none;" class="text-red-500 mt-2">
                                        Gambar tidak dapat ditampilkan. Upload gambar baru.
                                    </p>
                                </div>
                            @endif
                            <input type="file" name="image" accept="image/*" class="mt-1 block w-full">
                            <p class="mt-1 text-sm text-gray-500">Format yang didukung: JPG, JPEG, PNG</p>
                            <p class="text-red-500 text-xs mt-1" id="imageError"></p>
                        </div>

                        <div>
                            <button type="submit" class="inline-flex justify-center py-2 px-4 border border-transparent shadow-sm text-sm font-medium rounded-md text-white bg-indigo-600 hover:bg-indigo-700 focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-indigo-500">
                                Simpan Perubahan
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
            var token = '{{ csrf_token() }}';
            
            var uploadUrl = '{{ route("upload.image") }}?_token=' + token;
            
            CKEDITOR.replace('content', {
                height: 400,
                filebrowserUploadUrl: uploadUrl,
                filebrowserImageUploadUrl: uploadUrl,
                filebrowserImageBrowseUrl: false,
                uploadUrl: uploadUrl,
                removeDialogTabs: 'image:advanced;image:link;image:Link;link:advanced;link:target',
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
                forceSimpleAmpersand: true
                
            });

            document.getElementById('editForm').addEventListener('submit', function(e) {
                e.preventDefault();
                
                document.querySelectorAll('.text-red-500').forEach(el => el.textContent = '');
                
                const formData = new FormData(this);
                
                formData.set('content', CKEDITOR.instances.content.getData());
                
                document.getElementById('loadingIndicator')?.classList.remove('hidden');
                
                axios.post(`/api/news/{{ $news->id }}`, formData, {
                    headers: {
                        'Content-Type': 'multipart/form-data',
                        'X-CSRF-TOKEN': document.querySelector('meta[name="csrf-token"]').content,
                        'Accept': 'application/json',
                        'Authorization': 'Bearer ' + document.querySelector('meta[name="api-token"]').content,
                        'X-HTTP-Method-Override': 'PUT'
                    }
                })
                .then(response => {
                    if (response.data.success) {
                        showSuccess('Berita berhasil diperbarui');
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
                        showError('Terjadi kesalahan saat memperbarui berita');
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