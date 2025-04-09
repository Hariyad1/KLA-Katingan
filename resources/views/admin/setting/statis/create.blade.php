<x-app-layout>
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/notyf@3/notyf.min.css">
    <script src="https://cdn.jsdelivr.net/npm/notyf@3/notyf.min.js"></script>
    <x-slot name="header">
        <div class="flex justify-between items-center">
            <h2 class="font-semibold text-xl text-gray-800 leading-tight">
                {{ __('Tambah Pengaturan Statis') }}
            </h2>
            <a href="{{ route('admin.setting.statis.index') }}" class="bg-gray-300 hover:bg-gray-400 text-gray-800 font-bold py-2 px-4 rounded inline-flex items-center">
                <svg class="w-4 h-4 mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24" xmlns="http://www.w3.org/2000/svg">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M10 19l-7-7m0 0l7-7m-7 7h18"></path>
                </svg>
                <span>Kembali</span>
            </a>
        </div>
    </x-slot>

    <div class="py-12">
        <div class="max-w-7xl mx-auto sm:px-6 lg:px-8">
            <div class="bg-white overflow-hidden shadow-sm sm:rounded-lg">
                <div class="p-6 text-gray-900">
                    <div id="alertSuccess" class="hidden mb-4 p-4 bg-green-100 border border-green-400 text-green-700 rounded">
                        <span id="alertSuccessMessage"></span>
                    </div>
                    <div id="alertError" class="hidden mb-4 p-4 bg-red-100 border border-red-400 text-red-700 rounded">
                        <span id="alertErrorMessage"></span>
                    </div>

                    <div id="loadingIndicator" class="flex justify-center items-center py-8 hidden">
                        <svg class="animate-spin h-8 w-8 text-indigo-600" xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24">
                            <circle class="opacity-25" cx="12" cy="12" r="10" stroke="currentColor" stroke-width="4"></circle>
                            <path class="opacity-75" fill="currentColor" d="M4 12a8 8 0 018-8V0C5.373 0 0 5.373 0 12h4zm2 5.291A7.962 7.962 0 014 12H0c0 3.042 1.135 5.824 3 7.938l3-2.647z"></path>
                        </svg>
                    </div>

                    <form id="createSettingForm" class="space-y-6" enctype="multipart/form-data">
                        <div>
                            <label for="name" class="block text-sm font-medium text-gray-700">Nama</label>
                            <input type="text" name="name" id="name" class="mt-1 block w-full rounded-md border-gray-300 shadow-sm focus:border-indigo-500 focus:ring-indigo-500">
                            <p class="text-red-500 text-xs mt-1" id="nameError"></p>
                        </div>

                        <div>
                            <label for="page" class="block text-sm font-medium text-gray-700">Halaman</label>
                            <input type="text" name="page" id="page" class="mt-1 block w-full rounded-md border-gray-300 shadow-sm focus:border-indigo-500 focus:ring-indigo-500">
                            <p class="text-red-500 text-xs mt-1" id="pageError"></p>
                        </div>

                        <input type="hidden" name="type" value="statis">

                        <div id="urlField">
                            <label for="url" class="block text-sm font-medium text-gray-700">URL (contoh: klaster/kelembagaan)</label>
                            <input type="text" name="url" id="url" 
                                   class="mt-1 block w-full rounded-md border-gray-300 shadow-sm focus:border-indigo-500 focus:ring-indigo-500"
                                   placeholder="Masukkan URL tanpa tanda / di awal">
                            <p class="text-sm text-gray-500 mt-1">URL akan otomatis diformat menjadi slug</p>
                            <p class="text-red-500 text-xs mt-1" id="urlError"></p>
                        </div>

                        <div id="contentField">
                            <label for="content" class="block text-sm font-medium text-gray-700">Konten</label>
                            <textarea name="content" id="content" rows="3" class="mt-1 block w-full rounded-md border-gray-300 shadow-sm focus:border-indigo-500 focus:ring-indigo-500"></textarea>
                            <p class="text-red-500 text-xs mt-1" id="contentError"></p>
                        </div>

                        <div id="imageField">
                            <label for="image" class="block text-sm font-medium text-gray-700">Gambar</label>
                            <input type="file" name="image" id="image" class="mt-1 block w-full">
                            <p class="text-red-500 text-xs mt-1" id="imageError"></p>
                        </div>

                        <div>
                            <button type="submit" class="inline-flex justify-center py-2 px-4 border border-transparent shadow-sm text-sm font-medium rounded-md text-white bg-indigo-600 hover:bg-indigo-700 focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-indigo-500">
                                Simpan
                            </button>
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
    <script src="{{ asset('ckeditor/ckeditor.js') }}"></script>
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

        document.addEventListener('DOMContentLoaded', function() {
            toggleFields();

            document.getElementById('createSettingForm').addEventListener('submit', function(e) {
                e.preventDefault();

                document.getElementById('loadingIndicator')?.classList.remove('hidden');

                const formData = new FormData(this);
                formData.set('content', CKEDITOR.instances.content.getData());

                axios.post('/api/setting', formData, {
                    headers: {
                        'Accept': 'application/json',
                        'X-CSRF-TOKEN': '{{ csrf_token() }}',
                        'Authorization': 'Bearer {{ session("api_token") }}'
                    }
                })
                .then(function(response) {
                    document.getElementById('loadingIndicator')?.classList.add('hidden');

                    if (response.data.success) {
                        notyf.success('Pengaturan berhasil ditambahkan');
                        setTimeout(() => {
                            window.location.href = '{{ route("admin.setting.statis.index") }}';
                        }, 1500);
                    }
                })
                .catch(function(error) {
                    document.getElementById('loadingIndicator')?.classList.add('hidden');

                    if (error.response?.data?.errors) {
                        const errors = error.response.data.errors;
                        Object.keys(errors).forEach(key => {
                            notyf.error(errors[key][0]);
                        });
                    } else {
                        notyf.error('Terjadi kesalahan saat menambahkan pengaturan');
                    }
                });
            });
        });

        function toggleFields() {
            document.getElementById('imageField').style.display = 'block';
            document.getElementById('contentField').style.display = 'block';
            document.getElementById('urlField').style.display = 'block';
        }

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
    </script>
    @endpush
</x-app-layout> 