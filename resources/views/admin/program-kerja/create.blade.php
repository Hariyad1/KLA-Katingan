<x-app-layout>
    <x-slot name="header">
        <div class="flex justify-between items-center">
            <h2 class="font-semibold text-xl text-gray-800 leading-tight">
                {{ __('Tambah Program Kerja') }}
            </h2>
            <a href="{{ route('admin.program-kerja.index') }}" class="px-4 py-2 bg-gray-600 text-white rounded-md hover:bg-gray-700 transition-colors">
                Kembali
            </a>
        </div>
    </x-slot>

    <div class="py-12">
        <div class="max-w-4xl mx-auto sm:px-6 lg:px-8">
            <div class="bg-white overflow-hidden shadow-sm sm:rounded-lg">
                <div class="p-6 bg-white border-b border-gray-200">
                    <form action="{{ route('admin.program-kerja.store') }}" method="POST" id="createForm">
                        @csrf
                        <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
                            <!-- OPD -->
                            <div>
                                <label for="opd_id" class="block text-sm font-medium text-gray-700 mb-2">
                                    Perangkat Daerah <span class="text-red-500">*</span>
                                </label>                                <select name="opd_id" id="opd_id" required
                                        class="mt-1 block w-full rounded-md border-gray-300 shadow-sm focus:border-indigo-500 focus:ring-indigo-500">
                                    <option value="">Pilih Perangkat Daerah</option>
                                    @foreach($opds as $opd)
                                        <option value="{{ $opd->id }}" {{ old('opd_id') == $opd->id ? 'selected' : '' }}>
                                            {{ $opd->name }}
                                        </option>
                                    @endforeach
                                </select>
                                @error('opd_id')
                                    <p class="mt-2 text-sm text-red-600">{{ $message }}</p>
                                @enderror
                            </div>
                            
                            <!-- Tahun -->
                            <div>
                                <label for="tahun" class="block text-sm font-medium text-gray-700 mb-2">
                                    Tahun <span class="text-red-500">*</span>
                                </label>                                <select name="tahun" id="tahun" required
                                        class="mt-1 block w-full rounded-md border-gray-300 shadow-sm focus:border-indigo-500 focus:ring-indigo-500">
                                    @foreach($tahun as $t)
                                        <option value="{{ $t }}" {{ old('tahun', date('Y')) == $t ? 'selected' : '' }}>
                                            {{ $t }}
                                        </option>
                                    @endforeach
                                </select>
                                @error('tahun')
                                    <p class="mt-2 text-sm text-red-600">{{ $message }}</p>
                                @enderror
                            </div>
                              <!-- Deskripsi -->                            <div class="md:col-span-2">
                                <label for="description" class="block text-sm font-medium text-gray-700 mb-2">
                                    Deskripsi Program Kerja <span class="text-red-500">*</span>
                                </label>
                                <textarea name="description" id="description" rows="6" required
                                          placeholder="Masukkan deskripsi program kerja..."                                          class="mt-1 focus:ring-indigo-500 focus:border-indigo-500 block w-full shadow-sm sm:text-sm border-gray-300 rounded-md">{{ old('description') }}</textarea>
                                @error('description')
                                    <p class="mt-2 text-red-600">{{ $message }}</p>
                                @enderror
                            </div>
                        </div>
                        
                        <div class="mt-8 flex items-center justify-end space-x-4">
                            <a href="{{ route('admin.program-kerja.index') }}" 
                               class="px-4 py-2 border border-gray-300 rounded-md text-sm font-medium text-gray-700 bg-white hover:bg-gray-50 focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-indigo-500 transition-colors">
                                Batal
                            </a>
                            <button type="submit" 
                                    class="inline-flex items-center px-4 py-2 bg-indigo-600 border border-transparent rounded-md font-semibold text-xs text-white uppercase tracking-widest hover:bg-indigo-700 active:bg-indigo-800 focus:outline-none focus:border-indigo-900 focus:ring ring-indigo-300 disabled:opacity-25 transition ease-in-out duration-150">
                                <svg class="w-4 h-4 mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M5 13l4 4L19 7"></path>
                                </svg>
                                Simpan Program Kerja
                            </button>
                        </div>
                    </form>
                </div>
            </div>        </div>
    </div>

    @push('scripts')    <script src="https://cdn.jsdelivr.net/npm/notyf@3/notyf.min.js"></script>
    <script src="{{ asset('ckeditor/ckeditor.js') }}"></script>
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/notyf@3/notyf.min.css">
    
    <script>
        const notyf = new Notyf({
            duration: 4000,
            position: {
                x: 'right',
                y: 'top',
            }
        });

        // Initialize CKEditor
        let editor;
        CKEDITOR.replace('description', {
            height: 300,
            toolbar: [
                { name: 'basicstyles', items: ['Bold', 'Italic', 'Underline', 'Strike'] },
                { name: 'paragraph', items: ['NumberedList', 'BulletedList', '-', 'Outdent', 'Indent'] },
                { name: 'links', items: ['Link', 'Unlink'] },
                { name: 'insert', items: ['Table'] },
                { name: 'styles', items: ['Format'] },
                { name: 'tools', items: ['Maximize'] }
            ],
            on: {
                instanceReady: function(evt) {
                    editor = evt.editor;
                    updateCharCount();
                },
                change: function() {
                    updateCharCount();
                }
            }
        });

        // Character count for description
        const charCount = document.getElementById('charCount');
          function updateCharCount() {
            if (editor) {
                const text = editor.getData().replace(/<[^>]*>/g, ''); // Remove HTML tags
                const count = text.length;
                charCount.textContent = count;
                
                if (count < 10) {
                    charCount.parentElement.classList.add('text-red-500');
                    charCount.parentElement.classList.remove('text-gray-400');
                } else {
                    charCount.parentElement.classList.add('text-gray-400');
                    charCount.parentElement.classList.remove('text-red-500');
                }
            }
        }

        // Form validation
        document.getElementById('createForm').addEventListener('submit', function(e) {
            const opdSelect = document.getElementById('opd_id');
            const tahunSelect = document.getElementById('tahun');
            
            if (!opdSelect.value) {
                e.preventDefault();
                notyf.error('Silakan pilih Perangkat Daerah');
                opdSelect.focus();
                return;
            }
            
            if (!tahunSelect.value) {
                e.preventDefault();
                notyf.error('Silakan pilih tahun');
                tahunSelect.focus();
                return;
            }
            
            if (editor) {
                const description = editor.getData().replace(/<[^>]*>/g, '').trim();
                
                if (description.length < 10) {
                    e.preventDefault();
                    notyf.error('Deskripsi program kerja minimal 10 karakter');
                    editor.focus();
                    return;
                }
                  if (description.length > 2000) {
                    e.preventDefault();
                    notyf.error('Deskripsi program kerja maksimal 2000 karakter');
                    editor.focus();
                    return;
                }
            }
        });

        // Show success/error messages
        @if(session('success'))
            notyf.success("{{ session('success') }}");
        @endif

        @if(session('error'))
            notyf.error("{{ session('error') }}");
        @endif
    </script>
    @endpush
</x-app-layout>