<x-app-layout>
    <x-slot name="header">
        <div class="flex justify-between items-center">
            <h2 class="font-semibold text-xl text-gray-800 leading-tight">
                {{ __('Edit Pengaturan') }}
            </h2>
            <a href="{{ route('admin.setting.index') }}" class="bg-gray-300 hover:bg-gray-400 text-gray-800 font-bold py-2 px-4 rounded inline-flex items-center">
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
                    <!-- Alert untuk menampilkan pesan sukses/error -->
                    <div id="alertSuccess" class="hidden mb-4 p-4 bg-green-100 border border-green-400 text-green-700 rounded">
                        <span id="alertSuccessMessage"></span>
                    </div>
                    <div id="alertError" class="hidden mb-4 p-4 bg-red-100 border border-red-400 text-red-700 rounded">
                        <span id="alertErrorMessage"></span>
                    </div>

                    <!-- Loading indicator -->
                    <div id="loadingIndicator" class="flex justify-center items-center py-8 hidden">
                        <svg class="animate-spin h-8 w-8 text-indigo-600" xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24">
                            <circle class="opacity-25" cx="12" cy="12" r="10" stroke="currentColor" stroke-width="4"></circle>
                            <path class="opacity-75" fill="currentColor" d="M4 12a8 8 0 018-8V0C5.373 0 0 5.373 0 12h4zm2 5.291A7.962 7.962 0 014 12H0c0 3.042 1.135 5.824 3 7.938l3-2.647z"></path>
                        </svg>
                    </div>

                    <!-- Form Edit Setting -->
                    <form id="editSettingForm" class="space-y-6" enctype="multipart/form-data">
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

                        <div>
                            <label for="type" class="block text-sm font-medium text-gray-700">Tipe</label>
                            <select name="type" id="type" class="mt-1 block w-full rounded-md border-gray-300 shadow-sm focus:border-indigo-500 focus:ring-indigo-500" onchange="toggleFields()">
                                <option value="statis">Statis</option>
                                <option value="video">Video</option>
                            </select>
                            <p class="text-red-500 text-xs mt-1" id="typeError"></p>
                        </div>

                        <div id="urlField">
                            <label for="url" class="block text-sm font-medium text-gray-700">URL</label>
                            <input type="text" name="url" id="url" class="mt-1 block w-full rounded-md border-gray-300 shadow-sm focus:border-indigo-500 focus:ring-indigo-500">
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
                            <div id="currentImage" class="mt-2 hidden">
                                <p class="text-sm text-gray-500">Gambar saat ini:</p>
                                <img id="previewImage" src="" alt="Current Image" class="mt-1 h-32 object-cover">
                            </div>
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
    <script>
        document.addEventListener('DOMContentLoaded', function() {
            const settingId = '{{ $setting->id }}';
            
            // Tampilkan loading
            document.getElementById('loadingIndicator').classList.remove('hidden');
            
            // Konfigurasi axios
            const config = {
                headers: {
                    'Content-Type': 'application/json',
                    'X-CSRF-TOKEN': '{{ csrf_token() }}',
                    'Accept': 'application/json',
                    'Authorization': 'Bearer {{ session("api_token") }}'
                }
            };
            
            // Ambil data setting dari API
            axios.get(`/api/setting/${settingId}`, config)
                .then(function(response) {
                    // Sembunyikan loading
                    document.getElementById('loadingIndicator').classList.add('hidden');
                    
                    if (response.data.success) {
                        const setting = response.data.data;
                        
                        // Isi form dengan data
                        document.getElementById('name').value = setting.name || '';
                        document.getElementById('page').value = setting.page || '';
                        document.getElementById('url').value = setting.url || '';
                        document.getElementById('content').value = setting.content || '';
                        document.getElementById('type').value = setting.type || 'statis';
                        
                        // Tampilkan gambar jika ada
                        if (setting.image) {
                            document.getElementById('currentImage').classList.remove('hidden');
                            document.getElementById('previewImage').src = setting.image;
                        }
                        
                        // Atur tampilan field berdasarkan tipe
                        toggleFields();
                    } else {
                        showError('Gagal memuat data pengaturan');
                    }
                })
                .catch(function(error) {
                    // Sembunyikan loading
                    document.getElementById('loadingIndicator').classList.add('hidden');
                    
                    console.error('Error:', error);
                    showError('Terjadi kesalahan saat memuat data pengaturan');
                });
            
            // Handle form submit
            document.getElementById('editSettingForm').addEventListener('submit', function(e) {
                e.preventDefault();
                
                // Reset error messages
                document.querySelectorAll('.text-red-500').forEach(el => el.textContent = '');
                
                // Tampilkan loading
                document.getElementById('loadingIndicator').classList.remove('hidden');
                
                // Buat FormData untuk mengirim file
                const formData = new FormData(this);
                formData.append('_method', 'PUT');
                
                // Konfigurasi axios untuk form data
                const formConfig = {
                    headers: {
                        'X-CSRF-TOKEN': '{{ csrf_token() }}',
                        'Accept': 'application/json',
                        'Authorization': 'Bearer {{ session("api_token") }}'
                    }
                };
                
                // Kirim request ke API
                axios.post(`/api/setting/${settingId}?_method=PUT`, formData, formConfig)
                    .then(function(response) {
                        // Sembunyikan loading
                        document.getElementById('loadingIndicator').classList.add('hidden');
                        
                        if (response.data.success) {
                            showSuccess('Pengaturan berhasil diperbarui');
                            
                            // Redirect ke halaman index setelah 1 detik
                            setTimeout(function() {
                                window.location.href = '{{ route("admin.setting.index") }}';
                            }, 1000);
                        } else {
                            showError('Gagal memperbarui pengaturan');
                        }
                    })
                    .catch(function(error) {
                        // Sembunyikan loading
                        document.getElementById('loadingIndicator').classList.add('hidden');
                        
                        console.error('Error:', error);
                        
                        if (error.response && error.response.data && error.response.data.errors) {
                            // Tampilkan error validasi
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
                            
                            if (errors.type) {
                                document.getElementById('typeError').textContent = errors.type[0];
                            }
                            
                            if (errors.content) {
                                document.getElementById('contentError').textContent = errors.content[0];
                            }
                            
                            if (errors.image) {
                                document.getElementById('imageError').textContent = errors.image[0];
                            }
                        } else {
                            showError('Terjadi kesalahan saat memperbarui pengaturan');
                        }
                    });
            });
            
            // Fungsi untuk menampilkan pesan sukses
            function showSuccess(message) {
                const alert = document.getElementById('alertSuccess');
                const alertMessage = document.getElementById('alertSuccessMessage');
                alertMessage.textContent = message;
                alert.classList.remove('hidden');
                
                // Sembunyikan pesan setelah 3 detik
                setTimeout(function() {
                    alert.classList.add('hidden');
                }, 3000);
            }
            
            // Fungsi untuk menampilkan pesan error
            function showError(message) {
                const alert = document.getElementById('alertError');
                const alertMessage = document.getElementById('alertErrorMessage');
                alertMessage.textContent = message;
                alert.classList.remove('hidden');
                
                // Sembunyikan pesan setelah 3 detik
                setTimeout(function() {
                    alert.classList.add('hidden');
                }, 3000);
            }
        });
        
        // Fungsi untuk menampilkan/menyembunyikan field berdasarkan tipe
        function toggleFields() {
            const type = document.getElementById('type').value;
            
            if (type === 'statis') {
                document.getElementById('imageField').style.display = 'block';
                document.getElementById('contentField').style.display = 'block';
                document.getElementById('urlField').style.display = 'block';
            } else if (type === 'video') {
                document.getElementById('imageField').style.display = 'none';
                document.getElementById('contentField').style.display = 'block';
                document.getElementById('urlField').style.display = 'block';
            }
        }
    </script>
    @endpush
</x-app-layout> 