<div class="min-h-screen bg-gray-50">
    <!-- Hero Section -->
    <div class="bg-gradient-to-r from-pink-500 to-purple-600 text-white">
        <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8 py-12">
            <div class="text-center md:text-left">
                <h1 class="text-3xl md:text-4xl font-bold mb-4">
                    歡迎回來，{{ $user->name }}！
                </h1>
                <p class="text-xl opacity-90 mb-6">
                    讓我們為您打造完美的指甲藝術
                </p>
            </div>
        </div>
    </div>

    <!-- Main Content -->
    <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8 py-8">
        <!-- Quick Actions -->
        <div class="grid grid-cols-1 md:grid-cols-2 gap-6 mb-8">
            <!-- Book Now Card -->
            <div class="bg-white rounded-xl shadow-lg hover:shadow-xl transition-shadow duration-300 overflow-hidden border border-gray-300">
                <div class="p-6">
                    <div class="flex items-center justify-between mb-4">
                        <h3 class="text-xl font-bold text-gray-900">立即預約</h3>
                        <div class="p-3 bg-pink-100 rounded-full">
                            <svg class="w-6 h-6 text-pink-600" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M8 7V3m8 4V3m-9 8h10M5 21h14a2 2 0 002-2V7a2 2 0 00-2-2H5a2 2 0 00-2 2v12a2 2 0 002 2z"/>
                            </svg>
                        </div>
                    </div>
                    <p class="text-gray-600 mb-4">預約您的美甲服務，讓專業技師為您服務</p>
                    
                    @if($monthlyBookingCount < $monthlyBookingLimit)
                        <a href="{{ route('booking.create') }}" 
                           class="inline-flex items-center px-4 py-2 bg-pink-600 hover:bg-pink-700 text-white font-medium rounded-lg transition-colors duration-200">
                            開始預約
                            <svg class="ml-2 w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 5l7 7-7 7"/>
                            </svg>
                        </a>
                    @else
                        <div class="text-amber-600 text-sm bg-amber-50 rounded-lg p-3">
                            本月預約次數已達上限，請下個月再試
                        </div>
                    @endif
                </div>
            </div>

            <!-- Booking Management Card -->
            <div class="bg-white rounded-xl shadow-lg hover:shadow-xl transition-shadow duration-300 overflow-hidden border border-gray-300">
                <div class="p-6">
                    <div class="flex items-center justify-between mb-4">
                        <h3 class="text-xl font-bold text-gray-900">預約管理</h3>
                        <div class="p-3 bg-purple-100 rounded-full">
                            <svg class="w-6 h-6 text-purple-600" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12h6m-6 4h6m2 5H7a2 2 0 01-2-2V5a2 2 0 012-2h5.586a1 1 0 01.707.293l5.414 5.414a1 1 0 01.293.707V19a2 2 0 01-2 2z"/>
                            </svg>
                        </div>
                    </div>
                    <p class="text-gray-600 mb-4">查看、修改或取消您的預約</p>
                    <a href="{{ route('booking.index') }}" 
                       class="inline-flex items-center px-4 py-2 bg-purple-600 hover:bg-purple-700 text-white font-medium rounded-lg transition-colors duration-200">
                        查看預約
                        <svg class="ml-2 w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 5l7 7-7 7"/>
                        </svg>
                    </a>
                </div>
            </div>
        </div>

        <!-- Recent Bookings & Available Times -->
        <div class="grid grid-cols-1 lg:grid-cols-2 gap-8">
            <!-- Recent Bookings -->
            <div class="bg-white rounded-xl shadow-lg overflow-hidden border border-gray-300">
                <div class="p-6 border-b border-gray-200">
                    <h3 class="text-lg font-bold text-gray-900">最近預約</h3>
                </div>
                <div class="p-6">
                    @if($recentBookings->count() > 0)
                        <div class="space-y-4">
                            @foreach($recentBookings as $booking)
                                <div class="flex items-center justify-between p-4 bg-gray-50 rounded-lg">
                                    <div class="flex-1">
                                        <div class="font-medium text-gray-900">{{ $booking->booking_number }}</div>
                                        <div class="text-sm text-gray-600">{{ $booking->booking_time->format('Y/m/d H:i') }}</div>
                                        <div class="text-sm text-gray-500">{{ $booking->style_type === 'single' ? '單色' : '造型' }}</div>
                                    </div>
                                    <div class="flex-shrink-0">
                                        @switch($booking->status)
                                            @case('pending')
                                                <span class="inline-flex px-2 py-1 text-xs font-semibold rounded-full bg-yellow-100 text-yellow-800">
                                                    待審核
                                                </span>
                                                @break
                                            @case('confirmed')
                                                <span class="inline-flex px-2 py-1 text-xs font-semibold rounded-full bg-green-100 text-green-800">
                                                    預約成功
                                                </span>
                                                @break
                                            @case('cancelled')
                                                <span class="inline-flex px-2 py-1 text-xs font-semibold rounded-full bg-red-100 text-red-800">
                                                    已取消
                                                </span>
                                                @break
                                            @case('completed')
                                                <span class="inline-flex px-2 py-1 text-xs font-semibold rounded-full bg-blue-100 text-blue-800">
                                                    已完成
                                                </span>
                                                @break
                                        @endswitch
                                    </div>
                                </div>
                            @endforeach
                        </div>
                    @else
                        <div class="text-center py-8">
                            <svg class="mx-auto h-12 w-12 text-gray-400" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12h6m-6 4h6m2 5H7a2 2 0 01-2-2V5a2 2 0 012-2h5.586a1 1 0 01.707.293l5.414 5.414a1 1 0 01.293.707V19a2 2 0 01-2 2z"/>
                            </svg>
                            <h3 class="mt-2 text-sm font-medium text-gray-900">尚無預約紀錄</h3>
                            <p class="mt-1 text-sm text-gray-500">開始您的第一次預約吧！</p>
                        </div>
                    @endif
                </div>
            </div>

            <!-- Available Times -->
            <div class="bg-white rounded-xl shadow-lg overflow-hidden border border-gray-300">
                <div class="p-6 border-b border-gray-200">
                    <h3 class="text-lg font-bold text-gray-900">近期可預約時段</h3>
                </div>
                <div class="p-6">
                    @if($availableTimes->count() > 0)
                        <div class="grid grid-cols-1 sm:grid-cols-2 gap-3">
                            @foreach($availableTimes as $time)
                                <div class="p-3 bg-green-50 border border-green-200 rounded-lg text-center">
                                    <div class="font-medium text-green-800">
                                        {{ $time->available_time->format('m/d') }}
                                    </div>
                                    <div class="text-sm text-green-600">
                                        {{ $time->available_time->format('H:i') }}
                                    </div>
                                    <div class="text-xs text-green-500 mt-1">
                                        {{ $time->available_time->format('l') }}
                                    </div>
                                </div>
                            @endforeach
                        </div>
                        <div class="mt-4 text-center">
                            <a href="{{ route('booking.create') }}" 
                               class="text-pink-600 hover:text-pink-800 text-sm font-medium">
                                查看更多時段 →
                            </a>
                        </div>
                    @else
                        <div class="text-center py-8">
                            <svg class="mx-auto h-12 w-12 text-gray-400" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 8v4l3 3m6-3a9 9 0 11-18 0 9 9 0 0118 0z"/>
                            </svg>
                            <h3 class="mt-2 text-sm font-medium text-gray-900">暫無可預約時段</h3>
                            <p class="mt-1 text-sm text-gray-500">請稍後再查看</p>
                        </div>
                    @endif
                </div>
            </div>
        </div>

        <!-- Quick Tips -->
        <div class="mt-8 bg-blue-50 border border-blue-200 rounded-xl p-6">
            <h3 class="text-lg font-semibold text-blue-900 mb-4">預約小提醒</h3>
            <div class="grid grid-cols-1 md:grid-cols-3 gap-4">
                <div class="flex items-start">
                    <div class="flex-shrink-0">
                        <div class="flex items-center justify-center h-8 w-8 rounded-full bg-blue-500 text-white text-sm font-bold">1</div>
                    </div>
                    <div class="ml-3">
                        <p class="text-sm text-blue-800">需要提前三天預約</p>
                    </div>
                </div>
                <div class="flex items-start">
                    <div class="flex-shrink-0">
                        <div class="flex items-center justify-center h-8 w-8 rounded-full bg-blue-500 text-white text-sm font-bold">2</div>
                    </div>
                    <div class="ml-3">
                        <p class="text-sm text-blue-800">每月最多預約三次</p>
                    </div>
                </div>
                <div class="flex items-start">
                    <div class="flex-shrink-0">
                        <div class="flex items-center justify-center h-8 w-8 rounded-full bg-blue-500 text-white text-sm font-bold">3</div>
                    </div>
                    <div class="ml-3">
                        <p class="text-sm text-blue-800">預約前一天需確認行程</p>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>