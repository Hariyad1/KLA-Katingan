<x-app-layout>
    <x-slot name="header">
        <div class="flex justify-between items-center">
            <h2 class="font-semibold text-xl text-gray-800 leading-tight">
                {{ __('Edit Agenda') }}
            </h2>
            <a href="{{ route('admin.agenda.index') }}" class="bg-gray-300 hover:bg-gray-400 text-gray-800 font-bold py-2 px-4 rounded inline-flex items-center">
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

                    <!-- Form Edit -->
                    <form id="editForm">
                        <input type="hidden" id="agendaId" value="{{ $id }}">
                        
                        <div class="mb-4">
                            <label for="title" class="block text-sm font-medium text-gray-700">Judul</label>
                            <input type="text" id="title" name="title" class="mt-1 block w-full rounded-md border-gray-300 shadow-sm focus:border-indigo-500 focus:ring-indigo-500">
                            <p class="text-red-500 text-xs mt-1 hidden" id="titleError"></p>
                        </div>
                        
                        <div class="mb-4">
                            <label for="tanggal" class="block text-sm font-medium text-gray-700">Tanggal</label>
                            <input type="date" id="tanggal" name="tanggal" class="mt-1 block w-full rounded-md border-gray-300 shadow-sm focus:border-indigo-500 focus:ring-indigo-500">
                            <p class="text-red-500 text-xs mt-1 hidden" id="tanggalError"></p>
                        </div>
                        
                        <div class="mb-4">
                            <label for="keterangan" class="block text-sm font-medium text-gray-700">Keterangan</label>
                            <textarea id="keterangan" name="keterangan" rows="3" class="mt-1 block w-full rounded-md border-gray-300 shadow-sm focus:border-indigo-500 focus:ring-indigo-500"></textarea>
                            <p class="text-red-500 text-xs mt-1 hidden" id="keteranganError"></p>
                        </div>
                        
                        <div class="flex justify-end">
                            <a href="{{ route('admin.agenda.index') }}" class="bg-gray-200 text-gray-700 px-4 py-2 rounded mr-2">Batal</a>
                            <button type="button" onclick="updateAgenda()" class="bg-indigo-600 text-white px-4 py-2 rounded">Simpan</button>
                        </div>
                    </form>
                </div>
            </div>
        </div>
    </div>

    @push('scripts')
    <script>
        document.addEventListener('DOMContentLoaded', function() {
            loadAgendaData();
        });

        function loadAgendaData() {
            const id = document.getElementById('agendaId').value;
            
            // Tampilkan loading
            document.getElementById('loadingIndicator').classList.remove('hidden');
            
            // Konfigurasi axios
            const config = {
                headers: {
                    'Content-Type': 'application/json',
                    'Accept': 'application/json',
                    'Authorization': 'Bearer {{ session("api_token") }}'
                }
            };
            
            // Ambil data agenda
            axios.get(`/api/agenda/${id}`, config)
                .then(function(response) {
                    if (response.data.success) {
                        const agenda = response.data.data;
                        console.log('Data agenda:', agenda); // Tambahkan log untuk debugging
                        
                        // Isi form dengan data agenda
                        document.getElementById('title').value = agenda.title || '';
                        
                        // Format tanggal
                        if (agenda.tanggal) {
                            let tanggal = agenda.tanggal;
                            // Konversi ISO date string ke format YYYY-MM-DD
                            try {
                                const date = new Date(tanggal);
                                const year = date.getFullYear();
                                const month = String(date.getMonth() + 1).padStart(2, '0');
                                const day = String(date.getDate()).padStart(2, '0');
                                tanggal = `${year}-${month}-${day}`;
                                console.log('Formatted date:', tanggal);
                                document.getElementById('tanggal').value = tanggal;
                            } catch (e) {
                                console.error('Error formatting date:', e);
                            }
                        }
                        
                        document.getElementById('keterangan').value = agenda.keterangan || '';
                    } else {
                        showError('Gagal memuat data agenda');
                    }
                })
                .catch(function(error) {
                    console.error('Error:', error);
                    showError('Terjadi kesalahan saat memuat data agenda');
                })
                .finally(function() {
                    document.getElementById('loadingIndicator').classList.add('hidden');
                });
        }

        function updateAgenda() {
            const id = document.getElementById('agendaId').value;
            
            // Tampilkan loading
            document.getElementById('loadingIndicator').classList.remove('hidden');
            
            // Reset error messages
            document.querySelectorAll('.text-red-500').forEach(el => {
                el.textContent = '';
                el.classList.add('hidden');
            });
            
            const formData = {
                title: document.getElementById('title').value,
                tanggal: document.getElementById('tanggal').value,
                keterangan: document.getElementById('keterangan').value
            };
            
            // Konfigurasi axios
            const config = {
                headers: {
                    'Content-Type': 'application/json',
                    'Accept': 'application/json',
                    'Authorization': 'Bearer {{ session("api_token") }}'
                }
            };
            
            // Kirim request update
            axios.put(`/api/agenda/${id}`, formData, config)
                .then(function(response) {
                    if (response.data.success) {
                        showSuccess('Agenda berhasil diperbarui');
                        // Redirect ke halaman index setelah 1 detik
                        setTimeout(() => {
                            window.location.href = '{{ route("admin.agenda.index") }}';
                        }, 1000);
                    } else {
                        showError('Gagal memperbarui agenda');
                    }
                })
                .catch(function(error) {
                    console.error('Error:', error);
                    
                    // Tampilkan error validasi
                    if (error.response && error.response.data && error.response.data.errors) {
                        const errors = error.response.data.errors;
                        Object.keys(errors).forEach(field => {
                            const errorElement = document.getElementById(`${field}Error`);
                            if (errorElement) {
                                errorElement.textContent = errors[field][0];
                                errorElement.classList.remove('hidden');
                            }
                        });
                    } else {
                        showError('Terjadi kesalahan saat memperbarui agenda');
                    }
                })
                .finally(function() {
                    document.getElementById('loadingIndicator').classList.add('hidden');
                });
        }

        function showSuccess(message) {
            const alert = document.getElementById('alertSuccess');
            const alertMessage = document.getElementById('alertSuccessMessage');
            alertMessage.textContent = message;
            alert.classList.remove('hidden');
        }
        
        function showError(message) {
            const alert = document.getElementById('alertError');
            const alertMessage = document.getElementById('alertErrorMessage');
            alertMessage.textContent = message;
            alert.classList.remove('hidden');
        }
    </script>
    @endpush
</x-app-layout> 