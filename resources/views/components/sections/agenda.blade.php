@props(['upcomingAgendas'])

<div class="w-full lg:w-1/3">
    <div class="bg-gradient-to-br from-emerald-50 to-teal-100 p-6 shadow-lg rounded-lg mt-8">
        <h2 class="text-2xl font-bold text-emerald-800 mb-6 relative">
            <span class="relative inline-block">
                <span class="absolute -left-2 -right-2 bottom-2 h-3 bg-emerald-200/60"></span>
                <span class="relative">Agenda</span>
            </span>
        </h2>
        <div class="grid grid-cols-1 gap-4">
            @forelse($upcomingAgendas as $item)
                @php
                    $isActive = \Carbon\Carbon::parse($item->tanggal)->gte(\Carbon\Carbon::now()->subDay());
                @endphp
                @if($isActive)
                    <div class="flex gap-4 p-4 bg-white/80 rounded-xl hover:shadow-lg transition-all duration-300">
                        <div class="w-20 flex-shrink-0">
                            <div class="bg-gradient-to-br from-emerald-100 to-teal-200 rounded-lg p-3 text-center shadow-md">
                                <span class="text-xl font-bold block text-emerald-700">
                                    {{ \Carbon\Carbon::parse($item->tanggal)->format('d') }}
                                </span>
                                <span class="text-sm text-emerald-600 block">
                                    {{ \Carbon\Carbon::parse($item->tanggal)->isoFormat('MMM') }}
                                </span>
                            </div>
                        </div>
                        <div class="flex-1">
                            <h3 class="text-lg font-semibold text-emerald-700">{{ $item->title }}</h3>
                            <p class="text-sm text-emerald-600">
                                {{ \Carbon\Carbon::parse($item->tanggal)->isoFormat('dddd, D MMMM Y') }}
                            </p>
                            <p class="text-sm mt-1 text-gray-600">{{ $item->keterangan }}</p>
                        </div>
                    </div>
                @endif
            @empty
                <div class="text-center py-8">
                    <div class="mb-4">
                        <svg class="mx-auto h-12 w-12 text-gray-400" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M8 7V3m8 4V3m-9 8h10M5 21h14a2 2 0 002-2V7a2 2 0 00-2-2H5a2 2 0 00-2 2v12a2 2 0 002 2z"/>
                        </svg>
                    </div>
                    <h3 class="text-lg font-medium text-gray-900 mb-1">Tidak ada agenda</h3>
                    <p class="text-gray-500">Belum ada agenda yang akan datang saat ini</p>
                </div>
            @endforelse
        </div>
    </div>
</div> 