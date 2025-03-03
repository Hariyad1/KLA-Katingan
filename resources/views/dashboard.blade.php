<x-app-layout>
    <x-slot name="header">
        <h2 class="font-semibold text-xl text-gray-800 leading-tight">
            {{ __('Dashboard') }}
        </h2>
    </x-slot>

    <div class="flex">
        <!-- Main Content -->
        <main class="flex-1 ml-60">
            <div class="py-12">
                <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8">
                    <!-- Statistics Cards -->
                    <div class="grid grid-cols-1 md:grid-cols-3 gap-6 mb-6">
                        <div class="bg-white rounded-xl shadow p-6">
                            <div class="flex justify-between items-center">
                                <h3 class="text-gray-900 font-semibold">C</h3>
                                <span class="text-green-500">+1</span>
                            </div>
                            <p class="text-3xl font-bold mt-4">3</p>
                        </div>

                        <div class="bg-white rounded-xl shadow p-6">
                            <div class="flex justify-between items-center">
                                <h3 class="text-gray-900 font-semibold">O</h3>
                                <span class="text-red-500">-9</span>
                            </div>
                            <p class="text-3xl font-bold mt-4">5</p>
                        </div>

                        <div class="bg-white rounded-xl shadow p-6">
                            <div class="flex justify-between items-center">
                                <h3 class="text-gray-900 font-semibold">M</h3>
                                <span class="text-green-500">+10</span>
                            </div>
                            <p class="text-3xl font-bold mt-4">75</p>
                        </div>
                    </div>

                    <!-- Monthly Sales Chart -->
                    <div class="bg-white rounded-xl shadow p-6">
                        <h3 class="text-xl font-semibold mb-6">M1</h3>
                        <div class="h-80">
                            <!-- Di sini Anda bisa menambahkan chart menggunakan library seperti Chart.js -->
                            <div class="text-gray-500">C1</div>
                        </div>
                    </div>
                </div>
            </div>
        </main>
    </div>
</x-app-layout>
