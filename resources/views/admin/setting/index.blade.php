<x-app-layout>
    <div class="ml-60 py-12">
        <div class="max-w-7xl mx-auto sm:px-6 lg:px-8">
            <div class="bg-white overflow-hidden shadow-sm sm:rounded-lg">
                <div class="p-6">
                    <div class="flex justify-between items-center mb-6">
                        <h2 class="text-2xl font-semibold text-gray-800">List Setting</h2>
                        <button onclick="openCreateModal()" class="px-4 py-2 bg-indigo-600 text-white rounded-lg hover:bg-indigo-700">
                            Tambah Data
                        </button>
                    </div>

                    <!-- Action Buttons -->
                    <div class="mb-4 flex gap-2">
                        <button class="px-3 py-1 bg-gray-100 hover:bg-gray-200 rounded">Copy</button>
                        <button class="px-3 py-1 bg-gray-100 hover:bg-gray-200 rounded">CSV</button>
                        <button class="px-3 py-1 bg-gray-100 hover:bg-gray-200 rounded">Excel</button>
                        <button class="px-3 py-1 bg-gray-100 hover:bg-gray-200 rounded">PDF</button>
                        <button class="px-3 py-1 bg-gray-100 hover:bg-gray-200 rounded">Print</button>
                    </div>

                    <!-- Search -->
                    <div class="mb-4 flex justify-end">
                        <div class="flex items-center">
                            <label class="mr-2">Search:</label>
                            <input type="text" class="border rounded px-2 py-1">
                        </div>
                    </div>

                    <!-- Table -->
                    <div class="overflow-x-auto">
                        <table class="min-w-full divide-y divide-gray-200">
                            <thead class="bg-gray-50">
                                <tr>
                                    <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">#</th>
                                    <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Nama</th>
                                    <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Halaman</th>
                                    <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Isi</th>
                                    <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">URL</th>
                                    <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Gambar</th>
                                    <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Tipe</th>
                                    <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Tanggal</th>
                                    <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Aksi</th>
                                </tr>
                            </thead>
                            <tbody class="bg-white divide-y divide-gray-200">
                                @foreach($settings as $setting)
                                    <tr>
                                        <td class="px-6 py-4 whitespace-nowrap">{{ $loop->iteration }}</td>
                                        <td class="px-6 py-4">{{ $setting->name }}</td>
                                        <td class="px-6 py-4">{{ $setting->page }}</td>
                                        <td class="px-6 py-4">{{ Str::limit($setting->content, 50) }}</td>
                                        <td class="px-6 py-4">{{ $setting->url }}</td>
                                        <td class="px-6 py-4">{{ $setting->image }}</td>
                                        <td class="px-6 py-4">{{ $setting->type }}</td>
                                        <td class="px-6 py-4">{{ $setting->created_at->format('Y-m-d H:i:s') }}</td>
                                        <td class="px-6 py-4 whitespace-nowrap text-sm font-medium">
                                            <button onclick="editSetting({{ $setting->id }})" class="text-white bg-green-500 px-2 py-1 rounded hover:bg-green-600">Ubah</button>
                                            <button onclick="deleteSetting({{ $setting->id }})" class="text-white bg-red-500 px-2 py-1 rounded hover:bg-red-600">Hapus</button>
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

    @push('scripts')
    <script>
        function editSetting(id) {
            window.location.href = `/admin/setting/${id}/edit`;
        }

        function deleteSetting(id) {
            if (confirm('Apakah Anda yakin ingin menghapus setting ini?')) {
                fetch(`/api/setting/${id}`, {
                    method: 'DELETE',
                    headers: {
                        'X-CSRF-TOKEN': document.querySelector('meta[name="csrf-token"]').content
                    }
                })
                .then(response => response.json())
                .then(data => {
                    if (data.success) {
                        window.location.reload();
                    }
                });
            }
        }
    </script>
    @endpush
</x-app-layout> 