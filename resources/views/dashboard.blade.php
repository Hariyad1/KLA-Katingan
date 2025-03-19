<x-app-layout>
    <x-slot name="header">
        <div class="flex justify-between items-center">
            <h2 class="font-semibold text-xl text-gray-800 leading-tight">
                {{ __('Dashboard') }}
            </h2>
        </div>
    </x-slot>

    <div class="py-12">
        <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8">
            <!-- Statistics Cards -->
            <div class="grid grid-cols-1 md:grid-cols-3 gap-6 mb-6">
                <div class="bg-white rounded-xl shadow p-6">
                    <div class="flex justify-between items-center">
                        <h3 class="text-gray-900 font-semibold">Total Berita</h3>
                        <span class="text-green-500">
                            +{{ \App\Models\News::whereDate('created_at', today())->count() }}
                        </span>
                    </div>
                    <p class="text-3xl font-bold mt-4">{{ \App\Models\News::count() }}</p>
                </div>

                <div class="bg-white rounded-xl shadow p-6">
                    <div class="flex justify-between items-center">
                        <h3 class="text-gray-900 font-semibold">Total Agenda</h3>
                        <span class="text-{{ \App\Models\Agenda::whereDate('created_at', today())->count() > 0 ? 'green' : 'red' }}-500">
                            {{ \App\Models\Agenda::whereDate('created_at', today())->count() > 0 ? '+' : '' }}
                            {{ \App\Models\Agenda::whereDate('created_at', today())->count() }}
                        </span>
                    </div>
                    <p class="text-3xl font-bold mt-4">{{ \App\Models\Agenda::count() }}</p>
                </div>

                <div class="bg-white rounded-xl shadow p-6">
                    <div class="flex justify-between items-center">
                        <h3 class="text-gray-900 font-semibold">Total Pengunjung</h3>
                        <span class="text-green-500">
                            +{{ \App\Models\Statistic::whereDate('created_at', today())->count() }}
                        </span>
                    </div>
                    <p class="text-3xl font-bold mt-4">{{ \App\Models\Statistic::count() }}</p>
                </div>
            </div>

            <!-- Monthly Chart -->
            <div class="bg-white rounded-xl shadow p-6">
                <h3 class="text-xl font-semibold mb-6">Statistik Bulanan</h3>
                <div class="h-80">
                    <!-- Di sini Anda bisa menambahkan chart menggunakan library seperti Chart.js -->
                    <div class="text-gray-500">Chart akan ditampilkan di sini</div>
                </div>
            </div>
        </div>
    </div>
</x-app-layout>
