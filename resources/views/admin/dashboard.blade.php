{{-- <x-app-layout>
    <x-slot name="header">
        <h2 class="font-semibold text-xl text-gray-800 leading-tight">
            {{ __('Dashboard') }}
        </h2>
    </x-slot>

    <div class="py-12">
        <div class="max-w-7xl mx-auto sm:px-6 lg:px-8">
            <!-- Statistik Pengunjung -->
            <div class="bg-white overflow-hidden shadow-sm sm:rounded-lg mb-6">
                <div class="p-6">
                    <h3 class="text-lg font-medium mb-4">Statistik Pengunjung</h3>
                    <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-4 gap-4">
                        <!-- Total Pengunjung -->
                        <div class="bg-blue-50 p-4 rounded-lg">
                            <h4 class="text-sm font-medium text-blue-600">Total Pengunjung</h4>
                            <p class="text-2xl font-bold text-blue-800">{{ \App\Models\Statistic::count() }}</p>
                        </div>
                        
                        <!-- Pengunjung Hari Ini -->
                        <div class="bg-green-50 p-4 rounded-lg">
                            <h4 class="text-sm font-medium text-green-600">Pengunjung Hari Ini</h4>
                            <p class="text-2xl font-bold text-green-800">
                                {{ \App\Models\Statistic::whereDate('created_at', today())->count() }}
                            </p>
                        </div>
                    </div>
                </div>
            </div>

            <div class="bg-white overflow-hidden shadow-sm sm:rounded-lg">
                <div class="p-6 text-gray-900">
                    <h3 class="text-lg font-medium mb-4">Welcome Admin!</h3>
                    
                    <div class="grid grid-cols-1 md:grid-cols-3 gap-4">
                        <!-- Statistik Card -->
                        <div class="bg-blue-100 p-4 rounded-lg">
                            <h4 class="font-medium">Total Users</h4>
                            <p class="text-2xl font-bold">{{ \App\Models\User::count() }}</p>
                        </div>
                        
                        <!-- News Card -->
                        <div class="bg-green-100 p-4 rounded-lg">
                            <h4 class="font-medium">Total News</h4>
                            <p class="text-2xl font-bold">{{ \App\Models\News::count() }}</p>
                        </div>
                        
                        <!-- Votes Card -->
                        <div class="bg-yellow-100 p-4 rounded-lg">
                            <h4 class="font-medium">Total Votes</h4>
                            <p class="text-2xl font-bold">{{ \App\Models\Vote::count() }}</p>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
</x-app-layout>  --}}