{{-- resources/views/livewire/admin/daily-booking-list.blade.php --}}

<div class="min-h-screen bg-gray-50">
    <!-- Header -->
    <div class="bg-white border-b border-gray-200">
        <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8 py-6">
            <div class="flex flex-col sm:flex-row sm:items-center sm:justify-between">
                <div>
                    <h1 class="text-2xl font-bold text-gray-900">每日預約清單</h1>
                    <p class="mt-1 text-sm text-gray-600">查看每日、每週或每月的預約排程</p>
                </div>
                
                <!-- View Mode Switcher -->
                <div class="mt-4 sm:mt-0">
                    <div class="inline-flex rounded-lg border border-gray-300 bg-white p-1">
                        <button wire:click="setViewMode('day')"
                                class="px-3 py-2 text-sm font-medium rounded-md transition-all {{ $viewMode === 'day' ? 'bg-pink-500 text-white shadow-sm' : 'text-gray-500 hover:text-gray-700' }}">
                            日
                        </button>
                        <button wire:click="setViewMode('week')"
                                class="px-3 py-2 text-sm font-medium rounded-md transition-all {{ $viewMode === 'week' ? 'bg-pink-500 text-white shadow-sm' : 'text-gray-500 hover:text-gray-700' }}">
                            週
                        </button>
                        <button wire:click="setViewMode('month')"
                                class="px-3 py-2 text-sm font-medium rounded-md transition-all {{ $viewMode === 'month' ? 'bg-pink-500 text-white shadow-sm' : 'text-gray-500 hover:text-gray-700' }}">
                            月
                        </button>
                    </div>
                </div>
            </div>
        </div>
    </div>

    <!-- Navigation Controls -->
    <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8 py-6">
        <div class="bg-white rounded-lg shadow p-6 mb-6 border border-gray-300">
            <div class="flex items-center justify-between">
                <button wire:click="previousPeriod" 
                        class="flex items-center px-4 py-2 bg-gray-100 text-gray-700 rounded-lg hover:bg-gray-200 transition-colors">
                    <svg class="w-4 h-4 mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 19l-7-7 7-7"/>
                    </svg>
                    上一{{ $viewMode === 'day' ? '天' : ($viewMode === 'week' ? '週' : '月') }}
                </button>

                <div class="text-center">
                    @if($viewMode === 'day')
                        <h2 class="text-xl font-semibold text-gray-900">
                            {{ \Carbon\Carbon::parse($selectedDate)->format('Y年m月d日') }}
                        </h2>
                        <p class="text-sm text-gray-500">
                            {{ \Carbon\Carbon::parse($selectedDate)->locale('zh_TW')->dayName }}
                        </p>
                    @elseif($viewMode === 'week')
                        @php
                            $weekStart = \Carbon\Carbon::parse($currentWeekStart);
                            $weekEnd = $weekStart->copy()->endOfWeek();
                        @endphp
                        <h2 class="text-xl font-semibold text-gray-900">
                            {{ $weekStart->format('m/d') }} - {{ $weekEnd->format('m/d') }}
                        </h2>
                        <p class="text-sm text-gray-500">
                            {{ $weekStart->format('Y年') }}
                        </p>
                    @else
                        <h2 class="text-xl font-semibold text-gray-900">
                            {{ \Carbon\Carbon::createFromFormat('Y-m', $currentMonth)->format('Y年m月') }}
                        </h2>
                    @endif
                </div>

                <button wire:click="nextPeriod"
                        class="flex items-center px-4 py-2 bg-gray-100 text-gray-700 rounded-lg hover:bg-gray-200 transition-colors">
                    下一{{ $viewMode === 'day' ? '天' : ($viewMode === 'week' ? '週' : '月') }}
                    <svg class="w-4 h-4 ml-2" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 5l7 7-7 7"/>
                    </svg>
                </button>
            </div>
        </div>

        <!-- Content Based on View Mode -->
        @if($viewMode === 'day')
            <!-- Day View -->
            <div class="bg-white rounded-lg shadow border border-gray-300">
                <div class="p-6">
                    <div class="flex items-center justify-between mb-4">
                        <h3 class="text-lg font-medium text-gray-900">
                            今日預約 ({{ $bookings->count() }}個)
                        </h3>
                        @if(\Carbon\Carbon::parse($selectedDate)->isToday())
                            <span class="inline-flex px-2 py-1 text-xs font-semibold rounded-full bg-pink-100 text-pink-800">
                                今天
                            </span>
                        @endif
                    </div>

                    @if($bookings->count() > 0)
                        <div class="space-y-4">
                            @foreach($bookings as $booking)
                                <div class="border border-gray-300 rounded-lg p-4 hover:shadow-md transition-shadow">
                                    <div class="flex flex-col sm:flex-row sm:items-center sm:justify-between">
                                        <div class="flex-1">
                                            <div class="flex items-center space-x-3">
                                                <div class="text-lg font-semibold text-gray-900">
                                                    {{ $booking->booking_time->format('H:i') }}
                                                </div>
                                                <div class="text-sm text-gray-600">
                                                    {{ $booking->customer_name }}
                                                </div>
                                                <span class="inline-flex px-2 py-1 text-xs font-semibold rounded-full 
                                                    {{ $booking->status === 'pending' ? 'bg-yellow-100 text-yellow-800' : 'bg-green-100 text-green-800' }}">
                                                    {{ $booking->status_text }}
                                                </span>
                                            </div>
                                            <div class="mt-1 text-sm text-gray-500">
                                                {{ $booking->style_type_text }}
                                                @if($booking->need_removal) • 需卸甲 @endif
                                                • {{ $booking->customer_phone }}
                                            </div>
                                            @if($booking->notes)
                                                <div class="mt-2 text-sm text-gray-600 bg-gray-50 rounded p-2">
                                                    {{ $booking->notes }}
                                                </div>
                                            @endif
                                        </div>
                                        
                                        <div class="mt-3 sm:mt-0 sm:ml-4">
                                            <a href="{{ route('admin.bookings.index') }}?booking={{ $booking->id }}"
                                               class="inline-flex items-center px-3 py-1 text-sm bg-pink-600 text-white rounded hover:bg-pink-700">
                                                查看詳情
                                            </a>
                                        </div>
                                    </div>
                                </div>
                            @endforeach
                        </div>
                    @else
                        <div class="text-center py-12">
                            <svg class="mx-auto h-12 w-12 text-gray-400" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M8 7V3m8 4V3m-9 8h10M5 21h14a2 2 0 002-2V7a2 2 0 00-2-2H5a2 2 0 00-2 2v12a2 2 0 002 2z"/>
                            </svg>
                            <h3 class="mt-2 text-sm font-medium text-gray-900">當日無預約</h3>
                            <p class="mt-1 text-sm text-gray-500">今天沒有任何預約排程</p>
                        </div>
                    @endif
                </div>
            </div>

        @elseif($viewMode === 'week')
            <!-- Week View -->
            <div class="grid grid-cols-1 lg:grid-cols-7 gap-4">
                @php
                    $weekStart = \Carbon\Carbon::parse($currentWeekStart);
                    $days = [];
                    for ($i = 0; $i < 7; $i++) {
                        $days[] = $weekStart->copy()->addDays($i);
                    }
                @endphp

                @foreach($days as $day)
                    @php
                        $dayStr = $day->format('Y-m-d');
                        $dayBookings = $bookings[$dayStr] ?? collect();
                        $isToday = $day->isToday();
                        $isWeekend = $day->isWeekend();
                    @endphp
                    
                    <div class="bg-white rounded-lg shadow border border-gray-300 {{ $isToday ? 'ring-2 ring-pink-500' : '' }}">
                        <div class="p-4 border-b border-gray-200 {{ $isToday ? 'bg-pink-50' : ($isWeekend ? 'bg-blue-50' : 'bg-gray-50') }}">
                            <div class="text-center">
                                <div class="text-lg font-semibold {{ $isToday ? 'text-pink-700' : 'text-gray-900' }}">
                                    {{ $day->format('d') }}
                                </div>
                                <div class="text-xs {{ $isToday ? 'text-pink-600' : 'text-gray-500' }}">
                                    {{ $day->locale('zh_TW')->dayName }}
                                </div>
                                @if($isToday)
                                    <div class="text-xs text-pink-600 font-medium">今天</div>
                                @endif
                            </div>
                        </div>
                        
                        <div class="p-3">
                            @if($dayBookings->count() > 0)
                                <div class="space-y-2">
                                    @foreach($dayBookings->take(3) as $booking)
                                        <button wire:click="selectDate('{{ $dayStr }}')"
                                                class="w-full text-left p-2 text-xs bg-gray-50 hover:bg-gray-100 rounded border">
                                            <div class="font-medium text-gray-900">
                                                {{ $booking->booking_time->format('H:i') }}
                                            </div>
                                            <div class="text-gray-600 truncate">
                                                {{ $booking->customer_name }}
                                            </div>
                                        </button>
                                    @endforeach
                                    @if($dayBookings->count() > 3)
                                        <button wire:click="selectDate('{{ $dayStr }}')"
                                                class="w-full text-center p-2 text-xs text-pink-600 hover:text-pink-800">
                                            還有 {{ $dayBookings->count() - 3 }} 個...
                                        </button>
                                    @endif
                                </div>
                            @else
                                <div class="text-center py-4 text-xs text-gray-400">
                                    無預約
                                </div>
                            @endif
                        </div>
                    </div>
                @endforeach
            </div>

        @else
            <!-- Month View -->
            <div class="bg-white rounded-lg shadow border border-gray-300 overflow-hidden">
                <div class="p-6">
                    <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-3 gap-6">
                        @foreach($bookings as $date => $dayBookings)
                            @php
                                $dateObj = \Carbon\Carbon::parse($date);
                                $isToday = $dateObj->isToday();
                                $isWeekend = $dateObj->isWeekend();
                            @endphp
                            
                            <div class="border border-gray-300 rounded-lg {{ $isToday ? 'ring-2 ring-pink-500' : '' }}">
                                <div class="p-4 border-b border-gray-200 {{ $isToday ? 'bg-pink-50' : ($isWeekend ? 'bg-blue-50' : 'bg-gray-50') }}">
                                    <div class="flex items-center justify-between">
                                        <div>
                                            <div class="font-semibold {{ $isToday ? 'text-pink-700' : 'text-gray-900' }}">
                                                {{ $dateObj->format('m月d日') }}
                                            </div>
                                            <div class="text-sm {{ $isToday ? 'text-pink-600' : 'text-gray-500' }}">
                                                {{ $dateObj->locale('zh_TW')->dayName }}
                                            </div>
                                        </div>
                                        <div class="text-sm font-medium text-gray-600">
                                            {{ $dayBookings->count() }}個預約
                                        </div>
                                    </div>
                                </div>
                                
                                <div class="p-4">
                                    @if($dayBookings->count() > 0)
                                        <div class="space-y-2">
                                            @foreach($dayBookings->take(4) as $booking)
                                                <div class="text-sm p-2 bg-gray-50 rounded">
                                                    <div class="font-medium text-gray-900">
                                                        {{ $booking->booking_time->format('H:i') }} - {{ $booking->customer_name }}
                                                    </div>
                                                    <div class="text-gray-600">
                                                        {{ $booking->style_type_text }}
                                                    </div>
                                                </div>
                                            @endforeach
                                            @if($dayBookings->count() > 4)
                                                <button wire:click="selectDate('{{ $date }}')"
                                                        class="w-full text-center p-2 text-sm text-pink-600 hover:text-pink-800">
                                                    查看全部 {{ $dayBookings->count() }} 個預約
                                                </button>
                                            @endif
                                        </div>
                                    @else
                                        <div class="text-center py-4 text-sm text-gray-400">
                                            無預約
                                        </div>
                                    @endif
                                </div>
                            </div>
                        @endforeach
                    </div>

                    @if($bookings->count() === 0)
                        <div class="text-center py-12">
                            <svg class="mx-auto h-12 w-12 text-gray-400" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M8 7V3m8 4V3m-9 8h10M5 21h14a2 2 0 002-2V7a2 2 0 00-2-2H5a2 2 0 00-2 2v12a2 2 0 002 2z"/>
                            </svg>
                            <h3 class="mt-2 text-sm font-medium text-gray-900">當月無預約</h3>
                            <p class="mt-1 text-sm text-gray-500">這個月沒有任何預約記錄</p>
                        </div>
                    @endif
                </div>
            </div>
        @endif
    </div>
</div>