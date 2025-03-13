<x-app-layout>
    <!-- Tambahkan header -->
    <x-slot name="header">
        <div class="flex justify-between items-center">
            <h2 class="font-semibold text-xl text-gray-800 leading-tight">
                {{ __('Media Gambar') }}
            </h2>
            <a href="{{ route('admin.media.create') }}" class="bg-indigo-600 hover:bg-indigo-700 text-white font-bold py-2 px-4 rounded inline-flex items-center">
                <svg class="w-4 h-4 mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 6v6m0 0v6m0-6h6m-6 0H6"></path>
                </svg>
                <span>Upload Gambar</span>
            </a>
        </div>
    </x-slot>

    <!-- Hapus ml-60 -->
    <div class="py-12">
        <div class="max-w-7xl mx-auto sm:px-6 lg:px-8">
            <div class="bg-white overflow-hidden shadow-sm sm:rounded-lg">
                <div class="p-6">
                    <!-- Alert Messages -->
                    <div id="alertSuccess" class="hidden mb-4 p-4 bg-green-100 border border-green-400 text-green-700 rounded">
                        <span id="alertSuccessMessage"></span>
                    </div>
                    <div id="alertError" class="hidden mb-4 p-4 bg-red-100 border border-red-400 text-red-700 rounded">
                        <span id="alertErrorMessage"></span>
                    </div>

                    <!-- Tabel Media -->
                    <div class="overflow-x-auto">
                        <table class="min-w-full divide-y divide-gray-200">
                            <thead class="bg-gray-50">
                                <tr>
                                    <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">#</th>
                                    <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Nama</th>
                                    <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">File</th>
                                    <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Path</th>
                                    <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Slideshow</th>
                                    <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Aksi</th>
                                </tr>
                            </thead>
                            <tbody class="bg-white divide-y divide-gray-200">
                                @foreach($media as $index => $item)
                                    <tr data-media-id="{{ $item->id }}" data-media-name="{{ $item->name }}">
                                        <td class="px-6 py-4 whitespace-nowrap text-sm">{{ $index + 1 }}</td>
                                        <td class="px-6 py-4 whitespace-nowrap text-sm">{{ $item->name }}</td>
                                        <td class="px-6 py-4 whitespace-nowrap text-sm">
                                            @php
                                                $extension = strtolower(pathinfo($item->file, PATHINFO_EXTENSION));
                                                $type = match($extension) {
                                                    'jpg', 'jpeg', 'png', 'gif' => 'Image/' . strtoupper($extension),
                                                    'pdf' => 'PDF Document',
                                                    'doc', 'docx' => 'Word Document',
                                                    'xls', 'xlsx' => 'Excel Document',
                                                    default => 'Unknown'
                                                };
                                            @endphp
                                            {{ $type }}
                                        </td>
                                        <td class="px-6 py-4 whitespace-nowrap text-sm">{{ $item->file }}</td>
                                        <td class="px-6 py-4 whitespace-nowrap text-sm">
                                            <span class="px-2 inline-flex text-xs leading-5 font-semibold rounded-full
                                                {{ $item->slide_show ? 'bg-green-100 text-green-800' : 'bg-gray-100 text-gray-800' }}">
                                                {{ $item->slide_show ? 'Yes' : 'No' }}
                                            </span>
                                        </td>
                                        <td class="px-6 py-4 whitespace-nowrap text-sm font-medium">
                                            <a href="{{ route('media.edit', $item->id) }}" class="text-indigo-600 hover:text-indigo-900 mr-3">Ubah</a>
                                            <button onclick="deleteMedia({{ $item->id }})" class="text-red-600 hover:text-red-900">Hapus</button>
                                        </td>
                                    </tr>
                                @endforeach
                            </tbody>
                        </table>
                    </div>
                </div>
            </div>
        </div>
    </div>

    <!-- Upload Modal -->
    <div x-data="{ open: false }" x-show="open" @open-upload-modal.window="open = true" class="fixed inset-0 z-50 overflow-y-auto" style="display: none;">
        <div class="flex items-center justify-center min-h-screen px-4">
            <div class="fixed inset-0 bg-gray-500 bg-opacity-75 transition-opacity"></div>

            <div class="relative bg-white rounded-lg max-w-lg w-full">
                <div class="p-6">
                    <h3 class="text-lg font-medium text-gray-900 mb-4">Upload Gambar</h3>
                    
                    <form id="uploadForm" class="space-y-4">
                        @csrf
                        <div>
                            <label class="block text-sm font-medium text-gray-700">Nama</label>
                            <input type="text" name="name" class="mt-1 block w-full rounded-md border-gray-300 shadow-sm focus:border-indigo-500 focus:ring-indigo-500" required>
                        </div>

                        <div>
                            <label class="block text-sm font-medium text-gray-700">File Gambar</label>
                            <input type="file" name="file" accept="image/*" class="mt-1 block w-full" required>
                            <p class="mt-1 text-sm text-gray-500">Format yang didukung: JPG, JPEG, PNG, GIF</p>
                        </div>

                        <div>
                            <label class="flex items-center">
                                <input type="checkbox" name="slide_show" value="1" class="rounded border-gray-300 text-indigo-600 shadow-sm focus:border-indigo-500 focus:ring-indigo-500">
                                <span class="ml-2 text-sm text-gray-600">Tampilkan di slideshow</span>
                            </label>
                            <p class="mt-1 text-xs text-gray-500">Jika dicentang, gambar akan ditampilkan di slideshow</p>
                        </div>

                        <div class="mt-5 flex justify-end space-x-3">
                            <button type="button" @click="open = false" class="px-4 py-2 text-sm font-medium text-gray-700 bg-white border border-gray-300 rounded-md hover:bg-gray-50">
                                Batal
                            </button>
                            <button type="submit" class="px-4 py-2 text-sm font-medium text-white bg-indigo-600 border border-transparent rounded-md hover:bg-indigo-700">
                                Upload
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
        function openUploadModal() {
            window.dispatchEvent(new CustomEvent('open-upload-modal'));
        }

        function deleteMedia(id) {
            if (confirm('Apakah Anda yakin ingin menghapus media ini?')) {
                // Tampilkan loading
                document.getElementById('loadingIndicator')?.classList.remove('hidden');

                axios.delete(`/api/media/${id}`, {
                    headers: {
                        'X-CSRF-TOKEN': document.querySelector('meta[name="csrf-token"]').content,
                        'Accept': 'application/json',
                        'Authorization': 'Bearer ' + document.querySelector('meta[name="api-token"]').content
                    }
                })
                .then(response => {
                    const data = response.data;
                    if (data.success) {
                        showSuccess('Media berhasil dihapus');
                        setTimeout(() => window.location.reload(), 1000);
                    } else {
                        showError(data.message || 'Gagal menghapus media');
                    }
                })
                .catch(error => {
                    showError('Terjadi kesalahan saat menghapus media');
                    console.error('Error:', error);
                })
                .finally(() => {
                    document.getElementById('loadingIndicator')?.classList.add('hidden');
                });
            }
        }

        function toggleSlideshow(id) {
            const formData = new FormData();
            formData.append('_method', 'PUT');
            formData.append('name', document.querySelector(`[data-media-id="${id}"]`).dataset.mediaName);
            formData.append('slide_show', '1');
            
            // Tampilkan loading
            document.getElementById('loadingIndicator')?.classList.remove('hidden');

            axios.post(`/api/media/${id}`, formData, {
                headers: {
                    'X-CSRF-TOKEN': document.querySelector('meta[name="csrf-token"]').content,
                    'Accept': 'application/json',
                    'Authorization': 'Bearer ' + document.querySelector('meta[name="api-token"]').content
                }
            })
            .then(response => {
                const data = response.data;
                if (data.success) {
                    showSuccess('Status slideshow berhasil diubah');
                    setTimeout(() => window.location.reload(), 1000);
                } else {
                    showError(data.message || 'Gagal mengubah status slideshow');
                }
            })
            .catch(error => {
                showError('Terjadi kesalahan saat mengubah status slideshow');
                console.error('Error:', error);
            })
            .finally(() => {
                document.getElementById('loadingIndicator')?.classList.add('hidden');
            });
        }

        document.getElementById('uploadForm').addEventListener('submit', function(e) {
            e.preventDefault();
            
            const formData = new FormData(this);
            
            // Tampilkan loading
            showLoading();
            document.getElementById('loadingIndicator')?.classList.remove('hidden');
            
            axios.post('/api/media', formData, {
                headers: {
                    'Accept': 'application/json',
                    'Authorization': 'Bearer ' + document.querySelector('meta[name="api-token"]').content
                }
            })
            .then(response => {
                const data = response.data;
                if (data.success) {
                    showSuccess('Media berhasil diupload');
                    setTimeout(() => window.location.reload(), 1000);
                } else {
                    showError(data.message || 'Gagal mengupload media');
                }
            })
            .catch(error => {
                showError('Terjadi kesalahan saat mengupload media');
                console.error('Error:', error);
            })
            .finally(() => {
                hideLoading();
                document.getElementById('loadingIndicator')?.classList.add('hidden');
            });
        });

        // Utility functions
        function showLoading() {
            document.querySelector('button[type="submit"]').disabled = true;
            document.querySelector('button[type="submit"]').textContent = 'Uploading...';
        }

        function hideLoading() {
            document.querySelector('button[type="submit"]').disabled = false;
            document.querySelector('button[type="submit"]').textContent = 'Upload';
        }

        function showSuccess(message) {
            const alert = document.getElementById('alertSuccess');
            const alertMessage = document.getElementById('alertSuccessMessage');
            alertMessage.textContent = message;
            alert.classList.remove('hidden');
            setTimeout(() => alert.classList.add('hidden'), 3000);
        }

        function showError(message) {
            const alert = document.getElementById('alertError');
            const alertMessage = document.getElementById('alertErrorMessage');
            alertMessage.innerHTML = message;
            alert.classList.remove('hidden');
            setTimeout(() => alert.classList.add('hidden'), 5000);
        }
    </script>
    @endpush
</x-app-layout> 