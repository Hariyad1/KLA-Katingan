<x-app-layout>
    <div class="pl-4 py-12">
        <div class="max-w-7xl mx-auto sm:px-6 lg:px-8">
            <div class="bg-white overflow-hidden shadow-sm sm:rounded-lg">
                <div class="p-6">
                    <div class="flex justify-between items-center mb-6">
                        <h2 class="text-2xl font-semibold text-gray-800">Edit Media</h2>
                        <a href="{{ route('media.index') }}" class="bg-gray-300 hover:bg-gray-400 text-gray-800 font-bold py-2 px-4 rounded inline-flex items-center">
                            <svg class="w-4 h-4 mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M10 19l-7-7m0 0l7-7m-7 7h18"></path>
                            </svg>
                            <span>Kembali</span>
                        </a>
                    </div>

                    <!-- Alert Messages -->
                    <div id="alertSuccess" class="hidden mb-4 p-4 bg-green-100 border border-green-400 text-green-700 rounded">
                        <span id="alertSuccessMessage"></span>
                    </div>
                    <div id="alertError" class="hidden mb-4 p-4 bg-red-100 border border-red-400 text-red-700 rounded">
                        <span id="alertErrorMessage"></span>
                    </div>

                    <!-- Form Edit Media -->
                    <form id="editForm" class="space-y-6">
                        @csrf
                        @method('PUT')
                        <div>
                            <label class="block text-sm font-medium text-gray-700">Nama Media</label>
                            <input type="text" name="name" value="{{ $media->name }}" class="mt-1 block w-full rounded-md border-gray-300 shadow-sm focus:border-indigo-500 focus:ring-indigo-500" required>
                            <p class="text-red-500 text-xs mt-1" id="nameError"></p>
                        </div>

                        <div>
                            <label class="block text-sm font-medium text-gray-700">Gambar Saat Ini</label>
                            <img src="{{ $media->path }}" alt="{{ $media->name }}" class="mt-2 h-32 w-auto">
                        </div>

                        <div>
                            <label class="block text-sm font-medium text-gray-700">Ganti Gambar (Opsional)</label>
                            <input type="file" name="file" accept="image/*" class="mt-1 block w-full">
                            <p class="mt-1 text-sm text-gray-500">Format yang didukung: JPG, JPEG, PNG, GIF</p>
                            <p class="text-red-500 text-xs mt-1" id="fileError"></p>
                        </div>

                        <div>
                            <label class="flex items-center">
                                <input type="checkbox" id="slide_show" name="slide_show" {{ $media->slide_show ? 'checked' : '' }}
                                    class="rounded border-gray-300 text-indigo-600 shadow-sm focus:border-indigo-500 focus:ring-indigo-500">
                                <span class="ml-2 text-sm text-gray-600">Tampilkan di slideshow</span>
                            </label>
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
            const slideShowCheckbox = document.getElementById('slide_show');
            const editForm = document.getElementById('editForm');
            
            // Simpan nilai awal slide_show dari database sebagai konstanta
            const ORIGINAL_SLIDE_SHOW_STATE = {{ $media->slide_show ? 'true' : 'false' }};
            
            // Set checkbox ke nilai awal dari database
            slideShowCheckbox.checked = ORIGINAL_SLIDE_SHOW_STATE;
            
            editForm && editForm.addEventListener('submit', function(e) {
                e.preventDefault();
                
                const formData = new FormData(this);
                formData.append('_method', 'PUT');
                
                // Gunakan nilai awal jika checkbox tidak diubah
                const slideShowValue = slideShowCheckbox.checked === ORIGINAL_SLIDE_SHOW_STATE 
                    ? (ORIGINAL_SLIDE_SHOW_STATE ? '1' : '0')
                    : (slideShowCheckbox.checked ? '1' : '0');
                formData.append('slide_show', slideShowValue);
                
                axios.post('/api/media/{{ $media->id }}', formData, {
                    headers: {
                        'Content-Type': 'multipart/form-data',
                        'X-CSRF-TOKEN': document.querySelector('meta[name="csrf-token"]').content,
                        'Authorization': 'Bearer ' + document.querySelector('meta[name="api-token"]').content
                    }
                })
                .then(response => {
                    const data = response.data;
                    if (data.success) {
                        showSuccess('Media berhasil diperbarui');
                        setTimeout(() => {
                            window.location.href = '{{ route("admin.media.index") }}';
                        }, 1000);
                    }
                })
                .catch(({ response }) => {
                    if (response && response.data && response.data.errors) {
                        const errors = response.data.errors;
                        Object.keys(errors).forEach(key => {
                            const errorElement = document.getElementById(`${key}Error`);
                            if (errorElement) {
                                errorElement.textContent = errors[key][0];
                            }
                        });
                    } else {
                        showError('Terjadi kesalahan saat menyimpan media');
                    }
                    // Kembalikan ke nilai awal jika terjadi error
                    slideShowCheckbox.checked = ORIGINAL_SLIDE_SHOW_STATE;
                });
            });

            // Reset handler untuk mengembalikan ke nilai awal
            editForm.addEventListener('reset', function() {
                slideShowCheckbox.checked = ORIGINAL_SLIDE_SHOW_STATE;
            });
        });

        function showSuccess(message) {
            const alert = document.getElementById('alertSuccess');
            const alertMessage = document.getElementById('alertSuccessMessage');
            if (alert && alertMessage) {
                alertMessage.textContent = message;
                alert.classList.remove('hidden');
                setTimeout(() => {
                    alert.classList.add('hidden');
                }, 3000);
            }
        }

        function showError(message) {
            const alert = document.getElementById('alertError');
            const alertMessage = document.getElementById('alertErrorMessage');
            if (alert && alertMessage) {
                alertMessage.textContent = message;
                alert.classList.remove('hidden');
                setTimeout(() => {
                    alert.classList.add('hidden');
                }, 3000);
            }
        }
    </script>
    @endpush
</x-app-layout> 