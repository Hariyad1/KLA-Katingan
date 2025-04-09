<x-app-layout>
    <x-slot name="header">
        <div class="flex justify-between items-center">
            <h2 class="font-semibold text-xl text-gray-800 leading-tight">
                {{ __('Tambah Agenda Baru') }}
            </h2>
            <a href="{{ route('admin.agenda.index') }}" class="bg-gray-300 hover:bg-gray-400 text-gray-800 font-bold py-2 px-4 rounded inline-flex items-center">
                <svg class="w-4 h-4 mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24">
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
                    <div id="loadingIndicator" class="hidden flex justify-center items-center py-4">
                        <svg class="animate-spin h-8 w-8 text-indigo-600" xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24">
                            <circle class="opacity-25" cx="12" cy="12" r="10" stroke="currentColor" stroke-width="4"></circle>
                            <path class="opacity-75" fill="currentColor" d="M4 12a8 8 0 018-8V0C5.373 0 0 5.373 0 12h4zm2 5.291A7.962 7.962 0 014 12H0c0 3.042 1.135 5.824 3 7.938l3-2.647z"></path>
                        </svg>
                    </div>

                    <form id="createForm" class="space-y-6">
                        <div>
                            <label for="title" class="block text-sm font-medium text-gray-700">Judul</label>
                            <input type="text" id="title" name="title" class="mt-1 block w-full rounded-md border-gray-300 shadow-sm focus:border-indigo-500 focus:ring-indigo-500">
                            <p class="text-red-500 text-xs mt-1 hidden" id="titleError"></p>
                        </div>
                        
                        <div>
                            <label for="tanggal" class="block text-sm font-medium text-gray-700">Tanggal</label>
                            <input type="date" id="tanggal" name="tanggal" class="mt-1 block w-full rounded-md border-gray-300 shadow-sm focus:border-indigo-500 focus:ring-indigo-500">
                            <p class="text-red-500 text-xs mt-1 hidden" id="tanggalError"></p>
                        </div>
                        
                        <div>
                            <label for="keterangan" class="block text-sm font-medium text-gray-700">Keterangan</label>
                            <textarea id="keterangan" name="keterangan" rows="3" class="mt-1 block w-full rounded-md border-gray-300 shadow-sm focus:border-indigo-500 focus:ring-indigo-500"></textarea>
                            <p class="text-red-500 text-xs mt-1 hidden" id="keteranganError"></p>
                        </div>
                        
                        <div class="flex justify-end">
                            <a href="{{ route('admin.agenda.index') }}" class="bg-gray-200 text-gray-700 px-4 py-2 rounded mr-2">Batal</a>
                            <button type="button" onclick="submitForm()" class="bg-indigo-600 text-white px-4 py-2 rounded">Simpan</button>
                        </div>
                    </form>
                </div>
            </div>
        </div>
    </div>

    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/notyf@3/notyf.min.css">
    <script src="https://cdn.jsdelivr.net/npm/notyf@3/notyf.min.js"></script>

    @push('scripts')
    <script src="https://cdn.jsdelivr.net/npm/axios/dist/axios.min.js"></script>
    <script>
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

        function submitForm() {
            document.querySelectorAll('.text-red-500').forEach(el => {
                el.textContent = '';
                el.classList.add('hidden');
            });
            
            document.getElementById('loadingIndicator').classList.remove('hidden');
            
            const formData = {
                title: document.getElementById('title').value,
                tanggal: document.getElementById('tanggal').value,
                keterangan: document.getElementById('keterangan').value
            };
            
            axios.post('/api/agenda', formData, {
                headers: {
                    'Content-Type': 'application/json',
                    'X-CSRF-TOKEN': document.querySelector('meta[name="csrf-token"]').content,
                    'Accept': 'application/json',
                    'Authorization': 'Bearer ' + document.querySelector('meta[name="api-token"]').content
                }
            })
            .then(response => {
                const data = response.data;
                if (data.success) {
                    showSuccess('Agenda berhasil ditambahkan');
                    setTimeout(() => {
                        window.location.href = '{{ route("admin.agenda.index") }}';
                    }, 1000);
                } else {
                    showError(data.message || 'Gagal menambahkan agenda');
                }
            })
            .catch(error => {
                if (error.response?.data?.errors) {
                    const errors = error.response.data.errors;
                    Object.keys(errors).forEach(field => {
                        const errorElement = document.getElementById(`${field}Error`);
                        if (errorElement) {
                            errorElement.textContent = errors[field][0];
                            errorElement.classList.remove('hidden');
                        }
                    });
                } else {
                    showError('Terjadi kesalahan saat menyimpan agenda');
                }
            })
            .finally(() => {
                document.getElementById('loadingIndicator').classList.add('hidden');
            });
        }

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