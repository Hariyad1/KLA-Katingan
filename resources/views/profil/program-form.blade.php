<x-main-layout>
    <div class="relative h-[300px] flex items-center justify-center overflow-hidden">
        <div class="absolute inset-0">
            <img src="{{ asset('images/inner-head.png') }}" alt="Header Background" class="w-full h-full object-cover">
            <div class="absolute inset-0 bg-gradient-to-b from-purple-900/50 to-purple-900/70"></div>
        </div>
        
        <div class="absolute inset-0">
            <div class="absolute left-20 top-20">
                <svg width="80" height="80" viewBox="0 0 80 80" class="text-orange-500 opacity-80">
                    <circle cx="40" cy="40" r="40" fill="currentColor"/>
                </svg>
            </div>
            
            <div class="absolute right-32 top-16">
                <svg width="24" height="24" viewBox="0 0 24 24" class="text-yellow-300 opacity-80">
                    <path fill="currentColor" d="M12 2l3.09 6.26L22 9.27l-5 4.87 1.18 6.88L12 17.77l-6.18 3.25L7 14.14 2 9.27l6.91-1.01L12 2z"/>
                </svg>
            </div>
            <div class="absolute right-48 bottom-24">
                <svg width="16" height="16" viewBox="0 0 24 24" class="text-yellow-300 opacity-80">
                    <path fill="currentColor" d="M12 2l3.09 6.26L22 9.27l-5 4.87 1.18 6.88L12 17.77l-6.18 3.25L7 14.14 2 9.27l6.91-1.01L12 2z"/>
                </svg>
            </div>
            
            <div class="absolute right-16 top-12">
                <svg width="100" height="100" viewBox="0 0 100 100" class="text-yellow-300 opacity-80 transform -rotate-45">
                    <path fill="currentColor" d="M50 0 L52 98 L48 98 L50 0 Z"/>
                    <circle cx="50" cy="10" r="8" fill="currentColor"/>
                </svg>
            </div>
        </div>
        
        <div class="relative z-10 text-center">
            <h1 class="text-5xl font-extrabold text-white mb-4 tracking-wide">
                TAMBAH PROGRAM KERJA
            </h1>
            <div class="flex items-center justify-center text-white text-lg font-medium">
                <a href="{{ route('home') }}" class="hover:text-yellow-300 transition-colors">Beranda</a>
                <span class="mx-3 text-yellow-300">•</span>
                <a href="{{ route('profil.program') }}" class="hover:text-yellow-300 transition-colors">Program Kerja</a>
                <span class="mx-3 text-yellow-300">•</span>
                <span class="text-yellow-300">Tambah Program</span>
            </div>
        </div>
    </div>

    <div class="py-12">
        <div class="max-w-4xl mx-auto sm:px-6 lg:px-8">
            <div class="bg-white overflow-hidden shadow-sm sm:rounded-lg">
                <div class="p-6">
                    <div class="prose max-w-none">
                        <div class="bg-purple-50 p-8 rounded-lg border border-purple-200">
                            <h3 class="text-2xl font-bold text-purple-800 mb-6">Form Tambah Program Kerja</h3>
                            
                            @if($errors->any())
                                <div class="mb-6 p-4 bg-red-100 border border-red-200 text-red-700 rounded-md">
                                    <ul class="list-disc pl-5">
                                        @foreach($errors->all() as $error)
                                            <li>{{ $error }}</li>
                                        @endforeach
                                    </ul>
                                </div>
                            @endif
                            
                            <form action="{{ route('profil.program.store') }}" method="POST" class="grid grid-cols-1 gap-6">
                                @csrf
                                
                                <!-- OPD -->
                                <div>
                                    <label for="input_opd_id" class="block text-sm font-medium text-gray-700 mb-1">OPD <span class="text-red-500">*</span></label>
                                    <select id="input_opd_id" name="opd_id" required class="w-full rounded-md border-gray-300 shadow-sm focus:border-purple-500 focus:ring-purple-500">
                                        <option value="">Pilih OPD</option>
                                        @foreach($opds as $o)
                                            <option value="{{ $o->id }}" {{ old('opd_id') == $o->id ? 'selected' : '' }}>{{ $o->name }}</option>
                                        @endforeach
                                    </select>
                                    <p class="mt-1 text-sm text-gray-500">Pilih Organisasi Perangkat Daerah (OPD) terkait</p>
                                </div>
                                
                                <!-- Tahun -->
                                <div>
                                    <label for="input_tahun" class="block text-sm font-medium text-gray-700 mb-1">Tahun <span class="text-red-500">*</span></label>
                                    <select id="input_tahun" name="tahun" required class="w-full rounded-md border-gray-300 shadow-sm focus:border-purple-500 focus:ring-purple-500">
                                        <option value="">Pilih Tahun</option>
                                        @foreach($tahunList as $t)
                                            <option value="{{ $t }}" {{ old('tahun') == $t ? 'selected' : '' }}>{{ $t }}</option>
                                        @endforeach
                                        @php
                                            $currentYear = (int)date('Y');
                                            $nextYear = $currentYear + 1;
                                        @endphp
                                        @if(!in_array($currentYear, $tahunList))
                                            <option value="{{ $currentYear }}" {{ old('tahun') == $currentYear ? 'selected' : '' }}>{{ $currentYear }}</option>
                                        @endif
                                        @if(!in_array($nextYear, $tahunList))
                                            <option value="{{ $nextYear }}" {{ old('tahun') == $nextYear ? 'selected' : '' }}>{{ $nextYear }}</option>
                                        @endif
                                    </select>
                                    <p class="mt-1 text-sm text-gray-500">Pilih tahun pelaksanaan program kerja</p>
                                </div>
                                
                                <div>
                                    <label for="description" class="block text-sm font-medium text-gray-700 mb-1">Deskripsi Program Kerja <span class="text-red-500">*</span></label>
                                    <textarea id="description" name="description" rows="5" required class="w-full rounded-md border-gray-300 shadow-sm focus:border-purple-500 focus:ring-purple-500">{{ old('description') }}</textarea>
                                </div>
                                
                                <div class="flex gap-4 mt-4">
                                    <a href="{{ route('profil.program') }}" class="px-6 py-3 bg-gray-200 text-gray-700 rounded-md hover:bg-gray-300 transition-colors">
                                        Kembali
                                    </a>
                                    <button type="submit" class="px-6 py-3 bg-purple-600 text-white rounded-md hover:bg-purple-700 transition-colors">
                                        Simpan Program Kerja
                                    </button>
                                </div>
                            </form>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>

    <script src="{{ asset('ckeditor/ckeditor.js') }}"></script>
    <script src="https://cdn.jsdelivr.net/npm/sweetalert2@11"></script>
    
    <script>
        @if(session('success'))
            Swal.fire({
                icon: 'success',
                title: 'Berhasil!',
                text: '{{ session('success') }}',
                confirmButtonColor: '#9333ea'
            });
        @endif
        
        CKEDITOR.replace('description', {
            height: 400,
            removeButtons: 'PasteFromWord',
            toolbar: [
                { name: 'document', items: [ 'Source', '-', 'Preview', 'Print', '-', 'Templates' ] },
                { name: 'clipboard', items: [ 'Cut', 'Copy', 'Paste', 'PasteText', '-', 'Undo', 'Redo' ] },
                { name: 'editing', items: [ 'Find', 'Replace', '-', 'SelectAll', '-', 'Scayt' ] },
                { name: 'basicstyles', items: [ 'Bold', 'Italic', 'Underline', 'Strike', 'Subscript', 'Superscript', '-', 'CopyFormatting', 'RemoveFormat' ] },
                '/',
                { name: 'paragraph', items: [ 'NumberedList', 'BulletedList', '-', 'Outdent', 'Indent', '-', 'Blockquote', 'CreateDiv', '-', 'JustifyLeft', 'JustifyCenter', 'JustifyRight', 'JustifyBlock', '-', 'BidiLtr', 'BidiRtl' ] },
                { name: 'links', items: [ 'Link', 'Unlink', 'Anchor' ] },
                { name: 'insert', items: [ 'Table', 'HorizontalRule', 'SpecialChar' ] },
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
    </script>
</x-main-layout> 