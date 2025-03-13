<x-app-layout>
    <div class="pl-4 py-12">
        <div class="max-w-7xl mx-auto sm:px-6 lg:px-8">
            <div class="bg-white overflow-hidden shadow-sm sm:rounded-lg">
                <div class="p-6">
                    <div class="mb-6">
                        <h2 class="text-2xl font-semibold text-gray-800">Edit Pengguna</h2>
                    </div>

                    <form id="editUserForm">
                        <div class="mb-4">
                            <label for="name" class="block text-sm font-medium text-gray-700">Nama</label>
                            <input type="text" name="name" id="name" value="{{ $user->name }}" class="mt-1 block w-full rounded-md border-gray-300 shadow-sm focus:border-indigo-500 focus:ring-indigo-500" required>
                            <p class="text-red-500 text-xs mt-1 error-name"></p>
                        </div>
                        
                        <div class="mb-4">
                            <label for="email" class="block text-sm font-medium text-gray-700">Email</label>
                            <input type="email" name="email" id="email" value="{{ $user->email }}" class="mt-1 block w-full rounded-md border-gray-300 shadow-sm focus:border-indigo-500 focus:ring-indigo-500" required>
                            <p class="text-red-500 text-xs mt-1 error-email"></p>
                        </div>
                        
                        <div class="mb-4">
                            <label for="password" class="block text-sm font-medium text-gray-700">Password (kosongkan jika tidak ingin mengubah)</label>
                            <input type="password" name="password" id="password" class="mt-1 block w-full rounded-md border-gray-300 shadow-sm focus:border-indigo-500 focus:ring-indigo-500">
                            <p class="text-red-500 text-xs mt-1 error-password"></p>
                        </div>
                        
                        <div class="mb-4">
                            <label for="password_confirmation" class="block text-sm font-medium text-gray-700">Konfirmasi Password</label>
                            <input type="password" name="password_confirmation" id="password_confirmation" class="mt-1 block w-full rounded-md border-gray-300 shadow-sm focus:border-indigo-500 focus:ring-indigo-500">
                        </div>
                        
                        <div class="mb-4">
                            <label for="status" class="block text-sm font-medium text-gray-700">Status</label>
                            <select name="status" id="status" class="mt-1 block w-full rounded-md border-gray-300 shadow-sm focus:border-indigo-500 focus:ring-indigo-500">
                                <option value="0" {{ $user->status == 0 ? 'selected' : '' }}>User</option>
                                <option value="1" {{ $user->status == 1 ? 'selected' : '' }}>Admin</option>
                            </select>
                            <p class="text-red-500 text-xs mt-1 error-status"></p>
                        </div>
                        
                        <div class="flex items-center justify-end mt-6">
                            <a href="{{ route('admin.users.index') }}" class="bg-gray-200 hover:bg-gray-300 text-gray-800 font-bold py-2 px-4 rounded mr-2">
                                Batal
                            </a>
                            <button type="submit" class="bg-indigo-600 hover:bg-indigo-700 text-white font-bold py-2 px-4 rounded">
                                Perbarui
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
        document.getElementById('editUserForm').addEventListener('submit', function(e) {
            e.preventDefault();
            
            // Reset error messages
            document.querySelectorAll('.text-red-500').forEach(el => el.textContent = '');
            
            // Get form data
            const formData = {
                name: document.getElementById('name').value,
                email: document.getElementById('email').value,
                status: document.getElementById('status').value
            };
            
            // Add password if provided
            const password = document.getElementById('password').value;
            if (password) {
                formData.password = password;
                formData.password_confirmation = document.getElementById('password_confirmation').value;
            }
            
            // Konfigurasi axios
            const config = {
                headers: {
                    'Content-Type': 'application/json',
                    'X-CSRF-TOKEN': '{{ csrf_token() }}',
                    'Accept': 'application/json',
                    'Authorization': 'Bearer {{ session("api_token") }}'
                }
            };
            
            // Send API request dengan axios
            axios.put('/api/users/{{ $user->id }}', formData, config)
                .then(response => {
                    const data = response.data;
                    if (data.success) {
                        window.location.href = "{{ route('admin.users.index') }}";
                    } else {
                        alert('Terjadi kesalahan saat memperbarui data: ' + (data.message || 'Unknown error'));
                    }
                })
                .catch(error => {
                    console.error('Error:', error);
                    
                    if (error.response && error.response.data && error.response.data.errors) {
                        // Display validation errors
                        const errors = error.response.data.errors;
                        Object.keys(errors).forEach(key => {
                            const errorElement = document.querySelector(`.error-${key}`);
                            if (errorElement) {
                                errorElement.textContent = errors[key][0];
                            }
                        });
                    } else {
                        alert('Terjadi kesalahan saat memperbarui data: ' + 
                            (error.response && error.response.data && error.response.data.message 
                                ? error.response.data.message 
                                : error.message || 'Unknown error'));
                    }
                });
        });
    </script>
    @endpush
</x-app-layout> 