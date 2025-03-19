<x-app-layout>
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/@sweetalert2/theme-material-ui/material-ui.css">
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/notyf@3/notyf.min.css">
    <script src="https://cdn.jsdelivr.net/npm/notyf@3/notyf.min.js"></script>
    <!-- Tambahkan header -->
    <x-slot name="header">
        <div class="flex justify-between items-center">
            <h2 class="font-semibold text-xl text-gray-800 leading-tight">
                {{ __('Manajemen Pengguna') }}
            </h2>
            <a href="{{ route('admin.users.create') }}" class="bg-indigo-600 hover:bg-indigo-700 text-white font-bold py-2 px-4 rounded inline-flex items-center">
                <svg class="w-4 h-4 mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 6v6m0 0v6m0-6h6m-6 0H6"></path>
                </svg>
                <span>Tambah Pengguna</span>
            </a>
        </div>
    </x-slot>

    <!-- Hapus ml-60 -->
    <div class="py-12">
        <div class="max-w-7xl mx-auto sm:px-6 lg:px-8">
            <div class="bg-white overflow-hidden shadow-sm sm:rounded-lg">
                <div class="p-6">
                    <!-- Search dan Show Entries -->
                    <div class="flex justify-between items-center mb-4">
                        <div class="flex items-center space-x-2">
                            <span>Show</span>
                            <select id="entriesPerPage" class="border rounded px-2 py-1 w-20" onchange="loadUsersData()">
                                <option value="10">10</option>
                                <option value="25">25</option>
                                <option value="50">50</option>
                                <option value="100">100</option>
                            </select>
                            <span>entries</span>
                        </div>
                        <div class="flex items-center">
                            <span class="mr-2">Search:</span>
                            <input type="text" id="searchInput" class="border rounded px-3 py-1" onkeyup="loadUsersData()" placeholder="Search...">
                        </div>
                    </div>

                    <!-- Alert Messages -->
                    @if(session('success'))
                        <div class="mb-4 p-4 bg-green-100 border border-green-400 text-green-700 rounded">
                            <p>{{ session('success') }}</p>
                        </div>
                    @endif

                    <!-- Tabel Pengguna -->
                    <div class="overflow-x-auto">
                        <table class="min-w-full divide-y divide-gray-200">
                            <thead class="bg-gray-50">
                                <tr>
                                    <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">No</th>
                                    <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Nama</th>
                                    <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Email</th>
                                    <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Status</th>
                                    <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Aksi</th>
                                </tr>
                            </thead>
                            <tbody class="bg-white divide-y divide-gray-200">
                                @foreach($users as $user)
                                    <tr>
                                        <td class="px-6 py-4 whitespace-nowrap">{{ $loop->iteration }}</td>
                                        <td class="px-6 py-4">{{ $user->name }}</td>
                                        <td class="px-6 py-4">{{ $user->email }}</td>
                                        <td class="px-6 py-4">
                                            <span class="px-2 inline-flex text-xs leading-5 font-semibold rounded-full {{ $user->status == 1 ? 'bg-green-100 text-green-800' : 'bg-blue-100 text-blue-800' }}">
                                                {{ $user->status == 1 ? 'Admin' : 'User' }}
                                            </span>
                                        </td>
                                        <td class="px-6 py-4 whitespace-nowrap text-sm font-medium flex space-x-3">
                                            <a href="{{ route('admin.users.edit', $user->id) }}" class="text-blue-600 hover:text-blue-900">
                                                <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M11 5H6a2 2 0 00-2 2v11a2 2 0 002 2h11a2 2 0 002-2v-5m-1.414-9.414a2 2 0 112.828 2.828L11.828 15H9v-2.828l8.586-8.586z"/>
                                                </svg>
                                            </a>
                                            @if($user->id != auth()->id() && $user->status != 1)
                                                <button onclick="deleteUser({{ $user->id }})" class="text-red-600 hover:text-red-900">
                                                    <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 7l-.867 12.142A2 2 0 0116.138 21H7.862a2 2 0 01-1.995-1.858L5 7m5 4v6m4-6v6m1-10V4a1 1 0 00-1-1h-4a1 1 0 00-1 1v3M4 7h16"/>
                                                    </svg>
                                                </button>
                                            @endif
                                        </td>
                                    </tr>
                                @endforeach
                            </tbody>
                        </table>
                    </div>

                    <!-- Pagination -->
                    <div class="flex justify-between items-center mt-4">
                        <div id="tableInfo" class="text-sm text-gray-700">
                            Showing <span id="startEntry">1</span> to <span id="endEntry">10</span> of <span id="totalEntries">0</span> entries
                        </div>
                        <div class="flex items-center space-x-2">
                            <button id="prevBtn" onclick="previousPage()" class="px-3 py-1 border rounded hover:bg-gray-100 disabled:opacity-50 disabled:cursor-not-allowed">Previous</button>
                            <div class="flex items-center space-x-1">
                                <span>Page</span>
                                <span id="currentPageDisplay">1</span>
                                <span>of</span>
                                <span id="totalPagesDisplay">1</span>
                            </div>
                            <button id="nextBtn" onclick="nextPage()" class="px-3 py-1 border rounded hover:bg-gray-100 disabled:opacity-50 disabled:cursor-not-allowed">Next</button>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>

    @push('styles')
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/@sweetalert2/theme-material-ui/material-ui.css">
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/notyf@3/notyf.min.css">
    @endpush

    @push('scripts')
    <script src="https://cdn.jsdelivr.net/npm/sweetalert2@11"></script>
    <script src="https://cdn.jsdelivr.net/npm/axios/dist/axios.min.js"></script>
    <script src="https://cdn.jsdelivr.net/npm/notyf@3/notyf.min.js"></script>
    <script>
        // Inisialisasi Notyf untuk toast notifications
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

        function deleteUser(id) {
            if (id == {{ auth()->id() }}) {
                notyf.error('Anda tidak dapat menghapus akun Anda sendiri');
                return;
            }
            
            // Gunakan SweetAlert2 untuk konfirmasi
            Swal.fire({
                title: 'Apakah Anda yakin?',
                text: "Data pengguna yang dihapus tidak dapat dikembalikan!",
                icon: 'warning',
                showCancelButton: true,
                confirmButtonColor: '#3085d6',
                cancelButtonColor: '#d33',
                confirmButtonText: '<span style="color: white;">Ya, hapus!</span>',
                cancelButtonText: '<span style="color: white;">Batal</span>',
                reverseButtons: true
            }).then((result) => {
                if (result.isConfirmed) {
                    const config = {
                        headers: {
                            'Content-Type': 'application/json',
                            'X-CSRF-TOKEN': '{{ csrf_token() }}',
                            'Accept': 'application/json',
                            'Authorization': 'Bearer {{ session("api_token") }}'
                        }
                    };
                    
                    axios.delete(`/api/users/${id}`, config)
                        .then(response => {
                            if (response.data.success) {
                                notyf.success('Pengguna berhasil dihapus');
                                setTimeout(() => {
                                    window.location.reload();
                                }, 1500);
                            }
                        })
                        .catch(error => {
                            notyf.error(error.response?.data?.message || 'Terjadi kesalahan saat menghapus pengguna');
                            console.error('Error:', error);
                        });
                }
            });
        }

        // Tampilkan pesan sukses/error dengan Notyf
        @if(session('success'))
            notyf.success('{{ session("success") }}');
        @endif

        @if(session('error'))
            notyf.error('{{ session("error") }}');
        @endif

        let currentPage = 1;
        let totalPages = 1;
        let filteredData = [];
        let allUsers = @json($users);

        function loadUsersData() {
            const perPage = document.getElementById('entriesPerPage').value;
            const searchQuery = document.getElementById('searchInput').value.toLowerCase();

            // Filter data berdasarkan pencarian
            filteredData = allUsers.filter(user => 
                user.name.toLowerCase().includes(searchQuery) ||
                user.email.toLowerCase().includes(searchQuery)
            );

            // Hitung total halaman
            totalPages = Math.ceil(filteredData.length / perPage);
            
            // Pastikan halaman saat ini valid
            if (currentPage > totalPages) {
                currentPage = totalPages || 1;
            }

            // Update tampilan nomor halaman
            document.getElementById('currentPageDisplay').textContent = currentPage;
            document.getElementById('totalPagesDisplay').textContent = totalPages;

            // Hitung data untuk halaman saat ini
            const startIndex = (currentPage - 1) * perPage;
            const endIndex = Math.min(startIndex + parseInt(perPage), filteredData.length);
            const currentPageData = filteredData.slice(startIndex, endIndex);

            // Update tampilan tabel
            updateTable(currentPageData, startIndex);
            
            // Update informasi tabel
            document.getElementById('startEntry').textContent = filteredData.length ? startIndex + 1 : 0;
            document.getElementById('endEntry').textContent = endIndex;
            document.getElementById('totalEntries').textContent = filteredData.length;
            
            // Update status tombol pagination
            document.getElementById('prevBtn').disabled = currentPage === 1;
            document.getElementById('nextBtn').disabled = currentPage === totalPages;
        }

        function updateTable(users, startIndex) {
            const tbody = document.querySelector('tbody');
            tbody.innerHTML = '';

            if (users.length === 0) {
                tbody.innerHTML = `
                    <tr>
                        <td colspan="6" class="px-6 py-4 text-center text-sm text-gray-500">
                            Tidak ada pengguna yang tersedia
                        </td>
                    </tr>
                `;
                return;
            }

            users.forEach((user, index) => {
                const row = `
                    <tr>
                        <td class="px-6 py-4 whitespace-nowrap">${startIndex + index + 1}</td>
                        <td class="px-6 py-4">${user.name}</td>
                        <td class="px-6 py-4">${user.email}</td>
                        <td class="px-6 py-4">
                            <span class="px-2 inline-flex text-xs leading-5 font-semibold rounded-full ${user.status == 1 ? 'bg-green-100 text-green-800' : 'bg-blue-100 text-blue-800'}">
                                ${user.status == 1 ? 'Admin' : 'User'}
                            </span>
                        </td>
                        <td class="px-6 py-4 whitespace-nowrap text-sm font-medium flex space-x-3">
                            <a href="/manage/users/${user.id}/edit" class="text-blue-600 hover:text-blue-900">
                                <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M11 5H6a2 2 0 00-2 2v11a2 2 0 002 2h11a2 2 0 002-2v-5m-1.414-9.414a2 2 0 112.828 2.828L11.828 15H9v-2.828l8.586-8.586z"/>
                                </svg>
                            </a>
                            ${user.id != {{ auth()->id() }} && user.status != 1 ? `
                                <button onclick="deleteUser(${user.id})" class="text-red-600 hover:text-red-900">
                                    <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 7l-.867 12.142A2 2 0 0116.138 21H7.862a2 2 0 01-1.995-1.858L5 7m5 4v6m4-6v6m1-10V4a1 1 0 00-1-1h-4a1 1 0 00-1 1v3M4 7h16"/>
                                    </svg>
                                </button>
                            ` : ''}
                        </td>
                    </tr>
                `;
                tbody.insertAdjacentHTML('beforeend', row);
            });
        }

        function formatDate(dateString) {
            const date = new Date(dateString);
            return date.toLocaleDateString('id-ID', {
                day: '2-digit',
                month: 'short',
                year: 'numeric',
                hour: '2-digit',
                minute: '2-digit'
            });
        }

        function previousPage() {
            if (currentPage > 1) {
                currentPage--;
                loadUsersData();
            }
        }

        function nextPage() {
            if (currentPage < totalPages) {
                currentPage++;
                loadUsersData();
            }
        }

        // Load data saat halaman dimuat
        document.addEventListener('DOMContentLoaded', loadUsersData);
    </script>
    @endpush
</x-app-layout> 