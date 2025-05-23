<x-app-layout>
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/notyf@3/notyf.min.css">
    <script src="https://cdn.jsdelivr.net/npm/notyf@3/notyf.min.js"></script>
    <x-slot name="header">
        <div class="flex justify-between items-center">
            <h2 class="font-semibold text-xl text-gray-800 leading-tight">
                {{ __('Edit Pengaturan') }}
            </h2>
            <a href="{{ route('admin.setting.video.index') }}" class="bg-gray-300 hover:bg-gray-400 text-gray-800 font-bold py-2 px-4 rounded inline-flex items-center">
                <svg class="w-4 h mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24" xmlns="http://www.w3.org/2000/svg">
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
                    <div id="loadingIndicator" class="flex justify-center items-center py-8 hidden">
                        <svg class="animate-spin h-8 w-8 text-indigo-600" xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24">
                            <circle class="opacity-25" cx="12" cy="12" r="10" stroke="currentColor" stroke-width="4"></circle>
                            <path class="opacity-75" fill="currentColor" d="M4 12a8 8 0 018-8V0C5.373 0 0 5.373 0 12h4zm2 5.291A7.962 7.962 0 014 12H0c0 3.042 1.135 5.824 3 7.938l3-2.647z"></path>
                        </svg>
                    </div>

                    <form id="editSettingForm" class="space-y-6" enctype="multipart/form-data">
                        <div>
                            <label for="name" class="block text-sm font-medium text-gray-700">Nama</label>
                            <input type="text" name="name" id="name" class="mt-1 block w-full rounded-md border-gray-300 shadow-sm focus:border-indigo-500 focus:ring-indigo-500">
                            <p class="text-red-500 text-xs mt-1" id="nameError"></p>
                        </div>

                        <div>
                            <label for="page" class="block text-sm font-medium text-gray-700">Halaman (contoh: video)</label>
                            <input type="text" name="page" id="page" class="mt-1 block w-full rounded-md border-gray-300 shadow-sm focus:border-indigo-500 focus:ring-indigo-500">
                            <p class="text-red-500 text-xs mt-1" id="pageError"></p>
                        </div>

                        <input type="hidden" name="type" id="type" value="video">

                        <div id="urlField">
                            <label for="url" class="block text-sm font-medium text-gray-700">URL (contoh: https://www.youtube.com/watch?v=example)</label>
                            <input type="text" name="url" id="url" class="mt-1 block w-full rounded-md border-gray-300 shadow-sm focus:border-indigo-500 focus:ring-indigo-500">
                            <p class="text-red-500 text-xs mt-1" id="urlError"></p>
                        </div>

                        <div id="contentField">
                            <label for="content" class="block text-sm font-medium text-gray-700">Konten</label>
                            <textarea 
                                name="content" 
                                id="content" 
                                rows="5" 
                                class="mt-1 block w-full rounded-md border-gray-300 shadow-sm focus:border-indigo-500 focus:ring-indigo-500"
                                placeholder="Masukkan deskripsi video..."
                            ></textarea>
                            <p class="text-red-500 text-xs mt-1" id="contentError"></p>
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

    @push('styles')
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/notyf@3/notyf.min.css">
    @endpush

    @push('scripts')
    <script src="https://cdn.jsdelivr.net/npm/axios/dist/axios.min.js"></script>
    <script src="https://cdn.jsdelivr.net/npm/notyf@3/notyf.min.js"></script>
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
            const settingId = '{{ $setting->id }}';
            
            document.getElementById('loadingIndicator').classList.remove('hidden');
            
            const config = {
                headers: {
                    'Accept': 'application/json',
                    'X-CSRF-TOKEN': '{{ csrf_token() }}',
                    'Authorization': 'Bearer {{ session("api_token") }}'
                }
            };
            
            axios.get(`/api/setting/${settingId}`, config)
                .then(function(response) {
                    document.getElementById('loadingIndicator').classList.add('hidden');
                    
                    if (response.data.success) {
                        const setting = response.data.data;
                        
                        document.getElementById('name').value = setting.name || '';
                        document.getElementById('page').value = setting.page || '';
                        document.getElementById('url').value = setting.url || '';
                        document.getElementById('content').value = setting.content || '';
                    } else {
                        notyf.error('Gagal memuat data pengaturan');
                    }
                })
                .catch(function(error) {
                    document.getElementById('loadingIndicator').classList.add('hidden');
                    console.error('Error:', error);
                    notyf.error('Terjadi kesalahan saat memuat data pengaturan');
                });
            
            document.getElementById('editSettingForm').addEventListener('submit', function(e) {
                e.preventDefault();
                
                document.querySelectorAll('.text-red-500').forEach(el => el.textContent = '');
                
                document.getElementById('loadingIndicator').classList.remove('hidden');
                
                const formData = new FormData(this);
                formData.append('_method', 'PUT');
                
                axios.post(`/api/setting/${settingId}?_method=PUT`, formData, {
                    headers: {
                        'Accept': 'application/json',
                        'X-CSRF-TOKEN': '{{ csrf_token() }}',
                        'Authorization': 'Bearer {{ session("api_token") }}'
                    }
                })
                .then(function(response) {
                    document.getElementById('loadingIndicator').classList.add('hidden');
                    
                    if (response.data.success) {
                        notyf.success('Pengaturan berhasil diperbarui');
                        
                        setTimeout(function() {
                            window.location.href = '{{ route("admin.setting.video.index") }}';
                        }, 1500);
                    } else {
                        notyf.error('Gagal memperbarui pengaturan');
                    }
                })
                .catch(function(error) {
                    document.getElementById('loadingIndicator').classList.add('hidden');
                    
                    if (error.response && error.response.data && error.response.data.errors) {
                        const errors = error.response.data.errors;
                        
                        if (errors.name) {
                            document.getElementById('nameError').textContent = errors.name[0];
                        }
                        
                        if (errors.page) {
                            document.getElementById('pageError').textContent = errors.page[0];
                        }
                        
                        if (errors.url) {
                            document.getElementById('urlError').textContent = errors.url[0];
                        }
                        
                        if (errors.content) {
                            document.getElementById('contentError').textContent = errors.content[0];
                        }
                    } else {
                        notyf.error('Terjadi kesalahan saat memperbarui pengaturan');
                    }
                });
            });
        });
    </script>
    @endpush
</x-app-layout> 