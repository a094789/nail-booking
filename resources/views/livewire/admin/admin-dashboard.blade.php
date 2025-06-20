{{-- resources/views/livewire/admin/admin-dashboard.blade.php --}}

<div class="min-h-screen bg-gray-50">
    <!-- Header -->
    <div class="bg-white border-b border-gray-200">
        <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8 py-6">
            <div class="flex flex-col sm:flex-row sm:items-center sm:justify-between">
                <div>
                    <h1 class="text-3xl font-bold text-gray-900">管理員後台</h1>
                    <p class="mt-1 text-sm text-gray-600">歡迎使用美甲預約系統管理後台</p>
                </div>
            </div>
        </div>
    </div>

    <!-- Main Content -->
    <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8 py-8">
        <!-- Quick Stats -->
        <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-4 gap-6 mb-8">
            <!-- 今日預約 -->
            <div class="bg-white rounded-lg shadow p-6">
                <div class="flex items-center">
                    <div class="flex-shrink-0">
                        <div class="w-10 h-10 bg-blue-100 rounded-lg flex items-center justify-center">
                            <svg class="w-6 h-6 text-blue-600" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M8 7V3m8 4V3m-9 8h10M5 21h14a2 2 0 002-2V7a2 2 0 00-2-2H5a2 2 0 00-2 2v12a2 2 0 002 2z"/>
                            </svg>
                        </div>
                    </div>
                    <div class="ml-4">
                        <p class="text-sm font-medium text-gray-500">今日預約</p>
                        <p class="text-2xl font-semibold text-gray-900">{{ $this->todayBookings }}</p>
                    </div>
                </div>
            </div>

            <!-- 待審核 -->
            <div class="bg-white rounded-lg shadow p-6">
                <div class="flex items-center">
                    <div class="flex-shrink-0">
                        <div class="w-10 h-10 bg-yellow-100 rounded-lg flex items-center justify-center">
                            <svg class="w-6 h-6 text-yellow-600" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 8v4l3 3m6-3a9 9 0 11-18 0 9 9 0 0118 0z"/>
                            </svg>
                        </div>
                    </div>
                    <div class="ml-4">
                        <p class="text-sm font-medium text-gray-500">待審核</p>
                        <p class="text-2xl font-semibold text-gray-900">{{ $this->pendingBookings }}</p>
                    </div>
                </div>
            </div>

            <!-- 本月預約 -->
            <div class="bg-white rounded-lg shadow p-6">
                <div class="flex items-center">
                    <div class="flex-shrink-0">
                        <div class="w-10 h-10 bg-green-100 rounded-lg flex items-center justify-center">
                            <svg class="w-6 h-6 text-green-600" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12l2 2 4-4m6 2a9 9 0 11-18 0 9 9 0 0118 0z"/>
                            </svg>
                        </div>
                    </div>
                    <div class="ml-4">
                        <p class="text-sm font-medium text-gray-500">本月預約</p>
                        <p class="text-2xl font-semibold text-gray-900">{{ $this->monthlyBookings }}</p>
                    </div>
                </div>
            </div>

            <!-- 總用戶數 -->
            <div class="bg-white rounded-lg shadow p-6">
                <div class="flex items-center">
                    <div class="flex-shrink-0">
                        <div class="w-10 h-10 bg-purple-100 rounded-lg flex items-center justify-center">
                            <svg class="w-6 h-6 text-purple-600" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M17 20h5v-2a3 3 0 00-5.356-1.857M17 20H7m10 0v-2c0-.656-.126-1.283-.356-1.857M7 20H2v-2a3 3 0 015.356-1.857M7 20v-2c0-.656.126-1.283.356-1.857m0 0a5.002 5.002 0 019.288 0M15 7a3 3 0 11-6 0 3 3 0 016 0zm6 3a2 2 0 11-4 0 2 2 0 014 0zM7 10a2 2 0 11-4 0 2 2 0 014 0z" />
                            </svg>
                        </div>
                    </div>
                    <div class="ml-4">
                        <p class="text-sm font-medium text-gray-500">註冊用戶</p>
                        <p class="text-2xl font-semibold text-gray-900">{{ $this->totalUsers }}</p>
                    </div>
                </div>
            </div>
        </div>

        <!-- Quick Actions -->
        <div class="grid grid-cols-1 lg:grid-cols-2 gap-6 mb-8">
            <!-- 快速操作 -->
            <div class="bg-white rounded-lg shadow p-6">
                <h3 class="text-lg font-medium text-gray-900 mb-4">快速操作</h3>
                <div class="space-y-3">
                    <a href="{{ route('admin.bookings.index') }}" 
                       class="flex items-center p-3 rounded-lg hover:bg-gray-50 transition-colors">
                        <div class="w-8 h-8 bg-blue-100 rounded-lg flex items-center justify-center mr-3">
                            <svg class="w-5 h-5 text-blue-600" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 5H7a2 2 0 00-2 2v10a2 2 0 002 2h8a2 2 0 002-2V7a2 2 0 00-2-2h-2M9 5a2 2 0 002 2h2a2 2 0 002-2M9 5a2 2 0 012-2h2a2 2 0 012 2"/>
                            </svg>
                        </div>
                        <div>
                            <p class="font-medium text-gray-900">預約管理</p>
                            <p class="text-sm text-gray-500">查看和管理所有預約</p>
                        </div>
                    </a>

                    <a href="{{ route('admin.available-times.index') }}" 
                       class="flex items-center p-3 rounded-lg hover:bg-gray-50 transition-colors">
                        <div class="w-8 h-8 bg-green-100 rounded-lg flex items-center justify-center mr-3">
                            <svg class="w-5 h-5 text-green-600" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 8v4l3 3m6-3a9 9 0 11-18 0 9 9 0 0118 0z"/>
                            </svg>
                        </div>
                        <div>
                            <p class="font-medium text-gray-900">時段管理</p>
                            <p class="text-sm text-gray-500">設定可預約的時段</p>
                        </div>
                    </a>

                    <a href="{{ route('admin.bookings.daily') }}" 
                       class="flex items-center p-3 rounded-lg hover:bg-gray-50 transition-colors">
                        <div class="w-8 h-8 bg-purple-100 rounded-lg flex items-center justify-center mr-3">
                            <svg class="w-5 h-5 text-purple-600" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M8 7V3m8 4V3m-9 8h10M5 21h14a2 2 0 002-2V7a2 2 0 00-2-2H5a2 2 0 00-2 2v12a2 2 0 002 2z"/>
                            </svg>
                        </div>
                        <div>
                            <p class="font-medium text-gray-900">每日清單</p>
                            <p class="text-sm text-gray-500">查看每日預約排程</p>
                        </div>
                    </a>
                </div>
            </div>

            <!-- 最近預約 -->
            <div class="bg-white rounded-lg shadow p-6">
                <h3 class="text-lg font-medium text-gray-900 mb-4">最近預約</h3>
                <div class="space-y-3">
                    @forelse($this->recentBookings as $booking)
                    <div class="flex items-center justify-between p-3 rounded-lg bg-gray-50">
                        <div class="flex-1">
                            <p class="font-medium text-gray-900">{{ $booking->customer_name }}</p>
                            <p class="text-sm text-gray-500">{{ $booking->booking_time->format('m/d H:i') }}</p>
                        </div>
                        <span class="inline-flex px-2 py-1 text-xs font-semibold rounded-full 
                            @switch($booking->status)
                                @case('pending') bg-yellow-100 text-yellow-800 @break
                                @case('approved') bg-green-100 text-green-800 @break
                                @case('cancelled') bg-red-100 text-red-800 @break
                                @case('completed') bg-blue-100 text-blue-800 @break
                            @endswitch">
                            {{ $booking->status_text }}
                        </span>
                    </div>
                    @empty
                    <p class="text-gray-500 text-center py-4">暫無預約記錄</p>
                    @endforelse
                </div>
            </div>
        </div>

        <!-- 系統狀態 -->
        <div class="bg-white rounded-lg shadow p-6">
            <h3 class="text-lg font-medium text-gray-900 mb-4">系統狀態</h3>
            <div class="grid grid-cols-1 md:grid-cols-3 gap-4">
                <div class="text-center">
                    <div class="text-2xl font-bold text-green-600">{{ $this->availableSlots }}</div>
                    <p class="text-sm text-gray-500">可預約時段</p>
                </div>
                <div class="text-center">
                    <div class="text-2xl font-bold text-blue-600">{{ $this->activeBookings }}</div>
                    <p class="text-sm text-gray-500">進行中預約</p>
                </div>
                <div class="text-center">
                    <div class="text-2xl font-bold text-purple-600">{{ $this->completedThisMonth }}</div>
                    <p class="text-sm text-gray-500">本月已完成</p>
                </div>
            </div>
        </div>
    </div>
</div>